<?php
echo form_open_multipart($form_url);
?>
<div class="row">
  <div class="col-md-6">
    <?php
    $this->load->view('/admin/edition/general');
    if ($action == "edit") {
      $this->load->view('/admin/edition/races');
    }
    ?>
  </div>
  <div class="col-md-6">
    <?php
    if ($action == "edit") {
      $this->load->view('/admin/edition/entry');
      $this->load->view('/admin/edition/registration');
      $this->load->view('/admin/edition/tags');
    }
    ?>
  </div>
</div>

<?php
if ($action == "edit") {
?>
  <div class="row">
    <div class="col-md-6">
      <?php
      $this->load->view('/admin/edition/more_info');
      ?>
    </div>
    <div class="col-md-6">
      <?php
      $this->load->view('/admin/edition/urls');
      $this->load->view('/admin/edition/files');
      ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-9">
      <?php
      $this->load->view('/admin/edition/comments');
      ?>
    </div>
    <div class="col-md-3">
      <?php
      $this->load->view('/admin/edition/created_updated');
      ?>
    </div>
  </div>
<?php
} // IF EDIT
?>
<div class="row">
  <div class="col-md-12">
    <div class='btn-group' style='padding-bottom: 20px;'>
      <?php
      if ($action == "edit") {
        echo fbutton($text = "Apply", $type = "submit", $status = "primary", NULL, "save_only");
      }
      echo fbutton($text = "Save", $type = "submit", $status = "success");
      ?>
    </div>
    <div class='btn-group pull-right' style='padding-bottom: 20px;'>
      <?php
      echo fbuttonLink($return_url, "Cancel", $status = "warning");
      if ($edition_detail['edition_status'] == 2) {
        echo fbuttonLink($delete_url, "Delete", $status = "danger");
      }
      ?>
    </div>
  </div>
</div>

<?php
echo form_close();
//wts($edition_detail);
//wts($regtype_list);
//wts($date_list_by_type);
//wts($status_list);
//wts($race_list);
//wts($tag_list);