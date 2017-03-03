<?php

//##############################
//
//   GENERAL SETTINGS
//
//#############################

global $domain;
$domain = 'example.com';

global $tandem_root_path;
$tandem_root_path = "https://$domain/root/path?";

global $tandem_home_path;
$tandem_home_path = "https://$domain/home/path?";

global $logfile;
$logfile = './tandem.log';

global $errorMessage;
$errorMessage = 'Sorry, something went wrong...';

global $organisationName;
$organisationName = 'Some Organisation';

//##############################
//
//   DEBUGGING
//
//#############################

global $debug;
$debug = 1;

global $showTitle;
$showTitle = false;

//##############################
//
//   DATABASE / SQL
//
//#############################

// DATABASE CONNECTION
global $mysql_server;
$mysql_server = 'localhost';
global $mysql_username;
$mysql_username = 'username';
global $mysql_password;
$mysql_password = 'password';

global $db_name;
$db_name = 'sprachtandem';

// TABLE DEFINITION
global $db_table_name;
$db_table_name = 'sprachtandem';

global $db_colName_id, $db_colName_name, $db_colName_alter, $db_colName_ort, $db_colName_email, $db_colName_datum,
$db_colName_geschlecht, $db_colName_spracheGes, $db_colName_spracheAng, $db_colName_skill, $db_colName_beschreibung, $db_colName_hash,
$db_colName_lang, $db_colName_released, $db_colName_antworten;

$db_colName_id = 'id';
$db_colName_name = 'name';
$db_colName_alter = 'alter';
$db_colName_ort = 'ort';
$db_colName_email = 'email';
$db_colName_datum = 'datum';
$db_colName_geschlecht = 'geschlecht';
$db_colName_spracheGes = 'spracheGes';
$db_colName_spracheAng = 'spracheAng';
$db_colName_skills = 'kenntnis';
$db_colName_beschreibung = 'beschreibung';
$db_colName_hash = 'hash';
$db_colName_lang = 'lang';
$db_colName_released = 'released';
$db_colName_antworten = 'antworten';

global $db_columns;
$db_columns = array(
	$db_colName_id => null,
	$db_colName_name => null,
	$db_colName_alter => null,
	$db_colName_ort => null,
	$db_colName_email => null,
	$db_colName_datum => null,
	$db_colName_geschlecht => null,
	$db_colName_spracheGes => null,
	$db_colName_spracheAng => null,
	$db_colName_skills => null,
	$db_colName_beschreibung> null,
	$db_colName_hash => null,
	$db_colName_lang => null,
	$db_colName_released => null,
	$db_colName_antworten => null);

global $table_page_size;
$table_page_size = 20; //25;


//##############################
//
//   E-MAIL
//
//#############################

global $email_from, $email_replyto, $email_orga;

$email_from = 'noreply@example.com';
$email_replyto = $email_from;
$email_orga = 'sprachtandem@example.com';

global $reminder_cyclic, $reminder_first;

$reminder_cyclic = 1; //30;
$reminder_first = 1; //3;
?>
