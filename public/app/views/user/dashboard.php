<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <?php
                // CRUMBS WIDGET
                $this->load->view('widgets/crumbs');
                ?>
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Welcome <?= $logged_in_user['user_name'] . " " . $logged_in_user['user_surname']; ?>.<p>
                <p></p>
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


