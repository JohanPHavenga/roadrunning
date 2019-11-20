<section id="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>RACE CALENDAR</h2>
                <p>This is the race calendar page<p>

                    <?php
                    foreach ($edition_arr as $year => $year_list) {
                        echo "<h3>$year</h3>";
                        foreach ($year_list as $month => $month_list) {
                            echo "<h3>$month</h3>";
                            foreach ($month_list as $day => $edition_list) {
                                foreach ($edition_list as $edition_id => $edition) {
                                    echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                                }
                            }
                        }
                    }
                    ?>
            </div>
        </div>
    </div>
</section>