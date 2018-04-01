<form method="POST" >
    <p class=form_above><?php e($label['View_Form_ausfuellen']) ?></p>

    <table class="form_table" role="presentation">
        <tr>
            <th><label for="message_name"><?php e($label['View_Form_name']) ?></label></th>
            <td><input type="text" name="name" id="message_name" required value="<?php e($_POST['name']) ?>"/></td>
        </tr>
        <tr>
            <th><label for="message_geschlecht"><?php e($label['View_Form_geschlecht']) ?></label></th>
            <td><input type="text" name="geschlecht" id="message_geschlecht" required value="<?php e($_POST['geschlecht']) ?>"/></td>
        </tr>
        <tr>
            <th><label for="message_alter"><?php e($label['View_Form_alter']) ?></label></th>
            <td><input type="text" name="alter" id="message_alter" required value="<?php e($_POST['alter']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="message_ort"><?php e($label['View_Form_ort']) ?></label></th>
            <td><input type="text" name="ort" id="message_ort" required value="<?php e($_POST['ort']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="message_email"><?php e($label['View_Form_email']) ?></label></th>
            <td><input type="email" name="email" id="message_email" required value="<?php e($_POST['email']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="message_email_nochmal"><?php e($label['View_Form_email_nochmal']) ?></label></th>
            <td><input type="email" name="email_nochmal" id="message_email_nochmal" required value="<?php e($_POST['email_nochmal']) ?>" /></td>
        </tr>
        <tr class=areYouHuman>
            <th><label for="message_areYouHuman">NICHT ausfüllen/do NOT fill in</label></th>
            <td><input type="text" name="areYouHuman" id="message_areYouHuman" value="<?php e($_POST['areYouHuman']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="message_text"><?php e($label['View_Form_text']) ?></label></th>
            <td><textarea name="text" id="message_text" required cols="50" rows="10" style="width: 100%" ><?php e($_POST['text']) ?></textarea></td>
        </tr>
    </table>

    <table class="form_table" role="presentation">
        <tr>
            <td><input type="checkbox" name="datenschutz[]" id="datenschutz" required value="ja" /></td>
            <td><label for="datenschutz"><?php e(sprintf($label['View_datenschutz'], $GLOBALS['organisationName'])) ?></label></td>
        </tr>
    </table>

    <p>
        <button type="submit">
            <?php icon('send') ?>
            <?php e($label['View_Form_senden']) ?>
        </button>
    </p>
</form>
