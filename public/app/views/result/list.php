<?php
//    wts($crumbs_arr,1);
?>
<section id="page-content" class="sidebar-left">
    <div class="container">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                if (!empty($logged_in_user)) {
                    // PROFILE WIDGET
                    $this->load->view('widgets/profile');
                }
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
                        <h3 class="text-uppercase m-b-0"><span class="badge badge-<?= $race_info['race_color']; ?>" ><?= round($race_info['race_distance'], 0); ?>km</span></h3>
                        <h4 class="text-uppercase m-b-0"><?= $race_info['edition_name']; ?></h4>
                        <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($race_info['edition_date'], true); ?></h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="<?= base_url("file/race/" . $race_info['edition_slug'] . "/results/" . url_title($race_info['race_name']) . "/" . $race_info['file_name']); ?>" class="btn btn-light">
                            <i class="fa fa-file-excel"></i> Download Result File</a>
                    </div>
                </div>
                <hr >
                <?php
//                wts($race_info);
//                wts($result_list);
                if ($result_list) {

                    $template = array(
                        'table_open' => '<table id="datatable" class="table table-striped table-bordered table-hover table-sm result_table">',
                    );

                    $this->table->set_template($template);
                    $this->table->set_heading('Pos', 'Name', 'Surname', 'Club', 'Age', 'Cat', 'Time');
                    foreach ($result_list as $result_id => $result) {
                        $url = base_url("result/claim/" . $result_id);
                        $row = [
                            "<a href='$url'>" . $result['result_pos'] . "</a>",
                            "<a href='$url'>" . $result['result_name'] . "</a>",
                            "<a href='$url'>" . $result['result_surname'] . "</a>",
                            "<a href='$url'>" . $result['result_club'] . "</a>",
                            "<a href='$url'>" . $result['result_age'] . "</a>",
                            "<a href='$url'>" . $result['result_cat'] . "</a>",
                            "<a href='$url'>" . $result['result_time'] . "</a>",
                        ];
                        $this->table->add_row($row);
                    }
                    echo $this->table->generate();
                    if ($load == "summary") {
                        ?>
                        <p class='m-t-10'><b>Can't find yourself?</b> Above is a summary view of entries matching your name or surname. click below to view full result set.</p>
                        <a class="btn btn-outline" href="<?=base_url("result/list/" . $race_info['race_id'] ."/full");?>"> 
                            <i class="fa fa-refresh"></i> Show full result list</a>
                        <?php
                    }
                } else {
                    ?>
                    <p>No results available for the selected race.</p>
                    <a class="btn btn-default" href="/user/my-results"> 
                        <i class="fa fa-arrow-circle-left"></i> Back</a>
                        <?php
                    }
                    ?>
            </div>
            <!-- end: Content-->

        </div>
    </div>
</section>
<!-- end: My Results -->


