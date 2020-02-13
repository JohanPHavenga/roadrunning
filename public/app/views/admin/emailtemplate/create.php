<?php
    echo form_open($form_url);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-name font-dark bold uppercase"><?= ucfirst($action); ?> email template</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        echo "<div class='form-group'>";
                        echo form_label('Subject <span class="compulsary">*</span>', 'emailtemplate_name');
                        echo form_input([
                            'name' => 'emailtemplate_name',
                            'id' => 'emailtemplate_name',
                            'value' => set_value('emailtemplate_name', utf8_encode(@$emailtemplate_detail['emailtemplate_name'])),
                            'class' => 'form-control',
                            'required' => '',
                        ]);

                        echo "</div>";

                         //  Linked to
                        echo "<div class='form-group'>";
                            echo "<div class='row'>";
                                echo "<div class='col-md-12 linked_to'>";
                                echo form_label('Linked to? <span class="compulsary">*</span>', 'emailtemplate_linked_to');
                                $dropdown_data=[
                                    "id"=>"emailtemplate_linked_to",
                                    "class"=>"form-control input-large"
                                ];
                                echo form_dropdown('emailtemplate_linked_to', $linked_to_dropdown, @$emailtemplate_detail['emailtemplate_linked_to'], $dropdown_data);        
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
                        echo form_label('Email Body <span class="compulsary">*</span>', 'emailtemplate_body');
                        echo form_textarea([
                            'name' => 'emailtemplate_body',
                            'id' => 'emailtemplate_body',
                            'value' => utf8_encode(@$emailtemplate_detail['emailtemplate_body']),
                        ]);
                        echo "</div>";

                        //  BUTTONS
                        echo "<div class='btn-group' style='padding-bottom: 20px;'>";
                            $save_btn=["text"=>"Save","value"=>"save_only","status"=>"primary"];
                            echo fbuttonSave($save_btn);
                            $save_close_btn=["text"=>"Save & Close","value"=>"save_close","status"=>"success"];
                            echo fbuttonSave($save_close_btn);                      
                            echo fbuttonLink($return_url, "Cancel", "danger");
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