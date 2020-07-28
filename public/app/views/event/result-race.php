<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2><?= $race_data['text']; ?></h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <div class="content col-lg-12">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-uppercase m-b-0"><span class="badge badge-<?= $race_info['race_color']; ?>"><?= round($race_info['race_distance'], 1); ?>km</span></h3>
                        <h4 class="text-uppercase m-b-0"><?= $race_info['edition_name']; ?></h4>
                        <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($race_info['edition_date'], true); ?></h5>
                    </div>
                    <div class="col-md-4 text-right">                       
                        <a href="<?= $race_data['url']; ?>" class="btn btn-light">
                            <i class="fa fa-file-excel"></i> Download Result File</a>
                    </div>
                </div>
                <hr >
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (isset($result_list[$race_id])) {

                            $template = array(
                                'table_open' => '<table class="datatable table table-striped table-bordered table-hover table-sm result_table">',
                            );

                            $this->table->set_template($template);
                            $this->table->set_heading('Pos', 'Name', 'Surname', 'Club', 'Age', 'Cat', 'Time');
                            foreach ($result_list[$race_id] as $result_id => $result) {
                                if ($logged_in_user) {
                                    $url = base_url("result/claim/" . $result_id);
                                    $ref = "href='' data-href='' data-toggle='modal' data-target='#confirm-claim-" . $result_id . "'";
                                } else {
                                    $ref = "href='' data-href='' data-toggle='modal' data-target='#login-user-" . $race_id . "'";
                                }

                                $row = [
                                    "<a $ref>$result[result_pos]</a>",
                                    "<a $ref>$result[result_name]</a>",
                                    "<a $ref>$result[result_surname]</a>",
                                    "<a $ref>$result[result_club]</a>",
                                    "<a $ref>$result[result_age]</a>",
                                    "<a $ref>$result[result_cat]</a>",
                                    "<a $ref>$result[result_time]</a>",
                                ];
                                $this->table->add_row($row);
                            }
                            echo $this->table->generate();
                        } else {
                            ?>
                            <p>No listed results available for the selected race. Please download the results file by clicking on the button above.</p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="<?= base_url("event/" . $slug . "/results"); ?>" class="btn">Back</a>
                    </div>
                </div>
            </div>
            <?php
            $this->load->view('widgets/timingprovider');
            ?>
            <!-- end: Content-->
        </div>
    </div>
</section>
<!-- end: Shop products -->

<?php
if ($edition_data['edition_info_status'] == 11 && $result_list) {
// for every race in the edition

    if ($logged_in_user) {
        foreach ($result_list[$race_id] as $result_id => $result_detail) {
            $claim_url = base_url("result/claim/" . $result_id);
            ?>
            <!-- OPEN claim confirmation modal <?= $result_id; ?> -->
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
            <!-- CLOSE claim confirmation modal <?= $result_id; ?> -->
            <?php
        }
    } else {
        ?>
        <div class="modal fade" id="login-user-<?= $race_id; ?>" tabindex="-1" role="dialog" aria-labelledby="login-user-label-<?= $race_id; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <div class="modal-header">
                        <h4 class="text-uppercase">Log In</h4>
                    </div>
                    <div class="modal-body">
                        <p>You need to log in in order to claim a result</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-xs" data-dismiss="modal">Nevermind</button>
                        <a class="btn btn-success btn-ok btn-xs" href="<?= base_url("login"); ?>">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>


