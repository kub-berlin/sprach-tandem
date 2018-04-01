<?php

include './functions.php';

echo "<p> Verbinde mit Datenbank...";
$server = db_connectDB();
if ($server)
{
    echo "erfolgreich!</p>";

    $log = createLog();
    echo "<p> Create Logfile...";
    if ($log == true) {
        echo "erfolgreich!</p>";

        echo "<p> Create DB Table...";
        $createTable = db_createTandemTable($server);

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

    db_disconnectDB($server);
} else
{
    echo "fehlgeschlagen! </p>";
}
echo "</div>"
