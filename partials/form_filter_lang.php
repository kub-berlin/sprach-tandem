<form action="<?php echo htmlentities($caller) ?>" method="GET">
	<table class=filter_table>
		<colgroup id="col1"><col><col><col></colgroup>
		<colgroup id="col2"><col></colgroup>
		<colgroup id="col3"><col></colgroup>
		<tr>
			<th><?php echo $label['Table_filtern'] ?>:</th>
			<th>
				<select name="filterAng">
					<option><?php echo $label['Table_filter_alle'] ?></option>
					<?php foreach ($langs_ang as $key) : ?>
						<option value="<?php echo $key ?>" <?php if ($filterAng == $key) : ?>selected<?php endif?>><?php echo $label[$key] ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<select name="filterGes">
					<option><?php echo $label['Table_filter_alle'] ?></option>
					<?php foreach ($langs_ges as $key) : ?>
						<option value="<?php echo $key ?>" <?php if ($filterGes == $key) : ?>selected<?php endif?>><?php echo $label[$key] ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<p><button type="submit" class="button_image"><div id="image_button_filter"><?php echo $label['Table_filtern'] ?></div></button></p>
			</th>
		</tr>
	</table>

	<input type="hidden" name="action" value="table" />
	<input type="hidden" name="page" value="0" />
	<input type="hidden" name="lang" value="<?php echo htmlentities($label['lang']) ?>" />
</form>
