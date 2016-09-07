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
 * The muvideoFormFrame plugin adds styling <div> elements and a validation summary.
 *
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param array            $params  All attributes passed to this function from the template
 * @param string           $content The content of the block
 * @param Zikula_Form_View $view    Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_block_muvideoFormFrame($params, $content, $view)
{
    // As with all Forms plugins, we must remember to register our plugin.
    // In this case we also register a validation summary so we don't have to
    // do that explicitively in the templates.

    // We need to concatenate the output of boths plugins.
    $result = $view->registerPlugin('\\Zikula_Form_Plugin_ValidationSummary', $params);
    $result .= $view->registerBlock('MUVideo_Form_Plugin_FormFrame', $params, $content);

    return $result;
}
