<?php
//pr($information);exit;
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$role_id = $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
?>
<div class="row" id="table-view">
    <div class="col-xs-12 m-t-10">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'yp_initials') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('yp_initials', '<?php echo $sorttypepass; ?>')">  Initials</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')">Full Name</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'create_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('create_name', '<?php echo $sorttypepass; ?>')"> Created Name</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"> Placement Date</th>

                        <th><?= lang('actions') ?></th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />  
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        
                            <tr>
                                <td><?php echo $data['yp_initials']; ?></td>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['create_name']; ?></td>
                                <td><?php echo $data['created_date']; ?></td>
                                <td class="text-center"><a href="<?php echo base_url($crnt_view . '/view/' . $data['yp_id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--<ul class="pagination m-tb-0">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    -->
  <div class="clearfix"></div>
    <div id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>

</div>
<div class="row m-t-10" id="list-view" style="display: none;">

    <?php if (isset($information) && count($information) > 0) { ?>
        <?php foreach ($information as $data) { ?>
            <div class="col-lg-4 col-sm-6">
                <div class="panel panel-default tile">
                    <div class="panel-body">
                        <a href="<?php echo base_url($crnt_view . '/view/' . $data['yp_id']); ?>">
                            <h2><?php echo $data['name']; ?> 
                                <?php if ($data['gender'] == "F") { ?>
                                    <i>(Female)</i>
                                <?php } elseif ($data['gender'] == "M") { ?>
                                    <i>(Male)</i>
                                <?php } else { ?>
                                    <i>()</i>
                                <?php } ?>

                            </h2>
                            <div class="yp-short-info">


                                <?php if (empty($data['profile_img'])) { ?>
                                    <img class="pic" src="<?php echo base_url(); ?>uploads/images/profile-default.png"" />
                                    <?php
                                } else {
                                    if (file_exists(FCPATH . $this->config->item('yp_img_upload_path') . $data['profile_img'])) {
                                        ?>
                                        <img class="pic" src="<?= base_url() . $this->config->item('yp_img_upload_path') . $data['profile_img'] ?>" />
                                    <?php } else { ?>
                                    <img class="pic" src="<?php echo base_url(); ?>uploads/images/profile-default.png"" />
                                        <?php
                                    }
                                }
                                ?>



                                <h6>Age: <b><?php echo date_diff(date_create($data['date_of_birth']), date_create('today'))->y; ?></b></h6>
                                <h6>DOB: <b><?php echo $data['date_of_birth']; ?></b></h6>
                                <h6>Statting Ratio: <b><?php echo $data['staffing_ratio']; ?></b></h6>
                                <h6>Placement: <b></b></h6>
                            </div>
                        </a>                                                
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <!--        <ul class="pagination m-tb-0">
                <li class="disabled"><a href="#">&laquo;</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
            </ul>-->
    <div class="row" id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>

</div>
<div class="row">
    <div class="col-md-12 m-t-10">
        <a data-href="<?php echo base_url() . 'YoungPerson/registration'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default" >Add New YP</a>

    </div>
</div>