<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                    <h3 class="text-uppercase">Contact Confirmation</h3>
                    <p><?=$this->session->flashdata('confirm_msg');?></p>
                    
                    <a href="<?=$this->session->flashdata('confirm_btn_url');?>" class="btn"><i class="fa fa-arrow-circle-left"></i>&nbsp<?=$this->session->flashdata('confirm_btn_txt');?></a>
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
<!-- end: Contact products -->
