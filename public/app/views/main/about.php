<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <div style="float: right; margin: 0 0 10px 20px; border: 1px solid #ccc;">
                    <img src="<?=base_url("assets/img/johan-havenga.jpg");?>"/>
                </div>
                <p>Thank you for using my site and clicking on the About section to find out more about me. I'm flattered.</p>
                <p>Name is Johan Havenga. Nice to meet you. <b>Part time web developer, part time runner</b> with a full-time job. (that is not running this website). 
                    Hopefully one day that dream can become a reality. Cape Town is where I call home.</p>
                <p>This site started as an idea way back in 2015. It was born out of a frustration to find quality information on racing events in one place, 
                    across the regions where I live. At the time something like that did not exist, so I started building it from scratch in my spare time. 
                    By the end of 2016 I was ready to launch and so <a href="https://www.roadrunning.co.za/">RoadRunning.co.za</a> was born</p>
                <p>I have learned a lot along the way and have gotten a lot of feedback from you, my users. So it might be a bit later than planned, but here 
                    we are in 2020, ready to launch version 2.0 of RoadRunning.co.za. Hope you like it. If you do, or if you don’t, please 
                    <a href="<?=base_url("contact");?>">let me know</a>.</p>
                <p>Happy running.
                    <br>- Johan
                </p>
                <p>
                    <a href="<?=base_url("contact");?>" class="btn">Contact me</a>
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
<!-- end: About -->