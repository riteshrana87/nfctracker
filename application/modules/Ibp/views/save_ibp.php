<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
            UPDATE IBP
            
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4 >IBP Updated</h4>
                    <p>
                        The IBP is now updated. Please check your editing carefully.
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('Ibp/index/'.$id); ?>">
                        <button class="btn btn-primary" type="button">Check IBP</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>