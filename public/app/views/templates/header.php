<?php
if (!isset($page_title)) {
    $page_title = "Coyote 2.0";
}
if (!isset($meta_description)) {
    $meta_description = "Run without being chased.";
}
if (!isset($meta_robots)) {
    $meta_robots = "index";
}

// check methods to do header changes
if (($this->router->fetch_class() == "main") && ($this->router->fetch_method() == "index")) {
    $is_home = true;
//    $topbar_class = 'topbar-transparent dark';
//    $header_vals = 'data-transparent="true" class="dark"';
    $topbar_class = '';
    $header_vals = '';
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

        <?php
        if (isset($structured_data)) :
            echo $structured_data;
        endif;
        ?>
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
                                    echo "<a class='title' href='/user/profile'><i class='fa fa-user'></i> " . $logged_in_user['user_name'] . "</a>";
                                } else {
                                    echo "<a class='title' href='/login/'><i class='fa fa-user'></i> Login</a>";
                                }
                                ?>

                                <!--<a class="title" href="#" title="Log into your profile"><i class="fa fa-user"></i> Login</a>-->
                            </div>
                            <div class="topbar-dropdown">
                                <a class="title" href="<?= base_url('region/switch'); ?>" title="Change selected regions"><i class='fa fa-compass'></i> Change Region</a>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-sm-block">
                            <div class="social-icons social-icons-colored-hover">
                                <ul>
                                    <li class="social-facebook"><a href="https://www.facebook.com/roadrunningcoza"><i class="fab fa-facebook-f"></i></a></li>
                                    <li class="social-twitter"><a href="https://twitter.com/roadrunningcoza"><i class="fab fa-twitter"></i></a></li>
                                    <!--<li class="social-google"><a href="#"><i class="fab fa-instagram"></i></a></li>-->
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
                            <a href="<?=base_url();?>" class="logo" data-src-dark="<?= base_url('assets/img/roadrunning_logo_dark_80.svg'); ?>">
                                <img src="<?= base_url('assets/img/roadrunning_logo_80.svg'); ?>" alt="RR Logo">
                            </a>
                        </div>
                        <!--End: Logo-->

                        <!-- Search -->
                        <div id="search">
                            <div id="search-logo"><img src="<?= base_url('assets/img/roadrunning_logo_80.svg'); ?>" alt="RR Logo"></div>
                            <button id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i class="icon-x"></i></button>
                            <?php
                            $attributes = array('class' => 'search-form', 'method' => 'post', 'id' => 'main_search_form');
                            echo form_open(base_url("search"), $attributes);
                            echo form_input([
                                'name' => 'query',
                                'type' => 'search',
                                'id' => 'main_search',
//                                'value' => set_value('query'),
                                'class' => 'form-control',
                                'placeholder' => 'Search...',
                                'autocomplete' => 'off',
                            ]);
                            ?>
                            <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                            <?php
                            echo form_close();
                            ?>
                            <div class="search-suggestion-wrapper">
                                <div class="search-suggestion">
                                    <h3>Popular searches</h3>
                                    <?php
                                    foreach ($this->session->most_searched as $search_id => $search) {
                                        echo "<p><a href='#' id='search_".$search_id."'>" . $search['search_term'] . " </a></p>";
                                    }
                                    ?>
                                </div>
                                <div class="search-suggestion">
                                    <h3>Most viewed races</h3>

                                    <?php
                                    foreach ($this->session->most_viewed_pages as $key => $page) {
                                        echo "<p><a href='$page[edition_url]'>" . substr($page['edition_name'], 0, -5) . "</a></p>";
                                    }
                                    ?>
                                </div>
                                <div class="search-suggestion">
                                    <h3>Featured regions</h3>
                                    <?php
                                    foreach ($this->session->static_pages['featured-regions']['sub-menu'] as $key => $page) {
                                        echo "<p><a href='$page[loc]'>$page[display]</a></p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- end: search -->

                        <!--Header Extras-->
                        <div class="header-extras">
                            <ul>
                                <li class="d-none d-xl-block d-lg-block">
                                    <a href="<?= base_url("event/add"); ?>" class="btn">Add listing</a>
                                </li>
                                <li>
                                    <a id="btn-search" href="#"> <i class="icon-search1"></i></a>
                                </li>
                                <li>
                                    <div id="user-profile" class="p-dropdown">
                                        <a href="<?= base_url('user/profile'); ?>"><i class="icon-user11"></i></a>
                                        <div class="p-dropdown-content ">
                                            <div class="widget-profile">
                                                <?php
                                                if (!empty($logged_in_user)) {
                                                    ?>
                                                    <p class="small m-b-0">Logged in as</p>
                                                    <h4><?= $logged_in_user['user_name']; ?> <?= $logged_in_user['user_surname']; ?></h4>
                                                    <div class="cart-item">
                                                        
                                                    </div>
                                                    <hr>
                                                    <div class="cart-buttons">
                                                        <a href="<?= base_url('user/profile'); ?>" class="btn btn-xs">Profile</a>
                                                        <a href="<?= base_url('logout'); ?>" class="btn btn-xs btn-light">Logout</a>
                                                        <!--<button class="btn btn-xs">Log Out</button>-->
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="cart-item">
                                                        You are not currently logged in
                                                    </div>
                                                    <hr>
                                                    <div class="cart-buttons text-right">
                                                        <a href="<?= base_url('login'); ?>" class="btn btn-xs">Login</a>
                                                        <a href="<?= base_url('register'); ?>" class="btn btn-xs btn-light">Register</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!--end: Header Extras-->
                        <!--Navigation Responsive Trigger-->
                        <div id="mainMenu-trigger">
                            <button class="lines-button x"> <span class="lines"></span> </button>
                        </div>
                        <!--end: Navigation Responsive Trigger-->

                        <!--Navigation-->
                        <div id="mainMenu">
                            <div class="container">
                                <nav>
                                    <ul>
                                        <?php
                                        $white_list = ["home", "races", "results", "faq", "about", "contact"];
                                        foreach ($this->session->static_pages as $key => $page) {
                                            if (!in_array($key, $white_list)) {
                                                continue;
                                            }
                                            if (isset($page['sub-menu'])) {
                                                $d_cl="dropdown";
                                            } else {
                                                $d_cl = "";
                                            }
                                            echo "<li class='$d_cl'><a href='$page[loc]'>$page[display]</a>";
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
                                            echo "<li><a href='/logout'>Logout</a></li>";
                                        } else {
                                            echo "<li><a href='/login/'>Login</a></li>";
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