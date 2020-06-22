<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">Search Race Result Sets</span>
        </div>
        <div class='btn-group pull-right'>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-5">
                <?= form_open($form_url, ["class" => "form-inline"]); ?>
                <div class='form-group'>
                    <?php
                    echo form_input([
                        'name' => 'race_summary',
                        'id' => 'auto_field',
                        'value' => set_value('race_summary', "", false),
                        'class' => 'form-control input-xlarge',
                        "placeholder" => "Start typing race name",
                        'required' => '',
                    ]);
                    ?>
                </div>
                <?php
                echo fbutton("Show", "submit", "success");
                echo form_close();
                if (isset($race_id)) {
                    ?>
                    <p style="margin-top:10px;">
                        <b>Edition:</b> <?= $race_info['edition_name']; ?> <b>Race:</b> <?= $race_info['race_name']; ?> 
                    </p>
                    <?php
                }
                ?>
            </div>
            <?php
            if (isset($race_id)) {
                ?>
                <div class="col-md-7">
                    <p style="margin-top:6px;">
                    There were <b><?= sizeof($result_data); ?></b> result records found for the 
                    <?= fdateYear($race_info['edition_date']); ?> edition of the <b><?= $race_info['event_name']; ?></b>
                    which ran on <b><?= fdateHumanFull($race_info['edition_date'], true); ?></b>.
                    </p>
                    <?php
//                    wts($_POST);
//                    echo $race_id;
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
if (isset($race_id)) {
    ?>

    <div class="portlet light">
        <div class="portlet-title">
            Results for race #<?= $race_id; ?><br>
            <div class="caption uppercase">
                <span class="caption-subject font-dark bold uppercase"><?= $race_info['edition_name']; ?></span>
            </div>
            <div class='btn-group pull-right'>
            </div>
        </div>
        <div class="portlet-body">

            <?php
            if (!(empty($result_data))) {
                // create table
                $this->table->set_template(ftable('result_search_table'));
                $this->table->set_heading(["Pos", "Name", "Surname", "Club", "Time", "Actions"]);
                foreach ($result_data as $id => $data_entry) {

                    $action_array = [
                        [
                            "url" => "/admin/result/create/edit/" . $data_entry['result_id'],
                            "text" => "Edit",
                            "icon" => "icon-pencil",
                        ],
                        [
                            "url" => "/admin/result/delete/" . $data_entry['result_id'],
                            "text" => "Delete",
                            "icon" => "icon-dislike",
                            "confirmation_text" => "<b>Are you sure?</b>",
                        ],
                    ];

                    $row['pos'] = $data_entry['result_pos'];
                    $row['name'] = $data_entry['result_name'];
                    $row['surname'] = $data_entry['result_surname'];
                    $row['club'] = $data_entry['result_club'];
                    $row['time'] = ftimeSort($data_entry['result_time'], true);
                    $row['actions'] = fbuttonActionGroup($action_array);
                    $this->table->add_row($row);

                    unset($row);
                }
                echo $this->table->generate();
            } else {
                echo "<p>No data to show</p>";
            }
            ?>
            <div class="btn-group">
                <?php
                // add button
//                echo fbuttonLink(base_url("admin/result/create/add/".$race_id), "Manually Add Result", "primary");
                echo fbuttonLink(base_url("admin/result/delete_result_set/" . $race_id), "Delete result set for race", "danger", null, "data-toggle='confirmation' data-original-title='<b>Are you sure?</b>'");
                ?>
            </div>

        </div>
    </div>
    <?php
}
?>



<script type='text/javascript'>
    // here you retrieve datas from PHP and use them as global from your external
    var auto_data = <?php echo json_encode($race_list); ?>;
</script>
