<?php
/**
 * MUVideo.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUVideo
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de).
 */

/**
 * This handler class handles the page events of editing forms.
 * It collects common functionality required by different object types.
 *
 * Member variables in a form handler object are persisted across different page requests. This means
 * a member variable $this->X can be set on one request and on the next request it will still contain
 * the same value.
 *
 * A form handler will be notified of various events happening during it's life-cycle.
 * When a specific event occurs then the corresponding event handler (class method) will be executed. Handlers
 * are named exactly like their events - this is how the framework knows which methods to call.
 *
 * The list of events is:
 *
 * - <b>initialize</b>: this event fires before any of the events for the plugins and can be used to setup
 *   the form handler. The event handler typically takes care of reading URL variables, access control
 *   and reading of data from the database.
 *
 * - <b>handleCommand</b>: this event is fired by various plugins on the page. Typically it is done by the
 *   Zikula_Form_Plugin_Button plugin to signal that the user activated a button.
 */
class MUVideo_Form_Handler_Common_Base_Edit extends Zikula_Form_AbstractHandler
{
    /**
     * Name of treated object type.
     *
     * @var string
     */
    protected $objectType;

    /**
     * Name of treated object type starting with upper case.
     *
     * @var string
     */
    protected $objectTypeCapital;

    /**
     * Lower case version.
     *
     * @var string
     */
    protected $objectTypeLower;

    /**
     * Permission component based on object type.
     *
     * @var string
     */
    protected $permissionComponent;

    /**
     * Reference to treated entity instance.
     *
     * @var Zikula_EntityAccess
     */
    protected $entityRef = false;

    /**
     * List of identifier names.
     *
     * @var array
     */
    protected $idFields = array();

    /**
     * List of identifiers of treated entity.
     *
     * @var array
     */
    protected $idValues = array();
    
    /**
     * List of identifiers for predefined relationships.
     *
     * @var mixed
     */
    protected $relationPresets = array();

    /**
     * One of "create" or "edit".
     *
     * @var string
     */
    protected $mode;

    /**
     * Code defining the redirect goal after command handling.
     *
     * @var string
     */
    protected $returnTo = null;

    /**
     * Whether a create action is going to be repeated or not.
     *
     * @var boolean
     */
    protected $repeatCreateAction = false;

    /**
     * Url of current form with all parameters for multiple creations.
     *
     * @var string
     */
    protected $repeatReturnUrl = null;

    /**
     * Whether this form is being used inline within a window.
     *
     * @var boolean
     */
    protected $inlineUsage = false;

    /**
     * Full prefix for related items.
     *
     * @var string
     */
    protected $idPrefix = '';

    /**
     * Whether an existing item is used as template for a new one.
     *
     * @var boolean
     */
    protected $hasTemplateId = false;

    /**
     * Whether the PageLock extension is used for this entity type or not.
     *
     * @var boolean
     */
    protected $hasPageLockSupport = false;

    /**
     * Whether the entity is categorisable or not.
     *
     * @var boolean
     */
    protected $hasCategories = false;

    /**
     * Array with upload field names and mandatory flags.
     *
     * @var array
     */
    protected $uploadFields = array();

    /**
     * Array with list field names and multiple flags.
     *
     * @var array
     */
    protected $listFields = array();


    /**
     * Post construction hook.
     *
     * @return mixed
     */
    public function setup()
    {
    }

    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
    }

    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @param Zikula_Form_View $view The form view instance.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        $this->inlineUsage = ((UserUtil::getTheme() == 'Printer') ? true : false);
        $this->idPrefix = $this->request->query->filter('idp', '', FILTER_SANITIZE_STRING);
    
        // initialise redirect goal
        $this->returnTo = $this->request->query->filter('returnTo', null, FILTER_SANITIZE_STRING);
        // store current uri for repeated creations
        $this->repeatReturnUrl = System::getCurrentURI();
    
        $this->permissionComponent = $this->name . ':' . $this->objectTypeCapital . ':';
    
        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $this->idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $this->objectType));
    
        // retrieve identifier of the object we wish to view
        $controllerHelper = new MUVideo_Util_Controller($this->view->getServiceManager());
    
        $this->idValues = $controllerHelper->retrieveIdentifier($this->request, array(), $this->objectType, $this->idFields);
        $hasIdentifier = $controllerHelper->isValidIdentifier($this->idValues);
    
        $entity = null;
        $this->mode = ($hasIdentifier) ? 'edit' : 'create';
    
        if ($this->mode == 'edit') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, $this->createCompositeIdentifier() . '::', ACCESS_EDIT)) {
                return LogUtil::registerPermissionError();
            }
    
            $entity = $this->initEntityForEdit();
            if (!is_object($entity)) {
                return LogUtil::registerError($this->__('No such item.'));
            }
    
            if ($this->hasPageLockSupport === true && ModUtil::available('PageLock')) {
                // try to guarantee that only one person at a time can be editing this entity
                ModUtil::apiFunc('PageLock', 'user', 'pageLock',
                                         array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier(),
                                               'returnUrl' => $this->getRedirectUrl(null)));
            }
        } else {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_EDIT)) {
                return LogUtil::registerPermissionError();
            }
    
            $entity = $this->initEntityForCreation();
        }
    
        $this->view->assign('mode', $this->mode)
                   ->assign('inlineUsage', $this->inlineUsage);
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    
        
        if ($this->hasCategories === true) {
            $this->initCategoriesForEdit();
        }
    
        $workflowHelper = new MUVideo_Util_Workflow($this->view->getServiceManager());
        $actions = $workflowHelper->getActionsForObject($entity);
        if ($actions === false || !is_array($actions)) {
            return LogUtil::registerError($this->__('Error! Could not determine workflow actions.'));
        }
        // assign list of allowed actions to the view for further processing
        $this->view->assign('actions', $actions);
    
        // everything okay, no initialization errors occured
        return true;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers. 
     */
    protected function createCompositeIdentifier()
    {
        $itemId = '';
        foreach ($this->idFields as $idField) {
            if (!empty($itemId)) {
                $itemId .= '_';
            }
            $itemId .= $this->idValues[$idField];
        }
    
        return $itemId;
    }
    
    /**
     * Initialise existing entity for editing.
     *
     * @return Zikula_EntityAccess desired entity instance or null
     */
    protected function initEntityForEdit()
    {
        $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $this->objectType, 'id' => $this->idValues));
        if ($entity == null) {
            return LogUtil::registerError($this->__('No such item.'));
        }
    
        $entity->initWorkflow();
    
        return $entity;
    }
    
    /**
     * Initialise new entity for creation.
     *
     * @return Zikula_EntityAccess desired entity instance or null
     */
    protected function initEntityForCreation()
    {
        $this->hasTemplateId = false;
        $templateId = $this->request->query->get('astemplate', '');
        if (!empty($templateId)) {
            $templateIdValueParts = explode('_', $templateId);
            $this->hasTemplateId = (count($templateIdValueParts) == count($this->idFields));
        }
    
        if ($this->hasTemplateId === true) {
            $templateIdValues = array();
            $i = 0;
            foreach ($this->idFields as $idField) {
                $templateIdValues[$idField] = $templateIdValueParts[$i];
                $i++;
            }
            // reuse existing entity
            $entityT = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $this->objectType, 'id' => $templateIdValues));
            if ($entityT == null) {
                return LogUtil::registerError($this->__('No such item.'));
            }
            $entity = clone $entityT;
        } else {
            $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
            $entity = new $entityClass();
        }
    
        return $entity;
    }
    
    /**
     * Initialise categories.
     */
    protected function initCategoriesForEdit()
    {
        $entity = $this->entityRef;
    
        // assign the actual object for categories listener
        $this->view->assign($this->objectTypeLower . 'Obj', $entity);
    
        // load and assign registered categories
        $registries = ModUtil::apiFunc($this->name, 'category', 'getAllPropertiesWithMainCat', array('ot' => $this->objectType, 'arraykey' => $this->idFields[0]));
    
        // check if multiple selection is allowed for this object type
        $multiSelectionPerRegistry = array();
        foreach ($registries as $registryId => $registryCid) {
            $multiSelectionPerRegistry[$registryId] = ModUtil::apiFunc($this->name, 'category', 'hasMultipleSelection', array('ot' => $this->objectType, 'registry' => $registryId));
        }
        $this->view->assign('registries', $registries)
                   ->assign('multiSelectionPerRegistry', $multiSelectionPerRegistry);
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        $utilArgs = array('controller' => \FormUtil::getPassedValue('type', 'user', 'GETPOST'),
                          'action' => 'edit',
                          'mode' => $this->mode);
        $this->view->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
    }

    /**
     * Get list of allowed redirect codes.
     *
     * @return array list of possible redirect codes
     */
    protected function getRedirectCodes()
    {
        $codes = array();
        // main page of admin area
        $codes[] = 'admin';
        // admin list of entities
        $codes[] = 'adminView';
        // admin display page of treated entity
        $codes[] = 'adminDisplay';
        // main page of user area
        $codes[] = 'user';
        // user list of entities
        $codes[] = 'userView';
        // user display page of treated entity
        $codes[] = 'userDisplay';
        // main page of ajax area
        $codes[] = 'ajax';
    
        return $codes;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     *
     * @param Zikula_Form_View $view The form view instance.
     * @param array            $args Additional arguments.
     *
     * @see Zikula_Form_Plugin_Button
     * @see Zikula_Form_Plugin_ImageButton
     *
     * @return mixed Redirect or false on errors.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $action = $args['commandName'];
        $isRegularAction = !in_array($action, array('delete', 'cancel'));
    
        if ($isRegularAction) {
            // do forms validation including checking all validators on the page to validate their input
            if (!$this->view->isValid()) {
                return false;
            }
        }
    
        if ($action != 'cancel') {
            $otherFormData = $this->fetchInputData($view, $args);
            if ($otherFormData === false) {
                return false;
            }
        }
    
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $hookAreaPrefix = $entity->getHookAreaPrefix();
        if ($action != 'cancel') {
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
    
            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.' . $hookType, new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                return false;
            }
        }
    
        if ($action != 'cancel') {
            $success = $this->applyAction($args);
            if (!$success) {
                // the workflow operation failed
                return false;
            }
    
            // Let any hooks know that we have created, updated or deleted an item
            $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $url = new Zikula_ModUrl($this->name, FormUtil::getPassedValue('type', 'user', 'GETPOST'), 'display', ZLanguage::getLanguageCode(), $urlArgs);
            }
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.' . $hookType, $entity->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);
    
            // An item was created, updated or deleted, so we clear all cached pages for this item.
            $cacheArgs = array('ot' => $this->objectType, 'item' => $entity);
            ModUtil::apiFunc($this->name, 'cache', 'clearItemCache', $cacheArgs);
    
            // clear view cache to reflect our changes
            $this->view->clear_cache();
        }
    
        if ($this->hasPageLockSupport === true && $this->mode == 'edit' && ModUtil::available('PageLock')) {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                             array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier()));
        }
    
        return $this->view->redirect($this->getRedirectUrl($args));
    }
    
    /**
     * Get success or error message for default operations.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     * @return String desired status or error message.
     */
    protected function getDefaultMessage($args, $success = false)
    {
        $message = '';
        switch ($args['commandName']) {
            case 'create':
                    if ($success === true) {
                        $message = $this->__('Done! Item created.');
                    } else {
                        $message = $this->__('Error! Creation attempt failed.');
                    }
                    break;
            case 'update':
                    if ($success === true) {
                        $message = $this->__('Done! Item updated.');
                    } else {
                        $message = $this->__('Error! Update attempt failed.');
                    }
                    break;
            case 'delete':
                    if ($success === true) {
                        $message = $this->__('Done! Item deleted.');
                    } else {
                        $message = $this->__('Error! Deletion attempt failed.');
                    }
                    break;
        }
    
        return $message;
    }
    
    /**
     * Add success or error message to session.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     */
    protected function addDefaultMessage($args, $success = false)
    {
        $message = $this->getDefaultMessage($args, $success);
        if (!empty($message)) {
            if ($success === true) {
                LogUtil::registerStatus($message);
            } else {
                LogUtil::registerError($message);
            }
        }
    }

    /**
     * Input data processing called by handleCommand method.
     *
     * @param Zikula_Form_View $view The form view instance.
     * @param array            $args Additional arguments.
     *
     * @return array form data after processing.
     */
    public function fetchInputData(Zikula_Form_View $view, &$args)
    {
        // fetch posted data input values as an associative array
        $formData = $this->view->getValues();
        // we want the array with our field values
        $entityData = $formData[$this->objectTypeLower];
        unset($formData[$this->objectTypeLower]);
    
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
    
        if ($args['commandName'] != 'cancel') {
            if (count($this->uploadFields) > 0) {
                $entityData = $this->handleUploads($entityData, $entity);
                if ($entityData == false) {
                    return false;
                }
            }
    
            if (count($this->listFields) > 0) {
                foreach ($this->listFields as $listField => $multiple) {
                    if (!$multiple) {
                        continue;
                    }
                    if (is_array($entityData[$listField])) { 
                        $values = $entityData[$listField];
                        $entityData[$listField] = '';
                        if (count($values) > 0) {
                            $entityData[$listField] = '###' . implode('###', $values) . '###';
                        }
                    }
                }
            }
        } else {
            // remove fields for form options to prevent them being merged into the entity object
            if (count($this->uploadFields) > 0) {
                foreach ($this->uploadFields as $uploadField => $isMandatory) {
                    if (isset($entityData[$uploadField . 'DeleteFile'])) {
                        unset($entityData[$uploadField . 'DeleteFile']);
                    }
                }
            }
        }
    
        if (isset($entityData['repeatCreation'])) {
            if ($this->mode == 'create') {
                $this->repeatCreateAction = $entityData['repeatCreation'];
            }
            unset($entityData['repeatCreation']);
        }
        if (isset($entityData['additionalNotificationRemarks'])) {
            SessionUtil::setVar($this->name . 'AdditionalNotificationRemarks', $entityData['additionalNotificationRemarks']);
            unset($entityData['additionalNotificationRemarks']);
        }
    
        // search for relationship plugins to update the corresponding data
        $entityData = $this->writeRelationDataToEntity($view, $entity, $entityData);
    
        // assign fetched data
        $entity->merge($entityData);
    
        // we must persist related items now (after the merge) to avoid validation errors
        // if cascades cause the main entity becoming persisted automatically, too
        $this->persistRelationData($view);
    
        // save updated entity
        $this->entityRef = $entity;
    
        // return remaining form data
        return $formData;
    }
    
    /**
     * Updates the entity with new relationship data.
     *
     * @param Zikula_Form_View    $view       The form view instance.
     * @param Zikula_EntityAccess $entity     Reference to the updated entity.
     * @param array               $entityData Entity related form data.
     *
     * @return array form data after processing.
     */
    protected function writeRelationDataToEntity(Zikula_Form_View $view, $entity, $entityData)
    {
        $entityData = $this->writeRelationDataToEntity_rec($entity, $entityData, $view->plugins);
    
        return $entityData;
    }
    
    /**
     * Searches for relationship plugins to write their updated values
     * back to the given entity.
     *
     * @param Zikula_EntityAccess $entity     Reference to the updated entity.
     * @param array               $entityData Entity related form data.
     * @param array               $plugins    List of form plugin which are searched.
     *
     * @return array form data after processing.
     */
    protected function writeRelationDataToEntity_rec($entity, $entityData, $plugins)
    {
        foreach ($plugins as $plugin) {
            if ($plugin instanceof MUVideo_Form_Plugin_AbstractObjectSelector && method_exists($plugin, 'assignRelatedItemsToEntity')) {
                $entityData = $plugin->assignRelatedItemsToEntity($entity, $entityData);
            }
            $entityData = $this->writeRelationDataToEntity_rec($entity, $entityData, $plugin->plugins);
        }
    
        return $entityData;
    }
    
    /**
     * Persists any related items.
     *
     * @param Zikula_Form_View $view The form view instance.
     */
    protected function persistRelationData(Zikula_Form_View $view)
    {
        $this->persistRelationData_rec($view->plugins);
    }
    
    /**
     * Searches for relationship plugins to persist their related items.
     */
    protected function persistRelationData_rec($plugins)
    {
        foreach ($plugins as $plugin) {
            if ($plugin instanceof MUVideo_Form_Plugin_AbstractObjectSelector && method_exists($plugin, 'persistRelatedItems')) {
                $plugin->persistRelatedItems();
            }
            $this->persistRelationData_rec($plugin->plugins);
        }
    }

    /**
     * This method executes a certain workflow action.
     *
     * @param Array $args Arguments from handleCommand method.
     *
     * @return bool Whether everything worked well or not.
     */
    public function applyAction(array $args = array())
    {
        // stub for subclasses
        return false;
    }

    /**
     * Helper method to process upload fields.
     *
     * @param array  $formData       The form input data.
     * @param object $existingObject Data of existing entity object.
     *
     * @return array form data after processing.
     */
    protected function handleUploads($formData, $existingObject)
    {
        if (!count($this->uploadFields)) {
            return $formData;
        }
    
        // initialise the upload handler
        $uploadManager = new MUVideo_UploadHandler();
        $existingObjectData = $existingObject->toArray();
    
        $objectId = ($this->mode != 'create') ? $this->idValues[0] : 0;
    
        // process all fields
        foreach ($this->uploadFields as $uploadField => $isMandatory) {
            // check if an existing file must be deleted
            $hasOldFile = (!empty($existingObjectData[$uploadField]));
            $hasBeenDeleted = !$hasOldFile;
            if ($this->mode != 'create') {
                if (isset($formData[$uploadField . 'DeleteFile'])) {
                    if ($hasOldFile && $formData[$uploadField . 'DeleteFile'] === true) {
                        // remove upload file (and image thumbnails)
                        $existingObjectData = $uploadManager->deleteUploadFile($this->objectType, $existingObjectData, $uploadField, $objectId);
                        if (empty($existingObjectData[$uploadField])) {
                            $existingObject[$uploadField] = '';
                            $existingObject[$uploadField . 'Meta'] = array();
                        }
                    }
                    unset($formData[$uploadField . 'DeleteFile']);
                    $hasBeenDeleted = true;
                }
            }
    
            // look whether a file has been provided
            if (!$formData[$uploadField] || $formData[$uploadField]['size'] == 0) {
                // no file has been uploaded
                unset($formData[$uploadField]);
                // skip to next one
                continue;
            }
    
            if ($hasOldFile && $hasBeenDeleted !== true && $this->mode != 'create') {
                // remove old upload file (and image thumbnails)
                $existingObjectData = $uploadManager->deleteUploadFile($this->objectType, $existingObjectData, $uploadField, $objectId);
                if (empty($existingObjectData[$uploadField])) {
                    $existingObject[$uploadField] = '';
                    $existingObject[$uploadField . 'Meta'] = array();
                }
            }
    
            // do the actual upload (includes validation, physical file processing and reading meta data)
            $uploadResult = $uploadManager->performFileUpload($this->objectType, $formData, $uploadField);
            // assign the upload file name
            $formData[$uploadField] = $uploadResult['fileName'];
            // assign the meta data
            $formData[$uploadField . 'Meta'] = $uploadResult['metaData'];
    
            // if current field is mandatory check if everything has been done
            if ($isMandatory && empty($formData[$uploadField])) {
                // mandatory upload has not been completed successfully
                return false;
            }
    
            // upload succeeded
        }
    
        return $formData;
    }
}
