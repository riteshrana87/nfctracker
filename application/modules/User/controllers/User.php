<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		
		parent::__construct();
		if(checkPermission('User','view') == false)
           {
               redirect('/Dashboard');
           }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'Session'));

	}

	/*
	 @Author : Ritesh rana
	 @Desc   : Registration Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 23/02/2017
	 */

	public function index() {
        $searchtext='';
        $perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('user_data');
        }

        $searchsort_session = $this->session->userdata('user_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'login_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        //Query
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = LOGIN.' as l';
        $where = array("l.is_delete"=> "0","l.created_by"=> $login_user_id);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, rm.role_name As user_type, l.created_date, l.status,l.role_id as role_type");
        $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.role_id');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR l.email LIKE "%'.$searchtext.'%" OR l.mobile_number LIKE "%'.$searchtext.'%" OR rm.role_name LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['information']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['information']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
//pr($data['campaign_info']);exit;
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        
        $this->session->set_userdata('user_data', $sortsearchpage_data);
        setActiveSession('user_data'); // set current Session active
        $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'user');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/user/user.js');
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/userlist';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

	/*
	 @Author : Ritesh rana
	 @Desc   : create Registration page
	 @Input 	:
	 @Output	:
	 @Date   : 07/03/2017
	 */

    public function registration() {
        $this->formValidation();
        //echo $randomMachineId; exit;
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/custom/js/user/user.js');

            $data['crnt_view'] = $this->viewname;
            //Get Records From Login Table
            $table = LOGIN . ' as l';
            $match = "";
            $fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, l.role_id, l.created_date, l.status");
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $main_user_data = $this->session->userdata['LOGGED_IN'];
            $data['userType'] = getUserType($main_user_data['ROLE_TYPE']);
            $data['form_action_path'] = $this->viewname . '/registration';
            $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'user');
            $data['validation'] = validation_errors();
            $data['main_content'] = 'registration';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }else{
            $this->insertdata();
        }
    }



	/*
	 @Author : ritesh rana
	 @Desc   : User insert data
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2017
	 */

    public function insertdata() {

        $checkAvalibility = "";
		//Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
		$sess_array = array('setting_current_tab' => 'setting_user');
        $this->session->set_userdata($sess_array);

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('User'); //Redirect On Listing page
        }

        $duplicateName = $this->checkDuplicateEmail($this->input->post('email'));
        $getcount = count($duplicateName);

        if (isset($duplicateName) && empty($duplicateName) && $getcount == 0) {

            //$users = implode(",", $this->input->post('usertype'));


            $data['crnt_view'] = $this->viewname;
            //insert the user registration details into database
            //check User of CRM , PM, Support

            $data = array(
                'firstname' => $this->input->post('fname'),
                'lastname' => $this->input->post('lname'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'address' => $this->input->post('address'),
                'mobile_number' => $this->input->post('mobile_number'),
                'position' => $this->input->post('position'),
                'role_id' => $this->input->post('usertype'),
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'status' => $this->input->post('status'),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
                'session_id' => $main_user_data['session_id']
				
            );
            //Insert Record in Database
            if ($this->common_model->insert(LOGIN, $data)) {
                $msg = $this->lang->line('user_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

                // redirect($this->viewname);
            }

        } else {
            $msg = $this->lang->line('duplicate_user_error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            //redirect($this->viewname . '/userlist');
        }
        redirect('User');

    }


	/*
	 @Author : Ritesh Rana
	 @Desc   : UserList Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 07/03/2017
	 */
	public function edit($id) {
        $this->formValidation($id);
        //echo $randomMachineId; exit;
        if ($this->form_validation->run() == FALSE) {
            //pr(validation_errors());exit;
            $data['footerJs'][0] = base_url('uploads/custom/js/user/user.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = LOGIN . ' as l';
            $match = "l.login_id = '" . $id . "' AND l.is_delete=0 ";
            $fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, l.role_id, l.created_date, l.status");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $data['userType'] = getUserType();

            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/edit/' . $id;
            $data['main_content'] = '/registration';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }else{
            $this->updatedata();
        }
    }
    /*
	 @Author : Ritesh rana
	 @Desc   : User List Update Query
	 @Input 	: Post record from userlist
	 @Output	: Update data in database and redirect
	 @Date   : 07/03/2017
	 */

	public function updatedata() {
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
		$match = "l.login_id = '" . $id . "' AND l.is_delete=0 ";
		$fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, l.role_id, l.created_date, l.status");
		$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$data['id'] = $id;
		$data['userType'] = getUserType();
		$data['crnt_view'] = $this->viewname;

		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);

		$data = array(
            'firstname' => $this->input->post('fname'),
            'lastname' => $this->input->post('lname'),
            'address' => $this->input->post('address'),
            'mobile_number' => $this->input->post('mobile_number'),
            'position' => $this->input->post('position'),
            'role_id' => $usertype,
            'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
            'created_date' => datetimeformat(),
			'modified_date' => datetimeformat(),
			'status' => $status
		);
		
		if ($this->input->post('password') != "") {
			$data['password'] = md5($this->input->post('password'));
		}
		//Update Record in Database
		$where = array('login_id' => $id);

		// Update form data into database
		if ($this->common_model->update(LOGIN, $data, $where)) {

            if($_SESSION['LOGGED_IN']['ID'] == $id)
            {
                $_SESSION['LOGGED_IN']['FIRSTNAME'] = $data['firstname'];
                $_SESSION['LOGGED_IN']['LASTNAME'] = $data['lastname'];
            }
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

		} else {    // error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

			// redirect($this->viewname);
		}
        redirect('User'); //Redirect On Listing page
    }

	/*
	 @Author : Ritesh rana
	 @Desc   : User List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/03/2017
	 */

	public function deletedata($id) {
	
		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);
		//Delete Record From Database
		if($this->session->userdata['LOGGED_IN']['ID'] != $id){ // Login User Should not been delete
			if (!empty($id)) {

				$data = array('is_delete' => 1);

				$where = array('login_id' => $id);

				if ($this->common_model->update(LOGIN, $data, $where)) {
					$msg = $this->lang->line('user_delete_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
					//redirect($this->viewname . '/userlist'); //Redirect On Listing page
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						
					// redirect($this->viewname);
					// redirect($this->viewname . '/userlist'); //Redirect On Listing page
				}
			}
		}else{			
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			
		}
		redirect('User');
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : check Duplicate name
	 @Input 	:
	 @Output	:
	 @Date   : 16/03/2017
	 */

	public function checkDuplicateEmail($email, $user_id = null) {

		$table = LOGIN . ' as l';
		if (NULL !== $user_id) {
			$match = "l.is_delete=0 AND l.email = '" . $email . "' and l.login_id <> '" . $user_id . "'";
		} else {
			$match = "l.email = '" . $email . "' AND l.is_delete=0 ";
		}
		$fields = array("l.login_id,l.status");
		$data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
		return $data['duplicateEmail'];
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : UserList Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 20/03/2016
	 */

	public function view($id) {
        	//Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/user/user.js');
			$table = LOGIN . ' as l';
			$match = "l.login_id = " . $id;
			$fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.zipcode,l.country, l.mobile_number, l.role_id, l.created_date, l.status");
			$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);

			$countryName = "";
			$data['countryName'] = "";
			if (isset($data['editRecord'][0]['country'])) {
                $countryName = getCountryName($data['editRecord'][0]['country']);
				if (isset($countryName[0]['country_name'])) {
					$data['countryName'] = $countryName[0]['country_name'];
				}

				if ($data['countryName'] != NULL) {
					$data['countryName'] = $data['countryName'];
				}
			}

			if (isset($data['editRecord'][0]['role_id'])) {
				$roleName = getRoleName($data['editRecord'][0]['role_id']);
				$data['roleName'] = $roleName[0]['role_name'];
			}
			
			$data['readonly'] = array("disabled" => "disabled");
			$data['id'] = $id;
			$table1 = COUNTRIES . ' as cm';
			$fields1 = array("cm.country_name,cm.country_id");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', '');
            $data['form_action_path'] = "";
			$data['userType'] = getUserType();
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/registration';
		    $this->parser->parse('layouts/DefaultTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : check Duplicate name
	 @Input 	:
	 @Output	:
	 @Date   : 21/03/2016
	 */

	public function isDuplicateEmail() {
		$email = $this->input->post('emailID');
		$user_id = $this->input->post('userID');
		$table = LOGIN . ' as l';
		if (NULL !== $user_id && $user_id != "") {
			$match = "l.is_delete=0 AND l.email = '" . $email . "' and l.login_id <> '" . $user_id . "'";
		} else {
			$match = "l.email = '" . $email . "' AND l.is_delete=0 ";
		}

		$fields = array("l.login_id,l.status");
		$duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
		$count = count($duplicateEmail);
		if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
			echo "true";
		} else {
			echo "false";
		}
		
	}


	/*
	 @Author : Ritesh Rana
	 @Desc   : assignModuleCount
	 @Input  :
	 @Output :
	 @Date   : 20/03/2017
	 */
	public function assignModuleCount($role_id){

		$dataArr=array();
		$this->db->select('component_name,COUNT(*) AS ASSIGNED')->from(AAUTH_PERMS_TO_ROLE);
		$this->db->where('role_id =',$role_id);
		$this->db->group_by('component_name');
		$query = $this->db->get();
		$data = $query->result_array();

		foreach ($data as $dataz){

			$dataArr[$dataz['component_name']]=$dataz['ASSIGNED'];
		}
		return  $dataArr;
		//return  json_encode($dataArr);

	}
    /*
         @Author : Ritesh Rana
         @Desc   : form validation
         @Input  :
         @Output :
         @Date   : 20/03/2017
         */
    public function formValidation($id = null) {
            $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|integer|xss_clean');
            $this->form_validation->set_rules('position', 'Position', 'trim|required|xss_clean');
            $this->form_validation->set_rules('usertype', 'Role type', 'trim|required|xss_clean');
            if(empty($id)){
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
                $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[25]|matches[cpassword]|xss_clean');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|xss_clean');
            }
    }
}
