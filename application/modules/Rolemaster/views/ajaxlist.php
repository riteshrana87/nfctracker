<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$role_id =  $this->config->item('super_admin_role_id');
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <?php /* ?>
            <th <?php if(isset($sortfield) && $sortfield == 'cat_name'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('cat_name','<?php echo $sorttypepass;?>')">Category Name</th>

            <th <?php if(isset($sortfield) && $sortfield == 'created_date'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"> Created Date</th>

            <th <?php if(isset($sortfield) && $sortfield == 'status'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')">  <?= lang('status')?></th>

            <th><?= lang('actions')?></th>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
 <?php */ ?>
            <th><?= lang('role_name')?></th>
            <th><?= lang('status')?></th>
            <th><?= $this->lang->line('edit_delete_perms') ?></th>
            <th><?= $this->lang->line('edit_delete_role') ?></th>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
            <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php if (isset($information) && count($information) > 0) { ?>
            <?php
            foreach ($information as $data) {
            if ($data['status'] == 1) {
                $data['status'] = lang('active');
            } else {
                $data['status'] = lang('inactive');
            }
            ?>
        <tr>
            <td><?php echo $data['role_name']; ?></td>
            <td><?php echo $data['status']; ?></td>
            <td>
                <?php if(checkPermission('Rolemaster','view')){ ?>
                    <a class="btn btn-link" href="<?php echo base_url($crnt_view . '/view_perms_to_role_list/' . $data['role_id']); ?>" title="<?= lang('view')?>" ><i class="fa fa-file-text-o" aria-hidden="true"></i></a> <?php }?>
                <?php if(checkPermission('Rolemaster','edit')){ if($data['role_id']!=$role_id){ ?>
                    <a class="btn btn-link" href="<?php echo base_url($crnt_view . '/editPermission/' . $data['role_id']); ?>" title="<?= lang('edit')?>" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <?php } }?>
            </td>
            <td class="text-center">
                <?php if(checkPermission('Rolemaster','edit')){ ?>
                    <a class="btn btn-link" href="<?php echo base_url($crnt_view . '/edit/' . $data['role_id']); ?>" title="<?= lang('edit')?>" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <?php }?>
                <?php if($data['role_id']!=$role_id){?> <?php if(checkPermission('Rolemaster','delete')){ ?><a href="javascript:;" onclick="deleteRole_t(<?php echo $data['role_id']; ?>);" class="btn btn-link" title="<?= lang('delete')?>" ><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php }?> <?php }?>
            </td>
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
    <?php }else{
        //echo '<div class="col-sm-5">'.lang('showing').' : '.count($information).'</div>';
    }// echo (!empty($pagination)) ? $pagination : ''; ?>
</div>