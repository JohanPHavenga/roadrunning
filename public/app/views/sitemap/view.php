<h2>Sitemap</h2>
<ul>
<?php
    foreach ($static_pages as $pagename=>$url) {
        echo "<li><a href='".base_url($url)."'>".ucwords($pagename)."</a></li>";
    }
?>
</ul>
    
<?php
    foreach ($edition_list as $year => $year_list) {
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
//wts($edition_list);