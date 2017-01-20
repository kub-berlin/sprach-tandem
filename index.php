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

echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';

echo '<style type="text/css">';
echo '@charset "UTF-8";';
echo '@import "./style.css"';
echo '</style>';
mb_internal_encoding("UTF-8");
include './functions.php';
echo '<div class=tandem_body>';

$heading = '<h3>'.$GLOBALS['organisationName'].'-Sprach-Tandem';

$label = setLanguage(htmlentities($_GET["lang"]));

global $server;
$server = db_connectDB();

if ($server != null){
		// ==============
		//
		// REMINDER
		//
		// ==============
		scheduleReminder($label);

		$action = htmlentities($_GET["action"]);

		if ($action == '') {
			$action = "table";
		}

		switch ($action) {
			case 'table':
				if ($GLOBALS['showTitle'] == true){
					"<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				echo '<form action="index.php?action=add&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_add">'.$label["Add_Title"].'</button></p>'; //<div id="image_button_add"></div>
				echo '</form>';
				actionTable($label);
			break;
			case 'add':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				actionAdd($label);
				break;
			case 'view':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
	 			actionView($label);
	 			break;
			case 'edit':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["editDataset_Title"], $GLOBALS['organisationName'])."</h3>";
				}
	 			actionEdit($label);
	 			break;
			case 'delete':

	 			actionDelete($label);
	 			break;
			case 'release':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["releaseDataset_Title"], $GLOBALS['organisationName'])."</h3>";
				}
	 			actionRelease($label);
	 			break;
			case 'stat':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["Statistik_Title"], $GLOBALS['organisationName'])."</h3>";
				}
	 			echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
				echo '<p><button type="submit" class="button_menue_table">Tabelle</button></p>';
				echo '</form>';
	 			actionStatistic($label);
	 			break;
			case 'report':
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["Report_Title"], $GLOBALS['organisationName'])."</h3>";
				}
	 			actionReport($label);
	 			break;
			default:
				if ($GLOBALS['showTitle'] == true){
					echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
				}
				actionTable($label);
				break;
		}
		db_disconnectDB($server);

} else {
	echo "DB ERROR";
}

echo "</div>";
?>
