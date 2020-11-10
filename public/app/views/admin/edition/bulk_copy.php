<?php
if (@$error) {
  echo "<div class='note note-danger' role='alert'>$error</div>";
}
echo form_open();
?>

<div class="row">
  <div class="col-md-4">
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-edit font-dark"></i>
          <span class="caption-subject font-dark bold uppercase">Copy editions from time period</span>
        </div>
      </div>
      <?php
      echo "<div class='form-group'>";
      echo form_label('Choose timeperiod', 'eventfile');
      echo form_dropdown('time_period', $time_period, set_value('time_period'), ["id" => "time_period", "class" => "form-control input-medium"]);
      echo "</div>";

      echo "<div class='btn-group'>";
      echo fbutton("Preview", "submit", "primary", NULL, "btn_preview");
      echo "</div>";
      ?>
    </div>
  </div>
  <?php
  if ($_POST) {
    ?>
    <div class="col-md-8">
      <div class="portlet light">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase"><?= $col_head; ?></span>
          </div>
        </div>
        <?php
        if ($col_body == "preview") {
          $this->table->set_template(ftable('editions_noresults_table'));
          $this->table->set_heading(["Date","Name","ASA","To copy"]);
          foreach ($edition_list as $edition_id => $edition) {            
            if ($edition['copy']) { $s="color:#000"; } else { $s="color:#cc0000;font-weight:bold"; }
            $row['date']= "<span style='$s'>".fdateHumanShort($edition['edition_date'])."</span>";
            $row['name']="<span style='$s'>".$edition['edition_name']."</span>";            
            $row['region']="<span style='$s'>".$edition['asa_member_abbr']."</span>";  
            $row['to_copy']= "<span style='$s'>".fyesNo($edition['copy'])."</span>";
            $this->table->add_row($row);
          }
          echo $this->table->generate();
        }
        
        if ($col_body == "results") {
          echo "<p>A total number of <b>$copy_count</b> edtions has been copied.</p>";
        }
        
//        wts($col_body);
//        wts($this->input->post());
//        wts($this->input->post('time_period'));
//        wts($edition_list);
        ?>
        <div class='btn-group'>
          <?= fbutton("Bulk Copy", "submit", "warning", NULL, "btn_bulkcopy"); ?>
        </div>
      </div>

    </div>
    <?php
  } // if POST
  ?>
</div>

<?php
echo form_close();
