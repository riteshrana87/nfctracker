<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="main-content login-main-content">
        <!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page login-page ">
                <div class="widget-shadow">
                    <div class="login-top">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </div>
                    <div class="login-body">
                         <?php echo $this->session->flashdata('msgs'); ?>
                            <?php if(isset($error) && !empty($error)){ ?>
                                    <?php echo $error; ?>
                            <?php } ?>
                        <h1 class="page-title">Login</h1>
                            <?php 
		$attributes = array("name" => "frmlogin", "id" => "frmlogin", "data-parsley-validate" => "");
	  echo form_open('Masteradmin/verifylogin', $attributes); ?>
                       <input type="text" class="user" name="email" placeholder="Enter your email" required="">
                       <input type="password" name="password" class="lock" placeholder="Password" data-parsley-minlength="5">
                        <input type="submit" name="Sign In" value="Sign In">
                        <input type="hidden" name="timezone" id="timezone">
                        
                        <div class="forgot-grid">
                                <label class="checkbox">
                                    <input type="checkbox" name="checkbox" checked=""><i></i>Remember me
                                </label>
                            
                            
                                <div class="forgot">
                                    <a href="<?php echo base_url('Masteradmin/forgotpassword'); ?>">forgot password?</a>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                       <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--footer-->
        
        <!--//footer-->
    </div>