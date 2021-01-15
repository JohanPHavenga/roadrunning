<?php
if (!isset($title)) {
  $title = "Admin";
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
  <!--<![endif]-->
  <!-- BEGIN HEAD -->

  <head>
    <meta charset="utf-8" />
    <title><?= $title; ?> | RoadRunning.co.za</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />-->
    <link href="<?= base_url('assets/admin/css/open-sans.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <?php
    // load extra CSS files from controller
    if (isset($css_to_load)) :
      foreach ($css_to_load as $row):
        $css_link = base_url($row);
        echo "<link href='$css_link' rel='stylesheet'>";
      endforeach;
    endif;
    ?>
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?= base_url('assets/admin/css/components-rounded.min.css'); ?>" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?= base_url('assets/admin/css/plugins.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?= base_url('assets/admin/css/layout.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/css/theme.min.css'); ?>" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?= base_url('assets/admin/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="<?= base_url('assets/admin/ci-icon.ico'); ?>" />
  </head>
  <!-- END HEAD -->

  <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
      <!-- BEGIN HEADER INNER -->
      <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
          <a href="<?= base_url('admin'); ?>">
            <img src="<?= base_url('assets/admin/img/logo-default.png'); ?>" alt="logo" class="logo-default" /> </a>
          <div class="menu-toggler sidebar-toggler">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
          </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->
        <!-- DOC: Remove "hide" class to enable the page header actions -->
        <div class="page-actions">
          <div class="btn-group">
            <button type="button" class="btn btn-circle btn-outline red dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-plus"></i>&nbsp;
              <span class="hidden-sm hidden-xs"></span>&nbsp;
              <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?= base_url('admin/event/create/add'); ?>">
                  <i class="icon-rocket"></i> Add Event </a>
              </li>
              <li>
                <a href="<?= base_url('admin/edition/create/add'); ?>">
                  <i class="icon-calendar"></i> Add Edition </a>
              </li>
              <li>
                <a href="<?= base_url('admin/result/import'); ?>">
                  <i class="icon-trophy"></i> Upload Results </a>
              </li>
              <li>
                <a href="<?= base_url('admin/import'); ?>">
                  <i class="icon-cloud-upload"></i> Event Import </a>
              </li>
              <!-- <li class="divider"> </li> -->
            </ul>
          </div>
        </div>
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
          <!-- BEGIN HEADER SEARCH BOX -->
          <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
          <form class="search-form search-form-expanded" action="<?= base_url('admin/dashboard/search'); ?>" method="GET">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search..." name="query" autofocus onfocus="this.select();" value="<?= $this->input->get('query'); ?>">
              <span class="input-group-btn">
                <a href="javascript:;" class="btn submit">
                  <i class="icon-magnifier"></i>
                </a>
              </span>
            </div>
          </form>
          <!-- END HEADER SEARCH BOX -->
          <!-- BEGIN TOP NAVIGATION MENU -->
          <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
              <!-- BEGIN USER LOGIN DROPDOWN -->
              <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
              <li class="dropdown dropdown-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                  <span class="username username-hide-on-mobile"> <?= $this->session->user['user_name']; ?> </span>
                  <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                  <li>
                    <a href="<?= base_url('admin/user/profile'); ?>">
                      <i class="icon-user"></i> My Profile </a>
                  </li>
                  <li class="divider"> </li>
                  <li>
                    <a href="<?= base_url('login/logout'); ?>">
                      <i class="icon-key"></i> Log Out </a>
                  </li>
                </ul>
              </li>
              <!-- END USER LOGIN DROPDOWN -->
            </ul>
          </div>
          <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
      </div>
      <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->

    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
      <!-- BEGIN SIDEBAR -->
      <div class="page-sidebar-wrapper">
        <!-- END SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->

        <?php include("sidebar.php"); ?>

      </div>
      <!-- END SIDEBAR -->


      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
          <!-- BEGIN PAGE HEADER-->
          <div class="row">

            <?php
            if (isset($logo)) {
              $col = 10;
            } else {
              $col = 12;
            }
            ?>

            <div class="col-lg-<?= $col; ?>">
              <h1 class="page-title"> <?= (isset($title) ? $title : 'Page Title'); ?></h1>

              <div class="page-bar">
                <?php
                if (isset($crumbs)) {
                  echo "<ul class='page-breadcrumb'>";
                  foreach ($crumbs as $text => $link) {
                    if (empty($link)) {
                      echo "<li><span>$text</span></li>";
                    } else {
                      echo "<li>";
                      if ($text == "Home") {
                        echo '<i class="icon-home"></i> ';
                      }
                      echo "<a href='$link'>$text</a> <i class='fa fa-angle-right'></i></li> ";
                    }
                  }
                  echo "</ul>";
                }

                if (isset($page_action_list)) {
                  ?>
                  <div class="page-toolbar">
                    <div class="btn-group pull-right">
                      <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                        <i class="fa fa-angle-down"></i>
                      </button>
                      <ul class="dropdown-menu pull-right" role="menu">
                        <?php
                        foreach ($page_action_list as $action) {
                          if ($action['name'] == "divider") {
                            echo '<li class="divider"> </li>';
                          } else {
                            echo "<li>";
                            echo "<a href='" . base_url("admin/" . $action['uri']) . "'>";
                            echo "<i class='icon-" . $action['icon'] . "'></i> ";
                            echo $action['name'] . "</a>";
                            echo "</li>";
                          }
                        }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <?php
                }
                ?>
              </div>
            </div>

            <?php
            if (isset($logo)) {
              ?>
              <div class="col-lg-2">
                <img src="<?= base_url("file/edition/" . $slug . "/logo/" . $logo); ?>" style="height: 88px;"/>
              </div>
              <?php
            }
            ?>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <?php
              // alert message on top of the page
              // set flashdata [alert|status]
              if ($this->session->flashdata('alert')) {
                $alert_msg = $this->session->flashdata('alert');
                if (!($this->session->flashdata('status'))) {
                  $status = 'warning';
                } else {
                  $status = $this->session->flashdata('status');
                }
                echo "<div class='note note-$status' role='alert'>$alert_msg</div>";
              }

              // if there was a post, check for validation errors
              if ($_POST) {
                if (validation_errors()) {
                  echo "<div class='note note-danger' role='alert'>";
                  echo validation_errors();
                  echo "</div>";
                }
              }
              ?>
            </div>
          </div>



