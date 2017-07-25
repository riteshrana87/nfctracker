<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /*
      Author : Ritesh Rana
      Desc  :
      Input  :
      Output :
      Date   :11/05/2017
     */

    public function index() {
        $admin_session = $this->session->userdata('nfc_admin_session');
        if ($admin_session['active'] === TRUE) {
            redirect(base_url(ADMIN_SITE . '/Dashboard'));
        } else {
            $this->do_login();
        }
    }

    /*
      @Description : Check Login is valid or not
      @Author      : Ritesh rana
      @Input       : adminemail, passowrd and / or adminemail
      @Output      : true or false
      @Date        : 11-05-2017
     */

    public function do_login() {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        if ($email && $password) {
            $field = array('firstname', 'lastname', 'role_id', 'login_id', 'email', 'profile_img', 'status');
            $match = array('email' => $email, 'password' => $password, 'role_id' => 1,'status'=>1);
            $udata = $this->common_model->get_records(LOGIN, $field, '', '', $match);
            
            $session_id = session_id();
            if (count($udata) > 0) {
                if ($udata[0]['role_id'] == 1) {
                    if ($udata[0]['status'] == 'active') {
                        $newdata = array(
                            'name' => !empty($udata[0]['firstname']) ? $udata[0]['firstname'].' '.$udata[0]['lastname'] : '',
                            'admin_id' => $udata[0]['login_id'],
                            'admin_email' => $udata[0]['email'],
                            'admin_type' => $udata[0]['role_id'],
                            'admin_image' => $udata[0]['profile_img'],
                            'session_id' => $session_id,
                            'active' => TRUE);
                        $this->session->set_userdata('nfc_admin_session', $newdata);
                        redirect(base_url(ADMIN_SITE));
                    } else {
                        $msg = $this->lang->line('inactive_account');
                        $newdata = array('msg' => $msg);
                        $data['msg'] = $msg;
                        $this->load->view('admin', $data);
                    }
                } else {
                    $msg = $this->lang->line('invalid_us_pass');
                    $newdata = array('msg' => $msg);
                    $data['msg'] = $msg;
                    $this->load->view('admin', $data);
                }
            } else {
                $msg = $this->lang->line('invalid_us_pass');
                $newdata = array('msg' => $msg);
                $data['msg'] = $msg;
                $this->load->view('admin', $data);
            }
        } else {
            $data['msg'] = $this->session->flashdata('msg');
            $this->load->view('admin', $data);
        }
    }

    /*
      Author : Ritesh rana
      Desc   : Send mail to given email id
      Input  : Email id
      Output : Sent mail to given email id
      Date   : 11/05/2017
     */

    public function forgot_password() {
        $email = $this->input->post('forgot_email');
        $field = array('firstname', 'lastname', 'role_id', 'login_id', 'email', 'profile_img', 'status');
        $match = array('email' => $email, 'role_id' => 1);
        $result = $this->common_model->get_records(LOGIN, $field, '', '', $match);

        if ((count($result)) > 0) {
            $name = $result[0]['firstname'].' '. $result[0]['lastname'];
            $email = $result[0]['email'];
            $pass_variable_activation = array('name' => $name, 'email' => $email);
            $data['actdata'] = $pass_variable_activation;
            $this->load->view(ADMIN_SITE . '/' . 'reset_password', $data);
        } else {
            $msg = "No such user found";
            $data['msg'] = $msg;
            $this->load->view('admin', $data);
        }
    }

    /*
      Author : Ritesh Rana
      Desc  :
      Input  :
      Output :
      Date   :11/05/2017
     */

    public function reset_password() {
        $this->load->view('reset_password');
    }

    public function add_new_password() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $rpassword = $this->input->post('rpassword');

        if ($password == $rpassword) {
            $field = array('firstname', 'lastname', 'role_id', 'login_id', 'email', 'profile_img', 'status');
            $match = array('email' => $email);
            $result = $this->common_model->get_records(LOGIN, $field, '', '', $match);
            $where = array('login_id' => $result[0]['login_id']);
            $cdata['password'] = md5($password);
            $user_id = $this->common_model->update(LOGIN, $cdata, $where);

            $msg = 'Successfully Change Password.';
            $data['msg'] = $msg;
            $this->load->view('admin', $data);
        } else {
            $msg = 'Invalid old password.';
            $data['msg'] = $msg;
            $this->load->view(ADMIN_SITE . '/' . 'reset_password', $data);
        }
    }

    public function check_user() {
        $user_data = $this->login_model->get_all_data('users');
        $data['main_content'] = ADMIN_SITE . '/login/login';
        $this->load->view(ADMIN_SITE . '/assets/templatelogoin', $data);
    }

}
