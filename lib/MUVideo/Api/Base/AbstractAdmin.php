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
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * This is the Admin api helper class.
 */
abstract class MUVideo_Api_Base_AbstractAdmin extends Zikula_AbstractApi
{
    /**
     * Returns available admin panel links.
     *
     * @return array Array of admin links
     */
    public function getLinks()
    {
        $links = array();

        $controllerHelper = new MUVideo_Util_Controller($this->serviceManager);
        $utilArgs = array('api' => 'admin', 'action' => 'getLinks');
        $allowedObjectTypes = $controllerHelper->getObjectTypes('api', $utilArgs);

        $currentType = $this->request->query->filter('type', 'collection', FILTER_SANITIZE_STRING);
        $currentLegacyType = $this->request->query->filter('lct', 'user', FILTER_SANITIZE_STRING);
        $permLevel = in_array('admin', array($currentType, $currentLegacyType)) ? ACCESS_ADMIN : ACCESS_READ;

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'user', 'main'),
                 'text' => $this->__('Frontend'),
                 'title' => $this->__('Switch to user area.'),
                 'class' => 'z-icon-es-home'
             );
        }
        
        if (in_array('collection', $allowedObjectTypes)
            && SecurityUtil::checkPermission($this->name . ':Collection:', '::', $permLevel)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'collection')),
                 'text' => $this->__('Collections'),
                 'title' => $this->__('Collection list')
             );
        }
        if (in_array('movie', $allowedObjectTypes)
            && SecurityUtil::checkPermission($this->name . ':Movie:', '::', $permLevel)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'movie')),
                 'text' => $this->__('Movies'),
                 'title' => $this->__('Movie list')
             );
        }
        if (in_array('playlist', $allowedObjectTypes)
            && SecurityUtil::checkPermission($this->name . ':Playlist:', '::', $permLevel)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'playlist')),
                 'text' => $this->__('Playlists'),
                 'title' => $this->__('Playlist list')
             );
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'config'),
                'text' => $this->__('Configuration'),
                'title' => $this->__('Manage settings for this application')
            );
        }

        return $links;
    }
}
