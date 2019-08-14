<h2>CONFIRMATION</h2>
<?php
    switch($conf_type) {
        case "register":
            $text="Your registration has been successful. Please click on the confirmation email send to <strong>$email</strong> to complete the process";
            break;    
        case "forgot_password":
            $text="An email has been send to <strong>$email</strong> to confirm that it is really you. Please click on the confirmation email to complete the process and set a new password";
            break;
        case "confirm_email":
            $text="Your email address has been confirmed. Please proceed to the <a href='".base_url("login")."'>login</a> page, using your email address and newly set password to log in";
            break;        
        case "guid_not_found":
            $text="We can not seem to find your account, or the registration email has expired. Please try to <a href='".base_url("register")."'>register</a> your account again";
            break;  
        case "guid_not_found_pass":
            $text="The link you are using seems to have expired. Please try to <a href='".base_url("forgot-password")."'>reset your password</a> again";
            break;  
        case "reset_password":
            $text="Your password has been successfully changed. Please <a href='/login' title='Go to login page'>click here</a> to log in";
            break;
        default:
            $text="No confirmation type set";
            break;
    }
?>
<p><?=$text;?><p>
    
<?php 
//wts($this->input->post());
?>