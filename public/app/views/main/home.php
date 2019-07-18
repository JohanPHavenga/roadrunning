<p>Welcome, to the real world</p>

<h2>Logged in</h2>
<p><?= fyesNo($user_logged_in); ?>
        
<h2>List of Provinces</h2>
<?php wts($province_dropdown);?>

<h2>List of Featured Races</h2>

<h2>SESSION</h2>
<?php wts($_SESSION);?>

<h2>COOKIES</h2>
<?php wts($_COOKIE);?>
