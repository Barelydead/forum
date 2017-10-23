
<div class="tag-container container">
<?php foreach ($tags as $tag) : ?>
    <div class="tag-item my-2">
        <span class="tag-title">
            <a href="<?= $this->di->get("url")->create("forum/tag/" . $tag->tag) ?>" class="tag-item">
                <h4><?= $tag->tag ?></h4>
            </a>
        </span>
        <span class="tag-description">
            <?php if ($tag->description) :?>
                <span><?=$tag->description ?></span>
            <?php else : ?>
                <span>Det finns ingen beskrivning för denna tag. Hjälp till genom att <a href="<?= $this->di->get("url")->create("forum/update/tag/" . $tag->id) ?>">lägga till beskrivning </a></span>
            <?php endif; ?>
            <div class="">
                <?= $di->get("tag")->getPostCount($tag->id)->count ?>
                <i class="fa fa-comments" aria-hidden="true"></i>
            </div>
        </span>
    </div>
<?php endforeach; ?>
</div>
