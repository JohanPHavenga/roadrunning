<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase">UNSUBSCRIBE
                    <span class="badge badge-success" style="font-size: 0.6em; position: relative; top: -2px">SUCCESS</span></h3>
                <p>
                    You have been unsubscribed from the <b><?=$edition_info['edition_name'];?></b> mailing list.
                </p>
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
<!-- end: My Results -->
