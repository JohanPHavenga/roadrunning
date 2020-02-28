<?php
// check for home page, set a few variables for header changes
if (($this->router->fetch_class() == "main") && ($this->router->fetch_method() == "index")) {
    $home = true;
    $topbar_class = 'topbar-transparent dark';
    $header_vals = 'data-transparent="true" class="dark"';
} else {
    $home = false;
    $topbar_class = '';
    $header_vals = '';
}
?>
<footer id="footer" class="inverted">
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 1 -->
                    <div class="widget">
                        <h4>RACES</h4>
                        <ul class="list">
                            <?php
                            foreach ($this->session->static_pages['races']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]</a></li>";
                            }
                            foreach ($this->session->static_pages['results']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]";
                                if (isset($page['badge'])) {
                                    echo " <span class='badge badge-danger'>$page[badge]</span>";
                                }
                                echo "</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- end: Footer RACES --> 
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 4 -->
                    <div class="widget">
                        <h4>PAGES</h4>
                        <ul class="list">
                            <li><a href='<?= $this->session->static_pages['faq']['loc']; ?>'><?= $this->session->static_pages['faq']['display']; ?></a></li>
                            <li><a href='<?= $this->session->static_pages['about']['loc']; ?>'><?= $this->session->static_pages['about']['display']; ?></a></li>
                            <li><a href='<?= $this->session->static_pages['contact']['loc']; ?>'><?= $this->session->static_pages['contact']['display']; ?></a></li>
                            <li><a href='<?= $this->session->static_pages['add-listing']['loc']; ?>'><?= $this->session->static_pages['add-listing']['display']; ?></a></li>
                            <li><a href='<?= $this->session->static_pages['search']['loc']; ?>'><?= $this->session->static_pages['search']['display']; ?></a></li>
                            <?php
                            if ($this->session->user['logged_in']) {
                                ?>
                                <li><a href='<?= base_url("logout"); ?>'>Logout</a></li>
                                <?php
                            } else {
                                ?>
                                <li><a href='<?= $this->session->static_pages['login']['loc']; ?>'><?= $this->session->static_pages['login']['display']; ?></a></li>
                                <?php
                            }
                            ?>
                            <li><a href='<?= $this->session->static_pages['sitemap']['loc']; ?>'><?= $this->session->static_pages['sitemap']['display']; ?></a></li>
                            <li><a href='<?= base_url('old'); ?>'>Back to old site</a></li>
                        </ul>
                    </div>
                    <!-- end: Footer PAGES --> 
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 3 -->
                    <div class="widget">
                        <h4>REGIONS</h4>
                        <ul class="list">
                            <?php
                            foreach ($this->session->static_pages['featured-regions']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]";
                                if (isset($page['badge'])) {
                                    echo " <span class='badge badge-danger'>$page[badge]</span>";
                                }
                                echo "</a></li>";
                            }
                            ?>
                            <li><a href='<?= $this->session->static_pages['featured-regions']['loc']; ?>'><?= $this->session->static_pages['featured-regions']['display']; ?></a></li>
                            <li><a href='<?= base_url('region/switch'); ?>'>Switch Regions</a></li>
                        </ul>
                    </div>
                    <!-- end: Footer REGIONS --> 
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 2 -->
                    <div class="widget">
                        <h4>CALENDAR</h4>
                        <ul class="list"> 
                            <?php
                            foreach ($this->session->calendar_date_list as $year => $month_list) {
                                foreach ($month_list as $month_number => $month_name) {
                                    echo "<li><a href='" . base_url() . "calendar/" . $year . "/" . $month_number . "'>" . $month_name . "</a></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- end: Footer CALENDAR --> 
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12"> 
                    <!-- Footer widget area 5 -->
                    <div class="widget clearfix widget-newsletter">
                        <h4 class="widget-title"><i class="fa fa-envelope"></i> Sign Up For a Newsletter</h4>
                        <p>Monthly newsletter informing you of the latest loaded results as well as the upcoming races

                        </p>
                        <form class="widget-subscribe-form p-r-40" action="<?= base_url("contact/test"); ?>" role="form" method="post">

                            <div class="input-group">
                                <input aria-required="true" name="widget-subscribe-form-email" class="form-control required email" placeholder="Enter your Email" type="email">
                                <!--<input name="widget-subscribe-form-email">-->
                                <span class="input-group-btn">
                                    <button type="submit" id="widget-subscribe-submit-button" class="btn"><i class="fa fa-paper-plane"></i></button>
                                </span> </div>
                        </form>
                    </div>
                    <!-- end: Footer widget area 5 --> 

                    <p><span class='badge badge-info' style='font-size: 1.2em;'>SnapScan</span></p>
                    <div class="m-b-10 m-r-20" style="padding: 10px; background-color: #fff; float: left;">
                        <a href="https://pos.snapscan.io/qr/LAzMFdGZ">
                            <img src='<?= base_url("assets/img/snapscan_LAzMFdGZ.png"); ?>' /> 
                        </a>
                    </div>
                    <p style=''>Consider supporting the wesbite via SnapScan</p>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-content">
        <div class="container">

            <div class="row">
                <div class="col-lg-3">
                    <!-- Social icons -->
                    <div class="social-icons social-icons">
                        <ul>
                            <li class="social-facebook"><a href="https://www.facebook.com/roadrunningcoza"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="social-twitter"><a href="https://twitter.com/roadrunningcoza"><i class="fab fa-twitter"></i></a></li>
                        </ul>
                    </div>
                    <!-- end: Social icons --> 
                </div>

                <div class="col-lg-9 text-right">
                    <div class="copyright-text">&copy; <?= date("Y"); ?> RoadRunning.co.za. All Rights Reserved.
                        <?php
                        $white_list = ["terms", "sitemap"];
                        foreach ($this->session->static_pages as $key => $page) {
                            if (!in_array($key, $white_list)) {
                                continue;
                            }
                            echo " | <a href='$page[loc]'>$page[display]</a> ";
                        }
                        // KILL SESSION only in dev
                        if ($this->ini_array['enviroment']['server'] != "production") {
                            echo "| <a href='" . base_url("login/destroy") . "'>Kill Session</a> ";
                        }
                        // IF LOGGED IN USER IS ADMIN
                        if ($logged_in_user) {
                            if (in_array(1, $logged_in_user['role_list'])) {
                                echo "| <a href='" . base_url("mailer") . "'>Mailer</a> ";
                                echo "| <a href='" . base_url("admin") . "'>Admin Dashboard</a> ";
                            }
                        }
//                        wts($logged_in_user);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

</div>


<a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>

<script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?= base_url('assets/js/plugins.js'); ?>"></script>
<script src="<?= base_url('assets/js/functions.js'); ?>"></script>
<script src="<?= base_url('assets/js/fa.js'); ?>" crossorigin="anonymous"></script>

<?php
if (isset($scripts_to_load)) :
    foreach ($scripts_to_load as $row):
        if (substr($row, 0, 4) == "http") {
            $js_link = $row;
        } else {
            $js_link = base_url($row);
        }
        echo "<script src='$js_link' type='text/javascript' defer></script>";
    endforeach;
endif;
?>

<script>
<?php
foreach ($this->session->most_searched as $search_id => $search) {
    ?>
        $('#search_<?= $search_id; ?>').click(function () {
            $('#main_search').val('<?= $search['search_term']; ?>');
            $("#main_search_form").submit();
        });
    <?php
}
?>
</script>

<!-- START: AD BLOCK CHECK -->
<div class="modal-strip cookie-notify background-dark fadeInUp" data-wow-delay="1s" id="ad-block-notification">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <b>We get it!</b> Ads are annoying. But they help keep this website up and running. <br>
                Please help support this site by <u>disabling your ad block software</u>.
            </div>
            <div class="col-md-2 text-right">
                <button type="button" class="btn btn-rounded btn-light modal-close">OK!</button>
            </div>
        </div>
    </div>
</div>
<script src="/ads.js" type="text/javascript"></script>
<script type="text/javascript">//
    if (!document.getElementById('advertensieblok')) {
        document.getElementById('ad-block-notification').style.display = 'block';
    }
//</script>
<!-- END: AD BLOCK CHECK -->

<?php
//if ($this->ini_array['enviroment']['server'] != "production") {
//if (1 == 2) {
if (($logged_in_user) && (in_array(1, $logged_in_user['role_list'])) && (isset($_GET['debug']))) {
//    wts($this->session->most_viewed_pages);
    ?> 
    <h4 class="text-uppercase">Environment info</h4>
    <p>
        <?php
        echo "Enviroment: " . $this->ini_array['enviroment']['server'] . "<br>";
        echo "Controller: " . $this->router->fetch_class() . "<br>";
        echo "Method: " . $this->router->fetch_method();
        ?>
    </p>
    <h4 class="text-uppercase">New site</h4>
    <?php wts($new_page_count); ?>

    <h4 class="text-uppercase">User info</h4>
    <?php wts($logged_in_user); ?>

    <h4 class="text-uppercase">SESSION</h4>
    <?php wts($_SESSION); ?>

    <h4 class="text-uppercase">COOKIE</h4>
    <?php
    wts($_COOKIE);
    wts($rr_cookie);
}
?>
</body>
</html>