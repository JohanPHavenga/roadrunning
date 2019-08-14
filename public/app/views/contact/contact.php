<h2>CONTACT US</h2>
<p>Complete the form below to send an email to us</p>

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
    'required' => '',
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
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Email *', 'user_email');
echo form_input([
    'name' => 'user_email',
    'id' => 'user_email',
    'placeholder' => 'Email address',
    'value' => set_value('user_email'),
    'type' => 'email',
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_label('Comment', 'user_comment');
echo form_textarea([
    'name' => 'user_comment',
    'id' => 'user_comment',
    'required' => '',
]);
echo "</div>";

echo "<div>";
echo form_submit('', 'Send');
echo form_reset('', 'Clear');
echo form_close();
echo "</div>";
?>
