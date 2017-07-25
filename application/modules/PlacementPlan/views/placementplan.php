<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <h1 class="page-title">
                    Placement Plan <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('PlacementPlan/edit/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> EDIT PP
                                </a>
                            <a href="#" class="btn btn-default">
                                <i class="fa fa-search"></i> ARCHIVE
                            </a>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?=(!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00')?date('d-m-Y',strtotime($YP_details[0]['date_of_birth'])):''?>
                </h1>
                <div class="row">
                <?php
                if(!empty($pp_form_data))
                {
                    foreach ($pp_form_data as $row) {

                   if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                        ?>
                            <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-6'?>">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                         <p class ="slimScroll-120">
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                           <?=!empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                           <?php  }
                                            else if($row['type'] == 'checkbox-group') {
                                            if(!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',',$edit_data[0][$row['name']]);
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
                                             if(!empty($edit_data[0][$row['name']])) {
                                                echo !empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):'';
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
                            </div>
                        <?php
                        }
                        else if ($row['type'] == 'header') {
                           ?>
                           <div class="col-sm-12">
                                        <div class="">
                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                            <?=!empty($row['label'])?$row['label']:''?>
                                                
                                            <?php echo '</'.$head.'>'; ?>
                                        </div>
                                    </div>
                           <?php
                        }
                        else if ($row['type'] == 'file') { ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class="">   
                                            <?php 
                                            if(!empty($edit_data[0][$row['name']]))
                                            {
                                                $imgs = explode(',',$edit_data[0][$row['name']]);
                                                foreach ($imgs as $img) {
                                                    ?>
                                                        <div class="col-sm-1 margin-bottom">
                                                                   <?php 
                                                                    if(@is_array(getimagesize($this->config->item ('pp_img_base_url').$ypid.'/'.$img))){
                                                                           ?>
                                                                           <img width="100" height="100" src="<?=$this->config->item ('pp_img_base_url_small').$ypid.'/'.$img?>">
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
                                        </div>
                                    </div>
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
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                <a href="<?=base_url('YoungPerson'); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <a href="<?=base_url('PlacementPlan/edit/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> EDIT PP
                                </a>
                                <a href="#" class="btn btn-default">
                                    <i class="fa fa-search"></i> ARCHIVE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>