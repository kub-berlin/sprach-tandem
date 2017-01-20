<?php

//#############################
//
//   ACTION TABLE
//
//#############################

function actionTable($label)
{
	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}

	$page = (isset($_GET["page"]) or $page != '') ? htmlentities($_GET["page"]): 0;

	// ======================================
	//  TEXT ÜBER DER TABELLE

	echo '<p>'.$label['Table_intro'].'</p>';
	echo '<div class=div_table>';

	//=====================
	// Filter

	filterLanguageForm($label, "index.php?action=table&lang=".$label['lang']);

	$filterAng = isset($_POST['filterAng']) ? $_POST['filterAng'] : $label['Table_filter_alle'];
	$filterGes = isset($_POST['filterGes']) ? $_POST['filterGes'] : $label['Table_filter_alle'];

	$db_erg = db_selectTableData($filterAng, $filterGes, $label, $page);

	//===================
	// Tabelle

	echo '<table class=tandem_table>';//' border="1" cellpadding="5" cellspacing="0" bordercolordark="#a1a1a1" bordercolorlight="#a1a1a1" style="font-family:Arial; font-size:14">';
	echo '<colgroup id="col1"><col><col><col></colgroup>
				<colgroup id="col2"><col></colgroup>
				<colgroup id="col3"><col></colgroup>';
	echo '<tr>'.
				'<th>'.$label['Table_col_name'].'</th>'.
				'<th>'.$label['Table_col_spracheAng'].'</th>'.
				'<th>'.$label['Table_col_spracheGes'].'</th>'.
				'<th>'.$label['Table_col_datum'].'</th>'.
				'<th>'.$label['Table_col_ort'].'</th>'.
			'</tr>';

	while ($zeile = mysql_fetch_array ( $db_erg, MYSQL_ASSOC ))
	{
		echo "<tr>";
		echo '<td><a href="index.php?action=view&tid='. $zeile[$GLOBALS['db_colName_id']] .'&lang='.$label['lang'].'">'.
			html_entity_decode($zeile[$GLOBALS['db_colName_name']]) . "</a></td>";
		echo "<td>". $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])] . "</td>";
		echo "<td>". $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])] . "</td>";
		echo "<td>". date($label["dateFormat"], strtotime($zeile[$GLOBALS['db_colName_datum']])) . "</td>";
		echo "<td>". html_entity_decode($zeile[$GLOBALS['db_colName_ort']]) . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</form>";

	mysql_free_result($db_erg);

	$anzahl = db_countTableData($filterAng, $filterGes, $label);

	// PAGES
	$anzahl_pages = floor($anzahl/$GLOBALS['table_page_size']);
	if ($anzahl%$GLOBALS['table_page_size'] != 0)
	{
		$anzahl_pages = $anzahl_pages + 1;
	}

	echo '<p class=center>';
	if ($label['lang'] != "fa" and $label['lang'] != "ar")
	{
		if ($page > 1)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page=0&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/first.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';
		}
		if ($page > 0)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($page-1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/prev.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';
		}
		echo '  '.($page+1).'/'.$anzahl_pages.'  ';
		if ($page+1 < $anzahl_pages)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($page+1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/next.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';

		}
		if ($page+2 < $anzahl_pages)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($anzahl_pages-1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/last.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';
			//<img src="./images/last.svg" style="width: 28px" alt="next"> </a>';
		}
	} else {
		if ($page > 1)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page=0&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/last.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';
		}
		if ($page > 0)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($page-1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/next.svg" style="width: 28px; margin-bottom: -7px;" alt="prev"></a>';
		}
		echo '  '.($page+1).'/'.$anzahl_pages.'  ';
		if ($page+1 < $anzahl_pages)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($page+1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/prev.svg" style="width: 28px; margin-bottom: -7px;" alt="rtl_next"></a>';
		}
		if ($page+2 < $anzahl_pages)
		{
			echo '<a href="index.php?action=table&lang='.$label['lang'].'&page='.($anzahl_pages-1).'&filterAng='.$filterAng.'&filterGes='.$filterGes.'">';
			echo '<img src="./images/first.svg" style="width: 28px; margin-bottom: -7px;" alt="rtl_last"></a>';
		}
	}

	echo '</p>';
	echo '</div>';
	echo '</div>';
}


//##############################
//
//   ACTION ADD
//
//#############################

function actionAdd($label) {
	$senden = false;

	session_start();

	if (!isset($_POST["skills"]) or !isset($_SESSION['form_submitted'])) {
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
				if (strpos($key, 'sprache_') === 0 ) {
					if ($key == strip_tags($_POST['spracheAng'])) {
						$spracheAng = $key;
					}
					if ($key == strip_tags($_POST['spracheGes'])) {
						$spracheGes = $key;
					}
				}
			}
			$beschreibung = strip_tags(($_POST['text']));

			$add_erg = db_add_dataset($name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache);

			if ($add_erg['db_erg'])
			{
				$gesendet = send_notification_add($email, $name, $add_erg['id'], $add_erg['hash'], $label);
				$_SESSION['form_submitted'] = true;
				if ($gesendet == 1) {
					echo '<table>';
					echo '<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
					//echo '<tr><td valign="top"><img src="./images/check.svg" alt="Bild"></td>';
					echo '<td>'.$label['Add_gesendet'].'</td></tr>';
					echo '</table>';
					echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
					echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
					echo '</form>';
				} else {
					echo '<table>';
					echo '<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="Bild"></td>';
					echo '<td>'.$label['Add_nichtGesendet'].'</td></tr>';
					echo '</table>';
					echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
					echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
					echo '</form>';
				}
			}
		}
		echo '</div>';
	}
}


//##############################
//
//   ACTION VIEW
//
//#############################

function actionView($label)
{
	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}

	$id = strip_tags(htmlentities($_GET["tid"], ENT_QUOTES));

	if ( is_numeric($id) )
	{
		$db_erg = db_getDataSet($id);
	}

	if ( ! $db_erg OR ! is_numeric($id))
	{
		echo "<p>Error: Data not found.</p>";
	} else
	{
		$zeile = mysql_fetch_array($db_erg, MYSQL_ASSOC);

		echo '<h3>'. html_entity_decode($zeile[$GLOBALS['db_colName_name']]) . '</h3>';
		echo '<table>';
		echo '<colgroup id="form_col1"><col></colgroup> <colgroup id="form_col2"><col></colgroup>';
		echo '<tr><td><b>'.$label['View_alter'].':</b></td><td>'. $zeile[$GLOBALS['db_colName_alter']] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_geschlecht'].':</b></td><td>'. html_entity_decode($zeile[$GLOBALS['db_colName_geschlecht']]) . '</td></tr>';
		echo '<tr><td><b>'.$label['View_spracheAng'].': </b></td><td>'. $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_spracheGes'].': </b></td><td>'. $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_skills'].':</b></td><td>';
		if ($zeile[$GLOBALS['db_colName_skills']] == 0) {
			echo $label['View_skills_0'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 1) {
			echo $label['View_skills_1'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 2) {
			echo $label['View_skills_2'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 3) {
			echo $label['View_skills_3'];
		}
		echo '</td></tr>';
		echo '<tr><td><b>'.$label['View_ort'].': </b></td><td>'. html_entity_decode($zeile[$GLOBALS['db_colName_ort']]) . '</td></tr>';
		echo '<tr><td valign="top"><b>'.$label['View_beschreibung'].': </b></td><td><textarea name="lizenz" cols="50" rows="10" readonly style="width: 100%" >'. html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']]) .'</textarea></td></tr>';
		echo '<tr><td></td><td align="right"><a href=index.php?action=report&lang='.$label['lang'].'&tid='.$id.'><img src="./images/megaphone.svg" width=15px height=15px> '.$label['View_AnzeigeMelden'].'</a></td></tr>';
		echo '</table>';
		echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
		echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
		echo '</form>';
		echo "<hr>";

		//=======================
		// Formular
		//
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
		echo '<h3><img src="./images/chat.svg" width=20px height=20px> '.$label['View_Form_nachrichtAn'].''. $zeile[$GLOBALS['db_colName_name']].' </h3>';
		if ($senden == false)
		{
			sendMessageForm($label, "index.php?action=view&lang=".$label['lang']."&tid=".$id);
		} else
		{
			$name = strip_tags($_POST['name']);
			$geschlecht = strip_tags($_POST['geschlecht']);
			$alter = strip_tags($_POST['alter']);
			$ort = strip_tags($_POST['ort']);
			$email = strip_tags(strtolower($_POST['email']));
			$text = strip_tags($_POST['text']);

			$label_mail = setLanguage($zeile['lang']);

			$to = $zeile[$GLOBALS['db_colName_email']];

			$gesendet = send_notification_view($to, $zeile[$GLOBALS['db_colName_name']], $name, $zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']], $alter, $geschlecht, $ort, $email, $text, $label_mail);

			if ($gesendet == 1) {
				echo '<table>';
				echo '<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
				echo '<td>'.$label['View_gesendet'].'</td></tr>';
				echo '</table>';

				db_incr_answers($zeile[$GLOBALS['db_colName_id']]);
			} else {
				echo '<table>';
				echo '<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="OK, " width=20px height=20px></td>';
				echo '<td>'.$label['View_nichtGesendet'].'</td></tr>';
				echo '</table>';
			}

			mysql_free_result($db_erg);
		}
	}
	echo '</div>';
}

//##############################
//
//   ACTION EDIT
//
//#############################

function actionEdit($label)
{
	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}
	if (!isset( $_GET["first"] )) {
		$_GET["first"] = true;
	} else
	{
		$first= strip_tags(htmlentities($_GET["first"]));
	}
	if (isset($_GET["tid"]) and isset($_GET["a"]))
	{
		$id = strip_tags(htmlentities($_GET["tid"], ENT_QUOTES));
		$hash = strip_tags(htmlentities($_GET["a"], ENT_QUOTES));
		//echo '<font face="Arial" size="2">';

		if ( is_numeric($id) )
		{
			$db_erg = db_getDataSet($id);

			if ( ! $db_erg) {
				die("DB Error");
			}
			else
			{
				if ($zeile = mysql_fetch_array ( $db_erg, MYSQL_ASSOC ))
				{
					if (str_replace(' ', '', $zeile[$GLOBALS['db_colName_hash']]) === str_replace(' ', '', $hash))
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
							$_POST['name'] = $zeile[$GLOBALS['db_colName_name']];
							$_POST['alter'] = $zeile[$GLOBALS['db_colName_alter']];
							$_POST['geschlecht'] = $zeile[$GLOBALS['db_colName_geschlecht']];
							$_POST['email'] = $zeile[$GLOBALS['db_colName_email']];
							$_POST['ort'] = $zeile[$GLOBALS['db_colName_ort']];
							$_POST['skills'] = $zeile[$GLOBALS['db_colName_skills']];
							$_POST['spracheAng'] = $zeile[$GLOBALS['db_colName_spracheAng']];
							$_POST['spracheGes'] = $zeile[$GLOBALS['db_colName_spracheGes']];
							$_POST['text'] = $zeile[$GLOBALS['db_colName_beschreibung']];
							addTandemForm($label, 'index.php?action=edit&a='.$hash.'&tid='.$id.'&lang='.$label['lang'].'&first=0');
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

							db_edit_dataset($name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email);

							echo '<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
							echo '<td>'.$label['Edit_ok'].'</td></tr>';
							echo '</table>';
							echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
							echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
							echo '</form>';
						}
					}
					else
					{
						echo '<td>'.$GLOBALS['errorMessage'].'</td></tr>';
					}
				}
			}
		}
	}
	echo "</div>";
}

//##############################
//
//   ACTION DELETE
//
//#############################

function actionDelete($label)
{
	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}

	if ($_POST['delete'] == $label['deleteDataset_button_no'])
	{
		header('Location: index.php?action=table&lang='.$label["lang"]);
	} elseif ($_POST['delete'] == $label['deleteDataset_button_yes']) {
		$ok = 1;
	}

	/*if (!isset( $_GET["ok"] )) {
		$_GET["ok"] = 0;
		$ok = 0;
	} else
	{
		$ok = strip_tags(htmlentities($_GET["ok"]));
	}*/
	if (isset($_GET["tid"]) and isset($_GET["a"]))
	{
		$id = strip_tags(htmlentities($_GET["tid"], ENT_QUOTES));
		$hash = strip_tags(htmlentities($_GET["a"], ENT_QUOTES));

		//echo '<font face="Arial" size="2">';

		if ( is_numeric($id) )
		{
			if ($ok != 0)
			{
				$db_erg = db_delete_DataSet($id, $hash);

				if ( $db_erg > 0 ) {
					echo '<table>';
					echo '<tr><td valign="top"><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
					echo '<td>'.$label['deleteDataset'].'</td></tr>';
					echo '</table>';
					echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
					//echo '<p><button type="submit">'.$label["zurueck"].'</button></p>';
					echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
					echo '</form>';
				} else
				{
					echo '<table>';
					echo '<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="Sorry, " width=20px height=20px></td>';
					echo '<td>'.$GLOBALS['errorMessage'].'</td></tr>';
					echo '</table>';
					echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
					//echo '<p><button type="submit">'.$label["zurueck"].'</button></p>';
					echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
					echo '</form>';
				}
			} else
			{
				echo '<form action="index.php?action=delete&ok=1&tid='.$id.'&a='.$hash.'" method="POST" >';
				//echo '<>'.sprintf($label['deleteDataset_Title'], $GLOBALS['organisationName']).'?</p>';
				echo '<p> <input type="submit" name="delete" value="'.$label['deleteDataset_button_yes'].'" /> <input type="submit" name="delete" value="'.$label['deleteDataset_button_no'].'" /></p>';
				echo '</form>';
			}
		}
	}
	echo "</div>";
}


//##############################
//
//   ACTION RELEASE
//
//#############################

function actionRelease($label)
{
	$error = false;

	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}
	if (!isset( $_GET["ok"] )) {
		$_GET["ok"] = 0;
	} else
	{
		$ok= strip_tags(htmlentities($_GET["ok"]));
	}
	if (isset($_GET["tid"]) and isset($_GET["a"]))
	{
		$id = strip_tags(htmlentities($_GET["tid"], ENT_QUOTES));
		$hash = strip_tags(htmlentities($_GET["a"], ENT_QUOTES));

		//echo '<font face="Arial" size="2">';

		if ( is_numeric($id) )
		{
			$db_erg = db_release_DataSet($id, $hash);

			if ( $db_erg > 0) {
				echo '<table>';
				echo '<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
				echo '<td>'.$label['releaseDataset'].'</td></tr>';
				echo '</table>';
				echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit">'.$label["zurueck"].'</button></p>';
				echo '</form>';
			} else
			{
				$error = true;
			}
		} else
		{
			$error = true;
		}
		if ($error)
		{
			echo '<table>';
			echo '<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="Sorry, " width=20px height=20px></td>';
			echo '<td>'.$GLOBALS['errorMessage'].'</td></tr>';
			echo '</table>';
			echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
			echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
			echo '</form>';
		}
	}
	echo "</div>";
}

//##############################
//
//   ACTION STATISTIC
//
//#############################


function actionStatistic($label)
{
	echo '<h3>Statistik</h3>';
	echo '<p>folgende Sprach-Tandem Anzeigen wurden eingetragen: </p>';
	echo '<table class=tandem_table><th>Angebotene Sprache</th><th>Gesuchte Sprache</th><th>Anzahl der Anzeigen</th><th>Antworten</th>';
	$db_erg = db_get_langPairs();
	$sum_sum = 0;
	$sum_count = 0;
	while ($zeile = mysql_fetch_array ( $db_erg, MYSQL_ASSOC ))
	{
		$count = db_count_langPairs($zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']]);
		$sum = db_sum_answers($zeile[$GLOBALS['db_colName_spracheAng']], $zeile[$GLOBALS['db_colName_spracheGes']]);
		$sum_sum = $sum_sum + $sum;
		$sum_count = $sum_count + $count;
		echo '<tr><td>'.$label[$zeile[$GLOBALS['db_colName_spracheAng']]].'</td><td>'.$label[$zeile[$GLOBALS['db_colName_spracheGes']]].'</td><td>'.$count.'</td><td>'.$sum.'</td></tr>';
	}
	echo '<z/table>';
	echo '<p>Insgesamt wurden '.$sum_count.' Anzeigen aufgegeben und '.$sum_sum.' mal geantwortet</p>';
}

//##############################
//
//   ACTION REPORT
//
//#############################

function actionReport($label)
{
	if ($label["lang"] == 'fa' or $label["lang"] == 'ar')
	{
		echo '<div dir="rtl">';
	} else
	{
		echo '<div dir="ltr">';
	}

	$id = strip_tags(htmlentities($_GET["tid"], ENT_QUOTES));

	if ( is_numeric($id) )
	{
		$db_erg = db_getDataSet($id);
	}

	if ( ! $db_erg OR ! is_numeric($id))
	{
		echo "<p>Error: Data not found.</p>";
	}	else
	{
		$zeile = mysql_fetch_array ( $db_erg, MYSQL_ASSOC );

		echo '<h3>'. html_entity_decode($zeile[$GLOBALS['db_colName_name']]) . '</h3>';
		echo '<table>';
		echo '<tr><td><b>'.$label['View_alter'].':</b></td><td>'. $zeile[$GLOBALS['db_colName_alter']] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_geschlecht'].':</b></td><td>'. html_entity_decode($zeile[$GLOBALS['db_colName_geschlecht']]) . '</td></tr>';
		echo '<tr><td><b>'.$label['View_spracheAng'].': </b></td><td>'. $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_spracheGes'].': </b></td><td>'. $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])] . '</td></tr>';
		echo '<tr><td><b>'.$label['View_skills'].':</b></td><td>';
		if ($zeile[$GLOBALS['db_colName_skills']] == 0) {
			echo $label['View_skills_0'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 1) {
			echo $label['View_skills_1'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 2) {
			echo $label['View_skills_2'];
		} elseif ($zeile[$GLOBALS['db_colName_skills']] == 3) {
			echo $label['View_skills_3'];
		}
		echo '</td></tr>';
		echo '<tr><td><b>'.$label['View_ort'].': </b></td><td>'. html_entity_decode($zeile[$GLOBALS['db_colName_ort']]) . '</td></tr>';
		echo '<tr><td valign="top"><b>'.$label['View_beschreibung'].': </b></td><td><textarea name="lizenz" cols="50" rows="10" readonly style="width: 100%" >'. html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']]) .'</textarea></td></tr>';
		echo '</table>';
		echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
		echo '<p><button type="submit" class="button_image"><div id='.(($label['lang'] == "fa" or $label['lang'] == "ar") ? '"image_button_back_rtl"' : '"image_button_back"').'>'.$label["zurueck"].'</div></button></p>';
		echo '</form>';
		echo "<hr>";

		//=======================
		// Formular
		//
		if (isset($_POST['text']))
		{
			if (($_POST['text'] != "")
				AND
				($_POST['areYouHuman'] == '')
				AND
				((isValidEmail($_POST['email']) and (strtolower($_POST['email']) == strtolower($_POST['email_nochmal'])))
				OR
				($_POST['email'] == "")))
			{
				$senden = true;
			} else {
				$senden = false;
			}
		} else {
			$senden = false;
		}
		echo '<h3><img src="./images/megaphone.svg" width=20px height=20px> '.$label['View_AnzeigeMelden'].'</h3>';
		if ($senden == false)
		{
			reportForm($label, "index.php?action=report&lang=".$label['lang']."&tid=".$id);
		} else
		{
			$name = strip_tags($_POST['name']);
			$email = strip_tags(strtolower($_POST['email']));
			$text = strip_tags($_POST['text']);

			$label_mail = setLanguage("de");

			$to = $GLOBALS['email_orga'];

			$gesendet = send_notification_report($to, $name, $email, $text, html_entity_decode($zeile[$GLOBALS['db_colName_name']]), html_entity_decode($zeile[$GLOBALS['db_colName_id']]), html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']]), $label_mail);

			if ($gesendet == 1) {
				echo '<table>';
				echo '<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>';
				echo '<td>'.$label['Report_gesendet'].'</td></tr>';
				echo '</table>';
			} else {
				echo '<table>';
				echo '<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="OK, " width=20px height=20px></td>';
				echo '<td>'.$label['Report_nichtGesendet'].'</td></tr>';
				echo '</table>';
			}
			//echo '</br><a href=index.php?lang='.$label['lang'].'>'.$label['zurueck'].'</a>';
			mysql_free_result ( $db_erg );
		}
	}
	echo '</div>';
}
?>
