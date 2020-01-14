<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 center no-padding">
                <?php
                switch ($conf_type) {
                    case "register":
                        $heading = "<span class='fa fa-check-circle text-success'></span> EMAIL SEND";
                        $text = "Your registration process has been kicked-off! <br> Please click on the confirmation email send to <strong>$email</strong> to complete the process";
                        break;
                    case "forgot_password":
                        $heading = "<span class='fa fa-check-circle text-success'></span> EMAIL SEND";
                        $text = "An email is on it's way to <b><a href='mailto:$email'>$email</a></b> to confirm that it really is you resetting your password. <br>Click on the button or link in the confirmation email to complete the process.";
                        break;
                    case "confirm_email":
                        $heading = "<span class='fa fa-check-circle text-success'></span> YEAH! SUCCESS";
                        $text = "Your email address has been confirmed. Please proceed to the <a href='" . base_url("login") . "'>login</a> page, using your email address and newly created password to log in";                        
                        $btn_url = base_url('login');
                        $btn_text = "Login";
                        break;
                    case "guid_not_found":
                        $heading = "<span class='fa fa-minus-circle text-danger'></span> Mmmmm, something is not right";
                        $text = "We can not seem to find your account, or the registration email has expired. Please try to <a href='" . base_url("register") . "'>register</a> your account again";
                        break;
                    case "guid_not_found_pass":
                        $heading = "<span class='fa fa-info-circle text-warning'></span> Darn it, token has expired";
                        $text = "The link you are using seems to have expired. Please try to <a href='" . base_url("forgot-password") . "'>reset your password</a> again";
                        $btn_url = base_url('forgot-password');
                        $btn_text = "Reset Password";
                        break;
                    case "reset_password":
                        $heading = "<span class='fa fa-check-circle text-success'></span> PASSWORD UPDATED";
                        $text = "Your password has been successfully changed! Click on the button below to log in.";
                        $btn_url = base_url('login');
                        $btn_text = "Login";
                        break;
                    default:
                        $heading = "Err";
                        $text = "No confirmation type set";
                        break;
                }
                ?>
                <h3><?= $heading; ?></h3>
                <p><?= $text; ?><p>
                    <?php
                    if (isset($btn_url)) {
                        ?>
                        <a href="<?= $btn_url; ?>" class="btn btn-light"><?= $btn_text; ?></a>
                        <?php
                    }
                    ?>
            </div>
        </div>
    </div>
</section>