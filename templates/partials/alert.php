<div class="alert alert--<?php e($success ? 'success' : 'warning') ?>">
    <?php icon($success ? 'check' : 'emoji_sad', 'alert__icon') ?>
    <div class="alert__body">
        <?php e($msg) ?>
    </div>
</div>
<p>
    <a href="<?php e($backLink) ?>" class="button">
        <?php icon(l10nDirection('prev', $label)) ?>
        <?php e($label['zurueck']) ?>
    </a>
</p>
