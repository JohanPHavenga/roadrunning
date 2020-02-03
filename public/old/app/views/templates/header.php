<?php
if (!isset($title)) {
    $title = "RoadRunning.co.za - Run without being chased";
}
if (!isset($meta_description)) {
    $meta_description = "Listing road running events in and around the Cape Town area. We are committed to having accurate information loaded on the site as soon as it becomes available from the race organisers.";
}
if (!isset($keywords)) {
    $keywords = "Road, Running, Event, Race, Races, List";
}
if (!isset($meta_robots)) {
    $meta_robots = "index";
}
?>

<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />        
        <title><?= $title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="description" content="<?= $meta_description; ?>" />
        <meta name="keywords" content="<?= $keywords; ?>" />
        <meta name="author" content="Johan Havenga" />
        <meta name="robots" content="<?= $meta_robots; ?>" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->

        <!--<style>@font-face{font-family:"Roboto Condensed";font-style:normal;font-weight:300;src:local('Roboto Condensed Light'),local('RobotoCondensed-Light'),url(../fonts/roboto-condensed/RobotoCondensed-Light.ttf) format('truetype')}@font-face{font-family:"Roboto Condensed";font-style:italic;font-weight:300;src:local('Roboto Condensed Light Italic'),local('RobotoCondensed-LightItalic'),url(../fonts/roboto-condensed/RobotoCondensed-LightItalic.ttf) format('truetype')}@font-face{font-family:"Roboto Condensed";font-style:normal;font-weight:400;src:local('Roboto Condensed Regular'),local('RobotoCondensed-Regular'),url(../fonts/roboto-condensed/RobotoCondensed-Regular.ttf) format('truetype')}@font-face{font-family:"Roboto Condensed";font-style:italic;font-weight:400;src:local('Roboto Condensed Italic'),local('RobotoCondensed-Italic'),url(../fonts/roboto-condensed/RobotoCondensed-Italic.ttf) format('truetype')}@font-face{font-family:"Roboto Condensed";font-style:normal;font-weight:700;src:local('Roboto Condensed Bold'),local('RobotoCondensed-Bold'),url(../fonts/roboto-condensed/RobotoCondensed-Bold.ttf) format('truetype')}@font-face{font-family:"Roboto Condensed";font-style:italic;font-weight:700;src:local('Roboto Condensed Bold Italic'),local('RobotoCondensed-BoldItalic'),url(../fonts/roboto-condensed/RobotoCondensed-BoldItalic.ttf) format('truetype')}@charset "UTF-8";img{border:0}body{margin:0}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}header,nav{display:block}a{background-color:transparent}b{font-weight:700}img{vertical-align:middle}button,input{color:inherit;font:inherit;margin:0}button{overflow:visible}button{text-transform:none}button{-webkit-appearance:button}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}table{border-collapse:collapse;border-spacing:0}td{padding:0}.btn,.form-control{background-image:none}body{background-color:#fff}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-size:10px}body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:1.42857;color:#333}button,input{font-family:inherit;font-size:inherit;line-height:inherit}a{color:#337ab7;text-decoration:none}h3,h4{font-family:inherit;font-weight:500;line-height:1.1;color:inherit}h3{margin-top:20px;margin-bottom:10px}h4{margin-top:10px;margin-bottom:10px}h3{font-size:24px}h4{font-size:18px}p{margin:0 0 10px}table{background-color:transparent}ul{margin-top:0}ul{margin-bottom:10px}@media (min-width:768px){.container{width:750px}}.clearfix:after,.container:after,.panel-body:after,.row:after{clear:both}.container:after,.container:before,.row:after,.row:before{display:table;content:" "}.container{margin-right:auto;margin-left:auto}.container{padding-left:15px;padding-right:15px}@media (min-width:992px){.container{width:970px}}@media (min-width:1200px){.container{width:1170px}}.row{margin-left:-15px;margin-right:-15px}.col-md-12,.col-sm-3,.col-sm-9{position:relative;min-height:1px;padding-left:15px;padding-right:15px}@media (min-width:768px){.col-sm-3,.col-sm-9{float:left}.col-sm-3{width:25%}.col-sm-9{width:75%}}@media (min-width:992px){.col-md-12{float:left}.col-md-12{width:100%}}.form-control{font-size:14px;line-height:1.42857;color:#555;display:block}.form-control{width:100%;height:34px;padding:6px 12px;background-color:#fff;border:1px solid #c2cad8;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.form-control::-moz-placeholder{color:#999;opacity:1}.form-control:-ms-input-placeholder{color:#999}.form-control::-webkit-input-placeholder{color:#999}.form-control::-ms-expand{border:0;background-color:transparent}.btn{display:inline-block;margin-bottom:0;font-weight:400;text-align:center;vertical-align:middle;touch-action:manipulation;border:1px solid transparent;white-space:nowrap;padding:6px 12px;font-size:14px;line-height:1.42857;border-radius:4px}.collapse{display:none}.collapse.in{display:block}.nav{margin-bottom:0;padding-left:0;list-style:none}.nav:after,.nav:before{content:" ";display:table}.nav>li,.nav>li>a{display:block;position:relative}.nav:after{clear:both}.nav>li>a{padding:10px 15px}.nav-tabs{border-bottom:1px solid #ddd}.nav-tabs>li{float:left;margin-bottom:-1px}.nav-tabs>li>a{margin-right:2px;line-height:1.42857;border:1px solid transparent;border-radius:4px 4px 0 0}.nav-tabs>li.active>a{color:#555;background-color:#fff;border:1px solid #ddd;border-bottom-color:transparent}.tab-content>.tab-pane{display:none}.tab-content>.active{display:block}.navbar-nav{margin:7.5px -15px}.navbar-nav>li>a{padding-top:10px;padding-bottom:10px;line-height:20px}@media (min-width:768px){.navbar-nav{float:left;margin:0}.navbar-nav>li{float:left}.navbar-nav>li>a{padding-top:15px;padding-bottom:15px}}.badge{font-weight:700;line-height:1;white-space:nowrap;text-align:center}.badge{display:inline-block;min-width:10px;padding:3px 7px;font-size:12px;color:#fff;vertical-align:middle;background-color:#777;border-radius:10px}.panel-title,.panel-title>a{color:inherit}.panel{margin-bottom:20px;background-color:#fff;border:1px solid transparent;border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}.panel-title{margin-bottom:0}.panel-body{padding:15px}.panel-body:after,.panel-body:before{content:" ";display:table}.panel-heading{padding:10px 15px;border-bottom:1px solid transparent;border-top-right-radius:3px;border-top-left-radius:3px}.panel-title{margin-top:0;font-size:16px}.panel-group .panel-heading{border-bottom:0}.panel-group{margin-bottom:20px}.panel-group .panel{margin-bottom:0;border-radius:4px}.panel-group .panel+.panel{margin-top:5px}.panel-group .panel-heading+.panel-collapse>.panel-body{border-top:1px solid #ddd}.clearfix:after,.clearfix:before{content:" ";display:table}.hide{display:none!important}.visible-xs{display:none!important}@-ms-viewport{width:device-width}@media (max-width:767px){.visible-xs{display:block!important}}@media (max-width:767px){.hidden-xs{display:none!important}}h3{color:#3f444a;margin:10px 0}body{color:#5c6873;font-family:"Roboto Condensed",sans-serif;padding:0!important;margin:0!important;font-weight:300;font-size:17px}a{outline:0!important}.c-link{outline:0}@-ms-viewport{width:device-width}@-o-viewport{width:device-width}@viewport{width:device-width}@-ms-viewport{width:auto!important}h4{color:#3f444a;font-size:16px;margin:8px 0}h3{font-size:18px}.c-overflow-hide{overflow:hidden}.c-link{background:0;border:0}.c-margin-b-30{margin-bottom:30px}.c-font-center{text-align:center}.c-font-sbold{font-weight:500!important}.c-font-bold{font-weight:600!important}.c-font-uppercase{text-transform:uppercase}.c-font-34{font-size:34px}.c-font-green-2{color:#5dc09c!important}.c-font-yellow{color:#FF6B57!important}.c-font-yellow-2{color:#c5bf66!important}.c-bg-white{background-color:#FFF!important}.c-bg-green-2{background-color:#5dc09c!important}.c-bg-red-2{background-color:#e7505a!important}.c-bg-red-3{background-color:#d05163!important}.c-bg-yellow{background-color:#FF6B57!important}.c-bg-yellow-1{background-color:#c8d046!important}.c-bg-blue{background-color:#3498DB!important}.c-bg-purple{background-color:#b771b0!important}.c-layout-page:after,.c-layout-page:before{content:" ";display:table}.c-layout-page:after{clear:both}.c-layout-header .c-navbar:after,.c-layout-header .c-navbar:before{content:" ";display:table}.c-layout-header .c-navbar:after{clear:both}.c-layout-header .c-brand{display:inline-block}.c-layout-header .c-brand.c-pull-left{float:left}.c-layout-header .c-brand>.c-hor-nav-toggler,.c-layout-header .c-brand>.c-search-toggler{display:none}.c-layout-header .c-brand .c-desktop-logo{display:block}.c-layout-header .c-brand .c-desktop-logo-inverse{display:none}.c-layout-header .c-quick-search{display:none;padding:0;margin:0;position:relative}.c-layout-header .c-quick-search:after,.c-layout-header .c-quick-search:before{content:" ";display:table}.c-layout-header .c-quick-search:after{clear:both}.c-layout-header .c-quick-search>.form-control{display:block;font-size:22px;font-weight:400;border:0;background:0 0;box-shadow:none;-webkit-border-radius:0;-moz-border-radius:0;-ms-border-radius:0;-o-border-radius:0;border-radius:0}.c-layout-header .c-quick-search>.form-control::-ms-clear{display:none}.c-layout-header .c-quick-search>span{display:inline-block;position:absolute;font-size:36px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif}@media (min-width:992px){.c-layout-header .c-navbar{line-height:0}.c-layout-header .c-navbar>.container{position:relative}.c-layout-header .c-brand{margin:40px 0 37px}.c-layout-header .c-brand .c-desktop-logo-inverse{display:none}.c-layout-header .c-brand .c-desktop-logo{display:inline-block}.c-layout-header .c-brand .c-mobile-logo{display:none}.c-layout-header .c-quick-search>.form-control{padding:10px 0;height:100px}.c-layout-header .c-quick-search>span{top:50px;right:-2px}.c-layout-header-fixed .c-layout-header{top:0;position:fixed;z-index:9995;width:100%}.c-layout-header-fixed .c-layout-page{margin-top:100px}}@media (max-width:991px){.c-layout-header{margin:0}.c-layout-header .c-navbar{height:65px}.c-layout-header .c-navbar>.container{position:relative;padding:0}.c-layout-header .c-brand{float:none!important;display:block;margin:20px 15px 21px}.c-layout-header .c-brand .c-desktop-logo-inverse{display:none}.c-layout-header .c-brand>.c-hor-nav-toggler{display:inline-block;background:0 0;border:0;margin:-1px 0 0;padding:0;float:right;outline:0}.c-layout-header .c-brand>.c-hor-nav-toggler>.c-line{width:15px;display:block;height:2px;padding:0;margin:3px 0}.c-layout-header .c-brand>.c-hor-nav-toggler>.c-line:first-child{margin-top:4px}.c-layout-header .c-brand>.c-search-toggler{background:0 0;border:0;margin:0 25px 0 0;padding:0;float:right;outline:0;height:20px;width:20px}.c-layout-header .c-brand>.c-search-toggler>i{font-size:14px}.c-layout-header .c-brand>.c-search-toggler{display:inline-block}.c-layout-header .c-brand .c-desktop-logo{display:none}.c-layout-header .c-mega-menu{line-height:0}.c-layout-header .c-quick-search{position:relative}.c-layout-header .c-quick-search>.form-control{font-size:20px;padding:10px 15px;height:65px}.c-layout-header .c-quick-search>span{font-size:36px;right:20px;top:9px}.c-layout-header-fixed.c-layout-header-mobile-fixed .c-layout-header{top:0;position:fixed;z-index:9995;width:100%}.c-layout-header-fixed.c-layout-header-mobile-fixed .c-layout-page{margin-top:65px}}@media (min-width:992px){.c-layout-header .c-navbar .c-mega-menu{display:inline-block;padding:0;margin:0;position:static;line-height:0}.c-layout-header .c-navbar .c-mega-menu.c-pull-right{float:right}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav{line-height:0;background:0 0;position:static;margin:0;padding:0}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li{padding:0;margin:0;float:left;position:static}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-link{letter-spacing:1px;font-style:normal;padding:41px 15px 39px;min-height:100px;font-size:17px}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-btn-icon{background:0 0;outline:0;margin:24px 5px 20px}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-btn-icon>i{position:relative;top:0;font-size:14px;height:14px}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li.c-active>.c-link{background:0 0}.c-layout-header .c-navbar .c-mega-menu.c-fonts-uppercase>.nav.navbar-nav>li>.c-link{font-size:15px;text-transform:uppercase}.c-layout-header .c-navbar .c-mega-menu.c-fonts-bold>.nav.navbar-nav>li>.c-link{font-weight:600}}@media (max-width:991px){.c-layout-header .c-navbar .c-mega-menu{margin:0;padding:5px 10px;display:none;width:100%;float:none!important;overflow-x:hidden}.c-layout-header .c-navbar .c-mega-menu.c-pull-right{float:right}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav{margin:0 -15px!important;float:none}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li{display:block;float:none}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-link{padding:10px 20px;font-size:15px;letter-spacing:1px}.c-layout-header .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-search-toggler{display:none}.c-layout-header .c-navbar .c-mega-menu.c-fonts-uppercase>.nav.navbar-nav>li>.c-link{font-size:13px;text-transform:uppercase}.c-layout-header .c-navbar .c-mega-menu.c-fonts-bold>.nav.navbar-nav>li>.c-link{font-weight:400}}@media (max-width:991px){.c-layout-header.c-layout-header-default-mobile{background:#fff;border-bottom:0}.c-layout-header.c-layout-header-default-mobile .c-brand>.c-hor-nav-toggler{position:relative;top:-1px;display:inline-block;background:#f5f6f8;padding:5px 8px 6px}.c-layout-header.c-layout-header-default-mobile .c-brand>.c-hor-nav-toggler>.c-line{background:#bac3cd}.c-layout-header.c-layout-header-default-mobile .c-brand>.c-search-toggler>i{color:#9facba}.c-layout-header.c-layout-header-default-mobile .c-quick-search>.form-control{color:#677581}.c-layout-header.c-layout-header-default-mobile .c-quick-search>.form-control::-moz-placeholder{color:#818e9a}.c-layout-header.c-layout-header-default-mobile .c-quick-search>.form-control:-moz-placeholder{color:#818e9a}.c-layout-header.c-layout-header-default-mobile .c-quick-search>.form-control:-ms-input-placeholder{color:#818e9a}.c-layout-header.c-layout-header-default-mobile .c-quick-search>.form-control::-webkit-input-placeholder{color:#818e9a}.c-layout-header.c-layout-header-default-mobile .c-quick-search>span{color:#818e9a}.c-layout-header-fixed.c-layout-header-mobile-fixed .c-layout-header{border-bottom:0;box-shadow:0}}@media (min-width:992px){.c-layout-header.c-layout-header-4 .c-navbar .c-mega-menu>.nav.navbar-nav>li.c-active,.c-layout-header.c-layout-header-4 .c-navbar .c-mega-menu>.nav.navbar-nav>li.c-active>a:not(.btn),.c-layout-header.c-layout-header-4 .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-btn-icon,.c-layout-header.c-layout-header-4 .c-navbar .c-mega-menu>.nav.navbar-nav>li>.c-link{color:#3a3f45}.c-layout-header.c-layout-header-4{background:0 0;border-bottom:0}.c-layout-header.c-layout-header-4 .c-navbar{background:0 0}.c-layout-header.c-layout-header-4 .c-quick-search>.form-control{color:#69727c}.c-layout-header.c-layout-header-4 .c-quick-search>.form-control::-moz-placeholder{color:#828b96}.c-layout-header.c-layout-header-4 .c-quick-search>.form-control:-moz-placeholder{color:#828b96}.c-layout-header.c-layout-header-4 .c-quick-search>.form-control:-ms-input-placeholder{color:#828b96}.c-layout-header.c-layout-header-4 .c-quick-search>.form-control::-webkit-input-placeholder{color:#828b96}.c-layout-header.c-layout-header-4 .c-quick-search>span{color:#828b96}}@media (max-width:991px){.c-layout-header .c-navbar .c-mega-menu.c-mega-menu-dark-mobile{background:#394048}.c-layout-header .c-navbar .c-mega-menu.c-mega-menu-dark-mobile>.nav.navbar-nav>li>.c-link{border-bottom:1px solid #404851;color:#ebedf2}.c-layout-header .c-navbar .c-mega-menu.c-mega-menu-dark-mobile>.nav.navbar-nav>li.c-active>.c-link{color:#ff6b57;background:0 0}}.c-layout-header:after,.c-layout-header:before{content:" ";display:table}.c-layout-header:after{clear:both}.c-layout-breadcrumbs-1{padding:25px 0;background:#f7fafb}.c-layout-breadcrumbs-1:after,.c-layout-breadcrumbs-1:before{content:" ";display:table}.c-layout-breadcrumbs-1:after{clear:both}.c-layout-breadcrumbs-1 .c-page-title{display:inline-block}.c-layout-breadcrumbs-1 .c-page-title.c-pull-left{float:left}.c-layout-breadcrumbs-1 .c-page-title h3{color:#000;margin:10px 0 6px;font-weight:500;font-size:18px;letter-spacing:1px}.c-layout-breadcrumbs-1 .c-page-breadcrumbs{display:inline-block;padding:0;margin:0;list-style-type:none}.c-layout-breadcrumbs-1 .c-page-breadcrumbs.c-pull-right{float:right}.c-layout-breadcrumbs-1 .c-page-breadcrumbs>li{display:inline-block;margin:0;padding:8px 4px}.c-layout-breadcrumbs-1 .c-page-breadcrumbs>li,.c-layout-breadcrumbs-1 .c-page-breadcrumbs>li>a{color:#7f8c97;font-size:16px;font-weight:400}.c-layout-breadcrumbs-1 .c-page-breadcrumbs.c-pull-right>li:last-child{padding-right:0}@media (max-width:991px){.c-layout-breadcrumbs-1{padding:10px 0}.c-layout-breadcrumbs-1 .c-page-title>h3{margin:6px 15px 6px 0}.c-layout-breadcrumbs-1 .c-page-breadcrumbs{float:left!important;text-align:left;clear:both}.c-layout-breadcrumbs-1 .c-page-breadcrumbs>li{padding:6px 4px}.c-layout-breadcrumbs-1 .c-page-breadcrumbs>li:first-child{padding-left:0}}@media (max-width:767px){.c-layout-breadcrumbs-1 .c-page-title{display:block;float:left;text-align:left}.c-layout-breadcrumbs-1 .c-page-title:after,.c-layout-breadcrumbs-1 .c-page-title:before{content:" ";display:table}.c-layout-breadcrumbs-1 .c-page-title:after{clear:both}.c-layout-breadcrumbs-1 .c-page-breadcrumbs{display:block}}.c-layout-go2top{display:inline-block;position:fixed;bottom:20px;right:10px}.c-layout-go2top>i{opacity:.5;filter:alphaopacity=50;color:#89939e;font-size:38px;font-weight:300}.c-content-box.c-size-sm{padding:30px 0}.c-content-box.c-size-md{padding:60px 0}.c-content-box.c-no-bottom-padding{padding-bottom:0}.c-content-box.c-overflow-hide{overflow:hidden}@media (max-width:991px){.c-content-box.c-size-sm{padding:20px 0}.c-content-box.c-size-md{padding:30px 0}}.c-content-title-1>.c-line-center{width:30px;height:3px;background-color:#32c5d2;margin:0 auto 30px}.c-content-title-1>.c-line-left{width:30px;height:3px;background-color:#32c5d2;margin:0 0 30px}.c-content-title-1>h3{font-size:28px;color:#3f444a;font-weight:500;margin:0 0 30px}.c-content-title-1>h3.c-font-uppercase{font-size:30px}.c-content-title-1.c-title-md>.c-line-left{margin-bottom:20px}.c-content-accordion-1 .panel-group{margin-bottom:0}.c-content-accordion-1 .panel{padding:0;box-shadow:none;border-radius:0;border:0;margin-bottom:10px}.c-content-accordion-1 .panel:last-child{margin-bottom:0}.c-content-accordion-1 .panel>.panel-heading{padding:0;color:#fff}.c-content-accordion-1 .panel>.panel-heading>.panel-title{padding:0;margin:0}.c-content-accordion-1 .panel>.panel-heading>.panel-title>a{color:#fff;display:block;padding:30px 30px 25px;border-radius:0;box-shadow:none}.c-content-accordion-1 .panel>.panel-collapse>.panel-body{border-top:none;padding:0 30px 35px;color:#fff}.c-page-faq-2 .c-faq-tabs{border:1px solid #ddd}.c-page-faq-2 .c-faq-tabs>li{float:none}.c-page-faq-2 .c-faq-tabs>li>a{border:none}.c-page-faq-2 .c-faq-tabs>li.active{background-color:#eee}.c-page-faq-2 .c-faq-tabs>li.active>a{background-color:transparent;color:#32c5d2}.c-page-faq-2 .c-content-accordion-1 .panel{border-bottom:1px solid;border-color:#eee;margin:0}.c-page-faq-2 .c-content-accordion-1 .panel:last-child{border:none}.c-page-faq-2 .c-content-accordion-1 .panel>.panel-heading>.panel-title>a{color:#3f444a;padding:10px;font-size:19px}.c-page-faq-2 .c-content-accordion-1 .panel>.panel-collapse>.panel-body{color:#3f444a;padding-bottom:20px;padding-left:35px;font-size:15px}@media (max-width:991px){.c-page-faq-2 .c-content-title-1{margin-top:40px}}a{color:#3f444a}.c-theme-nav li.c-active{color:#32c5d2!important}.c-theme-nav li.c-active>a:not(.btn){color:#32c5d2!important}.c-theme-bg{background:#32c5d2!important}.c-theme-btn.btn{color:#fff;background:#32c5d2;border-color:#32c5d2}.c-theme-btn.c-btn-border-2x{border-color:#32c5d2;border-width:2px;color:#32c5d2;background:none;border-color:#32c5d2}@font-face{font-family:'FontAwesome';src:url(../fonts/fontawesome-webfont.eot?v=4.4.0);src:url(../fonts/fontawesome-webfont.eot?#iefix&v=4.4.0) format('embedded-opentype'),url(../fonts/fontawesome-webfont.woff2?v=4.4.0) format('woff2'),url(../fonts/fontawesome-webfont.woff?v=4.4.0) format('woff'),url(../fonts/fontawesome-webfont.ttf?v=4.4.0) format('truetype'),url(../fonts/fontawesome-webfont.svg?v=4.4.0#fontawesomeregular) format('svg');font-weight:400;font-style:normal}.fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.fa-search:before{content:"\f002"}.fa-check-square:before{content:"\f14a"}@font-face{font-family:Simple-Line-Icons;src:url(../css/fonts/Simple-Line-Icons.eot);src:url(../css/fonts/Simple-Line-Icons.eot?#iefix) format('embedded-opentype'),url(../css/fonts/Simple-Line-Icons.woff) format('woff'),url(../css/fonts/Simple-Line-Icons.ttf) format('truetype'),url(../css/fonts/Simple-Line-Icons.svg#Simple-Line-Icons) format('svg');font-weight:400;font-style:normal}@media screen and (-webkit-min-device-pixel-ratio:0){@font-face{font-family:Simple-Line-Icons;src:url(../css/fonts/Simple-Line-Icons.svg#Simple-Line-Icons) format('svg')}}.icon-arrow-up{font-family:Simple-Line-Icons;speak:none;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased}.icon-arrow-up:before{content:"\e078"}@charset "UTF-8";@charset "UTF-8";.badge{font-weight:400;padding:4px 7px}.form-control{font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;box-shadow:none;outline:0;font-weight:300;background:#fff;border-color:#d0d7de}.btn{outline:0!important;padding:6px 20px 4px;font-size:16px}.btn.c-btn-border-2x{border-width:2px;padding:6px 20px 4px}.c-btn-square{-webkit-border-radius:0;-moz-border-radius:0;-ms-border-radius:0;-o-border-radius:0;border-radius:0}body{font-size:19px}table.accordian td:first-child{padding:0 8px 0 0}.c-layout-breadcrumbs-1{padding:10px 0}td.badges{width:120px;text-align:right}@media (min-width:992px){.c-layout-header .c-brand{margin-top:32px}}@media (max-width:991px){.c-layout-header .c-brand{margin-top:19px}.c-page-faq-2 .c-content-title-1{margin-top:0}}@media (max-width:768px){.c-content-title-1>h3.c-font-uppercase{font-size:24px}.c-page-faq-2 .c-content-accordion-1 .panel>.panel-heading>.panel-title>a{font-size:16px;padding:8px 0}.c-page-faq-2 .c-content-accordion-1 .panel>.panel-collapse>.panel-body{padding-left:23px}}@media (max-width:480px){td.badges{display:none}}</style>-->

        <?php
        // load extra CSS files from controller
        if (isset($css_to_load)) :
            foreach ($css_to_load as $row):
                $css_link = base_url($row);
                echo "<link href='$css_link' rel='stylesheet'>";
            endforeach;
        endif;
        ?>

        <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('img/favicon/apple-icon-57x57.png'); ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('img/favicon/apple-icon-60x60.png'); ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('img/favicon/apple-icon-72x72.png'); ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('img/favicon/apple-icon-76x76.png'); ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('img/favicon/apple-icon-114x114.png'); ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('img/favicon/apple-icon-120x120.png'); ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('img/favicon/apple-icon-144x144.png'); ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('img/favicon/apple-icon-152x152.png'); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('img/favicon/apple-icon-180x180.png'); ?>">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= base_url('img/favicon/android-icon-192x192.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('img/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('img/favicon/favicon-96x96.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('img/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?= base_url('img/favicon/manifest.json'); ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= base_url('img/favicon/ms-icon-144x144.png'); ?>">
        <meta name="theme-color" content="#ffffff">

        <!-- GOOGLE ADS -->
        <!--<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
        <!-- auto ads -->
<!--        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-8912238222537097",
                enable_page_level_ads: true
            });
        </script>-->
        <!-- auto ads end -->
        <!-- reCaptcha -->
        <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
        <?php
// load extra CSS files from controller
        if (isset($structured_data)) :
            echo $structured_data;
        endif;
        ?>
        <script src="<?= base_url('scripts/scg_smooth_scrolling.js'); ?>" type="text/javascript"></script>
    </head>
    <body class="c-layout-header-fixed c-layout-header-mobile-fixed">
        <!-- Analytics -->
<!--        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
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

        </script>-->

        <!--        <div class="c-cookies-bar c-cookies-bar-1 c-cookies-bar-top c-bg-dark wow animate fadeInDown" id="xNOsjtKgLQiH"
                     data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: fadeInDown; opacity: 1;">
                    <div class="c-cookies-bar-container">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="c-cookies-bar-content c-font-white">
                                    Our website is made possible by displaying online advertisements to our visitors.
                                    Please consider supporting us by disabling your ad blocker.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="c-cookies-bar-btn">
                                    <a class="c-cookies-bar-close btn c-theme-btn c-btn-square c-btn-bold" href="javascript:;">Got it!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

        <!--        <div id="xNOsjtKgLQiH">
                    Our website is made possible by displaying online advertisements to our visitors.<br>
                    Please consider supporting us by disabling your ad blocker.
                </div>-->

<!--        <script src="/scripts/adblock.js" type="text/javascript"></script>
        <script type="text/javascript">

          if (!document.getElementById('YbRgKztoCxru')) {
              document.getElementById('xNOsjtKgLQiH').style.display = 'block';
          }

        </script>-->

        <!-- BEGIN: LAYOUT/HEADERS/HEADER-1 -->
        <!-- BEGIN: HEADER -->
        <header class="c-layout-header c-layout-header-4 c-layout-header-default-mobile" data-minimize-offset="80">
            <div class="c-navbar">
                <div class="container">
                    <div class="c-navbar-wrapper clearfix">
                        <div class="c-brand c-pull-left">
                            <a href="<?= base_url(); ?>" class="c-logo ">
                                <?php $img_url = "img/logo_37.png"; ?>
                                <img src="<?= base_url($img_url); ?>" alt="RoadRunning.co.za" class="c-desktop-logo">
                                <img src="<?= base_url($img_url); ?>" alt="RoadRunning.co.za" class="c-desktop-logo-inverse">
                                <img src="<?= base_url('img/logo_27.png'); ?>" alt="RoadRunning.co.za" class="c-mobile-logo">
                            </a>
                            <button class="c-hor-nav-toggler" type="button" data-target=".c-mega-menu">
                                <span class="c-line"></span>
                                <span class="c-line"></span>
                                <span class="c-line"></span>
                            </button>
                            <button class="c-search-toggler" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>

                        <!-- BEGIN: QUICK SEARCH -->
                        <form class="c-quick-search" action="<?= base_url("search"); ?>">
                            <input type="text" name="query" placeholder="Search for an event" value="" class="form-control" autocomplete="off">
                            <span class="c-theme-link">&times;</span>
                        </form>
                        <!-- END: QUICK SEARCH -->

                        <nav class="c-mega-menu c-pull-right c-mega-menu-dark c-mega-menu-dark-mobile c-fonts-uppercase c-fonts-bold">
                            <ul class="nav navbar-nav c-theme-nav">
                                <?php
                                foreach ($menu_array as $menu_item) {
                                    if ($section == $menu_item['section']) {
                                        $mc = "c-active";
                                    } else {
                                        $mc = "";
                                    }
                                    echo "<li class='$mc'>";
                                    echo "<a href='" . $menu_item['url'] . "' class='c-link dropdown-toggle'>" . $menu_item['text'] . "</a>";
                                    echo "</li>";
                                }
                                ?>
                                <li class="c-search-toggler-wrapper">
                                    <a href="#" class="c-btn-icon c-search-toggler">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- END: HEADER -->
        <!-- END: LAYOUT/HEADERS/HEADER-1 -->

<?php
//echo $this->router->fetch_class();
//echo $this->router->fetch_method();