<?php
if (!isset($page_title)) {
    $page_title = "Coyote 2.0";
}
?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $page_title; ?></title>
        <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <p>
            <?php
            unset($static_pages['login']);
            unset($static_pages['terms']);
            unset($static_pages['sitemap']);
            foreach ($static_pages as $page) {
                echo "<a href='$page[loc]'>$page[display]</a> | ";
            }
            ?>
            <a href="/mailer">Mailer</a> |
            <?php
            if ($this->session->user['logged_in']) {
                echo "<a href='/user/profile'>" . $this->session->user['user_name'] . "</a> | ";
                echo "<a href='/logout'>Log Out</a>";
            } else {
                echo "<a href='/login/'>Log In</a>";
            }
            ?>
        </p>
        <?php
        if ($this->session->flashdata()) {
            echo "<h4>FLASH DATA</h4>";
            wts($this->session->flashdata());
        }
        ?>
