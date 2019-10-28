    
<p>&nbsp;</p>
<p>
<!--    <a href="/sitemap">sitemap</a> |            
    <a href="/terms-conditions">terms & conditions</a>-->
    <?php
    $white_list = ["terms", "sitemap"];
    foreach ($static_pages as $key=>$page) {
        if (!in_array($key, $white_list)) { continue; }
        echo "<a href='$page[loc]'>$page[display]</a> | ";
    }    
    ?>
    <a href="<?=base_url("login/destroy");?>">Kill Session</a>
</p>
<hr>
<h2>FOOTER INFO</h2>

<h4>User info</h4>
<?php
wts($logged_in_user);
?>

<h4>SESSION</h4>
<?php wts($_SESSION); ?>
<?= "Session ID:" . session_id(); ?>

<h4>COOKIE</h4>
<?php wts($_COOKIE); ?>
</body>
</html>