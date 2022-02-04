<?php
if (!isset($page_title)) {
  $page_title = "RoadRunning.co.za - Run without being chased";
} else {
  //    $page_title=$page_title." - RoadRunning.co.za";
}
if (!isset($meta_description)) {
  $meta_description = "Listing Road Running races all over South Africa, presented in a modern, uniform manner";
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
  <title><?= $page_title; ?></title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="Johan Havenga" />
  <meta name="description" content="<?= $meta_description; ?>">
  <meta name="robots" content="<?= $meta_robots; ?>" />

  <!--        <link href="<?= base_url('assets/css/plugins.css'); ?>" rel="stylesheet" media="screen">
        <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet" media="screen">
        <link href="<?= base_url('assets/css/responsive.css'); ?>" rel="stylesheet" media="screen"> -->

  <!-- critical path css -->
  <link href="<?= base_url('assets/css/combined_min.css'); ?>" rel="stylesheet preload" type="text/css">
  <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />

  <?php
  if (isset($css_to_load)) :
    foreach ($css_to_load as $css_link) :
      echo "<link href='$css_link' rel='stylesheet' type='text/css' />";
    endforeach;
  endif;
  ?>

  <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/favicon/apple-icon-57x57.png'); ?>">
  <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/favicon/apple-icon-60x60.png'); ?>">
  <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/favicon/apple-icon-72x72.png'); ?>">
  <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/favicon/apple-icon-76x76.png'); ?>">
  <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/favicon/apple-icon-114x114.png'); ?>">
  <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/favicon/apple-icon-120x120.png'); ?>">
  <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/favicon/apple-icon-144x144.png'); ?>">
  <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/favicon/apple-icon-152x152.png'); ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/favicon/apple-icon-180x180.png'); ?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/favicon/android-icon-192x192.png'); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/favicon/favicon-32x32.png'); ?>">
  <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/favicon/favicon-96x96.png'); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/favicon/favicon-16x16.png'); ?>">
  <link rel="stylesheet" href="https://cdnjs.Cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="manifest" href="<?= base_url('assets/favicon/manifest.json'); ?>">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?= base_url('assets/favicon/ms-icon-144x144.png'); ?>">
  <meta name="theme-color" content="#ffffff">

  <!-- font preloads -->
  <link rel="preload" href="<?= base_url('assets/webfonts/fa-brands-400.woff2'); ?>" as="font" type="font/woff2" crossorigin="anonymous">
  <link rel="preload" href="<?= base_url('assets/webfonts/fa-regular-400.woff2'); ?>" as="font" type="font/woff2" crossorigin="anonymous">
  <link rel="preload" href="<?= base_url('assets/webfonts/inspiro-icons.ttf?mxrs1k'); ?>" as="font" type="font/woff2" crossorigin="anonymous">

  <!-- auto ads -->
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script async>
    (adsbygoogle = window.adsbygoogle || []).push({
      google_ad_client: "ca-pub-8912238222537097",
      enable_page_level_ads: true
    });
  </script>
  <!-- auto ads end -->
  <?php
  if (isset($structured_data)) :
    echo $structured_data;
  endif;
  ?>
</head>

<body>
  <!-- Analytics -->
  <script async>
    (function(i, s, o, g, r, a, m) {
      i['GoogleAnalyticsObject'] = r;
      i[r] = i[r] || function() {
        (i[r].q = i[r].q || []).push(arguments)
      }, i[r].l = 1 * new Date();
      a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
      a.async = 1;
      a.src = g;
      m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-85900175-2', 'auto');
    ga('send', 'pageview');
  </script>

  <?php
  if ($this->ini_array['enviroment']['server'] != "production") {
  ?>
    <div role="alert" class="alert alert-warning">
      <i class="fa fa-check-circle" aria-hidden="true"></i> <b>DEVELOPMENT</b> - You are wokring on the dev site
    </div>
  <?php
  }
  ?>
  <div class="body-inner">

    <!-- Topbar -->
    <div id="topbar" class="<?= $topbar_class; ?> topbar-fullwidth d-none d-xl-block d-lg-block">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="topbar-dropdown">
              <?php
              if ($this->session->user['logged_in']) {
                echo "<a class='title' href='" . base_url("user") . "'><i class='fa fa-user'></i> " . $logged_in_user['user_name'] . "</a>";
              } else {
                echo "<a class='title' href='" . base_url('login') . "'><i class='fa fa-user'></i> Login</a>";
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

    <!--            <div class='alert alert-info' role='alert'>
                      <i class='fa fa-info-circle' aria-hidden='true'></i>
                      <b>COVID-19:</b> All events has been cancelled or postponed until further notice. Please add yourself to the mailing list of a particular event to receive updates
                  </div>-->

    <!-- Header -->
    <header id="header" data-fullwidth="true" <?= $header_vals; ?>>
      <div class="header-inner">
        <div class="container">
          <!--Logo-->
          <div id="logo">
            <a href="<?= base_url(); ?>" class="logo" data-src-dark="<?= base_url('assets/img/roadrunning_logo_dark_80.svg'); ?>">
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
            echo form_hidden("when", get_cookie("search_when_pref"));
            echo form_hidden("show", get_cookie("listing_pref"));
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
                  echo "<p><a href='" . base_url('search') . "?query=" . $search['search_term'] . "' id='search_" . $search_id . "'>" . $search['search_term'] . " </a></p>";
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
                  <a href="#"><i class="icon-user11"></i></a>
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
                          <a href="<?= base_url('user'); ?>" class="btn btn-xs">Dashboard</a>
                          <!--<a data-href="" data-toggle="modal" data-target="#confirm-logout" class="btn btn-xs btn-light">Logout</a>-->
                          <button class="btn btn-xs btn-light" data-href="" data-toggle="modal" data-target="#confirm-logout">Logout</button>
                        </div>
                      <?php
                      } else {
                      ?>
                        <div class="cart-item">
                          You are not currently logged in
                        </div>
                        <hr>
                        <div class="cart-buttons" style="padding-right: 0;">
                          <a href="<?= base_url('login'); ?>" class="btn btn-xs m-l-0">Login</a>
                          <a href="<?= base_url('region/switch'); ?>" class="btn btn-xs btn-light m-l-0 m-r-0">Region Select</a>
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
          <div id="mainMenu" class="menu-lines">
            <div class="container">
              <nav style="margin-right: 10px;">
                <ul>
                  <?php
                  // get url bits
                  $url_bits = explode("/", uri_string());
                  // set whitelist to show in top menu
                  $white_list = ["races", "results", "resources", "about", "contact"];
                  foreach ($this->session->static_pages as $key => $page) {
                    $i_cl = $d_cl = null;
                    if (!in_array($key, $white_list)) {
                      continue;
                    }
                    //if top menu item 
                    if ($page['loc'] == current_url()) {
                      $i_cl = "current";
                    }
                    // else if there is a sub-menu check in sub-menu for url match
                    if (isset($page['sub-menu'])) {
                      $d_cl = "dropdown";
                      foreach ($page['sub-menu'] as $sub_page) {
                        if ($sub_page['loc'] == current_url()) {
                          $i_cl = "current";
                        }
                      }
                    }
                    // set link for the "contact" link in the main menu to the organisers if on an event page
                    if (($url_bits[0] == "event") && ($page['display'] == "Contact")) {
                      $page['loc'] = base_url("event/" . $slug . "/contact");
                    }
                    echo "<li class='$d_cl $i_cl'><a href='$page[loc]'>$page[display]</a>";
                    if (isset($page['sub-menu'])) {
                      echo '<ul class="dropdown-menu">';
                      foreach ($page['sub-menu'] as $sub_page) {
                        // exception for race contact
                        if (($url_bits[0] == "event") && ($sub_page['display'] == "Contact Us")) {
                          echo "<li><a href='" . base_url("event/" . $slug . "/contact") . "'>Contact Organisers</a></li>";
                          $sub_page['display'] = "Contact Web Admin";
                        }

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
                    $i_cl = $menu_html = null;
                    foreach ($user_menu as $key => $menu_item) {
                      if ($menu_item['loc'] == current_url()) {
                        $i_cl = "current";
                      }
                      if ($key == "logout") {
                        $menu_html .= '<li><a href="" data-href="" data-toggle="modal" data-target="#confirm-logout">' . $menu_item['display'] . '</a>';
                      } else {
                        $menu_html .= "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a></li>";
                      }
                    }
                    // IF LOGGED IN USER IS ADMIN
                    if ($logged_in_user) {
                      if (in_array(1, $logged_in_user['role_list'])) {
                        $menu_html .= "<li><a href='" . base_url("mailer") . "'>Mailer</a></li>";
                        $menu_html .= "<li><a href='" . base_url("admin") . "' target='_blank'>Admin Dashboard</a></li>";
                      }
                    }
                  ?>
                    <li class="dropdown <?= $i_cl; ?>">
                      <a href="/user"><?= $this->session->user['user_name']; ?></a>
                      <ul class="dropdown-menu">
                        <?= $menu_html; ?>
                      </ul>
                    </li>
                  <?php
                  } else {
                    echo "<li><a href='" . base_url('login') . "'>Login</a></li>";
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
    <?php
    //                wts(current_url());
    //                wts(uri_string());
    //                wts($user_menu,1);
    ?>