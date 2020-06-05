<?php

//##############################
//
//   GENERAL SETTINGS
//
//#############################

$domain = 'example.com';
$tandem_root_path = "https://$domain/root/path?";
$tandem_home_path = "https://$domain/home/path?";
$external_css = "https://$domain/static/style-%s.css";
$logfile = './tandem.log';
$errorMessage = 'Sorry, something went wrong...';
$organisationName = 'Some Organisation';
$secret = 'changeme';

//##############################
//
//   DEBUGGING
//
//#############################

$debug = 1;
$showTitle = false;

//##############################
//
//   DATABASE / SQL
//
//#############################

// DATABASE CONNECTION
$db_dsn = 'sqlite:db.sqlite';
// $db_dsn = 'mysql:host=localhost;dbname=sprachtandem';
$db_username = null;
$db_password = null;

// TABLE DEFINITION
$db_table_name = 'sprachtandem';

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

$table_page_size = 20; //25;


//##############################
//
//   E-MAIL
//
//#############################

$email_from = 'noreply@example.com';
$email_replyto = $email_from;
$email_orga = 'sprachtandem@example.com';
$email_blocklist = array();

$reminder_cyclic = 1; //30;
$reminder_first = 1; //3;
$delete_after = '2 YEAR';
$delete_unreleased_after = '2 MONTH';
