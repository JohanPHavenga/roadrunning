<?php
//    wts($crumbs_arr,1);
?>
<section id="page-content" class="sidebar-left">
    <div class="container">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // PROFILE WIDGET
                $this->load->view('widgets/profile');
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->

            <!-- Content-->
            <div class="content col-lg-9">
                <?php
                // CRUMBS WIDGET
                $this->load->view('widgets/crumbs');
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-uppercase m-b-0"><span class="badge badge-<?= $result_detail['race_color']; ?>" ><?= round($result_detail['race_distance'], 0); ?>km</span></h3>
                        <h4 class="text-uppercase m-b-0"><?= $result_detail['edition_name']; ?></h4>
                        <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($result_detail['edition_date'], true); ?></h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="<?= base_url("file/race/" . $result_detail['edition_slug'] . "/results/" . url_title($result_detail['race_name']) . "/" . $result_detail['file_name']); ?>" class="btn btn-light">
                            <i class="fa fa-file-excel"></i> Download Result File</a>
                    </div>
                </div>
                <hr >
                <?php
                $template = array(
                    'table_open' => '<table class="table table-striped table-bordered table-hover table-sm">',
                );

                $this->table->set_template($template);
//                $this->table->set_heading('Field', 'Value');

                $this->table->add_row(["Position", $result_detail['result_pos']]);
                $this->table->add_row(["Time", $result_detail['result_time']]);
                $this->table->add_row(["Name", $result_detail['result_name']]);
                $this->table->add_row(["Surame", $result_detail['result_surname']]);
                if ($result_detail['result_club']) {
                    $this->table->add_row(["Club", $result_detail['result_club']]);
                }
                if ($result_detail['result_age']) {
                    $this->table->add_row(["Age", $result_detail['result_age']]);
                }
                if ($result_detail['result_sex']) {
                    $this->table->add_row(["Gender", $result_detail['result_sex']]);
                }
                if ($result_detail['result_cat']) {
                    $this->table->add_row(["Category", $result_detail['result_cat']]);
                }
                if ($result_detail['result_asanum']) {
                    $this->table->add_row(["ASA Number", $result_detail['result_asanum']]);
                }
                if ($result_detail['result_racenum']) {
                    $this->table->add_row(["Race Number", $result_detail['result_racenum']]);
                }

                echo $this->table->generate();
                ?>
                <a href="/user/my-results" class="btn"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <!-- end: Content-->

        </div>
    </div>
</section>
<!-- end: My Results -->


