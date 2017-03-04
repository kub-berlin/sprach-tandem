<?php include 'templates/partials/detail.php' ?>

<p>
	<a href="index.php?action=table&lang=<?php e($label['lang']) ?>" class="button">
		<?php icon(l10nDirection('prev', $label)) ?>
		<?php e($label['zurueck']) ?>
	</a>
</p>

<hr>

<h3>
	<?php icon('megaphone') ?>
	<?php e($label['View_AnzeigeMelden']) ?>
</h3>

<?php if (!$senden) : ?>
	<?php reportForm($label, "index.php?action=report&lang=".$label['lang']."&tid=".$id) ?>
<?php elseif ($gesendet) : ?>
	<?php alert($label, true, $label['Report_gesendet'], 'index.php?action=table&lang='.$label["lang"]) ?>
<?php else : ?>
	<?php alert($label, false, $label['Report_nichtGesendet'], 'index.php?action=table&lang='.$label["lang"]) ?>
<?php endif ?>
