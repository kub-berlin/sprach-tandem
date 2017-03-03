<table>
	<tr>
		<td valign="top"><img src="./images/emoji-sad.svg" alt="Bild"></td>
		<td><?php e($label['Add_nichtGesendet']) ?></td>
	</tr>
</table>
<form action="index.php?action=table&lang=<?php e($label["lang"]) ?>" method="POST">
	<p>
		<button type="submit" class="button_image">
			<div id="<?php e(($label['lang'] == 'fa' or $label['lang'] == 'ar') ? 'image_button_back_rtl' : 'image_button_back') ?>"><? e($label['zurueck']) ?></div>
		</button>
	</p>
</form>
