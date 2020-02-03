<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Export event data for a timeperiod</span>
                </div>
            </div>
            <?php
                if (@$error) {
                    echo "<div class='note note-danger' role='alert'>$error</div>";
                }
                echo form_open($form_url);

                echo "<div class='form-group'>";
                echo form_label('Choose timeperiod', 'eventfile');

                echo form_dropdown('time_period', $time_period, 'large','class="form-control"');

                echo "</div>";

                echo "<div class='btn-group'>";
                echo fbutton($text="Download",$type="submit",$status="primary");
                echo "</div>";

                echo form_close();

                // wts($time_period);
                ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Export blank template</span>
                </div>
            </div>
            <a class="btn red" href="/admin/event/run_export">Get generic sample file</a>
        </div>
    </div>
</div>
