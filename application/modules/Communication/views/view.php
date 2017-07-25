<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <h1 class="page-title">
                    Communication <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                             <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('Communication/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-search" aria-hidden="true"></i> BACK
                            </a>
                        </div>
                    </div>
                </h1>
                <div class="clearfix"></div>
                <div class="row m-t-10">
                    <?php
                if(!empty($form_data))
                {
                    foreach ($form_data as $row) {

                     if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                        ?>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2><?=!empty($row['label'])?$row['label']:''?>
                                </h2>
                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class ="small">
                                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                                 
                                                
                                                   
                                                   <?=!empty($medical_data[0][$row['name']])?nl2br(htmlentities($medical_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                   <?php  }
                                                    else if($row['type'] == 'checkbox-group') {
                                                    if(!empty($medical_data[0][$row['name']])) {
                                                        $chk = explode(',',$medical_data[0][$row['name']]);
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
                                                     if(!empty($medical_data[0][$row['name']])) {
                                                        echo !empty($medical_data[0][$row['name']])?nl2br(htmlentities($medical_data[0][$row['name']])):'';
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
                                            <p class="date"><small><?=!empty($medical_data[0]['create_name'])?$medical_data[0]['create_name'].' : ':''?> <?=!empty($medical_data[0]['daily_observation_date'])?$medical_data[0]['daily_observation_date']:''?> </small></p>
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
                           <div class="col-lg-12">
                                <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                               
                            </div>
                     <?php
                        }
                        else if ($row['type'] == 'file') { ?>
                            <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class ="small">
                                                <?php 
                                            if(!empty($medical_data[0][$row['name']]))
                                            {
                                                $imgs = explode(',',$medical_data[0][$row['name']]);
                                                foreach ($imgs as $img) {
                                                    ?>
                                                        <div class="col-sm-1 margin-bottom">
                                                                   <?php 
                                                                    if(@is_array(getimagesize($this->config->item ('communication_img_base_url').$ypid.'/'.$img))){
                                                                           ?>
                                                                           <img width="100" height="100" src="<?=$this->config->item ('communication_img_base_url_small').$ypid.'/'.$img?>">
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
               
            </div>
        </div>