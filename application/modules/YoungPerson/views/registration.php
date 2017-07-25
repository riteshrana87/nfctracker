<?php
if (isset($editRecord) && $editRecord == "updatedata") {
    $record = "updatedata";
} else {
    $record = "insertdata";
}
?>
<script>
    var formAction = "<?php echo $record; ?>";
    var check_email_url = "<?php echo base_url('Director/isDuplicateEmail'); ?>";
</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'edit' : 'registration';
$path = $form_action_path;
if (isset($readonly)) {
    $disable = $readonly['disabled'];
} else {
    $disable = "";
}
$main_user_data = $this->session->userdata('LOGGED_IN');
$main_user_id = $main_user_data['ID'];
?>

<div class="modal-dialog modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Add New YP</h4>
                <div class="col-md-12 error-list">
          <?= isset($validation) ? $validation : ''; ?>
        </div>
            </div>
            <div class="modal-body">
                <?php
                $attributes = array("name" => "registration", "id" => "registration", "data-parsley-validate" => "true");
                echo form_open_multipart($path, $attributes);
                ?>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">First Name</label>
                    <input class="form-control" name="fname" placeholder="Enter First Name" type="text" value="<?php echo set_value('fname', (!empty($editRecord[0]['firstname'])) ? $editRecord[0]['firstname'] : '') ?>" data-parsley-pattern="/^([^0-9@]*)$/" required="" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Surname</label>
                    <input class="form-control" name="lname" placeholder="Enter Surname" type="text" value="<?PHP
                    if ($formAction == "registration") {
                        echo set_value('lname');
                        ?><?php } else { ?><?= !empty($editRecord[0]['lastname']) ? $editRecord[0]['lastname'] : '' ?><?php } ?>" data-parsley-pattern="/^([^0-9]*)$/" required="" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Placement</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Initials</label>
                    <input class="form-control" name="initials" readonly="" id="position" placeholder="Enter Initials" type="text" value="<?= !empty($editRecord[0]['initials']) ? $editRecord[0]['initials'] : $initialsId ?>" />                     </div>
                <div class="modal-footer">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                    <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Create Young Person" />
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>

</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>