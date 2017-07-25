<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation', 'Session'));
    }

    public function index() {
        $data['main_content'] = '/Dashboard';
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        /*
         * logged in user data
         */
        if($this->session->userdata('LOGGED_IN')){
            $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        
        $table = LOGIN.' as l';
        $where = array("l.login_id" => $login_id);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, l.created_date, l.status,l.role_id,rm.role_name");
        $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id = l.role_id');
        $data['logged_user']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);
        
        //pr($data['logged_user']);exit;
        /*
         * logged in user data ends
         */

        //get staff notices data

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = STAFF_NOTICES . ' as sn';
        $where = array();
        $fields = array("sn.staff_notices_id,sn.title,sn.notice,sn.created_by,l.firstname,l.lastname,sn.created_date");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= sn.created_by');
        $data['staff_no_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '','', $where);
        
        
        $table = SCHOOL_HANDOVER . ' as sh';
        $where = array();
        $fields = array("sh.school_handover_id,sh.title,sh.notice,sh.created_by,l.firstname,l.lastname,sh.created_date");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = sh.created_by');
        $data['school_hand_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '','', $where);
        
        
        $data['header'] = array('menu_module' => 'Dashboard');

        if ($this->input->is_ajax_request()) {
            $this->load->view('Dashboard', $data);
        } else {
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        
        
        }else{
            redirect('Masteradmin');
        }
        
        
        
    }

    public function staffNotices() {//Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/insertStaffNotices/';
        $data['main_content'] = '/staffnotices';
        $this->load->view('/staffnotices', $data);
    }

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('staff_notices_img_url') . '/' . $filename, $str);
    }

    public function insertStaffNotices() {
//        pr($_FILES);
//        pr($_POST);exit;
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();

        $success = $this->common_model->insert(STAFF_NOTICES, $data);
        $insert_id = $this->db->insert_id();

        $upload_dir = $this->config->item('staff_notices_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }

            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->module;
            $uploadData = uploadImage('fileUpload', $this->config->item('staff_notices_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);
            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'staff_notices_id' => $insert_id];
                    }
                }
            }


            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('staff_notices_img_url'), 'staff_notices_id' => $insert_id];
                }
            }
            if (count($estFIles) > 0) {
                $where = array('staff_notices_id' => $insert_id);
                if (!$this->common_model->insert_batch(STAFF_NOTICES_UPLOADS, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }

            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
             */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(STAFF_NOTICES_UPLOADS, 'file_id IN(' . $dlStr . ')');
            }
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            // redirect($this->viewname);
        }
        redirect('Dashboard'); //Redirect On Listing page
    }

    public function logout() {
        $user_session = $this->session->userdata('LOGGED_IN');
        if ($user_session) {
            $this->session->unset_userdata('LOGGED_IN');
            $error_msg = ERROR_START_DIV . lang('SUCCESS_LOGOUT') . ERROR_END_DIV;
            $this->session->set_userdata('ERRORMSG', $error_msg);
            //$this->session->sess_destroy();
            redirect(base_url('Masteradmin'));
        } else {
            redirect(base_url());
        }
    }
    
    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = STAFF_NOTICES_UPLOADS . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
               $pth = file_get_contents($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']);
            //$pth = file_get_contents($this->config->item('staff_notices_img_url') . $cost_files[0]['file_name']);
               $this->load->helper('download');
               force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
        }
    }

    
    //school handover for crisis
    
    
    public function schollHandover() {//Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/insertSchollHandover/';
        $data['main_content'] = '/schollhandover';
        $this->load->view('/schollhandover', $data);
    }

    public function schollHandoverUploadFile($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('school_handover_img_url') . '/' . $filename, $str);
    }

    public function insertSchollHandover() {
//        pr($_FILES);
//        pr($_POST);exit;
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();

        $success = $this->common_model->insert(SCHOOL_HANDOVER, $data);
        $insert_id = $this->db->insert_id();

        $upload_dir = $this->config->item('school_handover_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }

            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->module;
            $uploadData = uploadImage('fileUpload', $this->config->item('school_handover_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);
            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'school_handover_id' => $insert_id];
                    }
                }
            }


            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('staff_notices_img_url'), 'school_handover_id' => $insert_id];
                }
            }
            if (count($estFIles) > 0) {
                $where = array('school_handover_id' => $insert_id);
                if (!$this->common_model->insert_batch(SCHOOL_HANDOVER_FILE, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }

            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
             */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(SCHOOL_HANDOVER_FILE, 'file_id IN(' . $dlStr . ')');
            }
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('user_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            // redirect($this->viewname);
        }
        redirect('Dashboard'); //Redirect On Listing page
    }
    
    function HandoverFiledownload($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = SCHOOL_HANDOVER_FILE . ' as shf';
            $params['match_and'] = 'shf.file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
               $pth = file_get_contents($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']);
            //$pth = file_get_contents($this->config->item('staff_notices_img_url') . $cost_files[0]['file_name']);
               $this->load->helper('download');
               force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
        }
    }
}
