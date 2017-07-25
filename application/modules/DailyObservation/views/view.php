<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observations <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                     <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="#" class="btn btn-default">
                        <i class="fa fa-search" aria-hidden="true"></i> ARCHIVE
                    </a>
                </div>
            </div>
        </h1>
        <div class="clearfix"></div>
        <div class="row m-t-10">
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-360">
                        <h2>Overview<a class="pull-right" href="<?=base_url('DailyObservation/add_overview/'.$do_id); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Young Person</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['yp_name'])?$dodata[0]['yp_name']:''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staff</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['create_name'])?$dodata[0]['create_name']:''?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Awake</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=(!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['awake_time'])):''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Bedtime</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=(!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['bed_time'])):''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Contact</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['contact'])?$dodata[0]['contact']:''?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staffing</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <?php if(!empty($do_staff_data)) {
                                    foreach ($do_staff_data as $staff) {
                                        ?>
                                            <p><?=!empty($staff['staff_name'])?$staff['staff_name']:''?></p>
                                        <?php
                                    }
                                    } ?>
                                
                                <p><a href="<?=base_url('DailyObservation/add_staff/'.$do_id.'/'.$ypid); ?>" class="btn btn-default btn-sm">Add Staff</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-360">
                        <h2>Food Consumed<a class="pull-right" href="<?=base_url('DailyObservation/add_food/'.$do_id.'/'.$ypid); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></h2>
                        <?php   if(!empty($food_form_data))
                                {
                                    foreach ($food_form_data as $row) {
                                    if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {?>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 padding-r-0">
                                            <p class="text-right"><small><?=!empty($row['label'])?$row['label']:''?></small></p>
                                        </div>
                                        <div class="col-xs-8 col-sm-7">
                                            <p">
                                            <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                             
                                            
                                               
                                               <?=!empty($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                               <?php  }
                                                else if($row['type'] == 'checkbox-group') {
                                                if(!empty($food_data[0][$row['name']])) {
                                                    $chk = explode(',',$food_data[0][$row['name']]);
                                                            foreach ($chk as $chk) {
                                                                echo $chk."\n";
                                                            }
                                                         
                                                    
                                                }else{

                                                        if(count($row['values']) > 0) {
                                                           
                                                         foreach ($row['values'] as $chked) {
                                                            echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                             }
                                                           
                                                        }
                                                    }?>

                                               <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                 if(!empty($food_data[0][$row['name']])) {
                                                    echo !empty($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):'';
                                                 }
                                                 else
                                                 {
                                                     if(count($row['values']) > 0) {
                                                            
                                                         foreach ($row['values'] as $chked) {
                                                            echo isset($chked['selected'])?$chked['value']:'';
                                                             }
                                                            
                                                        }
                                                 }
                                                } ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    else if ($row['type'] == 'header') {
                                       ?>
                                       <div class="row">
                                       <div class="col-xs-4 col-sm-5 padding-r-0">
                                                    <div class="text-right">
                                                        <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                        <?php echo '<'.$head.' class="page-title">'; ?>
                                                        <?=!empty($row['label'])?$row['label']:''?>
                                                            
                                                        <?php echo '</'.$head.'>'; ?>
                                                    </div>
                                                </div>
                                        </div>
                                       <?php
                                    }else if ($row['type'] == 'file') {
                                       ?>
                                       <div class="row">
                                       <div class="col-xs-4 col-sm-5 padding-r-0">
                                                <p class="text-right"><?=!empty($row['label'])?$row['label']:''?></p>    
                                       </div>
                                       <div class="col-xs-8 col-sm-7 padding-r-0">
                                                 <?php 
                                                    if(!empty($food_data[0][$row['name']]))
                                                    {
                                                        $imgs = explode(',',$food_data[0][$row['name']]);
                                                        foreach ($imgs as $img) {
                                                            ?>
                                                            <?php 
                                                            if(@is_array(getimagesize($this->config->item ('do_img_base_url').$ypid.'/'.$img))){
                                                                   ?>
                                                                   <img class="margin-bottom" width="100" height="100" src="<?=$this->config->item ('do_img_base_url_small').$ypid.'/'.$img?>">
                                                                   <?php
                                                                } else {
                                                                    ?>
                                                                    <img class="margin-bottom" width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                    <?php
                                                                } } }
                                                        ?>
                                       </div>
                                        </div>
                                       <?php
                                    } ?>
                        <?php } }?>
                       
                    </div>
                </div>
            </div>
            
            <?php
        if(!empty($summary_form_data))
        {
            foreach ($summary_form_data as $row) {

             if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                ?>
          <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <h2><?=!empty($row['label'])?$row['label']:''?>
                        <form action="<?=base_url('DailyObservation/add_summary')?>" method="post">
                            <input type="hidden" name="doid" value="<?=$do_id?>">
                            <input type="hidden" name="summary_field" value='<?php echo json_encode($row)?>'>
                            <button type="submit" class="pull-right">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </form>
                        </h2>
                        <ul class="media-list media-xs">
                            <li class="media">
                                <div class="media-body">
                                    <p class ="small">
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                         
                                        
                                           
                                           <?=!empty($dodata[0][$row['name']])?nl2br(htmlentities($dodata[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                           <?php  }
                                            else if($row['type'] == 'checkbox-group') {
                                            if(!empty($dodata[0][$row['name']])) {
                                                $chk = explode(',',$dodata[0][$row['name']]);
                                                        foreach ($chk as $chk) {
                                                            echo $chk."\n";
                                                        }
                                                     
                                                
                                            }else{

                                                    if(count($row['values']) > 0) {
                                                       
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                         }
                                                       
                                                    }
                                                }?>

                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                             if(!empty($dodata[0][$row['name']])) {
                                                echo !empty($dodata[0][$row['name']])?nl2br(htmlentities($dodata[0][$row['name']])):'';
                                             }
                                             else
                                             {
                                                 if(count($row['values']) > 0) {
                                                        
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                         }
                                                        
                                                    }
                                             }
                                            } ?>
                                        </p>
                                    <p class="date"><small><?=!empty($dodata[0]['create_name'])?$dodata[0]['create_name'].' : ':''?> <?=!empty($dodata[0]['daily_observation_date'])?$dodata[0]['daily_observation_date']:''?> </small></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                }
                else if ($row['type'] == 'header') {
                   ?>
                    <div class="clearfix"></div>
                   <div class="col-lg-12">
                        <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                       
                    </div>
             <?php
                }
                else if ($row['type'] == 'file') { ?>
          <div class="clearfix"></div>
                    <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <h2><?=!empty($row['label'])?$row['label']:''?>
                            <form action="<?=base_url('DailyObservation/add_summary')?>" method="post">
                            <input type="hidden" name="doid" value="<?=$do_id?>">
                            <input type="hidden" name="summary_field" value='<?php echo json_encode($row)?>'>
                            <button type="submit" class="pull-right">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </form>
                        </h2>
                        <ul class="media-list media-xs">
                            <li class="media">
                                <div class="media-body">
                                    <p class ="small">
                                        <?php 
                                    if(!empty($dodata[0][$row['name']]))
                                    {
                                        $imgs = explode(',',$dodata[0][$row['name']]);
                                        foreach ($imgs as $img) {
                                            ?>
                                                <div class="col-sm-1 margin-bottom">
                                                           <?php 
                                                            if(@is_array(getimagesize($this->config->item ('do_img_base_url').$ypid.'/'.$img))){
                                                                   ?>
                                                                   <img width="100" height="100" src="<?=$this->config->item ('do_img_base_url_small').$ypid.'/'.$img?>">
                                                                   <?php
                                                                } else {
                                                                    ?>
                                                                    <img width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                    <?php
                                                                }
                                                        ?>
                                                        </div>
                                            <?php
                                        }
                                    }
                                    ?>  
                                        </p>
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
               <?php
                }
            } //foreach
        }
         ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <a class="btn btn-default" href="#">Archive</a>
                
            </div>
        </div>
    </div>
</div>