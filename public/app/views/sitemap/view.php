<h2>Sitemap</h2>
<ul>
<?php
    foreach ($static_pages as $page_detail) {
        echo "<li><a href='".$page_detail['loc']."'>".ucwords($page_detail['display'])."</a></li>";
    }
?>
</ul>
<ul>
<?php
    foreach ($province_pages as $province_id=>$province) {
        echo "<li><a href='".$province['loc']."'>".ucwords($province['display'])."</a></li>";
    }
?>
</ul>
<ul>
<?php
    foreach ($region_pages as $region_id=>$region) {
        echo "<li><a href='".$region['loc']."'>".ucwords($region['display'])."</a></li>";
    }
?>
</ul>    
<?php
    foreach ($edition_arr as $year => $year_list) {
        echo "<h3>$year</h3>";
        foreach ($year_list as $month => $month_list) {
            echo "<h3>$month</h3>";
            foreach ($month_list as $day => $edition_list) {
                foreach ($edition_list as $edition_id => $edition) {
                    echo date("D j M",strtotime($edition['edition_date']))." - <a href='".base_url('event/'.$edition['edition_slug'])."'>".$edition['edition_name']."</a> ";
                }
            }
        }
    }
?>
</ul>

<?php
//wts($static_pages);
//wts($province_arr);
//wts($edition_arr);