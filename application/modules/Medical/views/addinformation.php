<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? date('d-m-Y', strtotime($YP_details[0]['date_of_birth'])) : '' ?>
        </h1>
        <div class="row">
            <form action="<?= base_url('Medical/insert') ?>" method="post" id="docsform" name="docsform" data-parsley-validate enctype="multipart/form-data">
                <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                <h2>EDIT OVERVIEW</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Medical Number Card No</label>
                                            <input type="text" name="medical_number" value="<?= !empty($edit_record[0]['medical_number']) ? $edit_record[0]['medical_number'] : $medicalNumberId ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date Received</label>
                                            <div class="input-group">
                                                <input type="text" required="true" value="<?= !empty($edit_record[0]['date_received']) ? $edit_record[0]['date_received'] : '' ?>" name="date_received" id="create_date" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Allergies And Meds Not To Be Used</label>
                                            <textarea name="allergies_and_meds_not_to_be_used" rows="5" class="form-control"><?= !empty($edit_record[0]['allergies_and_meds_not_to_be_used']) ? $edit_record[0]['allergies_and_meds_not_to_be_used'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="yp_id" value="<?php echo $ypid;?>" id="" >
                                        <input type="hidden" name="mi_id" value="<?= !empty($edit_record[0]['mi_id']) ? $edit_record[0]['mi_id'] : '' ?>" id="" >
                                        
                                        
                                        <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Add Medical Information" />
                                    </div>
                                </div>
                            </div>
                        </div>

            </form>        
        </div>

    </div>
</div>
