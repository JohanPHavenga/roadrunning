<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action);?> entry</span>
                </div>
            </div>
            <div class="portlet-body">
            <?php 
                echo form_open($form_url); 

                echo "<div class='form-group'>";
                echo form_label('Name', 'sponsor_name');
                echo form_input([
                        'name'          => 'sponsor_name',
                        'id'            => 'sponsor_name',
                        'value'         => set_value('sponsor_name', @$sponsor_detail['sponsor_name']),
                        'class'         => 'form-control',
                    ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('Status', 'sponsor_status');
                echo form_dropdown('sponsor_status', $status_dropdown, @$sponsor_detail['sponsor_status'], ["id"=>"sponsor_status","class"=>"form-control"]);        
                echo "</div>";    

                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";

                echo form_close();

            //    wts($town_dropdown);

                //<input type="submit" name="submit" value="Edit Event">
            ?>
            </div>
        </div>
    </div>
</div>