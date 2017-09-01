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

namespace MU\VideoModule\Controller;

use MU\VideoModule\Controller\Base\AbstractCollectionController;

use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use MU\VideoModule\Entity\CollectionEntity;

/**
 * Collection controller class providing navigation and interaction functionality.
 */
class CollectionController extends AbstractCollectionController
{
    /**
     * @inheritDoc
     *
     * @Route("/admin/collections",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return parent::adminIndexAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/collections",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/collections/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|json"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::adminViewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/collections/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|json"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::viewAction($request, $sort, $sortdir, $pos, $num);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/collection/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html|json"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("collection", class="MUVideoModule:CollectionEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="collection.getUpdatedDate()", ETag="'Collection' ~ collection.getid() ~ collection.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param CollectionEntity $collection Treated collection instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if collection to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, CollectionEntity $collection)
    {
        return parent::adminDisplayAction($request, $collection);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/collection/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html|json"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("collection", class="MUVideoModule:CollectionEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="collection.getUpdatedDate()", ETag="'Collection' ~ collection.getid() ~ collection.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param CollectionEntity $collection Treated collection instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if collection to be displayed isn't found
     */
    public function displayAction(Request $request, CollectionEntity $collection)
    {
        return parent::displayAction($request, $collection);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/collection/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if collection to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return parent::adminEditAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/collection/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if collection to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/collection/delete/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("collection", class="MUVideoModule:CollectionEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="collection.getUpdatedDate()", ETag="'Collection' ~ collection.getid() ~ collection.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param CollectionEntity $collection Treated collection instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if collection to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, CollectionEntity $collection)
    {
        return parent::adminDeleteAction($request, $collection);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/collection/delete/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("collection", class="MUVideoModule:CollectionEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="collection.getUpdatedDate()", ETag="'Collection' ~ collection.getid() ~ collection.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param CollectionEntity $collection Treated collection instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if collection to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, CollectionEntity $collection)
    {
        return parent::deleteAction($request, $collection);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/admin/collections/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return parent::adminHandleSelectedEntriesAction($request);
    }
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/collections/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return parent::handleSelectedEntriesAction($request);
    }

    // feel free to add your own controller methods here
}
