<?php


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresFourniture
 */
class PluginFournituresFourniture extends CommonDBTM
{
    public $dohistory = true;
    public static $rightname = "plugin_fournitures";
    protected $usenotepad = true;

   /**
    * @param int $nb
    * @return translated
    */
    public static function getTypeName($nb = 0)
    {
        return _n('Fourniture', 'Fournitures', $nb, 'fournitures');
    }


   /**
    * @return array
    */
    public function getSearchOptions()
    {
        $tab = array();

        $tab['common'] = self::getTypeName(2);

        $tab[1]['table'] = $this->getTable();
        $tab[1]['field'] = 'name';
        $tab[1]['name'] = __('Nom');
        $tab[1]['datatype'] = 'itemlink';
        $tab[1]['itemlink_type'] = $this->getType();

        $tab[2]['table'] = 'glpi_plugin_fournitures_fournituretypes';
        $tab[2]['field'] = 'name';
        $tab[2]['name'] = __('Type');
        $tab[2]['datatype'] = 'dropdown';

        $tab[3]['table'] = 'glpi_plugin_fournitures_fournituremarques';
        $tab[3]['field'] = 'name';
        $tab[3]['name'] = __('Marque');
        $tab[3]['datatype'] = 'dropdown';

        $tab[4]['table'] = 'glpi_plugin_fournitures_fournituremodeles';
        $tab[4]['field'] = 'name';
        $tab[4]['name'] = __('Modèle');
        $tab[4]['datatype'] = 'dropdown';

        $tab[6]['table'] = $this->getTable();
        $tab[6]['field'] = 'quantite';
        $tab[6]['name'] = __('Quantité');
        $tab[6]['datatype'] = 'itemlink';
        $tab[6]['itemlink_type'] = $this->getType();

        $tab[7]['table'] = $this->getTable();
        $tab[7]['field'] = 'seuil';
        $tab[7]['name'] = __('Seuil d\'alerte');
        $tab[7]['datatype'] = 'itemlink';
        $tab[7]['itemlink_type'] = $this->getType();

        $tab[8]['table'] = $this->getTable();
        $tab[8]['field'] = 'comment';
        $tab[8]['name'] = __('Commentaires');
        $tab[8]['datatype'] = 'text';

        $tab[9]['table'] = $this->getTable();
        $tab[9]['field'] = 'date_mod';
        $tab[9]['name'] = __('Dernière modification');
        $tab[9]['datatype'] = 'datetime';
        $tab[9]['massiveaction'] = false;



        $tab[30]['table'] = $this->getTable();
        $tab[30]['field'] = 'id';
        $tab[30]['name'] = __('ID');
        $tab[30]['datatype'] = 'number';

        $tab[80]['table'] = 'glpi_entities';
        $tab[80]['field'] = 'completename';
        $tab[80]['name'] = __('Entité');
        $tab[80]['datatype'] = 'dropdown';

        $tab[81]['table'] = 'glpi_entities';
        $tab[81]['field'] = 'entities_id';
        $tab[81]['name'] = __('Entité') . "-" . __('ID');

        return $tab;
    }

    /**
    * @param array $options
    * @return array
    */
    public function defineTabs($options = array())
    {
        $ong = array();
        $this->addDefaultFormTab($ong);
        $this->addStandardTab('Notepad', $ong, $options);
        $this->addStandardTab('Log', $ong, $options);

        return $ong;
    }

   /**
    * @param $ID
    * @param array $options
    * @return bool
    */
    public function showForm($ID, $options = array())
    {
        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Nom') . "</td>";
            echo "<td>";
                Html::autocompletionTextField($this, "name");
            echo "</td>";

            echo "<td>" . __('Type') . "</td><td>";
            Dropdown::show(
                'PluginFournituresFournitureType',
                array(
                    'name' => "plugin_fournitures_fournituretypes_id",
                    'value' => $this->fields["plugin_fournitures_fournituretypes_id"]
                )
            );
            echo "</td>";
        echo "</tr>";


        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Marque') . "</td><td>";
                Dropdown::show(
                    'PluginFournituresFournitureMarque',
                    array(
                        'name' => "plugin_fournitures_fournituremarques_id",
                        'value' => $this->fields["plugin_fournitures_fournituremarques_id"]
                    )
                );
            echo "</td>";

            echo "<td>" . __('Modèle') . "</td><td>";
                Dropdown::show(
                    'PluginFournituresFournitureModele',
                    array(
                        'name' => "plugin_fournitures_fournituremodeles_id",
                        'value' => $this->fields["plugin_fournitures_fournituremodeles_id"]
                    )
                );
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Quantité') . "</td>";
            echo "<td>";
               echo "<input type='text' name='quantite' value='".$this->fields["quantite"]."' />";
            echo "</td>";
            echo "<td>" . __('Seuil d\'alerte') . "</td>";
            echo "<td>";
                echo "<input type='text' name='seuil' value='".$this->fields["seuil"]."' />";
            echo "</td>";
        echo "</tr>";


        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Commentaires') . "</td>";
            echo "<td class='center' colspan='3'>"
                    ."<textarea cols='115' rows='5' name='comment' >"
                        .$this->fields["comment"]
                    ."</textarea>";
            echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }


   //Massive Action
   /**
    * @param null $checkitem
    * @return an
    */
    public function getSpecificMassiveActions($checkitem = null)
    {
        $isadmin = static::canUpdate();
        $actions = parent::getSpecificMassiveActions($checkitem);

        if (Session::haveRight('transfer', READ && Session::isMultiEntitiesMode() && $isadmin)
         && Session::isMultiEntitiesMode()
         && $isadmin
        ) {
           $actions['PluginFournituresFourniture'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfer'] = __('Transfer');
        }
        return $actions;
    }


   /**
    * @param MassiveAction $ma
    * @return bool|false
    */
    public static function showMassiveActionsSubForm(MassiveAction $ma)
    {
        switch ($ma->getAction()) {
            case "transfer":
                Dropdown::show('Entity');
                echo Html::submit(_x('button', 'Post'), array('name' => 'massiveaction'));
                return true;
                break;
        }
        return parent::showMassiveActionsSubForm($ma);
    }

    /**
     * @since version 0.85
     *
     * @see CommonDBTM::processMassiveActionsForOneItemtype()
     * @param MassiveAction $ma
     * @param CommonDBTM $item
     * @param array $ids
     * @return nothing|void
     */
    public static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item, array $ids)
    {
        switch ($ma->getAction()) {
            case "transfer":
                $input = $ma->getInput();
                if ($item->getType() == 'PluginFournituresFourniture') {
                    foreach ($ids as $key) {
                        $item->getFromDB($key);
                        $values["id"] = $key;
                        $values["entities_id"] = $input['entities_id'];
                        if ($item->update($values)) {
                            $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                        } else {
                            $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                        }
                    }
                }
                break;
        }
        return;
    }


    static function queryAlertFournitures()
    {
        $query = "SELECT 
            glpi_plugin_fournitures_fournitures.name AS name, 
            glpi_plugin_fournitures_fournitures.quantite AS quantite, 
            glpi_plugin_fournitures_fournitures.seuil AS seuil,
            glpi_plugin_fournitures_fournituremarques.name AS marque,
            glpi_plugin_fournitures_fournituremodeles.name AS modele,
            glpi_plugin_fournitures_fournituretypes.name AS type,
            glpi_plugin_fournitures_fournitures.entities_id AS entities_id
         FROM `glpi_plugin_fournitures_fournitures`, `glpi_plugin_fournitures_fournituremarques`, `glpi_plugin_fournitures_fournituremodeles`, `glpi_plugin_fournitures_fournituretypes`
         WHERE `quantite` < `seuil`
         AND `is_deleted` = '0'
         AND  glpi_plugin_fournitures_fournitures.plugin_fournitures_fournituretypes_id = glpi_plugin_fournitures_fournituretypes.id
         AND  glpi_plugin_fournitures_fournitures.plugin_fournitures_fournituremarques_id = glpi_plugin_fournitures_fournituremarques.id
         AND  glpi_plugin_fournitures_fournitures.plugin_fournitures_fournituremodeles_id = glpi_plugin_fournitures_fournituremodeles.id";

        return $query;
    }

    /**
     * Tache planifiee pour vérifier les seuils.
     *
     * @param $task Object of CronTask class for log / stat
     *
     * @return interger
     *    >0 : done
     *    <0 : to be run again (not finished)
     *     0 : nothing to do
     */
    static function cronFournituresSeuil($task) {
        global $DB, $CFG_GLPI;

        $cron_status = 0;

        if (!$CFG_GLPI["use_mailing"]) {
            return 0;
        }

        $query_alert_fournitures = self::queryAlertFournitures();

        $message = "";
        foreach ($DB->request($query_alert_fournitures) as $data) {
            $data['entityName'] = Dropdown::getDropdownName("glpi_entities", $data['entities_id']);
            $fournitures = $data;
            $message .= $data['name']." ".$data['type']." ".$data['marque']." ".$data['modele']." ".$data['quantite']." ".$data['seuil']."<br>";
        }

        if (NotificationEvent::raiseEvent('FournituresSeuil', new PluginFournituresFourniture(), array('fournitures' => $fournitures))
        ) {
            $cron_status = 1;
            if ($task) {
                $task->log("Fournitures seuil alerte \n");
                $task->addVolume(1);
            } else {
                Session::addMessageAfterRedirect("Fournitures seuil alerte");
            }

        } else {
            if ($task) {
                $task->log("Fournitures seuil alerte : L'envoi du message a échoué.\n");
            } else {
                Session::addMessageAfterRedirect("Fournitures seuil alerte : L'envoi du message a échoué.\n", false, ERROR);
            }
        }

        return $cron_status;
    }


}
