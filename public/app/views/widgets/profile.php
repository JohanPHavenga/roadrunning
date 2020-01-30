<div class="widget widget-myaccount p-cb">
    <div class="avatar">
        <img src="<?= base_url("assets/img/Blank-Avatar.jpg"); ?>">
        <span><?= $logged_in_user['user_name']; ?> <?= $logged_in_user['user_surname']; ?></span>
        <p class="text-muted"><?= $logged_in_user['user_email']; ?></p>
    </div>
    <ul class="text-center">
        <li><a href="<?= base_url("user/profile"); ?>"><i class="icon-user11"></i>My profile</a></li>
        <li><a href="<?= base_url("user/my-results"); ?>"><i class="icon-clock21"></i>My Results</a></li>
        <li><a href="<?= base_url("user/my-subscriptions"); ?>"><i class="icon-mail"></i>My Subscriptions</a></li>
        <li><a href="<?= base_url("region/switch"); ?>"><i class="icon-settings1"></i>My Regions</a></li>
        <li><a href="<?= base_url("logout"); ?>"><i class="icon-log-out"></i>Log Out</a>
        </li>
    </ul>
</div>
