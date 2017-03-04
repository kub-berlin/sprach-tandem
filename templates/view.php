<?php include 'templates/partials/detail.php' ?>

<p>
	<a href="index.php?action=table&lang=<?php e($label["lang"]) ?>" class="button">
		<?php icon(l10nDirection('prev', $label)) ?>
		<?php e($label['zurueck']) ?>
	</a>
	<a href=index.php?action=report&lang=<?php e($label['lang']) ?>&tid=<?php e($id) ?> class="button">
		<?php icon('megaphone') ?>
		<?php e($label['View_AnzeigeMelden']) ?>
	</a>
</p>

<hr>

<h3>
	<?php icon('chat') ?>
	<?php e($label['View_Form_nachrichtAn']) ?>
	<?php e($zeile[$GLOBALS['db_colName_name']]) ?>
</h3>

<?php if ($senden == false) : ?>
	<?php sendMessageForm($label, "index.php?action=view&lang=".$label['lang']."&tid=".$id) ?>
<?php elseif ($gesendet == 1) : ?>
	<?php alert($label, true, $label['View_gesendet'], 'index.php?action=table&lang='.$label['lang']) ?>
<?php else : ?>
	<?php alert($label, false, $label['View_nichtGesendet'], 'index.php?action=table&lang='.$label['lang']) ?>
<?php endif ?>
