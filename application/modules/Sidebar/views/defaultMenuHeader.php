<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- LOGO HEADER END-->
<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse ">
                    <?php if (isset($this->session->userdata['LOGGED_IN'])) { ?>
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li <?php if (isset($param['menu_module']) && $param['menu_module'] == "Dashboard") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Dashboard'); ?>">Home</a></li>
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle <?php if (isset($param['menu_module']) && $param['menu_module'] == "masters") { ?>active<?php } ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Masters <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php  if (checkPermission('User', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "Dashboard") { ?>class="active"<?php } ?>><a href="<?php echo base_url('User'); ?>">User Master</a></li>
                                    <?php }  ?>
                                        <?php  if (checkPermission('YoungPerson', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "user") { ?>class="active"<?php } ?>><a href="<?php echo base_url('YoungPerson'); ?>">Add Young Person</a></li>
                                        <?php } ?>
                                        
<?php /* ?>
                                    <?php if (checkPermission('Director', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "OpsManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Director'); ?>">create Users</a></li>
                                    <?php } ?>
                                        
                                    <?php if (checkPermission('OpsManager', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "OpsManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('OpsManager'); ?>">Ops Manager Master</a></li>
                                    <?php } ?>

                                    <?php if (checkPermission('HeadTeacher', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "OpsManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('HeadTeacher'); ?>">Head Teacher</a></li>
                                    <?php } ?>
                                    <?php if (checkPermission('DeputyHead', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "DeputyHead") { ?>class="active"<?php } ?>><a href="<?php echo base_url('DeputyHead'); ?>">Deputy Head</a></li>
                                    <?php } ?>            
                                          <?php if (checkPermission('Teacher', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "DeputyHead") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Teacher'); ?>">Teacher</a></li>
                                    <?php } ?>   

                                        <?php if (checkPermission('SafeguardingOfficer', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "SafeguardingOfficer") { ?>class="active"<?php } ?>><a href="<?php echo base_url('SafeguardingOfficer'); ?>">Safeguarding Officer</a></li>
                                    <?php } ?>   
                                        
                                        <?php if (checkPermission('RegisteredManager', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "RegisteredManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('RegisteredManager'); ?>">Registered Manager</a></li>
                                    <?php } ?>   
                                        <?php if (checkPermission('TeamLeader', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "TeamLeader") { ?>class="active"<?php } ?>><a href="<?php echo base_url('TeamLeader'); ?>">Team Leader</a></li>
                                    <?php } ?>   
                                      
                                        <?php if (checkPermission('KeyWorker', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "KeyWorker") { ?>class="active"<?php } ?>><a href="<?php echo base_url('KeyWorker'); ?>">Key Worker</a></li>
                                    <?php } ?>   
                                        <?php if (checkPermission('CaseManager', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "CaseManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('CaseManager'); ?>">Case Manager</a></li>
                                    <?php } ?> 
                                        
                                        <?php if (checkPermission('Ncw', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "Ncw") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Ncw'); ?>">Ncw Manager</a></li>
                                    <?php } ?> 
                                        
                                        <?php if (checkPermission('TaManager', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "TaManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('TaManager'); ?>">Ta Manager</a></li>
                                    <?php } ?> 
                                        
                                        <?php if (checkPermission('LsaManager', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "LsaManager") { ?>class="active"<?php } ?>><a href="<?php echo base_url('LsaManager'); ?>">Lsa Manager</a></li>
                                    <?php } ?> 
                                        <?php */?>
                                    <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "changepassword") { ?>class="active"<?php } ?>><a href="<?php echo base_url('MyProfile/ChangePassword'); ?>"><?php echo lang('CHANGE_PASSWORD'); ?></a></li>
                                        <?php if (checkPermission('Rolemaster', 'view')) { ?>
                                              <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "changepassword") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Rolemaster');?>">Role Master</a></li>
                                        <?php } ?> 
                                    <!--<li><a href="#">Zone Assignment</a></li>-->
                                </ul>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- MENU SECTION END-->
