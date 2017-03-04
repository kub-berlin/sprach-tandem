<table>
	<tr>
		<td><?php icon($success ? 'check' : 'emoji_sad') ?></td>
		<td><?php e($msg) ?></td>
	</tr>
</table>
<p>
	<a href="<?php e($backLink) ?>" class="button">
		<?php icon(l10nDirection('prev', $label)) ?>
		<?php e($label['zurueck']) ?>
	</a>
</p>
