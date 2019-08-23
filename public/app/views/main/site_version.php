<h2>SITE VERSION</h2>
<p>Please use form below to select site version<p>
    
<?php
echo "<blockquote>";
echo validation_errors();
echo "</blockquote>";

echo form_open($form_url);

echo "<div class='form-group'>";
echo form_label('Site Version', 'site_version');
echo form_multiselect('site_version[]', $region_dropdown, $this->session->region_selection, ["id"=>"site_version","class"=>"form-control","size"=>10]);        
echo "</div>";

echo "<div>";
echo form_submit('', 'Set');
echo form_reset('', 'Reset');
echo form_close();
echo "</div>";
?>