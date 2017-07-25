<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$role_id =  $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'name'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('name','<?php echo $sorttypepass;?>')"><?= lang('name')?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'email'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('email','<?php echo $sorttypepass;?>')"> <?= lang('emails')?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'mobile_number'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('mobile_number','<?php echo $sorttypepass;?>')">  Mobile Number</th>

            <th <?php if(isset($sortfield) && $sortfield == 'role_name'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('role_name','<?php echo $sorttypepass;?>')">  <?= lang('usertype')?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'status'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')">  <?= lang('status')?></th>
            <th><?= lang('actions')?></th>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />


        </tr>
        </thead>
        <tbody>
        <tr>
            <?php if(isset($information) && count($information) > 0 ){ ?>
            <?php foreach($information as $data){
            if($data['status'] == 'active'){
                $data['status'] = lang('active');
            }else{
                $data['status'] = lang('inactive');
            }?>
        <tr>
            <td><?php echo $data['name'];?></td>
            <td><?php echo $data['email'];?></td>
            <td><?php echo $data['mobile_number'];?></td>
            <td><?php echo $data['user_type'];?></td>
            <td><?php echo $data['status'];?></td>
            <?php if($this->session->userdata['LOGGED_IN']['ROLE_TYPE'] != $role_id) {?>
                <td class="bd-actbn-btn">

                    <?php if(checkPermission('User','edit')){ ?>
                        <a class="btn btn-link" href="<?php echo base_url($crnt_view.'/edit/'.$data['login_id']);?>" title="<?= lang('edit')?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    <?php }?>
                    <?php if(checkPermission("User","delete")){ if($this->session->userdata['LOGGED_IN']['ID'] != $data['login_id'] && $master_user_id != $data['login_id']){?><a class="btn btn-link" data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['login_id']; ?>);" ><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } } ?>

                </td>
            <?php }else{?>
                <td class="text-center">
                    <?php /*  if(checkPermission('User','view')){ ?>
                        <a class="btn btn-link" href="<?php echo base_url($crnt_view.'/view/'.$data['login_id']);?>" title="<?= lang('view')?>" >
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </a>
                    <?php } */?>

                    <?php if(checkPermission('User','edit')){ ?>
                        <a class="btn btn-link" href="<?php echo base_url($crnt_view.'/edit/'.$data['login_id']);?>" title="<?= lang('edit')?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    <?php }?>
                    <?php if(checkPermission("User","delete")){ if($this->session->userdata['LOGGED_IN']['ID'] != $data['login_id'] && $master_user_id != $data['login_id']){?><a class="btn btn-link" data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['login_id']; ?>);" ><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } } ?>

                </td>
            <?php }?>
        </tr>
        <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>

            </tr>
        <?php } ?>
        </tr>
        </tbody>
    </table>
</div>
<div class="clearfix visible-xs-block"></div>
<div class="row" id="common_tb">
    <?php if (isset($pagination) && !empty($pagination)) {?>
        <div class="col-sm-12">
            <?php echo $pagination;?>
        </div>
    <?php }?>
</div>