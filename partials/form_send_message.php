<form action="<?php e($caller) ?>" method="POST" >
	<p class=form_above><?php e($label['View_Form_ausfuellen']) ?></p>
	<table  class=form_table>
		<tr><td><?php e($label['View_Form_name']) ?>:</td> <td><input type="text" name="name" value="<?php e($_POST['name']) ?>"/></td></tr>
		<tr><td><?php e($label['View_Form_geschlecht']) ?>:</td> <td><input type="text" name="geschlecht" value="<?php e($_POST['geschlecht']) ?>"/></td></tr>
		<tr><td><?php e($label['View_Form_alter']) ?>:</td> <td><input type="text" name="alter" value="<?php e($_POST['alter']) ?>" /></td></tr>
		<tr><td><?php e($label['View_Form_ort']) ?>:</td> <td><input type="text" name="ort" value="<?php e($_POST['ort']) ?>" /></td></tr>
		<tr><td><?php e($label['View_Form_email']) ?>:</td> <td><input type="text" name="email" value="<?php e($_POST['email']) ?>" /></td></tr>
		<tr><td><?php e($label['View_Form_email_nochmal']) ?>:</td> <td><input type="text" name="email_nochmal" value="<?php e($_POST['email_nochmal']) ?>" /></td></tr>
		<tr class=areYouHuman><td>NICHT ausfüllen/do NOT fill in:</td> <td><input type="text" name="areYouHuman" Value="<?php e($_POST['areYouHuman']) ?>" /></td></tr>
		<tr><td><?php e($label['View_Form_text']) ?>:</td> <td> <textarea name="text" cols="50" rows="10" style="width: 100%" ><?php e($_POST['text']) ?></textarea> </td></tr>
	</table>
	<table>
		<tr><td><input value="ja" name="datenschutz[]" type="checkbox"></td><td><?php e(sprintf($label['View_datenschutz'], $GLOBALS["organisationName"])) ?></td></tr>
	</table>
	<p>
		<button type="submit">
			<?php icon('send') ?>
			<?php e($label['View_Form_senden']) ?>
		</button>
	</p>
</form>
