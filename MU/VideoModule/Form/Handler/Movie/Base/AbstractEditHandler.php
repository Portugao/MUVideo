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

namespace MU\VideoModule\Form\Handler\Movie\Base;

use MU\VideoModule\Form\Handler\Common\EditHandler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use RuntimeException;
use MU\VideoModule\Helper\FeatureActivationHelper;

/**
 * This handler class handles the page events of editing forms.
 * It aims on the movie object type.
 */
abstract class AbstractEditHandler extends EditHandler
{
    /**
     * Initialise form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @param array $templateParameters List of preassigned template variables
     *
     * @return boolean False in case of initialisation errors, otherwise true
     */
    public function processForm(array $templateParameters)
    {
        $this->objectType = 'movie';
        $this->objectTypeCapital = 'Movie';
        $this->objectTypeLower = 'movie';
        
        $this->hasPageLockSupport = true;
        $this->hasTranslatableFields = true;
    
        $result = parent::processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
    
        if ($this->templateParameters['mode'] == 'create') {
            if (!$this->modelHelper->canBeCreated($this->objectType)) {
                $this->request->getSession()->getFlashBag()->add('error', $this->__('Sorry, but you can not create the movie yet as other items are required which must be created before!'));
                $logArgs = ['app' => 'MUVideoModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => $this->objectType];
                $this->logger->notice('{app}: User {user} tried to create a new {entity}, but failed as it other items are required which must be created before.', $logArgs);
    
                return new RedirectResponse($this->getRedirectUrl(['commandName' => '']), 302);
            }
        }
    
        $entityData = $this->entityRef->toArray();
    
        // assign data to template as array (for additions like standard fields)
        $this->templateParameters[$this->objectTypeLower] = $entityData;
    
        return $result;
    }
    
    /**
     * Initialises relationship presets.
     */
    protected function initRelationPresets()
    {
        $entity = $this->entityRef;
    
        
        // assign identifiers of predefined incoming relationships
        // editable relation, we store the id and assign it now to show it in UI
        $this->relationPresets['collection'] = $this->request->get('collection', '');
        if (!empty($this->relationPresets['collection'])) {
            $relObj = $this->entityFactory->getRepository('collection')->selectById($this->relationPresets['collection']);
            if (null !== $relObj) {
                $relObj->addMovie($entity);
            }
        }
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    }
    
    /**
     * Creates the form type.
     */
    protected function createForm()
    {
        $options = [
            'entity' => $this->entityRef,
            'mode' => $this->templateParameters['mode'],
            'actions' => $this->templateParameters['actions'],
            'has_moderate_permission' => $this->permissionApi->hasPermission($this->permissionComponent, $this->createCompositeIdentifier() . '::', ACCESS_MODERATE),
            'filter_by_ownership' => !$this->permissionApi->hasPermission($this->permissionComponent, $this->createCompositeIdentifier() . '::', ACCESS_ADD)
        ];
    
        $options['translations'] = [];
        foreach ($this->templateParameters['supportedLanguages'] as $language) {
            $options['translations'][$language] = isset($this->templateParameters[$this->objectTypeLower . $language]) ? $this->templateParameters[$this->objectTypeLower . $language] : [];
        }
    
        return $this->formFactory->create('MU\VideoModule\Form\Type\MovieType', $this->entityRef, $options);
    }


    /**
     * Get list of allowed redirect codes.
     *
     * @return array list of possible redirect codes
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
    
        // user index page of movie area
        $codes[] = 'userIndex';
        // admin index page of movie area
        $codes[] = 'adminIndex';
        // user list of movies
        $codes[] = 'userView';
        // admin list of movies
        $codes[] = 'adminView';
        // user list of own movies
        $codes[] = 'userOwnView';
        // admin list of own movies
        $codes[] = 'adminOwnView';
        // user detail page of treated movie
        $codes[] = 'userDisplay';
        // admin detail page of treated movie
        $codes[] = 'adminDisplay';
    
        // user list of collections
        $codes[] = 'userViewCollections';
        // admin list of collections
        $codes[] = 'adminViewCollections';
        // user list of own collections
        $codes[] = 'userOwnViewCollections';
        // admin list of own collections
        $codes[] = 'adminOwnViewCollections';
        // user detail page of related collection
        $codes[] = 'userDisplayCollection';
        // admin detail page of related collection
        $codes[] = 'adminDisplayCollection';
    
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     *
     * @param array $args List of arguments
     *
     * @return string The default redirect url
     */
    protected function getDefaultReturnUrl($args)
    {
        $objectIsPersisted = $args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel');
    
        if (null !== $this->returnTo) {
            $isDisplayOrEditPage = substr($this->returnTo, -7) == 'display' || substr($this->returnTo, -4) == 'edit';
            if (!$isDisplayOrEditPage || $objectIsPersisted) {
                // return to referer
                return $this->returnTo;
            }
        }
    
        $routeArea = array_key_exists('routeArea', $this->templateParameters) ? $this->templateParameters['routeArea'] : '';
        $routePrefix = 'muvideomodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // redirect to the list of movies
        $url = $this->router->generate($routePrefix . 'view');
    
        return $url;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     *
     * @param array $args List of arguments
     *
     * @return mixed Redirect or false on errors
     */
    public function handleCommand($args = [])
    {
        $result = parent::handleCommand($args);
        if (false === $result) {
            return $result;
        }
    
        // build $args for BC (e.g. used by redirect handling)
        foreach ($this->templateParameters['actions'] as $action) {
            if ($this->form->get($action['id'])->isClicked()) {
                $args['commandName'] = $action['id'];
            }
        }
        if ($this->form->get('cancel')->isClicked()) {
            $args['commandName'] = 'cancel';
        }
    
        return new RedirectResponse($this->getRedirectUrl($args), 302);
    }
    
    /**
     * Get success or error message for default operations.
     *
     * @param array   $args    Arguments from handleCommand method
     * @param Boolean $success Becomes true if this is a success, false for default error
     *
     * @return String desired status or error message
     */
    protected function getDefaultMessage($args, $success = false)
    {
        if (false === $success) {
            return parent::getDefaultMessage($args, $success);
        }
    
        $message = '';
        switch ($args['commandName']) {
            case 'submit':
                if ($this->templateParameters['mode'] == 'create') {
                    $message = $this->__('Done! Movie created.');
                } else {
                    $message = $this->__('Done! Movie updated.');
                }
                break;
            case 'delete':
                $message = $this->__('Done! Movie deleted.');
                break;
            default:
                $message = $this->__('Done! Movie updated.');
                break;
        }
    
        return $message;
    }

    /**
     * This method executes a certain workflow action.
     *
     * @param array $args Arguments from handleCommand method
     *
     * @return bool Whether everything worked well or not
     *
     * @throws RuntimeException Thrown if concurrent editing is recognised or another error occurs
     */
    public function applyAction(array $args = [])
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $action = $args['commandName'];
    
        $success = false;
        $flashBag = $this->request->getSession()->getFlashBag();
        try {
            // execute the workflow action
            $success = $this->workflowHelper->executeAction($entity, $action);
        } catch(\Exception $e) {
            $flashBag->add('error', $this->__f('Sorry, but an error occured during the %action% action. Please apply the changes again!', ['%action%' => $action]) . ' ' . $e->getMessage());
            $logArgs = ['app' => 'MUVideoModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => 'movie', 'id' => $entity->createCompositeIdentifier(), 'errorMessage' => $e->getMessage()];
            $this->logger->error('{app}: User {user} tried to edit the {entity} with id {id}, but failed. Error details: {errorMessage}.', $logArgs);
        }
    
        $this->addDefaultMessage($args, $success);
    
        if ($success && $this->templateParameters['mode'] == 'create') {
            // store new identifier
            foreach ($this->idFields as $idField) {
                $this->idValues[$idField] = $entity[$idField];
            }
        }
    
        return $success;
    }

    /**
     * Get url to redirect to.
     *
     * @param array $args List of arguments
     *
     * @return string The redirect url
     */
    protected function getRedirectUrl($args)
    {
        if ($this->repeatCreateAction) {
            return $this->repeatReturnUrl;
        }
    
        if ($this->request->getSession()->has('muvideomoduleReferer')) {
            $this->request->getSession()->del('muvideomoduleReferer');
        }
    
        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args);
        }
    
        $routeArea = substr($this->returnTo, 0, 5) == 'admin' ? 'admin' : '';
        $routePrefix = 'muvideomodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'userIndex':
            case 'adminIndex':
                return $this->router->generate($routePrefix . 'index');
            case 'userView':
            case 'adminView':
                return $this->router->generate($routePrefix . 'view');
            case 'userOwnView':
            case 'adminOwnView':
                return $this->router->generate($routePrefix . 'view', [ 'own' => 1 ]);
            case 'userDisplay':
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->templateParameters['mode'] == 'create' && $args['commandName'] == 'cancel')) {
                    foreach ($this->idFields as $idField) {
                        $urlArgs[$idField] = $this->idValues[$idField];
                    }
    
                    return $this->router->generate($routePrefix . 'display', $urlArgs);
                }
    
                return $this->getDefaultReturnUrl($args);
            case 'userViewCollections':
            case 'adminViewCollections':
                return $this->router->generate('muvideomodule_collection_' . $routeArea . 'view');
            case 'userOwnViewCollections':
            case 'adminOwnViewCollections':
                return $this->router->generate('muvideomodule_collection_' . $routeArea . 'view', [ 'own' => 1 ]);
            case 'userDisplayCollection':
            case 'adminDisplayCollection':
                if (!empty($this->relationPresets['collection'])) {
                    return $this->router->generate('muvideomodule_collection_' . $routeArea . 'display',  ['id' => $this->relationPresets['collection']]);
                }
    
                return $this->getDefaultReturnUrl($args);
            default:
                return $this->getDefaultReturnUrl($args);
        }
    }
}
