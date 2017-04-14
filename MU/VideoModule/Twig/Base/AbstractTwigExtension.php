<?php
/**
 * Video.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace MU\VideoModule\Twig\Base;

use Twig_Extension;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;
use MU\VideoModule\Helper\ListEntriesHelper;
use MU\VideoModule\Helper\WorkflowHelper;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends Twig_Extension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApi
     */
    protected $variableApi;
    
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    /**
     * TwigExtension constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param VariableApi         $variableApi    VariableApi service instance
     * @param UserRepositoryInterface $userRepository UserRepository service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     * @param ListEntriesHelper   $listHelper     ListEntriesHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        VariableApi $variableApi,
        UserRepositoryInterface $userRepository,
        WorkflowHelper $workflowHelper,
        ListEntriesHelper $listHelper)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->userRepository = $userRepository;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
    }
    
    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * Returns a list of custom Twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('muvideomodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new \Twig_SimpleFunction('muvideomodule_templateSelector', [$this, 'getTemplateSelector']),
            new \Twig_SimpleFunction('muvideomodule_userAvatar', [$this, 'getUserAvatar'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * Returns a list of custom Twig filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('muvideomodule_fileSize', [$this, 'getFileSize'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('muvideomodule_listEntry', [$this, 'getListEntry']),
            new \Twig_SimpleFilter('muvideomodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * The muvideomodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|muvideomodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|muvideomodule_objectState(false) }} {# no ui feedback #}
     *
     * @param string  $state      Name of given workflow state
     * @param boolean $uiFeedback Whether the output should include some visual feedback about the state
     *
     * @return string Enriched and translated workflow state ready for display
     */
    public function getObjectState($state = 'initial', $uiFeedback = true)
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if (true === $uiFeedback) {
            $result = '<span class="label label-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    
    /**
     * The muvideomodule_fileSize filter displays the size of a given file in a readable way.
     * Example:
     *     {{ 12345|muvideomodule_fileSize }}
     *
     * @param integer $size     File size in bytes
     * @param string  $filepath The input file path including file name (if file size is not known)
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    public function getFileSize($size = 0, $filepath = '', $nodesc = false, $onlydesc = false)
    {
        if (!is_numeric($size)) {
            $size = (int) $size;
        }
        if (!$size) {
            if (empty($filepath) || !file_exists($filepath)) {
                return '';
            }
            $size = filesize($filepath);
        }
        if (!$size) {
            return '';
        }
    
        return $this->getReadableFileSize($size, $nodesc, $onlydesc);
    }
    
    /**
     * Display a given file size in a readable format
     *
     * @param string  $size     File size in bytes
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    private function getReadableFileSize($size, $nodesc = false, $onlydesc = false)
    {
        $sizeDesc = $this->__('Bytes');
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('KB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('MB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('GB');
        }
        $sizeDesc = '&nbsp;' . $sizeDesc;
    
        // format number
        $dec_point = ',';
        $thousands_separator = '.';
        if ($size - number_format($size, 0) >= 0.005) {
            $size = number_format($size, 2, $dec_point, $thousands_separator);
        } else {
            $size = number_format($size, 0, '', $thousands_separator);
        }
    
        // append size descriptor if desired
        if (!$nodesc) {
            $size .= $sizeDesc;
        }
    
        // return either only the description or the complete string
        return $onlydesc ? $sizeDesc : $size;
    }
    
    
    /**
     * The muvideomodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|muvideomodule_listEntry('entityName', 'fieldName') }}
     *
     * @param string $value      The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     * @param string $delimiter  String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function getListEntry($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && $value != '0') || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    /**
     * The muvideomodule_objectTypeSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getObjectTypeSelector()
    {
        $result = [];
    
        $result[] = [
            'text' => $this->__('Collections'),
            'value' => 'collection'
        ];
        $result[] = [
            'text' => $this->__('Movies'),
            'value' => 'movie'
        ];
        $result[] = [
            'text' => $this->__('Playlists'),
            'value' => 'playlist'
        ];
    
        return $result;
    }
    
    
    /**
     * The muvideomodule_templateSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getTemplateSelector()
    {
        $result = [];
    
        $result[] = [
            'text' => $this->__('Only item titles'),
            'value' => 'itemlist_display.html.twig'
        ];
        $result[] = [
            'text' => $this->__('With description'),
            'value' => 'itemlist_display_description.html.twig'
        ];
        $result[] = [
            'text' => $this->__('Custom template'),
            'value' => 'custom'
        ];
    
        return $result;
    }
    
    /**
     * Display the avatar of a user.
     *
     * @param int|string $uid    The user's id or name
     * @param int        $width  Image width (optional)
     * @param int        $height Image height (optional)
     * @param int        $size   Gravatar size (optional)
     * @param string     $rating Gravatar self-rating [g|pg|r|x] see: http://en.gravatar.com/site/implement/images/ (optional)
     *
     * @return string
     */
    public function getUserAvatar($uid = 0, $width = 0, $height = 0, $size = 0, $rating = '')
    {
        if (!is_numeric($uid)) {
            $limit = 1;
            $filter = [
                'uname' => ['operator' => '=', 'operand' => $uid]
            ];
            $results = $this->userRepository->query($filter, [], $limit);
            if (!count($results)) {
                return '';
            }
    
            $uid = $results->getIterator()->getArrayCopy()[0]->getUname();
        }
        $params = ['uid' => $uid];
        if ($width > 0) {
            $params['width'] = $width;
        }
        if ($height > 0) {
            $params['height'] = $height;
        }
        if ($size > 0) {
            $params['size'] = $size;
        }
        if ($rating != '') {
            $params['rating'] = $rating;
        }
    
        include_once 'lib/legacy/viewplugins/function.useravatar.php';
    
        $view = \Zikula_View::getInstance('MUVideoModule');
        $result = smarty_function_useravatar($params, $view);
    
        return $result;
    }
}
