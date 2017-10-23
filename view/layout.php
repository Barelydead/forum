<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= $this->asset("css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= $this->asset("css/font-awesome.min.css") ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php foreach ($stylesheets as $stylesheet) : ?>
    <link rel="stylesheet" type="text/css" href="<?= $this->asset($stylesheet) ?>">
    <?php endforeach; ?>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

</head>
<body>

<div class="site-wrap">
    <div class="inner-wrap">

        <?php if ($this->regionHasContent("header")) : ?>
        <div class="header-wrap">
            <div class="container">
            <img src="<?= $di->get("url")->create("image") ?>/frameworktext.jpg?w=1140&height=320&crop-to-fit" alt="framework">
                <?php $this->renderRegion("header") ?>
            </div>

        </div>
        <?php endif; ?>

        <?php if ($this->regionHasContent("navbar")) : ?>
        <div class="navbar-wrap">
            <div class="container">
                <?php $this->renderRegion("navbar") ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this->regionHasContent("flash")) : ?>
        <div class="flash-wrap">
            <div class="container">
                <?php $this->renderRegion("flash") ?>
            </div>
        </div>
        <?php endif; ?>


        <?php if ($this->regionHasContent("main")) : ?>
        <div class="main-wrap">
            <div class="container">
                <?php $this->renderRegion("main") ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this->regionHasContent("footer")) : ?>
        <div class="footer-wrap">
            <div class="container">
                <?php $this->renderRegion("footer") ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script type="text/javascript" src="<?= $this->asset("js/main.js") ?>"></script>
</body>
</html>
