<?php

//##############################
//
//   ACTION TABLE
//
//#############################

function actionTable($label)
{
    $page = (isset($_GET['page']) and $_GET['page'] != '') ? intval($_GET['page']): 0;
    $filterAng = isset($_GET['filterAng']) ? $_GET['filterAng'] : $label['Table_filter_alle'];
    $filterGes = isset($_GET['filterGes']) ? $_GET['filterGes'] : $label['Table_filter_alle'];
    $anzahl = db_countTableData($GLOBALS['server'], $filterAng, $filterGes, $label);
    $anzahl_pages = ceil($anzahl / $GLOBALS['table_page_size']);

    $db_erg = db_selectTableData($GLOBALS['server'], $filterAng, $filterGes, $label, $page);

    include 'templates/table.php';
}


//##############################
//
//   ACTION ADD
//
//#############################

function actionAdd($label)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' and checkEntry()) {
        $name = $_POST['name'];
        $email = strtolower($_POST['email']);

        $add_erg = db_add_dataset(
            $GLOBALS['server'],
            $name,
            $_POST['alter'],
            $_POST['geschlecht'],
            $_POST['skills'],
            $_POST['spracheAng'],
            $_POST['spracheGes'],
            $_POST['text'],
            $_POST['ort'],
            $email,
            $_GET['lang']);

        if ($add_erg['db_erg']) {
            $gesendet = send_notification_add($email, $name, $add_erg['id'], $add_erg['hash'], $label);

            if ($gesendet == 1) {
                http_response_code(201);
                alert($label, true, $label['Add_gesendet'], 'index.php?action=table&lang='.$label['lang']);
            } else {
                http_response_code(500);
                alert($label, false, $label['Add_nichtGesendet'], 'index.php?action=table&lang='.$label['lang']);
            }
        } else {
            http_response_code(400);
            addTandemForm($label, 'index.php?action=table&lang='.$label['lang']);
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            http_response_code(400);
        }

        echo '<p>'.$label['Add_intro'].'</p>';
        addTandemForm($label, 'index.php?action=table&lang='.$label['lang']);
    }
}


//##############################
//
//   ACTION VIEW
//
//#############################

function actionView($label)
{
    if ($zeile = getEntry()) {
        $id = $_GET['tid'];
        $senden = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            and $_POST['name'] != ''
            and $_POST['alter'] != ''
            and $_POST['geschlecht'] != ''
            and $_POST['ort'] != ''
            and $_POST['email'] != ''
            and $_POST['datenschutz'][0] == 'ja'
            and $_POST['areYouHuman'] == ''
            and isValidEmail(strtolower($_POST['email']))
            and strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])) {
            $senden = true;

            $label_mail = setLanguage($zeile['lang']);
            $to = $zeile[$GLOBALS['db_colName_email']];

            $gesendet = send_notification_view(
                $to,
                $zeile[$GLOBALS['db_colName_name']],
                $_POST['name'],
                $zeile[$GLOBALS['db_colName_spracheAng']],
                $zeile[$GLOBALS['db_colName_spracheGes']],
                $_POST['alter'],
                $_POST['geschlecht'],
                $_POST['ort'],
                strtolower($_POST['email']),
                $_POST['text'],
                $label_mail);

            if ($gesendet == 1) {
                db_incr_answers($GLOBALS['server'], $zeile[$GLOBALS['db_colName_id']]);
            } else {
                http_response_code(500);
            }
        }

        include 'templates/view.php';
    }
}

//##############################
//
//   ACTION EDIT
//
//#############################

function actionEdit($label)
{
    if (($zeile = getEntry()) and ($hash = getHash($zeile))) {
        $id = $_GET['tid'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' and checkEntry()) {
            db_edit_dataset(
                $GLOBALS['server'],
                $_POST['name'],
                $id,
                $_POST['alter'],
                $_POST['geschlecht'],
                $_POST['skills'],
                $_POST['spracheAng'],
                $_POST['spracheGes'],
                $_POST['text'],
                $_POST['ort'],
                strtolower($_POST['email']));

            alert($label, true, $label['Edit_ok'], 'index.php?action=view&lang='.$label['lang'].'&tid='.$id);
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                http_response_code(400);
            }

            setDefaultParams(array('name', 'alter', 'geschlecht', 'skills', 'spracheAng', 'spracheGes', 'text', 'ort', 'email'));
            $_POST['name']          = $_POST['name']          == '' ? $zeile[$GLOBALS['db_colName_name']]         : $_POST['name'];
            $_POST['alter']         = $_POST['alter']         == '' ? $zeile[$GLOBALS['db_colName_alter']]        : $_POST['alter'];
            $_POST['geschlecht']    = $_POST['geschlecht']    == '' ? $zeile[$GLOBALS['db_colName_geschlecht']]   : $_POST['geschlecht'];
            $_POST['skills']        = $_POST['skills']        == '' ? $zeile[$GLOBALS['db_colName_skills']]       : $_POST['skills'];
            $_POST['spracheAng']    = $_POST['spracheAng']    == '' ? $zeile[$GLOBALS['db_colName_spracheAng']]   : $_POST['spracheAng'];
            $_POST['spracheGes']    = $_POST['spracheGes']    == '' ? $zeile[$GLOBALS['db_colName_spracheGes']]   : $_POST['spracheGes'];
            $_POST['text']          = $_POST['text']          == '' ? $zeile[$GLOBALS['db_colName_beschreibung']] : $_POST['text'];
            $_POST['ort']           = $_POST['ort']           == '' ? $zeile[$GLOBALS['db_colName_ort']]          : $_POST['ort'];
            $_POST['email']         = $_POST['email']         == '' ? $zeile[$GLOBALS['db_colName_email']]        : $_POST['email'];

            addTandemForm($label, 'index.php?action=view&lang='.$label['lang'].'&tid='.$id);
        }
    }
}

//##############################
//
//   ACTION DELETE
//
//#############################

function actionDelete($label)
{
    if (($zeile = getEntry()) and ($hash = getHash($zeile))) {
        $id = $_GET['tid'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db_erg = db_delete_DataSet($GLOBALS['server'], $id, $hash);
            if ($db_erg > 0) {
                alert($label, true, $label['deleteDataset'], 'index.php?action=table&lang='.$label['lang']);
            } else {
                http_response_code(500);
                alert($label, false, $GLOBALS['errorMessage'], 'index.php?action=table&lang='.$label['lang']);
            }
        } else {
            include 'templates/delete.php';
        }
    }
}


//##############################
//
//   ACTION RELEASE
//
//#############################

function actionRelease($label)
{
    if (($zeile = getEntry()) and ($hash = getHash($zeile))) {
        $id = $_GET['tid'];

        $db_erg = db_release_DataSet($GLOBALS['server'], $id, $hash);
        if ($db_erg) {
            alert($label, true, $label['releaseDataset'], 'index.php?action=view&lang='.$label['lang'].'&tid='.$id);
        } else {
            http_response_code(500);
            alert($label, false, $GLOBALS['errorMessage'], 'index.php?action=view&lang='.$label['lang'].'&tid='.$id);
        }
    }
}

//##############################
//
//   ACTION STATISTIC
//
//#############################


function actionStatistic($label)
{
    if (!isset($_GET['t'])) {
        $_GET['t'] = 'always';
    }

    $db_erg = db_get_langPairs($GLOBALS['server'], $_GET['t'] === 'year');
    $sum_replies = 0;
    $sum_count = 0;
    $sums = array();

    foreach ($db_erg as $zeile) {
        $_replies = db_sum_answers($GLOBALS['server'], $zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']], $_GET['t'] === 'year');
        $replies[] = $_replies;
        $sum_replies += $_replies;
        $sum_count += $zeile['count'];
    }

    include 'templates/stat.php';
}

//##############################
//
//   ACTION REPORT
//
//#############################

function actionReport($label)
{
    if ($zeile = getEntry()) {
        $id = $_GET['tid'];
        $senden = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            and $_POST['text'] != ''
            and $_POST['areYouHuman'] == ''
            and (
                $_POST['email'] == ''
                or (
                    isValidEmail($_POST['email'])
                    and strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])
                )
            )) {
            $senden = true;

            $name = $_POST['name'];
            $email = strtolower($_POST['email']);
            $text = $_POST['text'];

            $label_mail = setLanguage('de');

            $to = $GLOBALS['email_orga'];

            $gesendet = send_notification_report(
                $to,
                $name,
                $email,
                $text,
                $zeile[$GLOBALS['db_colName_name']],
                $zeile[$GLOBALS['db_colName_id']],
                $zeile[$GLOBALS['db_colName_beschreibung']],
                $label_mail);

            if (!$gesendet) {
                http_response_code(500);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            http_response_code(400);
        }

        include 'templates/report.php';
    }
}
