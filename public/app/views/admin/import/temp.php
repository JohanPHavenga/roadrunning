<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Import Event Info for <span style="color: #cc0000"><?= $asamember_name; ?></span></span>
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <?php
                    if ($this->session->flashdata('errors')) {
                        ?>
                        <div class='note note-info' role='alert'>
                            <ul style="margin-bottom: 0;">
                                <?php
                                foreach ($this->session->flashdata('errors') as $temp_id => $error) {
                                    echo "<li>$error</li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <div class='btn-group'>
                        <?= fbuttonLink(base_url("admin/import/fill_temp/town"), "Import Towns", $town_id_btn); ?>
                        <?php
                        if ($town_id_btn == "default") {
                            echo fbuttonLink(base_url("admin/import/fill_temp/user"), "Import Users", $user_id_btn); 
                            echo fbuttonLink(base_url("admin/import/fill_temp/club"), "Import Clubs", $club_id_btn);
                        }                        
                        if (($user_id_btn == "default") && ($town_id_btn == "default") && ($club_id_btn == "default")) {
                            echo fbuttonLink(base_url("admin/import/fill_temp/gps"), "Get GPS", $gps_btn);                            
                        }
                        if ($club_id_btn == "default") {
                            echo fbuttonLink(base_url("admin/import/table/club_web"), "Import Clubs URLs", "default");
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <div class='btn-group' style="margin: 10px 0 15px;">
                        <?php
                        
                        if (($user_id_btn == "default") && ($town_id_btn == "default") && ($club_id_btn == "default") && ($gps_btn == "default")) {
                            echo fbuttonLink(base_url("admin/import/table/event"), "Add Events", $event_id_btn);
                            if ($event_id_btn == "default") {
                                echo fbuttonLink(base_url("admin/import/table/edition"), "Add Editions", $edition_id_btn);
                            }
                            if ($edition_id_btn == "default") {
                                echo fbuttonLink(base_url("admin/import/table/race"), "Add Races", "primary");
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12 col-sm-12'>
                    <?php
                    if (!(empty($import_data))) {
                        // create table
                        $this->table->set_template(ftable('event_import_table'));
                        $this->table->set_heading($columns);
                        foreach ($import_data as $row_num => $data_entry) {
                            $this->table->add_row($data_entry);
                        }
                        echo $this->table->generate();
                    } else {
                        echo "<p>No data to show</p>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
//wts($_SESSION['import']);
?>
