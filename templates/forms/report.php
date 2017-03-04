<form action="<?php e($caller) ?>" method="POST">
	<p class=form_above><?php e(sprintf($label['Report_textabove'], $GLOBALS["organisationName"])) ?></p>

	<table class="form_table" role="presentation">
		<tr>
			<td><label for="report_name"><?php e($label['Report_Form_name']) ?></label></td>
			<td><input type="text" name="name" id="report_name" value="<?php e($_POST['name']) ?>"/></td>
		</tr>
		<tr>
			<td><label for="report_email"><?php e($label['Report_Form_email']) ?></label></td>
			<td><input type="email" name="email" id="report_email" value="<?php e($_POST['email']) ?>" /></td>
		</tr>
		<tr>
			<td><label for="report_email_nochmal"><?php e($label['Report_Form_email_nochmal']) ?></label></td>
			<td><input type="email" name="email_nochmal" id="report_email_nochmal" value="<?php e($_POST['email_nochmal']) ?>" /></td>
		</tr>
		<tr class=areYouHuman>
			<td><label for="report_areYouHuman">NICHT ausfüllen/do NOT fill in</label></td>
			<td><input type="text" name="areYouHuman" id="report_areYouHuman" value="" /></td>
		</tr>
		<tr>
			<td><label for="report_text"><?php e($label['Report_Form_text']) ?></label></td>
			<td><textarea name="text" id="report_text" required cols="50" rows="10" style="width: 100%"><?php e($_POST['text']) ?></textarea></td>
		</tr>
	<table>

	<p>
		<button type="submit" name="send" value="send">
			<?php icon('send') ?>
			<?php e($label['Report_Form_senden']) ?>
		</button>
	</p>
</form>
