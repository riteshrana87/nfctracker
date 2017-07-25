<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
           <?=!empty($header)?$header:''?>
            
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    
                    <p>
                        <?=!empty($detail)?$detail:''?>
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('Medical/index/'.$yp_id); ?>">
                        <button class="btn btn-primary" type="button">Check Meds</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>