<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <h1 class="page-title">
                    Key Session <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('KeySession/create/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> CREATE KS
                                </a>
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?=(!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00')?date('d-m-Y',strtotime($YP_details[0]['date_of_birth'])):''?>
                </h1>
                <div class="row">
                    <div class="whitebox" id="common_div">
                            <?php $this->load->view('ajaxlist'); ?>
                            <!-- Listing of User List Table: End -->
                        </div>
                </div>
                
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <a href="<?=base_url('KeySession/create/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> CREATE KS
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>