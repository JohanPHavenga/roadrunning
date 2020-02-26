<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Welcome <?= $logged_in_user['user_name'] . " " . $logged_in_user['user_surname']; ?>.<p>
                <p>This will be your profile page on <b>roadrunning.co.za</B> where you will be able to claim results and see graphs on your race times over time.</p>
                <p>It is a work in progress to please bare with me. Keep on watching this space.</p>

                <hr class="m-t-30 m-b-30">
                
                <h4 class="text-uppercase">Your details</h4>
                <?php
                $template = array(
                    'table_open' => '<table class="table table-striped table-bordered">'
                );

                $this->table->set_template($template);
                $this->table->set_heading('Field', 'Value');

                $this->table->add_row('Name', $logged_in_user['user_name']);
                $this->table->add_row('Surname', $logged_in_user['user_surname']);
                $this->table->add_row('Email', $logged_in_user['user_email']);
                if ($logged_in_user['user_contact']) {
                    $this->table->add_row('Contact', $logged_in_user['user_contact']);
                }
                if ($logged_in_user['user_gender']) {
                    $this->table->add_row('Gender', $logged_in_user['user_gender']);
                }
                echo $this->table->generate();
                
                ?>
                <a class="btn btn-default" href="<?=base_url("user/edit");?>"><i class="fa fa-edit"></i> Edit Details</a>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // PROFILE WIDGET
                $this->load->view('widgets/profile');
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: PROFILE -->


