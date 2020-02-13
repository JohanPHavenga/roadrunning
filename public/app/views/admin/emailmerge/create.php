<?php
echo form_open($form_url);
//wts($emailmerge_detail);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-name font-dark bold uppercase"><?= $title; ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        echo "<div class='form-group'>";
                        echo form_label('Subject <span class="compulsary">*</span>', 'emailmerge_subject');
                        echo form_input([
                            'name' => 'emailmerge_subject',
                            'id' => 'emailmerge_subject',
//                            'value' => set_value('emailmerge_subject', utf8_encode(@$emailmerge_detail['emailmerge_subject'])),
                            'value' => set_value('emailmerge_subject',$emailmerge_detail['emailmerge_subject'], FALSE),
                            'class' => 'form-control input-xlarge',
                            'required' => '',
                        ]);

                        echo "</div>";

                        // Recipients
                        echo "<div class='form-group'>";
                        echo "<div class='row'>";
                        echo "<div class='col-md-12 linked_to'>";
                        echo form_label('Recipients <span class="compulsary">*</span>', 'emailmerge_recipients');
                        $dropdown_data = [
                            "id" => "emailmerge_recipients",
                            "class" => "form-control input-xlarge",
                            "size" => 15,
                        ];
                        $select_arr = explode(",", $emailmerge_detail['emailmerge_recipients']);
                        echo form_multiselect('emailmerge_recipients[]', $user_dropdown, $select_arr, $dropdown_data);
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo form_label('Variable Options');
                        echo "<ul>";
                            echo "<li>%name% - Adds user name</li>";
                            echo "<li>%surname% - Adds user surname</li>";
                            echo "<li>%email% - Adds user email address</li>";
                            echo "<li>%event_name% - Adds name of event</li>";
                            echo "<li>%event_url% - URL to event page</li>";
                            echo "<li>%event_date% - Date of the event</li>";
                            echo "<li>%entries_close% - URL to event page</li>";
                            echo "<li>%town_name% - Town where event takes place</li>";
                            echo "<li>%events_past% - Adds table of events of past month</li>";
                            echo "<li>%events_future% - Adds table of events for next 2 months</li>";
                            echo "<li>%unsubscribe_url% - Unsubscribe link</li>";  
                        echo "</ul>";
                        echo "</div>";
                        echo "</div>";
                        ?>
                    </div> <!-- col -->
                    <div class="col-md-8">
                        <?php
                        //  Body
                        echo "<div class='form-group'>";
                        echo form_label('Email Body <span class="compulsary">*</span>', 'emailmerge_body');
                        echo form_textarea([
                            'name' => 'emailmerge_body',
                            'id' => 'emailmerge_body',
//                            'value' => utf8_encode(@$emailmerge_detail['emailmerge_body']),
                            'value' => set_value('emailmerge_body',$emailmerge_detail['emailmerge_body'], FALSE),
                        ]);
                        echo "</div>";

                        //  BUTTONS
                        if ($emailmerge_detail['emailmerge_status'] == 4) {
                            echo "<div class='btn-group' style='padding-bottom: 20px;'>";
                            $save_btn = ["text" => "Save", "value" => "save_only", "status" => "primary"];
                            echo fbuttonSave($save_btn);
                            $save_close_btn = ["text" => "Save & Close", "value" => "save_close", "status" => "success"];
                            echo fbuttonSave($save_close_btn);
                            $test_btn = ["text" => "Test Merge", "data-toggle" => "modal", "data-target" => "#mergeModal", "status" => "default", "type" => "button"];
                            echo fbuttonSave($test_btn);
                            echo "</div>";
                        }
                        echo "<div class='btn-group' style='padding-bottom: 20px; float: right'>";
                        $test_email_btn = ["text" => "Test email", "value" => "test_merge", "status" => "default"];
                        echo fbuttonSave($test_email_btn);
                        $test_html_btn = ["text" => "Test html", "value" => "test_html", "status" => "default"];
                        echo fbuttonSave($test_html_btn);
                        if ($emailmerge_detail['emailmerge_status'] == 4) {
                            $merge_btn = ["text" => "Execute Merge", "value" => "merge", "status" => "warning"];
                            echo fbuttonSave($merge_btn);
                        }
                        echo fbuttonLink($return_url, "Close", "danger");
                        echo "</div>";
                        ?>
                    </div> <!-- col -->
                </div> <!-- row -->
            </div> <!-- body -->
        </div> <!-- portlet -->
    </div> <!-- col -->
</div> <!-- row -->

<?php
echo form_close();
?>

<div class="modal fade bd-example-modal-lg" id="mergeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <?= $test_merge_body; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>