<p><?php e($label['Table_intro']) ?></p>
<div class=div_table>
	<?php filterLanguageForm($label, "index.php?action=table&lang=".$label['lang']) ?>

	<table class=tandem_table>
		<tr>
			<th><?php e($label['Table_col_name']) ?></th>
			<th><?php e($label['Table_col_spracheAng']) ?></th>
			<th><?php e($label['Table_col_spracheGes']) ?></th>
			<th><?php e($label['Table_col_datum']) ?></th>
			<th><?php e($label['Table_col_ort']) ?></th>
		</tr>

		<?php foreach ($db_erg as $zeile) : ?>
			<tr>
				<td><a href="index.php?action=view&tid=<?php e($zeile[$GLOBALS['db_colName_id']]) ?>&lang=<?php e($label['lang']) ?>">
					<?php e(html_entity_decode($zeile[$GLOBALS['db_colName_name']])) ?></a></td>
				<td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])]) ?></td>
				<td><?php e($label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])]) ?></td>
				<td><?php e(date($label["dateFormat"], strtotime($zeile[$GLOBALS['db_colName_datum']]))) ?></td>
				<td><?php e(html_entity_decode($zeile[$GLOBALS['db_colName_ort']])) ?></td>
			</tr>
		<?php endforeach ?>
	</table>

	<!-- pagination -->
	<p class=center>
		<?php if ($page > 1) : ?>
			<a href="index.php?action=table&lang=<?php e($label['lang']) ?>&page=0&filterAng=<?php e($filterAng) ?>&filterGes=<?php e($filterGes) ?>">
			<img src="./images/<?php e(l10nDirection('first', $label)) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="first"></a>
		<?php endif ?>

		<?php if ($page > 0) : ?>
			<a href="index.php?action=table&lang=<?php e($label['lang']) ?>&page=<?php e($page-1) ?>&filterAng=<?php e($filterAng) ?>&filterGes=<?php e($filterGes) ?>">
			<img src="./images/<?php e(l10nDirection('prev', $label)) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="previous"></a>
		<?php endif ?>

		<?php if ($anzahl_pages > 0) : ?>
			<?php e($page+1) ?>/<?php e($anzahl_pages) ?>
		<?php endif ?>

		<?php if ($page + 1 < $anzahl_pages) : ?>
			<a href="index.php?action=table&lang=<?php e($label['lang']) ?>&page=<?php e($page+1) ?>&filterAng=<?php e($filterAng) ?>&filterGes=<?php e($filterGes) ?>">
			<img src="./images/<?php e(l10nDirection('next', $label)) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="next"></a>
		<?php endif ?>

		<?php if ($page + 2 < $anzahl_pages) : ?>
			<a href="index.php?action=table&lang=<?php e($label['lang']) ?>&page=<?php e($anzahl_pages-1) ?>&filterAng=<?php e($filterAng) ?>&filterGes=<?php e($filterGes) ?>">
			<img src="./images/<?php e(l10nDirection('last', $label)) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="last"></a>
		<?php endif ?>
	</p>
</div>
