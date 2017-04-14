<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresProfile
 */
class PluginFournituresProfile extends CommonDBTM
{
    static $rightname = "profile";

    /**
     * @param CommonGLPI $item
     * @param int        $withtemplate
     *
     * @return string|translated
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item->getType() == 'Profile') {
            return PluginFournituresFourniture::getTypeName(2);
        }

        return '';
    }

    /**
     * @param CommonGLPI $item
     * @param int        $tabnum
     * @param int        $withtemplate
     *
     * @return bool
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item->getType() == 'Profile') {
            $ID = $item->getID();
            $prof = new self();

            self::addDefaultProfileInfos(
                $ID,
                array(
                    'plugin_fournitures' => 0
                )
            );
            $prof->showForm($ID);
        }

        return true;
    }

    /**
     * @param $ID
     */
    static function createFirstAccess($ID)
    {
        //85
        self::addDefaultProfileInfos(
            $ID,
            array(
                'plugin_fournitures' => 127
            ),
            true
        );
    }

    /**
     * @param      $profiles_id
     * @param      $rights
     * @param bool $drop_existing
     *
     * @internal param $profile
     */
    static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing = false)
    {
        $profileRight = new ProfileRight();
        foreach ($rights as $right => $value) {
            if (countElementsInTable(
                'glpi_profilerights',
                "`profiles_id`='$profiles_id' AND `name`='$right'"
            )
            && $drop_existing
            ) {
                $profileRight->deleteByCriteria(array('profiles_id' => $profiles_id, 'name' => $right));
            }
            if (!countElementsInTable(
                'glpi_profilerights',
                "`profiles_id`='$profiles_id' AND `name`='$right'"
            )
            ) {
                $myright['profiles_id'] = $profiles_id;
                $myright['name'] = $right;
                $myright['rights'] = $value;
                $profileRight->add($myright);

                //Add right to the current session
                $_SESSION['glpiactiveprofile'][$right] = $value;
            }
        }
    }

    /**
     * Show profile form
     *
     * @param int  $profiles_id
     * @param bool $openform
     * @param bool $closeform
     *
     * @return nothing
     * @internal param int $items_id id of the profile
     * @internal param value $target url of target
     *
     */
    function showForm($profiles_id = 0, $openform = true, $closeform = true)
    {

        echo "<div class='firstbloc'>";
        if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE)))
            && $openform
        ) {
            $profile = new Profile();
            echo "<form method='post' action='" . $profile->getFormURL() . "'>";
        }

        $profile = new Profile();
        $profile->getFromDB($profiles_id);
        $rights = $this->getAllRights();
        $profile->displayRightsChoiceMatrix(
            $rights,
            array(
                'canedit'       => $canedit,
                'default_class' => 'tab_bg_2',
                'title'         => __('General')
            )
        );

        if ($canedit
            && $closeform
        ) {
            echo "<div class='center'>";
            echo Html::hidden('id', array('value' => $profiles_id));
            echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
            echo "</div>\n";
            Html::closeForm();
        }
        echo "</div>";

        return;
    }

    /**
     * @param bool $all
     *
     * @return array
     */
    static function getAllRights($all = false)
    {
        $rights = array(
            array(
                'itemtype' => 'PluginFournituresFourniture',
                'label'    => _n('Fourniture', 'Fournitures', 2, 'fournitures'),
                'field'    => 'plugin_fournitures'
            )
        );

        if ($all) {
            $rights[] = array(
                'itemtype' => 'PluginFournituresFourniture'
            );
        }

        return $rights;
    }

    /**
     * Init profiles
     *
     * @param $old_right
     *
     * @return int
     */

    static function translateARight($old_right)
    {
        switch ($old_right) {
            case '':
                return 0;
            case 'r':
                return READ;
            case 'w':
                return ALLSTANDARDRIGHT + READNOTE + UPDATENOTE;
            case '0':
            case '1':
                return $old_right;
            default:
                return 0;
        }
    }

    /**
     * @since 0.85
     * Migration rights from old system to the new one for one profile
     *
     * @param $profiles_id the profile ID
     *
     * @return bool
     */
    static function migrateOneProfile($profiles_id)
    {
        global $DB;
        //Cannot launch migration if there's nothing to migrate...

        if (!TableExists('glpi_plugin_fournitures_profiles')) {
            return true;
        }

        foreach ($DB->request('glpi_plugin_fournitures_profiles', "`profiles_id`='$profiles_id'") as $profile_data) {
            $matching = array(
                'fournitures' => 'plugin_fournitures'
            );
            $current_rights = ProfileRight::getProfileRights($profiles_id, array_values($matching));
            foreach ($matching as $old => $new) {
                if (!isset($current_rights[$old])) {
                    $query = "UPDATE `glpi_profilerights` 
                             SET `rights`='" . self::translateARight($profile_data[$old]) . "' 
                             WHERE `name`='$new' AND `profiles_id`='$profiles_id'";
                    $DB->query($query);
                }
            }
        }

        return;
    }

    /**
     * Initialize profiles, and migrate it necessary
     */
    static function initProfile()
    {
        global $DB;
        //Migration old rights in new ones
        foreach ($DB->request("SELECT `id` FROM `glpi_profiles`") as $prof) {
            self::migrateOneProfile($prof['id']);
        }
        foreach ($DB->request("SELECT *
                           FROM `glpi_profilerights` 
                           WHERE `profiles_id`='" . $_SESSION['glpiactiveprofile']['id'] . "' 
                              AND `name` LIKE '%plugin_fournitures%'") as $prof) {
            $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights'];
        }
    }


    static function removeRightsFromSession()
    {
        foreach (self::getAllRights(true) as $right) {
            if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
                unset($_SESSION['glpiactiveprofile'][$right['field']]);
            }
        }
    }
}
