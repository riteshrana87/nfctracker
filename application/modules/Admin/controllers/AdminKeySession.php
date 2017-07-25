<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminKeySession extends CI_Controller {

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

    public function add() {
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
            $data['button_header'] = array('menu_module' => 'ks');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/add';
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
            'ks_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('ks_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('key_session')) {
            $this->dbforge->create_table('key_session');
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
        if ($this->common_model->insert(KS_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/edit/'. $pp_id);
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

    public function edit($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = KS_FORM;
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
            $data['button_header'] = array('menu_module' => 'ks');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/edit';
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
        $id = $this->input->post('ks_form_id');
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
        $fields_data = array('ks_id','yp_id','created_by');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('key_session');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('key_session')) {
            $this->dbforge->add_column('key_session', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('key_session');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'key_session')){
                $this->dbforge->drop_column('key_session', $delete_val);
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
            if($this->db->field_exists($val, 'key_session')){
                $this->dbforge->drop_column('key_session', $val);
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
        $where = array('ks_form_id' => $id);
        if ($this->common_model->update(KS_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/edit/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/edit/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/edit/'. $id);
    }

    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
