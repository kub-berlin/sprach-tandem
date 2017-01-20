<?php

//##############################
//
//   FORMULARE
//
//#############################

function addTandemForm($label, $caller)
{
	if ($_POST['send'] == "cancel")
	{
		//echo 'Location: index.php?action=table&lang='.$label["lang"];
		header('Location: index.php?action=table&lang='.$label["lang"]);
	}

	if (!isset( $_POST["name"] )){
		$_POST["name"] = "";
	}
	if (!isset( $_POST["alter"] )){
		$_POST["alter"] = "";
	}
	if (!isset( $_POST["email"] )){
		$_POST["email"] = "";
	}
	if (!isset( $_POST["email_nochmal"] )){
		$_POST["email_nochmal"] = "";
	}
	if (!isset( $_POST["ort"] )){
		$_POST["ort"] = "";
	}
	if (!isset( $_POST["geschlecht"] )){
		$_POST["geschlecht"] = "";
	}
	if (!isset( $_POST["skills"] )){
		$_POST["skills"] = "0";
	}
	if (!isset( $_POST["text"] )){
		$_POST["text"] = "";
	}

	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else {
		echo '<div dir="ltr">';
	}
	echo '<form action="'.htmlentities($caller).'" method="POST" >';
	echo '<p class=form_above>'.($label['Add_ausfuellen']).'</p>';
	// Formulareintragungen liegen (noch) nicht vor
	echo '<table class=form_table>';
	echo '<colgroup id="form_col1"><col></colgroup>	<colgroup id="form_col2"><col></colgroup>';
	echo '<tr><td>'.($label['Add_name']).':</td> <td><input type="text" name="name" Value="'. $_POST["name"] .'" /></td></tr>';
	echo '<tr><td>'.($label['Add_alter']).':</td> <td><input type="text" name="alter" Value="'. $_POST['alter'] .'" /></td></tr>';
	echo '<tr><td>'.($label['Add_email']).':</td> <td><input type="text" name="email" Value="'. $_POST['email'] .'" /></td></tr>';
	echo '<tr><td>'.($label['Add_email_nochmal']).':</td> <td><input type="text" name="email_nochmal" Value="'. $_POST['email_nochmal'] .'" /></td></tr>';
	echo '<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" Value="" /></td></tr>';
	echo '<tr><td>'.($label['Add_ort']).':</td> <td><input type="text" name="ort" Value="'. $_POST['ort'] .'" /></td></tr>';
	echo '<tr><td>'.($label['Add_geschlecht']).':</td> <td><input type="text" name="geschlecht" Value="'. $_POST['geschlecht'] .'" /></td></tr>';
	echo '<tr><td>'.($label['Add_spracheAng']).':</td> <td>';
	echo '<select name="spracheAng">';
	foreach ($label as $key => $value) {
		if (strpos($key, 'sprache_') === 0 ){
			if ($key == ($_POST['spracheAng'])){
				echo '<option value="'.$key.'" selected>'.($label[$key]).'</option>';
			} else {
				echo '<option value="'.$key.'">'.($label[$key]).'</option>';
			}
		}
	}
	echo '</select></td></tr>';
	echo '<tr><td>'.($label['Add_spracheGes']).':</td> <td>';
	echo '<select name="spracheGes">';
	foreach ($label as $key => $value) {
		if (strpos($key, 'sprache_') === 0 ){
			if ($key == ($_POST['spracheGes'])){
				echo '<option value="'.$key.'" selected>'.($label[$key]).'</option>';
			} else {
				echo '<option value="'.$key.'">'.($label[$key]).'</option>';
			}
		}
	}
	echo '</select></td></tr>';
	echo '<tr><td valign="top">'.($label['Add_skills']).':</td> <td>';
	echo '<Input type = "Radio" Name ="skills" value= "0" '. (($_POST['skills'] == "0" OR $_POST['skills'] == "") ? 'checked' : '') .'>'.$label['Add_skills_0'].'</br>';
	echo '<Input type = "Radio" Name ="skills" value= "1" '. ($_POST['skills'] == "1" ? "checked" : "") .'>'.$label['Add_skills_1'].'</br>';
	echo '<Input type = "Radio" Name ="skills" value= "2" '. ($_POST['skills'] == "2" ? "checked" : "") .'>'.$label['Add_skills_2'].'</br>';
	echo '<Input type = "Radio" Name ="skills" value= "3" '. ($_POST['skills'] == "3" ? "checked" : "") .'>'.$label['Add_skills_3'].'</td></tr>';

	echo '<tr><td valign="top">'.($label['Add_beschreibung']).':</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%" >'. ($_POST['text']).'</textarea></td></tr>';
	echo '</table>';
	echo '<table  class=form_table>'; //; font-size:14
	echo '<tr><td valign="top"><input value="ja" name="datenschutz[]" type="Checkbox"></td><td><font color="black">'.sprintf($label['Add_datenschutz'], $GLOBALS["organisationName"]).'</td></tr>';
	echo '</table><br/>';
	//echo '<p> <input type="submit" value="'.($label['Add_senden']).'" /></p>';
	echo '<p><button type="submit" name="send" value="cancel" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label['zurueck'].'</div></button>    ';
	echo '<button type="submit" name="send" value="send" class="button_image"><div id="image_button_send">'.$label['Add_senden'].'</div></button></p>';
	echo '</form>';
	echo '</div>';
}

function sendMessageForm($label, $caller)
{
	if (!isset( $_POST["name"] )){
		$_POST["name"] = "";
	}
	if (!isset( $_POST["alter"] )){
		$_POST["alter"] = "";
	}
	if (!isset( $_POST["email"] )){
		$_POST["email"] = "";
	}
	if (!isset( $_POST["email_nochmal"] )){
		$_POST["email_nochmal"] = "";
	}
	if (!isset( $_POST["ort"] )){
		$_POST["ort"] = "";
	}
	if (!isset( $_POST["geschlecht"] )){
		$_POST["geschlecht"] = "";
	}
	if (!isset( $_POST["text"] )){
		$_POST["text"] = "";
	}

	/*if ( $_POST['name'] == "" OR $_POST['email'] == "" OR $_POST['text'] == "" OR $_POST['geschlecht'] == "" OR $_POST['alter'] == "" OR $_POST['ort'] == "" OR $_POST['datenschutz'][0] != 'ja'OR strpos($_POST['email'], "@") === false OR strpos($_POST['email'], ".") === false)
	{*/
    // Formulareintragungen liegen (noch) nicht vor
		echo '<form action="'.htmlentities($caller).'" method="POST" >';
		echo '<p class=form_above>'.($label['View_Form_ausfuellen']).'</p>';
		echo '<table  class=form_table>';
		echo '<colgroup id="form_col1"><col></colgroup> <colgroup id="form_col2"><col></colgroup>';
		echo '<tr><td>'.$label['View_Form_name'].':</td> <td><input type="text" name="name" value="'.htmlentities($_POST['name']).'"/></td></tr>';
		echo '<tr><td>'.$label['View_Form_geschlecht'].':</td> <td><input type="text" name="geschlecht" value="'.htmlentities($_POST['geschlecht']).'"/></td></tr>';
		echo '<tr><td>'.$label['View_Form_alter'].':</td> <td><input type="text" name="alter" value="'.htmlentities($_POST['alter']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_ort'].':</td> <td><input type="text" name="ort" value="'.htmlentities($_POST['ort']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_email'].':</td> <td><input type="text" name="email" value="'.htmlentities($_POST['email']).'" /></td></tr>';
		echo '<tr><td>'.$label['View_Form_email_nochmal'].':</td> <td><input type="text" name="email_nochmal" value="'.htmlentities($_POST['email_nochmal']).'" /></td></tr>';
		echo '<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" Value="" /></td></tr>';
		echo '<tr><td valign="top">'.$label['View_Form_text'].':</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%" >'.htmlentities($_POST['text']).'</textarea> </td></tr>';
		echo '</table>';
		echo '<table>';
		echo '<tr><td valign="top"><input value="ja" name="datenschutz[]" type="Checkbox"></td><td><font color="black">'.sprintf($label['View_datenschutz'], $GLOBALS["organisationName"]).'</td></tr><br/> ';
		echo '</table>';
		echo '<br />';
		echo '<p><button type="submit" class="button_image"><div id="image_button_send">'.$label['View_Form_senden'].'</div></button></p>';
		echo '</form>';
		echo '</div>';
	//}
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
	$erg = db_selectFormColumn($GLOBALS['db_colName_spracheAng']);

	echo '<select name="filterAng">';
	echo '<option selected>'.$label['Table_filter_alle'].'</option>';
	while ($zeile = mysql_fetch_array ($erg))
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

	$erg = db_selectFormColumn($GLOBALS['db_colName_spracheGes']);

	echo '<th>';
	echo '<select name="filterGes">';
	echo '<option selected>'.$label['Table_filter_alle'].'</option>';
	while ($zeile = mysql_fetch_array ($erg))
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
	//echo '<p><button type="submit" id="filter_button"><img src="./images/funnel.svg" width=14px height=14px> '..'</button></p>';
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

	if (!isset( $_POST["text"] )){
		$_POST["text"] = "";
	}

	if (!isset( $_POST["email"] )){
		$_POST["email"] = "";
	}

	if (!isset( $_POST["name"] )){
		$_POST["name"] = "";
	}

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
	//echo '<p><button type="submit" class="button_image"><div id="image_button_send">'.$label['Report_Form_senden'].'</div></button></p>';
	echo '</form>';
	echo '</div>';
	//}
}
?>
