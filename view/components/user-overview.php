<div class="d-flex">
<?php foreach ($users as $user) : ?>
    <div class="p-3 user-card">
        <img src="<?=$di->get("user")->getUserImg($user->mail) ?>">
        <h4><?= $user->username ?></h4>
        <p><?= $user->name ?></p>
        <a href="<?= $di->get("url")->create("users/profile/$user->username") ?>" class="btn btn-primary">Go to profile</a>
    </div>
<?php endforeach; ?>
</div>
