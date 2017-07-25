<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            DAILY OBSERVATIONS <small>New Forest Care</small>
          
        </h1>
        
        <div class="row">
            <form action="<?=base_url($this->viewname.'/insert_overview')?>" method="post" id="ppform" name="ppform" data-parsley-validate enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">EDIT OVERVIEW</h1>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>WAKING TIME</h2>
                                    <div class="input-group input-append bootstrap-timepicker">
                                         <input class="form-control timepicker" type="text" name="awake_time" id="awake_time" readonly="" value="<?=(!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['awake_time'])):''?>">
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                        
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>BED TIME</h2>
                                    <div class="input-group input-append bootstrap-timepicker">
                                         <input class="form-control timepicker" type="text" name="bed_time" id="bed_time" readonly="" value="<?=(!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['bed_time'])):''?>">
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                   
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>CONTACT</h2>
                                    <textarea class="form-control" type="text" name="contact" id="contact" ><?=!empty($dodata[0]['contact'])?$dodata[0]['contact']:''?></textarea>
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-12">
                        <div class="">
                        <input type="hidden" name="do_id" id="do_id" value="<?=!empty($dodata[0]['do_id'])?$dodata[0]['do_id']:''?>">
                        <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($dodata[0]['yp_id'])?$dodata[0]['yp_id']:$ypid?>">
                        
                            <button type="submit" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Submit</button>
                        </div>
                    </div>
                   
            </form>        
        </div>
        
    </div>
</div>
        