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
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
            
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?> <span class="badge badge-danger" style="font-size: 0.6em; position: relative; top: -2px">Coming Soon</span></h3>
                <p>I am busy working on a module where all available results are imported into a big database. <br>
                    From there you can search and <b>claim a result</b> as your own, building up a nice history of your results.<p>
                <p>It is a work in progress. Hope to have it up by middle 2020.</p>
                <p>If you can to get notified of when this goes live, please consider <a href="<?= base_url('newsletter'); ?>">subscribing to my newsletter</a>.</p>
                <p>Watch this space.</p>
            </div>
            <!-- end: Content-->
            
        </div>
    </div>
</section>
<!-- end: My Results -->
