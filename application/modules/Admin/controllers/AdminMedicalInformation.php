<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMedicalInformation extends CI_Controller {

    function __construct() {
        parent::__construct();

        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
        
    }

    /*
      @Author : Ritesh rana
      @Desc   : Ingredient Category Index Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function index() {
       
    }

    /*
      @Author : ritesh rana
      @Desc   : Ingredient Type list view Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function addAuthorisations() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'mac');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addmedicalauthorisations';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addAuthorisations';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertModule();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'mac_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('mac_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('medical_authorisations_consents')) {
            $this->dbforge->create_table('medical_authorisations_consents');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(MAC_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editAuthorisations/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editAuthorisations($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = MAC_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'mac');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editAuthorisations/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editmedicalauthorisations';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateModule();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateModule() {
        $id = $this->input->post('mac_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('mac_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('medical_authorisations_consents');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('medical_authorisations_consents')) {
            $this->dbforge->add_column('medical_authorisations_consents', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('medical_authorisations_consents');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'medical_authorisations_consents')){
                $this->dbforge->drop_column('medical_authorisations_consents', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'medical_authorisations_consents')){
                $this->dbforge->drop_column('medical_authorisations_consents', $val);
            }    
       }
         
         */
       
       
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('mac_form_id' => $id);
        if ($this->common_model->update(MAC_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editAuthorisations/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editAuthorisations/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editAuthorisations/'. $id);
    }

    public function addProfessionals() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'mp');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addprofessionals';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addProfessionals';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertProfessionals();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertProfessionals() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'mp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('mp_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('medical_professionals')) {
            $this->dbforge->create_table('medical_professionals');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(MP_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editProfessionals/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editProfessionals($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = MP_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'mp');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editProfessionals/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editprofessionals';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateProfessionals();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateProfessionals() {
        $id = $this->input->post('mp_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('mp_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('medical_professionals');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('medical_professionals')) {
            $this->dbforge->add_column('medical_professionals', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('medical_professionals');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'medical_professionals')){
                $this->dbforge->drop_column('medical_professionals', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'medical_professionals')){
                $this->dbforge->drop_column('medical_professionals', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('mp_form_id' => $id);
        if ($this->common_model->update(MP_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editProfessionals/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editProfessionals/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editProfessionals/'. $id);
    }
    
    
    
    public function addOtherInformation() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'omi');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addotherinformation';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertOtherInformation';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertOtherInformation();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertOtherInformation() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'omi_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('omi_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('other_medical_information')) {
            $this->dbforge->create_table('other_medical_information');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(OMI_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editOtherInformation/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editOtherInformation($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = OMI_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'omi');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editOtherInformation/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editotherinformation';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateOtherInformation();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateOtherInformation() {
        $id = $this->input->post('omi_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('omi_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('other_medical_information');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('other_medical_information')) {
            $this->dbforge->add_column('other_medical_information', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('other_medical_information');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'other_medical_information')){
                $this->dbforge->drop_column('other_medical_information', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'other_medical_information')){
                $this->dbforge->drop_column('other_medical_information', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('omi_form_id' => $id);
        if ($this->common_model->update(OMI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editOtherInformation/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editOtherInformation/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editOtherInformation/'. $id);
    }
    
    
    public function addInoculations() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'ino');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addinoculations';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertInoculations';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertInoculations();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertInoculations() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'inoculations_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('inoculations_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('medical_inoculations')) {
            $this->dbforge->create_table('medical_inoculations');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(MI_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editInoculations/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editInoculations($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = MI_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'ino');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editInoculations/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editinoculations';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateInoculations();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateInoculations() {
        $id = $this->input->post('mi_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('inoculations_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('medical_inoculations');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('medical_inoculations')) {
            $this->dbforge->add_column('medical_inoculations', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('medical_inoculations');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'medical_inoculations')){
                $this->dbforge->drop_column('medical_inoculations', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'medical_inoculations')){
                $this->dbforge->drop_column('medical_inoculations', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('mi_form_id' => $id);
        if ($this->common_model->update(MI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editInoculations/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editInoculations/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editInoculations/'. $id);
    }
    
    
    
    public function addCommunication() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'mc');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addcommunication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertCommunication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertCommunication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertCommunication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'communication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('communication_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('medical_communication')) {
            $this->dbforge->create_table('medical_communication');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(MC_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editCommunication/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editCommunication($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = MC_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'mc');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editCommunication/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editcommunication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateCommunication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateCommunication() {
        $id = $this->input->post('mc_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('communication_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('medical_communication');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('medical_communication')) {
            $this->dbforge->add_column('medical_communication', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('medical_communication');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'medical_communication')){
                $this->dbforge->drop_column('medical_communication', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'medical_communication')){
                $this->dbforge->drop_column('medical_communication', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('mc_form_id' => $id);
        if ($this->common_model->update(MC_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editCommunication/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editCommunication/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editCommunication/'. $id);
    }
    
    
    public function addMedication() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'mm');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addmedication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertMedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertMedication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'medication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('medication_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('medication')) {
            $this->dbforge->create_table('medication');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(M_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editMedication/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editMedication($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = M_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'mm');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editMedication/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editmedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateMedication() {
        $id = $this->input->post('m_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('medication_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('medication');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('medication')) {
            $this->dbforge->add_column('medication', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('medication');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'medication')){
                $this->dbforge->drop_column('medication', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'medication')){
                $this->dbforge->drop_column('medication', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('m_form_id' => $id);
        if ($this->common_model->update(M_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editMedication/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editMedication/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editMedication/'. $id);
    }
    
    
    
    public function addAdministerMedication() {
        //pr($_POST);exit;
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'am');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addadministermedication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertAdministerMedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertAdministerMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertAdministerMedication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'administer_medication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('administer_medication_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('administer_medication')) {
            $this->dbforge->create_table('administer_medication');
        }
        //echo "test";exit;
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AM_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editAdministerMedication/'. $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function editAdministerMedication($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = AM_FORM;
            $match = "";
            $fields = array("*");
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][4] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][5] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['button_header'] = array('menu_module' => 'am');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editAdministerMedication/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editadministermedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateAdministerMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateAdministerMedication() {
        $id = $this->input->post('am_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value) {
            if ($value['type'] != 'header') {
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('administer_medication_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('administer_medication');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('administer_medication')) {
            $this->dbforge->add_column('administer_medication', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('administer_medication');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'administer_medication')){
                $this->dbforge->drop_column('administer_medication', $delete_val);
            }    
        }
        /*
        $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'administer_medication')){
                $this->dbforge->drop_column('administer_medication', $val);
            }    
       }
       */
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => $form_json_data,
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('am_form_id' => $id);
        if ($this->common_model->update(AM_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editAdministerMedication/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editAdministerMedication/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editAdministerMedication/'. $id);
    }
    
    
    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
