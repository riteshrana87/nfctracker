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
            <h4 class="modal-title" id="exampleModalLabel">Select Photo to Upload </h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="modal-body">
            <?php
            $attributes = array("name" => "profile_info", "id" => "registration", "data-parsley-validate" => "true");

            echo form_open_multipart($path, $attributes);
            ?>

            <p class="text-danger">IMPORTANT</p>
            <p>Before uploading the photo for the YP, please ensure that:</p>
            <p>1. The photo is resized/cropped to 200px x 200px</p>
            <p>2. The photo is a .jpg file</p>
            <p class="m-b-20">3. The photo is named <span class="text-danger">1.jpg</span></p>
            <div class="form-group dropzone">
                <!-- new code-->
                <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                    <div class="image_part" style="height: 100px;">
                        <label name="fileUpload">
                            <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
                                Select Files Here
                            </h1>
                            <input type="file" onchange="showimagepreview(this)" name="fileUpload" style="display: none" id="upl" />
                        </label>
                    </div>
                    <?php if (empty($editRecord[0]['profile_img'])) { ?>
                        <?php /* <img id="uploadPreview1" src="<?= $this->config->item('admin_image_path') . 'no_image.jpg' ?>"  width="100"  height="100" /> */ ?>
                        <img id="uploadPreview1" src='' />
                        <?php
                    } else {
                        if (file_exists(FCPATH . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'])) {
                            ?>
                            <img id="uploadPreview1" src="<?= base_url() . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'] ?>"  width="100"  height="100" />

                            <?php
                        }
                    }
                    ?>

                </div>


                <!-- end new code --> 
            </div>

            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($id) ? $id : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Create Young Person" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script>
    var image_upload_url = "<?php echo base_url('YoungPerson/upload_file'); ?>";
    var url_data = "<?php echo base_url(); ?>";

    /* image upload */

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "Are your Sure to delete this item?";

        BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls' value='" + dataPath + '/' + imgName + "'>");
                                $('#' + divId).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });

    });
//image upload
    function showimagepreview(input)
    {
        console.log(input);
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

            //           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 20480;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();

    }
</script>