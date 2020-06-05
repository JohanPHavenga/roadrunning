<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">Claim Result</span>
        </div>
        <div class='btn-group pull-right'>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-4">
                <?= form_open($form_url, ["class" => "form-inline"]); ?>
                <div class='form-group'>
                    <?php
                    echo form_input([
                        'name' => 'user',
                        'id' => 'auto_field',
                        'value' => set_value('race_summary', "", false),
                        'class' => 'form-control input-xlarge',
                        "placeholder" => "Start typing user name",
                        'required' => '',
                    ]);
                    ?>
                </div>
                <?php
                echo form_hidden([
                    'result_id' => $result_id,
                ]);
                echo fbutton("Claim", "submit", "success");
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    // here you retrieve datas from PHP and use them as global from your external
    var auto_data = <?php echo json_encode($user_list); ?>;
</script>
