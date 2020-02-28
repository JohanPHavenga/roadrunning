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
                <h3 class="text-uppercase">Show your Support</h3>
                <div style="float: right; margin: 0 0 10px 20px; border: 1px solid #ccc;">
                    <a href="https://pos.snapscan.io/qr/LAzMFdGZ">
                        <img src="<?= base_url("assets/img/SnapScan_CodeOnly.png"); ?>"/>
                    </a>
                </div>
                <p><b>THANK YOU</b> for considering supporting my site. At the moment this is a hobby more than a job. I do what I can in my spare time to keep the site updated.</p>
                <p>The dream is to one day do this as a full time job. That will allow me to load even more accurate information and unique content.<br>
                    Until then it is late nights, lunch time, and in between meetings work.</p>
                <p>Use <b>SnapScan, MasterPass</b> or <b>WeChat</b> to snap the code to the right and make a donatino of your choosing.<br>
                    If you are on mobile, you can click on the QR below and follow the onscreen instructions.
                <p><a href="<?= base_url("uploads/snapcode.pdf"); ?>">SnapScan Code PDF</a>
                <p>
                    <a href="<?= base_url("contact"); ?>" class="btn">Contact me</a>
                </p>
            </div>
            <!-- end: Content-->

        </div>
    </div>
</section>
<!-- end: Support -->