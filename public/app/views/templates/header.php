<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Coyote</title>
    </head>
    <body>
        <p>
            <a href="/">home</a> |
            <?php
            if ($user_logged_in) {
                echo "<a href='/login/logout'>log out</a>";
            } else {
                echo "<a href='/login/login'>log in</a>";
            }
            ?>
        </p>
