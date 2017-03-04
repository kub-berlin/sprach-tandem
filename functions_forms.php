<?php

//##############################
//
//   FORMULARE
//
//#############################

function addTandemForm($label)
{
	setDefaultParams(array('name', 'alter', 'email', 'ort', 'geschlecht', 'skills', 'text', 'spracheAng', 'spracheGes'));
	$_POST['email_nochmal'] = '';
	include 'templates/forms/add_tandem.php';
}

function sendMessageForm($label)
{
	setDefaultParams(array('name', 'alter', 'email', 'ort', 'geschlecht', 'text'));
	$_POST['email_nochmal'] = '';

	// Formulareintragungen liegen (noch) nicht vor
	include 'templates/forms/send_message.php';
}


function filterLanguageForm($label)
{
	$filterAng = isset($_GET['filterAng']) ? $_GET['filterAng'] : $label['Table_filter_alle'];
	$filterGes = isset($_GET['filterGes']) ? $_GET['filterGes'] : $label['Table_filter_alle'];

	$db_erg_ang = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheAng']);
	$langs_ang = array();
	foreach ($db_erg_ang as $zeile) {
		$langs_ang[] = $zeile[$GLOBALS['db_colName_spracheAng']];
	}

	$db_erg_ges = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheGes']);
	$langs_ges = array();
	foreach ($db_erg_ges as $zeile) {
		$langs_ges[] = $zeile[$GLOBALS['db_colName_spracheGes']];
	}

	include 'templates/forms/filter_lang.php';
}

function reportForm($label)
{
	setDefaultParams(array('name', 'email', 'text'));
	$_POST['email_nochmal'] = '';

	// Formulareintragungen liegen (noch) nicht vor
	include 'templates/forms/report.php';
}

?>
