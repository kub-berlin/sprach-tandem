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

mb_internal_encoding("UTF-8");
include './functions.php';
$label = setLanguage(htmlentities($_GET["lang"]));

?><!DOCTYPE html>
<html
	lang="<?php e($label['lang']) ?>"
	dir="<?php e(($label["lang"] == 'fa' or $label["lang"] == 'ar') ? 'rtl' : 'ltr') ?>"
>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="https://kub-berlin.org/templates/kub/css/kub-<?php e(($label["lang"] == 'fa' or $label["lang"] == 'ar') ? 'rtl' : 'ltr') ?>.css" />
	<link rel="stylesheet" type="text/css" href="./style.css" />
	<title><?php e(sprintf($label["Title"], $GLOBALS['organisationName'])) ?></title>
</head>

<body>

<?php
global $server;
$server = db_connectDB();

if ($server != null){
	scheduleReminder($label);

	$action = isset($_GET['action']) ? $_GET['action'] : 'table';

	if ($GLOBALS['showTitle'] == true){
		echo "<h3>".sprintf($label["Title"], $GLOBALS['organisationName'])."</h3>";
	}

	switch ($action) {
		case 'table':
			echo '<form action="index.php?action=add&lang='.$label["lang"].'" method="POST">';
			echo '<p><button type="submit">';
			icon('add_user');
			echo "\n";
			e($label["Add_Title"]);
			echo '</button></p>';
			echo '</form>';

			actionTable($label);
		break;
		case 'add':
			actionAdd($label);
			break;
		case 'view':
			actionView($label);
			break;
		case 'edit':
			actionEdit($label);
			break;
		case 'delete':
			actionDelete($label);
			break;
		case 'release':
			actionRelease($label);
			break;
		case 'stat':
			echo '<form action="index.php?action=table&lang='.$label["lang"].'" method="POST" >';
			echo '<p><button type="submit">';
			icon('menu');
			echo 'Tabelle</button></p>';
			echo '</form>';
			actionStatistic($label);
			break;
		case 'report':
			actionReport($label);
			break;
		default:
			actionTable($label);
			break;
	}
	db_disconnectDB($server);
}
?>

</body>
</html>
