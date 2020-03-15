<div class="portlet-footer" style="margin-top: 10px;">
    <div class='btn-group'>
        <?php
        if ($action == "edit") {
            echo fbutton("Apply", "submit", "primary", NULL, "save_only");
        }
        echo fbutton("Save", "submit", "success");
        ?>
    </div>
    <div class='btn-group'>
        <?php
        echo fbuttonLink($return_url, "Cancel", "warning");
        ?>
    </div>
</div>

