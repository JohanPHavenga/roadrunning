
<div class="portlet light" id="more_info">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">More Information</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "more_info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <div class="form-group">
            <div class="row">
                <div class='col-md-12'>
                    <div class='mt-checkbox-inline'>
                        <?php
                        $checkbox_array = [
                            [
                                "name" => "edition_info_medals",
                                "text" => "Medals",
                            ],
                            [
                                "name" => "edition_info_luckydraw",
                                "text" => "Lucky Draws",
                            ],
                            [
                                "name" => "edition_info_togbag",
                                "text" => "Tog bag",
                            ],
                            [
                                "name" => "edition_info_headphones",
                                "text" => "Headphones",
                            ],
                            [
                                "name" => "edition_info_refreshments",
                                "text" => "Refreshments",
                            ],
                            [
                                "name" => "edition_info_socialwalkers",
                                "text" => "Walkers",
                            ],
                        ];
                        foreach ($checkbox_array as $checkbox) {
                            $data = array(
                                'name' => $checkbox['name'],
                                'id' => $checkbox['name'],
                                'value' => '1',
                                'checked' => set_value($checkbox['name'], $edition_detail[$checkbox['name']]),
                            );
                            echo form_hidden($checkbox['name'], 0);
                            echo '<label class="mt-checkbox">' . form_checkbox($data) . $checkbox['text'] . "<span></span></label>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class='col-md-3'>
                    <?php
                    $field = "edition_info_prizegizing";
                    $display = "Prize-Giving";
                    $error_value = "00:00:00";
                    echo form_label($display, $field);
                    $form_input_array = [
                        'name' => $field,
                        'id' => $field,
                        'class' => 'form-control timepicker timepicker-24 input-xsmall',
                        'value' => set_value($field, ftimeSort($edition_detail[$field])),
                    ];
                    if ($edition_detail[$field] == $error_value) {
                        $form_input_array['class'] = $form_input_array['class'] . " danger";
                    }
                    echo '<div class="input-group input-xsmall">';
                    echo form_input($form_input_array);
                    echo '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-clock-o"></i></button></div>';
                    ?>
                </div>
                <div class='col-sm-9'>
                    <?php
                    echo form_label('Medals text', 'edition_info_medals_text');
                    echo form_input([
                        'name' => 'edition_info_medals_text',
                        'id' => 'edition_info_medals_text',
                        'value' => set_value('edition_info_medals_text', $edition_detail['edition_info_medals_text'], false),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class='col-sm-12'>
                <?php
                echo form_label('General Information', 'edition_general_detail');
                echo form_textarea([
                    'name' => 'edition_general_detail',
                    'id' => 'edition_description',
                    'value' => set_value('edition_general_detail', $edition_detail['edition_general_detail'], false),
                ]);
                ?>
            </div>
        </div>
    </div>
</div> 