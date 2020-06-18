<?php
if (isset($page_menu)) {
    ?>
    <div class="page-menu inverted">
        <div class="container">
            <nav>
                <ul>
                    <?php
                    foreach ($page_menu as $key => $page) {
                        $cl = '';
                        if (isset($page['sub_menu'])) {
                            echo "<li class='dropdown'><a href='#'>$page[display]</a>";
                            echo "<ul class='dropdown-menu menu-last'>";
                            foreach ($page['sub_menu'] as $key => $sub_page) {
                                echo "<li><a href='$sub_page[loc]'>$sub_page[display]</a></li>";
                            }
                            echo "</ul></li>";
                        } else {
                            // check for active
                            $url_bits = explode("/", uri_string());
                            $short_url = str_replace(base_url(), "", $page['loc']);
                            if (($page['loc'] == current_url()) || (($short_url=="user/my-results")&&($url_bits[0]=="result"))) {
                                $cl = "active";
                            } 
                            
                            if ($key == "logout") {
                                echo '<li class="' . $cl . '"><a href="" data-href="" data-toggle="modal" data-target="#confirm-logout">' . $page['display'] . '</a>';
                            } else {
                                echo "<li class='$cl'><a href='$page[loc]'>$page[display]</a></li>";
                            }
                        }
                    }
                    ?>
                </ul>
            </nav>
            <div id="pageMenu-trigger">
                <i class="fa fa-bars"></i>
            </div>

        </div>
    </div>
    <?php
}    