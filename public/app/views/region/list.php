<h2>REGION LIST</h2>
<p>This is a list of all the regions<p>
    
<ul>
<?php
    foreach ($region_pages as $region_id=>$region) {
        echo "<li><a href='".$region['loc']."'>".ucwords($region['display'])."</a></li>";
    }
?>
</ul>

