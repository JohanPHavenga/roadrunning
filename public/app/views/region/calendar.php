<h2>RACE CALENDAR FOR <?= strtoupper($region_name);?></h2>
<p>Races for this region<p>
    
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

