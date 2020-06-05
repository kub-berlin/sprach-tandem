<?php declare(strict_types=1);

include './config-default.php';
include './config.php';

if ($debug == 1) {
    error_reporting(-1);
    ini_set('display_errors', '1');

    // E_NOTICE ist sinnvoll um uninitialisierte oder
    // falsch geschriebene Variablen zu entdecken
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
} else {
    error_reporting(0);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

include './functions_db.php';
include './functions_mail.php';
include './functions_forms.php';
include './functions_actions.php';


function setDefaultParams($params)
{
    foreach ($params as $param) {
        if (!isset($_POST[$param])) {
            $_POST[$param] = '';
        }
    }
}

function e($s)
{
    if (is_int($s) or is_float($s)) {
        echo $s;
    } elseif (!is_null($s)) {
        echo htmlspecialchars($s, ENT_SUBSTITUTE);
    }
}

function icon($name, $className='', $alt='')
{
    ?>
    <svg
        class="icon <?php e($className) ?>"
        title="<?php e($alt) ?>"
        viewBox="0 0 20 20"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink">
           <use xlink:href="./images/icons.svg#<?php e($name) ?>"></use>
    </svg>
    <?php
}

function alert($label, $success, $msg, $backLink)
{
    include 'templates/partials/alert.php';
}

function getEntry()
{
    if (isset($_GET['tid']) and is_numeric($_GET['tid'])) {
        $db_erg = db_getDataSet($GLOBALS['server'], $_GET['tid']);
        if (count($db_erg) > 0) {
            return $db_erg[0];
        }
    }

    http_response_code(404);
    echo '<h1>Error: Data not found.</h1>';
}

function getHash($entry)
{
    if (isset($_GET['a'])) {
        if ($entry[$GLOBALS['db_colName_hash']] === $_GET['a']) {
            return $_GET['a'];
        }
    }

    http_response_code(401);
    echo '<h1>'.htmlspecialchars($GLOBALS['errorMessage']).'</h1>';
}

function checkEntry()
{
    return $_POST['name'] != ''
        and $_POST['alter'] != ''
        and $_POST['geschlecht'] != ''
        and $_POST['skills'] != ''
        and $_POST['spracheAng'] != ''
        and $_POST['spracheGes'] != ''
        and $_POST['text'] != ''
        and $_POST['ort'] != ''
        and $_POST['email'] != ''
        and $_POST['datenschutz'][0] == 'ja'
        and $_POST['areYouHuman'] == ''
        and isValidEmail(strtolower($_POST['email']))
        and strtolower($_POST['email']) == strtolower($_POST['email_nochmal']);
}

//##############################
//
//   SPRACH-AUSWAHL
//
//#############################

function readcsv($file)
{
    $file_handle = fopen($file, 'r');
    if (empty($file_handle)) {
        echo 'Error opening file '.$file.'.';
    };
    while (!feof($file_handle)) {
        $lines[] = (fgetcsv($file_handle, 2048, ','));
    }
    fclose($file_handle);
    return $lines;
}

function getLabel($lang)
{
    $fallback = readcsv("translations/de.csv");

    // security: do not allow hackers open arbitrary files
    if (strlen($lang) == 2) {
        $t_data = readcsv("translations/$lang.csv");
    } else {
        return;
    }

    foreach ($t_data as $i => $tstring) {
        $key = str_replace(' ', '', $tstring[0]);
        if ($tstring[1] != '') {
            $ret[$key] = $tstring[1];
        } else {
            $ret[$key] = $fallback[$i][1];
        }
    }

    $ret['dir'] = ($ret['lang'] == 'fa' or $ret['lang'] == 'ar') ? 'rtl' : 'ltr';

    asort($ret);
    return $ret;
}

function setLanguage($sprache)
{
    // Zuordnung von Locale-Strings und Übergabe an selectLanguage
    if (strstr($sprache, 'en')) {
        $ret = getLabel('en');
    } elseif (strstr($sprache, 'de')) {
        $ret = getLabel('de');
    } elseif (strstr($sprache, 'fr')) {
        $ret = getLabel('fr');
    } elseif (strstr($sprache, 'fa')) {
        $ret = getLabel('fa');
    } elseif (strstr($sprache, 'ar')) {
        $ret = getLabel('ar');
    } elseif (strstr($sprache, 'es')) {
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

    if ($label['dir'] == 'rtl') {
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

    if ($fh != false) {
        fwrite($fh, date('Y-m-d H:i', time())." Log-File created\n");
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
    if ($fh != false) {
        if (fwrite($fh, date('Y-m-d H:i', time()).' '.$string."\n") == false) {
            if ($GLOBALS['debug'] == 1) {
                echo '<p>Error: open Logfile</p>';
            }
        }
        fclose($fh);
    } else {
        if ($GLOBALS['debug'] == 1) {
            echo '<p>Error: open Logfile</p>';
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

    if (count($db_erg) > 0) {
        writeLog('REMINDER NOT RELEASED'.$db_erg);
        foreach ($db_erg as $zeile) {
            $label = setLanguage($zeile['lang']);
            $sprache = $label['lang'];
            $name = $zeile[$GLOBALS['db_colName_name']];
            $id = $zeile[$GLOBALS['db_colName_id']];
            $hash = $zeile[$GLOBALS['db_colName_hash']];
            $to = $zeile[$GLOBALS['db_colName_email']];
            $subject = 'Reminder first: ';

            $body = 'Reminder Body';
            $gesendet = send_notification_add($email, $name, $id, $hash, $label);
            writeLog('REMINDER NOT RELEASED Email senden id:'.$id.' gesendet:'.$gesendet);
        }
    }
}

function reminder_Released($label)
{
    $db_erg = db_getReminderDatasetsReleased($GLOBALS['server']);

    if (count($db_erg) > 0) {
        writeLog('REMINDER CYCLIC '.$db_erg);
        foreach ($db_erg as $zeile) {
            echo 'schleife';
            $label = setLanguage($zeile['lang']);
            $sprache = $label['lang'];
            $name = $zeile[$GLOBALS['db_colName_name']];
            $id = $zeile[$GLOBALS['db_colName_id']];
            $hash = $zeile[$GLOBALS['db_colName_hash']];
            $to = $zeile[$GLOBALS['db_colName_email']];
            $subject = 'Reminder Released: ';

            $body = 'Reminder Body';
            $gesendet = send_reminder($to, $name, $id, $hash, $label);
            writeLog('REMINDER CYCLIC E-Mail senden id:'.$id.' gesendet:'.$gesendet);
        }
    }
}


function scheduleReminder($label)
{
    if (file_exists($GLOBALS['logfile'])) {
        if (date('n', time()) != date('n', filemtime($GLOBALS['logfile']))) {
            reminder_Released($label);
        }
        if (date('W', time()) != date('W', filemtime($GLOBALS['logfile']))) {
            reminder_notReleased($label);
        }
    } else {
        createLog();
    }
}


function sign($s)
{
    return hash_hmac('sha256', $s, $GLOBALS['secret']);
}


function csrfProtection()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) or sign($_POST['csrf_token']) !== $_COOKIE['csrf_token']) {
            writeLog('CSRF mismatch post:'.$_POST['csrf_token'].' cookie:'.$_COOKIE['csrf_token']);
            http_response_code(403);
            die();
        }
    }

    $token = bin2hex(random_bytes(5));
    $GLOBALS['csrf_token'] = $token;
    setcookie('csrf_token', sign($token));
}
