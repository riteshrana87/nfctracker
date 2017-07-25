<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CI_Controller {

    function __construct() {
        $this->CI = & get_instance();
        // parent::__construct();
		$system_lang = $this->CI->common_model->get_lang();
		
		$this->selectedLang = $system_lang;
    }

	
	/*
      Author : Maitrak Modi
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultHeader($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/defaultHeader', $data);
    }
	
	/*
      Author : Maitrak Modi
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultLogoHeader($param = NULL) {
	$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $data['user_info'] = $this->CI->session->userdata('LOGGED_IN');
        $role_id = $data['user_info']['ROLE_TYPE'];
        $table = ROLE_MASTER . ' as rm';
        $where = "rm.role_id= '" . $role_id . "' ";
        $fieldsn = array("rm.role_name");
        $data['user_role_data'] = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        $this->CI->load->view('Sidebar/defaultLogoHeader', $data);
    }
	
	/*
      Author : Maitrak Modi
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultMenuHeader($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/defaultMenuHeader', $data);
    }
	
	/*
      Author : Maitrak Modi
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultFooter($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/defaultFooter', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Unset Error Message Variable for all Form
      Input  :
      Output : Unset Error Session
      Date   : 18/01/2016
     */

    public function unseterror() {
        $error = $this->CI->session->userdata('ERRORMSG');
        if (isset($error) && !empty($error)) {
            $this->CI->session->unset_userdata('ERRORMSG');
        }
        //session_destroy();
    }

    public function messageCount(){

        $this->CI->load->library('Encryption');  // this library is for encoding/decoding password
        $converter = new Encryption;
        $user_id= $this->CI->session->userdata('LOGGED_IN')['ID'];
        return;
    }
}
