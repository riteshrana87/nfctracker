<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class YoungPerson extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('YoungPerson', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->load->model('imageupload_model');
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh rana
      @Desc   : Registration Index Page
      @Input 	:
      @Output	:
      @Date   : 25/06/2017
     */

    public function index() {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('yp_data');
        }

        $searchsort_session = $this->session->userdata('yp_data');
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
                $sortfield = 'yp_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = YP_DETAILS . ' as yp';
        $where = array("yp.is_deleted" => "'0'", "yp.created_by" => "'$login_user_id'");
        $fields = array("yp.yp_id, CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,yp.yp_lname,CONCAT(`firstname`,' ', `lastname`) as create_name, l.firstname, l.lastname,yp.created_date,yp.yp_initials,yp.gender,yp.date_of_birth,yp.staffing_ratio,yp.profile_img");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= yp.created_by');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '((CONCAT(`yp_fname`, \' \', `yp_lname`) LIKE "%' . $searchtext . '%" OR yp.yp_fname LIKE "%' . $searchtext . '%" OR yp.yp_lname LIKE "%' . $searchtext . '%") OR (CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%"  OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%")AND yp.is_deleted = "0")';
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }

//pr($data['information']);exit;
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);

        $this->session->set_userdata('yp_data', $sortsearchpage_data);
        setActiveSession('yp_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/youngperson';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : create yp Registration page
      @Input 	:
      @Output	:
      @Date   : 27/06/2017
     */

    public function registration() {
        $this->formValidation();
        //echo $randomMachineId; exit;
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $data['crnt_view'] = $this->viewname;
            //Get Records From Login Table
            $data['userType'] = getUserType($main_user_data['ROLE_TYPE']);
            $data['initialsId'] = $this->common_model->initialsId();

            $data['form_action_path'] = $this->viewname . '/registration';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['validation'] = validation_errors();
            $data['main_content'] = '/registration';
            //$this->parser->parse('layouts/DefaultTemplate', $data);

            $this->load->view('/registration', $data);
        } else {
            $this->insertdata();
        }
    }

    /*
      @Author : ritesh rana
      @Desc   : yp insert data
      @Input 	:
      @Output	:
      @Date   : 27/06/2017
     */

    public function insertdata() {

        $checkAvalibility = "";
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('YoungPerson'); //Redirect On Listing page
        }
        $data['crnt_view'] = $this->viewname;
        $data = array(
            'yp_fname' => $this->input->post('fname'),
            'yp_lname' => $this->input->post('lname'),
            'yp_initials' => $this->input->post('initials'),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
            'updated_by' => $main_user_data['ID'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(YP_DETAILS, $data)) {
            $msg = $this->lang->line('user_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            // redirect($this->viewname);
        }


        redirect('YoungPerson');
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
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/edit/' . $id;
            $data['main_content'] = '/registration';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
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


    public function view($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $id;
        $fields = array("yp.*,pa.authority,pa.address_1,pa.address_2,pa.town,pa.county,pa.postcode,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id',SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id',OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
        
        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        $data['id'] = $id;
        $data['userType'] = getUserType();
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/profile';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    public function personal_info($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $id;
        $fields = array("yp.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updatePersonalInfo/' . $id;
        $data['main_content'] = '/personal_info';
        $this->load->view('/personal_info', $data);
    }
    
    public function updatePersonalInfo() {
        $id = $this->input->post('yp_id');
        $data['crnt_view'] = $this->viewname;
        $sess_array = array('setting_current_tab' => 'setting_user');
        $this->session->set_userdata($sess_array);
        $dob = date('Y-m-d', strtotime($this->input->post('date_of_birth')));
        $data = array(
        'date_of_birth' => $dob,
        'gender' => $this->input->post('gender'),
        'age' => date_diff(date_create($dob), date_create('today'))->y,
        'place_of_birth' => $this->input->post('place_of_birth'),
        'date_of_placement' => date('Y-m-d', strtotime($this->input->post('date_of_placement'))),
        'legal_status' => $this->input->post('legal_status'),
        'staffing_ratio' => $this->input->post('staffing_ratio'),
        'edt_out_of_hours' => $this->input->post('edt_out_of_hours'),
        'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
        'modified_date' => datetimeformat(),
        );
        
        //Update Record in Database
        $where = array('yp_id' => $id);
        // Update form data into database
        if ($this->common_model->update(YP_DETAILS, $data, $where)) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            // redirect($this->viewname);
        }
        redirect('YoungPerson/view/'.$id); //Redirect On Listing page
    }

    public function placingAuthority($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = PLACING_AUTHORITY . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updatePlacingAuthority/' . $id;
        $data['main_content'] = '/placingauthority';
        $this->load->view('/placingauthority', $data);
    }
    
    
    public function updatePlacingAuthority() {
        $id = $this->input->post('yp_id');
        $table = PLACING_AUTHORITY . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $placingRecord = $this->common_model->get_records($table, $fields, '', '', $match);
        
        $data['authority'] = $this->input->post('authority');
        $data['authority'] = $this->input->post('authority');
        $data['address_1'] = $this->input->post('address_1');
        $data['address_2'] = $this->input->post('address_2');
        $data['town'] = $this->input->post('town');
        $data['county'] = $this->input->post('county');
        $data['postcode'] = $this->input->post('postcode');
        $where = array('yp_id' => $id);
        if(empty($placingRecord)){
          $data['yp_id'] = $this->input->post('yp_id');
          $success=  $this->common_model->insert(PLACING_AUTHORITY, $data);
        } else {
           $success = $this->common_model->update(PLACING_AUTHORITY, $data, $where);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
               // redirect($this->viewname);
        }
        redirect('YoungPerson/view/'.$id); //Redirect On Listing page
    }
    
    
    public function socialWorkerDetails($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = SOCIAL_WORKER_DETAILS . ' as swd';
        $match = "swd.yp_id = " . $id;
        $fields = array("swd.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateSocialWorkerDetails/' . $id;
        $data['main_content'] = '/socialworkerdetails';
        $this->load->view('/socialworkerdetails', $data);
    }
    
    
    public function updateSocialWorkerDetails() {
        $id = $this->input->post('yp_id');
        $table = SOCIAL_WORKER_DETAILS . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $socialRecord = $this->common_model->get_records($table, $fields, '', '', $match);
        
        $data['social_worker_firstname'] = $this->input->post('social_worker_firstname');
        $data['social_worker_surname'] = $this->input->post('social_worker_surname');
        $data['landline'] = $this->input->post('landline');
        $data['mobile'] = $this->input->post('mobile');
        $data['other'] = $this->input->post('other');
        $data['email'] = $this->input->post('email');
        $data['senior_social_worker_firstname'] = $this->input->post('senior_social_worker_firstname');
        $data['senior_social_worker_surname'] = $this->input->post('senior_social_worker_surname');
        $where = array('yp_id' => $id);
        if(empty($socialRecord)){
          $data['yp_id'] = $this->input->post('yp_id');
          $success=  $this->common_model->insert(SOCIAL_WORKER_DETAILS, $data);
        } else {
           $success = $this->common_model->update(SOCIAL_WORKER_DETAILS, $data, $where);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
               // redirect($this->viewname);
        }
        redirect('YoungPerson/view/'.$id); //Redirect On Listing page
    }
    
    
    public function overviewOfYoungPerson($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = OVERVIEW_OF_YOUNG_PERSON . ' as oyp';
        $match = "oyp.yp_id = " . $id;
        $fields = array("oyp.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateOverviewOfYoungPerson/' . $id;
        $data['main_content'] = '/overviewofyoungperson';
        $this->load->view('/overviewofyoungperson', $data);
    }
    
    
    public function updateOverviewOfYoungPerson() {
        $id = $this->input->post('yp_id');
        $table = OVERVIEW_OF_YOUNG_PERSON . ' as oyp';
        $match = "oyp.yp_id = " . $id;
        $fields = array("oyp.*");
        $socialRecord = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['pen_portrait'] = $this->input->post('pen_portrait');
        $where = array('yp_id' => $id);
        if(empty($socialRecord)){
          $data['yp_id'] = $this->input->post('yp_id');
          $success=  $this->common_model->insert(OVERVIEW_OF_YOUNG_PERSON, $data);
        } else {
           $success = $this->common_model->update(OVERVIEW_OF_YOUNG_PERSON, $data, $where);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
               // redirect($this->viewname);
        }
        redirect('YoungPerson/view/'.$id); //Redirect On Listing page
    }
    
    
     
    public function ProfileInfo($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $id;
        $fields = array("yp.profile_img,yp.yp_id");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateProfileInfo/' . $id;
        $data['main_content'] = '/profileinfo';
        $this->load->view('/profileinfo', $data);
    }
    
    
    public function updateProfileInfo() {
        $id = $this->input->post('yp_id');
        $upload_dir = $this->config->item('yp_profile_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = $_FILES['fileUpload']['name'];

            if (!empty($_FILES['fileUpload']['name'])) {
                $oldbookimg = $this->input->post('fileUpload'); //new add
                $bgImgPath = $this->config->item('yp_profile_img_url');
                $uploadFile = 'fileUpload';
                $thumb = "thumb";
                $hiddenImage = !empty($oldbookimg) ? $oldbookimg : '';
                $estFIles['profile_img'] = $this->imageupload_model->upload_image($uploadFile, $bgImgPath, $thumb, TRUE);
                $where = array('yp_id' => $id);
                $sucsses = $this->common_model->update(YP_DETAILS, $estFIles, $where);
            } else {
                $this->session->set_flashdata('msg', lang('error'));
                redirect('YoungPerson/view/' . $id); //Redirect On Listing page
            }
        }
        
        $data['crnt_view'] = $this->viewname;
        redirect('YoungPerson/view/'.$id); //Redirect On Listing page
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

    public function assignModuleCount($role_id) {

        $dataArr = array();
        $this->db->select('component_name,COUNT(*) AS ASSIGNED')->from(AAUTH_PERMS_TO_ROLE);
        $this->db->where('role_id =', $role_id);
        $this->db->group_by('component_name');
        $query = $this->db->get();
        $data = $query->result_array();

        foreach ($data as $dataz) {

            $dataArr[$dataz['component_name']] = $dataz['ASSIGNED'];
        }
        return $dataArr;
        //return  json_encode($dataArr);
    }

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('yp_profile_img_url') . '/' . $filename, $str);
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
        $this->form_validation->set_rules('initials', 'initials', 'trim|required|xss_clean');
    }

    
    
}
