<?= form_open($form_url); ?>
<div class="row">
    <div class="col-lg-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption uppercase" style="font-size: 0.9em">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold "><?= ucfirst($action); ?> Result</span>
                    : <?= $race_detail['edition_name'] . " " . $race_detail['race_name']; ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class='col-md-2 col-sm-2'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Position <span class="compulsary">*</span>', 'result_pos');
                            echo form_input([
                                'name' => 'result_pos',
                                'id' => 'result_pos',
                                'value' => set_value('result_pos', $result_detail['result_pos']),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class='col-md-5 col-sm-5'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Surname <span class="compulsary">*</span>', 'result_surname');
                            echo form_input([
                                'name' => 'result_surname',
                                'id' => 'result_surname',
                                'value' => set_value('result_surname', $result_detail['result_surname'], false),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class='col-md-4 col-sm-4'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Name', 'result_name');
                            echo form_input([
                                'name' => 'result_name',
                                'id' => 'result_name',
                                'value' => set_value('result_name', $result_detail['result_name'], false),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-6 col-sm-6'>
                        <div class='form-group'>
                            <?php
                            echo form_label('Club', 'result_club');
                            echo form_input([
                                'name' => 'result_club',
                                'id' => 'result_club',
                                'value' => set_value('result_club', $result_detail['result_club'], false),
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group'>
                        <div class='col-md-2 col-sm-2'>
                            <?php
                            echo form_label('Time <span class="compulsary">*</span>', 'result_time');
                            ?>
                            <div class="input-group input-xsmall">
                                <?php
                                echo form_input([
                                    'name' => 'result_time',
                                    'id' => 'result_time',
                                    'value' => set_value('result_time', $result_detail['result_time'], false),
                                    'class' => 'form-control timepicker timepicker-24-seconds input-xsmall',
                                    'required' => '',
                                ]);
                                ?>
                                <span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2'>
                            <div class='form-group'>
                                <?php
                                echo form_label('Age', 'result_age');
                                echo form_input([
                                    'name' => 'result_age',
                                    'id' => 'result_age',
                                    'value' => set_value('result_age', $result_detail['result_age']),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2'>
                            <div class='form-group'>
                                <?php
                                echo form_label('Sex', 'result_sex');
                                echo form_dropdown(
                                        'result_sex',
                                        ["N" => "None", "M" => "M", "F" => "F"],
                                        set_value('result_sex', $result_detail['result_sex']),
                                        ["id" => "result_sex", "class" => "form-control"]
                                );
                                ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2'>
                            <div class='form-group'>
                                <?php
                                echo form_label('Cat', 'result_cat');
                                echo form_input([
                                    'name' => 'result_cat',
                                    'id' => 'result_cat',
                                    'value' => set_value('result_cat', $result_detail['result_cat']),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2'>
                            <div class='form-group'>
                                <?php
                                echo form_label('ASA Num', 'result_asanum');
                                echo form_input([
                                    'name' => 'result_asanum',
                                    'id' => 'result_asanum',
                                    'value' => set_value('result_asanum', $result_detail['result_asanum']),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2'>
                            <div class='form-group'>
                                <?php
                                echo form_label('Race Num', 'result_racenum');
                                echo form_input([
                                    'name' => 'result_racenum',
                                    'id' => 'result_racenum',
                                    'value' => set_value('result_racenum', $result_detail['result_racenum']),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="portlet-footer">
                <div class='row' style="margin-top: 10px;">
                    <div class='col-md-12 col-sm-12'>
                        <div class='btn-group'>
                            <?php
                            echo fbutton($text = "Apply", $type = "submit", $status = "primary", NULL, "save_only");
                            echo fbutton($text = "Save", $type = "submit", $status = "success");
                            ?>
                        </div>
                        <div class='btn-group pull-right'>
                            <?php
                            echo fbuttonLink($return_url, "Cancel", $status = "warning");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption uppercase" style="font-size: 0.9em">
                    <span class="caption-subject font-dark bold ">Claimed Results</span> for 
                    Result #<?= $result_id; ?><br>
                </div>
                <div class='btn-group pull-right'>
                    <?= fbuttonLink("/admin/userresult/create/add/" . $result_id, "Add Claim", "info"); ?>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (!(empty($user_result_list))) {
                    // create table
                    $this->table->set_template(ftable('user_result_table'));
                    $this->table->set_heading(["ID", "Name", "Surname", "Actions"]);
                    foreach ($user_result_list as $user_result) {

                        $action_array = [
                            [
                                "url" => "/admin/userresult/delete/". $user_result['user_id'] ."/".$result_id,
                                "text" => "Delete",
                                "icon" => "icon-dislike",
                                "confirmation_text" => "<b>Are you sure?</b>",
                            ],
                        ];


                        $row['user_id'] = $user_result['user_id'];
                        $row['user_name'] = $user_result['user_name'];
                        $row['user_surname'] = $user_result['user_surname'];
                        $row['actions'] = fbuttonActionGroup($action_array);

                        $this->table->add_row($row);
                        unset($row);
                    }
                    echo $this->table->generate();
                } else {
                    echo "<p>No claims on this result yet</p>";
                }
//                wts($user_result_list);
                ?>
            </div>
        </div>
    </div>

</div>


<?php
echo form_hidden([
    'race_id' => $race_id,
]);
echo form_close();
?>