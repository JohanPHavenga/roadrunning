<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>Below find a list of all the regions. Click on them to view races in that region<p>
                <ul>
                    <?php
                    foreach ($region_list as $province_id => $province) {
                        echo "<li><a href='" . base_url("province/" . $province['province_slug']) . "'>" . $province['province_name'] . "</a> (".$province['province_count'].")";
                        echo "<ul>";
                        foreach ($province['region_list'] as $region) {
                            echo "<li><a href='" . base_url("region/" . $region['region_slug']) . "'>" . $region['region_name'] . "</a> (".$region['region_count'].")</li>";
                        }
                        echo "</ul>";
                        echo "</li>";
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



