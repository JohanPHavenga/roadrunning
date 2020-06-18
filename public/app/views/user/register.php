<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 center no-padding">
                <!--<form class="form-transparent-grey">-->
                <?php
                if (validation_errors()) {
                    echo "<div class='alert alert-danger m-b-20' role='alert'><h5><i class='fa fa-exclamation-circle'></i> OOPS. Something went wrong!</h5>";
                    echo validation_errors();
                    echo "</div>";
                }
                echo form_open($form_url, ["class" => "form-transparent-grey"]);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Register New Account</h3>
                        <p>Create an account by entering the information below. If you are a returning user please login at the top of the page.</p>
                    </div>

                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('First Name', 'user_name', ['class' => "sr-only"]);
                        echo form_input([
                            'name' => 'user_name',
                            'id' => 'user_name',
                            'placeholder' => 'First Name',
                            'class' => 'form-control',
                            'value' => set_value('user_name'),
                            'required' => '',
                            'autofocus' => '',
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('Surname *', 'user_surname', ['class' => "sr-only"]);
                        echo form_input([
                            'name' => 'user_surname',
                            'id' => 'user_surname',
                            'placeholder' => 'Surname',
                            'class' => 'form-control',
                            'value' => set_value('user_surname'),
                            'required' => '',
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('Email', 'user_email', ['class' => "sr-only"]);
                        echo form_input([
                            'name' => 'user_email',
                            'id' => 'user_email',
                            'placeholder' => 'Email',
                            'class' => 'form-control',
                            'type' => 'email',
                            'value' => set_value('user_email'),
                            'required' => '',
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('Phone', 'user_contact', ['class' => "sr-only"]);
                        echo form_input([
                            'name' => 'user_contact',
                            'id' => 'user_contact',
                            'placeholder' => 'Phone',
                            'class' => 'form-control',
                            'value' => set_value('user_contact'),
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('Password', 'user_password', ['class' => "sr-only"]);
                        echo form_input([
                            'name' => 'user_password',
                            'id' => 'user_password',
                            'placeholder' => 'Password',
                            'class' => 'form-control',
                            'type' => 'password',
                            'required' => '',
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-6 form-group">
                        <?php
                        echo form_label('Confirm Password', 'user_password_conf', ['class' => "sr-only"]);
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
                    <div class="col-lg-12 form-group">
                        <?php
                        $data = array(
                            'id' => 'form-submit',
                            'type' => 'submit',
                            'content' => '<i class="fa fa-user-check"></i>&nbsp;Register New Account',
                            'class' => 'btn',
                        );
                        echo form_button($data);
                        ?>
                        <a href="<?= base_url("login"); ?>" class="btn btn-light">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>&nbsp;Cancel</a>
                    </div>
                </div>
                </form>
            </div>

        </div>
    </div>
</section>