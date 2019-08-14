<h2>RESET PASSWORD</h2>
<p>Type in your new password below</p>

<?php
echo "<blockquote>";
echo validation_errors();
echo "</blockquote>";

echo form_open($form_url);
echo "<div>";
echo form_label('Password *', 'user_password');
echo form_input([
    'name' => 'user_password',
    'id' => 'user_password',
    'type' => 'password',
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Confirm Password *', 'user_password_conf');
echo form_input([
    'name' => 'user_password_conf',
    'id' => 'user_password_conf',
    'type' => 'password',
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_submit('', 'Set Password');
echo form_reset('', 'Clear');
echo form_close();
echo "</div>";
?>
