<?php
if (!isset($page_title)) {
    $page_title = "Coyote 2.0";
}
if (!isset($meta_description)) {
    $meta_description = "Run without being chased. Again.";
}
if (!isset($meta_robots)) {
    $meta_robots = "index";
}

// check methods to do header changes
if (($this->router->fetch_class() == "main") && ($this->router->fetch_method() == "index")) {
    $is_home = true;
    $topbar_class = 'topbar-transparent dark';
    $header_vals = 'data-transparent="true" class="dark"';
} else {
    $is_home = false;
    $topbar_class = '';
    $header_vals = '';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="Johan Havenga" />
        <meta name="description" content="<?= $meta_description; ?>">
        <meta name="robots" content="<?= $meta_robots; ?>" />

        <title><?= $page_title; ?></title>

        <!-- critical path css -->
        <link href="<?= base_url('assets/css/combined_min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />

        <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/favicon/apple-icon-57x57.png'); ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/favicon/apple-icon-60x60.png'); ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/favicon/apple-icon-72x72.png'); ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/favicon/apple-icon-76x76.png'); ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/favicon/apple-icon-114x114.png'); ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/favicon/apple-icon-120x120.png'); ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/favicon/apple-icon-144x144.png'); ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/favicon/apple-icon-152x152.png'); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/favicon/apple-icon-180x180.png'); ?>">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= base_url('assets/favicon/android-icon-192x192.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/favicon/favicon-96x96.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?= base_url('assets/favicon/manifest.json'); ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= base_url('assets/favicon/ms-icon-144x144.png'); ?>">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="body-inner">

            <!-- Topbar -->
            <div id="topbar" class="<?= $topbar_class; ?> topbar-fullwidth d-none d-xl-block d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="topbar-dropdown">
                                <?php
                                if ($this->session->user['logged_in']) {
                                    echo "<a class='title' href='/user/profile'><i class='fa fa-user'></i> " . $logged_in_user['user_name'] . "</a></div>";
                                    echo "<div class='topbar-dropdown'><a class='title' href='/logout'>Log Out</a>";
                                } else {
                                    echo "<a class='title' href='/login/'><i class='fa fa-user'></i> Log In</a>";
                                }
                                ?>

                                <!--<a class="title" href="#" title="Log into your profile"><i class="fa fa-user"></i> Login</a>-->
                            </div>
                            <div class="topbar-dropdown">
                                <a class="title" href="<?= base_url('region/switch'); ?>" title="Change region">Western Cape</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-sm-block">
                            <div class="social-icons social-icons-colored-hover">
                                <ul>
                                    <li class="social-facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li class="social-twitter"><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li class="social-google"><a href="#"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: Topbar -->

            <!-- Header -->
            <header id="header" data-fullwidth="true" <?= $header_vals; ?>>
                <div class="header-inner">
                    <div class="container"> <!--Logo-->
                        <div id="logo">
                            <a href="/" class="logo" data-src-dark="<?= base_url('assets/img/roadrunning_logo_dark_80.svg'); ?>">
                                <img src="<?= base_url('assets/img/roadrunning_logo_80.svg'); ?>" alt="RR Logo">
                            </a>
                        </div>
                        <!--End: Logo-->

                        <!-- Search -->
                        <div id="search">
                            <div id="search-logo"><img src="<?= base_url('assets/img/roadrunning_logo_80.svg'); ?>" alt="RR Logo"></div>
                            <button id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i class="icon-x"></i></button>
                            <form class="search-form" action="search-results-page.html" method="get">
                                <input class="form-control" name="q" type="search" placeholder="Search..." autocomplete="off" />
                                <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                            </form>
                            <div class="search-suggestion-wrapper">
                                <div class="search-suggestion">
                                    <h3>News Articles</h3>
                                    <p><a href="#">Beautiful nature, and rare feathers!</a></p>
                                    <p><a href="#">New costs and rise of the economy!</a></p>
                                    <p><a href="#">A true story, that never been told!</a></p>
                                </div>
                                <div class="search-suggestion">
                                    <h3>Looking for these?</h3>
                                    <p><a href="#">New costs and rise of the economy!</a></p>
                                    <p><a href="#">AI can be trusted to take answer calls </a></p>
                                    <p><a href="#">Polo now lets you easily create any beautiful clean website</a></p>
                                </div>
                                <div class="search-suggestion">
                                    <h3>Blog Posts</h3>
                                    <p><a href="#">A true story, that never been told!</a></p>
                                    <p><a href="#">Beautiful nature, and rare feathers!</a></p>
                                    <p><a href="#">The most happiest time of the day!</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- end: search -->

                        <!--Header Extras-->
                        <div class="header-extras">
                            <ul>
                                <li class="d-none d-xl-block d-lg-block">
                                    <a href="" class="btn">Add listing</a>
                                </li>
                                <li>
                                    <a id="btn-search" href="#"> <i class="icon-search1"></i></a>
                                </li>
                                <li>
                                    <div id="user-profile" class="p-dropdown">
                                        <a href="<?= base_url('user/profile'); ?>"><i class="icon-user11"></i></a>
                                        <div class="p-dropdown-content ">
                                            <div class="widget-profile">
                                                <h4><?= $logged_in_user['user_name']; ?> <?= $logged_in_user['user_surname']; ?></h4>
                                                <div class="cart-item">
                                                    Stuff here
                                                </div>
                                                <hr>
                                                <div class="cart-buttons text-right">
                                                    <button class="btn btn-xs">Profile</button>
                                                    <button class="btn btn-xs">Log Out</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!--end: Header Extras-->
                        <!--Navigation Resposnive Trigger-->
                        <div id="mainMenu-trigger">
                            <button class="lines-button x"> <span class="lines"></span> </button>
                        </div>
                        <!--end: Navigation Resposnive Trigger-->

                        <!--Navigation-->
                        <div id="mainMenu">
                            <div class="container">
                                <nav>
                                    <ul>
                                        <?php
                                        $white_list = ["home", "races", "results", "faq", "about", "contact"];
                                        foreach ($static_pages as $key => $page) {
                                            if (!in_array($key, $white_list)) {
                                                continue;
                                            }
                                            echo "<li><a href='$page[loc]'>$page[display]</a>";
                                            if (isset($page['sub-menu'])) {
                                                echo '<ul class="dropdown-menu">';
                                                foreach ($page['sub-menu'] as $sub_page) {
                                                    echo "<li><a href='$sub_page[loc]'>$sub_page[display]";
                                                    if (isset($sub_page['badge'])) {
                                                        echo "<span class='badge badge-danger'>$sub_page[badge]</span>";
                                                    }
                                                    echo "</a></li>";
                                                }
                                                echo '</ul>';
                                            }
                                            echo "</li>";
                                        }
                                        if ($this->session->user['logged_in']) {
                                            echo "<li><a href='" . base_url('user/profile') . "'>My Profile</a></li>";
                                            echo "<li><a href='/logout'>Log Out</a></li>";
                                        } else {
                                            echo "<li><a href='/login/'>Log In</a></li>";
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!--END: NAVIGATION-->
                    </div>
                </div>
            </header>