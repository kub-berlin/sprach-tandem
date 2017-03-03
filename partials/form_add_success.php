<table>
	<tr>
		<td valign="top"><img src="./images/check.svg" alt="OK, " width="20px" height="20px"></td>
		<td><?php e($label['Add_gesendet']) ?></td>
	</tr>
</table>
<form action="index.php?action=table&lang=<?php e($label["lang"]) ?>" method="POST">
	<p>
		<button type="submit" class="button_image">
			<div id="<?php e(($label['lang'] == "fa" or $label['lang'] == "ar") ? 'image_button_back_rtl' : 'image_button_back') ?>"><?php e($label["zurueck"]) ?></div>
		</button>
	</p>
</form>
