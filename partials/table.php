<div dir="<?php echo ($label["lang"] == 'fa' or $label["lang"] == 'ar') ? 'rtl' : 'ltr' ?>">
	<p><?php echo $label['Table_intro'] ?></p>
	<div class=div_table>
		<?php filterLanguageForm($label, "index.php?action=table&lang=".$label['lang']) ?>

		<table class=tandem_table>
			<colgroup id="col1"><col><col><col></colgroup>
			<colgroup id="col2"><col></colgroup>
			<colgroup id="col3"><col></colgroup>
			<tr>
				<th><?php echo $label['Table_col_name'] ?></th>
				<th><?php echo $label['Table_col_spracheAng'] ?></th>
				<th><?php echo $label['Table_col_spracheGes'] ?></th>
				<th><?php echo $label['Table_col_datum'] ?></th>
				<th><?php echo $label['Table_col_ort'] ?></th>
			</tr>

			<?php foreach ($db_erg as $zeile) : ?>
				<tr>
					<td><a href="index.php?action=view&tid=<?php echo $zeile[$GLOBALS['db_colName_id']] ?>&lang=<?php echo $label['lang'] ?>">
						<?php echo html_entity_decode($zeile[$GLOBALS['db_colName_name']]) ?></a></td>
					<td><?php echo $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheAng']])] ?></td>
					<td><?php echo $label[html_entity_decode($zeile[$GLOBALS['db_colName_spracheGes']])] ?></td>
					<td><?php echo date($label["dateFormat"], strtotime($zeile[$GLOBALS['db_colName_datum']])) ?></td>
					<td><?php echo html_entity_decode($zeile[$GLOBALS['db_colName_ort']]) ?></td>
				</tr>
			<?php endforeach ?>
		</table>

		<!-- pagination -->
		<p class=center>
			<?php if ($page > 1) : ?>
				<a href="index.php?action=table&lang=<?php echo $label['lang'] ?>&page=0&filterAng=<?php echo $filterAng ?>&filterGes=<?php echo $filterGes ?>">
				<img src="./images/<?php echo l10nDirection('first', $label) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="first"></a>
			<?php endif ?>

			<?php if ($page > 0) : ?>
				<a href="index.php?action=table&lang=<?php echo $label['lang'] ?>&page=<?php echo ($page-1) ?>&filterAng=<?php echo $filterAng ?>&filterGes=<?php echo $filterGes ?>">
				<img src="./images/<?php echo l10nDirection('prev', $label) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="previous"></a>
			<?php endif ?>

			<?php if ($anzahl_pages > 0) : ?>
				<?php echo ($page+1) ?>/<?php echo $anzahl_pages ?>
			<?php endif ?>

			<?php if ($page + 1 < $anzahl_pages) : ?>
				<a href="index.php?action=table&lang=<?php echo $label['lang'] ?>&page=<?php echo ($page+1) ?>&filterAng=<?php echo $filterAng ?>&filterGes=<?php echo $filterGes ?>">
				<img src="./images/<?php echo l10nDirection('next', $label) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="next"></a>
			<?php endif ?>

			<?php if ($page + 2 < $anzahl_pages) : ?>
				<a href="index.php?action=table&lang=<?php echo $label['lang'] ?>&page=<?php echo ($anzahl_pages-1) ?>&filterAng=<?php echo $filterAng ?>&filterGes=<?php echo $filterGes ?>">
				<img src="./images/<?php echo l10nDirection('last', $label) ?>.svg" style="width: 28px; margin-bottom: -7px;" alt="last"></a>
			<?php endif ?>
		</p>
	</div>
</div>
