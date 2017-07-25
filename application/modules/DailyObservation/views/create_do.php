<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observation <small>New Forest Care</small>
          
        </h1>
       
        <div class="row">
            <form action="<?=base_url('DailyObservation/checkDo')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">Select Date</h1>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>Daily Observation Date</h2>
                                    <input type="text" required="true" name="create_date" id="create_date" readonly="">
                            </div>
                        </div>
                    </div>
                       
                     <div class="col-sm-12">
                        
   
                        <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($ypid)?$ypid:''?>">
                        
                            <button type="submit" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Check For Existing Observation</button>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To YP Info</a>
                      
                    </div>
                   
                    
            </form>        
        </div>
        
    </div>
</div>
