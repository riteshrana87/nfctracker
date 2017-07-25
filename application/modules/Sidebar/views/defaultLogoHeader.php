<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
$profile_src = base_url() . "uploads/assets/front/images/default-user.jpg";
if (isset($user_info['PROFILE_PHOTO']) && $user_info['PROFILE_PHOTO'] != '') {
    $explode_name = explode('.', $user_info['PROFILE_PHOTO']);
    $thumbnail_name = $explode_name[0] . '_thumb.' . $explode_name[1];
    $file = (FCPATH . "uploads/profile_photo/" . $thumbnail_name);

    if (file_exists($file)) {
        $profile_src = base_url() . "uploads/profile_photo/" . $thumbnail_name;
    } else {
        $profile_src = base_url() . "uploads/assets/front/images/default-user.jpg";
    }
}
?>
<?php if (isset($user_info) && !empty($user_info)) { ?>
<div class="sticky-header header-section ">
            <div class="header-left">
                <!--logo -->
                <div class="logo">
                    <a href="<?= base_url() ?>">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </a>
                </div>
                <!--//logo-->
                <div class="clearfix"> </div>
            </div>
            <div class="header-right">
                <div class="profile_details_left">
                    <!--notifications of menu start -->
                    <ul class="nofitications-dropdown">
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "Dashboard") { ?>active<?php } ?>">
                            <a href="<?= base_url('Dashboard');?>" class="dropdown-toggle">CRISIS INFO</a>
                        </li>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "YoungPerson") { ?>active<?php } ?>">
                            <a href="<?= base_url('YoungPerson'); ?>" class="dropdown-toggle">YP INFO</a>
                        </li>
                    </ul>
                    <div class="clearfix"> </div>
                </div>
                <!--notification menu end -->
                <div class="profile_details">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img"><img src="<?= $profile_src ?>" alt=""> </span>
                                    <div class="user-name">
                                <?php if (isset($user_info) && !empty($user_info)) { ?>
				<p><?php echo $user_info['FIRSTNAME'] . ' ' . $user_info['LASTNAME']; ?></p>
                                        <span><?php echo $user_role_data[0]['role_name'];?></span>
                                    <?php }?>
                                    </div>
                                    <i class="fa fa-angle-down lnr"></i>
                                    <i class="fa fa-angle-up lnr"></i>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li> <a href="<?php echo base_url('MyProfile'); ?>"><i class="fa fa-user"></i> My Profile</a> </li>
                                <li> <a href="<?php echo base_url('MyProfile/ChangePassword'); ?>"><i class="fa fa-cog"></i> Change Password</a> </li>
                                <li> <a href="<?php echo base_url('Dashboard/logout/'); ?>"><i class="fa fa-sign-out"></i> Logout</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
<?php }?>