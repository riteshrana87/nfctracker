<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <h1 class="page-title">
                    Medical Information <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?=(!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00')?date('d-m-Y',strtotime($YP_details[0]['date_of_birth'])):''?>
                </h1>
                <h1 class="page-title">
                    <small>MED CARD No / NHS No: </small><?= !empty($mi_details[0]['medical_number']) ? $mi_details[0]['medical_number'] : '' ?>
            
                    <small><span class="hidden-xs">&nbsp;&nbsp;&nbsp;&nbsp;</span><div class="visible-xs-block"></div></small>
                    <small>Date Received:</small> <?= !empty($mi_details[0]['date_received']) ? $mi_details[0]['date_received'] : '' ?>
                    <?php if(isset($mi_details) && !empty($mi_details)){?>
                    <a href="<?php echo base_url('Medical/editMi/'.$ypid.'/'.$mi_details[0]['mi_id']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <?php }else{ ?>
                    <a href="<?php echo base_url('Medical/create/'.$ypid);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <?php }?>
                    
                    
                    
                </h1>
                                    
                <div class="row">
                    <div class="col-sm-4">
                        <div class="panel panel-danger tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i>  Allergies & Meds not to be Used</h3>
                            </div>
                            <div class="panel-body min-h-140">
                                <p class="slimScroll-110">
                                   
                                    <?= !empty($mi_details[0]['allergies_and_meds_not_to_be_used']) ? $mi_details[0]['allergies_and_meds_not_to_be_used'] : '' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> Record Info</h3>
                            </div>
                            <div class="panel-body min-h-140">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/add_mc/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Medical Communication</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add New Medication</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/administer_medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>  Administer Medicication</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="#" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add Health Assessment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> View Logs</h3>
                            </div>
                            <div class="panel-body min-h-140">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/view_mc/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Coms & Appointment Log</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Medication List</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Medical/log_administer_medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Administration Log</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="#" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Health Assessment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>
                                Medical Authorisations & Consents
                               <a class="pull-right" href="<?=base_url('Medical/add_mac/'.$ypid); ?>" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </h2>
                             <?php
                            if(!empty($mac_form_data))
                            {
                                foreach ($mac_form_data as $row) {

                               if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                    ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                    <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                     <label class="value large">
                                                    <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                                       <?=!empty($mac_details[0][$row['name']])?nl2br(htmlentities($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                       <?php  }
                                                        else if($row['type'] == 'checkbox-group') {
                                                        if(!empty($mac_details[0][$row['name']])) {
                                                            $chk = explode(',',$mac_details[0][$row['name']]);
                                                                    foreach ($chk as $chk) {
                                                                        echo $chk."\n";
                                                                    }
                                                                 
                                                            
                                                        }else{

                                                                if(count($row['values']) > 0) {
                                                                   
                                                                 foreach ($row['values'] as $chked) {
                                                                    echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                     }
                                                                   
                                                                }
                                                            }?>

                                                       <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                         if(!empty($mac_details[0][$row['name']])) {
                                                            echo !empty($mac_details[0][$row['name']])?nl2br(htmlentities($mac_details[0][$row['name']])):'';
                                                         }
                                                         else
                                                         {
                                                             if(count($row['values']) > 0) {
                                                                    
                                                                 foreach ($row['values'] as $chked) {
                                                                    echo isset($chked['selected'])?$chked['value']:'';
                                                                     }
                                                                    
                                                                }
                                                         }
                                                        } ?>
                                                    </label>
                                                
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    else if ($row['type'] == 'textarea') { ?>
                                        <div class="col-sm-9">
                                             <div class="form-group">
                                                    <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                     <label class="value large">
                                                    <?=!empty($mac_details[0][$row['name']])?nl2br(htmlentities($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                    </label>
                                               
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    else if ($row['type'] == 'header') {
                                       ?>
                                       <div class="col-sm-12">
                                                    <div class="">
                                                        <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                        <?php echo '<'.$head.' class="page-title">'; ?>
                                                        <?=!empty($row['label'])?$row['label']:''?>
                                                            
                                                        <?php echo '</'.$head.'>'; ?>
                                                        <hr class="hr-10">
                                                    </div>
                                                </div>
                                       <?php
                                    }
                                    else if ($row['type'] == 'file') { ?>
                                        <div class="col-sm-12">
                                            <div class="panel panel-default tile tile-profile">
                                                <div class="panel-body">
                                                    <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                    <div class="">   
                                                        <?php 
                                                        if(!empty($mac_details[0][$row['name']]))
                                                        {
                                                            $imgs = explode(',',$mac_details[0][$row['name']]);
                                                            foreach ($imgs as $img) {
                                                                ?>
                                                                    <div class="col-sm-1 margin-bottom">
                                                                               <?php 
                                                                                if(@is_array(getimagesize($this->config->item ('medical_img_base_url').$ypid.'/'.$img))){
                                                                                       ?>
                                                                                       <img width="100" height="100" src="<?=$this->config->item ('medical_img_base_url_small').$ypid.'/'.$img?>">
                                                                                       <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <img width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                                        <?php
                                                                                    }
                                                                            ?>
                                                                            </div>
                                                                <?php
                                                            }
                                                        }
                                                        ?>                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } ?>

                                    <?php
                                } //foreach
                            }
                             ?>
                           
                            
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Medical Professionals<a class="pull-right" href="<?=base_url('Medical/add_mp/'.$ypid); ?>" title="Add Professionals"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a></h2>
                                <div class="table-responsive">
                                    <div class="whitebox" id="common_div">
                                        <?php $this->load->view('mp_ajaxlist'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Other Medical Information<a class="pull-right" href="<?=base_url('Medical/add_omi/'.$ypid); ?>" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></h2>
                                <?php
                                if(!empty($omi_form_data))
                                {
                                    foreach ($omi_form_data as $row) {
                                ?>
                                <h4><?=!empty($row['label'])?$row['label']:''?></h4>
                                <p><?=(!empty($omi_details[0][$row['name']]) && $omi_details[0][$row['name']] !='0000-00-00')?nl2br(htmlentities($omi_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></p>
                                <hr class="hr-10" />
                                <?php } } ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>
                                    Inoculations
                                    <a class="pull-right m-l-10 btn-nhs-info" href="http://www.nhs.uk/Planners/vaccinations/Pages/Vaccinationchecklist.aspx" title="NHS Info"><i class="fa fa-info-circle" aria-hidden="true"></i> NHS INFO</a>
                                    <a class="pull-right" href="<?=base_url('Medical/add_mi/'.$ypid); ?>" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                </h2>
                                 <?php
                                if(!empty($mi_form_data))
                                {
                                    foreach ($mi_form_data as $row) {

                                   if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                        ?>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                                           <?=!empty($miform_details[0][$row['name']])?nl2br(htmlentities($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                           <?php  }
                                                            else if($row['type'] == 'checkbox-group') {
                                                            if(!empty($miform_details[0][$row['name']])) {
                                                                $chk = explode(',',$miform_details[0][$row['name']]);
                                                                        foreach ($chk as $chk) {
                                                                            echo $chk."\n";
                                                                        }
                                                                     
                                                                
                                                            }else{

                                                                    if(count($row['values']) > 0) {
                                                                       
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                         }
                                                                       
                                                                    }
                                                                }?>

                                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                             if(!empty($miform_details[0][$row['name']])) {
                                                                echo !empty($miform_details[0][$row['name']])?nl2br(htmlentities($miform_details[0][$row['name']])):'';
                                                             }
                                                             else
                                                             {
                                                                 if(count($row['values']) > 0) {
                                                                        
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                                         }
                                                                        
                                                                    }
                                                             }
                                                            } ?>
                                                        </label>
                                                    
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'textarea') { ?>
                                            <div class="col-sm-6">
                                                 <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?=!empty($miform_details[0][$row['name']])?nl2br(htmlentities($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                        </label>
                                                   
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'header') {
                                           ?>
                                           <div class="col-sm-12">
                                                        <div class="">
                                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                                            <?=!empty($row['label'])?$row['label']:''?>
                                                                
                                                            <?php echo '</'.$head.'>'; ?>
                                                            <hr class="hr-10">
                                                        </div>
                                                    </div>
                                           <?php
                                        }
                                        else if ($row['type'] == 'file') { ?>
                                            <div class="col-sm-12">
                                                <div class="panel panel-default tile tile-profile">
                                                    <div class="panel-body">
                                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                        <div class="">   
                                                            <?php 
                                                            if(!empty($miform_details[0][$row['name']]))
                                                            {
                                                                $imgs = explode(',',$miform_details[0][$row['name']]);
                                                                foreach ($imgs as $img) {
                                                                    ?>
                                                                        <div class="col-sm-1 margin-bottom">
                                                                                   <?php 
                                                                                    if(@is_array(getimagesize($this->config->item ('medical_img_base_url').$ypid.'/'.$img))){
                                                                                           ?>
                                                                                           <img width="100" height="100" src="<?=$this->config->item ('medical_img_base_url_small').$ypid.'/'.$img?>">
                                                                                           <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <img width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                                            <?php
                                                                                        }
                                                                                ?>
                                                                                </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } ?>

                                        <?php
                                    } //foreach
                                }
                                 ?>
                              
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->load->view('/Common/common', '', true); ?>