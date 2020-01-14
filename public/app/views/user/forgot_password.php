<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 center no-padding">
                <h3>Reset Password</h3>
                <p>Enter your email address below to reset your password</p>
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
                        'value' => set_value('user_email', $this->input->get('email')),
                        'class' => 'form-control',
                        'type' => 'email',
                        'required' => '',
                        'autofocus' => '',
                    ]);
                    ?>
                </div>
                <div class="form-group m-t-15">
                    <?php
                    $data = array(
                        'id' => 'form-submit',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-refresh"></i>&nbsp;Reset',
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