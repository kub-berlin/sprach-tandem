<form method="POST">
    <p class=form_above><?php e($label['Add_ausfuellen']) ?></p>

    <table class="form_table" role="presentation">
        <tr>
            <th><label for="add_name"><?php e($label['Add_name']) ?></label></th>
            <td><input type="text" name="name" id="add_name" required value="<?php e($_POST["name"]) ?>" /></td>
        </tr>
        <tr>
            <th><label for="add_alter"><?php e($label['Add_alter']) ?></label></th>
            <td><input type="text" name="alter" id="add_alter" required value="<?php e($_POST['alter']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="add_email"><?php e($label['Add_email']) ?></label></th>
            <td><input type="email" name="email" id="add_email" required value="<?php e($_POST['email']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="add_email_nochmal"><?php e($label['Add_email_nochmal']) ?></label></th>
            <td><input type="email" name="email_nochmal" id="add_email_nochmal" required value="<?php e($_POST['email_nochmal']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="add_ort"><?php e($label['Add_ort']) ?></label></th>
            <td><input type="text" name="ort" id="add_ort" required value="<?php e($_POST['ort']) ?>" /></td>
        </tr>
        <tr>
            <th><label for="add_geschlecht"><?php e($label['Add_geschlecht']) ?></label></th>
            <td><input type="text" name="geschlecht" id="add_geschlecht" required value="<?php e($_POST['geschlecht']) ?>" /></td>
        </tr>

        <tr>
            <th><label for="add_spracheAng"><?php e($label['Add_spracheAng']) ?></label></th>
            <td>
                <select name="spracheAng" id="add_spracheAng">
                <?php foreach ($label as $key => $value) : ?>
                    <?php if (strpos($key, 'sprache_') === 0 ) : ?>
                        <option value="<?php e($key) ?>" <?php if ($key == $_POST['spracheAng']): ?>selected<?php endif?>><?php e($label[$key]) ?></option>
                    <?php endif ?>
                <?php endforeach ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><label for="add_spracheGes"><?php e($label['Add_spracheGes']) ?></label></th>
            <td>
                <select name="spracheGes" id="add_spracheGes">
                <?php foreach ($label as $key => $value) : ?>
                    <?php if (strpos($key, 'sprache_') === 0 ) : ?>
                        <option value="<?php e($key) ?>" <?php if ($key == $_POST['spracheGes']): ?>selected<?php endif?>><?php e($label[$key]) ?></option>
                    <?php endif ?>
                <?php endforeach ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?php e($label['Add_skills']) ?></th>
            <td>
                <?php for ($i = 0; $i < 4; $i++) : ?>
                <label>
                    <input type="radio" name="skills" required value="<?php e($i) ?>" <?php if ($_POST['skills'] == $i) : ?>checked<?php endif ?>>
                    <?php e($label['Add_skills_' . $i]) ?>
                </label>
                <?php endfor ?>
            </td>
        </tr>

        <tr>
            <th><label for="add_text"><?php e($label['Add_beschreibung']) ?></label></th>
            <td><textarea name="text" id="add_text" required cols="50" rows="10" style="width: 100%"><?php e($_POST['text']) ?></textarea></td>
        </tr>

        <tr class=areYouHuman>
            <th><label for="add_areYouHuman">NICHT ausfüllen/do NOT fill in</label></th>
            <td><input type="text" name="areYouHuman" id="add_areYouHuman" value="" /></td>
        </tr>
    </table>

    <table class="form_table" role="presentation">
        <tr>
            <td><input type="checkbox" name="datenschutz[]" id="datenschutz" required value="ja" /></td>
            <td><label for="datenschutz"><?php e(sprintf($label['Add_datenschutz'], $GLOBALS['organisationName'])) ?></label></td>
        </tr>
    </table>

    <p>
        <a href="<?php e($cancelLink) ?>" class="button">
            <?php icon(l10nDirection('prev', $label)) ?>
            <?php e($label['zurueck']) ?>
        </a>
        <button type="submit" name="send" id="add_send" value="send">
            <?php icon('send') ?>
            <?php e($label['Add_senden']) ?>
        </button>
    </p>
</form>
