<?php

/* ===================================
#
#   DATENBANK
#
   ===================================*/

function db_log($location, $error, $sql='')
{
	if ($GLOBALS['debug'] == 1)
	{
		echo '<p>' . htmlspecialchars($sql) . '</p>';
		echo '<p>in function ' . htmlspecialchars($location) . ':' . htmlspecialchars($error->getMessage()) . '</p>';
	}
	writeLog("DB $location: $sql\nERROR MESSAGE: ".$error->getMessage());
}

function db_connectDB()
{
	//  Connect to DB
	try {
		$pdo = new PDO('mysql:host='.$GLOBALS['mysql_server'].';dbname='.$GLOBALS['db_name'],$GLOBALS['mysql_username'], $GLOBALS['mysql_password']);
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

function db_createTandemTable($pdo){
	$ret = 0;

	$sql = 'CREATE TABLE IF NOT EXISTS '. $GLOBALS['db_table_name'] .' ('.
		'`'.$GLOBALS['db_colName_id'] .'` INT AUTO_INCREMENT PRIMARY KEY UNIQUE KEY NOT NULL, '.
		'`'.$GLOBALS['db_colName_name'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_alter'] .'` VARCHAR(25) NOT NULL, '.
		'`'.$GLOBALS['db_colName_ort'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_email'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_datum'] .'` DATE NOT NULL, '.
		'`'.$GLOBALS['db_colName_geschlecht'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_spracheGes'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_spracheAng'] .'` VARCHAR(50) NOT NULL, '.
		'`'.$GLOBALS['db_colName_skills'] .'` INT NOT NULL, '.
		'`'.$GLOBALS['db_colName_antworten'] .'` INT NOT NULL, '.
		'`'.$GLOBALS['db_colName_beschreibung'] .'` VARCHAR(255) NOT NULL, '.
		'`'.$GLOBALS['db_colName_lang'] .'` VARCHAR(2) NOT NULL, '.
		'`'.$GLOBALS['db_colName_released'] .'` INT(1) DEFAULT 0, '.
		'`'.$GLOBALS['db_colName_hash'] .'` VARCHAR(50) NOT NULL);';

	try{
		$pdo->query($sql);
		$ret = 1;
		writeLog('DB CREATE TABLE: '.$sql.'\n');
	} catch (PDOException $e) {
		db_log('db_createTandemTable', $e, $sql);
		$ret = -1;
	}
	return $ret;
}


function db_add_dataset($pdo, $name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache)
{
	array('db_erg' => -1, 'id' => -1, 'hash' => -1);
	$datum = date('Y-m-d', time());

	$hash = substr(md5(uniqid((string)microtime(true))), 0, 16);

	try
	{
		$sql = "INSERT INTO `".$GLOBALS['db_table_name']."` (
		`".$GLOBALS['db_colName_name']."` ,
		`".$GLOBALS['db_colName_alter']."` ,
		`".$GLOBALS['db_colName_geschlecht']."` ,
		`".$GLOBALS['db_colName_skills']."` ,
		`".$GLOBALS['db_colName_spracheAng']."` ,
		`".$GLOBALS['db_colName_spracheGes']."` ,
		`".$GLOBALS['db_colName_datum']."` ,
		`".$GLOBALS['db_colName_beschreibung']."` ,
		`".$GLOBALS['db_colName_ort']."` ,
		`".$GLOBALS['db_colName_email']."` ,
		`".$GLOBALS['db_colName_antworten']."` ,
		`".$GLOBALS['db_colName_hash']."` ,
		`".$GLOBALS['db_colName_lang']."`
		)
		VALUES (:name, :alter, :geschlecht, :skills, :spracheAng, :spracheGes, :datum, :beschreibung, :ort, :email, 0, :hash, :sprache)";

		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':name', $name);
		$statement -> bindParam(':alter', $alter);
		$statement -> bindParam(':geschlecht', $geschlecht);
		$statement -> bindParam(':skills', $skills);
		$statement -> bindParam(':spracheAng', $spracheAng);
		$statement -> bindParam(':spracheGes', $spracheGes);
		$statement -> bindParam(':datum', $datum);
		$statement -> bindParam(':beschreibung', $beschreibung);
		$statement -> bindParam(':ort', $ort);
		$statement -> bindParam(':email', $email);
		$statement -> bindParam(':hash', $hash);
		$statement -> bindParam(':sprache', $sprache);

		$db_erg = $statement->execute();

		$id = $pdo->lastInsertId();

		return array(
			'db_erg' => $db_erg,
			'id' => $id,
			'hash' => $hash);
	}
	catch (PDOException $e)
	{
		db_log('db_add_dataset', $e, $sql);
	}
}

function db_selectFormColumn($pdo, $colName)
{
	$ret = array();
	$sql = "SELECT ".$colName." FROM ".$GLOBALS['db_table_name']." WHERE `released`= 1 GROUP BY ".$colName;
	try {
		$statement = $pdo->query($sql);
		$ret = $statement->fetchAll();
	}  catch (PDOException $e) {
		db_log('db_selectFormColumn', $e, $sql);
		return -1;
	}
	return $ret;
}

function db_selectTableData($pdo, $filterAng, $filterGes, $label, $page)
{
	$ret = array();
	try {
		$sql = "SELECT * FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_released']."`= 1 ".
						(($filterAng == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheAng']."` = :filterAng").
						(($filterGes == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheGes']."` = :filterGes").
						" ORDER BY `".$GLOBALS['db_colName_datum']."` DESC LIMIT ".($page*$GLOBALS['table_page_size']).",".$GLOBALS['table_page_size'];

		$statement = $pdo->prepare($sql);
		if ($filterAng != $label['Table_filter_alle'])
			$statement -> bindParam(':filterAng', $filterAng);
		if ($filterGes != $label['Table_filter_alle'])
			$statement -> bindParam(':filterGes', $filterGes);
		$statement->execute();
		$ret = $statement->fetchAll();
	} catch (PDOException $e) {
		db_log('db_selectTableData', $e, $sql);
	}
	return $ret;

}

function db_countTableData($pdo, $filterAng, $filterGes, $label)
{
	$ret = 0;
	try
	{
		$sql = "SELECT COUNT(*) FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_released']."`= 1 ".
						(($filterAng == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheAng']."` = :filterAng").
						(($filterGes == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheGes']."` = :filterGes").
						" ORDER BY `".$GLOBALS['db_colName_datum']."` DESC";

		$statement = $pdo->prepare($sql);
		if ($filterAng != $label['Table_filter_alle'])
			$statement -> bindParam(':filterAng', $filterAng);

		if ($filterGes != $label['Table_filter_alle'])
			$statement -> bindParam(':filterGes', $filterGes);

		$statement->execute();
		$ret = $statement->fetchColumn();

	}
	catch (PDOException $e) {
		db_log('db_countTableData', $e, $sql);
	}
	return $ret;
}


function db_getDataSet($pdo, $id)
{
	$ret = array();
	try
	{
		$sql = "SELECT * FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_id']."`= :id";

		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':id', $id);
		$statement->execute();
		$ret = $statement->fetchAll();
	}
	catch (PDOException $e) {
		db_log('db_getDataSet', $e, $sql);
		$ret = -1;
	}
	return $ret;
}

function db_edit_dataset($pdo, $name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email)
{
	$datum = date('Y-m-d', time());

	try
	{
		$sql = "UPDATE `".$GLOBALS['db_table_name']."` SET
		`".$GLOBALS['db_colName_name']."` = :name,
		`".$GLOBALS['db_colName_alter']."` = :alter,
		`".$GLOBALS['db_colName_geschlecht']."` = :geschlecht,
		`".$GLOBALS['db_colName_skills']."` = :skills,
		`".$GLOBALS['db_colName_spracheAng']."` = :spracheAng,
		`".$GLOBALS['db_colName_spracheGes']."` = :spracheGes,
		`".$GLOBALS['db_colName_datum']."` = :datum,
		`".$GLOBALS['db_colName_beschreibung']."` = :beschreibung,
		`".$GLOBALS['db_colName_ort']."` = :ort,
		`".$GLOBALS['db_colName_email']."` = :email
		WHERE `".$GLOBALS['db_colName_id']."`= :id";
		$statement = $pdo->prepare($sql);
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':name', $name);
		$statement -> bindParam(':alter', $alter);
		$statement -> bindParam(':geschlecht', $geschlecht);
		$statement -> bindParam(':skills', $skills);
		$statement -> bindParam(':spracheAng', $spracheAng);
		$statement -> bindParam(':spracheGes', $spracheGes);
		$statement -> bindParam(':datum', $datum);
		$statement -> bindParam(':beschreibung', $beschreibung);
		$statement -> bindParam(':ort', $ort);
		$statement -> bindParam(':email', $email);
		$statement -> bindParam(':id', $id);

		$ret = $statement->execute();
	} catch (PDOException $e) {
		db_log('db_edit_dataset', $e, $sql);
		$ret = false;
	}

	return $ret;
}

function db_delete_DataSet($pdo, $id, $hash)
{

	try
	{
		$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
						$GLOBALS['db_colName_id'].'`= :id AND `'.
						$GLOBALS['db_colName_hash'].'` = :hash';
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':id', $id);
		$statement -> bindParam(':hash', $hash);
		$statement->execute();
		$count = $statement->fetchColumn();

		if ($count > 0)
		{
			$match = 1;
		} else {
			$match = 0;
		}
	}
	catch (PDOException $e)
	{
		$match = 0;
	}

	if ($match == 1)
	{

		try
		{
			$sql = "DELETE FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_id']."`= :id AND `".$GLOBALS['db_colName_hash']."` = :hash";
			$statement = $pdo->prepare($sql);
			$statement -> bindParam(':id', $id);
			$statement -> bindParam(':hash', $hash);
			$ret = $statement->execute();
		}
		catch (PDOException $e) {
			db_log('db_delete_DataSet', $e, $sql);
			$ret = -1;
		}
	} else
	{
		$ret = -1;
	}
	return $ret;
}

function db_release_DataSet($pdo, $id, $hash)
{
	try
	{
		$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
						$GLOBALS['db_colName_id'].'`= :id AND `'.
						$GLOBALS['db_colName_hash'].'` = :hash';
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':id', $id);
		$statement -> bindParam(':hash', $hash);
		$ret = $statement->execute();

		if ($ret)
		{
			$match = 1;
		} else {
			$match = 0;
		}
	} catch (PDOException $e) {
		$match = 0;
	}

	if ($match == 1)
	{
		try
		{
			$sql = "UPDATE `".$GLOBALS['db_table_name']."` SET `".$GLOBALS['db_colName_released']."` = 1 ".
						 "WHERE `".$GLOBALS['db_colName_id']."`= :id AND `".$GLOBALS['db_colName_hash']."` = :hash";
			$statement = $pdo->prepare($sql);
			$statement -> bindParam(':id', $id);
			$statement -> bindParam(':hash', $hash);
			$ret = $statement->execute();

		}
		catch (PDOException $e) {
			db_log('db_release_DataSet', $e, $sql);
			$ret = -1;
		}
	} else
	{
		$ret = -1;
	}
	return $ret;
}

//###############################
//
// STATISTICS
//
//##############################



function db_get_langPairs($pdo, $thisYear = false)
{

	$sql = 'SELECT '.$GLOBALS['db_colName_spracheAng'].', '.$GLOBALS['db_colName_spracheGes'].', '.$GLOBALS['db_colName_antworten'].', COUNT(*) AS count'.
		' FROM '.$GLOBALS['db_table_name'].' WHERE '.
		($thisYear ? $GLOBALS['db_colName_datum'].' > CONCAT(YEAR (CURDATE()), "-01-01") AND ' : '').
		"`".$GLOBALS['db_colName_released']."`=1".
		' GROUP BY '.$GLOBALS['db_colName_spracheAng'].', '.$GLOBALS['db_colName_spracheGes'].' ORDER BY Count DESC';

	try{
		$db_erg = $pdo->query( $sql )->fetchAll();
		$ret = $db_erg;
	}
	catch (PDOException $e)
	{
		db_log('db_get_langPairs', $e, $sql);
		$ret = -1;
	}
	return $ret;
}


function db_sum_answers($pdo, $spracheAng, $spracheGes)
{
	try
	{
		$sql = "SELECT * FROM `".$GLOBALS['db_table_name']."` WHERE `".
						$GLOBALS['db_colName_spracheAng']."`= :spracheAng AND `".
						$GLOBALS['db_colName_spracheGes']."`= :spracheGes AND `".
						$GLOBALS['db_colName_released']."`= 1";
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':spracheAng', $spracheAng);
		$statement -> bindParam(':spracheGes', $spracheGes);

		$statement->execute();
		$erg = $statement->fetchAll(PDO::FETCH_ASSOC);

		$sum = 0;
		foreach ($erg as $zeile){
			$sum = $sum + $zeile['antworten'];
		}
		$ret = $sum;

	}
	catch (PDOException $e)
	{
		db_log('db_sum_answers', $e, $sql);
	}
	return $ret;
}

function db_count_langPairs($pdo, $spracheAng, $spracheGes)
{

	try{
		$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
						$GLOBALS['db_colName_spracheAng'].'`= :spracheAng AND `'.
						$GLOBALS['db_colName_spracheGes'].'`= :spracheGes AND `'.
						$GLOBALS['db_colName_released'].'`= 1';
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':spracheAng', $spracheAng);
		$statement -> bindParam(':spracheGes', $spracheGes);

		$statement->execute();
		$ret = $statement->rowCount();
	}
	catch (PDOException $e) {
		db_log('db_count_langPairs', $e, $sql);
		$ret = -1;
	}
	return $ret;
}

function db_incr_answers($pdo, $id)
{
	try
	{
		$sql = "UPDATE `".$GLOBALS['db_table_name']."` SET `".
					$GLOBALS['db_colName_antworten']."` = ".$GLOBALS['db_colName_antworten']."+1 WHERE `".
					$GLOBALS['db_colName_id']."`= :id";
		$statement = $pdo->prepare($sql);
		$statement -> bindParam(':id', $id);

		$ret = $statement->execute();
	}
	catch (PDOException $e)
	{
		db_log('db_incr_answers', $e, $sql);
	}
	return $ret;
}

function db_getReminderDatasetsReleased($pdo)
{
	$ret = array();
	$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE (to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_cyclic'].' = 0 AND `'.$GLOBALS['db_colName_released'].'`=1;';
	try
	{
		$db_erg = $pdo->query( $sql )->fetchAll();
		$ret = $db_erg;
	}
	catch (PDOException $e)
	{
		db_log('db_getReminderDatasetsReleased', $e, $sql);
		//$ret = false;
	}
	return $ret;
}

function db_getReminderDatasetsNotReleased($pdo)
{
	$ret = array();
	$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE ('.
			'(to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_first'].' = 0 OR '.
			'(to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_cyclic'].' = 0 '.
			') AND `'.$GLOBALS['db_colName_released'].'`=0;';

	try{
		$db_erg = $pdo->query( $sql )->fetchAll();
		$ret = $db_erg;
	}
	catch (PDOException $e)
	{
		db_log('db_getReminderDatasetsNotReleased', $e, $sql);
	}

	return $ret;
}/**/
?>
