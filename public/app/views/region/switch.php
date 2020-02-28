<section id="page-content" class="sidebar-left">
    <div class="container">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                if (!empty($logged_in_user)) {
                    // PROFILE WIDGET
                    $this->load->view('widgets/profile');
                }
                // ADS WIDGET
//                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Please use form below to select the regions you would like to see races in.<p>

                    <?php
                    if (validation_errors()) {
                        echo "<div class='alert alert-danger m-b-20' role='alert'><h5><i class='fa fa-exclamation-circle'></i> OOPS. Something went wrong!</h5>";
                        echo validation_errors();
                        echo "</div>";
                    }
                    echo form_open($form_url);
                    ?>

                <div class="form-group">
                    <?php
                    echo form_label('Select your Region(s)', 'site_version');
                    echo form_multiselect('site_version[]', $region_dropdown, $this->session->region_selection, ["id" => "site_version", "class" => "form-control", "size" => 15]);
                    ?>
                </div>

                <p class="small">Ctrl+Click to select multiple<p>
                <div class="form-group">
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-compass"></i>&nbsp;Set',
                        'class' => 'btn',
                    );
                    echo form_button($data);
                    ?>
                </div>

                <?php
                echo form_close();
                ?>
            </div>
            <!-- end: Content-->

        </div>
    </div>
</section>
<!-- end: About -->
