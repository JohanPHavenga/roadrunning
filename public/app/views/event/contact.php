<section id="page-content" class="sidebar-right">
    <div class="container">
        <div class="row m-b-5">
            <div class="col-lg-10">
                <h2>Contact Race Organisers</h2>
            </div>
        </div>
        <?php
        $this->load->view('widgets/race_meta');
        ?>      
        <div class="row">
            <!-- Content-->
            <div class="content col-lg-9">

                <!-- add box -->
                <div class="row m-b-30">
                    <div class="col-lg-12">
                        <div style='height: 90px; width: 100%; background: #ccc;'>Ad</div>
                    </div>
                </div>

                <div class="row m-b-40">
                    <div class="col-lg-12">
                        <h3 class="text-uppercase">Get In Touch</h3>
                        <p>Got a question regarding this race? Use the form below to contact the race organisers directly. You will receive a copy of the email in your inbox as well.</p>
                        <div class="m-t-30">
                            <form class="widget-contact-form" action="include/contact-form.php" role="form" method="post" novalidate="novalidate">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Name</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Enter your Name" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Enter your Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Enter your Message" aria-required="true"></textarea>
                                </div>

                                <div class="form-group">
                                    <script type="text/javascript" async="" src="https://www.gstatic.com/recaptcha/releases/75nbHAdFrusJCwoMVGTXoHoM/recaptcha__en_gb.js"></script><script src="https://www.google.com/recaptcha/api.js"></script>
                                    <div class="g-recaptcha" data-sitekey="6LddCxAUAAAAAKOg0-U6IprqOZ7vTfiMNSyQT2-M"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LddCxAUAAAAAKOg0-U6IprqOZ7vTfiMNSyQT2-M&amp;co=aHR0cDovL2xvY2FsaG9zdDo4MA..&amp;hl=en-GB&amp;v=75nbHAdFrusJCwoMVGTXoHoM&amp;size=normal&amp;cb=rb1pcq48dbxb" role="presentation" name="a-pd6b2bc2kn15" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" width="304" height="78" frameborder="0"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div></div>
                                </div>


                                <button class="btn" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send message</button>
                            </form>

                        </div>
                    </div>

                    <!-- end: Product additional tabs -->
                </div>

            </div>
            <!-- end: Content-->

            <!-- Sidebar-->
            <div class="sidebar col-lg-3">  
                <?php
                // SUBSCRIBE WIDGET
                $data_to_widget['title'] = "Receive race notification";
                $this->load->view('widgets/subscribe', $data_to_widget);

                // ADS WIDGET
                $this->load->view('widgets/side_ad');
                ?>
            </div>
            <!-- end: Sidebar-->
        </div>
    </div>
</section>
<!-- end: Shop products -->