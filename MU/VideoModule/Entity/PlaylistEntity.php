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

namespace MU\VideoModule\Entity;

use MU\VideoModule\Entity\Base\AbstractPlaylistEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for playlist entities.
 * @Gedmo\TranslationEntity(class="MU\VideoModule\Entity\PlaylistTranslationEntity")
 * @ORM\Entity(repositoryClass="MU\VideoModule\Entity\Repository\PlaylistRepository")
 * @ORM\Table(name="mu_video_playlist",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class PlaylistEntity extends BaseEntity
{
    // feel free to add your own methods here
}