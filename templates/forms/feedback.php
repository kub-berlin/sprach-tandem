<?php function renderYesNo($name) { ?>
    <label>
        <input type="radio" name="<?php e($name) ?>" value="ja" <?php $_POST[$name] === 'ja' ? 'checked' : ''?>>
        <?php e($label['deleteDataset_button_yes']) ?>
    </label>
    <label>
        <input type="radio" name="<?php e($name) ?>" value="nein" <?php $_POST[$name] === 'nein' ? 'checked' : ''?>>
        <?php e($label['deleteDataset_button_no']) ?>
    </label>
<?php } ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php e($GLOBALS['csrf_token']) ?>" />

    <p><?php e($label['Feedback_intro']) ?></p>

    <p>
        <?php e($label['Feedback_frage1']) ?></br>
        <?php renderYesNo('frage1') ?>
    </p>
    <p>
        <?php e($label['Feedback_frage2']) ?></br>
        <?php renderYesNo('frage2') ?>
    </p>
    <p>
        <?php e($label['Feedback_frage3']) ?></br>
        <?php renderYesNo('frage3') ?>
    </p>
    <p>
        <?php e($label['Feedback_frage4']) ?></br>
        <?php renderYesNo('frage4') ?>
    </p>
    <p>
        <?php e($label['Feedback_frage5']) ?></br>
        <?php renderYesNo('frage5') ?>
    </p>
    <p>
        <?php e($label['Feedback_frage6']) ?></br>
        <?php renderYesNo('frage6') ?>
    </p>
    <p>
        <label>
            <?php e($label['Feedback_frage7']) ?></br>
            <textarea name="frage7" required cols="50" rows="10"><?php e($_POST['frage7']) ?></textarea>
        </label>
    </p>
    <p>
        <label>
            <?php e($label['Feedback_frage8']) ?></br>
            <textarea name="frage8" required cols="50" rows="10"><?php e($_POST['frage8']) ?></textarea>
        </label>
    </p>

    <p>
        <button name="send" value="send">
            <?php icon('send') ?>
            <?php e($label['Feedback_senden']) ?>
        </button>
    </p>
</form>
