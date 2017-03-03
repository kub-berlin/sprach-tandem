<form action="<?php echo htmlentities($caller) ?>&page=0" method="POST">
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
						<option value="<?php echo $key ?>"><?php echo $label[$key] ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<select name="filterGes">
					<option><?php echo $label['Table_filter_alle'] ?></option>
					<?php foreach ($langs_ges as $key) : ?>
						<option value="<?php echo $key ?>"><?php echo $label[$key] ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<p><button type="submit" class="button_image"><div id="image_button_filter"><?php echo $label['Table_filtern'] ?></div></button></p>
			</th>
		</tr>
	</table>
</form>
