<?php
    echo form_open($form_url);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Compose email: <?= ucfirst($action); ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        echo "<div class='form-group'>";
                        echo form_label('Subject <span class="compulsary">*</span>', 'emailque_subject');
                        echo form_input([
                            'name' => 'emailque_subject',
                            'id' => 'emailque_subject',
                            'value' => set_value('emailque_subject', utf8_encode(@$emailque_detail['emailque_subject'])),
                            'class' => 'form-control',
                            'required' => '',
                        ]);

                        echo "</div>";

                        echo "<div class='form-group'>";
                        echo form_label('To Address <span class="compulsary">*</span>', 'emailque_to_address');
                        echo form_input([
                            'name' => 'emailque_to_address',
                            'id' => 'emailque_to_address',
                            'type' => 'email',
                            'value' => set_value('emailque_to_address', utf8_encode(@$emailque_detail['emailque_to_address'])),
                            'class' => 'form-control',
                            'required' => '',
                        ]);
                        echo "</div>";

                        echo "<div class='form-group'>";
                        echo form_label('To Name', 'emailque_to_name');
                        echo form_input([
                            'name' => 'emailque_to_name',
                            'id' => 'emailque_to_name',
                            'value' => set_value('emailque_to_name', utf8_encode(@$emailque_detail['emailque_to_name'])),
                            'class' => 'form-control',
                        ]);
                        echo "</div>";
                        
                        
                        echo "<div class='form-group'>";
                        echo form_label('Bcc', 'emailque_bcc_address');
                        echo form_input([
                            'name' => 'emailque_bcc_address',
                            'id' => 'emailque_bcc_address',
                            'type' => 'email',
                            'value' => set_value('emailque_bcc_address', utf8_encode(@$emailque_detail['emailque_bcc_address'])),
                            'class' => 'form-control',
                        ]);
                        echo "</div>";
                        ?>
                    </div> <!-- col -->
                    <div class="col-md-8">
                        <?php
                        //  Body
                        echo "<div class='form-group'>";
                        echo form_label('Email Body <span class="compulsary">*</span>', 'emailque_body');
                        echo form_textarea([
                            'name' => 'emailque_body',
                            'id' => 'emailque_body',
                            'value' => utf8_encode(@$emailque_detail['emailque_body']),
                        ]);
                        echo "</div>";

                        //  BUTTONS
                        echo "<div class='btn-group' style='padding-bottom: 20px;'>";
                            $save_btn=["text"=>"Save","value"=>"save_only","status"=>"primary"];
                            echo fbuttonSave($save_btn);
                            $save_close_btn=["text"=>"Save & Close","value"=>"save_close","status"=>"success"];
                            echo fbuttonSave($save_close_btn);
                        echo "</div>";
                        echo "<div class='btn-group' style='padding-bottom: 20px; float: right'>";                        
                            $send_btn=["text"=>"Send Mail","value"=>"send_mail","status"=>"warning"];
                            echo fbuttonSave($send_btn);                            
                            echo fbuttonLink($return_url, "Discard", "danger");
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