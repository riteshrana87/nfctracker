<div id="page-wrapper">
    <div class="main-page">
        <!--<h1 class="page-title">CRISIS TEAM New <small>Forest Care</small></h1>-->
        <div class="row">
            <div class="col-md-12">
                <div class="profile widget-shadow profile-page">
                    <div class="profile-top">
                        <div class="row">
                            <div class="col-sm-3 text-center w-20-per m-b-30">
                                <a href="<?php echo base_url('PlacementPlan/index/' . $id); ?>"><i class="fa fa-file-text"></i>PP</a>
                            </div>
                            <div class="col-sm-3 text-center w-20-per m-b-30">
                                <a href="<?php echo base_url('Ibp/index/' . $id); ?>"><i class="fa fa-file-text"></i>IBP</a>
                            </div>
                            <div class="hidden-xs col-sm-3 text-center w-20-per m-b-30">
                            </div>
                            <div class="col-sm-3 text-center  w-20-per m-b-30">
                                <a href="<?php echo base_url('RiskAssesment/index/' . $id); ?>"><i class="fa fa-envelope"></i>RA</a>
                            </div>
                            <div class="col-sm-3 text-center  w-20-per m-b-30">
                                <a href="<?php echo base_url('DailyObservation/index/' . $id); ?>"><i class="fa fa-folder-open"></i>DO</a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-3 text-center w-20-per">
                                <a href="<?php echo base_url('KeySession/index/' . $id); ?>"><i class="fa fa-file-text"></i>KS</a>
                            </div>
                            <div class="col-sm-3 text-center w-20-per">
                                <a href="<?php echo base_url('Documents/index/' . $id); ?>"><i class="fa fa-edit"></i>DOCS</a>
                            </div>
                            <div class="hidden-xs col-sm-3 text-center w-20-per">
                            </div>
                            <div class="col-sm-3 text-center  w-20-per">
                                <a href="<?php echo base_url('Medical/index/' . $id); ?>"><i class="fa fa-medkit"></i>MEDS</a>
                            </div>
                            <div class="col-sm-3 text-center  w-20-per">
                                <a href="<?php echo base_url('Communication/index/' . $id); ?>"><i class="fa fa-phone-square"></i>COMS</a>
                            </div>
                            <div class="absolute">



                                <?php if (empty($editRecord[0]['profile_img'])) { ?>
                                    <?php /* <img src="<?= base_url() ?>uploads/assets/front/images/img1.png" /> */ ?>
                                    <img src="<?php echo $this->config->item('yp_profile_no_image_base_url'); ?>" />
                                    <?php
                                } else {
                                    if (file_exists(FCPATH . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'])) {
                                        ?>
                                        <img src="<?= base_url() . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'] ?>" />
                                    <?php } else { ?>
                                        <?php /* <img src="<?= base_url() ?>uploads/assets/front/images/img1.png" /> */ ?>
                                        <img src="<?php echo $this->config->item('yp_profile_no_image_base_url'); ?>" />
                                        <?php
                                    }
                                }
                                ?>



                                <h4><?= !empty($editRecord[0]['yp_fname']) ? $editRecord[0]['yp_fname'] : '' ?>
                                    <?= !empty($editRecord[0]['yp_lname']) ? $editRecord[0]['yp_lname'] : '' ?></h4>
                                <h5>Young Person Profile</h5>
                                <a data-href="<?php echo base_url() . 'YoungPerson/ProfileInfo/' . $editRecord[0]['yp_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="upload-user-image" ></a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <h1 class="page-title">Personal Information</h1>
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-310">

                        <h2>Personal Info<a data-href="<?php echo base_url() . 'YoungPerson/personal_info/' . $editRecord[0]['yp_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="pull-right" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>D.O.B.</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['date_of_birth']) ? $editRecord[0]['date_of_birth'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Age</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['age']) ? $editRecord[0]['age'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Place of Birth</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['place_of_birth']) ? $editRecord[0]['place_of_birth'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Gender</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if (isset($editRecord[0]['gender'])) {
                                        echo (($editRecord[0]['gender'] == 'M') ? 'Male' : (($editRecord[0]['gender'] == 'F') ? 'Female' : ''));
                                    } else {
                                        echo "";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Date of Placement</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['date_of_placement']) ? $editRecord[0]['date_of_placement'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Legal Status</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['legal_status']) ? $editRecord[0]['legal_status'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staffing Ratio</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['staffing_ratio']) ? $editRecord[0]['staffing_ratio'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>EDT / Out Of Hours</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['edt_out_of_hours']) ? $editRecord[0]['edt_out_of_hours'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <h1 class="page-title">Authority Information</h1>
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-310">
                        <h2>Placing Authority
                            <a data-href="<?php echo base_url() . 'YoungPerson/placingAuthority/' . $editRecord[0]['yp_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="pull-right" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                        </h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Authority</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['authority']) ? $editRecord[0]['authority'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Address</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['address_1']) ? $editRecord[0]['address_1'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Town</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['town']) ? $editRecord[0]['town'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>County</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['county']) ? $editRecord[0]['county'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Postcode</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['postcode']) ? $editRecord[0]['postcode'] : '' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <h1 class="page-title">Authority Information</h1>
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-310">
                        <h2>Social Worker Details
                            <a data-href="<?php echo base_url() . 'YoungPerson/socialWorkerDetails/' . $editRecord[0]['yp_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="pull-right" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Social Worker</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['social_worker_firstname']) ? $editRecord[0]['social_worker_firstname'] : '' ?> <?= !empty($editRecord[0]['social_worker_surname']) ? $editRecord[0]['social_worker_surname'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Mobile</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['mobile']) ? $editRecord[0]['mobile'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Landline</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['landline']) ? $editRecord[0]['landline'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Other</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['other']) ? $editRecord[0]['other'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Email</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><a href="mailto:melanie.bowley@westsussex.gov.uk"><?= !empty($editRecord[0]['email']) ? $editRecord[0]['email'] : '' ?></a></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Senior SW</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['senior_social_worker_firstname']) ? $editRecord[0]['senior_social_worker_firstname'] : '' ?> <?= !empty($editRecord[0]['senior_social_worker_surname']) ? $editRecord[0]['senior_social_worker_surname'] : '' ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-6">
                <h1 class="page-title">Overview of Young Person</h1>
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-310">
                        <h2>Pen Portrait & Risk Oversight
                            <a data-href="<?php echo base_url() . 'YoungPerson/overviewOfYoungPerson/' . $editRecord[0]['yp_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="pull-right" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                        </h2>
                        <p class ="slimScroll-245">
                            <?= !empty($editRecord[0]['pen_portrait']) ? $editRecord[0]['pen_portrait'] : '' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--        <div class="row">
                    <div class="col-xs-12">
                        <a class="btn btn-default" href="#">Archive</a>
                        <a class="btn btn-default" href="#">Upload File</a>
                    </div>
                </div>-->
    </div>
</div>