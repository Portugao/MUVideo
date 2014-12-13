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
 * Event handler implementation class for special purposes and 3rd party api support.
 */
class MUVideo_Listener_ThirdParty extends MUVideo_Listener_Base_ThirdParty
{
    /**
     * Listener for the 'get.pending_content' event with registration requests and
     * other submitted data pending approval.
     *
     * When a 'get.pending_content' event is fired, the Users module will respond with the
     * number of registration requests that are pending administrator approval. The number
     * pending may not equal the total number of outstanding registration requests, depending
     * on how the 'moderation_order' module configuration variable is set, and whether e-mail
     * address verification is required.
     * If the 'moderation_order' variable is set to require approval after e-mail verification
     * (and e-mail verification is also required) then the number of pending registration
     * requests will equal the number of registration requested that have completed the
     * verification process but have not yet been approved. For other values of
     * 'moderation_order', the number should equal the number of registration requests that
     * have not yet been approved, without regard to their current e-mail verification state.
     * If moderation of registrations is not enabled, then the value will always be 0.
     * In accordance with the 'get_pending_content' conventions, the count of pending
     * registrations, along with information necessary to access the detailed list, is
     * assemped as a {@link Zikula_Provider_AggregateItem} and added to the event
     * subject's collection.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function pendingContentListener(Zikula_Event $event)
    {
        parent::pendingContentListener($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * Listener for the `module.content.gettypes` event.
     *
     * This event occurs when the Content module is 'searching' for Content plugins.
     * The subject is an instance of Content_Types.
     * You can register custom content types as well as custom layout types.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function contentGetTypes(Zikula_Event $event)
    {
        parent::contentGetTypes($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * Listener for the `module.scribite.editorhelpers` event.
     *
     * This occurs when Scribite adds pagevars to the editor page.
     * MUVideo will use this to add a javascript helper to add custom items.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getEditorHelpers(Zikula_Event $event)
    {
        parent::getEditorHelpers($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * Listener for the `moduleplugin.tinymce.externalplugins` event.
     *
     * Adds external plugin to TinyMCE.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getTinyMcePlugins(Zikula_Event $event)
    {
        parent::getTinyMcePlugins($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * Listener for the `moduleplugin.ckeditor.externalplugins` event.
     *
     * Adds external plugin to CKEditor.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getCKEditorPlugins(Zikula_Event $event)
    {
        parent::getCKEditorPlugins($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
}
