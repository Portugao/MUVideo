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

namespace MU\VideoModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\ThemeModule\ThemeEvents;
use Zikula\ThemeModule\Bridge\Event\TwigPostRenderEvent;
use Zikula\ThemeModule\Bridge\Event\TwigPreRenderEvent;

/**
 * Event handler base class for theme-related events.
 */
abstract class AbstractThemeListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            ThemeEvents::PRE_RENDER  => ['preRender', 5],
            ThemeEvents::POST_RENDER => ['postRender', 5]
        ];
    }
    
    /**
     * Listener for the `theme.pre_render` event.
     *
     * Occurs immediately before twig theme engine renders a template.
     * The event subject is \Zikula\ThemeModule\Bridge\Event\TwigPreRenderEvent.
     *
     * @param TwigPreRenderEvent $event The event instance
     */
    public function preRender(TwigPreRenderEvent $event)
    {
    }
    
    /**
     * Listener for the `theme.post_render` event.
     *
     * Occurs immediately after twig theme engine renders a template.
     * The event subject is \Zikula\ThemeModule\Bridge\Event\TwigPostRenderEvent.
     *
     * An example for implementing this event is \Zikula\ThemeModule\EventListener\TemplateNameExposeListener.
     *
     * @param TwigPostRenderEvent $event The event instance
     */
    public function postRender(TwigPostRenderEvent $event)
    {
    }
}