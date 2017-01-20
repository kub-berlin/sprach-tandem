<?php

//#############################
//
//   DATENBANK
//
//#############################

function db_connectDB()
{
	//  Connect to DB
	$ret = -1;
	$server = mysql_connect($GLOBALS['mysql_server'],
		$GLOBALS['mysql_username'],
		$GLOBALS['mysql_password']);
	if ($server != false)
	{
		$ret = $server;
	} else {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>in function db_connectDB:".mysql_error()."</p>";
		}
		writeLog('DB CONNECT: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}
	return $ret;
}

function db_selectDB($server)
{
	$ret = -1;
	mysql_set_charset('utf8',$server);
	$db_sel = mysql_select_db($GLOBALS['db_name']);
	if ($db_sel == true)
	{
		$ret = 1;
	} else
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>in function db_selectDB:".mysql_error()."</p>";
		}
	}
	return $ret;
}

function db_disconnectDB($server)
{
	$ret = mysql_close($server);
	return $ret;
}

function db_createDB($server)
{
	$ret = 0;
	$sql = 'CREATE DATABASE '.$GLOBALS['db_name'].';';
	if (mysql_query($sql, $server)) {
		$ret = 1;
		writeLog('DB CREATE DB: '.$sql.' erg: '.$db_erg.'\n');
	} else {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_createDB:".mysql_error()."</p>";
		}
		$ret = -1;
		writeLog('DB CREATE DB: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}
	return $ret;
}

function db_createTandemTable($server){
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

	if (mysql_query($sql, $server)) {
		$ret = 1;
		writeLog('DB CREATE TABLE: '.$sql.' erg: '.$db_erg.'\n');
	} else {
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_createTable:".mysql_error()."</p>";
		}
		writeLog('DB CREATE TABLE: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
		$ret = -1;
	}
	return $ret;
}

function db_add_dataset($name, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email, $sprache)
{
	array('db_erg' => -1, 'id' => -1, 'hash' => -1);
	$datum = strip_tags(date("Y-m-d", time()));

	$hash = substr(md5(uniqid((string)microtime(true))), 0, 16);

	$sql = 'INSERT INTO `'.$GLOBALS['db_table_name'].'` (
		`'.$GLOBALS['db_colName_name'].'` ,
		`'.$GLOBALS['db_colName_alter'].'` ,
		`'.$GLOBALS['db_colName_geschlecht'].'` ,
		`'.$GLOBALS['db_colName_skills'].'` ,
		`'.$GLOBALS['db_colName_spracheAng'].'` ,
		`'.$GLOBALS['db_colName_spracheGes'].'` ,
		`'.$GLOBALS['db_colName_datum'].'` ,
		`'.$GLOBALS['db_colName_beschreibung'].'` ,
		`'.$GLOBALS['db_colName_ort'].'` ,
		`'.$GLOBALS['db_colName_email'].'` ,
		`'.$GLOBALS['db_colName_id'].'` ,
		`'.$GLOBALS['db_colName_antworten'].'` ,
		`'.$GLOBALS['db_colName_hash'].'` ,
		`'.$GLOBALS['db_colName_lang'].'`
		)
		VALUES ("'.
		mysql_real_escape_string($name) .'", "'.
		mysql_real_escape_string($alter) .'", "'.
		mysql_real_escape_string($geschlecht) .'", "'.
		mysql_real_escape_string($skills) .'", "'.
		mysql_real_escape_string($spracheAng) .'", "'.
		mysql_real_escape_string($spracheGes) .'", "'.
		mysql_real_escape_string($datum) .'", "'.
		mysql_real_escape_string($beschreibung) .'", "'.
		mysql_real_escape_string($ort) .'", "'.
		mysql_real_escape_string($email) .'", NULL, 0,"'.
		mysql_real_escape_string($hash) .' ", "'.
		mysql_real_escape_string($sprache) .'"
	)';

	$db_erg = mysql_query ( $sql );
	if (!$db_erg)
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_add_dataset:".mysql_error()."</p>";
		}
		writeLog('DB ADD DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}

	$id = mysql_insert_id();
	$ret['db_erg'] = $db_erg;
	$ret['id'] = $id;
	$ret['hash'] = $hash;

	return $ret;
}

function db_selectFormColumn($colName)
{
	$sql = "SELECT ".$colName." FROM ".$GLOBALS['db_table_name']." WHERE `released`= 1 GROUP BY ".$colName."";

	$db_erg = mysql_query ( $sql );
	if ( ! $db_erg ){
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_selectFormColumn:".mysql_error()."</p>";
		}
		$ret = -1;
		writeLog('DB SELECT FROMCOLUMN: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	} else {
		$ret = $db_erg;
	}
	return $ret;
}

function db_selectTableData($filterAng, $filterGes, $label, $page)
{
	$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.$GLOBALS['db_colName_released'].'`= 1'.
		(($filterAng == $label['Table_filter_alle']) ? '' : ' AND `'.$GLOBALS['db_colName_spracheAng'].'` = "'.mysql_real_escape_string($filterAng).'"').''.
		(($filterGes == $label['Table_filter_alle']) ? '' : ' AND `'.$GLOBALS['db_colName_spracheGes'].'` = "'.mysql_real_escape_string($filterGes).'"').''.
		' ORDER BY `'.$GLOBALS['db_colName_datum'].'` DESC LIMIT '.($page*$GLOBALS['table_page_size']).','.$GLOBALS['table_page_size'].'';

	$db_erg = mysql_query ( $sql );
	if ( ! $db_erg ){
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_selectTableData:".mysql_error()."</p>";
		}
		writeLog('DB SELECT TABLEDATA: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
		$ret = -1;
	} else
	{
		$ret = $db_erg;
	}
	return $ret;
}

function db_countTableData($filterAng, $filterGes, $label)
{
	$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.$GLOBALS['db_colName_released'].'`= 1'.
		(($filterAng == $label['Table_filter_alle']) ? '' : ' AND `'.$GLOBALS['db_colName_spracheAng'].'` = "'.mysql_real_escape_string($filterAng).'"').''.
		(($filterGes == $label['Table_filter_alle']) ? '' : ' AND `'.$GLOBALS['db_colName_spracheGes'].'` = "'.mysql_real_escape_string($filterGes).'"').'';

	$db_erg = mysql_query ( $sql );

	if ( ! $db_erg ){
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_countTableData:".mysql_error()."</p>";
		}
		writeLog('DB COUNT TABLEDATA: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
		$ret = -1;
	} else
	{
		$ret =mysql_result($db_erg, 0);
	}
	return $ret;
}

function db_getDataSet($id)
{
	$sql = "SELECT * FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_id']."`=". mysql_real_escape_string($id);

	$db_erg = mysql_query ( $sql );
	if ( ! $db_erg ){
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_getDataSet:".mysql_error()."</p>";
		}
		writeLog('DB GET DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
		$ret = -1;
	} else
	{
		$ret = $db_erg;
	}
	return $ret;
}

function db_edit_dataset($name, $id, $alter, $geschlecht, $skills, $spracheAng, $spracheGes, $beschreibung, $ort, $email)
{
	$datum = strip_tags(date("Y-m-d", time()));

	$sql = 'UPDATE `'.$GLOBALS['db_table_name'].'` SET
		`'.$GLOBALS['db_colName_name'].'` = "'.mysql_real_escape_string($name).'",
		`'.$GLOBALS['db_colName_alter'].'` = "'.mysql_real_escape_string($alter).'",
		`'.$GLOBALS['db_colName_geschlecht'].'` = "'.mysql_real_escape_string($geschlecht).'",
		`'.$GLOBALS['db_colName_skills'].'` = "'.mysql_real_escape_string($skills).'",
		`'.$GLOBALS['db_colName_spracheAng'].'` = "'.mysql_real_escape_string($spracheAng).'",
		`'.$GLOBALS['db_colName_spracheGes'].'` = "'.mysql_real_escape_string($spracheGes).'",
		`'.$GLOBALS['db_colName_datum'].'` = "'.mysql_real_escape_string($datum).'",
		`'.$GLOBALS['db_colName_beschreibung'].'` = "'.mysql_real_escape_string($beschreibung).'",
		`'.$GLOBALS['db_colName_ort'].'` = "'.mysql_real_escape_string($ort).'",
		`'.$GLOBALS['db_colName_email'].'` = "'.mysql_real_escape_string($email).'"
		WHERE `'.$GLOBALS['db_colName_id'].'`= '.mysql_real_escape_string($id).'';

	$db_erg = mysql_query ( $sql );
	if (!$db_erg)
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_edit_dataset:".mysql_error()."</p>";
		}
		writeLog('DB EDIT DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}

	$id = mysql_insert_id();
	$ret = $db_erg;

	return $ret;
}

function db_delete_DataSet($id, $hash)
{
	$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
		$GLOBALS['db_colName_id'].'`='. mysql_real_escape_string($id).' AND `'.
		$GLOBALS['db_colName_hash'].'` = "'. mysql_real_escape_string($hash).'"';

	$db_erg = mysql_query ( $sql);

	if ( $db_erg )
	{
		if (mysql_result($db_erg, 0) > 0)
		{
			$match = 1;
		} else {
			$match = 0;
		}
	} else {
		$match = 0;
	}

	mysql_free_result($db_erg);

	if ($match == 1)
	{
		$sql = "DELETE FROM `".$GLOBALS['db_table_name']."` WHERE `".$GLOBALS['db_colName_id']."`=". mysql_real_escape_string($id).' AND `'.$GLOBALS['db_colName_hash'].'` = "'. mysql_real_escape_string($hash).'"';

		$db_erg = mysql_query ( $sql );
		if ( ! $db_erg ){
			if ($GLOBALS['debug'] == 1)
			{
				echo "<p>".$sql."</p>";
				echo "<p>in function db_delete_DataSet:".mysql_error()."</p>";
			}
			writeLog('DB DELETE DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
			$ret = -1;
		} else
		{
			$ret = $db_erg;
		}
	} else
	{
		$ret = -1;
	}
	return $ret;
}

function db_release_DataSet($id, $hash)
{
	$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
		$GLOBALS['db_colName_id'].'`='. mysql_real_escape_string($id).' AND `'.
		$GLOBALS['db_colName_hash'].'` = "'. mysql_real_escape_string($hash).'"';

	$db_erg = mysql_query ( $sql);

	if ( $db_erg )
	{
		if (mysql_result($db_erg, 0) > 0)
		{
			$match = 1;
		} else {
			$match = 0;
		}
	} else {
		$match = 0;
	}

	mysql_free_result($db_erg);

	if ($match == 1)
	{
		$sql = "UPDATE `".$GLOBALS['db_table_name']."` SET `".$GLOBALS['db_colName_released']."` = 1 WHERE `".$GLOBALS['db_colName_id']."`=". mysql_real_escape_string($id).' AND `'.$GLOBALS['db_colName_hash'].'` = "'. mysql_real_escape_string($hash).'"';

		$db_erg = mysql_query ( $sql );
		if ( ! $db_erg ){
			if ($GLOBALS['debug'] == 1)
			{
				echo "<p>".$sql."</p>";
				echo "<p>in function db_release_DataSet:".mysql_error()."</p>";
			}
			writeLog('DB RELEASE DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
			$ret = -1;
		} else
		{
			$ret = $db_erg;
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

function db_get_langPairs()
{
	$sql = 'SELECT * FROM '.$GLOBALS['db_table_name'].' WHERE `'.$GLOBALS['db_colName_released'].'`= 1'.
		' GROUP BY '.$GLOBALS['db_colName_spracheAng'].', '.$GLOBALS['db_colName_spracheGes'];

	$db_erg = mysql_query($sql);

	if ( ! $db_erg )
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_get_langPairs:".mysql_error()."</p>";
		}
		$ret = -1;
		writeLog('DB GET LANGPAIRS: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	} else
	{
		$ret = $db_erg;
	}
	return $ret;
}

function db_sum_answers($spracheAng, $spracheGes)
{
	$sql = 'SELECT SUM('.$GLOBALS['db_colName_antworten'].') FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
		$GLOBALS['db_colName_spracheAng'].'`= "'. mysql_real_escape_string($spracheAng).'" AND `'.
		$GLOBALS['db_colName_spracheGes'].'`= "'. mysql_real_escape_string($spracheGes).'" AND `'.
		$GLOBALS['db_colName_released'].'`= 1';

	$db_erg = mysql_query($sql);

	if ( ! $db_erg )
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_count_langPairs:".mysql_error()."</p>";
		}
		$ret = -1;
		writeLog('DB SUM ANSWERS: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	} else
	{
		$ret = mysql_result($db_erg, 0);
	}
	return $ret;
}

function db_count_langPairs($spracheAng, $spracheGes)
{
	$sql = 'SELECT COUNT(*) FROM `'.$GLOBALS['db_table_name'].'` WHERE `'.
		$GLOBALS['db_colName_spracheAng'].'`= "'. mysql_real_escape_string($spracheAng).'" AND `'.
		$GLOBALS['db_colName_spracheGes'].'`= "'. mysql_real_escape_string($spracheGes).'" AND `'.
		$GLOBALS['db_colName_released'].'`= 1';

	$db_erg = mysql_query($sql);

	if ( ! $db_erg )
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_count_langPairs:".mysql_error()."</p>";
		}
		$ret = -1;
		writeLog('DB COUNT LANGPAIRS: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	} else
	{
		$ret = mysql_result($db_erg, 0);
	}
	return $ret;
}

function db_incr_answers($id)
{

	$sql = 'UPDATE `'.$GLOBALS['db_table_name'].'` SET `'.
		$GLOBALS['db_colName_antworten'].'` = '.$GLOBALS['db_colName_antworten'].'+1 WHERE `'.
		$GLOBALS['db_colName_id'].'`='.mysql_real_escape_string($id);

	$db_erg = mysql_query($sql);
	if (!$db_erg)
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function db_edit_dataset:".mysql_error()."</p>";
		}
		writeLog('DB EDIT DATASET: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}

	$id = mysql_insert_id();
	$ret = $db_erg;

	return $ret;
}

function db_getReminderDatasetsReleased()
{
	$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE (to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_cyclic'].' = 0 AND `'.$GLOBALS['db_colName_released'].'`=1;';

	$db_erg = mysql_query($sql);
	if (!$db_erg)
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function  db_getReminderDatasetsReleased:".mysql_error()."</p>";
		}
		writeLog('DB GET REMINDER RELESASED: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}

	$ret = $db_erg;

	return $ret;
}

function db_getReminderDatasetsNotReleased()
{
	$sql = 'SELECT * FROM `'.$GLOBALS['db_table_name'].'` WHERE ('.
			'(to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_first'].' = 0 OR '.
			'(to_days( `'.$GLOBALS['db_colName_datum'].'` ) - to_days( current_date )) %'.$GLOBALS['reminder_cyclic'].' = 0 '.
			') AND `'.$GLOBALS['db_colName_released'].'`=0;';

	$db_erg = mysql_query($sql);
	if (!$db_erg)
	{
		if ($GLOBALS['debug'] == 1)
		{
			echo "<p>".$sql."</p>";
			echo "<p>in function  db_getReminderDatasetsNotReleased:".mysql_error()."</p>";
		}
		writeLog('DB GET REMINDER NOT RELESASED: '.$sql.' erg: '.$db_erg.'\nERROR MESSAGE: '.mysql_error());
	}

	$ret = $db_erg;

	return $ret;
}
?>
