<h3><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_name']])) ?></h3>
<table>
	<colgroup id="form_col1"><col></colgroup>
	<colgroup id="form_col2"><col></colgroup>
	<tr><td><b><?php e($label['View_alter']) ?>:</b></td><td><?php e($zeile[$GLOBALS['db_colName_alter']]) ?></td></tr>
	<tr><td><b><?php e($label['View_geschlecht']) ?>:</b></td><td><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_geschlecht']])) ?></td></tr>
	<tr><td><b><?php e($label['View_spracheAng']) ?>: </b></td><td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])]) ?></td></tr>
	<tr><td><b><?php e($label['View_spracheGes']) ?>: </b></td><td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])]) ?></td></tr>
	<tr><td><b><?php e($label['View_skills']) ?>:</b></td><td>
		<?php e($label['View_skills_' . $zeile[$GLOBALS['db_colName_skills']]]) ?>
	</td></tr>
	<tr><td><b><?php e($label['View_ort']) ?>: </b></td><td><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_ort']])) ?></td></tr>
	<tr><td valign="top"><b><?php e($label['View_beschreibung']) ?>: </b></td><td>
		<textarea name="lizenz" cols="50" rows="10" readonly style="width: 100%"><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_beschreibung']])) ?></textarea>
	</td></tr>
	<tr><td></td><td align="right">
		<a href=index.php?action=report&lang=<?php e($label['lang']) ?>&tid=<?php e($id) ?>>
			<img src="./images/megaphone.svg" width="15px" height="15px" />
			<?php e($label['View_AnzeigeMelden']) ?>
		</a>
	</td></tr>
</table>

<form action="index.php?action=table&lang=<?php e($label["lang"]) ?>" method="POST">
	<p>
		<button type="submit" class="button_image">
			<div id="<?php e((($label['lang'] == "fa" or $label['lang'] == "ar") ? 'image_button_back_rtl' : 'image_button_back')) ?>"><?php e($label["zurueck"]) ?></div>
		</button>
	</p>
</form>

<hr>

<h3>
	<img src="./images/chat.svg" width="20px" height="20px">
	<?php e($label['View_Form_nachrichtAn']) ?>
	<?php e($zeile[$GLOBALS['db_colName_name']]) ?>
</h3>

<?php if ($senden == false) : ?>
	<?php sendMessageForm($label, "index.php?action=view&lang=".$label['lang']."&tid=".$id) ?>
<?php elseif ($gesendet == 1) : ?>
	<table>
		<tr><td valign="top"><img src="./images/check.svg" alt="OK, " width=20px height=20px></td>
		<td><?php e($label['View_gesendet']) ?></td></tr>
	</table>
<?php else : ?>
	<table>
		<tr><td valign="top"><img src="./images/emoji-sad.svg" alt="OK, " width=20px height=20px></td>
		<td><?php e($label['View_nichtGesendet']) ?></td></tr>
	</table>
<?php endif ?>
