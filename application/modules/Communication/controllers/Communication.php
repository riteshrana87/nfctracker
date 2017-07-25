<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Communication extends CI_Controller {

    function __construct() {

        parent::__construct();
        /*if (checkPermission('Communication', 'view') == false) {
            redirect('/Dashboard');
        }*/
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : DO LIst Page
      @Input 	:
      @Output	:
      @Date   : 17/07/2017
     */

    public function index($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('daily_observation_data');
        }

        $searchsort_session = $this->session->userdata('daily_observation_data');
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
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = COMMUNICATION_LOG . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*");
        //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        
        $data['ypid'] = $ypid;
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

        $this->session->set_userdata('daily_observation_data', $sortsearchpage_data);
        setActiveSession('daily_observation_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //get communication form
        $match = array('coms_form_id'=> 1);
        $formsdata = $this->common_model->get_records(COMS_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create do page
      @Input 	:
      @Output	:
      @Date   : 17/07/2017
     */

    public function add_communication($ypid) {
        //get communication form
        $match = array('coms_form_id'=> 1);
        $formsdata = $this->common_model->get_records(COMS_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['ypid'] = $ypid;	
        $data['main_content'] = '/add_communication';
        $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert pp form
      @Input    :
      @Output   :
      @Date   : 10/07/2017
     */
   public function insert()
   {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
        $match = array('coms_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(COMS_FORM,'', '', '', $match);
        if(!empty($pp_forms))
        {
            $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                     if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(COMMUNICATION_LOG,array($row['name']), '', '', $match);
                      //delete img
                      if(!empty($postData['hidden_'.$row['name']]))
                      {
                          $delete_img = explode(',', $postData['hidden_'.$row['name']]);
                          $db_images = explode(',',$pp_yp_data[0][$filename]);
                          $differentedImage = array_diff ($db_images, $delete_img);
                          $pp_yp_data[0][$filename] = !empty($differentedImage)?implode(',',$differentedImage):'';
                          if(!empty($delete_img))
                          {
                              foreach ($delete_img as $img) {

                                if (file_exists ($this->config->item ('communication_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('communication_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('communication_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('communication_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('communication_base_url'))) {
                                  mkdir($this->config->item('communication_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('communication_base_big_url'))) {                                
                              mkdir($this->config->item('communication_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('communication_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('communication_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('communication_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('communication_base_small_url'))) {                                        
                                    mkdir($this->config->item('communication_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('communication_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('communication_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('communication_img_url') . $postData['yp_id'], $this->config->item ('communication_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            if(!empty($pp_yp_data[0][$filename]))
                            {
                              $images .=','.$pp_yp_data[0][$filename];
                            }
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                        else
                        {
                            $data[$row['name']] = !empty($pp_yp_data[0][$filename])?$pp_yp_data[0][$filename]:'';
                        }
                    }
                    else{
                          if($row['type'] != 'button')
                          {
                              if($row['type'] == 'checkbox-group')
                              {$data[$row['name']] = !empty($postData[$row['name']])?implode(',',$postData[$row['name']]):'';}
                              else{$data[$row['name']] = strip_slashes($postData[$row['name']]);}
                          }
                      }
                }
            }
        }

        if(!empty($postData['communication_log_id']))
        {
             $data['communication_log_id'] = $postData['communication_log_id'];
             $data['yp_id'] = $postData['yp_id'];

             $this->common_model->update(COMMUNICATION_LOG,$data,array('communication_log_id'=>$postData['communication_log_id']));
        }
        else
        {
             $data['yp_id'] = $postData['yp_id'];
             $this->common_model->insert(COMMUNICATION_LOG, $data);
        }
        redirect('/' . $this->viewname .'/save_comm/'.$postData['yp_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : save data communication
      @Input  :
      @Output :
      @Date   : 17/07/2017
     */
      public function save_comm($id) {
      //get daily observation data
      $data = array(
        'header' => 'You have added a new Communication:',
        'detail' =>'New Communication has been updated. Please check your added carefully.',
      );
      $data['do_id'] = $id;
      $data['main_content'] = '/save_data';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
  
    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input 	:
      @Output	:
      @Date   : 15/07/2017
     */

    public function view($id) {
    	//get daily observation data
    	  $table = COMMUNICATION_LOG . ' as do';
        $where = array("do.communication_log_id"=>$id);
        $fields = array("do.*");
        $data['medical_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '','','', '', $where);
       	
		    //get communication form
        $match = array('coms_form_id'=> 1);
        $formsdata = $this->common_model->get_records(COMS_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
    		$data['do_id'] = $id;
    		$data['ypid']  = !empty($data['medical_data'][0]['yp_id'])?$data['medical_data'][0]['yp_id']:'';
    			
    		$data['main_content'] = '/view';
    		$data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
    		$this->parser->parse('layouts/DefaultTemplate', $data);
    }
}
