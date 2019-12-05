<?php
if (isset($page_menu)) {
    ?>
    <div class="page-menu inverted">
        <div class="container">
            <nav>
                <ul>
                    <?php
                    foreach ($page_menu as $page) {
                        if (isset($page['sub_menu'])) {
                            echo "<li class='dropdown'><a href='#'>$page[display]</a>";
                            echo "<ul class='dropdown-menu menu-last'>";
                            foreach ($page['sub_menu'] as $key => $sub_page) {
                                echo "<li><a href='$sub_page[loc]'>$sub_page[display]</a></li>";
                            }
                            echo "</ul></li>";
                        } else {
                            echo "<li><a href='$page[loc]'>$page[display]</a></li>";
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