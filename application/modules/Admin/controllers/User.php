<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation'));
    }

    /*
      @Author : Mehul Patel
      @Desc   : Customers Listing form
      @Input  :
      @Output :
      @Date   : 12th May 2017
     */

    public function index($page = '') {
        
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('user_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('user_sortsearchpage_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'login_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }        
        //Query
        //$login_user_id = $this->session->userdata['nfc_admin_session']['admin_id'];
        $user_data = $this->session->userdata('nfc_admin_session');
        $table = LOGIN.' as l';
        $where = array("l.is_delete"=> "0");
        $wehere_not_in = array("l.role_id"=> $user_data['admin_type']);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address, l.mobile_number, rm.role_name As user_type, l.created_date, l.status,l.role_id as role_type");
        $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.role_id');
        
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR l.email LIKE "%'.$searchtext.'%" OR l.mobile_number LIKE "%'.$searchtext.'%" OR rm.role_name LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['information']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search,'','','','',$wehere_not_in);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1','',$wehere_not_in);
        }
        else
        {
            $data['information']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where,'','','','',$wehere_not_in);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1','',$wehere_not_in);
        }
       //pr($data['information']);exit;
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/admin_user/user.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('user_sortsearchpage_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type.'/assets/template',$data);
        }
    }
    

    /*
      @Author : Maitrak Modi
      @Desc   : Customers Form validation
      @Input 	:
      @Output	:
      @Date   : 11th May 2017
     */

    public function formValidation($id = null) {

         $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|integer|xss_clean');
            $this->form_validation->set_rules('usertype', 'Role type', 'trim|required|xss_clean');
            if(empty($id)){
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
                $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[25]|matches[cpassword]|xss_clean');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|xss_clean');
            }
    }

    function phone_valid($str) {
        return preg_match('/^[\d\+\-\.\(\)\/\s]*$/', $str);
    }

    /*
      Author : Maitrak Modi
      Desc  : Add cutosmer
      Input  :
      Output :
      Date   :11th May 2017
     */

    public function add() {
        $this->formValidation(''); // form Validation fields

        if ($this->form_validation->run() == FALSE) {

            $data['validation'] = validation_errors();

            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/add';
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['screenType'] = 'add';
            $data['footerJs'][0] = base_url('uploads/custom/js/admin_user/user.js');
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);
            //$this->load->view(ADMIN_SITE . '/assets/template', $data);
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            //success form
            $this->insertData();
        }
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Customer Insert Data
      @Input 	:
      @Output	:
      @Date   : 11th May 2017
     */

    public function insertData() {

        $randomPassword = rand_string(8);
        pr($this->session->userdata['nfc_admin_session']);eixt;
        // Inserted Array Data
     $data = array(
                'firstname' => $this->input->post('fname'),
                'lastname' => $this->input->post('lname'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'address' => $this->input->post('address'),
                'mobile_number' => $this->input->post('mobile_number'),
                'role_id' => $this->input->post('usertype'),
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'status' => $this->input->post('status'),
                'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
                'updated_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
                'session_id' => $this->session->userdata['nfc_admin_session']['session_id']
            );
        

        // Insert query
        if ($this->common_model->insert(LOGIN, $data)) {
            //if (true) {

            $this->sendMailToCustomer($data, $randomPassword); // send mail

            $msg = 'Customer has been added successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = 'Something went wrong. Please try after sometime.' ;//$this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Customer Edit Function
      @Input  : customerId
      @Output :
      @Date   : 11th May 2017
     */
    public function edit($id) {
        $this->formValidation($id);
        //echo $randomMachineId; exit;
        if ($this->form_validation->run() == FALSE) {
            //pr(validation_errors());exit;
            $data['footerJs'][0] = base_url('uploads/custom/js/admin_user/user.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = LOGIN . ' as l';
            $match = "l.login_id = '" . $id . "' AND l.is_delete=0 ";
            $fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address, l.mobile_number, l.role_id, l.created_date, l.status");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);

            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->viewname . '/addEdit';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        }else{
            $this->updatedata();
        }
    }
    

    /*
      @Author : Maitrak Modi
      @Desc   : Customer Update Data
      @Input 	: CustomerId
      @Output	:
      @Date   : 11th May 2017
     */

    public function updatedata($UserId) {
		$status = "";
		$usertype = "";
                if($this->input->post('status') == ""){
			$status = $this->input->post('selected_status');
		}else{
			$status = $this->input->post('status');
		}
		
		if($this->input->post('usertype') == ""){			
			$usertype = $this->input->post('role_selected_id'); 
		}else{
			$usertype =$this->input->post('usertype');			
		}

		$id = $this->input->post('login_id');
		$selectedUserType = "";
	
		//Get Records From Login Table
		$table = LOGIN . ' as l';
		$match = "l.login_id = '" . $UserId . "' AND l.is_delete=0 ";
		$fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address, l.mobile_number, l.role_id, l.created_date, l.status");
		$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$data['id'] = $UserId;
		$data['userType'] = getUserType();
		$data['crnt_view'] = $this->viewname;
                
		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);

	$data = array(
            'firstname' => $this->input->post('fname'),
            'lastname' => $this->input->post('lname'),
            'address' => $this->input->post('address'),
            'mobile_number' => $this->input->post('mobile_number'),
            'role_id' => $usertype,
            'updated_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'status' => $status
	);
		
		//Update Record in Database
		$where = array('login_id' => $id);

		// Update form data into database
	if ($this->common_model->update(LOGIN, $data, $where)) {
            if($_SESSION['nfc_admin_session']['admin_id'] == $id)
            {
            $_SESSION['nfc_admin_session']['name'] = !empty($data[0]['firstname']) ? $data[0]['lastname'] : '';
            }
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {    // error
		$msg = $this->lang->line('error_msg');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
         redirect(ADMIN_SITE . '/' . $this->viewname);
    }
    
    
    public function view($id) {

        //pr(validation_errors());exit;
            $data['footerJs'][0] = base_url('uploads/custom/js/admin_user/user.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = LOGIN . ' as l';
            $match = "l.login_id = '" . $id . "' AND l.is_delete=0 ";
            $fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address, l.mobile_number, l.role_id, l.created_date, l.status");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);

            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->viewname . '/addEdit';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $data['readonly'] = array("disabled" => "disabled");
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        }

    
    
    private function sendMailToCustomer($data = array(), $randomPassword = '') {

        if (!empty($data) && !empty($randomPassword)) {

            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['first_name'] . ' ' . $data['last_name'];
            $loginLink = base_url('login/');

            $find = array(
                '{NAME}',
                '{EMAIL}',
                '{PASSWORD}',
                '{LINK}',
            );

            $replace = array(
                'NAME' => $customerName,
                'EMAIL' => $toEmailId,
                'PASSWORD' => $randomPassword,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to Revspoke';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Your credentials are as below:</p> '
                    . '<p>Email : {EMAIL} </p> '
                    . '<p>Password : {PASSWORD}</p> '
                    . '<p>Please click onm below link:</p> '
                    . '<p><a herf= "{LINK}">{LINK} </a> </p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Customer Check Duplicate email
      @Input 	:
      @Output	:
      @Date   : 11th May 2017
     */

    public function isDuplicateEmail() {

        $isduplicate = 0;

        $emailName = trim($this->input->post('email'));
        $customer_id = trim($this->input->post('customer_id'));
        
        if (!empty($emailName)) {

            $tableName = CUSTOMER_TABLE;
            $fields = array('COUNT(customers_id) AS cntData');

            if (!empty($customer_id)) { // edit 
                $match = array('email' => $emailName, 'customers_id <>' => $customer_id);
            } else {
                $match = array('email' => $emailName);
            }

            $duplicateEmail = $this->common_model->get_records($tableName, $fields, '', '', $match);
            //echo $this->db->last_query();
            

            if ($duplicateEmail[0]['cntData'] > 0) {
                $isduplicate = 1;
            } else {
                $isduplicate = 0;
            }
        }

        echo $isduplicate;
    }
	 /*
      @Author : Mehul Patel
      @Desc   : Bulk Delete projects
      @Input 	:
      @Output	:
      @Date   : 11/5/2017
     */

    public function customerBulkDelete() {
        $id = $this->input->get('customerid');
        $customers_ids = explode(",", $id);
        if (!empty($id)) {
            foreach ($customers_ids as $customersIds) {
                $data = array('is_deleted' => 1 );
                $where = array('customers_id' => $customersIds, 'role_id != ' => 1);               
                
                if ($this->common_model->update(CUSTOMER_TABLE, $data, $where)) {
                    $msg = "Customer Deleted Successfully";
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    unset($id);
                } else {
                    // error
                    $msg = 'Something went wrong. Please try after sometime.' ;//$this->lang->line('error_msg');
                    //$msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            }
        }
         redirect(ADMIN_SITE.'/customers');
    }

}
