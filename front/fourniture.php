<?php
include('../../../inc/includes.php');

$plugin = new Plugin();
if ($plugin->isActivated("environment")) {
    Html::header(PluginFournituresFourniture::getTypeName(2), '', "assets", "pluginenvironmentdisplay", "fournitures");
} else {
    Html::header(PluginFournituresFourniture::getTypeName(2), '', "assets", "pluginfournituresmenu");
}

$fourniture = new PluginFournituresFourniture();
$fourniture->checkGlobal(READ);

if ($fourniture->canView()) {
    Search::show("PluginFournituresFourniture");

} else {
    Html::displayRightError();
}

Html::footer();
