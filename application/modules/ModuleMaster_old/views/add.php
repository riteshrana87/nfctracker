<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'edit':'add';
$path = $form_action_path;
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
    <div class="container">
      <div clas="row">
        <div class="col-md-12 error-list">
          <?= isset($validation) ? $validation : ''; ?>
        </div>
      </div>
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">
                    <?PHP if($formAction == "add"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?>

                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?PHP if($formAction == "add"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?>
                    </div>
                    <div class="panel-body">
                        <form id="moduleform" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="component_name" class="control-label col-sm-3 required"><?=$this->lang->line('component_name')?></label>
                                <div class="col-sm-9">
                                    <?php
                                    $options = array('ERP'=>"ERP");
                                    $name = "component_name";
                                    if($formAction == "add"){
                                        $selected = 1;
                                    }else{
                                        $selected = $editModuleRecord[0]['component_name'];
                                    }
                                    echo dropdown( $name, $options, $selected );
                                    ?>
                                    <span class="text-danger"><?php echo form_error('component_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="module_name" class="control-label col-sm-3 required"><?=$this->lang->line('module_name')?></label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="module_name" placeholder="<?=$this->lang->line('module_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['module_name'])?$editModuleRecord[0]['module_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
                                    <span class="text-danger"><?php echo form_error('module_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="controller_name" class="control-label col-sm-3 required"><?=$this->lang->line('controller_name')?></label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="controller_name" placeholder="<?=$this->lang->line('controller_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['controller_name'])?$editModuleRecord[0]['controller_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
                                    <span class="text-danger"><?php echo form_error('controller_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="module_status" class="control-label col-sm-3 required"><?=$this->lang->line('module_status')?></label>
                                <div class="col-sm-9">
                                    <?php
                                    $options = array('1'=>"Active",'0'=>"InActive");
                                    $name = "module_status";
                                    if($formAction == "add"){
                                        $selected = 1;
                                    }else{
                                        $selected = $editModuleRecord[0]['status'];
                                    }
                                    echo dropdown( $name, $options, $selected );
                                    ?>
                                    <span class="text-danger"><?php echo form_error('module_status'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                    <input name="module_id" type="hidden" value="<?=!empty($editModuleRecord[0]['module_id'])?$editModuleRecord[0]['module_id']:''?>" />
                                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                    <?php if($formAction == "add"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_module')?>" />
                                    <?php }else{?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_module')?>" />
                                    <?php }?>
                                    <input type="reset"  class="btn btn-info" name="remove"  value="Reset" />
                                    <a href="<?php echo base_url()?>/ModuleMaster" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
