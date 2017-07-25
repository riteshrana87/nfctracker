<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <?php $this->load->view(ADMIN_SITE . '/assets/reporttype'); ?>
        <h1>
           Reports
        </h1>
        <?php /*<ol class="breadcrumb">
            <li><a href="<?=base_url($this->type.'/dashboard')?>"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Reports</li>
        </ol> */ ?>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Reports</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <?php if(($this->session->flashdata('message_session'))){ ?>
                            <div class="col-sm-12 text-center" id="div_msg">
                                <?php echo $this->session->flashdata('message_session');?>
                            </div>
                            <?php } ?>
                            <div class="row">                                
                                <div class="col-sm-12">
                                    <div id="example1_filter" class="dataTables_filter">
                                        <label>
                                            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                            Search:<input type="text" name="searchtext" id="searchtext" class="form-control input-sm" placeholder="" aria-controls="example1" value="<?=!empty($searchtext)?$searchtext:''?>">
                                            <button onclick="data_search('changesearch')" class="btn btn-primary howler"  title="Search">Search</button>
                                            <button class="btn btn-primary howler flt" title="Reset" onclick="reset_data()" title="Reset">Reset</button>
                                        </label>
                                    </div>
                                </div>
                            </div>
                             <div class="clearfix"></div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="common_div">
                                        <?=$this->load->view($this->type.'/'.$this->viewname.'/ajax_list','',true);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->