<form action="<?php e($caller) ?>" method="POST">
	<p class=form_above><?php e($label['Add_ausfuellen']) ?></p>
	<?php // Formulareintragungen liegen (noch) nicht vor ?>
	<table class=form_table>
		<colgroup id="form_col1"><col></colgroup>
		<colgroup id="form_col2"><col></colgroup>
		<tr><td><?php e($label['Add_name']) ?>:</td> <td><input type="text" name="name" value="<?php e($_POST["name"]) ?>" /></td></tr>
		<tr><td><?php e($label['Add_alter']) ?>:</td> <td><input type="text" name="alter" value="<?php e($_POST['alter']) ?>" /></td></tr>
		<tr><td><?php e($label['Add_email']) ?>:</td> <td><input type="text" name="email" value="<?php e($_POST['email']) ?>" /></td></tr>
		<tr><td><?php e($label['Add_email_nochmal']) ?>:</td> <td><input type="text" name="email_nochmal" value="<?php e($_POST['email_nochmal']) ?>" /></td></tr>
		<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" value="" /></td></tr>
		<tr><td><?php e($label['Add_ort']) ?>:</td> <td><input type="text" name="ort" value="<?php e($_POST['ort']) ?>" /></td></tr>
		<tr><td><?php e($label['Add_geschlecht']) ?>:</td> <td><input type="text" name="geschlecht" value="<?php e($_POST['geschlecht']) ?>" /></td></tr>
		<tr><td><?php e($label['Add_spracheAng']) ?>:</td> <td>
			<select name="spracheAng">
			<?php foreach ($label as $key => $value) : ?>
				<?php if (strpos($key, 'sprache_') === 0 ) : ?>
					<?php if ($key == ($_POST['spracheAng'])): ?>
						<option value="<?php e($key) ?>" selected><?php e($label[$key]) ?></option>
					<?php else: ?>
						<option value="<?php e($key) ?>"><?php e($label[$key]) ?></option>
					<?php endif ?>
				<?php endif ?>
			<?php endforeach ?>
			</select>
		</td></tr>
		<tr><td><?php e($label['Add_spracheGes']) ?>:</td> <td>
			<select name="spracheGes">
			<?php foreach ($label as $key => $value) : ?>
				<?php if (strpos($key, 'sprache_') === 0 ) : ?>
					<?php if ($key == ($_POST['spracheGes'])): ?>
						<option value="<?php e($key) ?>" selected><?php e($label[$key]) ?></option>
					<?php else: ?>
						<option value="<?php e($key) ?>"><?php e($label[$key]) ?></option>
					<?php endif ?>
				<?php endif ?>
			<?php endforeach ?>
			</select>
		</td></tr>
		<tr><td valign="top"><?php e($label['Add_skills']) ?>:</td> <td>
			<input type="radio" name="skills" value="0" <?php if ($_POST['skills'] == "0" OR $_POST['skills'] == "") : ?>checked<?php endif ?>><?php e($label['Add_skills_0']) ?></br>
			<input type="radio" name="skills" value="1" <?php if ($_POST['skills'] == "1") : ?>checked<?php endif ?>><?php e($label['Add_skills_1']) ?></br>
			<input type="radio" name="skills" value="2" <?php if ($_POST['skills'] == "2") : ?>checked<?php endif ?>><?php e($label['Add_skills_2']) ?></br>
			<input type="radio" name="skills" value="3" <?php if ($_POST['skills'] == "3") : ?>checked<?php endif ?>><?php e($label['Add_skills_3']) ?>
		</td></tr>

		<tr><td valign="top"><?php e($label['Add_beschreibung']) ?>:</td> <td>
			<textarea name="text" cols="50" rows="10" style="width: 100%"><?php e($_POST['text']) ?></textarea>
		</td></tr>
	</table>
	<table class=form_table>
		<tr><td valign="top"><input value="ja" name="datenschutz[]" type="checkbox"></td><td><?php e(sprintf($label['Add_datenschutz'], $GLOBALS["organisationName"])) ?></td></tr>
	</table>
	<p>
		<button type="submit" name="send" value="cancel" class="button_image">
			<div id="<?php e(($label['lang'] == "fa" or $label['lang'] == "ar") ? 'image_button_back_rtl' : 'image_button_back') ?>"><?php e($label['zurueck']) ?></div>
		</button>
		<button type="submit" name="send" value="send" class="button_image">
			<div id="image_button_send"><?php e($label['Add_senden']) ?></div>
		</button>
	</p>
</form>
