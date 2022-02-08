<section id="page-content" class="sidebar-right m-t-0 p-t-5">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        if (isset($tag)) {
                        ?>
                            <h3 class="uppercase m-t-20">Tag: <b><?= $tag; ?></b></h3>
                        <?php
                        }
                        ?>
                        <div class="accordion accordion-simple m-t-10">
                            <?php
                            if ($edition_list) {
                                foreach ($edition_list as $edition_id => $edition) {
                                    $badge_state = false;
                                    foreach ($edition['race_list'] as $race) {
                                        if ($race['racetype_abbr'] == "R/W") {
                                            $race['racetype_abbr'] = "R";
                                        }
                                        $race_summary['distance'][$race['race_distance']] = $race['race_distance'];
                                        $race_summary['distance_int'][$race['race_distance']] = intval($race['race_distance']);
                                        $race_summary['color'][$race['race_distance']] = $race['race_color'];
                                        $race_summary['name'][$race['race_distance']] = $race['race_name'];
                                        $race_summary['dist_icon'][$race['race_distance']] = $race['racetype_icon'];
                                        //                                        $race_summary['type'][$race['racetype_abbr']] = $race['racetype_name'];
                                        $race_summary['icon'][$race['racetype_abbr']] = $race['racetype_icon'];
                                        $race_summary['abbr'][$race['racetype_abbr']] = $race['racetype_abbr'];
                                    }
                                    $edition_list[$edition_id]['race_summary'] = $race_summary;
                                    //                                    wts($race_summary);
                                    if ($edition_id === array_key_first($edition_list)) {
                                        $ac_cl = "ac-active";
                                    } else {
                                        $ac_cl = "";
                                    }
                            ?>
                                    <div class="ac-item <?= $ac_cl; ?>">
                                        <h5 class="ac-title">
                                            <i class="fa fa-<?= $edition['status_info']['icon']; ?> text-<?= $edition['status_info']['state']; ?>"></i>
                                            <?php
                                            if ($edition['edition_isfeatured']) {
                                                echo "<b>";
                                            }
                                            echo date("d M", strtotime($edition['edition_date'])) . " - " . $edition['edition_name'];
                                            if ($edition['edition_isfeatured']) {
                                                echo "</b>";
                                                $badge_state = "success";
                                                $badge_text = "Featured";
                                            }

                                            // check state for badge
                                            if ($edition['edition_status'] == 3) {
                                                $badge_state = "danger";
                                                $badge_text = "Cancelled";
                                            }
                                            // check state for badge
                                            if ($edition['edition_status'] == 9) {
                                                $badge_state = "warning";
                                                $badge_text = "Postponed";
                                            }
                                            // check state for badge
                                            if ($edition['edition_status'] == 17) {
                                                $badge_state = "primary";
                                                $badge_text = "Virtual";
                                            }

                                            if ($badge_state) {
                                                echo " <span class='badge badge-$badge_state'>$badge_text</span>";
                                                $edition_list[$edition_id]['badge']['state'] = $badge_state;
                                                $edition_list[$edition_id]['badge']['text'] = $badge_text;
                                            }
                                            //                                            echo "&nbsp";
                                            //                                            foreach ($race_summary['icon'] as $rt) {
                                            //                                                echo "<i class='fa fa-" . $rt . "' aria-hidden='true' title=''></i>";
                                            //                                            }
                                            ?>

                                        </h5>
                                        <div class="ac-content">
                                            <p>Annual <?= $edition['event_name']; ?> in <b><?= $edition['town_name'] . "</b>, " . $edition['province_abbr']; ?>.</p>
                                            <p>
                                                <b>When:</b> <?= date("l, d F Y", strtotime($edition['edition_date'])); ?><br>
                                                <b>Distances:</b>
                                                <?php
                                                foreach ($race_summary['distance'] as $dist) {
                                                    //                                                            echo fraceDistance($dist).", ";
                                                    echo '<a href="' . base_url('event/' . $edition['edition_slug'] . '/distances/' . url_title($race_summary['name'][$dist])) . '"><span title="' . $race_summary['name'][$dist] . '" class="badge badge-' . $race_summary['color'][$dist] . '">' . fraceDistance($dist) . '';
                                                    echo ' <i class="fa fa-' . $race_summary['dist_icon'][$dist] . '"></i></span></a> ';
                                                }
                                                ?><br>
                                                <b>Time:</b> <?= ftimeMil($edition['race_time_start']); ?><br>
                                                <b>Info Status:</b> <?= $edition['status_info']['short_msg']; ?>
                                            </p>

                                            <div class="btn-group m-l-25">
                                                <a class="btn btn-outline btn-sm" href="<?= base_url("event/" . $edition['edition_slug']); ?>">View <i class="fa fa-eye"></i></a>
                                                <?php
                                                if (isset($edition['has_results'])) {
                                                    if ($edition['has_results']) {
                                                ?>
                                                        <a class="btn btn-outline btn-sm" href="<?= base_url("event/" . $edition['edition_slug']) . "/results"; ?>">Results <i class="fa fa-list-alt" aria-hidden="true"></i></a>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            //                                            wts($edition);
                                            //                                            wts($race_summary);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                    unset($race_summary);
                                }
                            } else {
                                echo '<div class="alert alert-info m-t-15 m-b-10"><i class="fa fa-info-circle"></i> <b>Notice:</b> No races were found matching those paramaters. Please try a new search using the form above.</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    // wts($edition_list);
                    ?>
                    <div class="col-lg-12" id="print-race-table">
                        <?php
                        if ($edition_list) {
                        ?>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Race Name</th>
                                        <th scope="col">Distances</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Town</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($edition_list as $edition_id => $edition) {                                        
                                        echo "<tr>";
                                        echo "<th scope='row'>" . date("d M Y", strtotime($edition['edition_date'])) . "</th>";
                                        echo "<td>" . $edition['edition_name'];
                                        if ($edition['edition_status'] != 1) {
                                            echo " [" . $edition['badge']['text'] . "]";
                                        }
                                        echo "</td>";
                                        echo "<td>". implode("/",$edition['race_summary']['distance_int'])."</td>";
                                        echo "<td>" . ftimeSort($edition['race_time_start']) . "</td>";
                                        echo "<td>" . $edition['town_name'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                    </div>

                </div>
                <!-- add box -->
                <div class="row m-b-10 m-t-10">
                    <div class="col-lg-12">
                        <?php
                        // LANDSCAPE ADS WIDGET
                        $this->load->view('widgets/horizontal_ad');
                        ?>
                    </div>
                </div>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3 m-t-15">
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Race List -->

<?php
//wts($edition_arr);
