<section class="p-b-150 p-t-150" data-parallax-image="<?= base_url("assets/img/banner/run_02.webp"); ?>"><div class="parallax-container img-loaded" data-velocity="-.090" style="background: rgba(0, 0, 0, 0) url(&quot;<?= base_url("assets/img/banner/run_02webp"); ?>&quot;) repeat scroll 0% 0px;"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-error-404" style="color: #666;">404</div>
            </div>
            <div class="col-lg-6">
                <div class="text-left">
                    <h1 class="text-medium">Ooops, This Page Could Not Be Found!</h1>
                    <p class="lead">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    <div class="seperator m-t-20 m-b-20"></div>

                    <div class="search-form">
                        <p>Please try searching again</p>

                        <form action="<?= base_url("search"); ?>" method="post" class="form-inline">
                            <div class="input-group input-group-lg">
                                <input type="text" aria-required="true" name="query" class="form-control widget-search-form" placeholder="Search for pages...">
                                <div class="input-group-append">
                                    <span class="input-group-btn">
                                        <button type="submit" id="widget-widget-search-form-button" class="btn"><i class="fa fa-search"></i></button>
                                    </span>
                                </div> 
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>