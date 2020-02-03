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
                echo validation_errors(); 

                echo form_open($form_url); 

                echo "<div class='form-group'>";
                echo form_label('ASA Number', 'asanumber_num');
                echo form_input([
                        'name'          => 'asanumber_num',
                        'id'            => 'asanumber_num',
                        'value'         => set_value('user_name', @$asanumber_detail['asanumber_num']),
                        'class'         => 'form-control',
                        'required'      => '',
                    ]);

                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('ASA Number Year', 'asanumber_year');
                echo "<div class='input-group date'>";
                echo form_input([
                        'name'          => 'asanumber_year',
                        'value'         => set_value('asanumber_year', @$asanumber_detail['asanumber_year'],false),
                        'class'         => 'form-control asanumber_year',
                    ]);    
                echo '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
                echo "</div>";

                echo "<div class='form-group'>";
                echo form_label('User', 'user_id');
                echo form_dropdown('user_id', $user_dropdown, @$asanumber_detail['user_id'], ["id"=>"user_id","class"=>"form-control"]);        
                echo "</div>";
                
                echo "<div class='btn-group'>";
                echo fbutton();
                echo fbuttonLink($return_url,"Cancel");
                echo "</div>";

                echo form_close();
            ?>
            </div>
        </div>
    </div>
</div>