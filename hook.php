<?php

/**
 * @return bool
 */
function plugin_fournitures_install()
{
    global $DB;

    include_once(GLPI_ROOT . "/plugins/fournitures/inc/profile.class.php");

    if (!TableExists("glpi_plugin_fournitures") && !TableExists("glpi_plugin_fournitures_fournituretypes")  && !TableExists("glpi_plugin_fournitures_fournituremodeles")  && !TableExists("glpi_plugin_fournitures_fournituremarques")) {
        $DB->runFile(GLPI_ROOT . "/plugins/fournitures/sql/empty-1.0.0.sql");
    }


    // Seuil bas alerte notification
    $query_id = "SELECT `id` FROM `glpi_notificationtemplates` WHERE `itemtype`='PluginFournituresFourniture' AND `name` = 'FournituresSeuil'";
    $result = $DB->query($query_id) or die($DB->error());
    $itemtype = $DB->result($result, 0, 'id');
    if (empty($itemtype)) {
        $query_id = "INSERT INTO `glpi_notificationtemplates`(`name`, `itemtype`, `date_mod`, `comment`, `css`) VALUES ('FournituresSeuil','PluginFournituresFourniture', NOW(),'','');";
        $result = $DB->query($query_id) or die($DB->error());
        $query_id = "SELECT `id` FROM `glpi_notificationtemplates` WHERE `itemtype`='PluginFournituresFourniture' AND `name` = 'FournituresSeuil'";
        $result = $DB->query($query_id) or die($DB->error());
        $itemtype = $DB->result($result, 0, 'id');
    }

    $query = "INSERT INTO `glpi_notificationtemplatetranslations`
                              VALUES(NULL, '" . $itemtype . "', '','Alerte seuil bas : fournitures',
                                     '##FOREACHfourniture##
                                      ##fourniture.name## 
                                      ##fourniture.type##
                                      Quantité : ##fourniture.quantite##
                                      &lt;/p&gt;', '##fourniture.name## 
                                      ##fourniture.type##
                                      Quantité : ##fourniture.quantite##
                                      ##ENDFOREACHfourniture##
                                      &lt;/p&gt;' );";
    $DB->query($query);

    $query = "INSERT INTO `glpi_notifications`
                                VALUES (NULL, 'Alerte seuil bas', 0, 'PluginFournituresFourniture', 'FournituresSeuil',
                                       'mail','" . $itemtype . "',
                                       '', 1, 1, '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "');";
    $DB->query($query);

    CronTask::Register('PluginFournituresFourniture', 'FournituresSeuil', DAY_TIMESTAMP);

    PluginFournituresProfile::initProfile();
    PluginFournituresProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
    $migration = new Migration("1.0.0");
    $migration->dropTable('glpi_plugin_fournitures_profiles');

    return true;
}

/**
 * @return bool
 */
function plugin_fournitures_uninstall()
{
    global $DB;

    include_once(GLPI_ROOT . "/plugins/fournitures/inc/profile.class.php");
    include_once(GLPI_ROOT . "/plugins/fournitures/inc/menu.class.php");

    $tables = array(
        "glpi_plugin_fournitures_fournitures",
        "glpi_plugin_fournitures_fournituretypes",
        "glpi_plugin_fournitures_fournituremarques",
        "glpi_plugin_fournitures_fournituremodeles",
        "glpi_plugin_fournitures_configs",
        "glpi_plugin_badges_notificationstates",
        "glpi_plugin_fournitures_requests"
    );

    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`;");
    }

    $tables_glpi = array(
        "glpi_displaypreferences",
        "glpi_logs",
        "glpi_notepads",
        "glpi_dropdowntranslations"
    );

    foreach ($tables_glpi as $table_glpi) {
        $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` LIKE 'PluginFournitures%';");
    }

    if (class_exists('PluginDatainjectionModel')) {
        PluginDatainjectionModel::clean(array('itemtype' => 'PluginFournituresFourniture'));
    }


   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
    foreach (PluginFournituresProfile::getAllRights() as $right) {
        $profileRight->deleteByCriteria(array('name' => $right['field']));
    }

    PluginFournituresMenu::removeRightsFromSession();
    PluginFournituresProfile::removeRightsFromSession();

    return true;
}

// Define dropdown relations
/**
 * @return array
 */
function plugin_fournitures_getDatabaseRelations()
{
    $plugin = new Plugin();
    if ($plugin->isActivated("fournitures")) {
        return array(
            "glpi_plugin_fournitures_fournituretypes" => array(
                "glpi_plugin_fournitures_fournitures" => "plugin_fournitures_fournituretypes_id"
            ),
            "glpi_plugin_fournitures_fournituremarques" => array(
                "glpi_plugin_fournitures_fournitures" => "plugin_fournitures_fournituremarques_id"
            ),
            "glpi_plugin_fournitures_fournituremodeles" => array(
                "glpi_plugin_fournitures_fournitures" => "plugin_fournitures_fournituremodeles_id"
            ),
            "glpi_entities" => array(
                "glpi_plugin_fournitures_fournitures" => "entities_id",
                "glpi_plugin_fournitures_fournituretypes" => "entities_id"
            )
        );
    } else {
        return array();
    }
}

// Define Dropdown tables to be manage in GLPI :
/**
 * @return array
 */
function plugin_fournitures_getDropdown()
{
    $plugin = new Plugin();
    if ($plugin->isActivated("fournitures")) {
        return array(
            "PluginFournituresFournitureType" => PluginFournituresFournitureType::getTypeName(2),
            "PluginFournituresFournitureMarque" => PluginFournituresFournitureMarque::getMarqueName(2),
            "PluginFournituresFournitureModele" => PluginFournituresFournitureModele::getModeleName(2)
        );
    } else {
        return array();
    }
}


/**
 *
 */
function plugin_datainjection_populate_fournitures()
{
    global $INJECTABLE_TYPES;
    $INJECTABLE_TYPES['PluginFournituresFournitureInjection'] = 'fournitures';
}
