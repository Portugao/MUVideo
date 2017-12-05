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

namespace MU\VideoModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use MU\VideoModule\Traits\StandardFieldsTrait;
use MU\VideoModule\Validator\Constraints as VideoAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for movie entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractMovieEntity extends EntityAccess implements Translatable
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'movie';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     *
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @VideoAssert\ListEntry(entityName="movie", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $title
     */
    protected $title = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=4000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="4000")
     * @var text $description
     */
    protected $description = '';
    
    /**
     * Upload of movie meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $uploadOfMovieMeta
     */
    protected $uploadOfMovieMeta = [];
    
    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
     *    maxSize = "200M",
     *    mimeTypes = {"video/*"}
     * )
     * @var string $uploadOfMovie
     */
    protected $uploadOfMovie = null;
    
    /**
     * Full upload of movie path as url.
     *
     * @Assert\Type(type="string")
     * @var string $uploadOfMovieUrl
     */
    protected $uploadOfMovieUrl = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @Assert\Url(checkDNS=false)
     * @var string $urlOfYoutube
     */
    protected $urlOfYoutube = '';
    
    /**
     * Poster meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $posterMeta
     */
    protected $posterMeta = [];
    
    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
     *    maxSize = "200k",
     *    mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
     * )
     * @var string $poster
     */
    protected $poster = null;
    
    /**
     * Full poster path as url.
     *
     * @Assert\Type(type="string")
     * @var string $posterUrl
     */
    protected $posterUrl = '';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $widthOfMovie
     */
    protected $widthOfMovie = 0;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $heightOfMovie
     */
    protected $heightOfMovie = 0;
    
    
    /**
     * Used locale to override Translation listener's locale.
     * this is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\Locale()
     * @Gedmo\Locale
     * @var string $locale
     */
    protected $locale;
    
    /**
     * @ORM\OneToMany(targetEntity="\MU\VideoModule\Entity\MovieCategoryEntity", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     * @var \MU\VideoModule\Entity\MovieCategoryEntity
     */
    protected $categories = null;
    
    /**
     * Bidirectional - Many movie [movies] are linked by one collection [collection] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="MU\VideoModule\Entity\CollectionEntity", inversedBy="movie")
     * @ORM\JoinTable(name="mu_video_collection")
     * @Assert\Type(type="MU\VideoModule\Entity\CollectionEntity")
     * @var \MU\VideoModule\Entity\CollectionEntity $collection
     */
    protected $collection;
    
    
    /**
     * MovieEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        if ($this->title !== $title) {
            $this->title = isset($title) ? $title : '';
        }
    }
    
    /**
     * Returns the description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description.
     *
     * @param text $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        if ($this->description !== $description) {
            $this->description = isset($description) ? $description : '';
        }
    }
    
    /**
     * Returns the upload of movie.
     *
     * @return string
     */
    public function getUploadOfMovie()
    {
        return $this->uploadOfMovie;
    }
    
    /**
     * Sets the upload of movie.
     *
     * @param string $uploadOfMovie
     *
     * @return void
     */
    public function setUploadOfMovie($uploadOfMovie)
    {
        if ($this->uploadOfMovie !== $uploadOfMovie) {
            $this->uploadOfMovie = $uploadOfMovie;
        }
    }
    
    /**
     * Returns the upload of movie url.
     *
     * @return string
     */
    public function getUploadOfMovieUrl()
    {
        return $this->uploadOfMovieUrl;
    }
    
    /**
     * Sets the upload of movie url.
     *
     * @param string $uploadOfMovieUrl
     *
     * @return void
     */
    public function setUploadOfMovieUrl($uploadOfMovieUrl)
    {
        if ($this->uploadOfMovieUrl !== $uploadOfMovieUrl) {
            $this->uploadOfMovieUrl = $uploadOfMovieUrl;
        }
    }
    
    /**
     * Returns the upload of movie meta.
     *
     * @return array
     */
    public function getUploadOfMovieMeta()
    {
        return $this->uploadOfMovieMeta;
    }
    
    /**
     * Sets the upload of movie meta.
     *
     * @param array $uploadOfMovieMeta
     *
     * @return void
     */
    public function setUploadOfMovieMeta($uploadOfMovieMeta = [])
    {
        if ($this->uploadOfMovieMeta !== $uploadOfMovieMeta) {
            $this->uploadOfMovieMeta = $uploadOfMovieMeta;
        }
    }
    
    /**
     * Returns the url of youtube.
     *
     * @return string
     */
    public function getUrlOfYoutube()
    {
        return $this->urlOfYoutube;
    }
    
    /**
     * Sets the url of youtube.
     *
     * @param string $urlOfYoutube
     *
     * @return void
     */
    public function setUrlOfYoutube($urlOfYoutube)
    {
        if ($this->urlOfYoutube !== $urlOfYoutube) {
            $this->urlOfYoutube = isset($urlOfYoutube) ? $urlOfYoutube : '';
        }
    }
    
    /**
     * Returns the poster.
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }
    
    /**
     * Sets the poster.
     *
     * @param string $poster
     *
     * @return void
     */
    public function setPoster($poster)
    {
        if ($this->poster !== $poster) {
            $this->poster = $poster;
        }
    }
    
    /**
     * Returns the poster url.
     *
     * @return string
     */
    public function getPosterUrl()
    {
        return $this->posterUrl;
    }
    
    /**
     * Sets the poster url.
     *
     * @param string $posterUrl
     *
     * @return void
     */
    public function setPosterUrl($posterUrl)
    {
        if ($this->posterUrl !== $posterUrl) {
            $this->posterUrl = $posterUrl;
        }
    }
    
    /**
     * Returns the poster meta.
     *
     * @return array
     */
    public function getPosterMeta()
    {
        return $this->posterMeta;
    }
    
    /**
     * Sets the poster meta.
     *
     * @param array $posterMeta
     *
     * @return void
     */
    public function setPosterMeta($posterMeta = [])
    {
        if ($this->posterMeta !== $posterMeta) {
            $this->posterMeta = $posterMeta;
        }
    }
    
    /**
     * Returns the width of movie.
     *
     * @return integer
     */
    public function getWidthOfMovie()
    {
        return $this->widthOfMovie;
    }
    
    /**
     * Sets the width of movie.
     *
     * @param integer $widthOfMovie
     *
     * @return void
     */
    public function setWidthOfMovie($widthOfMovie)
    {
        if (intval($this->widthOfMovie) !== intval($widthOfMovie)) {
            $this->widthOfMovie = intval($widthOfMovie);
        }
    }
    
    /**
     * Returns the height of movie.
     *
     * @return integer
     */
    public function getHeightOfMovie()
    {
        return $this->heightOfMovie;
    }
    
    /**
     * Sets the height of movie.
     *
     * @param integer $heightOfMovie
     *
     * @return void
     */
    public function setHeightOfMovie($heightOfMovie)
    {
        if (intval($this->heightOfMovie) !== intval($heightOfMovie)) {
            $this->heightOfMovie = intval($heightOfMovie);
        }
    }
    
    /**
     * Returns the locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    /**
     * Sets the locale.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        if ($this->locale != $locale) {
            $this->locale = $locale;
        }
    }
    
    /**
     * Returns the categories.
     *
     * @return ArrayCollection[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    
    /**
     * Sets the categories.
     *
     * @param ArrayCollection $categories List of categories
     *
     * @return void
     */
    public function setCategories(ArrayCollection $categories)
    {
        foreach ($this->categories as $category) {
            if (false === $key = $this->collectionContains($categories, $category)) {
                $this->categories->removeElement($category);
            } else {
                $categories->remove($key);
            }
        }
        foreach ($categories as $category) {
            $this->categories->add($category);
        }
    }
    
    /**
     * Checks if a collection contains an element based only on two criteria (categoryRegistryId, category).
     *
     * @param ArrayCollection $collection Given collection
     * @param \MU\VideoModule\Entity\MovieCategoryEntity $element Element to search for
     *
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, \MU\VideoModule\Entity\MovieCategoryEntity $element)
    {
        foreach ($collection as $key => $category) {
            /** @var \MU\VideoModule\Entity\MovieCategoryEntity $category */
            if ($category->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $category->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
    
        return false;
    }
    
    /**
     * Returns the collection.
     *
     * @return \MU\VideoModule\Entity\CollectionEntity
     */
    public function getCollection()
    {
        return $this->collection;
    }
    
    /**
     * Sets the collection.
     *
     * @param \MU\VideoModule\Entity\CollectionEntity $collection
     *
     * @return void
     */
    public function setCollection($collection = null)
    {
        $this->collection = $collection;
    }
    
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array List of resulting arguments
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'muvideomodule.ui_hooks.movies';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects Objects that are added to this array
     * 
     * @return array List of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = [])
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Movie ' . $this->getKey() . ': ' . $this->getTitle();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        // reset upload fields
        $this->setUploadOfMovie(null);
        $this->setUploadOfMovieMeta([]);
        $this->setUploadOfMovieUrl('');
        $this->setPoster(null);
        $this->setPosterMeta([]);
        $this->setPosterUrl('');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    
        // clone categories
        $categories = $this->categories;
        $this->categories = new ArrayCollection();
        foreach ($categories as $c) {
            $newCat = clone $c;
            $this->categories->add($newCat);
            $newCat->setEntity($this);
        }
    }
}
