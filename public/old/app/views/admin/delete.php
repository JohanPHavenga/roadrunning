<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Delete Data</span>
                </div>
            </div>
            <div class="portlet-body">
            <?php
                echo "Are you sure you want to delete this record?";

                echo validation_errors(); 

                echo form_open("admin/$controller/delete/confirm"); 
                echo form_hidden($id_field, $id);
                echo "<br>";
                echo fbutton("Confirm","submit","danger");
                echo "&nbsp;";
                echo fbuttonLink("/admin/".$controller."/view","Cancel");

                echo form_close();
            ?>
            </div>
        </div>
    </div>
</div>