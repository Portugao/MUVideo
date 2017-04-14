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

use MU\VideoModule\Entity\Base\AbstractMovieCategoryEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity extension domain class storing movie categories.
 *
 * This is the concrete category class for movie entities.
 * @ORM\Entity(repositoryClass="\MU\VideoModule\Entity\Repository\MovieCategoryRepository")
 * @ORM\Table(name="mu_video_movie_category",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="cat_unq", columns={"registryId", "categoryId", "entityId"})
 *     }
 * )
 */
class MovieCategoryEntity extends BaseEntity
{
    // feel free to add your own methods here
}
