<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresFournitureMarque
 */
class PluginFournituresFournitureMarque extends CommonDropdown
{

    static $rightname = "dropdown";
    var $can_be_translated = true;

   /**
    * @param int $nb
    * @return translated
    */
    static function getMarqueName($nb = 0)
    {
        return _n('Marque de l\'fourniture', 'Marques de l\'fourniture', $nb, 'fournitures');
    }


}
