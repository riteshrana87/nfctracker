<div id="page-wrapper">
            <div class="main-page">
                <h1 class="page-title">Search YP Info</small></h1>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row" id="searchForm">
                                    <div class="col-sm-8">
                                        <div class="form-inline">
                                <div class="input-group search">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                    <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" value="<?=!empty($searchtext)?$searchtext:''?>">
                                      <div class="input-group-btn">
                                        <button onclick="data_search('changesearch')" class="btn btn-primary"  title=""
                                          <?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                        </button>
                                        <button class="btn btn-default" onclick="reset_data()" title=""
                                          <?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                      </div>
                                </div>

                             
                            

                            </div>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <div class="btn-group btn-view-selector" role="group" aria-label="...">
                                            <button type="button" class="btn btn-default active" id="display-table"><i class="fa fa-table" aria-hidden="true"></i></button>
                                            <button type="button" class="btn btn-default" id="display-list"><i class="fa fa-list-ul" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                        <?php echo $this->session->flashdata('msg'); ?>
                       <div class="whitebox" id="common_div">
                            <?php $this->load->view('ajaxlist'); ?>
                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
<?= $this->load->view('/Common/common', '', true); ?>