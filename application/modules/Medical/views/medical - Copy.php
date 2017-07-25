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
                                <h2>Medical Authorisations & Consents<a class="pull-right" href="<?=base_url('Medical/add_mac/'.$ypid); ?>" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></h2>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-t-10 m-b-10">
                                        <thead>
                                            <tr>
                                                <th class="">Concent</th>
                                                <th class="">Date Received</th>
                                                <th class="">Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!empty($mac_form_data))
                                        {
                                            $i= 1;
                                            foreach ($mac_form_data as $row) {
                                               /*echo 'sa'.$i;
                                                if($i == 4)
                                                { 
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                }*/
                                                ?>
                                                <?php if($row['type'] == 'header') { 
                                                    echo '</tr>';
                                                    echo '<tr>';?>
                                                <td><?=!empty($row['label'])?$row['label']:''?></td>
                                                <?php } else { ?>
                                                <td><?=(!empty($mac_details[0][$row['name']]) && $mac_details[0][$row['name']] !='0000-00-00')?nl2br(htmlentities($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></td>
                                                <?php } ?>
                                                
                                          
                                            <?php $i++;} echo '</tr>';?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="<?=!empty($mac_form_data)?count($mac_form_data):'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                                            </tr>
                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
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
                                    <table class="table table-bordered table-striped m-t-10 m-b-10">
                                        <thead>
                                            <tr>
                                             <?php
                                             if (isset($sortby) && $sortby == 'asc') {
                                                $sorttypepass = 'desc';
                                            } else {
                                                $sorttypepass = 'asc';
                                            }
                                                if(!empty($mp_form_data))
                                                {
                                                    foreach ($mp_form_data as $row) {
                                                        ?>
                                                    <th <?php
                                                        if (isset($sortfield) && $sortfield == $row['name']) {
                                                            if ($sortby == 'asc') {
                                                                echo "class = 'sort-dsc'";
                                                            } else {
                                                                echo "class = 'sort-asc'";
                                                            }
                                                        } else {
                                                            echo "class = 'sort'";
                                                        }
                                                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting($row['name'], '<?php echo $sorttypepass; ?>')">  <?=!empty($row['label'])?$row['label']:''?></th>
                                                <?php } }?>
                                                <th class="text-center">Appointments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($mp_details) && count($mp_details) > 0) { ?>
                                                    <?php foreach ($mp_details as $data) { ?>
                                                        <tbody>
                                                            <tr>
                                                            <?php
                                                        if(!empty($mp_form_data))
                                                        {
                                                            foreach ($mp_form_data as $row) {
                                                                ?>
                                                                <td> <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')?nl2br(htmlentities($data[$row['name']])):(isset($row['value'])?$row['value']:'')?></td>
                                                                <?php } }?>
                                                                <td class="text-center">
                                                                    <a href="<?php echo base_url($crnt_view.'/add_appointment/'.$data['mp_id'].'/'.$ypid);?>">
                                                                    <button type="button" class="btn btn-info btn-xs">
                                                                        <i class="fa fa-plus-circle"></i> Add &nbsp;
                                                                    </button>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                           <td colspan="<?=!empty($mp_form_data)?count($mp_form_data):'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                                                        </tr>
                                                    <?php } ?>
                                        </tbody>
                                    </table>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-t-10 m-b-10">
                                        <thead>
                                            <tr>
                                                <th class="sort">Inoculations</th>
                                                <th class="sort text-center">Date Received</th>
                                                <th class="sort text-center">Date Due</th>
                                                <th>Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!empty($mi_form_data))
                                        {
                                            $i= 1;
                                            foreach ($mi_form_data as $row) {
                                               /*echo 'sa'.$i;
                                                if($i == 4)
                                                { 
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                }*/
                                                ?>
                                                <?php if($row['type'] == 'header') { 
                                                    echo '</tr>';
                                                    echo '<tr>';?>
                                                <td><?=!empty($row['label'])?$row['label']:''?></td>
                                                <?php } else { ?>
                                                <td><?=(!empty($miform_details[0][$row['name']]) && $miform_details[0][$row['name']] !='0000-00-00')?nl2br(htmlentities($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></td>
                                                <?php } ?>
                                                
                                          
                                            <?php $i++;} echo '</tr>';?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="<?=!empty($mac_form_data)?count($mac_form_data):'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                                            </tr>
                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                
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