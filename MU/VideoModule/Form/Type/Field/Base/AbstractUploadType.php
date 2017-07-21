<?php
/**
 * Video.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\VideoModule\Form\Type\Field\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Zikula\Common\Translator\TranslatorInterface;
use MU\VideoModule\Form\DataTransformer\UploadFileTransformer;
use MU\VideoModule\Helper\ImageHelper;
use MU\VideoModule\Helper\UploadHelper;

/**
 * Upload field type base class.
 */
abstract class AbstractUploadType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestStack
     */
    protected $requestStack = '';

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var UploadHelper
     */
    protected $uploadHelper = '';

    /**
     * @var FormBuilderInterface
     */
    protected $formBuilder = null;

    /**
     * @var object
     */
    protected $entity = null;

    /**
     * UploadTypeExtension constructor.
     *
     * @param TranslatorInterface $translator   Translator service instance
     * @param RequestStack        $requestStack RequestStack service instance
     * @param ImageHelper         $imageHelper  ImageHelper service instance
     * @param UploadHelper        $uploadHelper UploadHelper service instance
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, ImageHelper $imageHelper, UploadHelper $uploadHelper)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->imageHelper = $imageHelper;
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['compound'] = false;
        $fieldName = $builder->getName();

        $this->entity = $options['entity'];
        $this->formBuilder = $builder;

        $fileOptions = [];
        foreach ($options as $optionName => $optionValue) {
            if (in_array($optionName, ['entity', 'allowed_extensions', 'allowed_size'])) {
                continue;
            }
            $fileOptions[$optionName] = $optionValue;
        }
        $fileOptions['attr']['class'] = 'validate-upload';

        $builder->add($fieldName, FileType::class, $fileOptions);
        $uploadFileTransformer = new UploadFileTransformer($this, $this->requestStack, $this->uploadHelper, $fieldName);
        $builder->get($fieldName)->addModelTransformer($uploadFileTransformer);

        if ($options['required']) {
            return;
        }

        $builder->add($fieldName . 'DeleteFile', CheckboxType::class, [
            'mapped' => false,
            'label' => $this->translator->__('Delete existing file'),
            'required' => false,
            'attr' => [
                'title' => $this->translator->__('Delete this file ?')
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $fieldName = $form->getConfig()->getName();

        $view->vars['object_type'] = $this->entity->get_objectType();
        $view->vars['field_name'] = $fieldName;
        $view->vars['edited_entity'] = $this->entity;

        $parentData = $form->getParent()->getData();
        $accessor = PropertyAccess::createPropertyAccessor();
        $fieldNameGetter = 'get' . ucfirst($fieldName);

        // assign basic file properties
        $file = null !== $parentData ? $accessor->getValue($parentData, $fieldNameGetter) : null;
        if (null !== $file && is_array($file)) {
            $file = $file[$fieldName];
        }
        if (null !== $file && is_string($file)) {
            if (false === strpos($file, '/')) {
                $file = $this->uploadHelper->getFileBaseFolder($this->entity->get_objectType(), $fieldName) . $file;
            }
            $file = new File($file);
        }
        $hasFile = null !== $file;
        $fileMeta = $hasFile ? $accessor->getValue($parentData, $fieldNameGetter . 'Meta') : [];
        if (!isset($fileMeta['isImage'])) {
            $fileMeta['isImage'] = false;
        }
        if (!isset($fileMeta['size'])) {
            $fileMeta['size'] = 0;
        }
        $view->vars['file_meta'] = $fileMeta;
        $view->vars['file_path'] = $hasFile ? $file->getPathname() : null;
        $view->vars['file_url'] = $hasFile ? $accessor->getValue($parentData, $fieldNameGetter . 'Url') : null;

        // assign other custom options
        $view->vars['allowed_extensions'] = array_key_exists('allowed_extensions', $options) ? $options['allowed_extensions'] : '';
        $view->vars['allowed_size'] = array_key_exists('allowed_size', $options) ? $options['allowed_size'] : 0;
        $view->vars['thumb_runtime_options'] = null;

        if (true === $fileMeta['isImage']) {
            $view->vars['thumb_runtime_options'] = $this->imageHelper->getRuntimeOptions($this->entity->get_objectType(), $fieldName, 'controllerAction', ['action' => 'edit']);
        }
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['entity'])
            ->setOptional(['allowed_extensions', 'allowed_size'])
            ->setDefaults([
                'attr' => [
                    'class' => 'file-selector'
                ],
                'allowed_extensions' => '',
                'allowed_size' => '',
                'error_bubbling' => false
            ])
            ->setAllowedTypes('allowed_extensions', 'string')
            ->setAllowedTypes('allowed_size', 'string')
        ;
    }

    /**
     * Returns the form builder.
     *
     * @return FormBuilderInterface
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }
    
    /**
     * Returns the entity.
     *
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'muvideomodule_field_upload';
    }
}
