<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Select a year below<p>

                <div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20">
                    <?php
                    for ($year = date("Y"); $year >= 2016; $year--) {
                        ?>
                        <div class="portfolio-item overlay-links light-bg img-zoom">
                            <div class="portfolio-item-wrap">
                                <div class="portfolio-image">
                                    <a href="<?= base_url("race/history/" . $year); ?>"><img src="<?= base_url('assets/img/year/' . $year . '.jpg'); ?>" alt=""></a>
                                    <div class="portfolio-links">
                                        <a href="<?= base_url("race/history/" . $year); ?>" class="btn btn-xxs btn-outline btn-light">View</a>
                                    </div>
                                </div>
                                <div class="portfolio-description">
                                    <a href="<?= base_url("race/history/" . $year); ?>">
                                        <h3><?= $year ?></h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
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
