<?php
/*
  @Description : Module Add/edit
  @Author      : Ritesh Rana
  @Date        : 08-06-2017

 */
$this->type = ADMIN_SITE;
//$formAction = !empty($getFormData)?'edit':'add';
$path = $form_action_path;
?>
<script>
    var formAction = "<?php echo $getFormData[0]['form_json_data'];?>";
</script>
<?php /* if(isset($from_val)){
              echo html_entity_decode($from_val[0]['form_data']);exit;  
        }
 * 
 */
    ?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="nav-buttons">
      <ul class="nav nav-pills nav-justified">
        <li class="active">
          <a href="#">
            <i class="fa fa-file-text"></i>PP</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-file-text"></i>IBP</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-envelope"></i>RA</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-folder-open"></i>DO</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-file-text"></i>KS</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-edit"></i>DOCS</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-medkit"></i>MEDS</a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-phone-square"></i>COMS</a>
        </li>
      </ul>
    </div>
    <h1>
      jQuery formBuilder
    </h1>
  </section>
    <input type="hidden" id="editformjson" value='<?php echo json_encode($getFormData[0]["form_json_data"]);?>'>
<div class="content">    
    <div id="stage1" class="build-wrap"></div>
    <form class="render-wrap">
    </form>
    <button id="edit-form">Edit Form</button>
    <form id="formbuild_data" method="post" action="<?= base_url($path) ?>">
        <input type="hidden" id="rm_form_id" name="rm_form_id" value="<?php echo $id;?>">
        <input type="hidden" id="formbuild_data_val" name="form_data">
        <input type="hidden" id="form_json_data" name="form_json_data">
        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
        <div id="deletefields">
            
        </div>
</form>
    <div class="action-buttons">
      <button style="display: none" id="showData" type="button">Show Data</button>
      <button style="display: none" id="clearFields" type="button">Clear All Fields</button>
      <button style="display: none" id="getData" type="button">Get Data</button>
      <button style="display: none" id="getXML" type="button">Get XML Data</button>
      <button style="display: none" id="getJSON" type="button">Get JSON Data</button>
      <button style="display: none" id="getJS" type="button">Get JS Data</button>
      <button style="display: none" id="setData" type="button">Set Data</button>
      <button style="display: none" id="addField" type="button">Add Field</button>
      <button style="display: none" id="removeField" type="button">Remove Field</button>
      <button style="display: none" id="testSubmit" type="submit">Submit</button>
      <button style="display: none" id="resetDemo" type="button">Reset Demo</button>
      
    </div>
  </div>

</div><!-- /.content-wrapper -->
