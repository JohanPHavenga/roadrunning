<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Race Distances</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- add box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <div class="accordion accordion-shadow">
                            <?php
                            foreach ($race_list as $race_id => $race) {
                                $active = '';
//                                if ($race_id === array_key_first($race_list)) {
                                if (isset($url_params[1])) {
                                    if (url_title($race['race_name']) == $url_params[1]) {
                                        $active = "ac-active";
                                    }
                                } elseif ($race_id === array_key_first($race_list)) {                                    
                                    $active = "ac-active";
                                }
                                ?>
                                <div class="ac-item <?= $active; ?>">
                                    <h5 class="ac-title"><i class="fa fa-<?= $race['racetype_icon']; ?>"></i><?= $race['race_name']; ?></h5>
                                    <div class="ac-content">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td style='width: 50%;'>Date</td>
                                                    <td><?= fdateHuman($race['race_date']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Start Time</td>
                                                    <td><?= ftimeSort($race['race_time_start']); ?></td>
                                                </tr>
                                                <?php
                                                if ($race['race_time_end'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Cut-off Time</td>
                                                        <td><?= ftimeSort($race['race_time_end']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td>Distance</td>
                                                    <td><span class="badge badge-<?= $race['race_color']; ?>"><?= fraceDistance($race['race_distance']); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Race Type</td>
                                                    <td><?= $race['racetype_name']; ?></td>
                                                </tr>
                                                <?php
                                                if ($race['race_fee_flat'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Race fee:</td>
                                                        <td>R<?= floatval($race['race_fee_flat']); ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    if ($race['race_fee_senior_licenced'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Senior Licensed Runner:</td>
                                                            <td>R<?= floatval($race['race_fee_senior_licenced']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    if ($race['race_fee_senior_unlicenced'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Senior Unlicensed Runner:</td>
                                                            <td>R<?= floatval($race['race_fee_senior_unlicenced']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    if ($race['race_fee_junior_licenced'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Junior Licensed Runner:</td>
                                                            <td>R<?= floatval($race['race_fee_junior_licenced']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    if ($race['race_fee_junior_unlicenced'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>Junior Unlicensed Runner:</td>
                                                            <td>R<?= floatval($race['race_fee_junior_unlicenced']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                if ($race['race_isover70free']) {
                                                    ?>
                                                    <tr>
                                                        <td>Licensed Athlete 70+:</td>
                                                        <td>Free</td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($race['race_minimum_age'] > 0) {
                                                    ?>
                                                    <tr>
                                                        <td>Minimum age:</td>
                                                        <td><?= $race['race_minimum_age']; ?> years</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <p>
                                            <?= $race['race_notes']; ?>
                                        </p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Get Notified via Email";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<?php
//wts($race_list);

