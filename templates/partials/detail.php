<h3><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_name']])) ?></h3>
<table class="form_table">
	<tr><td><b><?php e($label['View_alter']) ?>:</b></td><td><?php e($zeile[$GLOBALS['db_colName_alter']]) ?></td></tr>
	<tr><td><b><?php e($label['View_geschlecht']) ?>:</b></td><td><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_geschlecht']])) ?></td></tr>
	<tr><td><b><?php e($label['View_spracheAng']) ?>: </b></td><td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])]) ?></td></tr>
	<tr><td><b><?php e($label['View_spracheGes']) ?>: </b></td><td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])]) ?></td></tr>
	<tr><td><b><?php e($label['View_skills']) ?>:</b></td><td>
		<?php e($label['View_skills_' . $zeile[$GLOBALS['db_colName_skills']]]) ?>
	</td></tr>
	<tr><td><b><?php e($label['View_ort']) ?>: </b></td><td><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_ort']])) ?></td></tr>
	<tr><td><b><?php e($label['View_beschreibung']) ?>: </b></td><td>
		<textarea name="lizenz" cols="50" rows="10" readonly style="width: 100%"><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']])) ?></textarea>
	</td></tr>
</table>
