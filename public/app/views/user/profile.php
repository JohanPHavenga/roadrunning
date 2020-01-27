<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p>This is the about page<p>
                    <?php
                    wts($subs);
                    wts($user);
                    ?>
            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <div class="widget widget-myaccount p-cb">
                    <div class="avatar">
                        <img src="<?= base_url("assets/img/Blank-Avatar.jpg");?>">
                        <span><?=$user['user_name'];?> <?=$user['user_surname'];?></span>
                        <p class="text-muted"><?=$user['user_email'];?></p>
                    </div>
                    <ul class="text-center">
                        <li><a href="#"><i class="icon-user11"></i>My profile</a></li>
                        <li><a href="#"><i class="icon-clock21"></i>Activity logs</a></li>
                        <li><a href="#"><i class="icon-mail"></i>Messages</a></li>
                        <li><a href="#"><i class="icon-settings1"></i>Settings</a></li>
                        <li><a href="#"><i class="icon-log-out"></i>Sing Out</a>
                        </li>
                    </ul>
                </div>
                <?php
                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: PROFILE -->


