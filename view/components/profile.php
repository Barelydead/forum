<?php
$url = $this->di->get("url");
?>
<div class="container">
    <div class="row mt-3">
        <div class="col d-flex flex-column align-items-center p-2">
            <img src="<?= $di->get("user")->getUserImg($user->mail) ?>">
            <h1><?= $user->username ?></h1>
            <p><?= $user->name ?><br></p>
            <p><?= $user->mail ?><br></p>
        </div>

        <div class="col-md-6">
            <div class="p-2">
                <h4>Description</h4>
                <?php if (isset($user->description)) : ?>
                    <p><?= $user->description ?></p>
                <?php else : ?>
                    <p>No description set yet</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col">
            <h4>Statistics</h4>
            <p>joined <?= ($user->created) ?></p>
            <p>last update <?= ($user->updated) ?></p>
            <p><i class="fa fa-balance-scale" aria-hidden="true"></i>
                <?= $user->karma ?> karma
            </p>
            <p><i class="fa fa-comments" aria-hidden="true"></i>
                <?= count($posts) ?> post
            </p>
            <p><i class="fa fa-thumbs-up" aria-hidden="true"></i>
                <?php if (isset($votes[0])) : ?>
                    <?= $votes[0]->count ?> upvotes
                <?php endif;?>
            </p>
            <p><i class="fa fa-thumbs-down" aria-hidden="true"></i>
                <?php if (isset($votes[1])) : ?>
                    <?= $votes[1]->count ?> downvotes
                <?php endif;?>
            </p>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="p-2">
                <h2>Questions</h2>
                <?php foreach ($posts as $p) :?>
                    <?php if ($p->question == 1) : ?>
                        <h4><?=$p->title?></h4>
                        <p><?= $p->content ?></p>
                        <a href="<?= $di->get("url")->create("forum/post/$p->id") ?>" class="">View post</a>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="p-2">
                <h2>Answers</h2>
                <?php foreach ($posts as $p) :?>
                    <?php if ($p->reply == 1) :?>
                        <p><?= $p->content ?></p>
                        <a href="<?= $di->get("url")->create("forum/post/$p->questionId") ?>" class="">View post</a>
                        <a href="<?= $di->get("url")->create("forum/post/delete/$p->questionId") ?>" class="">delete post</a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="p-2">
                <h2>Comments</h2>
                <?php foreach ($posts as $p) :?>
                    <?php if ($p->comment == 1) :?>
                        <p><?= $p->content ?></p>
                        <a href="<?= $di->get("url")->create("forum/post/$p->questionId") ?>" class="">View post</a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

</div>
