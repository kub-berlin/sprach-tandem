<table>
	<tr>
		<td valign="top"><?php icon('check') ?></td>
		<td><?php e($label['Add_gesendet']) ?></td>
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
