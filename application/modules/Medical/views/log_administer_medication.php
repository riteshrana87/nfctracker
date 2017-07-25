<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <!--<h1 class="page-title">CRISIS TEAM New <small>Forest Care</small></h1>-->
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-title">
                    Medication <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('Medical/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> MEDS
                            </a>
                            <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                        </div>
                    </div>
                </h1>
                <div class="clearfix"></div>
                <div class="panel panel-default tile tile-profile m-t-10">
                    <div class="panel-body min-h-310">
                        <h2>Administration History</h2>
                        <div class="clearfix"></div>
                        <div class="whitebox" id="common_div">
                            <?php $this->load->view('administer_ajaxlist'); ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>