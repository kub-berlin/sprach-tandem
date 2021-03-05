<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php e($GLOBALS['csrf_token']) ?>" />

    <p><?php e($label['Feedback_intro']) ?></p>

        <p>
			<?php e($label['Feedback_frage1']) ?></br>
			<input type="radio" id="frage1Ja" name="frage1" value="ja" <?php e($_POST['frage1']) == 'ja' ? 'checked' : ''?>>
			<label for="frage1Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frage1Nein" name="frage1" value="nein" <?php e($_POST['frage1']) != 'ja' ? 'checked' : ''?>>
			<label for="frage1Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
        <p>
            <?php e($label['Feedback_frage2']) ?></br>
			<input type="radio" id="frage2Ja" name="frage2" value="ja" <?php e($_POST['frage2']) == 'ja' ? 'checked' : ''?>>
			<label for="frage2Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frage2Nein" name="frage2" value="nein" <?php e($_POST['frage2']) != 'ja' ? 'checked' : ''?>>
			<label for="frage2Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <?php e($label['Feedback_frage3']) ?></br>
			<input type="radio" id="frage3Ja" name="frage3" value="ja" <?php e($_POST['frage3']) == 'ja' ? 'checked' : ''?>>
			<label for="frage3Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frage3Nein" name="frage3" value="nein" <?php e($_POST['frage3']) != 'ja' ? 'checked' : ''?>>
			<label for="frage3Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <?php e($label['Feedback_frage4']) ?></br>
			<input type="radio" id="frage4Ja" name="frage4" value="ja" <?php e($_POST['frage4']) == 'ja' ? 'checked' : ''?>>
			<label for="frage4Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frageNein" name="frage4" value="nein" <?php e($_POST['frage4']) != 'ja' ? 'checked' : ''?>>
			<label for="frage4Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <?php e($label['Feedback_frage5']) ?></br>
			<input type="radio" id="frage5Ja" name="frage5" value="ja" <?php e($_POST['frage5']) == 'ja' ? 'checked' : ''?>>
			<label for="frage5Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frage5Nein" name="frage5" value="nein" <?php e($_POST['frage5']) != 'ja' ? 'checked' : ''?>>
			<label for="frage5Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <?php e($label['Feedback_frage6']) ?></br>
			<input type="radio" id="frage6Ja" name="frage5" value="ja" <?php e($_POST['frage6']) == 'ja' ? 'checked' : ''?>>
			<label for="frage6Ja"><?php e($label['deleteDataset_button_yes']) ?></label>
			<input type="radio" id="frage6Nein" name="frage6" value="nein" <?php e($_POST['frage6']) != 'ja' ? 'checked' : ''?>>
			<label for="frage6Nein"><?php e($label['deleteDataset_button_no']) ?></label>
        </p>
		<p>
            <?php e($label['Feedback_frage7']) ?></br>
            <textarea name="frage7" id="frage7" required cols="50" rows="10" style="width: 100%"><?php e($_POST['frage7']) ?></textarea>
        </p>
		<p>
            <?php e($label['Feedback_frage8']) ?></br>
            <textarea name="frage8" id="frage8" required cols="50" rows="10" style="width: 100%"><?php e($_POST['frage8']) ?></textarea>
        </p>


    <p>
        <button type="submit" name="send" value="send">
            <?php icon('send') ?>
            <?php e($label['Feedback_senden']) ?>
        </button>
    </p>
</form>
