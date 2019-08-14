<h2>FORGOT PASSWORD</h2>
<p>Enter email below to reset your password</p>

<?php
echo "<blockquote>";
echo validation_errors();
echo "</blockquote>";

echo form_open($form_url);
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
echo form_submit('', 'Reset');
echo form_reset('', 'Clear');
echo form_close();
echo "</div>";
?>
