<h3>
	<?php icon('trash') ?>
	<?php e(sprintf($label["deleteDataset_Title"], $GLOBALS['organisationName'])) ?>
</h3>

<form method="POST">
	<p>
		<a href="index.php?action=table&lang=<?php e($label['lang']) ?>" class="button">
			<?php icon(l10nDirection('prev', $label)) ?>
			<?php e($label['deleteDataset_button_no']) ?>
		</a>
		<button type="submit" name="delete">
			<?php icon('trash') ?>
			<?php e($label['deleteDataset_button_yes']) ?>
		</button>
	</p>
</form>