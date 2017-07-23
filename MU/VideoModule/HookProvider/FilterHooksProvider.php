<?php
/**
 * Video.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\VideoModule\HookProvider;

use Zikula\Bundle\HookBundle\Hook\FilterHook;
use MU\VideoModule\HookProvider\Base\AbstractFilterHooksProvider;
use MU\VideoModule\Entity\Factory\EntityFactory;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * Implementation class for filter hooks provider.
 */
class FilterHooksProvider extends AbstractFilterHooksProvider
{
	/**
	 * @var EntityFactory
	 */
	protected $entityFactory;
	
	/**
	 * FilterHooksProvider constructor.
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator,
			EntityFactory $entityFactory)
	{
		$this->translator = $translator;
		$this->entityFactory = $entityFactory;
	}
	
    /**
     * Filters the given data.
     *
     * @param FilterHook $hook
     */
    public function applyFilter(FilterHook $hook)
    {
    	$content = $hook->getData();

    	// we look for youtube video pattern and replace if found one
    	$pattern = "(YOUTUBE)\[([0-9]*)\]";
    	$newData = preg_replace_callback("/$pattern/", 			function ($treffer)
    	{
    		$movieId = $treffer[2];
    		$movierepository = $this->entityFactory->getRepository('movie');
    		$movie = $movierepository->selectById($movieId);
    		if (is_object($movie)) {
    			$youtubeUrl = $movie['urlOfYoutube'];
    			if ($youtubeUrl != '') {
    				$youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $youtubeUrl);
    				return '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/' . $youtubeId . '?rel=0" allowfullscreen></iframe></div>';
    			} else {
    				return '';
    			}
    		} else {
    			return '';
    		}
    	}, $content);
        $hook->setData($newData);
    }
    
    protected function setEntityFactory(EntityFactory $entityFactory) {
    	$this->entityFactory = $entityFactory;
    }

    // feel free to add your own convenience methods here
}