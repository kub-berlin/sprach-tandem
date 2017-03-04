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

	include 'partials/table.php';
}


//##############################
//
//   ACTION ADD
//
//#############################

function actionAdd($label){
	$senden = false;

	session_start();

	if (!isset($_POST["skills"]) or !isset($_SESSION['form_submitted'])){
		$_SESSION['form_submitted'] = false;
	}

	if(($_SESSION['form_submitted'] == true))
	{
		die('You have already submitted the form.');
	}
	else
	{
		if ((isset($_POST['geschlecht']) OR isset($_POST['skills']) OR isset($_POST['name']) OR
			isset($_POST['email']) OR isset($_POST['ort']) OR isset($_POST['spracheAng']) OR
			isset($_POST['spracheGes']) OR isset($_POST['text']) OR isset($_POST['alter']) OR isset($_POST['datenschutz'])))
		{
			if (($_POST['geschlecht'] == "" OR $_POST['skills'] == "" OR $_POST['name'] == "" OR
					($_POST['email'] == "" OR $_POST['ort'] == "" OR $_POST['spracheAng'] == "" OR $_POST['spracheGes'] == "" OR
					$_POST['text'] == "" OR $_POST['alter'] == "" OR $_POST['datenschutz'][0] != "ja")
					OR
					!($_POST['areYouHuman'] == '')
					OR
					(!isValidEmail(strtolower($_POST['email'])) or !(strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])))))
					{
						$senden = false;
					} else {
						$senden = true;
					}
		} else {
			$senden = false;

		}
		if ($senden == false) {
			echo '<p>'.$label['Add_intro'].'</p>';
			// Wenn noch nicht alles ausgefüllt ist:
			addTandemForm($label, "index.php?action=add&lang=".$label['lang']);
		} else
		{

			$name = strip_tags($_POST['name']);
			$alter =  strip_tags($_POST['alter']);
			$email = strip_tags(strtolower($_POST['email']));
			$ort = strip_tags($_POST['ort']);
			$name = strip_tags($_POST['name']);
			$geschlecht = strip_tags($_POST['geschlecht']);
			$skills = strip_tags($_POST['skills']);
			$sprache = strip_tags($label['lang']);

			foreach ($label as $key => $value) {
				if (strpos($key, 'sprache_') === 0 ){
					if ($key == strip_tags($_POST['spracheAng'])){
						$spracheAng = $key;
					}
					if ($key == strip_tags($_POST['spracheGes'])){
						$spracheGes = $key;
					}
				}
			}
			$beschreibung = strip_tags(($_POST['text']));

			$add_erg = db_add_dataset($GLOBALS['server'], $name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache);

			if ($add_erg['db_erg'])
			{
				$gesendet = send_notification_add($email, $name, $add_erg['id'], $add_erg['hash'], $label);
				$_SESSION['form_submitted'] = true;
				if ($gesendet == 1){
					alert($label, true, $label['Add_gesendet'], 'index.php?action=table&lang='.$label["lang"]);
				} else {
					alert($label, false, $label['Add_nichtGesendet'], 'index.php?action=table&lang='.$label["lang"]);
				}
			}
		}
	}
}


//##############################
//
//   ACTION VIEW
//
//#############################

function actionView($label)
{
	if ($zeile = getEntry())
	{
		$id = $_GET['tid'];

		if (isset($_POST['name']) OR isset($_POST['email']) OR isset($_POST['geschlecht']) OR
				isset($_POST['alter']) OR isset($_POST['ort']) OR isset($_POST['datenschutz']))
		{
			if ($_POST['name'] == "" OR $_POST['email'] == "" OR $_POST['geschlecht'] == "" OR
					$_POST['alter'] == "" OR $_POST['ort'] == "" OR $_POST['datenschutz'][0] != "ja"
					OR
					!($_POST['areYouHuman'] == '')
					OR
					(!isValidEmail($_POST['email']) or !(strtolower($_POST['email']) == strtolower($_POST['email_nochmal']))))
			{
				$senden = false;
			} else {
				$senden = true;
			}
		} else {
			$senden = false;
		}

		if ($senden){
			$name = strip_tags($_POST['name']);
			$geschlecht = strip_tags($_POST['geschlecht']);
			$alter = strip_tags($_POST['alter']);
			$ort = strip_tags($_POST['ort']);
			$email = strip_tags(strtolower($_POST['email']));
			$text = strip_tags($_POST['text']);

			$label_mail = setLanguage($zeile['lang']);

			$to = $zeile[$GLOBALS['db_colName_email']];

			$gesendet = send_notification_view($to, $zeile[$GLOBALS['db_colName_name']], $name, $zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']], $alter, $geschlecht, $ort, $email, $text, $label_mail);

			if ($gesendet == 1){
				db_incr_answers($GLOBALS['server'], $zeile[$GLOBALS['db_colName_id']]);
			}
		}

		include 'partials/view.php';
	}
}

//##############################
//
//   ACTION EDIT
//
//#############################

function actionEdit($label)
{
	if (($zeile = getEntry()) and ($hash = getHash($zeile)))
	{
		$id = $_GET['tid'];

		if ((isset($_POST['geschlecht']) OR isset($_POST['skills']) OR isset($_POST['name']) OR
		isset($_POST['email']) OR isset($_POST['ort']) OR isset($_POST['spracheAng']) OR
		isset($_POST['spracheGes']) OR isset($_POST['text']) OR isset($_POST['alter']) OR isset($_POST['datenschutz'])))
		{

			if (($_POST['geschlecht'] == "" OR $_POST['skills'] == "" OR $_POST['name'] == "" OR
					($_POST['email'] == "" OR $_POST['ort'] == "" OR $_POST['spracheAng'] == "" OR $_POST['spracheGes'] == "" OR
					$_POST['text'] == "" OR $_POST['alter'] == "" OR $_POST['datenschutz'][0] != "ja")
					OR
					!($_POST['areYouHuman'] == '')
					OR
					(!isValidEmail($_POST['email']) or !(strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])))))
			{

				$senden = false;
			} else {
				$senden = true;
			}
		} else {
			$senden = false;
		}

		if ( $senden == false)
		{
			$_POST['name'] = (($_POST['name'] == "") ? $zeile[$GLOBALS['db_colName_name']] : $_POST['name']);
			$_POST['alter'] = (($_POST['alter'] == "") ? $zeile[$GLOBALS['db_colName_alter']] : $_POST['alter']);
			$_POST['geschlecht'] = ($_POST['geschlecht'] == "") ? $zeile[$GLOBALS['db_colName_geschlecht']] : $_POST['geschlecht'];
			$_POST['email'] = $_POST['email'] == "" ? $zeile[$GLOBALS['db_colName_email']] : $_POST['email'];
			$_POST['email_nochmal'] = $_POST['email_nochmal'] == "" ? $zeile[$GLOBALS['db_colName_email']] : $_POST['email_nochmal'];
			$_POST['ort'] = $_POST['ort'] == "" ? $zeile[$GLOBALS['db_colName_ort']] : $_POST['ort'];
			$_POST['skills'] = $_POST['skills'] == "" ? $zeile[$GLOBALS['db_colName_skills']] : $_POST['skills'];
			$_POST['spracheAng'] = $_POST['spracheAng'] == "" ? $zeile[$GLOBALS['db_colName_spracheAng']] : $_POST['spracheAng'];
			$_POST['spracheGes'] = $_POST['spracheGes'] == "" ? $zeile[$GLOBALS['db_colName_spracheGes']] : $_POST['spracheGes'];
			$_POST['text'] = $_POST['text'] == "" ? $zeile[$GLOBALS['db_colName_beschreibung']] : $_POST['text'];
			addTandemForm($label, 'index.php?action=edit&a='.$hash.'&tid='.$id.'&lang='.$label['lang'].'&first=0#oben');
			$first = false;
		}
		else
		{
			$name = strip_tags(($_POST['name']));
			$email = strip_tags(strtolower($_POST['email']));
			$ort = strip_tags(($_POST['ort']));
			$spracheAng = strip_tags(($_POST['spracheAng']));
			$spracheGes = strip_tags(($_POST['spracheGes']));
			$beschreibung = strip_tags(($_POST['text']));
			$alter = strip_tags(($_POST['alter']));
			$geschlecht = strip_tags(($_POST['geschlecht']));
			$kenntnis = strip_tags(($_POST['kenntnis']));

			db_edit_dataset($GLOBALS['server'], $name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email);

			alert($label, true, $label['Edit_ok'], 'index.php?action=table&lang='.$label["lang"]);
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
	if (($zeile = getEntry()) and ($hash = getHash($zeile)))
	{
		$id = $_GET['tid'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$db_erg = db_delete_DataSet($GLOBALS['server'], $id, $hash);
			if ( $db_erg > 0 ){
				alert($label, true, $label['deleteDataset'], 'index.php?action=table&lang='.$label["lang"]);
			} else
			{
				alert($label, false, $GLOBALS['errorMessage'], 'index.php?action=table&lang='.$label["lang"]);
			}
		} else
		{
			include 'partials/delete.php';
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
	if (($zeile = getEntry()) and ($hash = getHash($zeile)))
	{
		$id = $_GET['tid'];

		$db_erg = db_release_DataSet($GLOBALS['server'], $id, $hash);
		if ($db_erg){
			alert($label, true, $label['releaseDataset'], 'index.php?action=table&lang='.$label["lang"]);
		} else
		{
			alert($label, false, $GLOBALS['errorMessage'], 'index.php?action=table&lang='.$label["lang"]);
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
	$db_erg = db_get_langPairs($GLOBALS['server'], $_GET["t"] === "year");
	$sum_replies = 0;
	$sum_count = 0;
	$sums = array();

	foreach ($db_erg as $zeile)
	{
		$_replies = db_sum_answers($GLOBALS['server'], $zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']]);
		$replies[] = $_replies;
		$sum_replies += $_replies;
		$sum_count += $zeile['count'];
	}

	include 'partials/stat.php';
}

//##############################
//
//   ACTION REPORT
//
//#############################

function actionReport($label)
{
	if ($zeile = getEntry())
	{
		$id = $_GET['tid'];
		$senden = false;

		if ($_SERVER['REQUEST_METHOD'] === 'POST'
			AND $_POST['text'] != ''
			AND $_POST['areYouHuman'] == ''
			AND (
				$_POST['email'] == ''
				OR (
					isValidEmail($_POST['email'])
					AND strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])
				)
			))
		{
			$senden = true;

			$name = $_POST['name'];
			$email = strtolower($_POST['email']);
			$text = $_POST['text'];

			$label_mail = setLanguage("de");

			$to = $GLOBALS['email_orga'];

			$gesendet = send_notification_report(
				$to,
				$name,
				$email,
				$text,
				html_entity_decode($zeile[$GLOBALS['db_colName_name']]),
				html_entity_decode($zeile[$GLOBALS['db_colName_id']]),
				html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']]),
				$label_mail);
		}

		include 'partials/report.php';
	}
}

?>
