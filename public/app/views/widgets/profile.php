<div class="widget widget-myaccount p-cb">
    <div class="avatar">
        <?php
        if ($logged_in_user['user_picture']) {
            $img_url = $logged_in_user['user_picture'];
        } else {
            $img_url = base_url("assets/img/Blank-Avatar.jpg");
        }
        ?>
        <img src="<?= $img_url; ?>" style="height: 80px; width: 80px;">
        <span><?= $logged_in_user['user_name']; ?> <?= $logged_in_user['user_surname']; ?></span>
        <p class="text-muted"><?= $logged_in_user['user_email']; ?></p>
    </div>
    <ul class="text-center">
        <?php
        $class = false;
        if ($page_title == "User Profile") {
            $class = "active";
        }
        ?>
        <li class='<?= $class; ?>'><a href="<?= base_url("user/profile"); ?>"><i class="icon-user11"></i>My profile</a></li>

        <?php
        $class = false;
        if (key_exists("My Results", $crumbs_arr)) {
            $class = "active";
        }
        ?>
        <li class='<?= $class; ?>'><a href="<?= base_url("user/my-results"); ?>"><i class="icon-clock21"></i>My Results</a></li>

        <?php
        $class = false;
        if ($page_title == "My Subscriptions") {
            $class = "active";
        }
        ?>
        <li class='<?= $class; ?>'><a href="<?= base_url("user/my-subscriptions"); ?>"><i class="icon-mail"></i>My Subscriptions</a></li>

        <?php
        $class = false;
        if ($page_title == "Region Selection") {
            $class = "active";
        }
        ?>
        <li class='<?= $class; ?>'><a href="<?= base_url("region/switch"); ?>"><i class="icon-settings1"></i>My Regions</a></li>

        <?php
        $class = false;
        if ($page_title == "Donations") {
            $class = "active";
        }
        ?>
        <li class='<?= $class; ?>'><a href="<?= base_url("support"); ?>"><i class="icon-user-check1"></i>Donate</a></li>

        <li><a href="<?= base_url("logout"); ?>"><i class="icon-log-out"></i>Log Out</a>
        </li>
    </ul>
</div>
