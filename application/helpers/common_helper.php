<?php

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc	: Function for use Pre in Short-Cut
  @Input 	: Array
  @Output	: Array
  @Date	: 12/01/2016
 */

function pr($var) {
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}

/*
  @Author	: Maitrak Modi
  @Desc	        : Function for generate random number
  @Input 	: no of character length
  @Output	: generated random number
  @Date	        : 15th March 2017
 */

function randomNumber($length, $character = false) {
    $random = "";
    rand((double) microtime() * 1000000);

    $data = '1234567890';
    if ($character) {

        $data .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        //$data .= "0FGH45OP89";
    }

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
}



/*
  @Author	: Maitrak Modi
  @Desc	: Function for Active current session for pagination
  @Input 	: no of character length
  @Output	: generated random number
  @Date	: 15th March 2017
 */

function setActiveSession($activeSession) {
    $CI = & get_instance();

    $CI->load->library('session');
    $sess_array = $CI->session->all_userdata();

    //pr($sess_array['LOGGED_IN']);
    foreach ($sess_array as $key => $val) {

        if ($key != 'session_id' && $key != $activeSession && $key != 'LOGGED_IN') { // Except Login Session
            $CI->session->unset_userdata($key);
        }
    }
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Function for Show Round Amount
  @Input 	: Array
  @Output	: Array
  @Date		: 10/03/2016
 */

function amtRound($val) {
    return round($val, 2);
}

/*
  @Author	: RJ(Rupesh Jorkar)
  @Desc		: Set Date Formate as per Config Table
  @Input 	: Date
  @Output	: Date with formate
  @Date		: 13/04/2016
 */

function configDateTime($date) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'date_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo[0]['value'], strtotime($date));
    } else {
        return date($dateInfo[0]['value']);
    }
}

/*
  @Author : Ritesh Rana
  @Desc   : Set Datetime Formate as per Config Table
  @Input  : Date
  @Output : Datetime with formate
  @Date   : 11/05/2017
 */

function configDateTimeFormat($date) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'datetime_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo[0]['value'], strtotime($date));
    } else {
        return date($dateInfo[0]['value']);
    }
}


function getStaffUploadData($staff_id) {
    $ci = & get_instance();
    $table = STAFF_NOTICES_UPLOADS . ' as snu';
    $where = array("staff_notices_id" => $staff_id);
    $fields = array("snu.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

function getSchoolHandUploadData($staff_id) {
    $ci = & get_instance();
    $table = SCHOOL_HANDOVER_FILE . ' as shf';
    $where = array("school_handover_id" => $staff_id);
    $fields = array("shf.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Function for Formate Date
  @Input 	: Date Formate
  @Output	: Date
  @Date   : 12/01/2016
 */

function datetimeformat($date = '') {
    if (!empty($date)) {
        return date("Y-m-d H:i:s", strtotime($date));
    } else {
        return date("Y-m-d H:i:s");
    }
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Get User Detail As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 21/04/2016
 */

function getUserDetail($login_id) {
    $CI = & get_instance();
    $table = LOGIN . ' as lgn';
    $fields = array("lgn.firstname, lgn.lastname, lgn.email, lgn.address");
    $where = array('lgn.status' => '1', 'lgn.is_delete' => '0', 'lgn.login_id' => $login_id);
    $userArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $userArray;
}

/*
  @Author : RJ(Rupesh Jorkar)
  @Desc   : Get Random String as you want
  @Input  :
  @param  : type of random string.basic,alpha,alunum,numeric,nozero,unique,md5,encrypt and sha1
  @Output : string
  @Date   : 21/01/2016
 */

function random_string($type = 'alnum', $len = 8) {
    switch ($type) {
        case 'basic' : return mt_rand();
            break;
        case 'alnum' :
        case 'numeric' :
        case 'nozero' :
        case 'alpha' :

            switch ($type) {
                case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric' : $pool = '0123456789';
                    break;
                case 'nozero' : $pool = '123456789';
                    break;
            }

            $str = '';
            for ($i = 0; $i < $len; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }
            return $str;
            break;
        case 'unique' :
        case 'md5' :

            return md5(uniqid(mt_rand()));
            break;
        case 'encrypt' :
        case 'sha1' :

            $CI = & get_instance();
            $CI->load->helper('security');

            return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
            break;
    }
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserType($role_id = null) {
    $ci = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $fields = array("rm.role_id, rm.role_name");
    if($role_id == '1'){
        $where = array('rm.status' => 1, 'rm.is_delete' => 0);
    }else{
        $where = array('rm.status' => 1, 'rm.is_delete' => 0,'rm.parent_role' => $role_id );
    }
    
    $where_not_in = array('rm.role_id' => $role_id );
    // $where = array('rm.is_delete' => '0');
    $data['role_option'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where,'','','','',$where_not_in);

    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserTypeList() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `nfc_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserTypeAssign() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `nfc_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   :  Create lang function for get lang line
  @Input 	:  $line
  @Output	:  Display line
  @Date   : 04/04/2017
 */
if (!function_exists('lang')) {

    function lang($line, $id = '') {
        $CI = & get_instance();
        $line = $CI->lang->line($line);

        if ($id != '') {
            $line = '<label for="' . $id . '">' . $line . "</label>";
        }

        return $line;
    }

}

/*
  @Author : Ritesh Rana
  @Desc   : Get permission list from aauth_perms
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getPermsList() {

    $ci = & get_instance();
    $table = AAUTH_PERMS . ' as ap';
    $match = "";
    $fields = array("ap.id, ap.name");
    $data['permsList'] = $ci->common_model->get_records($table, $fields);
    return $data['permsList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getModuleList() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getCRMModuleList() {
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module Status from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getModuleStatus() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $fields = array("mm.status");
    $data['module_option'] = $ci->common_model->get_records($table, $fields);

    return $data['module_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Helperfunction for checkpermission
  @Input  : action name
  @Output : if has permission then return true else false
  @Date   : 04/04/2017
 */

function checkPermission($controller, $method) {
    $CI = & get_instance();

    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
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
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input 	:
      @Output	:
      @Date   : 04/04/2017
     */
}

function uploadImage($input, $path, $redirect, $file_name = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
    $CI = & get_instance();
    $files = $_FILES;
    $FileDataArr = array();
    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';
    $config['max_size'] = 204800;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
    $config['file_ext_tolower'] = $file_ext_tolower;
    $config['encrypt_name'] = $encrypt_name;
    $config['remove_spaces'] = $remove_spaces;
    $config['detect_mime'] = $detect_mime;
    if ($file_name != null) {
        $config['file_name'] = $file_name;
    }

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
            $_FILES[$input]['name'] = 'file_'.time().'.'.$imgname;
            $_FILES[$input]['type'] = $files[$input]['type'][$i];
            $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
            $_FILES[$input]['error'] = $files[$input]['error'][$i];
            $_FILES[$input]['size'] = $files[$input]['size'][$i];

            $content = file_get_contents($_FILES[$input]['tmp_name']);
            if (preg_match('/\<\?php/i', $content)) {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($redirect);
                die;
            }
            $CI->load->library('upload', $config);
            if ($CI->upload->do_upload($input)) {
                $FileDataArr[] = $CI->upload->data();
            } else {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $CI->upload->display_errors() . "</div>");
                redirect($redirect);
                die;
            }
        }
    }
    return $FileDataArr;
}
 /*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input  :
      @Output :
      @Date   : 13/07/2017
     */

function do_resize($source, $destination, $filename) {
    $CI =& get_instance ();
    $CI->load->library ('image_lib');
    $CI->image_lib->clear ();
    if (!file_exists ($destination)) {
        mkdir ($destination, 0777, TRUE);
        chmod ($destination, 0777);
    }
    $source_path  = $source . '/' . $filename;
    $target_path  = $destination;
    $config_manip = array(
        'image_library'  => 'gd2',
        'source_image'   => $source_path,
        'new_image'      => $target_path,
        'maintain_ratio' => TRUE,
        'create_thumb'   => TRUE,
        'thumb_marker'   => '',
        'width'          => 150,
        'height'         => 150
    );
    $CI->image_lib->initialize ($config_manip);
    if (!$CI->image_lib->resize ()) {
        echo $CI->image_lib->display_errors ();
    }
    // clear //
    $CI->image_lib->clear ();
}
/*
  @Author : Ritesh Rana
  @Desc   : Get Email settings
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getMailConfig() {

    $CI = & get_instance();
    $dashWhere = "config_key='email_settings'";
    $defaultDashboard = $CI->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
    $configData = (array) json_decode($defaultDashboard[0]['value']);
    return $configData;
}

/*
  @Author : Ritesh Rana
  @Desc   : Send mail with CI Helper
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function send_mail($to, $subject, $message, $attach = NULL) {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        $CI->load->library('email', $config); // load email library
        $CI->email->from($configs['smtp_user'], "NFC Tracker");
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_Email = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $CI->email->initialize($config);
        $config['mailtype'] = "html";
        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from($from_Email, $project_Name);
        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }
        return $CI->email->send();
    }


    // pr($data); exit;
}

/*
  @Author : Ritesh Rana
  @Desc   : Send mail with CI Helper
  @Input  : $attach = array(),
  $headers = array(),
  $configs_arr = array(),
  other inputs are strings
  @Output :
  @Date   : 4/03/2017
 */

function send_mail1($to, $subject, $message, $attach = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $headers = '', $configs_arr = '') {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        $CI->load->library('email', $config); // load email library
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($configs['smtp_user'], "CMS TEST");
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_email_conf = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //$CI->load->library('email', $config); // load email library
        $CI->email->initialize($config);
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->set_newline("\r\n");

        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($from_email_conf, $project_Name);
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
        }
        return $CI->email->send();
    }


    // pr($data); exit;
}

/*
  @Author : Ritesh Rana
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 14/03/2017
 */

function createFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET', $secret);
    return $secret;
}

function generateFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET_DATA', $secret);
    return $secret;
}

/*
  @Author : Ritesh Rana
  @Desc   :validates Token on Form
  @Input  :
  @Output :
  @Date   : 14/03/2017
 */

function validateFormSecret() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

function validateFormSecretsData() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

/*
  @Author : Sanket jayani
  @Desc   :for getting document image name
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function getImgFromFileExtension($file_extension) {
    if ($file_extension == '') {
        $file_extension = 'txt';
    }

    $document_array = array('jpg' => 'jpg-64.png', 'csv' => 'xls-64.png',
        'aac' => 'aac-64.png', 'aib' => 'aib-64.png',
        'avi' => 'avi-64.png', 'docx' => 'docs-64.png',
        'flac' => 'flac-64.png', 'gif' => 'gif-64.png',
        'html' => 'html-64.png', 'js' => 'js-64.png',
        'movs' => 'movs-64.png', 'mp4' => 'mp3-64.png',
        'mp4' => 'mp4-64.png', 'pdf' => 'pdf-64.png', 'default' => 'file-64.png',
        'png' => 'png-64.png', 'psd' => 'psd-64.png',
        'txt' => 'txt-64.png', 'xlsx' => 'xlsx-64.png', 'xls' => 'xls-64.png', 'ppt' => 'ppt-64.png', 'pptx' => 'pptx-64.png');

    if (array_key_exists(strtolower($file_extension), $document_array)) {
        return $document_array[strtolower($file_extension)];
    } else {

        return $document_array['default'];
    }
}

function getCountryName($country_id) {

    $CI = & get_instance();
    $table = COUNTRIES . ' as c';
    $match = "country_id = " . $country_id;
    $fields = array("c.country_name");
    $countryName = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $countryName;
}

function getCategoryName($cat_id) {

    $CI = & get_instance();
    $table = CATEGORY . ' as c';
    $match = "cat_id = " . $cat_id;
    $fields = array("c.cat_name");
    $categoryName = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $categoryName;
}

function getRoleName($role_id) {
    
    //echo "ROLE ID :".$role_id;
    $CI = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $match = "rm.role_id = " . $role_id;
    $fields = array("rm.role_name");
    $roleName = $CI->common_model->get_records($table, $fields, '', '', $match);
    //print_r($roleName);
    return $roleName;
}

function getSelectedModule($id) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.login_id " => $id);
    $fields3 = array("l.role_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
    return $getCountofSupportuser1;
}

function convertTimeTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}

// get Languages from blzdsk_language_master table
function getLanguages() {
    $CI = & get_instance();
    $table = LANGUAGE_MASTER . ' as lm';
    $fields = array("lm.language_id,lm.language_name,lm.name");
    $order_by = 'lm.language_name';
    $order = 'ASC';
    $language_data = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', $order_by, $order);
    return $language_data;
}

// Get User List from Role 
function getUserList($roleID) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.role_id " => $roleID, "l.is_delete" => "0");
    $fields3 = array("l.login_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

    return $getCountofSupportuser1;
}

function getSubcategory($category_id) {
    $CI = & get_instance();
    //print_r($roleName);
    $table = SUBCATEGORY . ' as sct';
    $match = "sct.status = 'active' AND sct.is_deleted = 0 AND sct.cat_id = $category_id";
    $fields = array("sct.subcat_id, sct.subcat_name");
    $subcategory = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $subcategory;
}

function showTotaldata($delivery_order_id) {
    $ci = & get_instance();
    $table = DELIVERY_ITEM_LIST . ' as dit';
    $where = array("dit.delivery_order_id" => $delivery_order_id);
    $fields = array("dit.quantity ,dit.price,dit.quantity * dit.price as 'total'");
    $total_info_id = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    $total_val = "";
    foreach ($total_info_id as $total_data) {
        $total_val += $total_data['total'];
    }
    return $total_val;
}

/*
  @Author : Ritesh Rana
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 10/04/2017
 */

function dropdown($name, array $options, $selected = null, $readonly = null, $first_option = null, $second_option = null) {
    //pr($first_option);die();
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" name="' . $name . '" id="' . $name . '" ' . $readonly . '>' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    if ($first_option != '') {
        $dropdown .= '<option value="">' . $first_option . '</option>' . "\n";
    }
    if ($second_option != '') {
        $select = $selected == '0' ? ' selected' : null;
        $dropdown .= '<option value="0" ' . $select . '>' . $second_option . '</option>' . "\n";
    }
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */

        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}



function check_admin_login()
{
    $CI = & get_instance();  //get instance, access the CI superobject
    $adminLogin = $CI->session->userdata('nfc_admin_session');
    (!empty($adminLogin['admin_id']))?'':redirect('admin');
}

/*
  @Author : Maitrak Modi
  @Desc   : random string generate for password
  @Input  : Length
  @Output : random string
  @Date   : 11th May 2017
 */
function rand_string( $length ) {

  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  return substr(str_shuffle($chars),0,$length);
} 


function checkAdminPermission($controller, $method) {
    $CI = & get_instance();

    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
    if ($CI->session->has_userdata('nfc_admin_session')) {
        $session = $CI->session->userdata('nfc_admin_session');
        $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
        $CI->db->from('aauth_perm_to_group as APG');
        $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
        $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
        $CI->db->where('role_id', $session['admin_type']);
        $CI->db->where('controller_name', $class);
        $resultData = $CI->db->get()->result_array();

        $configPerms = $CI->load->config('acl');
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input 	:
      @Output	:
      @Date   : 04/04/2017
     */
}

function checkFormBuilderData($table) {
    $ci = & get_instance();
    //$table = $table;
    //$match = "";
    $fields = array("*");
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '');
    if (!empty($dateInfo)) {
        return $dateInfo;
    } else {
        return FALSE;
    }
}

?>
