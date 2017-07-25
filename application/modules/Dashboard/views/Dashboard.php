<?php //pr($staff_no_data);    ?>
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">CRISIS TEAM New <small>Forest Care</small></h1>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="profile widget-shadow">
                    <div class="profile-top">
                        <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">
                        <h4><?php echo $logged_user[0]['name']; ?></h4>
                        <h5><?php echo $logged_user[0]['role_name']?></h5>
                    </div>
                    <div class="profile-btm">
                        <ul>
                            <li>
                                <a href="#">
                                    <h4>NFC Email</h4>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h4>NFC Forms</h4>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="panel-group tool-tips" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a class="accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    STAFF NOTICES
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="row">
                                    
                                    <?php 
                                    $i = 0;
                                    $count = count($staff_no_data);
                                    foreach ($staff_no_data as $key => $value) { ?>
                                    
                                        <div class="col-md-6">
                                            <div class="panel panel-default tile">
                                                <div class="panel-body">
                                                    <h2><?php echo $value['title']; ?> 
                                                        <?php 
                                                            $staff_id = $value['staff_notices_id']; 
                                                          $upload_data = getStaffUploadData($staff_id);
                                                        foreach($upload_data as $val){
                                                        ?>
                                                        
                                                        <a href="<?php echo base_url('Dashboard/download/' . $val['file_id']); ?>" title="<?php echo $val['file_name']?>" class="attachment"><i class="fa fa-paperclip fa-flip-horizontal" aria-hidden="true"></i></a>
                                                        
                                                        <?php } ?>
                                                    </h2>
                                                    <p class="slimScroll"><?php echo $value['notice'] ?></p>
                                                    <h6><b><?php echo $value['firstname'] . ' ' . $value['lastname']; ?>: </b><?php echo $value['created_date'] ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if($i %2==0 && $i>0){?>
                                    <div class="clearfix"></div>
                                    <?php }?>
                                    <?php $i++; } ?>
                                    <div class="col-md-12">
                                        <a data-href="<?php echo base_url() . 'Dashboard/staffNotices'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default" >Add New Notice</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    SCHOOL HANDOVER FOR CRISIS
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <div class="row">
                                    
                                    <?php 
                                    $i = 0;
                                    $count = count($school_hand_data);
                                    foreach ($school_hand_data as $key => $value) { ?>
                                    
                                        <div class="col-md-6">
                                            <div class="panel panel-default tile">
                                                <div class="panel-body">
                                                    <h2><?php echo $value['title']; ?> 
                                                        <?php 
                                                            $school_hand_id = $value['school_handover_id']; 
                                                          $upload_data = getSchoolHandUploadData($school_hand_id);
                                                        foreach($upload_data as $val){
                                                        ?>
                                                        
                                                        <a href="<?php echo base_url('Dashboard/HandoverFiledownload/' . $val['file_id']); ?>" title="<?php echo $val['file_name']?>" class="attachment"><i class="fa fa-paperclip fa-flip-horizontal" aria-hidden="true"></i></a>
                                                        
                                                        <?php } ?>
                                                    </h2>
                                                    <p class="slimScroll"><?php echo $value['notice'] ?></p>
                                                    <h6><b><?php echo $value['firstname'] . ' ' . $value['lastname']; ?>: </b><?php echo $value['created_date'] ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if($i %2==0 && $i>0){?>
                                    <div class="clearfix"></div>
                                    <?php }?>
                                    <?php $i++; } ?>
                                    
                                    <div class="col-md-12">
                                        <a data-href="<?php echo base_url() . 'Dashboard/schollHandover'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default" >Add School Handover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>