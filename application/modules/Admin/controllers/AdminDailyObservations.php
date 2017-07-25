<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDailyObservations extends CI_Controller {

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
            $data['button_header'] = array('menu_module' => 'do');
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
            'do_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),'daily_observation_date' => array('type' => 'DATE', 'unsigned' => TRUE),'awake_time' => array('type' => 'TIME', 'unsigned' => TRUE),'bed_time' => array('type' => 'TIME', 'unsigned' => TRUE),'contact' => array('type' => 'TEXT', 'unsigned' => TRUE),'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE) );
        
        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('do_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('daily_observations')) {
            $this->dbforge->create_table('daily_observations');
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
        if ($this->common_model->insert(DO_FORM, $data_list)) {
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
            $table = DO_FORM;
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
            $data['button_header'] = array('menu_module' => 'do');
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
        $id = $this->input->post('do_form_id');
        
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
        $fields_data = array('do_id','yp_id','daily_observation_date','awake_time','bed_time','contact','created_by');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('daily_observations');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('daily_observations')) {
            $this->dbforge->add_column('daily_observations', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('daily_observations');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'daily_observations')){
                $this->dbforge->drop_column('daily_observations', $delete_val);
            }    
        }
        
       /* $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'daily_observations')){
                $this->dbforge->drop_column('daily_observations', $val);
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
        $where = array('do_form_id' => $id);
        if ($this->common_model->update(DO_FORM, $data_list, $where)) {
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
    
    
    
    //start do overview page 
    
     public function addOverview() {
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
            $data['button_header'] = array('menu_module' => 'overview');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addoverview';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addOverview';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertOverview();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertOverview() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'overview_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('overview_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('do_overview')) {
            $this->dbforge->create_table('do_overview');
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
        if ($this->common_model->insert(OVERVIEW_FORM, $data_list)) {
            $do_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editOverview/'. $do_id);
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

    public function editOverview($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = OVERVIEW_FORM;
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
            $data['button_header'] = array('menu_module' => 'overview');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editOverview/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editoverview';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateOverview();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateOverview() {
        $id = $this->input->post('overview_form_id');
        
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
        $fields_data = array('overview_id','yp_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('do_overview');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('do_overview')) {
            $this->dbforge->add_column('do_overview', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('do_overview');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
                    
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'do_overview')){
                $this->dbforge->drop_column('do_overview', $delete_val);
            }    
        }
        
       /* $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'daily_observations')){
                $this->dbforge->drop_column('daily_observations', $val);
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
        $where = array('overview_form_id' => $id);
        if ($this->common_model->update(OVERVIEW_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editOverview/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editOverview/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editOverview/'. $id);
    }
    
    
    //food 
    
    
     public function addFoodConsumed() {
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
            $data['button_header'] = array('menu_module' => 'food');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addfoodconsumed';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addFoodConsumed';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertFood();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertFood() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        // pr($jsonData); exit;
        $fields = array(
            'foodconsumed_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),'do_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE));

        foreach ($jsonData as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('foodconsumed_id', TRUE);
        //echo $this->db->table_exists('formbuilder');exit;
        if (!$this->db->table_exists('do_foodconsumed')) {
            $this->dbforge->create_table('do_foodconsumed');
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
        if ($this->common_model->insert(FOOD_FORM, $data_list)) {
            $do_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
             redirect(ADMIN_SITE . '/' . $this->viewname .'/editFoodConsumed/'. $do_id);
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

    public function editFoodConsumed($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $table = FOOD_FORM;
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
            $data['button_header'] = array('menu_module' => 'food');
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editFoodConsumed/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/editfoodconsumed';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateFood();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateFood() {
        $id = $this->input->post('food_form_id');
        
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        //pr($form_json_data);exit;
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        foreach ($jsonData as $key => $value){
            if ($value['type'] != 'header'){
                $fields[]= $value['name'];
            }
        }
        $fields_data = array('foodconsumed_id','yp_id','do_id');
        $result = array_merge($fields_data, $fields);
        
        $val_data = $this->db->list_fields('do_foodconsumed');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }
        
        if ($this->db->table_exists('do_foodconsumed')) {
            $this->dbforge->add_column('do_foodconsumed', $updated_fields);
        }
        
        $valaa_data = $this->db->list_fields('do_foodconsumed');
        $delete = array();
        foreach($valaa_data as $val){
            if(!in_array($val, $result)){
                $delete[] = $val;
            }
        }
        
        foreach($delete as $delete_val){
            if($this->db->field_exists($delete_val, 'do_foodconsumed')){
                $this->dbforge->drop_column('do_foodconsumed', $delete_val);
            }    
        }
        
       /* $delete_fields = $this->input->post('field_data');
        $fieldArr = array();  
        foreach ($delete_fields as $delete_field) {
            $fieldArr[]= str_replace("-preview", '', $delete_field);
        }
        $field_delete= array_unique($fieldArr);
        foreach($field_delete as $val){
            if($this->db->field_exists($val, 'do_foodconsumed')){
                $this->dbforge->drop_column('do_foodconsumed', $val);
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
        $where = array('food_form_id' => $id);
        if ($this->common_model->update(FOOD_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editFoodConsumed/'. $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname .'/editFoodConsumed/'. $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname .'/editFoodConsumed/'. $id);
    }
    
    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
