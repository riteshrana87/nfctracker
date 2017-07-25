<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
          KEY SESSION
            
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4 >Key Session Saved</h4>
                    <p>
                        The key session has been saved. Please review your notes.
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('KeySession/view/'.$ks_id.'/'.$yp_id); ?>">
                        <button class="btn btn-primary" type="button">Review Key Session</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>