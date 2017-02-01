<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFournituresFournitureInjection
 */
class PluginFournituresFournitureInjection extends PluginFournituresFourniture
   implements PluginDatainjectionInjectionInterface {

   /**
    * @return mixed
    */
    static function getTable()
    {
        $parenttype = get_parent_class();
        return $parenttype::getTable();
    }

   /**
    * @return bool
    */
    function isPrimaryType()
    {
        return true;
    }

   /**
    * @return array
    */
    function connectedTo()
    {
        return array();
    }

   /**
    * @param string $primary_type
    * @return array|the
    */
    function getOptions($primary_type = '')
    {
        $tab = Search::getOptions(get_parent_class($this));

        //Specific to location
        $tab[4]['checktype'] = 'date';
        $tab[5]['checktype'] = 'date';

        //Remove some options because some fields cannot be imported
        $notimportable = array(11,30,80);
        $options['ignore_fields'] = $notimportable;
        $options['displaytype'] = array("dropdown"       => array(2,6,7),
                                      "user"           => array(10),
                                      "multiline_text" => array(8),
                                      "date"           => array(4,5),
                                      "bool"           => array(9));

        $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

        return $tab;
    }

   /**
    * Standard method to delete an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param array $values
    * @param array|options $options
    * @return an
    * @internal param fields $fields to add into glpi
    * @internal param options $options used during creation
    */
    function deleteObject($values=array(), $options=array())
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->deleteObject();
        return $lib->getInjectionResults();
    }

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param array|fields $values
    * @param array|options $options
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    * @internal param fields $values to add into glpi
    * @internal param options $options used during creation
    */
    function addOrUpdateObject($values=array(), $options=array())
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }
}