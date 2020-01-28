<?php
    if (!empty($logged_in_user)) {
        $email_value=$logged_in_user['user_email'];
    } else {
        $email_value=$rr_cookie['sub_email'];
    }
?>

<div class="widget clearfix widget-newsletter">
    <form class="form-inline" method="post" action="<?= $subscribe_url; ?>">
        <h4 class="widget-title"><?=$title;?></h4>
        <p>Receive notification of when entries open, results are loaded and more.</p>
        <div class="input-group m-b-20">
            <input type="email" placeholder="Enter your Email" class="form-control required email" name="user_email" aria-required="true" required="" value="<?=$email_value;?>">
            <span class="input-group-btn">
                <button type="submit" class="btn"><i class="fa fa-paper-plane"></i></button>
            </span>
        </div>
        
    </form>
</div>
