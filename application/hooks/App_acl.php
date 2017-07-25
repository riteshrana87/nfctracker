<?php

/**
 * This class will be called by the post_controller_constructor hook and act as ACL
 * @author Mehul Patel
 */
class ACL {

    /**
     * Array to hold the rules
     * Keys are the role_id and values arrays
     * In this second level arrays the key is the controller and value an array with key method and value boolean
     * @var Array
     */
    public function __construct() {
        
    }

    /**
     * The main method, determines if the a user is allowed to view a site
     * @author
     * @return boolean
     */
    public function auth() {
        $CI = & get_instance();
        $system_lang = $CI->common_model->get_lang();
        $CI->config->set_item('language', $system_lang);
        $CI->lang->load('label', $system_lang ? $system_lang : 'english');

        $this->loginpage_redirect();  //Function added by RJ for redirection
        // added by Ritesh Rana
        $class = $CI->router->fetch_class();  //Get Class
        $method = $CI->router->fetch_method();  //Get Method

        $crm_module = $this->listofCRMcontroller(); // Get List of CRM Module's Controller

        $pm_module = $this->listofPMcontroller(); // Get List of PM Module's Controller

        $support_module = $this->listofSupportcontroller(); // Get List of Support Module's Controller
        // Added by Ritesh

        if (!isset($CI->router)) { # Router is not loaded
            $CI->load->library('router');
        }
        if (!isset($CI->session)) { # Sessions are not loaded
            $CI->load->library('session');
            $CI->load->library('database');
        }

        $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
        $flag = 0;
        $class = $CI->router->fetch_class();
        // Allow Controller class which can be access without permission  added by Mehul Patel
        $allow_class = array('Dashboard', 'Help', 'Masteradmin', 'Set_language', 'MyProfile', 'Mail', 'Admin');
        $method = $CI->router->fetch_method();

        

        //$master_user_id = $CI->config->item('master_user_id');

        if ($CI->session->has_userdata('LOGGED_IN')) {
            $session = $CI->session->userdata('LOGGED_IN');
            $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
            $CI->db->from('aauth_perm_to_group as APG');
            $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
            $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
            $CI->db->where('role_id', $session['ROLE_TYPE']);
            $CI->db->where('controller_name', $class);
            $resultData = $CI->db->get()->result_array();
            $configPerms = $CI->load->config('acl');
            //print_r($resultData);
            $permsArray = $CI->config->item($class);
            if (count($resultData) > 0) {
                $dbPermArray = array_map(function ($obj) {
                    return $obj['name'];
                }, $resultData);
            }
            foreach ($dbPermArray as $prmObj) {
                if (is_array($permsArray) && array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[] = $permsArray[$prmObj];
                }
            }
            foreach ($permArrMaster as $permObj) {
                foreach ($permObj as $innerObj) {
                    $validateArr[] = $innerObj;
                }
            }
            if (in_array($class, $allow_class)) {
                return true;
            } else {

                if (in_array($method, $validateArr)) {

                    /*
                     * custom code for validating project status condition whether project is completed or not
                     */
                    if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {
                        if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }
                } else {

                    if (!empty($validateArr)) {

                        redirect('Dashboard');
                        // return true;
                        //redirect(base_url('Dashboard/logout')); //Redirect on Dashboard
                        //	return true;
                    }
                }
            }


            // }
        }
    }

    /*
      Author : RJ(Rupesh Jorkar)
      Desc   : Make Login condition
      Output : If user are Logged out then redirect on login page.
      Date   : 01/02/2016
     */

    public function loginpage_redirect() {
        $CI = & get_instance();
        $user_info = $CI->session->userdata('LOGGED_IN');  //Current Login information
        $admin_user_info = $CI->session->userdata('nfc_admin_session');  //Current Login information

        $currentClass = $CI->router->fetch_class();
        if (empty($user_info) && $user_info == "" && (!$CI->session->has_userdata('nfc_admin_session'))) {

            if (!in_array($CI->uri->ruri_string(), $this->before_login_allow_pages)) {
                //Condition for compare module
                if (in_array($CI->router->fetch_class(), $this->before_login_allow_module)) {
                    
                } else {

                    //pr($this->uri->segment(2));exit;
                    echo "<script>window.location.href='" . base_url('Masteradmin/login') . "';</script>";
                    die;
                    //redirect(base_url('Masteradmin/login'),'refresh');  //Redirect on login//exit;
                }
            }
        } else if ($CI->session->has_userdata('nfc_admin_session') && (!$CI->session->userdata('LOGGED_IN'))) {
//            if ($admin_user_info['active'] == TRUE) {
//                redirect(base_url(ADMIN_SITE . '/Dashboard'));
//            } else {
//                echo "<script>window.location.href='" . base_url('Admin/login') . "';</script>";
//                die;
//            }
        } else {
            if (in_array($CI->uri->ruri_string(), $this->after_login_ignore_pages)) {
                //$msg = $CI->lang->line('DONT_HAVE_PAGE_PERMISSION');
                //$CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(base_url());  //Redirect on Dashboard
            }
        }
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : $before_login_allow_module = Allow whole Module to access before Login.
      : $before_login_allow_pages  = Allow Only page before Login.
      : $after_login_ignore_pages  = Ignore Pages after login.
      Date   : 01/02/2016
     */

    private $before_login_allow_module = array('Help', 'Admin');
    private $before_login_allow_pages = array(
        '../modules/Admin/controllers/Admin/index',
        '../modules/Admin/controllers/Admin/do_login',
        '../modules/Admin/controllers/Admin/forgot_password',
        '../modules/Masteradmin/controllers/Masteradmin/index',
        '../modules/Masteradmin/controllers/Masteradmin/login',
        '../modules/Masteradmin/controllers/Masteradmin/verifylogin',
        'Set_language/index',
        '../modules/Masteradmin/controllers/Masteradmin/forgotpassword',
        '../modules/Masteradmin/controllers/Masteradmin/resetpassword',
        '../modules/Masteradmin/controllers/Masteradmin/updatepassword',
        '../modules/Masteradmin/controllers/Masteradmin/updatePasswords',
        '../modules/Masteradmin/controllers/Masteradmin/removed_session',
        '../modules/Masteradmin/controllers/Masteradmin/forgotpassword',
        '../modules/Masteradmin/controllers/Masteradmin/resetpassword',
        '../modules/Masteradmin/controllers/Masteradmin/updatepassword',
        '../modules/Masteradmin/controllers/Masteradmin/updatePasswords');
    private $after_login_ignore_pages = array(
        '../modules/Masteradmin/controllers/Masteradmin/index',
        '../modules/Masteradmin/controllers/Masteradmin/login',
        '../modules/Masteradmin/controllers/Masteradmin/verifylogin',
        '../modules/Masteradmin/controllers/Masteradmin/forgotpassword');

    // Added by Mehul Patel
    public function listofCRMcontroller() {
        $CI = & get_instance();
        $crmController = array();
        $CI->db->select('mm.controller_name');
        $CI->db->from('module_master as mm');
        $CI->db->where('mm.component_name', "CRM");
        $CI->db->where('mm.status', "1");
        $resultData = $CI->db->get()->result_array();

        foreach ($resultData As $key => $val) {
            foreach ($val as $k => $v) {
                array_push($crmController, $v);
            }
        }
        return $crmController;
    }

    // Added by Mehul Patel
    public function listofPMcontroller() {
        $CI = & get_instance();
        $pmController = array();
        $CI->db->select('mm.controller_name');
        $CI->db->from('module_master as mm');
        $CI->db->where('mm.component_name', "PM");
        $CI->db->where('mm.status', "1");
        $resultData = $CI->db->get()->result_array();

        foreach ($resultData As $key => $val) {
            foreach ($val as $k => $v) {
                array_push($pmController, $v);
            }
        }
        return $pmController;
    }

    // Added by Mehul Patel
    public function listofSupportcontroller() {
        $CI = & get_instance();
        $supportController = array();
        $CI->db->select('mm.controller_name');
        $CI->db->from('module_master as mm');
        $CI->db->where('mm.component_name', "Support");
        $CI->db->where('mm.status', "1");
        $resultData = $CI->db->get()->result_array();

        foreach ($resultData As $key => $val) {
            foreach ($val as $k => $v) {
                array_push($supportController, $v);
            }
        }
        return $supportController;
    }

    // Added by Mehul Patel
    public function listofUsercontroller() {
        $CI = & get_instance();
        $userController = array();
        $CI->db->select('mm.controller_name');
        $CI->db->from('module_master as mm');
        $CI->db->where('mm.component_name', "User");
        $CI->db->where('mm.status', "1");
        $resultData = $CI->db->get()->result_array();

        foreach ($resultData As $key => $val) {
            foreach ($val as $k => $v) {
                array_push($userController, $v);
            }
        }
        return $userController;
    }

}
