<?php

declare(strict_types=1);

/* ===================================
#
#   DATENBANK
#
   ===================================*/

function db_log($location, $error, $sql='')
{
    if ($GLOBALS['debug'] == 1) {
        echo '<p>' . htmlspecialchars($sql) . '</p>';
        echo '<p>in function ' . htmlspecialchars($location) . ':' . htmlspecialchars($error->getMessage()) . '</p>';
    }
    writeLog("DB $location: $sql\nERROR MESSAGE: ".$error->getMessage());
}

function db_connectDB()
{
    try {
        $pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        db_log('db_connectDB', $e);
    }
}

function db_disconnectDB($pdo)
{
    $pdo = null;
    return 1;
}

function db_createTandemTable($pdo)
{
    $sql = "CREATE TABLE IF NOT EXISTS {$GLOBALS['db_table_name']} (
        `{$GLOBALS['db_colName_id']}` INT AUTO_INCREMENT PRIMARY KEY UNIQUE KEY NOT NULL,
        `{$GLOBALS['db_colName_name']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_alter']}` VARCHAR(25) NOT NULL,
        `{$GLOBALS['db_colName_ort']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_email']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_datum']}` DATE NOT NULL,
        `{$GLOBALS['db_colName_geschlecht']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_spracheGes']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_spracheAng']}` VARCHAR(50) NOT NULL,
        `{$GLOBALS['db_colName_skills']}` INT NOT NULL,
        `{$GLOBALS['db_colName_antworten']}` INT NOT NULL,
        `{$GLOBALS['db_colName_beschreibung']}` VARCHAR(255) NOT NULL,
        `{$GLOBALS['db_colName_lang']}` VARCHAR(2) NOT NULL,
        `{$GLOBALS['db_colName_released']}` INT(1) DEFAULT 0,
        `{$GLOBALS['db_colName_hash']}` VARCHAR(50) NOT NULL)";

    try {
        $pdo->query($sql);
        writeLog('DB CREATE TABLE: '.$sql.'\n');
        return 1;
    } catch (PDOException $e) {
        db_log('db_createTandemTable', $e, $sql);
        return -1;
    }
}

function db_add_dataset($pdo, $name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache)
{
    $datum = date('Y-m-d', time());

    $hash = substr(md5(uniqid((string)microtime(true))), 0, 16);

    $sql = "INSERT INTO `{$GLOBALS['db_table_name']}` (
            `{$GLOBALS['db_colName_name']}`,
            `{$GLOBALS['db_colName_alter']}`,
            `{$GLOBALS['db_colName_geschlecht']}`,
            `{$GLOBALS['db_colName_skills']}`,
            `{$GLOBALS['db_colName_spracheAng']}`,
            `{$GLOBALS['db_colName_spracheGes']}`,
            `{$GLOBALS['db_colName_datum']}`,
            `{$GLOBALS['db_colName_beschreibung']}`,
            `{$GLOBALS['db_colName_ort']}`,
            `{$GLOBALS['db_colName_email']}`,
            `{$GLOBALS['db_colName_antworten']}`,
            `{$GLOBALS['db_colName_hash']}`,
            `{$GLOBALS['db_colName_lang']}`
        ) VALUES (:name, :alter, :geschlecht, :skills, :spracheAng, :spracheGes, :datum, :beschreibung, :ort, :email, 0, :hash, :sprache)";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':alter', $alter);
        $statement->bindParam(':geschlecht', $geschlecht);
        $statement->bindParam(':skills', $skills);
        $statement->bindParam(':spracheAng', $spracheAng);
        $statement->bindParam(':spracheGes', $spracheGes);
        $statement->bindParam(':datum', $datum);
        $statement->bindParam(':beschreibung', $beschreibung);
        $statement->bindParam(':ort', $ort);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':hash', $hash);
        $statement->bindParam(':sprache', $sprache);

        $db_erg = $statement->execute();

        $id = $pdo->lastInsertId();

        return array(
            'db_erg' => $db_erg,
            'id' => $id,
            'hash' => $hash);
    } catch (PDOException $e) {
        db_log('db_add_dataset', $e, $sql);
    }
}

function db_selectFormColumn($pdo, $colName)
{
    $sql = "SELECT DISTINCT $colName FROM {$GLOBALS['db_table_name']} WHERE `{$GLOBALS['db_colName_released']}` = 1 ORDER BY $colName";

    try {
        $statement = $pdo->query($sql);
        return $statement->fetchAll();
    } catch (PDOException $e) {
        db_log('db_selectFormColumn', $e, $sql);
        return -1;
    }
}

function db_selectTableData($pdo, $filterAng, $filterGes, $label, $page)
{
    $offset = $page * $GLOBALS['table_page_size'];

    $sql = "SELECT * FROM `{$GLOBALS['db_table_name']}` WHERE `{$GLOBALS['db_colName_released']}` = 1";
    if ($filterAng != $label['Table_filter_alle']) {
        $sql .= " AND `{$GLOBALS['db_colName_spracheAng']}` = :filterAng";
    }
    if ($filterGes != $label['Table_filter_alle']) {
        $sql .= " AND `{$GLOBALS['db_colName_spracheGes']}` = :filterGes";
    }
    $sql .= " ORDER BY `{$GLOBALS['db_colName_datum']}` DESC LIMIT $offset, {$GLOBALS['table_page_size']}";

    try {
        $statement = $pdo->prepare($sql);
        if ($filterAng != $label['Table_filter_alle']) {
            $statement->bindParam(':filterAng', $filterAng);
        }
        if ($filterGes != $label['Table_filter_alle']) {
            $statement->bindParam(':filterGes', $filterGes);
        }
        $statement->execute();
        return $statement->fetchAll();
    } catch (PDOException $e) {
        db_log('db_selectTableData', $e, $sql);
        return array();
    }
}

function db_countTableData($pdo, $filterAng, $filterGes, $label)
{
    $sql = "SELECT COUNT(*) FROM `{$GLOBALS['db_table_name']}` WHERE `{$GLOBALS['db_colName_released']}` = 1";
    if ($filterAng != $label['Table_filter_alle']) {
        $sql .= " AND `{$GLOBALS['db_colName_spracheAng']}` = :filterAng";
    }
    if ($filterGes != $label['Table_filter_alle']) {
        $sql .= " AND `{$GLOBALS['db_colName_spracheGes']}` = :filterGes";
    }

    try {
        $statement = $pdo->prepare($sql);
        if ($filterAng != $label['Table_filter_alle']) {
            $statement->bindParam(':filterAng', $filterAng);
        }
        if ($filterGes != $label['Table_filter_alle']) {
            $statement->bindParam(':filterGes', $filterGes);
        }
        $statement->execute();
        return $statement->fetchColumn();
    } catch (PDOException $e) {
        db_log('db_countTableData', $e, $sql);
        return 0;
    }
}

function db_getDataSet($pdo, $id)
{
    $sql = "SELECT * FROM `{$GLOBALS['db_table_name']}` WHERE `{$GLOBALS['db_colName_id']}`= :id";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->fetchAll();
    } catch (PDOException $e) {
        db_log('db_getDataSet', $e, $sql);
        $ret = -1;
    }
}

function db_edit_dataset($pdo, $name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email)
{
    $datum = date('Y-m-d', time());
    $sql = "UPDATE `{$GLOBALS['db_table_name']}` SET
        `{$GLOBALS['db_colName_name']}` = :name,
        `{$GLOBALS['db_colName_alter']}` = :alter,
        `{$GLOBALS['db_colName_geschlecht']}` = :geschlecht,
        `{$GLOBALS['db_colName_skills']}` = :skills,
        `{$GLOBALS['db_colName_spracheAng']}` = :spracheAng,
        `{$GLOBALS['db_colName_spracheGes']}` = :spracheGes,
        `{$GLOBALS['db_colName_datum']}` = :datum,
        `{$GLOBALS['db_colName_beschreibung']}` = :beschreibung,
        `{$GLOBALS['db_colName_ort']}` = :ort,
        `{$GLOBALS['db_colName_email']}` = :email
        WHERE `{$GLOBALS['db_colName_id']}` = :id";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':alter', $alter);
        $statement->bindParam(':geschlecht', $geschlecht);
        $statement->bindParam(':skills', $skills);
        $statement->bindParam(':spracheAng', $spracheAng);
        $statement->bindParam(':spracheGes', $spracheGes);
        $statement->bindParam(':datum', $datum);
        $statement->bindParam(':beschreibung', $beschreibung);
        $statement->bindParam(':ort', $ort);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':id', $id);

        return $statement->execute();
    } catch (PDOException $e) {
        db_log('db_edit_dataset', $e, $sql);
        return false;
    }
}

function db_delete_DataSet($pdo, $id, $hash)
{
    $sql = "DELETE FROM `{$GLOBALS['db_table_name']}` WHERE `{$GLOBALS['db_colName_id']}` = :id AND `{$GLOBALS['db_colName_hash']}` = :hash";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':hash', $hash);
        $ret = $statement->execute();

        if ($statement->rowCount() != 1) {
            $ret = -1;
        }
    } catch (PDOException $e) {
        db_log('db_delete_DataSet', $e, $sql);
        $ret = -1;
    }

    return $ret;
}

function db_release_DataSet($pdo, $id, $hash)
{
    $sql = "UPDATE `{$GLOBALS['db_table_name']}` SET `{$GLOBALS['db_colName_released']}` = 1
        WHERE `{$GLOBALS['db_colName_id']}`= :id AND `{$GLOBALS['db_colName_hash']}` = :hash";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':hash', $hash);
        $ret = $statement->execute();

        if ($statement->rowCount() == 1) {
            $ret = -1;
        }
    } catch (PDOException $e) {
        db_log('db_release_DataSet', $e, $sql);
        $ret = -1;
    }

    return $ret;
}

function db_get_langPairs($pdo, $thisYear = false)
{
    $sql = "SELECT
            {$GLOBALS['db_colName_spracheAng']},
            {$GLOBALS['db_colName_spracheGes']},
            {$GLOBALS['db_colName_antworten']},
            COUNT(*) AS count
        FROM {$GLOBALS['db_table_name']} WHERE `{$GLOBALS['db_colName_released']}` = 1";
    if ($thisYear) {
        $sql .= " AND {$GLOBALS['db_colName_datum']} > CONCAT(YEAR (CURDATE()), '-01-01')";
    }
    $sql .= " GROUP BY {$GLOBALS['db_colName_spracheAng']}, {$GLOBALS['db_colName_spracheGes']} ORDER BY count DESC";

    try {
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        db_log('db_get_langPairs', $e, $sql);
        return -1;
    }
}

function db_sum_answers($pdo, $spracheAng, $spracheGes, $thisYear = false)
{
    $sql = "SELECT SUM({$GLOBALS['db_colName_antworten']}) FROM `{$GLOBALS['db_table_name']}` WHERE
        `{$GLOBALS['db_colName_spracheAng']}` = :spracheAng AND
        `{$GLOBALS['db_colName_spracheGes']}` = :spracheGes AND
        `{$GLOBALS['db_colName_released']}` = 1";
    if ($thisYear) {
        $sql .= " AND {$GLOBALS['db_colName_datum']} > CONCAT(YEAR (CURDATE()), '-01-01')";
    }

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':spracheAng', $spracheAng);
        $statement->bindParam(':spracheGes', $spracheGes);

        $statement->execute();
        return $statement->fetchColumn();
    } catch (PDOException $e) {
        db_log('db_sum_answers', $e, $sql);
    }
}

function db_incr_answers($pdo, $id)
{
    $sql = "UPDATE `{$GLOBALS['db_table_name']}` SET
        `{$GLOBALS['db_colName_antworten']}` = {$GLOBALS['db_colName_antworten']} + 1
        WHERE `{$GLOBALS['db_colName_id']}` = :id";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id);

        return $statement->execute();
    } catch (PDOException $e) {
        db_log('db_incr_answers', $e, $sql);
    }
}

function db_getReminderDatasetsReleased($pdo)
{
    $sql = "SELECT * FROM `{$GLOBALS['db_table_name']}` WHERE (
            (to_days( `{$GLOBALS['db_colName_datum']}` ) - to_days( current_date )) %{$GLOBALS['reminder_cyclic']} = 0
        ) AND `{$GLOBALS['db_colName_released']}` = 1";

    try {
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        db_log('db_getReminderDatasetsReleased', $e, $sql);
        return array();
    }
}

function db_getReminderDatasetsNotReleased($pdo)
{
    $sql = "SELECT * FROM `{$GLOBALS['db_table_name']}` WHERE (
            (to_days( `{$GLOBALS['db_colName_datum']}` ) - to_days( current_date )) %{$GLOBALS['reminder_first']} = 0 OR
            (to_days( `{$GLOBALS['db_colName_datum']}` ) - to_days( current_date )) %{$GLOBALS['reminder_cyclic']} = 0
        ) AND `{$GLOBALS['db_colName_released']}` = 0";

    try {
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        db_log('db_getReminderDatasetsNotReleased', $e, $sql);
        return array();
    }
}
