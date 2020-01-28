<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Welcome <?=$logged_in_user['user_name']." ".$logged_in_user['user_surname'];?>.<p>
                <p>This will be your profile page on <b>roadrunning.co.za</B> where you will be able to claim results and see graphs on your race times over time.</p>
                <p>It is a work in progress to please bare with me. Keep on watching this space.</p>
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


