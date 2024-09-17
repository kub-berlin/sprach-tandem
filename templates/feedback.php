<h3><?php e($label['Feedback_title']) ?></h3>

<?php if (!$senden) : ?>
    <?php feedbackForm($label) ?>
<?php elseif ($gesendet) : ?>
    <?php alert($label, true, $label['Report_gesendet'], 'index.php?action=table&lang='.$label["lang"]) ?>
<?php else : ?>
    <?php alert($label, false, $label['Report_nichtGesendet'], 'index.php?action=table&lang='.$label["lang"]) ?>
<?php endif ?>
