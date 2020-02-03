<?php
if (isset($no_notice)) {
    // do nothign
} elseif (!isset($rr_cookie['no_new_site'])) {
?>
<!--<div class="c-cookies-bar c-cookies-bar-1 c-cookies-bar-bottom c-bg-yellow wow animate fadeInUp" data-wow-delay="1s">
    <div class="c-cookies-bar-container">
        <div class="row">
            <div class="col-md-9">
                <div class="c-cookies-bar-content c-font-white">
                    <b>GOOD NEWS EVERYONE!</b> The new website is nearing completion. 
                </div>
            </div>
            <div class="col-md-3">
                <div class="c-cookies-bar-btn">
                    <a href="<?= base_url("new/".$uri_string); ?>" class="btn c-btn-white c-btn-border-1x c-btn-bold c-btn-square c-cookie-bar-link">Take a sneak peak</a> 
                    <a class="c-cookies-bar-close btn c-btn-white c-btn-square c-btn-bold" href="<?= base_url("pages/no_new_site/".my_encrypt($uri_string)); ?>">Don't care</a>
                </div>
            </div>
        </div>
    </div>
</div>-->
<?php
}
//    wts($rr_cookie);
?>

<!-- BEGIN: LAYOUT/FOOTERS/FOOTER -->
<a name="footer"></a>
<footer class="c-layout-footer c-layout-footer-7">
    <div class="container">
        <div class="c-prefooter">
            <div class="c-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="c-content-title-1 c-title-md">
                            <h3 class="c-font-white c-font-uppercase c-font-bold">Main Menu</h3>
                            <div class="c-line-left hide"></div>
                        </div>
                        <ul class="c-links c-theme-ul">
                            <li>
                                <a href="<?= base_url(); ?>">Home</a>
                            </li>
                            <li>
                                <a href="<?= base_url("calendar");?>">Upcoming Events</a>
                            </li>
                            <li>
                                <a href="<?= base_url("calendar/results");?>"> Results</a>
                            </li>
                            <li>
                                <a href="<?= base_url("parkrun/calendar");?>"> Parkrun</a>
                            </li>
                            <li>
                                <a href="<?= base_url("faq");?>"> FAQ</a>
                            </li>
                            <li>
                                <a href="<?= base_url("contact");?>">Contact Us</a>
                            </li>
                            <li>
                                <a href="<?= base_url("search");?>">Search</a>
                            </li>
                            <li>
                                <a href="https://www.roadrunning.co.za/">Switch to NEW SITE</a>
                            </li>
                        </ul>
                        <ul class="c-links c-theme-ul">

                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="c-content-title-1 c-title-md">
                            <h3 class="c-font-white c-font-uppercase c-font-bold">Events By Area</h3>
                            <div class="c-line-left hide"></div>
                        </div>
                        <ul class="c-links c-theme-ul">
                            <?php
                            foreach ($area_list as $area_id => $area) {
                                echo "<li><a href='" . base_url() . str_replace(" ", "", strtolower($area['area_name'])) . "'>" . $area['area_name'] . "</a></li>";
                            }
                            ?>
                        </ul>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="c-content-title-1 c-title-md">
                            <h3 class="c-font-white c-font-uppercase c-font-bold">Events By Date</h3>
                            <div class="c-line-left hide"></div>
                        </div>
                        <ul class="c-links c-theme-ul">
                            <?php
                            foreach ($date_list as $year => $month_list) {
                                foreach ($month_list as $month_number => $month_name) {
                                    echo "<li><a href='" . base_url() . "calendar/" . $year . "/" . $month_number . "'>" . $month_name . "</a></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <div class="c-container c-last">
                            <div class="c-content-title-1 c-title-md">
                                <h3 class="c-font-uppercase c-font-bold c-font-white">Get In Touch</h3>
                                <div class="c-line-left hide"></div>
                                <p>Please get in touch, we would love to hear from you. Send us suggestions on how we can make this site even better.</p>
                            </div>
                            <ul class="c-socials">
                                <li><a href="https://www.facebook.com/roadrunningcoza" target="_blank" title="Follow us on Facebook"><i class="icon-social-facebook"></i></a></li>
                                <li><a href="https://twitter.com/roadrunningcoza" target="_blank" title="Follow us on Twitter"><i class="icon-social-twitter"></i></a></li>
<!--                                <li><a href="#"><i class="icon-social-youtube"></i></a></li>
                                <li><a href="#"><i class="icon-social-tumblr"></i></a></li>-->
                            </ul>
                            <ul class="c-address">
                                <li><i class="icon-pointer c-theme-font"></i> Cape Town, South Africa</li>
                                <!--<li><i class="icon-call-end c-theme-font"></i> +1800 1234 5678</li>-->
                                <li><i class="icon-envelope c-theme-font"></i> <a href="mailto:info@roadrunning.co.za">info@roadrunning.co.za</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="c-line"></div>
            <div class="c-foot">
                <div class="row">
                    <div class="col-md-7">
                        <div class="c-content-title-1 c-title-md">
                            <h3 class="c-title c-font-uppercase c-font-bold">About
                                <span class="c-theme-font">RoadRunning.co.za</span>
                            </h3>
                            <div class="c-line-left hide"></div>
                        </div>
                        <p class="c-text c-font-16 c-font-regular">
                            We are amateur runners in it for the love of the road, the simplicity and beauty of it.<br>
                            This project started out due to a lack of a <b>comprehensive, modern listing site for running events</b>. 
                            Since then a few has been established, but our goal remains and the mission is to become the <b>number one road running events listing site</b> in the country.
                        </p>
                    </div>
                    <div class="col-md-5">
                        <div class="c-content-title-1 c-title-md">
                            <h3 class="c-title c-font-uppercase c-font-bold">Subscribe to Newsletter</h3>
                            <div class="c-line-left hide"></div>
                        </div>
                        <div class="c-line-left hide"></div>
                        <form action="<?= base_url("newsletter");?>" method="post">
                            <div class="input-group input-group-lg c-square">
                                <input type="email" class="c-input form-control c-square c-theme" placeholder="Your Email Here" name="demail"/>
                                <span class="input-group-btn">
                                    <button class="btn c-theme-btn c-theme-border c-btn-square c-btn-uppercase c-font-16" type="submit" name="button" value="footer-btn">Subscribe</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="c-postfooter c-bg-dark-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 c-col">
                    <p class="c-copyright c-font-grey"><?= date('Y'); ?> &copy; RoadRunning.co.za
                        <span class="c-font-grey-3">All Rights Reserved.</span>
                        <!--<a href="<?= $admin_login; ?>">Admin&nbsp;Login</a>-->
                    </p>
                </div>
                <div class="col-md-6 col-sm-6">
                    <ul class="c-socials">
                        <!--                        <li>
                                                    <style>.bmc-button img{width: 27px !important;margin-bottom: 1px !important;box-shadow: none !important;border: none !important;vertical-align: middle !important;}.bmc-button{line-height: 36px !important;height:37px !important;text-decoration: none !important;display:inline-flex !important;color:#FFFFFF !important;background-color:#FF813F !important;border-radius: 3px !important;border: 1px solid transparent !important;padding: 1px 9px !important;font-size: 22px !important;letter-spacing: 0.6px !important;box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;margin: 0 auto !important;font-family:'Cookie', cursive !important;-webkit-box-sizing: border-box !important;box-sizing: border-box !important;-o-transition: 0.3s all linear !important;-webkit-transition: 0.3s all linear !important;-moz-transition: 0.3s all linear !important;-ms-transition: 0.3s all linear !important;transition: 0.3s all linear !important;}.bmc-button:hover, .bmc-button:active, .bmc-button:focus {-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;text-decoration: none !important;box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;opacity: 0.85 !important;color:#FFFFFF !important;}</style><link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet"><a class="bmc-button" target="_blank" href="https://www.buymeacoffee.com/roadrunning"><img src="https://bmc-cdn.nyc3.digitaloceanspaces.com/BMC-button-images/BMC-btn-logo.svg" alt="Buy me a coffee"><span style="margin-left:5px">Buy me a coffee</span></a>
                                                </li>-->
                        <!--                        <li>
                                                    <script src="https://liberapay.com/RoadRunningZA/widgets/button.js"></script>
                        <noscript><a href="https://liberapay.com/RoadRunningZA/donate"><img alt="Donate using Liberapay" src="https://liberapay.com/assets/widgets/donate.svg"></a></noscript>
                                                </li>-->
<!--                        <li><a href="https://www.patreon.com/roadrunningza" target="_blank" title="Support us on Patreon">
                                <img src="/img/become_a_patron.png" alt="Support us on Patreon" style="height: 36px; margin-top: 1px;"></a>
                        </li>-->
<!--                                                        <li><a href="https://twitter.com/roadrunningcoza" target="_blank" title="Follow us on Twitter"><i class="icon-social-twitter"></i></a></li>
                        <li><a href="https://www.facebook.com/roadrunningcoza" target="_blank" title="Like us on Facebook"><i class="icon-social-facebook"></i></a></li>-->
                      <!--<li><a href="#"><i class="icon-social-youtube"></i></a></li>-->
                      <!--<li><a href="#"><i class="icon-social-dribbble"></i></a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- BEGIN: LAYOUT/FOOTERS/GO2TOP -->
<div class="c-layout-go2top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END: LAYOUT/FOOTERS/GO2TOP -->
<!-- BEGIN: LAYOUT/BASE/BOTTOM -->

<link href="<?= base_url('css/roboto-condensed.min.css'); ?>" rel="stylesheet" type="text/css" media="screen" />
<!--<link href="<?= base_url('css/roboto.css'); ?>" rel="stylesheet" type="text/css" />-->
<link href="<?= base_url('plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('css/components.min.css'); ?>" id="style_components" rel="stylesheet" type="text/css" />
<link href="<?= base_url('css/theme.css'); ?>" rel="stylesheet" id="style_theme" type="text/css" />
<link href="<?= base_url('plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css" /
      <noscript>
          <!--<link href="<?= base_url('plugins/bootstrap-social/bootstrap-social.css'); ?>" rel="stylesheet" type="text/css" />-->
<link href="<?= base_url('plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('plugins/animate/animate.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('css/plugins.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('css/custom.css'); ?>" rel="stylesheet" type="text/css" />
</noscript>

<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN THEME STYLES -->
<!--<link href="<?= base_url('css/custom.css'); ?>" rel="stylesheet" type="text/css" />-->
<!-- END THEME STYLES -->

<!-- DEFER LOADING OF CSS FILES -->
<script type="text/javascript">
    /* Font Awesome */
    var font_awesome = document.createElement('link');
    font_awesome.rel = 'stylesheet';
    font_awesome.href = '<?= base_url('plugins/font-awesome/css/font-awesome.min.css'); ?>';
    font_awesome.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(font_awesome, godefer);
    /* Simple Line Icons */
    var simple_line_icons = document.createElement('link');
    simple_line_icons.rel = 'stylesheet';
    simple_line_icons.href = '<?= base_url('plugins/simple-line-icons/simple-line-icons.min.css'); ?>';
    simple_line_icons.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(simple_line_icons, godefer);
    /* Animate */
    var animate = document.createElement('link');
    animate.rel = 'stylesheet';
    animate.href = '<?= base_url('plugins/animate/animate.min.css'); ?>';
    animate.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(animate, godefer);
    /* Plugins */
    var plugins = document.createElement('link');
    plugins.rel = 'stylesheet';
    plugins.href = '<?= base_url('css/plugins.min.css'); ?>';
    plugins.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(plugins, godefer);
    /* Custom */
    var custom = document.createElement('link');
    custom.rel = 'stylesheet';
    custom.href = '<?= base_url('css/custom.css'); ?>';
    custom.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(custom, godefer);</script>
<!-- BEGIN: CORE PLUGINS -->


<!--[if lt IE 9]>
<script src="<?= base_url('plugins/excanvas.min.js'); ?>"></script>
<![endif]-->
<script src="<?= base_url('plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/jquery.easing.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/reveal-animate/wow.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('scripts/reveal-animate/reveal-animate.js'); ?>" type="text/javascript"></script>
<!-- END: CORE PLUGINS -->
<?php
// load extra JS files from controller
if (isset($js_to_load)) :
    foreach ($js_to_load as $row):
        if (substr($row, 0, 4) == "http") {
            $js_link = $row;
        } else {
            $js_link = base_url($row);
        }
        echo "<script src='$js_link'></script>";
    endforeach;
endif;
?>
<!-- BEGIN: LAYOUT PLUGINS -->
<script src="<?= base_url('plugins/cubeportfolio/js/jquery.cubeportfolio.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/counterup/jquery.waypoints.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/counterup/jquery.counterup.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/fancybox/jquery.fancybox.pack.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('plugins/smooth-scroll/jquery.smooth-scroll.js'); ?>" type="text/javascript"></script>
<!-- END: LAYOUT PLUGINS -->
<!-- BEGIN: THEME SCRIPTS -->
<script src="<?= base_url('js/components.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('js/app.js'); ?>" type="text/javascript"></script>
<!--<script src="http://maps.google.com/maps/api/js?sensor=true"></script>-->
<script>
    $(document).ready(function ()
    {
    App.init(); // init core
    });
    }
    );</script>
<!-- END: THEME SCRIPTS -->
<!-- BEGIN: PAGE SCRIPTS -->
<?php
// load script files from controller
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

if (isset($scripts_to_display)) {
    echo "<script>";
    foreach ($scripts_to_display as $script) {
        echo $script;
    }
    echo "</script>";
}
?>
<!-- END: PAGE SCRIPTS -->
<!-- END: LAYOUT/BASE/BOTTOM -->

<!-- START: AD BLOCK CHECK -->
<div class="c-cookies-bar c-cookies-bar-2 c-cookies-bar-top c-theme-bg c-rounded wow animate fadeInDown" data-wow-delay="1s" id="ad-block-notification">
    <div class="c-cookies-bar-container">
        <div class="row">
            <div class="col-md-10">
                <div class="c-cookies-bar-content c-font-white">
                    <b>We get it!</b> Ads are annoying. But they help keep this website up and running. <br>
                    Please help us make a few bucks by <u>disabling your ad block software</u>.
                </div>
            </div>
            <div class="col-md-2">
                <div class="c-cookies-bar-btn">
                    <a class="c-cookies-bar-close btn c-btn-white c-btn-square c-btn-bold" href="javascript:;">OK!</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="/ads.js" type="text/javascript"></script>-->
<!--<script type="text/javascript">
    if (!document.getElementById('advertensieblok')) {
        document.getElementById('ad-block-notification').style.display = 'block';
    }
</script>-->
<!-- END: AD BLOCK CHECK -->

</body>
</html>