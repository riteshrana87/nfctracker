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
            <h4 class="modal-title" id="exampleModalLabel">Placing Authority </h4>
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
                <label for="recipient-name" class="control-label">Authority</label>                
                <input class="form-control" name="authority" placeholder="Enter Authority" type="text" value="<?PHP
                if ($formAction == "registration") {
                    echo set_value('authority');
                    ?><?php } else { ?><?= !empty($editRecord[0]['authority']) ? $editRecord[0]['authority'] : '' ?><?php } ?>" required="" />
            </div>


            <div class="form-group">
                <label for="recipient-name" class="control-label">Address</label>                
                <input class="form-control" name="address_1" placeholder="Enter Address" type="text" value="<?PHP
                if ($formAction == "registration") {
                    echo set_value('age');
                    ?><?php } else { ?><?= !empty($editRecord[0]['address_1']) ? $editRecord[0]['address_1'] : '' ?><?php } ?>" required="" />
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Town</label>
                <input class="form-control" name="town" id="position" placeholder="Enter town" type="text" value="<?= !empty($editRecord[0]['town']) ? $editRecord[0]['town'] : '' ?>" /> 

            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">County</label>
                <input class="form-control" name="county" id="position" placeholder="Enter county" type="text" value="<?= !empty($editRecord[0]['county']) ? $editRecord[0]['county'] : '' ?>" />                     </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Postcode</label>
                    <input type="text"  name='postcode' class="form-control" placeholder="Enter Postcode" value="<?php echo set_value('postcode', (isset($editRecord[0]['postcode'])) ? $editRecord[0]['postcode'] : ''); ?>" required='true' data-parsley-required-message="Please Enter Postcode" >
                
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

