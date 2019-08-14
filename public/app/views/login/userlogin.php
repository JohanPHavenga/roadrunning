<h2>LOGIN</h2>
<p>Please log in</p>

<?php
echo validation_errors();

echo form_open($form_url);
echo "<div>";
echo form_label('Email', 'user_email');
echo form_input([
    'name' => 'user_email',
    'id' => 'user_email',
    'placeholder' => 'Email address',
    'value' => set_value('user_email'),
    'required' => '',
    'autofocus' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Password', 'user_password');
echo form_input([
    'name' => 'user_password',
    'id' => 'user_password',
    'type' => 'password',
    'placeholder' => 'Password',
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_submit('', 'Login');
echo form_reset('', 'Clear');
echo form_close();
echo "</div>";
?>
<p>
    <a href="/register">Register</a> |
    <a href="/forgot-password">Forgot Password</a>
</p>
