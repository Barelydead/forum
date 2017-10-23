<div class="jumbotron">
    <div class="d-flex align-items-start">
        <div class="p-2 mt-2">
            <a href="<?= $di->get("url")->create("forum/post/$post->id/$loggedInUser/upvote")?>">
                <i class="fa fa-arrow-up fa-2x" aria-hidden="true"></i>
            </a><br>
            <a href="<?= $di->get("url")->create("forum/post/$post->id/$loggedInUser/downvote")?>">
                <i class="fa fa-arrow-down fa-2x" aria-hidden="true"></i>
            </a>
        </div>
        <div class="p-2">
            <h1><?= $post->title ?></h1>

            <div class="d-flex justify-content-between">
                <p><?= $post->likes ?> <i class="fa fa-thumbs-up" aria-hidden="true"></i></p>
                <span>
                    <?php foreach ($tags as $tag) :?>
                        <a href="<?= $di->get("url")->create("forum/tag/" . $tag->tag)?>" class="tag-link"><?=$tag->tag ?></a>
                    <?php endforeach; ?>
                    <?php if ($loggedInUser == $post->user) :?>
                        <a href="<?= $di->get("url")->create("forum/edit/tags/" . $post->id)?>" class="tag-link">
                            <i class="fa fa-pencil" aria-hidden="true" title="edit tags"></i>
                        </a>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="p-2 ml-auto">
            <?php if ($loggedInUser == $post->user) :?>
                <a href="<?= $di->get("url")->create("forum/post/delete/$post->id")?>" class="btn btn-danger">delete</a>
                <a href="<?= $di->get("url")->create("forum/edit/" . $post->id)?>" class="btn btn-warning">Edit</a>
            <?php endif; ?>
            <a href="#reply" class="btn btn-secondary">Svara</a>
        </div>

    </div>

    <div class="question-wrap row mt-2">
        <div class="post-user d-flex flex-column col-3">
            <img src="<?= $di->get("user")->getUserImg($userInfo->mail) ?>" class="avatar">
            <h1><?= $userInfo->username?></h1>
            <p class="post-date"><?=$post->created?></p>
        </div>

        <div class="col">
            <div class="comment-content p-2">
                <?= $di->get("textfilter")->markdown($post->content) ?>
            </div>
        </div>
    </div>
</div>

<ul class="nav justify-content-start">
    <li class="nav-item">
        <a class="nav-link disabled" href="#">Sort answer by: </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?sort=created&order=DESC">newest</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?sort=created&order=ASC">oldest</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?sort=likes&order=DESC">most likes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="?sort=likes&order=ASC">fewest likes</a>
    </li>
</ul>

<?php foreach ($replies as $reply) : ?>
<div class="super-wrap container">
    <div class="question-wrap row">
        <div class="col-1 p-2 align-items-center d-flex flex-column">
            <a href="<?= $di->get("url")->create("forum/post/$reply->postId/$loggedInUser/upvote")?>">
                <i class="fa fa-arrow-up fa-2x" aria-hidden="true"></i>
            </a><br>
            <p class"font-weight-bold"><?= $reply->likes ?></p>
            <a href="<?= $di->get("url")->create("forum/post/$reply->postId/$loggedInUser/downvote")?>">
                <i class="fa fa-arrow-down fa-2x" aria-hidden="true"></i>
            </a>
            <?php if ($reply->accepted) :?>
                <i class="fa fa-check-circle-o fa-3x" aria-hidden="true" title="Accepted answer"></i>

            <?php endif; ?>
        </div>

        <div class="post-user d-flex flex-column col-3">
            <img src="<?= $di->get("user")->getUserImg($reply->mail) ?>" class="avatar">
            <h1><?= $reply->username?></h1>
            <p class="post-date"><?=$reply->created?></p>
        </div>

        <div class="col">
            <div class="comment-content p-2">
                <?= $di->get("textfilter")->markdown($reply->content) ?>
            </div>
        </div>

        <div class="col-1">
            <?php if ($loggedInUser == $post->user) :?>
                <a href="<?= $di->get("url")->create("forum/post/mark/reply/$reply->postId") ?>">
                    <i class="fa fa-check-circle-o" aria-hidden="true" title="Accpeted answer toggle"></i>
                </a>
                <a href="<?= $di->get("url")->create("forum/edit/" . $reply->postId)?>">
                    <i class="fa fa-pencil" aria-hidden="true" title="edit"></i>
                </a>
                <a href="<?= $di->get("url")->create("forum/post/delete/$reply->postId")?>">
                    <i class="fa fa-trash" aria-hidden="true" title="delete"></i>
                </a>
            <?php endif; ?>
            <?php if (isset($loggedInUser)) : ?>
                <a href="<?= $di->get("url")->create("forum/reply/$post->id/comment/$reply->postId") ?>">
                    <i class="fa fa-comment" aria-hidden="true" title="comment on this"></i>
                </a>
            <?php endif; ?>

        </div>
    </div>

    <?php foreach ($comments as $comment) : ?>
        <?php if ($comment->replyId == $reply->postId) : ?>
            <div class="comment-wrap row align-items-end my-2">
                <div class="col-1 offset-1 align-items-center d-flex flex-column">
                    <a href="<?= $di->get("url")->create("forum/post/$comment->postId/$loggedInUser/upvote")?>">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                    </a><br>
                    <span><?= $comment->likes ?></span>
                    <a href="<?= $di->get("url")->create("forum/post/$comment->postId/$loggedInUser/downvote")?>">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="comment-content col">
                    <?= $di->get("textfilter")->markdown($comment->content) ?> <span class="text-muted"> - <?= $comment->username ?></span>
                </div>
                <div class="col-1">
                    <?php if ($loggedInUser == $comment->user) :?>
                        <a href="<?= $di->get("url")->create("forum/edit/" . $comment->postId)?>">
                            <i class="fa fa-pencil" aria-hidden="true" title="edit"></i>
                        </a>
                        <a href="<?= $di->get("url")->create("forum/post/delete/$comment->postId")?>">
                            <i class="fa fa-trash" aria-hidden="true" title="delete"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>
<?php endforeach; ?>


<a id="reply">
</a>
