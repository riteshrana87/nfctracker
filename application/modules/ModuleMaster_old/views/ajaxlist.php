<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'module_name'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('module_name','<?php echo $sorttypepass;?>')">Ingredient Name</th>

            <th <?php if(isset($sortfield) && $sortfield == 'controller_name'){if($sortby == 'asc'){echo "class = 'sort-desc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('controller_name','<?php echo $sorttypepass;?>')">Type Name</th>


          <th><?= lang('actions')?></th>
          <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />       <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
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
            <td><?php echo $data['module_name'];?></td>
            <td><?php echo $data['controller_name'];?></td>
                <td class="text-center">
                <?php if(checkPermission('ModuleMaster','edit')){ ?>
                        <a class="btn btn-link" href="<?php echo base_url($crnt_view.'/edit/'.$data['module_id']);?>" title="<?= lang('edit')?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    <?php }?>
                    <?php if(checkPermission("ModuleMaster","delete")){?><a class="btn btn-link" data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['module_id']; ?>);" ><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } ?></td>
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