<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
           New Crisis Notice Added
            
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    
                    <p>
                       You have added a new Medication:
                    </p>
                    <?php 
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                ?>
                                <p>
                                <label><?=!empty($row['label'])?$row['label']:''?> :</label>
                                <?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>
                                </p>
                                <?php
                        }
                        }
                    ?>
                    <p>
                        <a class="" href="<?php echo base_url('Medical/index/'.$edit_data[0]['yp_id']); ?>">
                        <button class="btn btn-primary" type="button">Back To Meds</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>