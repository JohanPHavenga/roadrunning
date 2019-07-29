<?php
    if (!isset($page_title)) { $page_title="Coyote 2.0"; }
?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$page_title;?></title>
    </head>
    <body>
        <p>
            <a href="/">home</a> |            
            <a href="/about">about</a> |
            <a href="/mailer/test">mail</a> |
            <?php
            if (isset($_SESSION['user']['logged_in'])) {
                echo "<a href='/login/logout'>log out</a>";
            } else {
                echo "<a href='/login/userlogin'>log in</a>";
            }
            ?>
        </p>
        <?php 
         if ($this->session->flashdata()) {
            echo "<h4>FLASH DATA</h4>";
             wts($this->session->flashdata()); 
         }
         ?>
