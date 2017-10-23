<ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="nav-link" href="<?= $di->get("url")->create("user/profile")?>">Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= $di->get("url")->create("user/edit/profile")?>">Edit Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= $di->get("url")->create("user/edit/password")?>">Change password</a>
    </li>
</ul>
