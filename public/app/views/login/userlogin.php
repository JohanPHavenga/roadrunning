<h2>LOGIN</h2>
<p>Please log in</p>

<?php
echo validation_errors();

echo form_open($form_url);
echo form_label('Username', 'user_username');
echo form_input([
    'name' => 'user_username',
    'id' => 'user_username',
    'placeholder' => 'Username',
    'value' => set_value('user_username'),
    'required' => '',
    'autofocus' => '',
]);
echo form_label('Password', 'user_password');
echo form_input([
    'name' => 'user_password',
    'id' => 'user_password',
    'type' => 'password',
    'placeholder' => 'Password',
    'required' => '',
]);
echo form_submit('', 'Login');
echo form_reset('', 'Clear');
echo form_close();
?>
<p>
    <a href="">Register</a> |
    <a href="">Forgot Password</a>
</p>
