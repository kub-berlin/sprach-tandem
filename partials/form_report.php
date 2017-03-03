<form action="<?php e($caller) ?>" method="POST">
	<p class=form_above><?php e(sprintf($label['Report_textabove'], $GLOBALS["organisationName"])) ?></p>
	<table class=form_table>
		<tr><td><?php e($label['Report_Form_name']) ?>:</td> <td><input type="text" name="name" value="<?php e($_POST['name']) ?>"/></td></tr>
		<tr><td><?php e($label['Report_Form_email']) ?>:</td> <td><input type="text" name="email" value="<?php e($_POST['email']) ?>" /></td></tr>
		<tr><td><?php e($label['Report_Form_email_nochmal']) ?>:</td> <td><input type="text" name="email_nochmal" value="<?php e($_POST['email_nochmal']) ?>" /></td></tr>
		<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" value="" /></td></tr>
		<tr><td valign="top"><?php e($label['Report_Form_text']) ?>:</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%"><?php e($_POST['text']) ?></textarea> </td></tr>
		</table>
	<table>
	<p>
		<button type="submit" name="send" value="send" class="button_image">
			<div id="image_button_send"><?php e($label['Report_Form_senden']) ?></div>
		</button>
	</p>
</form>
