<?php
    if (!isset($page_title)) { $page_title="Coyote 2.0"; }
?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$page_title;?></title>
        <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <p>
            <a href="/">home</a> |            
            <a href="/about">about</a> |
            <a href="/contact">contact</a> |
            <a href="/mailer">mailer</a> |
            <?php
            if (isset($_SESSION['user']['logged_in'])) {
                echo "<a href='/user/profile'>".$this->session->user['user_name']."</a> | ";
                echo "<a href='/logout'>log out</a>";
            } else {
                echo "<a href='/login/'>log in</a>";
            }
            ?>
        </p>
        <?php 
         if ($this->session->flashdata()) {
            echo "<h4>FLASH DATA</h4>";
             wts($this->session->flashdata()); 
         }
         ?>
