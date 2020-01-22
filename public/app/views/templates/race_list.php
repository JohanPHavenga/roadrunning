<section id="page-content" class="sidebar-right m-t-0 p-t-5">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="accordion accordion-simple">
                            <?php
                            if ($edition_list) {
                                foreach ($edition_list as $edition_id => $edition) {
                                    foreach ($edition['race_list'] as $race) {
                                        if ($race['racetype_abbr'] == "R/W") {
                                            $race['racetype_abbr'] = "R";
                                        }
                                        $race_summary['distance'][$race['race_distance']] = $race['race_distance'];
                                        $race_summary['color'][$race['race_distance']] = $race['race_color'];
                                        $race_summary['name'][$race['race_distance']] = $race['race_name'];
                                        $race_summary['dist_icon'][$race['race_distance']] = $race['racetype_icon'];
//                                        $race_summary['type'][$race['racetype_abbr']] = $race['racetype_name'];
                                        $race_summary['icon'][$race['racetype_abbr']] = $race['racetype_icon'];
                                        $race_summary['abbr'][$race['racetype_abbr']] = $race['racetype_abbr'];
                                    }
//                                    wts($race_summary);
//                                    if ($edition_id === array_key_first($edition_list)) {
//                                        $ac_cl="ac-active";
//                                    } else {
//                                        $ac_cl="";
//                                    }
                                    ?>
                                    <div class="ac-item">
                                        <h5 class="ac-title">   
                                            <i class="fa fa-<?= $edition['status_info']['icon']; ?> text-<?= $edition['status_info']['state']; ?>"></i>
                                            <?php
                                            if ($edition['edition_isfeatured']) {
                                                echo "<b>";
                                            }
                                            echo date("d M", strtotime($edition['edition_date'])) . " - " . $edition['edition_name'];
                                            if ($edition['edition_isfeatured']) {
                                                echo "</b>";
                                                echo " <span class='badge badge-danger' title='Featured Race'>Featured</span>";
                                            }
//                                            echo "&nbsp";
//                                            foreach ($race_summary['icon'] as $rt) {
//                                                echo "<i class='fa fa-" . $rt . "' aria-hidden='true' title=''></i>";
//                                            }
                                            ?>

                                        </h5>
                                        <div class="ac-content" style="display: none;">
                                            <p>Annual <?= $edition['event_name']; ?> in <b><?= $edition['town_name'] . "</b>, " . $edition['province_abbr']; ?>.</p>
                                            <p>
                                                <b>When:</b> <?= date("l, d F Y", strtotime($edition['edition_date'])); ?><br>
                                                <b>Distances:</b> 
                                                <?php
                                                foreach ($race_summary['distance'] as $dist) {
//                                                            echo fraceDistance($dist).", ";
                                                    echo '<a href="'. base_url('event/' . $edition['edition_slug'] . '/distances/' . url_title($race_summary['name'][$dist])).'"><span title="' . $race_summary['name'][$dist] . '" class="badge badge-' . $race_summary['color'][$dist] . '">' . fraceDistance($dist) . '';
                                                    echo ' <i class="fa fa-'.$race_summary['dist_icon'][$dist].'"></i></span></a> ';
                                                }
                                                ?><br>
                                                <b>Time:</b> <?= ftimeMil($edition['race_time_start']); ?><br>
                                                <b>Info Status:</b> <?= $edition['status_info']['short_msg']; ?>
                                            </p>

                                            <p>
                                                <a class="btn btn-light btn-sm" href="<?= base_url("event/".$edition['edition_slug']) ;?>">More Info</a>
                                            </p>
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
                </div>                
                <!-- add box -->
                <div class="row m-b-30 m-t-10">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
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

