<?php

//##############################
//
//   FORMULARE
//
//#############################

function addTandemForm($label, $caller)
{
	if (isset($_POST['send']) and $_POST['send'] == "cancel")
	{
		header('Location: index.php?action=table&lang='.$label["lang"]);
	}

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
	if ($_POST['send'] == "cancel")
	{
		header('Location: index.php?action=table&lang='.$label["lang"]);
	}

	setDefaultParams(array('name', 'email', 'email_nochmal', 'text'));

	// Formulareintragungen liegen (noch) nicht vor
	echo '<form action="'.htmlentities($caller).'" method="POST" >';
	echo '<p class=form_above>'.sprintf($label['Report_textabove'], $GLOBALS["organisationName"]).'</p>';
	echo '<table class=form_table>';
	echo '<tr><td>'.$label['Report_Form_name'].':</td> <td><input type="text" name="name" value="'.htmlentities($_POST['name']).'"/></td></tr>';
	echo '<tr><td>'.$label['Report_Form_email'].':</td> <td><input type="text" name="email" value="'.htmlentities($_POST['email']).'" /></td></tr>';
	echo '<tr><td>'.$label['Report_Form_email_nochmal'].':</td> <td><input type="text" name="email_nochmal" value="'.htmlentities($_POST['email_nochmal']).'" /></td></tr>';
	echo '<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" Value="" /></td></tr>';
	echo '<tr><td valign="top">'.$label['Report_Form_text'].':</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%" >'.htmlentities($_POST['text']).'</textarea> </td></tr>';
	echo '</table>';
	echo '<table>';
	echo '<br />';
	echo '<p><button type="submit" name="send" value="send" class="button_image"><div id="image_button_send">'.$label['Report_Form_senden'].'</div></button></p>';
	echo '</form>';
	echo '</div>';
}

?>
