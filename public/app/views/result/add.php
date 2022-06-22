<section id="page-content">
    <div class="container">
        <!-- Content-->
        <div class="content">
            <?php
            // CRUMBS WIDGET
            $this->load->view('widgets/crumbs');
            ?>

            <div class="row">
                <div class="col-md-12">
                    <h3 class=" text-uppercase">Manual Result Add <i class='fa fa-plus-circle' style='color: #26b8f3'></i></h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-uppercase m-b-0"><span class="badge badge-<?= $race_info['race_color']; ?>"><?= round($race_info['race_distance'], 0); ?>km</span></h3>
                    <h4 class="text-uppercase m-b-0"><?= $race_info['edition_name']; ?></h4>
                    <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($race_info['edition_date'], true); ?></h5>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p>Use the form below to add a manual result to the race for yourself.<br>
                        <b>PLEASE NOTE:</b> Should official results be loaded for this race in the future, your manual result will be overwritten.

                    <p>
                        <?php
                        if (validation_errors()) {
                            echo "<div class='alert alert-danger' role='alert'><strong><i class='fa fa-exclamation-circle'></i> Validation Error</strong>";
                            echo validation_errors();
                            echo "</div>";
                        }
                        ?>
                    <div class="m-t-30">
                        <?php
                        $attributes = array('class' => 'contact_form', 'role' => 'form');
                        echo form_open("result/add/" . $race_id, $attributes);
                        ?>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php
                                // show event name just for info purposes
                                echo form_label('Event Name', 'event_name');
                                $event_name = $race_info['edition_name'] . " (" . fraceDistance($race_info['race_distance']) . ")";
                                echo form_input([
                                    'id' => 'event_name',
                                    'value' => set_value('event_name', $event_name),
                                    'class' => 'form-control',
                                    'disabled' => '',
                                ]);
                                // race ID as a hidden field
                                echo form_hidden('race_id', $race_id);
                                ?>
                            </div>
                            <div class="form-group col-md-3">
                                <?php
                                // show date just for info purposes
                                echo form_label('Event Date *', 'event_date');
                                echo form_input([
                                    'id' => 'event_date',
                                    'value' => set_value('event_date', date("Y-m-d", strtotime($race_info['edition_date']))),
                                    'class' => 'form-control required',
                                    'type' => 'date',
                                    'disabled' => '',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Your Time *', 'result_time');
                                echo form_input([
                                    'name' => 'result_time',
                                    'id' => 'result_time',
                                    'type' => 'time',
                                    'step' => '1',
                                    'value' => set_value('result_time'),
                                    'class' => 'form-control required',
                                    'required' => '',
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Your Position', 'result_pos');
                                echo form_input([
                                    'name' => 'result_pos',
                                    'id' => 'result_pos',
                                    'value' => set_value('result_pos', 0),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'min' => 0
                                ]);
                                ?>
                                <small>Leave as 0 if not certain</small>
                            </div>

                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Category', 'result_cat');
                                echo form_dropdown('result_cat', $category_dropdown, set_value('result_cat', ''), [
                                    'id' => 'result_cat',
                                    'class' => 'form-control',
                                ]);
                                ?>
                                <small>If you know it</small>
                            </div>
                            <div class="form-group col-md-4">
                                <?php
                                echo form_label('Your Club', 'result_club');
                                echo form_dropdown('result_club', $club_dropdown, set_value('result_club', 'None'), [
                                    'id' => 'result_club',
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Race Number', 'result_racenum');
                                echo form_input([
                                    'name' => 'result_racenum',
                                    'id' => 'result_racenum',
                                    'value' => set_value('result_racenum'),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('ASA Number', 'result_asanum');
                                echo form_input([
                                    'name' => 'result_asanum',
                                    'id' => 'result_asanum',
                                    'value' => set_value('result_asanum'),
                                    'class' => 'form-control',
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <?php
                                echo form_label('Name *', 'result_name');
                                echo form_input([
                                    'name' => 'result_name',
                                    'id' => 'result_name',
                                    'value' => set_value('result_name', $logged_in_user['user_name']),
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter your Name',
                                    'required' => '',
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-md-4">
                                <?php
                                echo form_label('Surname *', 'result_surname');
                                echo form_input([
                                    'name' => 'result_surname',
                                    'id' => 'result_surname',
                                    'value' => set_value('user_surname', $logged_in_user['user_surname']),
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter your Surname',
                                    'required' => '',
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Gender *', 'result_sex');
                                echo form_dropdown('result_sex', ["" => "", "M" => "Male", "F" => "Female"], set_value('result_sex'), [
                                    'id' => 'result_sex',
                                    'class' => 'form-control',
                                    'required' => '',
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-md-2">
                                <?php
                                echo form_label('Your Age', 'result_age');
                                echo form_input([
                                    'name' => 'result_age',
                                    'id' => 'result_age',
                                    'value' => set_value('result_age'),
                                    'class' => 'form-control',
                                    'type' => 'number',
                                    'min' => 0
                                ]);
                                ?>
                                <small>At the time of the race</small>
                            </div>
                        </div>                      
                        <?php
                        $data = array(
                            'id' => 'form-submit',
                            'type' => 'submit',
                            'content' => '<i class="fa fa-plus-circle"></i>&nbsp;Add Result',
                            'class' => 'btn',
                        );
                        echo form_button($data);
                        $data = array(
                            'id' => 'form-clear',
                            'type' => 'reset',
                            'content' => '<i class="fa fa-eraser"></i>&nbsp;Clear',
                            'class' => 'btn btn-light',
                        );
                        echo form_button($data);
                        echo "<a href='".base_url('user/my-results')."' class='btn btn-secondary'><i class='fa fa-arrow-left'></i>&nbsp;Back</a>";
                        echo form_close();

                        // wts($race_info);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>