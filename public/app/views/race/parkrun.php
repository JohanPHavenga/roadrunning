<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase"><?= $page_title; ?></h3>
                <p><b>parkrun</b> organise free, weekly, 5km timed runs around the world. They are open to everyone, <b>free</b>, and are safe and easy to take part in.</p>
                <p>These events take place in pleasant parkland surroundings and people of every ability are encouraged to take part; 
                    from walkers or those taking their first steps in running to Olympians; from juniors to those with more experience; everyone is welcome!</p>
                <p>
                    <a href="https://www.parkrun.co.za/events/#geo=4.7/-28.78/23.38" class="btn btn-light  btn-icon-holder">view parkrun events<i class="fa fa-arrow-right"></i></a>
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
<!-- end: parkrun -->