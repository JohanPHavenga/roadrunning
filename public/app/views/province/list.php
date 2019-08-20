<h2>PROVINCE LIST</h2>
<p>This is a list of all the provinces<p>
    
<ul>
<?php
    foreach ($province_pages as $province_id=>$province) {
        echo "<li><a href='".$province['loc']."'>".ucwords($province['display'])."</a></li>";
    }
?>
</ul>

