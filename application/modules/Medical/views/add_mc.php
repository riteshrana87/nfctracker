<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <h1 class="page-title">
                    MEDICAL INFORMATION <small>New Forest Care</small>
                  
                </h1>
               
                <div class="row">
                    <form action="<?=base_url('Medical/insert_mc')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title">ADD MEDICAL COMMUNICATION</h1>
                                </div>
                            </div>
                            
                        <?php
                        // /pr($form_data);
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                              

                            if($row['type'] == 'textarea') {
                                ?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <textarea 
                                                 class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                 <?=!empty($row['required'])?'required=true':''?>
                                                 name="<?=!empty($row['name'])?$row['name']:''?>" 
                                                 placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                 <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                 <?=!empty($row['rows'])?'rows="'.$row['rows'].'"':''?>
                                                 id="<?=!empty($row['name'])?$row['name']:''?>" ><?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?></textarea>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                                { 
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                              <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'input-group input-append bootstrap-timepicker':''?>">
                                               <input type="<?=(!empty($row['type']) && $row['type']=='number')?'number':((!empty($row['subtype']) && $row['subtype'] !='time')?$row['subtype']:'text')?>"
                                                class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                <?=!empty($row['required'])?'required=true':''?>
                                                name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" 
                                                <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                <?=!empty($row['min'])?'min="'.$row['min'].'"':''?>
                                                <?=!empty($row['max'])?'max="'.$row['max'].'"':''?>
                                                <?=!empty($row['step'])?'step="'.$row['step'].'"':''?>
                                                placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                value="<?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>" <?=($row['type'] =='date')?'readonly':''?> />
                                                  <?php if(!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $("<?=!empty($row['name'])?'#'.$row['name']:'#'?>").timepicker({
                                                                defaultTime: '',
                                                            });
                                                          
                                                        });
                                                    </script>
                                                    <?php } ?>
                                                 <?php if($row['type'] == 'date') {?>
                                                
                                                </span>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $("<?=!empty($row['name'])?'#'.$row['name']:'#'?>").datepicker({
                                                                format: 'yyyy-mm-dd',
                                                            });
                                                        });
                                                    </script>
                                                    <?php } ?>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                <?php
                                }
                                else if($row['type'] == 'radio-group')
                                {
                                ?>
                                <div class="col-sm-6">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <div class="radio-group">
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $radio) {
                                                     if(!empty($radio['label'])) {

                                                  ?>
                                                 <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">
                                                     <label ><input name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                                                         class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                         value="<?=!empty($radio['value'])?$radio['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value'])?'checked="checked"':isset($radio['selected'])?'checked="checked"':''?>  type="radio">
                                                     <?=!empty($radio['label'])?$radio['label']:''?></label>
                                                 </div>
                                                <?php } } } //radio loop ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else if($row['type'] == 'checkbox-group')
                                {
                                ?>
                                <div class="col-sm-6">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <div class="checkbox-group">
                                                 <?php if(count($row['values']) > 0) {
                                                    $checkedValues =array();
                                                    if(!empty($edit_data[0][$row['name']]))
                                                    {
                                                    $checkedValues = explode(',',$edit_data[0][$row['name']]);
                                                    }
                                                 foreach ($row['values'] as $checkbox) {
                                                     if(!empty($checkbox['label'])) {
                                                  ?>
                                                 <div class="<?=!empty($row['inline'])?'checkbox-inline':'checkbox'?>">
                                                     <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>"><input 
                                                        class="<?=!empty($row['className'])?$row['className']:''?> <?=!empty($row['toggle'])?'kc-toggle':''?>"
                                                       name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'])?'checked="checked"':''?>  
                                                            <?=!empty($row['required'])?'required=true':''?>
                                                            type="checkbox">
                                                     <?=!empty($checkbox['label'])?$checkbox['label']:''?></label>
                                                 </div>
                                                <?php } } } //radio loop ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else if($row['type'] == 'select')
                                {
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                             
                                                 <select class="<?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                                 <option value="">Select</option>
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $select) {
                                                     if(!empty($select['label'])) {
                                                  ?>
                                                  <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                                <?php } } } //select loop ?>
                                                
                                                 </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                else if($row['type'] == 'hidden' || $row['type'] == 'button')
                                {
                                    ?>
                                     <?php if($row['type'] == 'button'){ ?>
                                     <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <div class="fb-button form-group">
                                               
                                                    <button name="<?=!empty($row['name'])?$row['name']:''?>" value="" type="<?=!empty($row['type'])?$row['type']:''?>" class="<?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" style="<?=!empty($row['style'])?$row['style']:''?>"><?=!empty($row['label'])?$row['label']:''?></button>
                                                
                                               
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                     <?php if($row['type'] == 'hidden'){ ?>
                                     <div class="col-sm-12">
                                        <input type="hidden" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" value="" />
                                        </div>
                                    <?php } ?>
                                <?php
                                }
                                else if($row['type'] == 'header')
                                {
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="">
                                            <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                                        </div>
                                    </div>
                                <?php } else if($row['type'] == 'file')
                                {?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                            <h2 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h2>
                                            <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                <?=!empty($row['multiple'])?'multiple="true"':''?> >
                                                <h2></h2>
                                                        <?php 
                                                    if(!empty($edit_data[0][$row['name']]))
                                                    {
                                                        $imgs = explode(',',$edit_data[0][$row['name']]);
                                                        foreach ($imgs as $img) {
                                                            ?>
                                                                <div class="col-sm-1 margin-bottom">
                                                                <?php 
                                                                    if(@is_array(getimagesize($this->config->item ('medical_img_base_url').$ypid.'/'.$img))){
                                                                           ?>
                                                                           <img width="100" height="100" src="<?=$this->config->item ('medical_img_base_url_small').$ypid.'/'.$img?>">
                                                                           <?php
                                                                        } else {
                                                                            ?>
                                                                            <img width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                            <?php
                                                                        }
                                                                ?>
                                                                     <span class="astrick delete_img" onclick="delete_img(this,'<?=$img?>','<?='hidden_'.$row['name']?>')">X</span>
                                                                </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                            </div>

                                        </div>
                                    </div>

                                <?php
                                }
                            } //foreach
                            ?>
                            
                             <div class="col-sm-12">
                                <div class="">
                                <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>">
                                 <input type="hidden" name="summary_field" value='<?=!empty($summary_field)?$summary_field:''?>'>
                                </div>
                            </div>
                            <?php
                        }
                         ?>
                            
                    
                               
                             <div class="col-sm-12">
                                
                                    <button type="submit" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Submit</button>
                                    <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To YP Info</a>
                              
                            </div>
                           
                            
                    </form>        
                </div>
                
            </div>
        </div>
        