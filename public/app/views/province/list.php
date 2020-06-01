<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Below find a list of provinces that have races list in them. Click on them to view races in that province<p>
                <ul>
                    <?php
                    foreach ($province_list as $province) {
                        echo "<li><a href='" . $province['loc'] . "'>" . $province['display'] . "</a> (".$province['edition_count'].")</li>";
                    }
                    ?>
                </ul>
                <a class="btn btn-default m-t-20" href="<?=base_url("region/list");?>">View region breakdown</a>
                
                <?php
//                wts($province_list);
                ?>
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

