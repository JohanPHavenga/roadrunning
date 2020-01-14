<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 center no-padding">
                <h3>Login</h3>
                <?php
                if (validation_errors()) {
                    echo "<div class='alert alert-danger m-b-20' role='alert'><h5><i class='fa fa-exclamation-circle'></i> OOPS. Something went wrong!</h5>";
                    echo validation_errors();
                    echo "</div>";
                }
                echo form_open($form_url);
                ?>
                <div class="form-group">
                    <?php
                    echo form_label('Email', 'user_email', ["class" => "sr-only"]);
                    echo form_input([
                        'name' => 'user_email',
                        'id' => 'user_email',
                        'placeholder' => 'Email address',
                        'value' => set_value('user_email', $this->session->flashdata('email')),
                        'class' => 'form-control',
                        'type' => 'email',
                        'required' => '',
                        'autofocus' => '',
                    ]);
                    ?>
                </div>
                <div class="form-group m-b-5">
                    <?php
                    echo form_label('Password', 'user_password', ["class" => "sr-only"]);
                    echo form_input([
                        'name' => 'user_password',
                        'id' => 'user_password',
                        'type' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Password',
                        'required' => '',
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
                <div class="form-group form-inline m-b-10 m-t-15">
                    <!--                            <div class="form-check">
                                                    <label>
                                                        <input type="checkbox"><small class="m-l-10"> Keep me logged in</small>
                                                    </label>
                                                </div>-->
                </div>
                <div class="form-group">
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-user-check"></i>&nbsp;Login',
                        'class' => 'btn',
                    );
                    echo form_button($data);
                    ?>
                    <a href="<?= $register_url; ?>" class="btn btn-light m-l-10">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;Register</a>
                    <a href="<?= $reset_password_url; ?>" class="btn btn-light">
                        <i class="fa fa-key" aria-hidden="true"></i>&nbsp;Reset Password</a>
                </div>
                <?php
                echo form_close();
                ?>
                <p class="small">
                    Don't have an account yet? <a href="<?= $register_url; ?>">Register New Account</a><br>
                    Or if you forgot your password you can <a href="<?= $reset_password_url; ?>">reset your password</a>
                </p>
            </div>   
        </div>
    </div>
</section>