<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresFournitureModele
 */
class PluginFournituresFournitureModele extends CommonDropdown
{

    static $rightname = "dropdown";
    var $can_be_translated = true;

    /**
     * @param int $nb
     *
     * @return translated
     */
    static function getModeleName($nb = 0)
    {
        return _n('Modèle de l\'fourniture', 'Modèles de l\'fourniture', $nb, 'fournitures');
    }
}
