<?php
mb_internal_encoding("UTF-8");

include './config-default.php';
include './config.php';

if ($debug == 1)
{
	error_reporting(-1);
	ini_set('display_errors', 1);

	// E_NOTICE ist sinnvoll um uninitialisierte oder
	// falsch geschriebene Variablen zu entdecken
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
} else {
	error_reporting(0);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}

include './functions_db.php';
include './functions_mail.php';
include './functions_forms.php';
include './functions_actions.php';


//##############################
//
//   SPRACH-AUSWAHL
//
//#############################

function readcsv($file) {
	$file_handle = fopen($file, 'r');
	if (empty($file_handle)) { echo 'Error opening file '.$file.'.'; };
	while (!feof($file_handle) ) {
		$lines[] = (fgetcsv($file_handle, 2048, ','));
	}
	fclose($file_handle);
	return $lines;
}

function getLabel($lang)
{
	$t_data = readcsv('uebersetzung.csv');
	$t_lang = 1;

	$i = 0;
	foreach ($t_data[0] as $spalte){
		if ($spalte === $lang){
			$t_lang = $i;
		}
		$i = $i + 1;
	}
	/*if ($t_lang = 4)
		$t_lang = 2;*/
	foreach ($t_data as $tstring) {
		if ($tstring[$t_lang] != '') {
			$ret[(str_replace(' ', '', $tstring[0]))] = ($tstring[$t_lang]);
		} else {
			$ret[(str_replace(' ', '', $tstring[0]))] = ($tstring[1]);
		}
	}
	asort($ret);
	return $ret;
}

function setLanguage($sprache)
{
	// Zuordnung von Locale-Strings und Übergabe an selectLanguage
	if (strlen(strstr($sprache,'en'))>0){
		echo '<div align="left">';
		$ret = getLabel('en');
	} elseif (strlen(strstr($sprache,'de'))>0){
		echo '<div align="left">';
		$ret = getLabel('de');

	} elseif (strlen(strstr($sprache,'fr'))>0){
		echo '<div align="left">';
		$ret = getLabel('fr');
	} elseif (strlen(strstr($sprache,'fa'))>0){
		echo '<div align="right">';
		$ret = getLabel('fa');
	} elseif (strlen(strstr($sprache,'ar'))>0){
		echo '<div align="right">';
		$ret = getLabel('ar');
	} elseif (strlen(strstr($sprache,'es'))>0){
		echo '<div align="left">';
		$ret = getLabel('es');
	} else {
		echo '<div align="left">';
		$ret = getLabel('de');
	}
	return $ret;
}


//##############################
//
//   LOG-FILE
//
//#############################

function createLog()
{
	$logFile = $GLOBALS['logfile'];
	$fh = fopen($logFile, 'w');

	if ($fh != false){
		fwrite($fh, date("Y-m-d H:i",time())." Log-File created\n");
		fclose($fh);
		$ret = true;
	} else {
		$ret = false;
	}
	return $ret;
}

function writeLog($string)
{
	$ret = -1;
	$logFile = $GLOBALS['logfile'];
	$fh = fopen($logFile, 'a');
	if ($fh != false){
		if (fwrite($fh, date("Y-m-d H:i",time())." ".$string."\n") == false){
			if ($GLOBALS['debug'] == 1)
			{
				echo "<p>Error: open Logfile</p>";
			}
		}
		fclose($fh);
	} else {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>Error: open Logfile</p>";
		}
	}
}

//##############################
//
//   REMINDER
//
//#############################

function reminder_notReleased($label)
{
	$db_erg = db_getReminderDatasetsNotReleased($GLOBALS['server']);

	if (count($db_erg) > 0)
	{
		writeLog('REMINDER NOT RELEASED'.$db_erg);
		foreach ($db_erg as $zeile) {
			$label = setLanguage($zeile["lang"]);
			$sprache = strip_tags($label['lang']);
			$name = $zeile[$GLOBALS['db_colName_name']];
			$id = $zeile[$GLOBALS['db_colName_id']];
			$hash = $zeile[$GLOBALS['db_colName_hash']];
			$to = $zeile[$GLOBALS['db_colName_email']];
			$subject = "Reminder first: ";

			$body = "Reminder Body";
			$gesendet = send_notification_add($email, $name, $id, $hash, $label);
			writeLog('REMINDER NOT RELEASED Email senden id:'.$id.' gesendet:'.$gesendet);
		}
	}
}

function reminder_Released($label)
{

	$db_erg = db_getReminderDatasetsReleased($GLOBALS['server']);

	if (count($db_erg) > 0)
	{
		writeLog('REMINDER CYCLIC '.$db_erg);
		echo "hallo";
		foreach ($db_erg as $zeile) {
			echo "schleife";
			$label = setLanguage($zeile["lang"]);
			$sprache = strip_tags($label['lang']);
			$name = $zeile[$GLOBALS['db_colName_name']];
			$id = $zeile[$GLOBALS['db_colName_id']];
			$hash = $zeile[$GLOBALS['db_colName_hash']];
			$to = $zeile[$GLOBALS['db_colName_email']];
			$subject = "Reminder Released: ";

			$body = "Reminder Body";
			$gesendet = send_reminder($to, $name, $id, $hash, $label);
			writeLog('REMINDER CYCLIC E-Mail senden id:'.$id.' gesendet:'.$gesendet);
		}
	}
}


function scheduleReminder($label)
{
	if (file_exists($GLOBALS['logfile'])){
		if (date("n",time()) != date("n",filemtime($GLOBALS['logfile'])))
		{
			reminder_Released($label);
		}
		if (date("W",time()) != date("W",filemtime($GLOBALS['logfile'])))
		{
			reminder_notReleased($label);
		}
	} else {
		createLog();
	}
}

?>
