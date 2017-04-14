<?php

// Init the hooks of the plugins -Needed
function plugin_init_fournitures()
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['fournitures'] = true;
    $PLUGIN_HOOKS['change_profile']['fournitures'] = array('PluginFournituresProfile', 'initProfile');

    if (Session::getLoginUserID()) {
        Plugin::registerClass('PluginFournituresFourniture', array(
            'linkuser_types' => true
        ));


        Plugin::registerClass('PluginFournituresProfile', array('addtabon' => 'Profile'));
        //Plugin::registerClass('PluginFournituresSeuil', array('addtabon' => 'CronTask'));

        if (class_exists('PluginResourcesResource')) {
            PluginResourcesResource::registerType('PluginFournituresFourniture');
        }

        $plugin = new Plugin();
        if (!$plugin->isActivated('environment') && Session::haveRight("plugin_fournitures", READ)) {
            $PLUGIN_HOOKS['menu_toadd']['fournitures'] = array('assets' => 'PluginFournituresMenu');
        }

        if (Session::haveRight("plugin_fournitures", UPDATE)) {
            $PLUGIN_HOOKS['use_massive_action']['fournitures'] = 1;
        }

        $PLUGIN_HOOKS['redirect_page']['fournitures'] = 'front/wizard.php';
    }
}

// Get the name and the version of the plugin - Needed

/**
 * @return array
 */
function plugin_version_fournitures()
{
    return array(
        'name'           => _n('Fourniture', 'Fournitures', 2, 'fournitures'),
        'version'        => '1.0.0',
        'author'         => "Dev",
        'license'        => 'GPLv2+',
        'homepage'       => 'https://github.com/eldiablo62/glpi_plugins_fournitures.git',
        'minGlpiVersion' => '9.1',
    );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
/**
 * @return bool
 */
function plugin_fournitures_check_prerequisites()
{
    if (version_compare(GLPI_VERSION, '9.1', 'lt') || version_compare(GLPI_VERSION, '9.2', 'ge')) {
        _e('This plugin requires GLPI >= 9.1', 'fournitures');

        return false;
    }

    return true;
}

// Uninstall process for plugin : need to return true if succeeded
//may display messages or add to message after redirect
/**
 * @return bool
 */
function plugin_fournitures_check_config()
{
    return true;
}

/**
 * @param $types
 *
 * @return mixed
 */
function plugin_datainjection_migratetypes_fournitures($types)
{
    $types[1600] = 'PluginFournituresFourniture';

    return $types;
}
