<?php
$this->type = ADMIN_SITE;
$admin_session = $this->session->userdata('nfc_admin_session');

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
             <div class="pull-left image">
                <?php if (!empty($admin_session['admin_image'])) { ?> 
                    <img src="<?= $this->config->item('admin_user_small_img_url') . $admin_session['admin_image'] ?>" class="user-image" alt="User Image">
                <?php } else { ?>
                    <img src="<?php echo base_url("uploads/assets/front/images/default-user.jpg")?>" class="user-image" alt="User Image">
                <?php } ?>
            </div> 
            <div class="pull-left info">
                <p><?= !empty($admin_session['name']) ? $admin_session['name'] : '' ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php if ($this->uri->segment(2) == 'Dashboard') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Dashboard') ?>"><i class="fa fa-circle-o text-aqua"></i> <span><?= "DashBoard" ?></span></a></li>
            <?php /*if ($admin_session['admin_type'] == 1) { ?> 
                <li <?php if ($this->uri->segment(2) == 'admin_users') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/admin_users') ?>"><i class="fa fa-list-alt"></i> <span><?= $this->lang->line('admin_user_module_title') ?></span></a></li>
            <?php } */?>
            <li <?php if ($this->uri->segment(2) == 'User') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/User') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "User Master"; ?></span></a></li>
            <li <?php if ($this->uri->segment(2) == 'Rolemaster') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Rolemaster') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Role Management"; ?></span></a></li>
            
           <?php /* ?> <li><a href="<?= base_url($this->type . '/ModuleMaster') ?>"><i class="fa fa-list-alt"></i> <span>Module Master</span></a></li>
            <?php */ ?>
            <?php 
            $formdata = checkFormBuilderData(PP_FORM);
            if(!empty($formdata)){ ?>
            <li <?php if ($this->uri->segment(2) == 'AdminPlacementPlan') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/AdminPlacementPlan/edit/').'/'.$formdata[0]['pp_form_id'];?>"><i class="fa fa-list-alt"></i><span>Masterform</span></a></li>
            <?php }else{?>
            <li <?php if ($this->uri->segment(2) == 'AdminPlacementPlan') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/AdminPlacementPlan/add') ?>"><i class="fa fa-list-alt"></i> <span>Masterform</span></a></li>
            <?php }?>
            
<!--            <li <?php if ($this->uri->segment(2) == 'Reports') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Reports') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Reports Management"; ?></span></a></li>-->
            
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>