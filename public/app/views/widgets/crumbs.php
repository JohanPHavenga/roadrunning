<div class="breadcrumb">
    <ul>
        <?php
        $cl = '';
        foreach ($crumbs_arr as $name => $url) {
            if ($name === array_key_last($crumbs_arr)) {
                $cl = "active";
            }
            echo "<li class='$cl'><a href='$url'>$name</a> </li>";
        }
        ?>
    </ul>
</div>

