<form method="GET">
	<div class="filter-bar">
		<?php e($label['Table_filtern']) ?>

		<label title="<?php e($label['Table_col_spracheAng']) ?>">
			<select name="filterAng">
				<option><?php e($label['Table_filter_alle']) ?></option>
				<?php foreach ($langs_ang as $key) : ?>
					<option value="<?php e($key) ?>" <?php if ($filterAng == $key) : ?>selected<?php endif?>><?php e($label[$key]) ?></option>
				<?php endforeach ?>
			</select>
		</label>

		<label title="<?php e($label['Table_col_spracheGes']) ?>">
			<select name="filterGes">
				<option><?php e($label['Table_filter_alle']) ?></option>
				<?php foreach ($langs_ges as $key) : ?>
					<option value="<?php e($key) ?>" <?php if ($filterGes == $key) : ?>selected<?php endif?>><?php e($label[$key]) ?></option>
				<?php endforeach ?>
			</select>
		</label>

		<button type="submit">
			<?php icon('funnel') ?>
			<?php e($label['Table_filtern']) ?>
		</button>
	</div>

	<input type="hidden" name="action" value="table" />
	<input type="hidden" name="page" value="0" />
	<input type="hidden" name="lang" value="<?php e($label['lang']) ?>" />
</form>
