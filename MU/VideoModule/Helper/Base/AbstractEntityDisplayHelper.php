<?php
/**
 * Video.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\VideoModule\Helper\Base;

use Zikula\Common\Translator\TranslatorInterface;
use MU\VideoModule\Entity\CollectionEntity;
use MU\VideoModule\Entity\MovieEntity;
use MU\VideoModule\Entity\PlaylistEntity;
use MU\VideoModule\Helper\ListEntriesHelper;

/**
 * Entity display helper base class.
 */
abstract class AbstractEntityDisplayHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ListEntriesHelper Helper service for managing list entries
     */
    protected $listEntriesHelper;

    /**
     * EntityDisplayHelper constructor.
     *
     * @param TranslatorInterface $translator        Translator service instance
     * @param ListEntriesHelper   $listEntriesHelper Helper service for managing list entries
     */
    public function __construct(
        TranslatorInterface $translator,
        ListEntriesHelper $listEntriesHelper
    ) {
        $this->translator = $translator;
        $this->listEntriesHelper = $listEntriesHelper;
    }

    /**
     * Returns the formatted title for a given entity.
     *
     * @param object $entity The given entity instance
     *
     * @return string The formatted title
     */
    public function getFormattedTitle($entity)
    {
        if ($entity instanceof CollectionEntity) {
            return $this->formatCollection($entity);
        }
        if ($entity instanceof MovieEntity) {
            return $this->formatMovie($entity);
        }
        if ($entity instanceof PlaylistEntity) {
            return $this->formatPlaylist($entity);
        }
    
        return '';
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param CollectionEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatCollection(CollectionEntity $entity)
    {
        return $this->translator->__f('%title%', [
            '%title%' => $entity->getTitle()
        ]);
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param MovieEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatMovie(MovieEntity $entity)
    {
        return $this->translator->__f('%title%', [
            '%title%' => $entity->getTitle()
        ]);
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param PlaylistEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatPlaylist(PlaylistEntity $entity)
    {
        return $this->translator->__f('%title%', [
            '%title%' => $entity->getTitle()
        ]);
    }
    
    /**
     * Returns name of the field used as title / name for entities of this repository.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as title
     */
    public function getTitleFieldName($objectType)
    {
        if ($objectType == 'collection') {
            return 'title';
        }
        if ($objectType == 'movie') {
            return 'title';
        }
        if ($objectType == 'playlist') {
            return 'title';
        }
    
        return '';
    }
    
    /**
     * Returns name of the field used for describing entities of this repository.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as description
     */
    public function getDescriptionFieldName($objectType)
    {
        if ($objectType == 'collection') {
            return 'description';
        }
        if ($objectType == 'movie') {
            return 'description';
        }
        if ($objectType == 'playlist') {
            return 'description';
        }
    
        return '';
    }
    
    /**
     * Returns name of first upload field which is capable for handling images.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used for preview images
     */
    public function getPreviewFieldName($objectType)
    {
        if ($objectType == 'movie') {
            return 'poster';
        }
    
        return '';
    }
    
    /**
     * Returns name of the date(time) field to be used for representing the start
     * of this object. Used for providing meta data to the tag module.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as date
     */
    public function getStartDateFieldName($objectType)
    {
        if ($objectType == 'collection') {
            return 'createdDate';
        }
        if ($objectType == 'movie') {
            return 'createdDate';
        }
        if ($objectType == 'playlist') {
            return 'createdDate';
        }
    
        return '';
    }
}
