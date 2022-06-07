<div class="portlet light">
    <div class="portlet-body">  
        <div class='form-group'>
            <div class='row'>
                <div class='col-sm-6 col-md-12 col-lg-12'>
                    <?php
                    echo form_label('Date Created', 'created_date');
                    echo form_input([
                        'value' => set_value('created_date', $edition_detail['edition_created_date']),
                        'class' => 'form-control',
                        'disabled' => ''
                    ]);
                    ?>
                </div>
                <div class='col-sm-6 col-md-12 col-lg-12'>
                    <?php
                    echo form_label('Date Updated', 'updated_date');
                    echo form_input([
                        'value' => set_value('updated_date', $edition_detail['edition_updated_date']),
                        'class' => 'form-control',
                        'disabled' => ''
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>