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
            <h4 class="modal-title" id="exampleModalLabel">Social Worker Details </h4>
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
                <label for="recipient-name" class="control-label">Social Worker Firstname</label>                
                <input class="form-control" name="social_worker_firstname" placeholder="Enter Social Worker Firstname" type="text" value="<?= !empty($editRecord[0]['social_worker_firstname']) ? $editRecord[0]['social_worker_firstname'] : '' ?>" required="" />
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Social Worker Surname</label>                
                <input class="form-control" name="social_worker_surname" placeholder="Enter Social Worker Surname" type="text" value="<?= !empty($editRecord[0]['social_worker_surname']) ? $editRecord[0]['social_worker_surname'] : '' ?>" required="" />
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Mobile</label>                
                <input type="text" id='mobile_number' name='mobile' class="form-control" placeholder="Enter Mobile Number" value="<?php echo set_value('mobile', (isset($editRecord[0]['mobile']) ? $editRecord[0]['mobile'] : '')) ?>" required='true' data-parsley-required-message="Please Enter Mobile Number" data-parsley-maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-trigger="keyup"/>
                
            </div>


            <div class="form-group">
                <label for="recipient-name" class="control-label">Landline</label>                
               <input type="text" id='landline' name='landline' class="form-control" placeholder="Enter Landline Number" value="<?php echo set_value('mobile', (isset($editRecord[0]['landline']) ? $editRecord[0]['landline'] : '')) ?>" required='true' data-parsley-required-message="Please Enter Landline Number" data-parsley-maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-trigger="keyup"/>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Other</label>
                <input type="text" id='landline' name='other' class="form-control" placeholder="Enter Other Number" value="<?php echo set_value('other ', (isset($editRecord[0]['other']) ? $editRecord[0]['other'] : '')) ?>" required='true' data-parsley-required-message="Please Enter Other Number" data-parsley-maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-trigger="keyup"/>

            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Email</label>
                
                <input class="form-control" id="email" name="email" autocomplete="false" placeholder="Enter Email Id" data-parsley-trigger="change" required="" data-parsley-required-message="Please Enter Email Id" type="email" value="<?= !empty($editRecord[0]['email']) ? $editRecord[0]['email'] : '' ?>" data-parsley-email />
                
            </div>
                
            <div class="form-group">
                <label for="recipient-name" class="control-label">Senior Social Worker Firstname</label>
                    <input type="text"  name='senior_social_worker_firstname' class="form-control" placeholder="Enter Senior Social Worker Firstname" value="<?php echo set_value('senior_social_worker_firstname', (isset($editRecord[0]['senior_social_worker_firstname'])) ? $editRecord[0]['senior_social_worker_firstname'] : ''); ?>" required='true' data-parsley-required-message="Please Enter Senior Social Worker Firstname" >
                
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Senior Social Worker Surname</label>
                    <input type="text"  name='senior_social_worker_surname' class="form-control" placeholder="Enter Senior Social Worker Surname" value="<?php echo set_value('senior_social_worker_surname', (isset($editRecord[0]['senior_social_worker_surname'])) ? $editRecord[0]['senior_social_worker_surname'] : ''); ?>" required='true' data-parsley-required-message="Please Enter Senior Social Worker Surname" >
                
            </div>

            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($id) ? $id : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

