<?php
echo form_open_multipart($form_url); 

//adding from edition - diable fields
if (($action=="add")&&(@$file_detail['linked_id']>1)) { $disable_fields=true; } else { $disable_fields=false; }
?>
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
                 //  File type
                echo "<div class='form-group'>";
                    echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo form_label('File Type <span class="compulsary">*</span>', 'event_id');
                        echo form_dropdown('filetype_id', $filetype_dropdown, @$file_detail['filetype_id'], ["id"=>"edition_id","class"=>"form-control input-xlarge"]);        
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                
                //  Linked to
                echo "<div class='form-group'>";
                    echo "<div class='row'>";
                        echo "<div class='col-md-12 linked_to'>";
                        echo form_label('Linked to? <span class="compulsary">*</span>', 'file_linked_to');
                        $dropdown_data=[
                            "id"=>"file_linked_to",
                            "class"=>"form-control input-xlarge"
                        ];
                        if ($disable_fields) { $dropdown_data["readonly"]="readonly"; }
                        echo form_dropdown('file_linked_to', $linked_to_dropdown, @$file_detail['file_linked_to'], $dropdown_data);        
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                
                foreach ($linked_to_list as $linked_to_id=>$linked_to_name) {
                    $h_class="input-".$linked_to_name;  $h_id=0;
                    $linked_id_name=$linked_to_name."_id";
                    $class_name="hidden-input-".$linked_to_name;
                    $dropdown_name=$linked_to_name."_dropdown";
                    
                    if ((($action=="add")&&(@$file_detail['linked_id']<1))||(@$file_detail['file_linked_to']!=$linked_to_name)) { 
                        $h_class=$class_name; 
                    } else { 
                        $h_id=@$file_detail['linked_id'];
                    }
                    echo "<div class='form-group $h_class'>";
                        echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                            echo form_label(ucfirst($linked_to_name), $linked_id_name);
                            $dropdown_data=[
                                "id"=>$linked_id_name,
                                "class"=>"form-control input-xlarge"
                            ];
                            if ($disable_fields) { $dropdown_data["readonly"]="readonly"; }
                            echo form_dropdown($linked_id_name, $$dropdown_name, @$h_id, $dropdown_data);        
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
                
//                //  EDITION LINK
//                if (($action=="add")||($file_detail['file_linked_to']!="edition")) { 
//                    $ed_class="hidden-input-edition"; $ed_id=""; 
//                } else { 
//                    $ed_class=""; $ed_id=@$file_detail['linked_id'];
//                }
//                echo "<div class='form-group $ed_class'>"; 
//                    echo "<div class='row'>";
//                        echo "<div class='col-md-12'>";
//                        echo form_label('Edition', 'edition_id');
//                        echo form_dropdown('edition_id', $edition_dropdown, $ed_id, ["id"=>"edition_id","class"=>"form-control input-xlarge"]);        
//                        echo "</div>";
//                    echo "</div>";
//                echo "</div>";
//                
//                 //  RACE LINK
//                if ((($action=="add")&&(@$file_detail['linked_id']<1))||($file_detail['file_linked_to']!="race")) { 
//                    $rc_class="hidden-input-race"; $rc_id=""; 
//                } else { 
//                    $rc_class=""; $rc_id=@$file_detail['linked_id'];
//                }
//                echo "<div class='form-group $rc_class'>";
//                    echo "<div class='row'>";
//                        echo "<div class='col-md-12'>";
//                        echo form_label('Race', 'race_id');
//                        echo form_dropdown('race_id', $race_dropdown, @$rc_id, ["id"=>"race_id","class"=>"form-control input-xlarge"]);        
//                        echo "</div>";
//                    echo "</div>";
//                echo "</div>";
                
                // FILE
                echo "<div class='form-group'>";
                    echo "<div class='row'>";
                    
                        echo "<div class='col-md-12'>";
                            echo form_label('File <span class="compulsary">*</span>', 'file_upload');
                            if ($action=="edit") {    
                                $file_url = base_url("uploads/" . $file_detail['file_linked_to'] . "/" . $file_detail['linked_id'] . "/" . $file_detail['file_name']);                            
                                echo "<div><a href='$file_url'>".$file_detail['file_name']."</a></div>";
                            } else {
                                echo form_input([
                                    'name'          => 'file_upload',
                                    'id'            => 'file_upload',
                                    'type'          => 'file',
                                    'multiple'      => '',
//                                    'accept'        => '.pdf',
                                ]);
                            }
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                
                echo "<div class='form-group'>";
                    echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                
                //  BUTTONS
                echo "<div class='btn-group'>";
//                echo fbutton($text="Save",$type="submit",$status="primary",NULL,"save_only");
                echo fbutton($text="Save & Close",$type="submit",$status="success");
                echo fbuttonLink($return_url,"Cancel",$status="danger");
                echo "</div>";

            ?>
            </div>
        </div>
    </div>
    
    <?php
    if ($action=="edit") {
    ?>
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">More information</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                 //  DATES Created + Updated
                echo "<div class='form-group'>";
                echo form_label('Date Created', 'created_date');
                echo form_input([
                        'value'         => set_value('created_date', @$file_detail['created_date']),
                        'class'         => 'form-control input-medium',
                        'disabled'      => ''
                    ]);

                echo "</div>";
                echo "<div class='form-group'>";
                echo form_label('Date Updated', 'updated_date');
                echo form_input([
                        'value'         => set_value('updated_date', @$file_detail['updated_date']),
                        'class'         => 'form-control input-medium',
                        'disabled'      => ''
                    ]);

                echo "</div>";
                ?>
            </div>
        </div>        
    </div>
    <?php
    }
    ?>
</div>
<?php
echo form_close();

//wts(@$file_detail);
?>