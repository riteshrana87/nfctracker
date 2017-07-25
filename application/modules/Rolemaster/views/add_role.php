<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata?id='.$id:'insertdata';
$path = $crnt_view.'/'.$formAction;
?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">
                    <?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?>
                    </div>
                    <div class="panel-body">
                        <form <?PHP if($formAction == "insertdata"){ ?>id="addrole"<?php }else{ ?>id="addrole1"<?php }?>id="addrole" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate ="">
                            
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="parents_role">
                                    Parents Role
                                </label>
                                <div class="col-sm-9">
                                    <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Parents Role"  name="parent_role" id="parent_role" required="">
                                            <?php
                                            $salutions_id = "";
                                            if(!empty($editRecord[0]['role_id'])){
                                                $salutions_id = $editRecord[0]['role_id'];
                                            }
                                            ?>
                                            <?php foreach($information as $rows){
                                                if($salutions_id == $rows['role_id']){?>
                                                    <option selected value="<?php echo $rows['role_id'];?>"><?php echo $rows['role_name'];?></option>
                                                <?php }else{?>
                                                    <option value="<?php echo $rows['role_id'];?>"><?php echo $rows['role_name'];?></option>
                                                <?php }}?>
                                        </select>
                                   <span id="parent_error"></span>
                                </div>
                            </div>
                        </div>
                            
                            <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cat_name" class="control-label col-sm-3 required"><?=$this->lang->line('role_name')?></label>
                                <div class="col-sm-9">
                                <input class="form-control" name="role_name" placeholder="<?=$this->lang->line('role_name')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('role_name');?><?php }else{?><?=!empty($editRecord[0]['role_name'])?htmlentities($editRecord[0]['role_name']):''?><?php }?>" required="" />

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="status">
                                    <?=$this->lang->line('role_status')?>
                                </label>
                                <div class="col-sm-9">
                                    <?php
                                    $options = array('1'=>lang('active'),'0'=>lang('inactive'));
                                    $name = "status";
                                    if($formAction == "insertdata"){
                                        $selected = 1;
                                    }else{
                                        $selected = $editRecord[0]['status'];
                                    }
                                    echo dropdown( $name, $options, $selected );
                                ?>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
						<br>
                        <div class="">
                            <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                    <input name="role_id" type="hidden" value="<?=!empty($editRecord[0]['role_id'])?$editRecord[0]['role_id']:''?>" />
                                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                    <?php  if($formAction == "insertdata"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_role')?>" />
                                    <?php }else{ ?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_role')?>" />
                                    <?php }?>

                                    <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />
                                    <a href="<?php echo base_url('Rolemaster') ?>" class="btn btn-default">Cancel</a>

                                </div>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>