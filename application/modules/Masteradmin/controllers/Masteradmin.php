<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Masteradmin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form'));
        //This method will have the credentials validation
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index() {
        $this->login();
    }

    public function removed_session() {
        $session = $this->input->post('session_id');
        $where = array('id' => $session);
        $this->common_model->delete(CI_SESSION, $where);
        //pr($this->db->last_query());exit;
    }

    public function login() {
        $data['error'] = $this->session->userdata('ERRORMSG');   //Pass Error message
        $data['main_content'] = '/login';      //Pass Content
        $data['session_id'] = session_id();
        //$this->parser->parse('layouts/LoginTemplate', $data);
		$this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Verify login information
      Input  : Post User Email and password for verify
      Output : If login then redirect on Home page and if not then redirect on login page
      Date   : 18/01/2016
     */

    public function verifylogin() {
        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            //$error_array = validation_errors();
            $error_msg = ERROR_START_DIV_NEW . lang('ERROR_MSG_LOGIN') . ERROR_END_DIV;
            $this->session->set_userdata('ERRORMSG', $error_msg);
            redirect($this->viewname);
        } else {
            //Login sucessfully done so now redirect on Dashboard page
            //$login_info = $this->session->userdata('LOGGED_IN');
            $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information

            $master_user_id = $this->config->item('master_user_id');
            //$master_user_id = $data['user_info']['ID'];
            redirect(base_url('Dashboard')); //Redirect on Dashboard
        }
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : This function is Call back function
      Input  : $password
      Output : Return false and true
      Date   : 18/01/2016
     */

    function check_database() {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $session_id = session_id();
        //PROFILE_PHOTO added by sanket on 29_02_2016
        
        $email = quotes_to_entities($this->input->post('email'));
        $password = quotes_to_entities($this->input->post('password'));
        //timezone added by maulik suthar 15-03-16
        $timezone = $this->input->post('timezone');
        //Compare Email and password from database
        $table = LOGIN.' as l';
        $match = "l.email = '" . $email . "' && l.password = '" . md5($password) . "' && l.status = 'active' && l.is_delete = 0";
         $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.role_id');
        $result = $this->common_model->get_records($table, array("l.login_id, l.firstname, l.lastname, l.email, l.role_id,rm.role_name"), $join_tables, 'left', $match);
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'ID' => $row['login_id'],
                    'EMAIL' => $row['email'],
                    'FIRSTNAME' => $row['firstname'],
                    'LASTNAME' => $row['lastname'],
                    'ROLE_TYPE' => $row['role_id'],
                    'TIMEZONE' => $timezone,
                    //'session_id' => $session_id
                );
                
                $this->session->set_userdata('LOGGED_IN', $sess_array);
                /*$match = "login_id = '" . $row['login_id'] . "'";
                $log_data = $this->common_model->get_records(LOG_MASTER, array("login_id, ip_address, session_id"), '', '', $match);
                foreach ($log_data as $log_result) {
                    $where = array('id' => $log_result['session_id']);
                    $this->common_model->delete(CI_SESSION, $where);
                }
*/
                
//                $login_id = $row['login_id'];
//                $check_login['session_id'] = $session_id;
//                $check_login['login_id'] = $login_id;
//                $check_login['ip_address'] = $ip_address;
//                $check_login['browser'] = $browser;
//                $check_login['date'] = datetimeformat();
//                //pr($check_login);exit;
//                $this->common_model->insert(LOG_MASTER, $check_login);
                
            }

            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', $this->lang->line('ERROR_INVALID_CREDENTIALS'));
            return false;
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Forgotpassword page redirect
      Input  :
      Output :
      Date   : 14/03/2017
     */

    public function forgotpassword() {


        $data['main_content'] = '/forgotpassword';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      Author : Ritesh Rana
      Desc   : resetpassword prepare template and sent to requester
      Input  :
      Output :
      Date   : 15/04/2017
     */

    public function resetpassword() {

        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
            $this->session->set_flashdata('msgs', $msg);
            redirect('Masteradmin/forgotpassword');
        } else {
            $exitEmailId = $this->checkEmailId($this->input->post('email'));
            if (empty($exitEmailId)) {
                // error
                $msg = $this->lang->line('email_id_not_exit');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                //redirect('user/register');
                redirect('Masteradmin/forgotpassword');
            } else {
                if ($this->input->post('email')) {

                    $token = md5($this->input->post('email') . date("Y-m-d H:i:s"));
                    $newpasswordlink = "<a href='" . base_url() . "/Masteradmin/updatepassword?token=" . $token . "'>" . "Click Here" . "</a>";

                    // Get Template from Template Master
                    $table = EMAIL_TEMPLATE_MASTER . ' as et';
                    // $match = "et.subject ='Forgot Password' ";
                    $match = "et.template_id =1";
                    $fields = array("et.subject,et.body");
                    $template = $this->common_model->get_records($table, $fields, '', '', $match);

                    $body1 = str_replace("{PASS_KEY_URL}", $newpasswordlink, $template[0]['body']);

                    $to = $this->input->post('email');
                    $body = str_replace("{SITE_NAME}", base_url(), $body1);
                    $subject = "NFC Tracker :: " . $template[0]['subject'];

                    $data = array('reset_password_token' => $token, 'modified_date' => datetimeformat());
                    $where = array('email' => $this->input->post('email'));

                    if ($this->common_model->update(LOGIN, $data, $where)) {
                        //send_mail($to, $subject, $body);
                        if (send_mail($to, $subject, $body)) {
                            $msg = $this->lang->line('new_password_sent');
                        } else {

                            $msg = $this->lang->line('FAIL_WITH_SENDING_EMAILS');
                        }

                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        redirect('Masteradmin/forgotpassword');
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect('user/register');
                        redirect('Masteradmin/forgotpassword');
                    }
                }
            }

            redirect('Masteradmin/forgotpassword');
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Update Password Page
      Input  :
      Output :
      Date   : 15/03/2017
     */

    public function updatepassword() {
    	$token_ID = $this->input->get('token');
    	if($token_ID != ""){

    		$table1 = LOGIN . ' as l';
    		$match1 = "l.reset_password_token = '".$token_ID."'";
    		$fields1 = array("l.login_id");
    		$checkTokenexist = $this->common_model->get_records($table1, $fields1, '', '', $match1);
    		if(isset($checkTokenexist[0]['login_id']) && $checkTokenexist[0]['login_id'] !="" ){
    			$data['main_content'] = '/updatepassword';

                        $this->parser->parse('layouts/DefaultTemplate', $data);
                        
    		}else{
    			 
    			redirect('Masteradmin');
    		}

    	}else{
			
			redirect('Masteradmin');
    	}

    }

    /*
      Author : Ritesh Rana
      Desc   : Update Password to requested person redirect to updatepassword page
      Input  :
      Output :
      Date   : 16/03/2017
     */

    public function updatePasswords() {
        $this->form_validation->set_rules('password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|md5|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $msg = validation_errors();
            $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");

            $redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
            redirect($redirect_to);
        } else {

            $tokenID = $this->input->post('tokenID');
            $password = $this->input->post('password');

            if($tokenID !=""){
            	
	           	
            	
            	$data = array('password' => $password, 'modified_date' => datetimeformat());
            	$where = array('reset_password_token' => $tokenID);

            	$affectedrow = $this->common_model->update(LOGIN, $data, $where);
            	//$affectedrow = $this->db->affected_rows();
			
            	if ($affectedrow) {
            		$msg = $this->lang->line('newpasswordupdated');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-success text-center'>$msg</div>");
            		// Once Requester update the password with token then here Token will be remove from db.
            		$data1 = array('reset_password_token' => '', 'modified_date' => datetimeformat());
            		$where1 = array('reset_password_token' => $tokenID);
            		$this->common_model->update(LOGIN, $data1, $where1);

            		redirect('Masteradmin');
            	} else {
            		// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		//redirect('user/register');
            		redirect('Masteradmin/updatepassword');
            	}
            }else{
					// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		redirect('user/register');
            			//redirect('Masteradmin');
			}
            
            
            
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Check Email id is exist into DB or not
      Input  :
      Output :
      Date   : 16/03/2017
     */

    public function checkEmailId($emailID) {
        $table = LOGIN . ' as l';
        $match = "l.email = '" . $emailID . "' AND l.is_delete = 0";
        $fields = array("l.login_id,l.status");
        $data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
        return $data['duplicateEmail'];
    }

}
