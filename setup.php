<?php

include './functions.php';

/*echo '<style type="text/css">';
echo '@charset "UTF-8";';
echo '@import "./style.css"';
echo '</style>';

echo '<div class=tandem_body>';*/

//$label = setLanguage(htmlentities($_GET["lang"]));

echo "<p> Verbinde mit MySQL-Server...";
$server = db_connectDB();
if ($server > 0)
{
	echo $server;
	echo "erfolgreich!</p>";

	$log = createLog();
	echo "<p> Create Logfile...";
	if ($log == true) {
		echo "erfolgreich!</p>";

		if (db_selectDB($server) < 0) {
			echo "<p> Create Database...";
			$createDB = db_createDB($server);
			if ($createDB == 1) {
				echo "erfolgreich!</p>";
			} else {
				echo "fehlgeschlagen! </p>";
			}
		} else {
			echo "existiert bereits! </p>";
		}
		echo "<p> Select Database...";
		$selectDB = db_selectDB($server);
		if ($selectDB > 0) {
			echo "erfolgreich!</p>";

			echo "<p> Create DB Table...";
			if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$GLOBALS['db_table_name']."'"))!=1) {
				$createTable = db_createTandemTable($server);
			} else {
				$createTable = 1;
			}
			if ($createTable > 0) {
				echo "erfolgreich!</p>";

				echo "<p> Email-Test...";
				if (isValidEmail($GLOBALS['email_orga'])) {
					$email = sendEmail($GLOBALS['email_orga'], "Sprach-Tandem Email Test", "Hallo, das ist der Email-Test", $GLOBALS['email_orga']);
					if ($email == true) {
						echo "erfolgreich!</p>";
					}
				} else {
					echo "fehlgeschlagen! Emailadresse nicht valide.</p>";
				}
			} else {
				echo "fehlgeschlagen! </p>";
			}
		} else {
				echo "fehlgeschlagen! </p>";
		}
	} else {
		echo "fehlgeschlagen! </p>";
	}

	db_disconnectDB($db_status['server']);
} else
{
	echo "fehlgeschlagen! </p>";
}
echo "</div>"
?>
