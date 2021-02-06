<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php e($GLOBALS['csrf_token']) ?>" />

    <p><?php e($label['Feedback_intro']) ?></p>

        <p>
            <label for="feedback_frage1"><?php e($label['Feedback_frage1']) ?></label></br>
			<input type="radio" id="Ja" name="frage1" value="ja" <?php e($_POST['frage1']) == 'ja' ? 'checked' : ''?>>
			<label for="ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="nein" name="frage1" value="nein" <?php e($_POST['frage1']) != 'ja' ? 'checked' : ''?>>
			<label for="nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
        <p>
            <label for="feedback_frage2"><?php e($label['Feedback_frage2']) ?></label></br>
			<input type="radio" id="Ja" name="frage2" value="ja" <?php e($_POST['frage2']) == 'ja' ? 'checked' : ''?>>
			<label for="ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="nein" name="frage2" value="nein" <?php e($_POST['frage2']) != 'ja' ? 'checked' : ''?>>
			<label for="nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <label for="feedback_frage3"><?php e($label['Feedback_frage3']) ?></label></br>
			<input type="radio" id="Ja" name="frage3" value="ja" <?php e($_POST['frage3']) == 'ja' ? 'checked' : ''?>>
			<label for="ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="nein" name="frage3" value="nein" <?php e($_POST['frage3']) != 'ja' ? 'checked' : ''?>>
			<label for="nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <label for="feedback_frage4"><?php e($label['Feedback_frage4']) ?></label></br>
			<input type="radio" id="Ja" name="frage4" value="ja" <?php e($_POST['frage4']) == 'ja' ? 'checked' : ''?>>
			<label for="ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="nein" name="frage4" value="nein" <?php e($_POST['frage4']) != 'ja' ? 'checked' : ''?>>
			<label for="nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <label for="feedback_frage5"><?php e($label['Feedback_frage5']) ?></label></br>
			<input type="radio" id="Ja" name="frage5" value="ja" <?php e($_POST['frage5']) == 'ja' ? 'checked' : ''?>>
			<label for="ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="nein" name="frage5" value="nein" <?php e($_POST['frage5']) != 'ja' ? 'checked' : ''?>>
			<label for="nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <label for="feedback_frage6"><?php e($label['Feedback_frage6']) ?></label></br>
            <textarea name="frage6" id="frage6" required cols="50" rows="10" style="width: 100%"><?php e($_POST['frage6']) ?></textarea>
        </p>
		<p>
            <label for="feedback_frage7"><?php e($label['Feedback_frage7']) ?></label></br>
            <textarea name="frage7" id="frage7" required cols="50" rows="10" style="width: 100%"><?php e($_POST['frage7']) ?></textarea>
        </p>


    <p>
        <button type="submit" name="send" value="send">
            <?php icon('send') ?>
            <?php e($label['Feedback_senden']) ?>
        </button>
    </p>
</form>
