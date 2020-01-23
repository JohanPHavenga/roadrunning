<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Below find a list of provinces that have races list in them. Click on them to view races in that province<p>
                <ul>
                    <?php
                    foreach ($this->session->province_pages as $province) {
                        echo "<li><a href='" . $province['loc'] . "'>" . $province['display'] . "</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Region List -->

