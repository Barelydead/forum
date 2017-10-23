<div class="container list-wrap">
<?php foreach ($comments as $comment) : ?>
        <div class="row my-2 p-2 post-container">
            <div class="col-md-5">
                <a href="<?= $this->di->get("url")->create("forum/post/" . $comment->id) ?>">
                    <h4><?= $comment->title ?></h4>
                </a>
                    <p><?= $comment->postDate ?>
            </div>

            <div class="col-md-3">
                <p><?= $comment->username ?> - <?= $comment->mail ?></p>
            </div>

            <div class="col">
                <!--  GET TAGS AND LOOP -->
                <?php $tags = $di->get("tag")->getTagsForPost($comment->id); ?>
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?= $di->get("url")->create("forum/tag/" . $tag->tag)?>" class="tag-link">
                        <?=$tag->tag ?>
                    </a>
                <?php endforeach; ?>
            </div>


            <div class="col d-flex align-items-end flex-column">
                <p><?= $di->get("comment")->countAnswers($comment->id) ?>
                    <i class="fa fa-reply" aria-hidden="true" title="replies"></i>
                </p>
                <p><?= $comment->likes ?> <i class="fa fa-thumbs-up" aria-hidden="true" title="likes"></i></p>
            </div>
        </div>

<?php endforeach; ?>
</div>
