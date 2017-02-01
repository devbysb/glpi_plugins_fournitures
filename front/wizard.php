<?php

include('../../../inc/includes.php');

if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
   Html::header(PluginFournituresWizard::getTypeName(2), '', "assets", "pluginfournituresmenu");
} else {
   Html::helpHeader(PluginFournituresWizard::getTypeName(2));
}

$wizard = new PluginFournituresWizard();
$wizard->showMenu();

if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
   Html::footer();
} else {
   Html::helpFooter();
}