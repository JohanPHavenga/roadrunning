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
        foreach ($page_menu as $key => $page) {
            $cl = null;
            if ($page['loc'] == current_url()) {
                $cl = "active";
            }
            if ($key == "logout") {
                echo "<li class='$cl'><a href='' data-href='' data-toggle='modal' data-target='#confirm-logout'><i class='$page[icon]'></i>$page[display]</a>";
            } else {
                echo "<li class='$cl'><a href='$page[loc]'><i class='$page[icon]'></i>$page[display]</a></li>";
            }
        }
        ?>
        </li>
    </ul>
</div>
