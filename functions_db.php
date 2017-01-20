<?php

/* ===================================
#
#   DATENBANK
#
   ===================================*/

function db_connectDB()
{
	//  Connect to DB
	try {
		$pdo = new PDO('mysql:host='.$GLOBALS['mysql_server'].';dbname='.$GLOBALS['db_name'],$GLOBALS['mysql_username'], $GLOBALS['mysql_password']);
		$ret = $pdo;
		mysql_set_charset('utf8',$server);
		$pdo->query("SET NAMES UTF8");
	} catch (PDOException $e) {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>in function db_connectDB:".$e->getMessage()."</p>";
		}
		writeLog('DB CONNECT: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
		$ret = -1;
	}
	return $ret;
}


function db_disconnectDB($pdo)
{
	$pdo = null;
	//$ret = mysql_close($server);
	return 1;
}

function db_createDB($pdo)
{
	$ret = 0;
	try{
		$sql = 'CREATE DATABASE '.$GLOBALS['db_name'].';';
		writeLog('DB CREATE DB: '.$sql.'\n');
		$pdo->query($sql);
		$ret = 1;
	} catch (PDOException $e) {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_createDB:".$e->getMessage()."</p>";
		}
		$ret = -1;
		writeLog('DB CREATE DB: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
	}
	return $ret;
}

function db_createTandemTable($pdo){
	$ret = 0;

	$sql = 'CREATE TABLE '. $GLOBALS['db_table_name'] .' ('.
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_createTable:".$e->getMessage()."</p>";
		}
		writeLog('DB CREATE TABLE: '.$sql.'\nERROR MESSAGE: '.$$e->getMessage());
		$ret = -1;
	}
	return $ret;
}


function db_add_dataset($pdo, $name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache)
{
	array('db_erg' => -1, 'id' => -1, 'hash' => -1);
	$datum = strip_tags(date("Y-m-d", time()));

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
		`".$GLOBALS['db_colName_id']."` ,
		`".$GLOBALS['db_colName_antworten']."` ,
		`".$GLOBALS['db_colName_hash']."` ,
		`".$GLOBALS['db_colName_lang']."`
		)
		VALUES (:name, :alter, :geschlecht, :skills, :spracheAng, :spracheGes, :datum, :beschreibung, :ort, :email, NULL, 0, :hash, :sprache)";

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

  }
  catch (PDOException $e)
  {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_add_dataset:".$e->getMessage()."</p>";
		}
		writeLog('DB ADD DATASET: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
  }

	$id = $pdo->lastInsertId();
	$ret['db_erg'] = $db_erg;
	$ret['id'] = $id;
	$ret['hash'] = $hash;

	return $ret;
}

function db_selectFormColumn($pdo, $colName)
{
	$ret = array();
	try {
		$statement = $pdo->query("SELECT ".$colName." FROM ".$GLOBALS['db_table_name']." WHERE `released`= 1 GROUP BY ".$colName);
		$ret = $statement->fetchAll();
	}  catch (PDOException $e) {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_selectFormColumn:".$e->getMessage()."</p>";
		}
		return -1;
		writeLog('DB SELECT FROMCOLUMN: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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
	}  catch (PDOException $e) {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_selectTableData:".$e->getMessage()."</p>";
		}
		writeLog('DB SELECT TABLEDATA: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
	}
	return $ret;

}

function db_countTableData($pdo, $filterAng, $filterGes, $label)
{
	$ret = 0;
	try
	{
		$sql = "SELECT * FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_released']."`= 1 ".
						(($filterAng == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheAng']."` = :filterAng").
						(($filterGes == $label['Table_filter_alle']) ? "" : " AND `".$GLOBALS['db_colName_spracheGes']."` = :filterGes").
						" ORDER BY `".$GLOBALS['db_colName_datum']."` DESC";

		$statement = $pdo->prepare($sql);
		if ($filterAng != $label['Table_filter_alle'])
			$statement -> bindParam(':filterAng', $filterAng);

		if ($filterGes != $label['Table_filter_alle'])
			$statement -> bindParam(':filterGes', $filterGes);

		$statement->execute();
		$ret = $statement->rowCount();

	}
	catch (PDOException $e) {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_countTableData:".$e->getMessage()."</p>";
		}
		writeLog('DB COUNT TABLEDATA: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_getDataSet:".$e->getMessage()."</p>";
		}
		writeLog('DB GET DATASET: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
		$ret = -1;
	}
	return $ret;
}

function db_edit_dataset($pdo, $name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email)
{
	$datum = strip_tags(date("Y-m-d", time()));

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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_edit_dataset:".$e->getMessage()."</p>";
		}
		writeLog('DB EDIT DATASET: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
		$ret = false;
  }

	//$id = mysql_insert_id();
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
		$count = $statement->rowCount() > 0;

		if ($count)
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
	//mysql_free_result($db_erg);

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
			if ($GLOBALS['debug'] == 1)
			{
				echo "<p>".$sql."</p>";
				echo "<p>in function db_delete_DataSet:".$e->getMessage()."</p>";
			}
			writeLog('DB DELETE DATASET: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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

	mysql_free_result($db_erg);

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
			if ($GLOBALS['debug'] == 1)
			{
				echo "<p>".$sql."</p>";
				echo "<p>in function db_release_DataSet:".$e->getMessage()."</p>";
			}
			writeLog('DB RELEASE DATASET: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_get_langPairs:".$e->getMessage()."</p>";
		}
		$ret = -1;
		writeLog('DB GET LANGPAIRS: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_count_langPairs:".$e->getMessage()."</p>";
		}
		writeLog('DB SUM ANSWERS: '.$sql.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_count_langPairs:".$e->getMessage()."</p>";
		}
		$ret = -1;
		writeLog('DB COUNT LANGPAIRS: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_edit_dataset:".$e->getMessage()."</p>";
		}
		writeLog('DB EDIT DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function  db_getReminderDatasetsReleased:".$e->getMessage()."</p>";
		}
		writeLog('DB GET REMINDER RELESASED: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.$e->getMessage());
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
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function  db_getReminderDatasetsNotReleased:".$e->getMessage()."</p>";
		}
		writeLog('DB GET REMINDER NOT RELESASED: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.$e->getMessage());
	}

	return $ret;
}/**/
?>
