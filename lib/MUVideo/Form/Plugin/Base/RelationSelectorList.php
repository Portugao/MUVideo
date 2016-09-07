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
 * Relation selector plugin base class.
 */
class MUVideo_Form_Plugin_Base_RelationSelectorList extends MUVideo_Form_Plugin_AbstractObjectSelector
{
    /**
     * Get filename of this file.
     * The information is used to re-establish the plugins on postback.
     *
     * @return string
     */
    public function getFilename()
    {
        return __FILE__;
    }

    /**
     * Load event handler.
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object
     * @param array            &$params Parameters passed from the Smarty plugin function
     *
     * @return void
     */
    public function load(Zikula_Form_View $view, &$params)
    {
        $this->processRequestData($view, 'GET');

        // load list items
        parent::load($view, $params);

        // preprocess selection: collect id list for related items
        $this->preprocessIdentifiers($view, $params);
    }

    /**
     * Entry point for customised css class.
     */
    protected function getStyleClass()
    {
        return 'z-form-relationlist';
    }

    /**
     * Decode event handler.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object
     *
     * @return void
     */
    public function decode(Zikula_Form_View $view)
    {
        parent::decode($view);

        // postprocess selection: reinstantiate objects for identifiers
        $this->processRequestData($view, 'POST');
    }
}
