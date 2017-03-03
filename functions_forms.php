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
		echo '<form action="'.htmlentities($caller).'" method="POST" >';
		echo '<p class=form_above>'.($label['View_Form_ausfuellen']).'</p>';
		echo '<table  class=form_table>';
		echo '<colgroup id="form_col1"><col></colgroup>	<colgroup id="form_col2"><col></colgroup>';
		echo '<tr><td>'.$label['View_Form_name'].':</td> <td><input type="text" name="name" value="'.htmlentities($_POST['name']).'"/></td></tr>';
		echo '<tr><td>'.$label['View_Form_geschlecht'].':</td> <td><input type="text" name="geschlecht" value="'.htmlentities($_POST['geschlecht']).'"/></td></tr>';
		echo '<tr><td>'.$label['View_Form_alter'].':</td> <td><input type="text" name="alter" value="'.htmlentities($_POST['alter']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_ort'].':</td> <td><input type="text" name="ort" value="'.htmlentities($_POST['ort']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_email'].':</td> <td><input type="text" name="email" value="'.htmlentities($_POST['email']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_email_nochmal'].':</td> <td><input type="text" name="email_nochmal" value="'.htmlentities($_POST['email_nochmal']).'" /></td></tr>';
		echo '<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" Value="'.htmlentities($_POST['areYouHuman']).'" /></td></tr>';
		echo '<tr><td valign="top">'.$label['View_Form_text'].':</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%" >'.htmlentities($_POST['text']).'</textarea> </td></tr>';
		echo '</table>';
		echo '<table>';
		echo '<tr><td valign="top"><input value="ja" name="datenschutz[]" type="Checkbox"></td><td><font color="black">'.sprintf($label['View_datenschutz'], $GLOBALS["organisationName"]).'</td></tr><br/> ';
		echo '</table>';
		echo '<br />';
		echo '<p><button type="submit" class="button_image"><div id="image_button_send">'.$label['View_Form_senden'].'</div></button></p>';
		echo '</form>';
		echo '</div>';
}


function filterLanguageForm($label, $caller)
{
	echo '<form action="'.htmlentities($caller).'&page=0" method="POST" >';
	echo '<br>';
	echo '<table class=filter_table>';
	echo '<colgroup id="col1"><col><col><col></colgroup>
				<colgroup id="col2"><col></colgroup>
				<colgroup id="col3"><col></colgroup>';
	echo '<tr><th>'.$label['Table_filtern'].':</th><th>';

	// Sprache1
	$db_erg = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheAng']);

	echo '<select name="filterAng">';
	echo '<option selected>'.$label['Table_filter_alle'].'</option>';
	//while ($zeile = $erg->fetch())
	foreach ($db_erg as $zeile)
	{
		foreach ($label as $key => $value){
			if (strpos($key, 'sprache_') === 0 ){
				if ($key == html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])){
					echo '<option value="'.$key.'">'.$label[$key].'</option>';
				}
			}
		}
	}
	echo '</select>';
	echo '</th>';

	$db_erg = db_selectFormColumn($GLOBALS['server'], $GLOBALS['db_colName_spracheGes']);
	echo '<th>';
	echo '<select name="filterGes">';
	echo '<option selected>'.$label['Table_filter_alle'].'</option>';

	foreach ($db_erg as $zeile)
	{
		foreach ($label as $key => $value){
			if (strpos($key, 'sprache_') === 0 ){
				if ($key == html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])){
					echo '<option value="'.$key.'">'.$label[$key].'</option>';
				}
			}
		}
	}
	echo '</select>';
	echo '</th>';

	echo '<th>';
	echo '<p><button type="submit" class="button_image"><div id="image_button_filter">'.$label['Table_filtern'].'</div></button></p>';
	echo '</th></tr>';
	echo '</table>';
	echo '</form>';
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
