<?php
$url = $this->di->get("url");
?>
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto p-2">
            <li><a class="nav-link" href="<?= $url->create("")?>">Forum</a></li>
            <li><a class="nav-link" href="<?= $url->create("forum/posts")?>">All Posts</a></li>
            <li><a class="nav-link" href="<?= $url->create("forum/tags")?>">Tags</a></li>
            <li><a class="nav-link" href="<?= $url->create("forum/new")?>">New post</a></li>
            <li><a class="nav-link" href="<?= $url->create("users")?>">Users</a></li>
            <li><a class="nav-link" href="<?= $url->create("about")?>">About</a></li>
        </ul>
        <ul class="navbar-nav p-2">
            <li><a class="nav-link" href="<?= $url->create("login")?>">Login</a></li>
            <li><a class="nav-link" href="<?= $url->create("create")?>">Create</a></li>
            <li><a class="nav-link" href="<?= $url->create("user/profile")?>">Profile</a></li>
            <li><a class="nav-link" href="<?= $url->create("user/logout")?>">Logout</a></li>
        </ul>
    </div>
</nav>
