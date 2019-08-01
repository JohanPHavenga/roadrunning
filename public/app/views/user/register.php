<h2>REGISTER</h2>
<p>Complete the form below to register as a user on the site</p>

<?php
echo "<blockquote>";
echo validation_errors();
echo "</blockquote>";

echo form_open($form_url);
echo "<div>";
echo form_label('Name *', 'user_name');
echo form_input([
    'name' => 'user_name',
    'id' => 'user_name',
    'placeholder' => 'Name',
    'value' => set_value('user_name'),
//    'required' => '',
    'autofocus' => '',
]);
echo "</div>";


echo "<div>";
echo form_label('Surname *', 'user_surname');
echo form_input([
    'name' => 'user_surname',
    'id' => 'user_surname',
    'placeholder' => 'Surame',
    'value' => set_value('user_surname'),
//    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Email *', 'user_email');
echo form_input([
    'name' => 'user_email',
    'id' => 'user_email',
    'placeholder' => 'Email address',
    'value' => set_value('user_email'),
//    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Password *', 'user_password');
echo form_input([
    'name' => 'user_password',
    'id' => 'user_password',
    'type' => 'password',
//    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Confirm Password *', 'user_password_conf');
echo form_input([
    'name' => 'user_password_conf',
    'id' => 'user_password_conf',
    'type' => 'password',
//    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_submit('', 'Register');
echo form_reset('', 'Clear');
echo form_close();
echo "</div>";
?>
