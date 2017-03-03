<div dir="<?php echo ($label["lang"] == 'fa' or $label["lang"] == 'ar') ? 'rtl' : 'ltr' ?>">
	<form action="<?php echo htmlentities($caller) ?>" method="POST">
		<p class=form_above><?php echo $label['Add_ausfuellen'] ?></p>
		<?php // Formulareintragungen liegen (noch) nicht vor ?>
		<table class=form_table>
			<colgroup id="form_col1"><col></colgroup>
			<colgroup id="form_col2"><col></colgroup>
			<tr><td><?php echo $label['Add_name'] ?>:</td> <td><input type="text" name="name" value="<?php echo $_POST["name"] ?>" /></td></tr>
			<tr><td><?php echo $label['Add_alter'] ?>:</td> <td><input type="text" name="alter" value="<?php echo $_POST['alter'] ?>" /></td></tr>
			<tr><td><?php echo $label['Add_email'] ?>:</td> <td><input type="text" name="email" value="<?php echo $_POST['email'] ?>" /></td></tr>
			<tr><td><?php echo $label['Add_email_nochmal'] ?>:</td> <td><input type="text" name="email_nochmal" value="<?php echo $_POST['email_nochmal'] ?>" /></td></tr>
			<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" value="" /></td></tr>
			<tr><td><?php echo $label['Add_ort'] ?>:</td> <td><input type="text" name="ort" value="<?php echo $_POST['ort'] ?>" /></td></tr>
			<tr><td><?php echo $label['Add_geschlecht'] ?>:</td> <td><input type="text" name="geschlecht" value="<?php echo $_POST['geschlecht'] ?>" /></td></tr>
			<tr><td><?php echo $label['Add_spracheAng'] ?>:</td> <td>
				<select name="spracheAng">
				<?php foreach ($label as $key => $value) : ?>
					<?php if (strpos($key, 'sprache_') === 0 ) : ?>
						<?php if ($key == ($_POST['spracheAng'])): ?>
							<option value="<?php echo $key ?>" selected><?php echo $label[$key] ?></option>
						<?php else: ?>
							<option value="<?php echo $key ?>"><?php echo $label[$key] ?></option>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach ?>
				</select>
			</td></tr>
			<tr><td><?php echo $label['Add_spracheGes'] ?>:</td> <td>
				<select name="spracheGes">
				<?php foreach ($label as $key => $value) : ?>
					<?php if (strpos($key, 'sprache_') === 0 ) : ?>
						<?php if ($key == ($_POST['spracheGes'])): ?>
							<option value="<?php echo $key ?>" selected><?php echo $label[$key] ?></option>
						<?php else: ?>
							<option value="<?php echo $key ?>"><?php echo $label[$key] ?></option>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach ?>
				</select>
			</td></tr>
			<tr><td valign="top"><?php echo $label['Add_skills'] ?>:</td> <td>
				<input type="radio" name="skills" value="0" <?php if ($_POST['skills'] == "0" OR $_POST['skills'] == "") : ?>checked<?php endif ?>><?php echo $label['Add_skills_0'] ?></br>
				<input type="radio" name="skills" value="1" <?php if ($_POST['skills'] == "1") : ?>checked<?php endif ?>><?php echo $label['Add_skills_1'] ?></br>
				<input type="radio" name="skills" value="2" <?php if ($_POST['skills'] == "2") : ?>checked<?php endif ?>><?php echo $label['Add_skills_2'] ?></br>
				<input type="radio" name="skills" value="3" <?php if ($_POST['skills'] == "3") : ?>checked<?php endif ?>><?php echo $label['Add_skills_3'] ?>
			</td></tr>

			<tr><td valign="top"><?php echo $label['Add_beschreibung'] ?>:</td> <td>
				<textarea name="text" cols="50" rows="10" style="width: 100%"><?php echo $_POST['text'] ?></textarea>
			</td></tr>
		</table>
		<table class=form_table>
			<tr><td valign="top"><input value="ja" name="datenschutz[]" type="checkbox"></td><td><?php echo sprintf($label['Add_datenschutz'], $GLOBALS["organisationName"]) ?></td></tr>
		</table>
		<p>
			<button type="submit" name="send" value="cancel" class="button_image">
				<div id="<?php echo ($label['lang'] == "fa" or $label['lang'] == "ar") ? 'image_button_back_rtl' : 'image_button_back' ?>"><?php echo $label['zurueck'] ?></div>
			</button>
			<button type="submit" name="send" value="send" class="button_image">
				<div id="image_button_send"><?php echo $label['Add_senden'] ?></div>
			</button>
		</p>
	</form>
</div>
