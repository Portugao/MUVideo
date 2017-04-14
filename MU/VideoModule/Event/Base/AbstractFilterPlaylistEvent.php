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

namespace MU\VideoModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use MU\VideoModule\Entity\PlaylistEntity;

/**
 * Event base class for filtering playlist processing.
 */
class AbstractFilterPlaylistEvent extends Event
{
    /**
     * @var PlaylistEntity Reference to treated entity instance.
     */
    protected $playlist;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    /**
     * FilterPlaylistEvent constructor.
     *
     * @param PlaylistEntity $playlist Processed entity
     * @param array $entityChangeSet Change set for preUpdate events
     */
    public function __construct(PlaylistEntity $playlist, $entityChangeSet = [])
    {
        $this->playlist = $playlist;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * Returns the entity.
     *
     * @return PlaylistEntity
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * Returns the change set.
     *
     * @return array
     */
    public function getEntityChangeSet()
    {
        return $this->entityChangeSet;
    }
}