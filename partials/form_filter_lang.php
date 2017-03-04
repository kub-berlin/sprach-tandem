<form action="<?php e($caller) ?>" method="GET">
	<table class=filter_table>
		<tr>
			<th><?php e($label['Table_filtern']) ?>:</th>
			<th>
				<select name="filterAng">
					<option><?php e($label['Table_filter_alle']) ?></option>
					<?php foreach ($langs_ang as $key) : ?>
						<option value="<?php e($key) ?>" <?php if ($filterAng == $key) : ?>selected<?php endif?>><?php e($label[$key]) ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<select name="filterGes">
					<option><?php e($label['Table_filter_alle']) ?></option>
					<?php foreach ($langs_ges as $key) : ?>
						<option value="<?php e($key) ?>" <?php if ($filterGes == $key) : ?>selected<?php endif?>><?php e($label[$key]) ?></option>
					<?php endforeach ?>
				</select>
			</th>
			<th>
				<button type="submit">
					<?php icon('funnel') ?>
					<?php e($label['Table_filtern']) ?>
				</button>
			</th>
		</tr>
	</table>

	<input type="hidden" name="action" value="table" />
	<input type="hidden" name="page" value="0" />
	<input type="hidden" name="lang" value="<?php e($label['lang']) ?>" />
</form>
