<?php
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


function setDefaultParams($params) {
	foreach ($params as $param)
	{
		if (!isset($_POST[$param])){
			if ($param == 'skills'){
				$_POST[$param] = "0";
			}
			else
			{
				$_POST[$param] = "";
			}
		}
	}
}

function e($s) {
	echo htmlspecialchars($s);
}

function icon($name, $className='', $alt='') {
	echo '<svg class="icon '.$className.'" title="'.$alt.'" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'
		. '<use xlink:href="./images/icons.svg#' . htmlspecialchars($name) . '"></use>'
		. '</svg>';
}

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
		$ret = getLabel('en');
	} elseif (strlen(strstr($sprache,'de'))>0){
		$ret = getLabel('de');
	} elseif (strlen(strstr($sprache,'fr'))>0){
		$ret = getLabel('fr');
	} elseif (strlen(strstr($sprache,'fa'))>0){
		$ret = getLabel('fa');
	} elseif (strlen(strstr($sprache,'ar'))>0){
		$ret = getLabel('ar');
	} elseif (strlen(strstr($sprache,'es'))>0){
		$ret = getLabel('es');
	} else {
		$ret = getLabel('de');
	}
	return $ret;
}

function l10nDirection($dir, $label)
{
	$rtl_map = array(
		'first' => 'last',
		'prev' => 'next',
		'next' => 'prev',
		'last' => 'first');

	if ($label['lang'] == 'fa' or $label['lang'] == 'ar'){
		return $rtl_map[$dir];
	} else {
		return $dir;
	}
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
