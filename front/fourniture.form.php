<?php
include('../../../inc/includes.php');

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$fourniture = new PluginFournituresFourniture();

if (isset($_POST["add"])) {

   $fourniture->check(-1, CREATE, $_POST);
   $newID = $fourniture->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($fourniture->getFormURL() . "?id=" . $newID);
   }
   Html::back();

} else if (isset($_POST["delete"])) {

   $fourniture->check($_POST['id'], DELETE);
   $fourniture->delete($_POST);
   $fourniture->redirectToList();

} else if (isset($_POST["restore"])) {

   $fourniture->check($_POST['id'], PURGE);
   $fourniture->restore($_POST);
   $fourniture->redirectToList();

} else if (isset($_POST["purge"])) {

   $fourniture->check($_POST['id'], PURGE);
   $fourniture->delete($_POST, 1);
   $fourniture->redirectToList();

} else if (isset($_POST["update"])) {

   $fourniture->check($_POST['id'], UPDATE);
   $fourniture->update($_POST);
   Html::back();

} else {

   $fourniture->checkGlobal(READ);

   $plugin = new Plugin();
   if ($plugin->isActivated("environment")) {
      Html::header(PluginFournituresFourniture::getTypeName(2), '', "assets", "pluginenvironmentdisplay", "fournitures");
   } else {
      Html::header(PluginFournituresFourniture::getTypeName(2), '', "assets", "pluginfournituresmenu");
   }
   $fourniture->display($_GET);

   Html::footer();
}