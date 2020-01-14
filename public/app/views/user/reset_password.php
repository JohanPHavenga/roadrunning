<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 center no-padding">
                <h3>Reset Password</h3>
                <p>Enter your new password below</p>
                <?php
                if (validation_errors()) {
                    echo "<div class='alert alert-danger m-b-20' role='alert'><h5><i class='fa fa-info-circle'></i> No, that is not right</h5>";
                    echo validation_errors();
                    echo "</div>";
                }
                echo form_open($form_url);
                ?>
                <div class="form-group">
                    <?php
                    echo form_label('New Password', 'user_password', ["class" => "sr-only"]);
                    echo form_input([
                        'name' => 'user_password',
                        'id' => 'user_password',
                        'placeholder' => 'New Password',
                        'class' => 'form-control',
                        'type' => 'password',
                        'required' => '',
                        'autofocus' => '',
                    ]);
                    ?>
                </div>

                <div class="form-group">
                    <?php
                    echo form_label('Confirm Password', 'user_password_conf', ["class" => "sr-only"]);
                    echo form_input([
                        'name' => 'user_password_conf',
                        'id' => 'user_password_conf',
                        'placeholder' => 'Confirm Password',
                        'class' => 'form-control',
                        'type' => 'password',
                        'required' => '',
                    ]);
                    ?>
                </div>
                <div class="form-group m-t-15">
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-lock"></i>&nbsp;Set Password',
                        'class' => 'btn',
                    );
                    echo form_button($data);
                    ?>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</section>
