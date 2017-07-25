<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DailyObservation extends CI_Controller {

    function __construct() {

        parent::__construct();
        /*if (checkPermission('DailyObservation', 'view') == false) {
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
      @Date   : 13/07/2017
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
        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.yp_id"=>$ypid);
        $fields = array("do.do_id,do.yp_id,do.daily_observation_date,CONCAT(`firstname`,' ', `lastname`) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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
        $data['header'] = array('menu_module' => 'DailyObservation');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

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
      @Date   : 14/07/2017
     */

    public function createDo($ypid) {
        $data['ypid'] = $ypid;	
        $data['main_content'] = '/create_do';
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert select date
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */

    public function checkDo() {
    	$postData = $this->input->post ();
    	//get daily observation data
    	$table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.daily_observation_date"=>$postData['create_date']);
        $fields = array("do.do_id,do.yp_id,do.daily_observation_date");
        $information = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
        if(empty($information))
        {
        	//insert data of do
        	$insertdata = array(
        		'yp_id'                  => $postData['yp_id'],
        		'daily_observation_date' => $postData['create_date'],
        		'created_by'             => !empty($this->session->userdata('LOGGED_IN')['ID'])?$this->session->userdata('LOGGED_IN')['ID']:''
        		);
        	$data['do_id']      = $this->common_model->insert(DAILY_OBSERVATIONS,$insertdata);
        	$data['is_created'] = true;
        }
        else
        {
        	$data['is_created'] = false;
        	$data['do_id'] = !empty($information[0]['do_id'])?$information[0]['do_id']:'';
        }
        $data['ypid']         = $postData['yp_id'];
        $data['created_date'] = $postData['create_date'];
        $data['main_content'] = '/verify_create_do';
        $data['footerJs'][0]  = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */

    public function view($id) {
    	//get daily observation data
    	$table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.do_id"=>$id);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
        
        $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);
       	
        //get staff details
        $table = DO_STAFF_TRANSECTION . ' as do';
        $where = array("do.do_id"=>$id);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as staff_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');
        
        $data['do_staff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);
		//get food form
		$match = array('food_form_id'=> 1);
		$food_forms = $this->common_model->get_records(FOOD_FORM,'', '', '', $match);
		if(!empty($food_forms))
		{
		    $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
		}

		//get food data
		$match = array('do_id'=> $id);
		$data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED,'', '', '', $match);

		//get SUMMARIES form
		$match = array('do_form_id'=> 1);
		$food_forms = $this->common_model->get_records(DO_FORM,'', '', '', $match);
		if(!empty($food_forms))
		{
		    $data['summary_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
		}
		$data['do_id'] = $id;
		$data['ypid']  = !empty($data['dodata'][0]['yp_id'])?$data['dodata'][0]['yp_id']:'';
			
		$data['main_content'] = '/view';
		$data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
		$this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add overview Page
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */

    public function add_overview($id) {
    	//get daily observation data
    	$table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.do_id"=>$id);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
        
        $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);

    	$data['main_content'] = '/add_overview';
        $data['footerJs'][0]  = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
   /*
      @Author : Niral Patel
      @Desc   : Insert overview
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function insert_overview() {
      	$postData = $this->input->post ();
    	//update data of do
    	$updatedata = array(
    		'awake_time' 	=> date('H:i:s',strtotime($postData['awake_time'])),
    		'bed_time' 		=> date('H:i:s',strtotime($postData['bed_time'])),
    		'contact'       => $postData['contact']
    		);
    	$this->common_model->update(DAILY_OBSERVATIONS,$updatedata,array('do_id'=>$postData['do_id']));

    	redirect('/' . $this->viewname .'/save_overview/'.$postData['do_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : save data overview
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function save_overview($id) {
    	//get daily observation data
    	$data = array(
    		'header' => 'Overview Updated',
    		'detail' =>'The Overview part of the Daily Observation is now updated. Please check your editing carefully.',
    	);
    	$data['do_id'] = $id;
    	$data['main_content'] = '/save_data';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add staff
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */

    public function add_staff($id,$ypid) {
    	//get daily observation data
    	$table = LOGIN;
        $where = array("role_id !="=>1);
        $fields = array("login_id,CONCAT(firstname,' ', lastname) as username");
        $data['userdata'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '','','', '', $where);

        $data['do_id'] = $id;
        $data['ypid'] = $ypid;

    	$data['main_content'] = '/add_staff';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert staff
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function insert_staff() {
      	$postData = $this->input->post ();
    	//update data of do
    	$updatedata = array(
    		'do_id' 	=> $postData['do_id'],
    		'user_id' 		=> $postData['staff'],
    		'created_date' =>datetimeformat()
    		);
    	$this->common_model->insert(DO_STAFF_TRANSECTION,$updatedata);

    	redirect('/' . $this->viewname .'/save_staff/'.$postData['do_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : save data staff
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function save_staff($id) {
    	//get daily observation data
    	$data = array(
    		'header' => 'Daily Obs Updated',
    		'detail' =>'The staff member has been added to the Daily Observation..',
    	);
    	$data['do_id'] = $id;
    	$data['main_content'] = '/save_data';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add summary data
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */

    public function add_summary() {
    	$postData = $this->input->post ();
    	$form_data[0] = !empty($postData['summary_field'])?json_decode($postData['summary_field'], TRUE):'';
    	$data['form_data'] = $form_data;
    	
    	//get daily observation data
    	$table = DAILY_OBSERVATIONS;
        $where = array("do_id"=>$postData['doid']);
        $fields = array("`".$form_data[0]['name']."`",'yp_id');
        $data['edit_data'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
       
        $data['do_id'] = $postData['doid'];
        $data['summary_field'] = $postData['summary_field'];
        $data['ypid'] = $data['edit_data'][0]['yp_id'];
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
    	$data['main_content'] = '/add_summary';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert summary
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function insert_summary() {
      	$postData = $this->input->post ();
      	
      	$form_data = !empty($postData['summary_field'])?json_decode($postData['summary_field'], TRUE):'';
      	
      	if(!empty($form_data))
      	{
      		if(isset($form_data['name']))
			{
			    if($form_data['type'] == 'file')
			    { 
			      $filename = $form_data['name'];
			      //get image previous image
			      $match = array('yp_id'=> $postData['yp_id']);
			      $pp_yp_data = $this->common_model->get_records(DAILY_OBSERVATIONS,array($form_data['name']), '', '', $match);
			      //delete img
			      if(!empty($postData['hidden_'.$form_data['name']]))
			      {
			          $delete_img = explode(',', $postData['hidden_'.$form_data['name']]);
			          $db_images = explode(',',$pp_yp_data[0][$filename]);
			          $differentedImage = array_diff ($db_images, $delete_img);
			          $pp_yp_data[0][$filename] = !empty($differentedImage)?implode(',',$differentedImage):'';
			          if(!empty($delete_img))
			          {
			              foreach ($delete_img as $img) {

			                if (file_exists ($this->config->item ('do_img_url') .$postData['yp_id'].'/'.$img)) { 
			                    unlink ($this->config->item ('do_img_url') .$postData['yp_id'].'/'.$img);
			                }
			                if (file_exists ($this->config->item ('do_img_url_small') .$postData['yp_id'].'/'.$img)) {
			                    unlink ($this->config->item ('do_img_url_small') .$postData['yp_id'].'/'.$img);
			                }
			              } 
			          }
			      }
			     
			      if(!empty($_FILES[$filename]['name'][0]))                     
			      {
			          //create dir and give permission
                if (!is_dir($this->config->item('do_base_url'))) {
                        mkdir($this->config->item('do_base_url'), 0777, TRUE);
                }

                if (!is_dir($this->config->item('do_base_big_url'))) {                                
                    mkdir($this->config->item('do_base_big_url'), 0777, TRUE);
                }
                
                
                if (!is_dir($this->config->item('do_base_big_url') . '/' . $postData['yp_id'])) {
                    mkdir($this->config->item('do_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                }
			          $file_view = $this->config->item ('do_img_url').$postData['yp_id'];
			          //upload big image
			          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


			          //upload small image
			          $insertImagesData = array();
			          if(!empty($upload_data))
			          {
			            foreach ($upload_data as $imageFiles) {
                    if (!is_dir($this->config->item('do_base_small_url'))) {                                        
                            mkdir($this->config->item('do_base_small_url'), 0777, TRUE);
                        }
                        
                        if (!is_dir($this->config->item('do_base_small_url') . '/' . $postData['yp_id'])) {                                        
                            mkdir($this->config->item('do_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                        }
			                $a = do_resize ($this->config->item ('do_img_url') . $postData['yp_id'], $this->config->item ('do_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
			            $data[$form_data['name']] = !empty($images)?$images:'';
			          }
			        }
			        else
			        {
			            $data[$form_data['name']] = !empty($pp_yp_data[0][$filename])?$pp_yp_data[0][$filename]:'';
			        }
			    }
			    else{
			          if($form_data['type'] != 'button')
			          {
			              if($form_data['type'] == 'checkbox-group')
			              {$data[$form_data['name']] = !empty($postData[$form_data['name']])?implode(',',$postData[$form_data['name']]):'';}
			              else{$data[$form_data['name']] = strip_slashes($postData[$form_data['name']]);}
			          }
			      }
			}
      	}
		
    	//update data of do
    	 $this->common_model->update(DAILY_OBSERVATIONS,$data,array('do_id'=>$postData['do_id']));

    	redirect('/' . $this->viewname .'/save_summary/'.$postData['do_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : save data summary
      @Input 	:
      @Output	:
      @Date   : 14/07/2017
     */
      public function save_summary($id) {
    	//get daily observation data
    	$data = array(
    		'header' => 'Daily Obs Updated',
    		'detail' =>'The daily observation sheet has been updated. Please check your editing carefully.',
    	);
    	$data['do_id'] = $id;
    	$data['main_content'] = '/save_data';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add food data
      @Input 	:
      @Output	:
      @Date   : 17/07/2017
     */

    public function add_food($id,$ypid) {
    	$postData = $this->input->post ();
    	//get food form
		$match = array('food_form_id'=> 1);
		$food_forms = $this->common_model->get_records(FOOD_FORM,'', '', '', $match);
		if(!empty($food_forms))
		{
		    $data['form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
		}

		//get food data
		$match = array('do_id'=> $id);
		$data['edit_data'] = $this->common_model->get_records(DO_FOODCONSUMED,'', '', '', $match);

        $data['do_id'] = $id;
        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
    	$data['main_content'] = '/add_food';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert food data
      @Input  :
      @Output :
      @Date   : 17/07/2017
     */
    public function insert_food()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('food_form_id'=> 1);
       $form_data = $this->common_model->get_records(FOOD_FORM,'', '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(DO_FOODCONSUMED,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('do_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('do_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('do_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('do_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('do_base_url'))) {
                                  mkdir($this->config->item('do_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('do_base_big_url'))) {                                
                              mkdir($this->config->item('do_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('do_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('do_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('do_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                               if (!is_dir($this->config->item('do_base_small_url'))) {                                        
                                    mkdir($this->config->item('do_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('do_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('do_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('do_img_url') . $postData['yp_id'], $this->config->item ('do_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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

       //get food data
		$match = array('do_id'=> $postData['do_id']);
		$food_data = $this->common_model->get_records(DO_FOODCONSUMED,'', '', '', $match);

        if(!empty($food_data))
        {
             $this->common_model->update(DO_FOODCONSUMED,$data,array('do_id'=>$postData['do_id']));
            
        }
        else
        {
        	 $data['do_id'] = $postData['do_id'];
             $data['yp_id'] = $postData['yp_id'];
             $this->common_model->insert(DO_FOODCONSUMED, $data);
        }
        
        redirect('/' . $this->viewname .'/save_food/'.$postData['do_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data food
      @Input 	:
      @Output	:
      @Date   : 17/07/2017
     */
      public function save_food($id) {
    	//get daily observation data
    	$data = array(
    		'header' => 'Food Updated',
    		'detail' =>'The Food part of the Daily Observation is now updated. Please check your editing carefully..',
    	);
    	$data['do_id'] = $id;
    	$data['main_content'] = '/save_data';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
}
