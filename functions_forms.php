<?php

//##############################
//
//   FORMULARE
//
//#############################

function addTandemForm($label, $caller)
{
	setDefaultParams(array('name', 'alter', 'email', 'email_nochmal', 'ort', 'geschlecht', 'skills', 'text', 'spracheAng', 'spracheGes'));
	include 'partials/form_add_tandem.php';
}

function sendMessageForm($label, $caller)
{
	setDefaultParams(array('name', 'alter', 'email', 'email_nochmal', 'ort', 'geschlecht', 'text'));

	// Formulareintragungen liegen (noch) nicht vor
	include 'partials/form_send_message.php';
}


function filterLanguageForm($label, $caller)
{
	$filterAng = isset($_GET['filterAng']) ? $_GET['filterAng'] : $label['Table_filter_alle'];
	$filterGes = isset($_GET['filterGes']) ? $_GET['filterGes'] : $label['Table_filter_alle'];

	$db_erg_ang = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheAng']);
	$langs_ang = array();
	foreach ($db_erg_ang as $zeile) {
		$langs_ang[] = html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']]);
	}

	$db_erg_ges = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheGes']);
	$langs_ges = array();
	foreach ($db_erg_ges as $zeile) {
		$langs_ges[] = html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']]);
	}

	include 'partials/form_filter_lang.php';
}

function reportForm($label, $caller)
{
	if (isset($_POST['send']) and $_POST['send'] == "cancel")
	{
		header('Location: index.php?action=table&lang='.$label["lang"]);
	}

	setDefaultParams(array('name', 'email', 'email_nochmal', 'text'));

	// Formulareintragungen liegen (noch) nicht vor
	include 'partials/form_report.php';
}

?>
