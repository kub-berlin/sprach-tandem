<table>
	<tr>
		<td valign="top"><?php icon('emoji_sad') ?></td>
		<td><?php e($label['Add_nichtGesendet']) ?></td>
	</tr>
</table>
<form action="index.php?action=table&lang=<?php e($label["lang"]) ?>" method="POST">
	<p>
		<button type="submit">
			<?php icon(l10nDirection('prev', $label)) ?>
			<?php e($label['zurueck']) ?>
		</button>
	</p>
</form>
