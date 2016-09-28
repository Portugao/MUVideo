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
 * This handler class handles the page events of the Form called by the mUVideo_collection_getVideos() function.
 * It aims on the collection object type.
 */
class MUVideo_Form_Handler_Collection_GetPlaylists extends MUVideo_Form_Handler_Collection_Base_GetPlaylists
{
    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        $playLists = ModUtil::getVar($this->name, 'channelIds');
        $playLists = explode(';', $playLists);
        if (count($playLists) == 1) {
            $playLists = explode(',', $playLists[0]);         
            // initialise list entries for the 'channelId' setting
            $formVars['channelIdItems'] = array(array('value' => $playLists[0], 'text' => $playLists[1]));
            // assign all module vars
            $this->view->assign('getPlaylists', $formVars);
        } else {
            foreach ($playLists as $playList) {
                $thisPlaylist = explode(',', $playList);
                $formVars['channelIdItems'][] = array('value' => $thisPlaylist[0], 'text' => $thisPlaylist[1]);
            }
            $this->view->assign('getPlaylists', $formVars);
        }
        parent::postInitialize();
    }
    
    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     *
     * @param Zikula_Form_View $view The form view instance.
     * @param array            $args Additional arguments.
     *
     * @return mixed Redirect or false on errors.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        // get collection id    
        $collectionId = $this->request->query->filter('collectionId', 0, FILTER_SANITIZE_NUMBER_INT);
        
        if ($collectionId == 0) {
            return LogUtil::registerError(__('Sorry. There is no valid collection id!'));
        }

        // get channel id from form
        $channelId = $this->request->request->filter('channelId', '', FILTER_SANITIZE_STRING);
        
        $serviceManager = ServiceUtil::getManager();
        $controllerHelper = new MUVideo_Util_Controller($serviceManager);
        
        return $controllerHelper->getYoutubePlaylists($channelId[0], $collectionId);
    }
    
}