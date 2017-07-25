<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
      //  check_admin_login();
        $this->viewname = 'Dashboard';
    }

    /*
      Author : Niral Patel
      Desc  :
      Input  :
      Output :
      Date   :12/10/2015
     */
    public function index() 
    {
        $data['main_content'] = ADMIN_SITE. '/'.$this->viewname.'/'.$this->viewname;
        $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
    }
    
    

}
