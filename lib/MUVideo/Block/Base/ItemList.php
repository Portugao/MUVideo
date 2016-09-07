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
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

/**
 * Generic item list block base class.
 */
class MUVideo_Block_Base_ItemList extends Zikula_Controller_AbstractBlock
{
    /**
     * List of object types allowing categorisation.
     *
     * @var array
     */
    protected $categorisableObjectTypes;
    
    /**
     * Initialise the block.
     */
    public function init()
    {
        //SecurityUtil::registerPermissionSchema('MUVideo:ItemListBlock:', 'Block title::');
    
        $this->categorisableObjectTypes = array('collection', 'movie');
    }
    
    /**
     * Get information on the block.
     *
     * @return array The block information
     */
    public function info()
    {
        $requirementMessage = '';
        // check if the module is available at all
        if (!ModUtil::available('MUVideo')) {
            $requirementMessage .= $this->__('Notice: This block will not be displayed until you activate the MUVideo module.');
        }
    
        return array(
            'module'          => 'MUVideo',
            'text_type'       => $this->__('MUVideo list view'),
            'text_type_long'  => $this->__('Display list of MUVideo objects.'),
            'allow_multiple'  => true,
            'form_content'    => false,
            'form_refresh'    => false,
            'show_preview'    => true,
            'admin_tableless' => true,
            'requirement'     => $requirementMessage
        );
    }
    
    /**
     * Display the block content.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return string output of the rendered block
     */
    public function display($blockinfo)
    {
        // only show block content if the user has the required permissions
        if (!SecurityUtil::checkPermission('MUVideo:ItemListBlock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return false;
        }
    
        // check if the module is available at all
        if (!ModUtil::available('MUVideo')) {
            return false;
        }
    
        // get current block content
        $properties = BlockUtil::varsFromContent($blockinfo['content']);
        $properties['bid'] = $blockinfo['bid'];
    
        // set default values for all params which are not properly set
        $defaults = $this->getDefaults();
        $properties = array_merge($defaults, $properties);
    
        $properties = $this->resolveCategoryIds($properties);
    
        ModUtil::initOOModule('MUVideo');
    
        $controllerHelper = new MUVideo_Util_Controller($this->serviceManager);
        $utilArgs = array('name' => 'list');
        if (!isset($properties['objectType']) || !in_array($properties['objectType'], $controllerHelper->getObjectTypes('block', $utilArgs))) {
            $properties['objectType'] = $controllerHelper->getDefaultObjectType('block', $utilArgs);
        }
    
        $objectType = $properties['objectType'];
    
        $entityClass = 'MUVideo_Entity_' . ucfirst($objectType);
        $entityManager = $this->serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository($entityClass);
    
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);
        // set cache id
        $component = 'MUVideo:' . ucfirst($objectType) . ':';
        $instance = '::';
        $accessLevel = ACCESS_READ;
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        $this->view->setCacheId('view|ot_' . $objectType . '_sort_' . $properties['sorting'] . '_amount_' . $properties['amount'] . '_' . $accessLevel);
    
        $template = $this->getDisplayTemplate($properties);
    
        // if page is cached return cached content
        if ($this->view->is_cached($template)) {
            $blockinfo['content'] = $this->view->fetch($template);
    
            return BlockUtil::themeBlock($blockinfo);
        }
    
        // create query
        $where = $properties['filter'];
        $orderBy = $this->getSortParam($properties, $repository);
        $qb = $repository->genericBaseQuery($where, $orderBy);
    
        // fetch category registries
        $catProperties = null;
        if (in_array($objectType, $this->categorisableObjectTypes)) {
            $catProperties = ModUtil::apiFunc('MUVideo', 'category', 'getAllProperties', array('ot' => $objectType));
            // apply category filters
            if (is_array($properties['catIds']) && count($properties['catIds']) > 0) {
                $qb = ModUtil::apiFunc('MUVideo', 'category', 'buildFilterClauses', array('qb' => $qb, 'ot' => $objectType, 'catids' => $properties['catIds']));
            }
        }
    
        // get objects from database
        $currentPage = 1;
        $resultsPerPage = $properties['amount'];
        list($query, $count) = $repository->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
        $entities = $repository->retrieveCollectionResult($query, $orderBy, true);
    
        // assign block vars and fetched data
        $this->view->assign('vars', $properties)
                   ->assign('objectType', $objectType)
                   ->assign('items', $entities)
                   ->assign($repository->getAdditionalTemplateParameters('block'));
    
        // assign category registries
        $this->view->assign('properties', $catProperties);
    
        // set a block title
        if (empty($blockinfo['title'])) {
            $blockinfo['title'] = $this->__('MUVideo items');
        }
    
        $blockinfo['content'] = $this->view->fetch($template);
    
        // return the block to the theme
        return BlockUtil::themeBlock($blockinfo);
    }
    
    /**
     * Returns the template used for output.
     *
     * @param array $properties The block properties array
     *
     * @return string the template path
     */
    protected function getDisplayTemplate(array $properties)
    {
        $templateFile = $properties['template'];
        if ($templateFile == 'custom') {
            $templateFile = $properties['customTemplate'];
        }
    
        $templateForObjectType = str_replace('itemlist_', 'itemlist_' . $properties['objectType'] . '_', $templateFile);
    
        $template = '';
        if ($this->view->template_exists('contenttype/' . $templateForObjectType)) {
            $template = 'contenttype/' . $templateForObjectType;
        } elseif ($this->view->template_exists('block/' . $templateForObjectType)) {
            $template = 'block/' . $templateForObjectType;
        } elseif ($this->view->template_exists('contenttype/' . $templateFile)) {
            $template = 'contenttype/' . $templateFile;
        } elseif ($this->view->template_exists('block/' . $templateFile)) {
            $template = 'block/' . $templateFile;
        } else {
            $template = 'block/itemlist.tpl';
        }
    
        return $template;
    }
    
    /**
     * Determines the order by parameter for item selection.
     *
     * @param array               $properties The block properties array
     * @param Doctrine_Repository $repository The repository used for data fetching
     *
     * @return string the sorting clause
     */
    protected function getSortParam(array $properties, $repository)
    {
        if ($properties['sorting'] == 'random') {
            return 'RAND()';
        }
    
        $sortParam = '';
        if ($properties['sorting'] == 'newest') {
            $idFields = ModUtil::apiFunc('MUVideo', 'selection', 'getIdFields', array('ot' => $properties['objectType']));
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' DESC';
                }
            }
        } elseif ($properties['sorting'] == 'default') {
            $sortParam = $repository->getDefaultSortingField() . ' ASC';
        }
    
        return $sortParam;
    }
    
    /**
     * Modify block settings.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return string output of the block editing form
     */
    public function modify($blockinfo)
    {
        // Get current content
        $properties = BlockUtil::varsFromContent($blockinfo['content']);
    
        // set default values for all params which are not properly set
        $defaults = $this->getDefaults();
        $properties = array_merge($defaults, $properties);
    
        $properties = $this->resolveCategoryIds($properties);
    
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    
        // assign the appropriate values
        $this->view->assign($properties);
    
        // Return the output that has been generated by this function
        return $this->view->fetch('block/itemlist_modify.tpl');
    }
    
    /**
     * Update block settings.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return array the modified blockinfo structure
     */
    public function update($blockinfo)
    {
        // Get current content
        $properties = BlockUtil::varsFromContent($blockinfo['content']);
    
        $properties['objectType'] = $this->request->request->filter('objecttype', 'collection', FILTER_SANITIZE_STRING);
        $properties['sorting'] = $this->request->request->filter('sorting', 'default', FILTER_SANITIZE_STRING);
        $properties['amount'] = (int) $this->request->request->filter('amount', 5, FILTER_VALIDATE_INT);
        $properties['template'] = $this->request->request->get('template', '');
        $properties['customTemplate'] = $this->request->request->get('customtemplate', '');
        $properties['filter'] = $this->request->request->get('filter', '');
    
        $controllerHelper = new MUVideo_Util_Controller($this->serviceManager);
        if (!in_array($properties['objectType'], $controllerHelper->getObjectTypes('block'))) {
            $properties['objectType'] = $controllerHelper->getDefaultObjectType('block');
        }
    
        $primaryRegistry = ModUtil::apiFunc('MUVideo', 'category', 'getPrimaryProperty', array('ot' => $properties['objectType']));
        $properties['catIds'] = array($primaryRegistry => array());
        if (in_array($properties['objectType'], $this->categorisableObjectTypes)) {
            $properties['catIds'] = ModUtil::apiFunc('MUVideo', 'category', 'retrieveCategoriesFromRequest', array('ot' => $properties['objectType']));
        }
    
        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($properties);
    
        // clear the block cache
        $this->view->clear_cache('block/itemlist_display.tpl');
        $this->view->clear_cache('block/itemlist_' . $properties['objectType'] . '_display.tpl');
        $this->view->clear_cache('block/itemlist_display_description.tpl');
        $this->view->clear_cache('block/itemlist_' . $properties['objectType'] . '_display_description.tpl');
    
        return $blockinfo;
    }
    
    /**
     * Returns default settings for this block.
     *
     * @return array The default settings
     */
    protected function getDefaults()
    {
        $defaults = array(
            'objectType' => 'collection',
            'sorting' => 'default',
            'amount' => 5,
            'template' => 'itemlist_display.tpl',
            'customTemplate' => '',
            'filter' => ''
        );
    
        return $defaults;
    }
    
    
    /**
     * Resolves category filter ids.
     *
     * @param array $properties The block properties array
     *
     * @return array The updated block properties
     */
    protected function resolveCategoryIds(array $properties)
    {
        if (!isset($properties['catIds'])) {
            $primaryRegistry = ModUtil::apiFunc('MUVideo', 'category', 'getPrimaryProperty', array('ot' => $properties['objectType']));
            $properties['catIds'] = array($primaryRegistry => array());
            // backwards compatibility
            if (isset($properties['catId'])) {
                $properties['catIds'][$primaryRegistry][] = $properties['catId'];
                unset($properties['catId']);
            }
        } elseif (!is_array($properties['catIds'])) {
            $properties['catIds'] = explode(',', $properties['catIds']);
        }
    
        return $properties;
    }
}
