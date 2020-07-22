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
                    <h4 class="text-uppercase m-b-0"><span class="badge badge-primary" >Result Suggestions</span></h4>
                    <h4 class="text-uppercase m-b-0">For <?= $logged_in_user['user_name']; ?> <?= $logged_in_user['user_surname']; ?></h4>
                    <?php
                        if ($result_list) {
                    ?>
                    <h5 class="text-uppercase" style="color: #999;">Click on the line item to claim the result as your own</h5>
                    <?php
                        }
                    ?>
                    
                </div>
            </div>
            <hr >
            <?php
            if ($result_list) {

                $template = array(
                    'table_open' => '<table id="datatable" class="table table-striped table-bordered table-hover table-sm result_table">',
                );

                $this->table->set_template($template);
                $this->table->set_heading('Pos', 'Event', 'Date', 'Distance', 'Name', 'Age', 'Time');
                foreach ($result_list as $result_id => $result) {
                    $url = base_url("result/claim/" . $result_id);
                    $ref = "href='' data-href='' data-toggle='modal' data-target='#confirm-claim-" . $result_id . "'";
                    $row = [
                        "<a $ref>$result[result_pos]</a>",
                        "<a $ref>$result[edition_name]</a>",
                        "<a $ref>".fdateShort($result['edition_date'])."</a>",
                        "<a $ref>".fraceDistance($result['race_distance'])."</a>",
                        "<a $ref>$result[result_name] $result[result_surname]</a>",
                        "<a $ref>$result[result_age]</a>",
                        "<a $ref>$result[result_time]</a>",
                    ];
                    $this->table->add_row($row);
                }
                echo $this->table->generate();
                ?>
                <?php
            } else {
                ?>
                <p>No unclaimed auto suggestions has been found.</p>
                <?php
            }
            ?>
            <a class="btn btn-default" href="/user/my-results"><i class="fa fa-arrow-circle-left"></i> Back</a>
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


