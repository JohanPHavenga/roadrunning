<section id="page-content" class="background-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Below find a list of races for the year <b><?= $year; ?></b>. Click on the race name to expand.<p>
                    <?php
                    if ($edition_arr) {
                        ?>
                    <div class="accordion accordion-shadow">
                        <?php
                        foreach ($edition_arr as $year => $year_list) {
                            foreach ($year_list as $month => $month_list) {
                                foreach ($month_list as $day => $edition_list) {
                                    foreach ($edition_list as $edition_id => $edition) {
                                        foreach ($edition['race_list'] as $race) {
                                            $dist = fraceDistance(round($race['race_distance'], 0));
                                            $filter_dist_arr[$dist] = "ct-" . $dist;
                                            $racetype = strtolower(str_replace(" / ", "", $race['racetype_name']));
                                            $filter_type_arr[$racetype] = "ct-" . $racetype;
                                        }
                                        ?>
                                        <div class="ac-item">
                                            <h5 class="ac-title"><?= date("D j M Y", strtotime($edition['edition_date'])) . " - " . $edition['edition_name']; ?></h5>
                                            <div class="ac-content" style="display: none;">
                                                <p>Annual <?= $edition['event_name']; ?> in <?= $edition['town_name'] . ", " . $edition['province_abbr']; ?>.</p>
                                                <?php
                                                $days_ago = date("d", time() - strtotime($edition['edition_date']));
                                                switch ($edition['edition_info_status']) {
                                                    case 10:
                                                        // pending
                                                        echo "<p><span class='fa fa-info-circle text-info'></span> Results is pending and has not been loaded yet.</p>";
                                                        break;
                                                    case 11;
                                                        // loaded
                                                        echo "<p><span class='fa fa-check-circle text-success'></span> Results has been loaded.</p>";
                                                        break;
                                                    case 12:
                                                        // no results expected
                                                        echo "<p><span class='fa fa-minus-circle text-warning'></span> No results is expected for this race.</p>";
                                                        break;
                                                    default:
                                                        echo "<p>No results available.</p>";
                                                        break;
                                                }
                                                ?>
                                                <p>
                                                    <a class="btn btn-light btn-icon-holder" href="<?= $edition['edition_url']; ?>">View race details
                                                        <i class="fa fa-arrow-right"></i></a>
                                                </p>

                                            </div>
                                        </div>
                                        <?php
//                                echo date("D j M", strtotime($edition['edition_date'])) . " - <a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a> ";
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    echo "<p>No results found</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
//wts($edition_arr);

