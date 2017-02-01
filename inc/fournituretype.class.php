<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresFournitureType
 */
class PluginFournituresFournitureType extends CommonDropdown
{

    static $rightname = "dropdown";
    var $can_be_translated = true;

   /**
    * @param int $nb
    * @return translated
    */
    static function getTypeName($nb = 0)
    {
        return _n('Type d\'fourniture', 'Type d\'fourniture', $nb, 'fournitures');
    }

}
