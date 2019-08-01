<h2>CONFIRMATION</h2>
<?php
    switch($conf_type) {
        case "register":
            $text="Your registration has been successful. Please click on the confirmation email send to <strong>$email</strong> to complete the process";
            break;        
        case "password-reset":
            $text="Your password reset request has been successful. Please <a href='/login' title='Go to login page'>click here</a> to log in";
            break;
        default:
            $text="No confirmation type set";
            break;
    }
?>
<p><?=$text;?><p>
    
<?php
wts($this->input->post());
?>