</div>
</section>

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
                            foreach ($static_pages['races']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- end: Footer widget area 1 --> 
                </div>
                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 2 -->
                    <div class="widget">
                        <h4>RESULTS</h4>
                        <ul class="list"> 
                            <?php
                            foreach ($static_pages['results']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- end: Footer widget area 2 --> 
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 4 -->
                    <div class="widget">
                        <h4>PAGES</h4>
                        <ul class="list">
                            <li><a href='<?= $static_pages['faq']['loc']; ?>'><?= $static_pages['faq']['display']; ?></a></li>
                            <li><a href='<?= $static_pages['about']['loc']; ?>'><?= $static_pages['about']['display']; ?></a></li>
                            <li><a href='<?= $static_pages['contact']['loc']; ?>'><?= $static_pages['contact']['display']; ?></a></li>
                            <li><a href='<?= $static_pages['races']['loc']; ?>'>All <?= $static_pages['races']['display']; ?></a></li>
                            <li><a href='<?= $static_pages['sitemap']['loc']; ?>'><?= $static_pages['sitemap']['display']; ?></a></li>
                        </ul>
                    </div>
                    <!-- end: Footer widget area 4 --> 
                </div>

                <div class="col-xl-2 col-lg-2 col-md-3"> 
                    <!-- Footer widget area 3 -->
                    <div class="widget">
                        <h4> REGIONS</h4>
                        <ul class="list">
                            <?php
                            foreach ($static_pages['featured-regions']['sub-menu'] as $key => $page) {
                                echo "<li><a href='$page[loc]'>$page[display]</a></li>";
                            }
                            ?>
                            <li><a href='<?= $static_pages['featured-regions']['loc']; ?>'><?= $static_pages['featured-regions']['display']; ?></a></li>
                            <li><a href='<?= base_url('region/switch'); ?>'>Switch Regions</a></li>
                        </ul>
                    </div>
                    <!-- end: Footer widget area 3 --> 
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12"> 
                    <!-- Footer widget area 5 -->
                    <div class="widget clearfix widget-newsletter">
                        <h4 class="widget-title"><i class="fa fa-envelope"></i> Sign Up For a Newsletter</h4>
                        <p>Monthly newsletter informing you of the latest loaded results as well as the upcoming races

                        </p>
                        <form class="widget-subscribe-form p-r-40" action="include/subscribe-form.php" role="form" method="post" novalidate="novalidate">


                            <div class="input-group">
                                <input aria-required="true" name="widget-subscribe-form-email" class="form-control required email" placeholder="Enter your Email" type="email">
                                <span class="input-group-btn">
                                    <button type="submit" id="widget-subscribe-submit-button" class="btn"><i class="fa fa-paper-plane"></i></button>
                                </span> </div>
                        </form>
                    </div>
                    <!-- end: Footer widget area 5 --> 
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
                            <li class="social-facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="social-twitter"><a href="#"><i class="fab fa-twitter"></i></a></li>
                        </ul>
                    </div>
                    <!-- end: Social icons --> 
                </div>

                <div class="col-lg-9 text-right">
                    <div class="copyright-text">&copy; 2019 RoadRunning.co.za. All Rights Reserved.
                        <?php
                        $white_list = ["terms", "sitemap", "disclaimer"];
                        foreach ($static_pages as $key => $page) {
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
                        if (in_array(1, $logged_in_user['role_list'])) {
                            echo "| <a href='/mailer'>Mailer</a> ";
                            echo "| <a href='" . base_url("login/admin") . "'>Admin Login</a> ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

</div>


<a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>


<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = '<?=base_url('assets/css/combined_min.css');?>';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
    </script>
<script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?= base_url('assets/js/plugins.js'); ?>"></script>

<script src="<?= base_url('assets/js/functions.js'); ?>"></script>

<?php
if ($home) {
    ?>

    <link rel="stylesheet" href="<?= base_url('assets/js/plugins/components/daterangepicker/daterangepicker.css'); ?>" type="text/css" />
    <script src="<?= base_url('assets/js/plugins/components/moment.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/components/daterangepicker/daterangepicker.js'); ?>"></script>

    <script>
        $(function () {
            $('input[name="fromDate"]').daterangepicker({
                singleDatePicker: true,
                /* showDropdowns: true,*/
            });

            $('input[name="toDate"]').daterangepicker({
                singleDatePicker: true,
                /* showDropdowns: true,*/
            });

        });
    </script>
    <?php
}

//if ($this->ini_array['enviroment']['server'] != "production") {
    if (1==2) {
//    wts($static_pages);
    ?> 
    <h4 class="text-uppercase">Environment info</h4>
    <p>
        <?php
        echo "Enviroment: " . $this->ini_array['enviroment']['server'] . "<br>";
        echo "Controller: " . $this->router->fetch_class() . "<br>";
        echo "Method: " . $this->router->fetch_method();
        ?>
    </p>
    <h4 class="text-uppercase">User info</h4>
    <?php wts($logged_in_user); ?>

    <h4 class="text-uppercase">SESSION</h4>
    <?php wts($_SESSION); ?>

    <h4 class="text-uppercase">COOKIE</h4>
    <?php
    wts($_COOKIE);
} // if to not show in PROD
?>
</body>
</html>