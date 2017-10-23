<header class="page-header">
    <h2><?= $header ?></h2>
    <p><?= $tag->description ?> <a href="<?= $di->get("url")->create("forum/update/tag/$tag->id") ?>"><i class="fa fa-pencil-square-o" aria-hidden="true" title="edit tag description"></i>
</a></p>
</header>
<hr>
