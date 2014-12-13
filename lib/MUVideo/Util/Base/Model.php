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
 * Utility base class for model helper methods.
 */
class MUVideo_Util_Base_Model extends Zikula_AbstractBase
{
    /**
     * Determines whether creating an instance of a certain object type is possible.
     * This is when
     *     - no tree is used
     *     - it has no incoming bidirectional non-nullable relationships.
     *     - the edit type of all those relationships has PASSIVE_EDIT and auto completion is used on the target side
     *       (then a new source object can be created while creating the target object).
     *     - corresponding source objects exist already in the system.
     *
     * Note that even creation of a certain object is possible, it may still be forbidden for the current user
     * if he does not have the required permission level.
     *
     * @param string $objectType Name of treated entity type.
     *
     * @return boolean Whether a new instance can be created or not.
     */
    public function canBeCreated($objectType)
    {
        $controllerHelper = new MUVideo_Util_Controller($this->serviceManager);
        if (!in_array($objectType, $controllerHelper->getObjectTypes('util', array('util' => 'model', 'action' => 'canBeCreated')))) {
            throw new \Exception('Error! Invalid object type received.');
        }
    
        $result = false;
    
        switch ($objectType) {
            case 'collection':
                $result = true;
                break;
            case 'movie':
                $result = true;
                break;
        }
    
        return $result;
    }

    /**
     * Determines whether there exist at least one instance of a certain object type in the database.
     *
     * @param string $objectType Name of treated entity type.
     *
     * @return boolean Whether at least one instance exists or not.
     */
    protected function hasExistingInstances($objectType)
    {
        $controllerHelper = new MUVideo_Util_Controller($this->serviceManager);
        if (!in_array($objectType, $controllerHelper->getObjectTypes('util', array('util' => 'model', 'action' => 'hasExistingInstances')))) {
            throw new \Exception('Error! Invalid object type received.');
        }
    
        $entityClass = 'MUVideo_Entity_' . ucfirst($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
    
        return ($repository->selectCount() > 0);
    }
}
