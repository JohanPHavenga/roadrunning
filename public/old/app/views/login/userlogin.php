<!-- BEGIN: PAGE CONTAINER -->
<div class="c-layout-page">
    <!-- BEGIN: LAYOUT/BREADCRUMBS/BREADCRUMBS-1 -->
    <div class="c-layout-breadcrumbs-1 c-fonts-uppercase c-fonts-bold">
        <div class="container">
            <div class="c-page-title c-pull-left">
                <h3 class="c-font-uppercase c-font-sbold">User Login</h3>
            </div>
            <ul class="c-page-breadcrumbs c-theme-nav c-pull-right c-fonts-regular">
                <li>
                    <a href="/login/">User Login</a>
                </li>
                <li>/</li>
                <li>
                    <a href="/">Home</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: LAYOUT/BREADCRUMBS/BREADCRUMBS-1 -->

    <!-- BEGIN: PAGE CONTENT -->

    <!-- BEGIN: CONTENT/FEATURES/FEATURES-1 -->
    <div class="c-content-box c-size-md c-bg-white">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <?php
                        echo validation_errors();

                        echo "<div class='form-group'>";
                        echo form_label('Username', 'user_username');
                        echo form_open($form_url);
                        echo form_input([
                            'name'          => 'user_username',
                            'id'            => 'user_username',
                            'class'         => 'form-control',
                            'placeholder'   => 'Username',
                            'required'      => '',
                            'autofocus'     => '',
                        ]);
                        echo "</div>";

                        echo "<div class='form-group'>";
                        echo form_label('Username', 'user_password');
                        echo form_input([
                            'name'          => 'user_password',
                            'id'            => 'user_password',
                            'class'         => 'form-control',
                            'type'          => 'password',
                            'placeholder'   => 'Password',
                            'required'      => '',
                        ]);
                        echo "</div>";
                        echo fbutton("Sign in", "submit", "primary");
                        echo form_close();
                        ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END: CONTENT/FEATURES/FEATURES-1 -->

    <!-- END: PAGE CONTENT -->
</div>
<!-- END: PAGE CONTAINER -->
