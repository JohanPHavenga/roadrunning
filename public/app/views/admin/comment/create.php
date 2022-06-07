<?= form_open($form_url); ?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= ucfirst($action); ?> entry</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <div class="row">
                        <div class='col-lg-12'>
                            <?php
                            echo form_label('Comment', 'comment_data');
                            echo form_textarea([
                                'name' => 'comment_data',
                                'id' => 'comment_data',
                                'rows' => '4',
                                'style' => 'width: 100%',
                                'value' => set_value('comment_data', @$comment_detail['comment_data'], false),
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                //  BUTTONS
                echo "<div class='btn-group'>";
                echo fbutton($text = "Save", $type = "submit", $status = "primary", NULL, "save_only");
                echo fbuttonLink($return_url, "Cancel", $status = "danger");
                echo "</div>";
                ?>
            </div>
        </div>
    </div>

    <?php
    if ($action == "edit") {
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
                        'value'         => set_value('created_date', @$comment_detail['created_date']),
                        'class'         => 'form-control input-medium',
                        'disabled'      => ''
                    ]);

                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value'         => set_value('updated_date', @$comment_detail['updated_date']),
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
<?= form_close(); ?>