<div class="reply-form-wrap">
<h2>Hel a fellow out, post a reply!</h2>
<?php if (isset($form)) : ?>
    <div class="form">
        <?= $form ?>
    </div>
<?php else : ?>
    <div class="form">
        You have to log in to post replys
    </div>
<?php endif; ?>
</div>
