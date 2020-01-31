<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">
                <h3 class="text-uppercase">Training Programs for runners</h3>
                <p>Looking for a <b>training program</b> to train for your <b>first 5km?</b> or to run a <b>sub 4-hour marathon</b>? You have come to right place.</p>
                <p><a href="<?= $coach_parry_link; ?>" class="btn btn-default"><i class="fa fa-running"></i> <?=$page_title;?>s</a><p>
                <p>If you Google “Running Training Programs” or something similar you will find many free programs out there. And some are great, 
                    but if you are looking for something more, <a href="https://coachparry.com/"><strong>Coach Parry</strong></a> is the way to go. 
                    They have a whole range of programs, and an awesome app to help you every step of the way. What is more you can join the forums 
                    on their website to ask questions or advise. 
                    <p>Coach Parry himself, the <u>Official Coaching Partner</u> for the Comrades, Soweto Marathon & Om die Dam Ultra will be on have to provide
                    questions and give advise.</p>
                <p>Click below to check out their offerings and find the best fit for you:</p>
                <p><a href="<?= $coach_parry_link; ?>" class="btn btn-default"><i class="fa fa-running"></i> Coach Parry <?=$page_title;?></a><p>
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