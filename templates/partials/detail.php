<h3><?php e($zeile[$GLOBALS['db_colName_name']]) ?></h3>

<table class="form_table" role="presentation">
	<tr>
		<th><?php e($label['View_alter']) ?></th>
		<td><?php e($zeile[$GLOBALS['db_colName_alter']]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_geschlecht']) ?></th>
		<td><?php e($zeile[$GLOBALS['db_colName_geschlecht']]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_spracheAng']) ?></th>
		<td><?php e($label[$zeile[$GLOBALS['db_colName_spracheAng']]]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_spracheGes']) ?></th>
		<td><?php e($label[$zeile[$GLOBALS['db_colName_spracheGes']]]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_skills']) ?></th>
		<td><?php e($label['View_skills_' . $zeile[$GLOBALS['db_colName_skills']]]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_ort']) ?></th>
		<td><?php e($zeile[$GLOBALS['db_colName_ort']]) ?></td>
	</tr>
	<tr>
		<th><?php e($label['View_beschreibung']) ?></th>
		<td><textarea name="lizenz" cols="50" rows="10" readonly style="width: 100%"><?php e($zeile[$GLOBALS['db_colName_beschreibung']]) ?></textarea></td>
	</tr>
</table>
