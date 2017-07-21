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

namespace MU\VideoModule\Container\Base;

use Zikula\Bundle\HookBundle\AbstractHookContainer as ZikulaHookContainer;
use Zikula\Bundle\HookBundle\Bundle\SubscriberBundle;

/**
 * Base class for hook container methods.
 */
abstract class AbstractHookContainer extends ZikulaHookContainer
{
    /**
     * Define the hook bundles supported by this module.
     *
     * @return void
     */
    protected function setupHookBundles()
    {
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.ui_hooks.collections', 'ui_hooks', $this->__('muvideomodule. Collections Display Hooks'));
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muvideomodule.ui_hooks.collections.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muvideomodule.ui_hooks.collections.form_edit');
        // Validate input from an item to be edited.
        $bundle->addEvent('validate_edit', 'muvideomodule.ui_hooks.collections.validate_edit');
        // Perform the final update actions for an edited item.
        $bundle->addEvent('process_edit', 'muvideomodule.ui_hooks.collections.process_edit');
        // Display hook for delete forms.
        $bundle->addEvent('form_delete', 'muvideomodule.ui_hooks.collections.form_delete');
        // Validate input from an item to be deleted.
        $bundle->addEvent('validate_delete', 'muvideomodule.ui_hooks.collections.validate_delete');
        // Perform the final delete actions for a deleted item.
        $bundle->addEvent('process_delete', 'muvideomodule.ui_hooks.collections.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.filter_hooks.collections', 'filter_hooks', $this->__('muvideomodule. Collections Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muvideomodule.filter_hooks.collections.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.ui_hooks.movies', 'ui_hooks', $this->__('muvideomodule. Movies Display Hooks'));
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muvideomodule.ui_hooks.movies.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muvideomodule.ui_hooks.movies.form_edit');
        // Validate input from an item to be edited.
        $bundle->addEvent('validate_edit', 'muvideomodule.ui_hooks.movies.validate_edit');
        // Perform the final update actions for an edited item.
        $bundle->addEvent('process_edit', 'muvideomodule.ui_hooks.movies.process_edit');
        // Display hook for delete forms.
        $bundle->addEvent('form_delete', 'muvideomodule.ui_hooks.movies.form_delete');
        // Validate input from an item to be deleted.
        $bundle->addEvent('validate_delete', 'muvideomodule.ui_hooks.movies.validate_delete');
        // Perform the final delete actions for a deleted item.
        $bundle->addEvent('process_delete', 'muvideomodule.ui_hooks.movies.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.filter_hooks.movies', 'filter_hooks', $this->__('muvideomodule. Movies Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muvideomodule.filter_hooks.movies.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.ui_hooks.playlists', 'ui_hooks', $this->__('muvideomodule. Playlists Display Hooks'));
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muvideomodule.ui_hooks.playlists.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muvideomodule.ui_hooks.playlists.form_edit');
        // Validate input from an item to be edited.
        $bundle->addEvent('validate_edit', 'muvideomodule.ui_hooks.playlists.validate_edit');
        // Perform the final update actions for an edited item.
        $bundle->addEvent('process_edit', 'muvideomodule.ui_hooks.playlists.process_edit');
        // Display hook for delete forms.
        $bundle->addEvent('form_delete', 'muvideomodule.ui_hooks.playlists.form_delete');
        // Validate input from an item to be deleted.
        $bundle->addEvent('validate_delete', 'muvideomodule.ui_hooks.playlists.validate_delete');
        // Perform the final delete actions for a deleted item.
        $bundle->addEvent('process_delete', 'muvideomodule.ui_hooks.playlists.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUVideoModule', 'subscriber.muvideomodule.filter_hooks.playlists', 'filter_hooks', $this->__('muvideomodule. Playlists Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muvideomodule.filter_hooks.playlists.filter');
        $this->registerHookSubscriberBundle($bundle);
        
    }
}
