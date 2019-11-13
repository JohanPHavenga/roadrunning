<?php
if (!isset($page_title)) {
    $page_title = "Coyote 2.0";
}
if (!isset($description)) {
    $description = "Run without being chased. Again.";
}

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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="Johan Havenga" />
        <meta name="description" content="<?= $description; ?>">

        <title><?= $page_title; ?></title>
        
        <!-- critical path css -->
        <style>
            /*:root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-overflow-style:scrollbar}@-ms-viewport{width:device-width}footer,header,nav,section{display:block}body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}hr{box-sizing:content-box;height:0;overflow:visible}h1,h2,h3,h4{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}ul{margin-top:0;margin-bottom:1rem}ul ul{margin-bottom:0}a{color:#007bff;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects}a:not([href]):not([tabindex]){color:inherit;text-decoration:none}img{vertical-align:middle;border-style:none}button{border-radius:0}button,input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button{text-transform:none}[type=submit],button{-webkit-appearance:button}[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}[type=search]{outline-offset:-2px;-webkit-appearance:none}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h1,h2,h3,h4{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit}h1{font-size:2.5rem}h2{font-size:2rem}h3{font-size:1.75rem}h4{font-size:1.5rem}hr{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0,0,0,.1)}.container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.row{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-9,.col-md-12,.col-md-3,.col-md-6,.col-xl-2,.col-xl-4{position:relative;width:100%;min-height:1px;padding-right:15px;padding-left:15px}@media (min-width:768px){.col-md-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:992px){.col-lg-2{-webkit-box-flex:0;-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-lg-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-lg-4{-webkit-box-flex:0;-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-lg-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}}@media (min-width:1200px){.col-xl-2{-webkit-box-flex:0;-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-xl-4{-webkit-box-flex:0;-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}}.form-control{display:block;width:100%;padding:.375rem .75rem;font-size:1rem;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem}.form-control::-ms-expand{background-color:transparent;border:0}.form-control::-webkit-input-placeholder{color:#6c757d;opacity:1}.form-control::-moz-placeholder{color:#6c757d;opacity:1}.form-control:-ms-input-placeholder{color:#6c757d;opacity:1}.form-control::-ms-input-placeholder{color:#6c757d;opacity:1}.btn{display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:1rem;color:#212529;text-align:left;list-style:none;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.15);border-radius:.25rem}.input-group{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:stretch;-ms-flex-align:stretch;align-items:stretch;width:100%}.input-group>.form-control{position:relative;-webkit-box-flex:1;-ms-flex:1 1 auto;flex:1 1 auto;width:1%;margin-bottom:0}.input-group>.form-control:not(:last-child){border-top-right-radius:0;border-bottom-right-radius:0}img{page-break-inside:avoid}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}body{min-width:992px!important}.container{min-width:992px!important}.badge{border:1px solid #000}@charset "UTF-8";.fa,.fab{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1}.fa-envelope:before{content:"\f0e0"}.fa-facebook-f:before{content:"\f39e"}.fa-instagram:before{content:"\f16d"}.fa-paper-plane:before{content:"\f1d8"}.fa-twitter:before{content:"\f099"}.fa-user:before{content:"\f007"}@font-face{font-family:'Font Awesome 5 Brands';font-style:normal;font-weight:400;src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.eot);src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.eot?#iefix) format("embedded-opentype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.woff2) format("woff2"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.woff) format("woff"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.ttf) format("truetype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-brands-400.svg#fontawesome) format("svg")}.fab{font-family:'Font Awesome 5 Brands'}.fa{font-family:'Font Awesome 5 Free'}@font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:400;src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.eot);src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.eot?#iefix) format("embedded-opentype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.woff2) format("woff2"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.woff) format("woff"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.ttf) format("truetype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-regular-400.svg#fontawesome) format("svg")}@font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:900;src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.eot);src:url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.eot?#iefix) format("embedded-opentype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.woff2) format("woff2"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.woff) format("woff"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.ttf) format("truetype"),url(https://www.roadrunning.co.za/new/assets/webfonts/fa-solid-900.svg#fontawesome) format("svg")}.fa{font-weight:900}@font-face{font-family:inspiro-icons;src:url(https://www.roadrunning.co.za/new/assets/webfonts/inspiro-icons.eot?mxrs1k);src:url(https://www.roadrunning.co.za/new/assets/webfonts/inspiro-icons.eot?mxrs1k#iefix) format('embedded-opentype'),url(https://www.roadrunning.co.za/new/assets/webfonts/inspiro-icons.ttf?mxrs1k) format('truetype'),url(https://www.roadrunning.co.za/new/assets/webfonts/inspiro-icons.woff?mxrs1k) format('woff'),url(https://www.roadrunning.co.za/new/assets/webfonts/inspiro-icons.svg?mxrs1k#inspiro-icons) format('svg');font-weight:400;font-style:normal}i:not(.fa):not(.fab):not(.far):not(.fas){font-family:inspiro-icons!important;speak:none;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.icon-chevron-up1:before{content:"\e95b"}.icon-search1:before{content:"\e9eb"}.icon-user11:before{content:"\ea1b"}.icon-x:before{content:"\ea2c"}@charset "UTF-8";@import "https://fonts.googleapis.com/css?family=Open+Sans:300,400,800,700,600";@import "https://fonts.googleapis.com/css?family=Poppins:100,200,400,500,600,700,800";@import "https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800";@media (min-width:1px) and (max-width:991px){#header .container,#header .header-inner .container,.container{max-width:960px !important;padding:0 30px !important}}body .body-inner{padding:0;margin:0}section{padding:80px 0;position:relative;width:100%;overflow:hidden;z-index:1;background-color:#ffffff}#page-title{clear:both;padding:80px 0;background-color:#f8f9fa;position:relative}#page-title .page-title{text-align:center;padding:0}#page-title .page-title > h1{font-family:"Poppins","Helvetica","Arial",sans-serif;font-size:44px;line-height:56px;font-weight:400;margin-bottom:10px}#page-title .breadcrumb{text-align:center;margin-bottom:0;padding:0}#page-title.page-title-left .page-title{float:left;text-align:left;clear:both}#page-title.page-title-left .breadcrumb{float:left;text-align:left}@media (max-width:991px){#page-title{padding:50px 0 !important}#page-title .page-title > h1{font-size:26px;line-height:34px}}#page-content{padding:40px 0 40px 0}@media (max-width:991px){#page-content{padding:20px 0 20px 0}}#topbar{position:relative;z-index:199;background-color:#fff}#topbar .social-icons{float:right;height:100%;overflow:hidden}#topbar .social-icons li,#topbar .social-icons li a{float:left;list-style:outside none none}#topbar .social-icons li a{border-radius:0px;font-size:15px;height:40px;line-height:40px;text-align:center;width:35px;overflow:hidden;margin:0}.topbar-dropdown{color:#747474;float:left;font-size:13px;font-weight:400;position:relative}.topbar-dropdown .title{border-left:1px solid #eeeeee;padding:0 20px;line-height:40px}.topbar-dropdown .title .fa{margin-left:7px;position:relative;top:-1px}.topbar-dropdown .title .fa:first-child{margin-left:0;margin-right:7px}.topbar-dropdown:first-child .title{border-color:transparent;padding-left:0}#topbar.topbar-fullwidth > .container{max-width:100%;padding:0 30px}#topbar{border-bottom:1px solid #eeeeee}#header{position:relative;width:100%;z-index:199 !important;height:80px;line-height:80px}#header .container{position:relative}#header .header-inner{height:80px;background-color:#fff;-webkit-backface-visibility:hidden;left:0;right:0}#header .header-inner #logo{float:left;font-size:28px;position:relative;z-index:1;height:80px}#header .header-inner #logo a > img{vertical-align:inherit;height:80px;width:auto}#header[data-fullwidth="true"] .header-inner .container{max-width:100%;padding:0 30px}#header #mainMenu-trigger{position:absolute;opacity:0;visibility:hidden;height:80px;z-index:1;float:right;width:26px}#header #mainMenu-trigger button{background:none;border:0;padding:0}#mainMenu{padding:0}#mainMenu > .container{padding:0 !important}#mainMenu nav{float:right}#mainMenu nav > ul{list-style:none;padding:0;margin:0}#mainMenu nav > ul > li{float:left;border:0;margin-left:6px}#mainMenu nav > ul > li > a{position:relative;font-family:"Poppins","Helvetica","Arial",sans-serif;padding:10px 12px;text-transform:uppercase;font-size:12px;font-weight:600;letter-spacing:0.6px;color:#000000;border-radius:0;border-width:0;border-style:solid;border-color:transparent;line-height:normal}#mainMenu nav > ul > li .dropdown-menu{background-position:right bottom;background-repeat:no-repeat;min-width:230px;top:auto;background-color:#fff;border:0;border-style:solid;border-color:#eeeeee;border-width:1px !important;left:auto;margin:0;margin-top:-6px;border-radius:4px;box-shadow:0 33px 32px rgba(0,0,0,0.1);padding:10px}#mainMenu nav > ul > li .dropdown-menu > li > a{font-size:12px;line-height:14px;font-weight:500;font-style:normal;color:#444;font-family:"Poppins","Helvetica","Arial",sans-serif;padding:12px 20px 12px 18px;display:block}#mainMenu nav > ul > li:last-child{margin-right:0}#mainMenu nav > ul li ul{list-style:none;padding:0}#mainMenu nav > ul .badge{font-size:8px;padding:2px 4px;line-height:9px;margin:0 4px}.header-extras{float:right;z-index:201;position:relative;height:80px}.header-extras > ul{list-style:none;padding:0;margin:0}.header-extras > ul > li{float:left;border:0}.header-extras > ul > li > a:not(.btn),.header-extras > ul > li > .p-dropdown{padding-left:6px;padding-right:6px}.header-extras > ul > li > a:not(.btn){font-family:"Poppins","Helvetica","Arial",sans-serif;font-size:12px;position:relative;display:block;font-style:normal;text-transform:uppercase;font-weight:700}.header-extras > ul > li > a:not(.btn) i{font-size:14px;position:relative}.header-extras > ul > li .btn{margin-bottom:0px}@media (max-width:991px){#header[data-fullwidth="true"] .header-inner .container{padding:0 30px}#header .header-inner{height:auto}#header #logo{position:absolute !important;width:100%;text-align:center;margin:0 !important;float:none;height:100px;left:0;right:0;padding:0 !important}#header #logo > a{display:inline-block}#header #mainMenu-trigger{position:relative;opacity:1;visibility:visible}#header #mainMenu:not(.menu-overlay){max-height:0;clear:both;display:block;width:100%;opacity:1;overflow:hidden}#header #mainMenu:not(.menu-overlay) > .container{text-align:left !important;width:100%;max-width:100%}#header #mainMenu:not(.menu-overlay) nav{line-height:40px;float:none;width:100%;padding-bottom:20px}#header #mainMenu:not(.menu-overlay) nav > ul{float:none;width:100%}#header #mainMenu:not(.menu-overlay) nav > ul > li{padding:0;margin:0;clear:both;float:none;display:block;border:0}#header #mainMenu:not(.menu-overlay) nav > ul > li > a{display:block;padding:12px 0;font-size:14px;border:0;border-radius:0}#header #mainMenu:not(.menu-overlay) nav > ul > li > a:after{display:none}#header #mainMenu:not(.menu-overlay) nav > ul > li .dropdown-menu{background-image:none !important;max-height:0;overflow:hidden;opacity:0;position:static;clear:both;float:none;box-shadow:none;border:0 !important;min-width:100%;margin:0;border-radius:0px;padding:0 16px}#header #mainMenu:not(.menu-overlay) nav > ul > li .dropdown-menu > li > a{line-height:16px;font-size:14px;padding:12px 0;display:block}#header #mainMenu:not(.menu-overlay) nav > ul li > .dropdown-menu{border:0 none;background-color:transparent;display:block}.header-extras{float:left}.header-extras > ul .p-dropdown > a > i,.header-extras > ul > li > a > i{font-size:16px !important}.header-extras > ul .p-dropdown:first-child > a,.header-extras > ul > li:first-child > a{padding-left:0}}#search{display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;background:#fff;line-height:normal;position:fixed;z-index:1000;top:0;left:0;width:100%;height:100vh;opacity:0}#search #search-logo{top:-1px;left:30px;opacity:0;position:absolute;line-height:80px;height:80px}#search #search-logo img{width:100%;height:100%}#search .search-form{opacity:0;transform:scale3d(0.8,0.8,1);margin:80px 0}#search .search-form .text-muted{opacity:.4}#search .search-form .form-control{border-width:0px 0px 2px 0px;border-radius:0px;font-size:34px;padding:10px 0;border-color:#cecece;font-family:"Poppins","Helvetica","Arial",sans-serif;font-weight:700;margin-bottom:20px;background-color:transparent}#search .search-form .form-control:-webkit-input-placeholder{color:#cecece}#search .search-form .form-control:-moz-placeholder{color:#cecece}#search .search-form .form-control:-ms-input-placeholder{color:#cecece}#search .btn-search-close{font-size:2em;position:absolute;top:20px;right:30px;display:none;padding:12px;line-height:12px;background-color:#2250fc;border:0;border-radius:5px;color:#fff;opacity:0;transform:scale3d(0.8,0.8,1)}#search .search-suggestion-wrapper{display:flex;width:60%}#search .search-suggestion-wrapper .search-suggestion{width:33.33%;text-align:left;opacity:0;transform:translate3d(0,-30px,0)}#search .search-suggestion-wrapper .search-suggestion:nth-child(2){margin:0 3em}#search .search-suggestion-wrapper .search-suggestion h3{font-size:1.35em;margin:0 0 12px 0}#search .search-suggestion-wrapper .search-suggestion h3::before{content:'\21FE';display:inline-block;padding:0 0.5em 0 0}#search .search-suggestion-wrapper .search-suggestion p{line-height:1.4;margin:0 0 10px 0}@media (max-width:991px){#search #search-logo{margin:0 auto !important;max-width:130px;left:auto}#search .search-form{margin:40px}#search .search-form .form-control{font-size:24px;margin-bottom:8px}#search .search-form .text-muted{font-size:10px}#search .btn-search-close{font-size:1em;padding:8px;line-height:8px}#search .search-suggestion-wrapper{display:none}}:active,:focus{outline:none !important}*,h1,h2,h3,h4,a{margin:0;padding:0}html{font-size:87.5%}@media all and (max-width:768px){html{font-size:81.25%}}body{font-size:1em;line-height:1.65714286em;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-family:"Nunito","Helvetica","Arial",sans-serif;color:#565656}h1,h2,h3,h4{font-family:"Poppins","Helvetica","Arial",sans-serif;color:#1f1f1f;margin:0;letter-spacing:0.5px}h1{font-size:3.14285714em;line-height:1.31818182em}h2{font-size:2.35714286em;line-height:1.36363636em;font-weight:500}h2:not(:last-child){margin-bottom:16px}h3{font-size:1.78571429em;line-height:1.5em;font-weight:500}h3:not(:last-child){margin-bottom:12px}h4{font-size:1.35714286em;line-height:1.68421053em;font-weight:500}h4:not(:last-child){margin-bottom:8px}@media all and (max-width:767px){h1{font-size:2.35714286em;line-height:1.36363636em}h2{font-size:1.78571429em;line-height:1.5em}h3{font-size:1.35714286em;line-height:1.85714286em}}p{font-size:1em;font-style:normal;font-weight:400;text-transform:none;line-height:1.6;letter-spacing:0;color:#777777;font-family:"Open Sans","Helvetica","Arial",sans-serif}a:not(.btn){color:#565656}a:not(.btn):not(.btn):not([href]):not([tabindex]){color:#565656}.text-light,.text-light h1,.text-light a:not(.btn),.text-light li,.text-light div:not(.alert){color:#fff !important}.p-r-40{padding-right:40px !important}#footer{display:block;position:relative;background-color:#f8f9fa;font-size:14px;line-height:32px;font-weight:400;font-family:"Poppins","Helvetica","Arial",sans-serif}#footer p{font-family:"Poppins","Helvetica","Arial",sans-serif}#footer a:not(.btn){font-weight:400}#footer .footer-content{padding:60px 0 40px 0}#footer .widget .widget-title,#footer .widget h4{font-family:"Poppins","Helvetica","Arial",sans-serif;font-size:14px;letter-spacing:1px;font-weight:600}#footer .copyright-content{min-height:80px;padding:30px 0;background-color:#eff1f4;font-size:13px}#footer.inverted{background-color:#181818}#footer.inverted p{font-family:"Poppins","Helvetica","Arial",sans-serif;color:#999}#footer.inverted a:not(.btn){color:#999;font-weight:400}#footer.inverted h4{color:#fff}#footer.inverted .widget .widget-title,#footer.inverted .widget h4{color:#fff}#footer.inverted .copyright-content{background-color:#1E1E1E}.widget{margin-bottom:30px;position:relative}.widget .widget-title,.widget > h4{font-family:"Open Sans";font-size:14px;font-style:normal;font-weight:600;text-transform:uppercase;line-height:24px;letter-spacing:1px;margin-bottom:20px}.widget:after,.widget:before{clear:both;content:" ";display:table}.widget.widget-newsletter button{margin-left:-1px}.widget.widget-newsletter button{border-bottom-left-radius:0;border-top-left-radius:0}.widget.widget-newsletter .btn{text-transform:none}.widget.widget-newsletter .form-control{height:40px}.widget.widget-newsletter .btn{font-size:12px;font-weight:600;height:40px;padding:8px 16px}#scrollTop{z-index:-1;opacity:0;position:fixed;text-align:center;line-height:12px !important;right:26px;bottom:0;color:#fff;border-radius:100px;height:40px;width:40px;background-color:rgba(0,0,0,0.25);background-repeat:no-repeat;background-position:center;background-color:rgba(0,0,0,0.25) !important;overflow:hidden}#scrollTop i{line-height:39px !important;width:39px !important;height:39px !important;font-size:16px !important;top:0px !important;left:0px !important;text-align:center !important;position:relative;z-index:10;background-color:transparent !important;transform:translate(0,0px)}#scrollTop:after,#scrollTop:before{display:block;content:' ';height:100%;width:100%;position:absolute;top:0;left:0;z-index:1;background-color:#2250fc;transform:scale(0);border-radius:100px}#scrollTop:before{background-color:rgba(255,255,255,0.25);transform:scale(1);opacity:0;z-index:2}[class^="icon"]{display:inline-block}.lines-button{-webkit-appearance:none;-moz-appearance:none;appearance:none;border:none;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:100%;width:16px;background:transparent;outline:none}.lines-button > *{display:block}.lines-button::-moz-focus-inner{border:0}.lines{display:inline-block;height:2px;width:20px;border-radius:4px;position:relative;top:-4px}.lines::before,.lines::after{display:inline-block;height:2px;width:20px;border-radius:4px;content:'';position:absolute;left:0;-webkit-transform-origin:2.85714px center;transform-origin:2.85714px center;width:100%}.lines::before{top:6px}.lines::after{top:-6px}.lines,.lines:before,.lines:after{background-color:#111}.x{width:auto}.p-dropdown{float:left;font-size:13px;font-weight:400;position:relative}.p-dropdown .btn{padding-bottom:10px;overflow:unset !important}.p-dropdown .btn:before{top:30px;right:5px}.p-dropdown .p-dropdown-content{line-height:normal;position:absolute;min-width:140px;z-index:5;text-align:left;opacity:0;visibility:hidden;transform:translateY(8px);padding:14px 20px;width:min-content;top:auto;right:0;margin:0;border-radius:4px;background-color:#fff;border:1px solid #ececec;min-width:180px;box-shadow:0 14px 20px rgba(0,0,0,0.1)}.p-dropdown .p-dropdown-content hr{margin-left:-20px;margin-right:-20px}.header-extras .p-dropdown .p-dropdown-content{right:-26px;margin-top:-8px}.header-extras .p-dropdown .p-dropdown-content:before{display:none}a:not([href]):not([tabindex]):not(.btn){color:none}.btn{font-family:"Poppins","Helvetica","Arial",sans-serif;border-radius:5px 5px 5px 5px;font-size:12px;font-weight:600;letter-spacing:1px;outline:none;padding:10px 18px;position:relative;text-transform:uppercase;background-color:#2250fc;border-color:#2250fc;border-width:1px;border-style:solid;color:#fff;margin-bottom:6px;outline:none;line-height:14px}.btn:after{content:'';position:absolute;z-index:-1}.btn i{text-shadow:none}.btn.btn-xs{font-size:10px;height:24px;line-height:22px;padding:0 10px}.btn + .btn{margin-left:4px}.social-icons ul{padding:0;margin:0}.social-icons li{float:left;list-style:none}.social-icons li a{float:left;height:32px;width:32px;line-height:32px;font-size:16px;text-align:center;margin:0 4px 4px 0;border-radius:4px;border:0;background:transparent;color:#333;overflow:hidden}i:not(.fa):not(.fab):not(.far):not(.fas){line-height:unset}hr{margin-bottom:10px;margin-top:10px;clear:both}form .btn{padding:12px 14px}.form-control,input{border-radius:0;box-shadow:none;line-height:18px;padding:10px 16px;border-radius:5px;border:1px solid #ececec;font-size:1rem}.form-control::-moz-placeholder,.form-control::-ms-input-placeholder,.form-control::-webkit-input-placeholder{color:#bbbbbb}.badge{font-family:"Open Sans","Helvetica","Arial",sans-serif;font-weight:600}button::-moz-focus-inner{padding:0;border:0}.list{clear:both;display:block;position:relative}.list li{line-height:32px}.list li a{font-weight:600}.list{list-style:none}.breadcrumb{background-color:transparent;font-size:12px;font-weight:400;letter-spacing:0.5px;margin-bottom:10px;display:block}.breadcrumb ul{display:inline;margin:0;padding:0}.breadcrumb ul li{display:inline;position:relative;opacity:.8}.breadcrumb ul li + li:before{content:"\e95a";font-family:"inspiro-icons";margin:0 5px;background-color:transparent}.breadcrumb ul li.active,.breadcrumb ul li:last-child{opacity:1}@media (max-width:991px){.body-inner{width:100%;margin:0}.widget{float:left;width:100%}}@media (max-width:767px){.body-inner{width:100% !important}.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-9{clear:left;width:100%}.col-md-12{width:100% !important}section{padding:40px 0}}::-webkit-selection{background:#26B8F3}.btn,#scrollTop:after,#scrollTop:before{background-color:#26B8F3}.btn{border-color:#26B8F3}#mainMenu nav > ul > li > a{font-size:1em;font-family:"Poppins","Helvetica","Arial",sans-serif}#mainMenu nav > ul > li .dropdown-menu > li > a{font-size:1em}.topbar-dropdown{font-family:"Poppins","Helvetica","Arial",sans-serif}.header-extras a.btn{margin:0 5px}button,input{font-family:"Open Sans",Arial;font-style:normal}#header .header-inner #logo a > img,#header .header-inner #search-logo img{height:80px;width:195px}#search .btn-search-close{background-color:#26B8F3}.p-dropdown .p-dropdown-content{min-width:220px}#page-title{background-color:#232427;background-size:cover;background-position:center center}@media (max-width:991px){#search #search-logo{max-width:none}#search .btn-search-close{margin-top:6px}}*/
        </style>
        <link href="<?= base_url('assets/css/combined_min.css'); ?>" rel="stylesheet" type="text/css">
        
<!--        <link href="<?= base_url('assets/css/plugins.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css"> 
        <link href="<?= base_url('assets/css/color-variations/blue.css'); ?>" rel="stylesheet" type="text/css" media="screen">
        <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />-->
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
                            <a href="index.html" class="logo" data-src-dark="<?= base_url('assets/img/roadrunning_logo_dark_80.svg'); ?>">
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
                                        <!--                                        <li><a href="index.html">Home</a></li>
                                                                                <li><a href="index.html">Races</a>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li><a href="">Upcoming<span class="badge badge-danger">POPULAR</span></a></li>
                                                                                        <li><a href="">Per Region</a></li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><a href="index.html">Results</a></li>
                                                                                <li><a href="index.html">FAQ</a></li>
                                                                                <li><a href="about.html">About</a></li>
                                                                                <li><a href="index.html">Contact</a></li>                                        -->
                                    </ul>
                                </nav>                                
                            </div>
                        </div>
                        <!--END: NAVIGATION-->
                    </div>
                </div>
            </header>

            <?php
            if ($home) {
                ?>
                <!-- Slider -->
                <div id="slider" class="inspiro-slider arrows-large arrows-creative dots-creative" data-height-xs="360" data-autoplay-timeout="2600" data-animate-in="fadeIn" data-animate-out="fadeOut" data-items="1" data-loop="true" data-autoplay="true">

                    <div class="slide background-overlay-one" style="background-image:url('<?= base_url('assets/img/slider/run_02.webp'); ?>')">
                        <div class="container">
                            <div class="slide-captions d-none d-md-block">
                                <h2 class="text-sm no-margin">Any idiot can run</h2>
                                <h2 class="text-medium no-margin">but it takes a special kind of idiot to run a marathon</h2>
                            </div>
                        </div>
                    </div>

                    <div class="slide background-overlay-one" style="background-image:url('<?= base_url('assets/img/slider/run_03.webp'); ?>')">
                        <div class="container">
                            <div class="slide-captions">
                                <h2 class="text-sm no-margin text-colored">If a hill has a name</h2>
                                <h2 class="text-medium no-margin">It's probably a pretty tough hill</h2>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end: Slider -->
                <?php
            } else {
                ?>
                <!-- Page title -->
                <section id="page-title" class="text-light page-title-left" style="background-image:linear-gradient(rgba(0, 0, 0, 0.4),rgba(0, 0, 0, 0.4)),url(<?= base_url('assets/img/slider/run_01.webp'); ?>);">

                    <div class="container">
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="#">Home</a> </li>
                                <li><a href="#">Page title</a> </li>
                                <li class="active"><a href="#"><?= $page_title; ?></a> </li>
                            </ul>
                        </div>
                        <div class="page-title">
                            <h1><?= $page_title; ?></h1>
                        </div>
                    </div>
                </section>            
                <!-- end: Page title -->
                <?php
            }
            ?>
            <?php
            if ($this->session->flashdata()) {
                ?>
                <div role="alert" class="alert alert-<?= $this->session->flashdata('status'); ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>
                    <strong><i class="fa fa-check-circle"></i> <?= ucfirst($this->session->flashdata('status')); ?>!</strong> <?= $this->session->flashdata('alert'); ?> 
                </div>
                <?php
            }
            ?>
            <!-- Page Content -->
            <section id="page-content" class="sidebar-right">
                <div class="container">


