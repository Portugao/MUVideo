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
 * This is the User api helper class.
 */
class MUVideo_Api_User extends MUVideo_Api_Base_User
{
    /**
     * Returns the supported modules set in the configuration.
     *
     * @return array $modules
     */
    public static function checkModules()
    {
        $modules = ModUtil::getVar('MUVideo', 'supportedModules');
        $modules = explode(',', $modules);
        return $modules;
    }
}
