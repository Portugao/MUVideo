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

namespace MU\VideoModule\Entity\Factory\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use MU\VideoModule\Entity\Factory\EntityInitialiser;

/**
 * Factory class used to create entities and receive entity repositories.
 */
abstract class AbstractVideoFactory
{
    /**
     * @var ObjectManager The object manager to be used for determining the repository
     */
    protected $objectManager;

    /**
     * @var EntityInitialiser The entity initialiser for dynamical application of default values
     */
    protected $entityInitialiser;

    /**
     * VideoFactory constructor.
     *
     * @param ObjectManager     $objectManager     The object manager to be used for determining the repositories
     * @param EntityInitialiser $entityInitialiser The entity initialiser for dynamical application of default values
     */
    public function __construct(ObjectManager $objectManager, EntityInitialiser $entityInitialiser)
    {
        $this->objectManager = $objectManager;
        $this->entityInitialiser = $entityInitialiser;
    }

    /**
     * Returns a repository for a given object type.
     *
     * @param string $objectType Name of desired entity type
     *
     * @return EntityRepository The repository responsible for the given object type
     */
    public function getRepository($objectType)
    {
        $entityClass = 'MU\\VideoModule\\Entity\\' . ucfirst($objectType) . 'Entity';

        return $this->objectManager->getRepository($entityClass);
    }

    /**
     * Creates a new collection instance.
     *
     * @return MU\VideoModule\Entity\collectionEntity The newly created entity instance
     */
    public function createCollection()
    {
        $entityClass = 'MU\\VideoModule\\Entity\\CollectionEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initCollection($entity);

        return $entity;
    }

    /**
     * Creates a new movie instance.
     *
     * @return MU\VideoModule\Entity\movieEntity The newly created entity instance
     */
    public function createMovie()
    {
        $entityClass = 'MU\\VideoModule\\Entity\\MovieEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initMovie($entity);

        return $entity;
    }

    /**
     * Creates a new playlist instance.
     *
     * @return MU\VideoModule\Entity\playlistEntity The newly created entity instance
     */
    public function createPlaylist()
    {
        $entityClass = 'MU\\VideoModule\\Entity\\PlaylistEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initPlaylist($entity);

        return $entity;
    }

    /**
     * Gets the list of identifier fields for a given object type.
     *
     * @param string $objectType The object type to be treated
     *
     * @return array List of identifier field names
     */
    public function getIdFields($objectType = '')
    {
        if (empty($objectType)) {
            throw new InvalidArgumentException('Invalid object type received.');
        }
        $entityClass = 'MUVideoModule:' . ucfirst($objectType) . 'Entity';
    
        $meta = $this->getObjectManager()->getClassMetadata($entityClass);
    
        if ($this->hasCompositeKeys($objectType)) {
            $idFields = $meta->getIdentifierFieldNames();
        } else {
            $idFields = [$meta->getSingleIdentifierFieldName()];
        }
    
        return $idFields;
    }

    /**
     * Checks whether a certain entity type uses composite keys or not.
     *
     * @param string $objectType The object type to retrieve
     *
     * @return Boolean Whether composite keys are used or not
     */
    public function hasCompositeKeys($objectType)
    {
        return false;
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Sets the object manager.
     *
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function setObjectManager($objectManager)
    {
        if ($this->objectManager != $objectManager) {
            $this->objectManager = $objectManager;
        }
    }
    

    /**
     * Returns the entity initialiser.
     *
     * @return EntityInitialiser
     */
    public function getEntityInitialiser()
    {
        return $this->entityInitialiser;
    }
    
    /**
     * Sets the entity initialiser.
     *
     * @param EntityInitialiser $entityInitialiser
     *
     * @return void
     */
    public function setEntityInitialiser($entityInitialiser)
    {
        if ($this->entityInitialiser != $entityInitialiser) {
            $this->entityInitialiser = $entityInitialiser;
        }
    }
    
}
