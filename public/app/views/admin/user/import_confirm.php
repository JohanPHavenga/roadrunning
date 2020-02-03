<div class="row">
    <div class="col-md-12">
        <div class="portlet light">

            <div class="m-heading-1 border-red m-bordered">
                <h3>Cofirm import data</h3>
                <p> Cofirm that the data below is correct before confirming the import. </p>
            </div>

            <?php
                foreach ($sum_data as $action=>$entry_list) {
                    ?>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-edit font-dark"></i>
                            <span class="caption-subject font-dark bold uppercase"><?=$action;?></span>
                        </div>
                    </div>
                    <?php
                    $this->table->set_template(ftable());
                    foreach ($entry_list as $id=>$entry) {
                        $data[]="<b>$id</b>";
                        foreach ($entry as $field_name=>$value) {
                            $data[]=$field_name."=[<b>".$value."</b>]";
                        }

                        $this->table->add_row($data);
                        unset($data);
                    }
                    echo $this->table->generate();
                }
                // wts($sum_data);
            ?>

            <div class='btn-group'>
                <?= fbuttonLink('../import_done','Confirm Import','primary'); ?>
            </div>
        </div>
    </div>
</div>
