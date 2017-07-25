<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
        </h1>
        
        <div class="row">
            <form action="<?= base_url('Medical/insert_appointment') ?>" method="post" id="docsform" name="docsform" data-parsley-validate enctype="multipart/form-data">
                <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                <h2>ADD MEDICAL APPOINTMENT</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=!empty($mp_data[0][$form_data[0]['name']])?$mp_data[0][$form_data[0]['name']]:(isset($form_data[0]['value'])?$form_data[0]['value']:'')?></label>
                                            <p>
                                                <?=!empty($mp_data[0][$form_data[1]['name']])?$mp_data[0][$form_data[1]['name']]:(isset($form_data[1]['value'])?$form_data[1]['value']:'')?>
                                                <?=!empty($mp_data[0][$form_data[2]['name']])?$mp_data[0][$form_data[2]['name']]:(isset($form_data[2]['value'])?$form_data[2]['value']:'')?>
                                                <?=!empty($mp_data[0][$form_data[3]['name']])?$mp_data[0][$form_data[3]['name']]:(isset($form_data[3]['value'])?$form_data[3]['value']:'')?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Appointment</label>
                                                <input class="form-control" type="text" required="true" value="" name="appointment_date" id="appointment_date" readonly="">
                                           
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>TIME</label>
                                            <div class="input-group input-append bootstrap-timepicker">
                                                 <input class="form-control timepicker" type="text" name="appointment_time" id="appointment_time" readonly="" value="">
                                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Comments</label>
                                            <textarea name="comments" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="yp_id" value="<?php echo $ypid;?>" id="" >
                                        <input type="hidden" name="mp_id" value="<?= !empty($mp_data[0]['mp_id']) ? $mp_data[0]['mp_id'] : '' ?>" id="" >
                                        
                                        
                                        <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Add Medical Appointment" />
                                    </div>
                                </div>
                            </div>
                        </div>

            </form>        
        </div>

    </div>
</div>
