<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms/';
$path = $crnt_view . '/' . $formAction;
?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>

<?php  echo $this->session->flashdata('verify_msg'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">
             <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $this->lang->line('viewPerms') ?></h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                         <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
                        
                    <div class="modal-body">
                        
                        <div class="form-group">
                                <label for="parent_role">Parents Role:</label>:
                                    <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error" placeholder="<?=$this->lang->line('usertype')?>"  name="usertype" id="usertype" required="" <?php echo $disable; ?> >
                                    <?php
                                            $salutions_id = "";
                                            if(!empty($parednt_data)){
                                                $salutions_id = $parednt_data;
                                            }
                                            foreach ($parents_role as $key => $values) {
                                        if ($this->uri->segment(4) !== $values['role_id']){
                                            if($salutions_id == $values['role_id']){?>
                        <option selected value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>     <?php }else{ ?>
                        <option value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>
                                
                                        <?php } } } ?>
                                                         
                                 </select>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>

                                </label>
                            </div>
                        
                        
                            <div class="form-group">
                                <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>:
                                    <?php
                                    //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                    $options1 = array();
                                    $options2 = array();
                                    $selected = "";
                                    foreach ($userType as $key => $value) {
                                        if ($this->uri->segment(4) == $value['role_id']) {
                                            echo $value['role_name'];
                                            echo "<input type='hidden' name='usertype' value='".$value['role_id']."'>";
                                        }
                                    }

                                    ?>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>

                                </label>
                            </div>
                            <div class="form-group" id="edit_CRM_LIST" >

                                    <table class="table table-responsive" >
                                        <thead>
                                        <th><?php echo lang('module_list') ?></th>
                                        <?php
                                        if (count($getPermList) > 0) {
                                            foreach ($getPermList as $perm) {
                                                ?>

                                                <th><input type="checkbox" class="edit_CRM_LIST_parent_horizontal_checkbox" data-tag="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></th>

                                                <?php
                                            }
                                        }
                                        ?>
                                        <th><input type="checkbox"  class="edit_CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></th>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($CRM_module_list) > 0) {
                                            foreach ($CRM_module_list as $modObj) {
                                                $counter = 0;
                                                ?>
                                                <tr>

                                                    <td><?php echo $modObj['module_name']; ?></td>
                                                    <?php
                                                    if (count($getPermList) > 0) {
                                                        foreach ($getPermList as $perm) {
                                                            ?>

                                                            <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id']; ?>"
                                                                    <?php
                                                                    foreach ($view_perms_to_role_list as $assignData) {
                                                                        $checked = '';
                                                                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                            echo $checked = "checked=true";
                                                                            $counter++;
                                                                        } else {
                                                                            echo $checked = "";
                                                                        }
                                                                    }
                                                                    ?>

                                                                       class="child <?php echo $modObj['module_unique_name']; ?> child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                    <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_CRM_LIST" ></td>
                                                    <!--  <td><input type="checkbox" name=""></td> -->
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>


                                </div>
                        <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
                                    <input type="hidden" name="editPerm" value="Edit Permissions">
                                    <?php if($formAction == "insertAssginPerms"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />
                                    <?php }else{?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('edit_perms')?>" />
                                    <?php }?>


                                    <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>

                        </div>
                        
                     </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
