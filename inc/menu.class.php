<?php

/**
 * Class PluginFournituresMenu
 */
class PluginFournituresMenu extends CommonGLPI
{
    public static $rightname = 'plugin_fournitures';

   /**
    * @return translated
    */
    public static function getMenuName()
    {
        return _n('Fournitures', 'Fournitures', 2, 'fournitures');
    }

   /**
    * @return array
    */
    public static function getMenuContent()
    {
        $menu = array();
        $menu['title'] = self::getMenuName();
        $menu['page'] = "/plugins/fournitures/front/fourniture.php";
        $menu['links']['search'] = PluginFournituresFourniture::getSearchURL(false);
        if (PluginFournituresFourniture::canCreate()) {
            $menu['links']['add'] = PluginFournituresFourniture::getFormURL(false);
        }

        return $menu;
    }

    public static function removeRightsFromSession()
    {
        if (isset($_SESSION['glpimenu']['assets']['types']['PluginFournituresMenu'])) {
            unset($_SESSION['glpimenu']['assets']['types']['PluginFournituresMenu']);
        }
        if (isset($_SESSION['glpimenu']['assets']['content']['pluginfournituresmenu'])) {
            unset($_SESSION['glpimenu']['assets']['content']['pluginfournituresmenu']);
        }
    }
}
