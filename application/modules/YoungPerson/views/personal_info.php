<?php
if (isset($editRecord) && $editRecord == "updatedata") {
    $record = "updatedata";
} else {
    $record = "insertdata";
}
?>

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

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Personal Info</h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="modal-body">
            <?php
            $attributes = array("name" => "personal_info", "id" => "registration", "data-parsley-validate" => "true", "onload" => "loadit('registration')");

            echo form_open_multipart($path, $attributes);
            ?>

            <div class="form-group">
                <label for="recipient-name" class="control-label">D.O.B</label>
                <div class="input-group" id="date_of_birth">
                    <input type="text"  name='date_of_birth' class="form-control time-input" placeholder="MM/DD/YYYY" value="<?php echo set_value('date_of_birth', (isset($editRecord[0]['date_of_birth'])) ? $editRecord[0]['date_of_birth'] : ''); ?>" required='true' data-parsley-required-message="Please Enter Date" >
                    <div class="input-group-addon">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>



<!--            <div class="form-group">
                <label for="recipient-name" class="control-label">Age</label>
                <input class="form-control" name="age" placeholder="Enter Age" type="text" value="<?PHP
                if ($formAction == "registration") {
                    echo set_value('age');
                    ?><?php } else { ?><?= !empty($editRecord[0]['age']) ? $editRecord[0]['age'] : '' ?><?php } ?>" required="" />
            </div>-->
            <div class="form-group">
                <label for="recipient-name" class="control-label">Place of Birth</label>
                <input class="form-control" name="place_of_birth" id="position" placeholder="Enter place of birth" type="text" value="<?= !empty($editRecord[0]['place_of_birth']) ? $editRecord[0]['place_of_birth'] : '' ?>" /> 
                
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Gender</label>
                <label class="radio-inline">
                    <input class="radio" name="gender"  id="gender" type="radio" value="M" <?= ($editRecord[0]['gender']=='M') ? 'checked' : '' ?>/>Male
                </label>
                <label class="radio-inline">
                    <input class="radio" name="gender" id="gender" type="radio" value="F" <?= ($editRecord[0]['gender']=='F') ? 'checked' : '' ?>/> Female
                </label>                           
            </div>

            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Date of Placement</label>
                <div class="input-group" id="date_of_birth">
                    <input type="text"  name='date_of_placement' class="form-control time-input" placeholder="MM/DD/YYYY" value="<?php echo set_value('date_of_placement', (isset($editRecord[0]['date_of_placement'])) ? $editRecord[0]['date_of_placement'] : ''); ?>" required='true' data-parsley-required-message="Please Enter Date" >
                    <div class="input-group-addon">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Legal Status</label>
                <input class="form-control" name="legal_status" id="position" placeholder="Enter Legal Status" type="text" value="<?= !empty($editRecord[0]['legal_status']) ? $editRecord[0]['legal_status'] : '' ?>" />                     </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Staffing Ratio</label>
                <input class="form-control" name="staffing_ratio" id="position" placeholder="Enter Staffing Ratio" type="text" value="<?= !empty($editRecord[0]['staffing_ratio']) ? $editRecord[0]['staffing_ratio'] : '' ?>" />                     </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">EDT / Out Of Hours</label>
                <input class="form-control" name="edt_out_of_hours" id="position" placeholder="Enter EDT / Out Of Hours" type="text" value="<?= !empty($editRecord[0]['edt_out_of_hours']) ? $editRecord[0]['edt_out_of_hours'] : '' ?>" />                     </div>    



            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                 <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($editRecord[0]['yp_id']) ? $editRecord[0]['yp_id'] : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

