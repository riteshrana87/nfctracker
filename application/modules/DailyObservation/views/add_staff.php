<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observation <small>New Forest Care</small>
          
        </h1>
       
        <div class="row">
            <form action="<?=base_url('DailyObservation/insert_staff')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">ADD STAFF</h1>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>Select Staff</h2>
                                    <select name="staff" id="staff" class="form-control">
                                        <?php if(!empty($userdata)) { 
                                            foreach ($userdata as $row) {
                                           ?>
                                           <option <?=($this->session->userdata('LOGGED_IN')['ID'] == $row['login_id'])?'selected="selected"':''?> value="<?=!empty($row['login_id'])?$row['login_id']:''?>"><?=!empty($row['username'])?$row['username']:''?></option>
                                           <?php
                                        }}?>
                                        
                                    </select>
                            </div>
                        </div>
                    </div>
                       
                     <div class="col-sm-12">
                        
   
                        <input type="hidden" name="do_id" id="do_id" value="<?=!empty($do_id)?$do_id:''?>">
                        
                            <button type="submit" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Add Staff</button>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To YP Info</a>
                      
                    </div>
                   
                    
            </form>        
        </div>
        
    </div>
</div>
        