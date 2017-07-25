<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
        } ?>
        <h1 class="page-title">
          Documents
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4>Documents Saved</h4>
                    <p>
                        Your file has been successfully uploaded 
                    </p>
                    <p>
                        <a class="" href="<?=base_url('YoungPerson'); ?>">
                        <button class="btn btn-primary" type="button">Back To YP Info</button></a>
                        
                        <a class="" href="<?=base_url('Documents/create/'.$yp_id); ?>">
                        <button class="btn btn-primary" type="button">Add File</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>