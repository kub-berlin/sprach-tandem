<?php

/*
TO DOS

- Übersetzungen anpassen
- Reminder-Texte
- statistik passwort (unnötig?)
- Anzeige melden Text (und übersetzung)
- Filtern nach Geschlecht ()
- Organisation Ersetzen (erledigt, außer Arabisch/Persisch)
- Buttons (style) (quatsch?)
- AreYouHuman Add,View,Report,Edit

*/
include './functions.php';
echo '<style type="text/css">';
echo '@charset "UTF-8";';
echo '@import "./style.css"';
echo '</style>';

echo '<div class=tandem_body>';

$heading = '<h3>'.$GLOBALS['organisationName'].'-Sprach-Tandem';

$label = setLanguage(htmlentities($_GET["lang"]));

$server = db_connectDB();

/*foreach ($label as $key => $value) {
	echo "<p>".$key.": ".$value;
}*/

if ($server > 0) {
	$db_select = db_selectDB();
	if ($db_select > 0) {
		// ==============
		//
		// REMINDER
		//
		// ==============

		scheduleReminder($label);

		//echo "<p>".$label['lang']."</p>";

		$action = htmlentities($_GET["action"]);

		if ($action == '') {
			$action = "table";
		}

		switch ($action) {
			case 'table':
				//echo "table";
				if ($GLOBALS['showTitle'] == true) {
					"<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				echo '<form action="index.php?action=add&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_add">'.$label["Add_Title"].'</button></p>'; //<div id="image_button_add"></div>
				echo '</form>';
				//echo '<tr><td></td><td align="right"><a href=index.php?action=report&lang='.$label['lang'].'&tid='.$id.'><img src="./images/megaphone.svg" width=15px height=15px> '.$label['View_AnzeigeMelden'].'</a></td></tr>';
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=add>ADD</a></p>";
				actionTable($label);
				break;
			case 'add':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">'.$label["Table_Title"].'</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionAdd($label);
				break;
			case 'view':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">'.$label["Table_Title"].'</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionView($label);
				break;
			case 'edit':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["editDataset_Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">'.$label["Table_Title"].'</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionEdit($label);
				break;
			case 'delete':
				if ($GLOBALS['showTitle'] == true or true) {
					echo "<h3>".sprintf($label["deleteDataset_Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">'.$label["Table_Title"].'</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionDelete($label);
				break;
			case 'release':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["releaseDataset_Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">Tabelle</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionRelease($label);
				break;
			case 'stat':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["Statistik_Title"], $GLOBALS['organisationName'])."</h3>";
				}
				echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">Tabelle</button></p>';
				echo '</form>';
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionStatistic($label);
				break;
			case 'report':
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["Report_Title"], $GLOBALS['organisationName'])."</h3>";
				}
				/*echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">'.$label["Table_Title"].'</button></p>';
				echo '</form>';*/
				//echo "<p><a href=http://".$GLOBALS['tandem_root_path']."/index.php?action=table>TABLE</a></p>";
				actionReport($label);
				break;
			default:
				if ($GLOBALS['showTitle'] == true) {
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				actionTable($label);
				break;
		}
		echo "<p>".db_disconnectDB($db_status['server'])."</p>";
	} else
	{
		echo "DB ERROR";
	}
} else {
	echo "DB ERROR";
}

echo "</div>";
?>
