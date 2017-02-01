<?php

if (!defined('GLPI_ROOT')){
    die("Sorry. You can't access directly to this file");
}
// Class NotificationTarget
class PluginFournitureNotificationTargetFourniture extends NotificationTarget {

    const REQUESTER = 30;
    const FournituresSeuil = "FournituresSeuil";

    function getEvents() {
        return array (self::FournituresSeuil => __('FournituresSeuil', 'fournitures'));
    }
    
    function getDatasForTemplate($event, $options=array()) {
        global $DB, $CFG_GLPI;

        $message = "";
        if($event == self::FournituresSeuil) {
            if (isset($options['fournitures'])) {
                foreach ($options['fournitures'] as $id => $fourniture) {
                    $tmp = array();
                    $tmp['##fourniture.entity##'] = Dropdown::getDropdownName('glpi_entities', $fourniture['entities_id']);
                    $tmp['##fourniture.name##'] = $fourniture['name'];
                    $tmp['##fourniture.quantite##'] = $fourniture['quantite'];

                    $this->datas['fournitures'][] = $tmp;

                    $message .= $fourniture['name']." ".$fourniture['type']." ".$fourniture['marque']." ".$fourniture['modele']." ".$fourniture['quantite']." ".$fourniture['seuil']."<br>";


                }
            }
        }

        mail("s.bertholon@beauvaisis.fr", "test", $message);
        //$this->datas['##example.name##'] = __('Example', 'example');
    }

    /**
     * Get additionnals targets for Tickets
     * @param string $event
     */
    function getAdditionalTargets($event = '')
    {
        if ($event == self::FournituresSeuil ) {
            $this->addTarget(self::REQUESTER, __("Requester"));
        }
    }

    /**
     * @param $data
     * @param $options
     */
    function getSpecificTargets($data, $options)
    {
        switch ($data['items_id']) {
            case self::REQUESTER:
                if (isset($this->options['fourniture'])) {
                    foreach ($this->options['fourniture'] as $fourniture) {
                        $this->target_object->fields['requesters_id'] = $fourniture['requesters_id'];
                        $this->getUserByField("requesters_id");
                    }
                }
                break;
        }
    }

}
?>