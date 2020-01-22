<section id="page-content" class="no-padding background-grey">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <?php
                if ($edition_list) {
                    ?>
                    <!-- Filter -->
                    <nav class="grid-filter gf-outline" data-layout="#portfolio" style="margin-top: 30px;">
                        <ul>
                            <li class="active"><a href="#" data-category="*">Show All</a></li>
                            <li><a href="#" data-category=".ct-road">Road Run</a></li>
                            <li><a href="#" data-category=".ct-trail">Trail Run</a></li>
                            <li><a href="#" data-category=".ct-runwalk">Run / Walk</a></li>
                            <li><a href="#" data-category=".ct-walk">Walk</a></li>
                            <li><a href="#" data-category=".ct-5km">5km</a></li>
                            <li><a href="#" data-category=".ct-10km">10km</a></li>
                            <li><a href="#" data-category=".ct-21km">Half</a></li>
                            <li><a href="#" data-category=".ct-42km">Marathon</a></li>
                        </ul>
                        <div class="grid-active-title">Show All</div>
                    </nav>
                    <!-- end: Filter -->
                    <div id="portfolio" class="grid-layout portfolio-5-columns" data-margin="20">
                        <?php
                        $count = 0;
                        foreach ($edition_list as $edition) {
                            foreach ($edition['race_list'] as $race) {
                                $dist = fraceDistance(round($race['race_distance'], 0));
                                $filter_dist_arr[$dist] = "ct-" . $dist;
                                $racetype = strtolower(str_replace(" / ", "", $race['racetype_name']));
                                $filter_type_arr[$racetype] = "ct-" . $racetype;
                            }
                            ?>
                            <div class="portfolio-item overlay-links light-bg img-zoom <?= implode(" ", $filter_type_arr); ?> <?= implode(" ", $filter_dist_arr); ?>">
                                <div class="portfolio-item-wrap">
                                    <div class="portfolio-image">
                                        <a href="<?= $edition['edition_url']; ?>" title="<?= $edition['edition_name'] ?>"><img src="<?= $edition['img_url']; ?>" alt="<?= $edition['edition_name'] ?>"></a>
                                        <div class="portfolio-links">
                                            <a href="<?= $edition['edition_url']; ?>" class="btn btn-xxs btn-outline btn-light">View</a>
                                        </div>
                                    </div>
                                    <div class="portfolio-description">
                                        <a href="<?= $edition['edition_url']; ?>" title="<?= $edition['edition_name'] ?>">
                                            <h3><?= $edition['edition_name'] ?></h3>
                                            <span><?= fdateHuman($edition['edition_date']) . " " . ftimeMil($edition['race_time_start']) ?></span>
                                            <p><?= $edition['town_name'] . ", " . $edition['province_abbr'] ?><br>
                                                <?php
                                                $dist_str = "";
                                                foreach ($edition['race_distance_arr'] as $dist) {
                                                    $dist_str .= $dist . ", ";
                                                }
                                                $dist_str = substr($dist_str, 0, -2);
//                                        echo $dist_str;
                                                ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            unset($filter_dist_arr);
                            unset($filter_type_arr);
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    echo '<div class="alert alert-info m-t-15 m-b-10"><i class="fa fa-info-circle"></i> <b>Notice:</b> No results were found for that search. Please try different search paramaters in the form above.</div>';
                }
                ?>
            </div>
        </div>
        <!-- add box -->
        <div class="row m-b-30 m-t-10">
            <div class="col-lg-12">
                <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
            </div>
        </div> 
    </div>
</section>

