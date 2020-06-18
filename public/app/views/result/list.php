<?php
//    wts($crumbs_arr,1);
?>
<section id="page-content">
    <div class="container">
        <!-- Content-->
        <div class="content">
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
                        "<a href='' data-href='' data-toggle='modal' data-target='#confirm-claim-" . $result_id . "'>" . $result['result_pos'] . "</a>",
//                        "<a href='$url'>$result[result_pos]</a>",
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
                    <a class="btn btn-outline" href="<?= base_url("result/list/" . $race_info['race_id'] . "/full"); ?>"> 
                        <i class="fa fa-refresh"></i> Show full result list</a>
                    <?php
                }
            } else {
                ?>
                <p>No results available for the selected race.</p>
                <a class="btn btn-default" href="/user/my-results"><i class="fa fa-arrow-circle-left"></i> Back</a>
                <?php
            }
            ?>
        </div>
        <!-- end: Content-->
        <?php
//        wts($result_list);
        ?>
    </div>
</section>
<!-- end: My Results -->

<?php
// confirmation_modals
foreach ($result_list as $result_id => $result_detail) {
    $claim_url = base_url("result/claim/" . $result_id);
    ?>
    <!-- logout confirmation modal -->
    <div class="modal fade" id="confirm-claim-<?= $result_id; ?>" tabindex="-1" role="dialog" aria-labelledby="confirm-claim-label-<?= $result_id; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">  
                <div class="modal-header">
                    <h4 class="text-uppercase">Review Result</h4>
                </div> 
                <div class="modal-body">
                    <p>Please review the result details below:</p>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-xs" data-dismiss="modal">Not Me</button>
                    <a class="btn btn-success btn-ok btn-xs" href="<?= $claim_url; ?>">Claim Result</a>
                </div>
            </div>
        </div>
    </div>
    <!-- logout confirmation modal -->
    <?php
}


