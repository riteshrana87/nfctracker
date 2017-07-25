<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KeySession extends CI_Controller {

    function __construct() {

        parent::__construct();
        /*if (checkPermission('KeySession', 'view') == false) {
            redirect('/Dashboard');
        }*/
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Key session Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 13/07/2017
     */

    
    public function index($id) {
        $searchtext='';
        $perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('ks_data');
        }

        $searchsort_session = $this->session->userdata('ks_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'ks_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        
        //Query
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = KEY_SESSION.' as ks';
        $where = array("ks.yp_id"=> $id,"ks.created_by"=> $login_user_id);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR ks.date LIKE "%'.$searchtext.'%" OR ks.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['edit_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
        
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        //echo $this->db->last_query();exit;
//pr($data['edit_data']);exit;
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        
        
        $match = array('yp_id'=> $id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        $data['ypid'] = $id;
        //get ks form
        $match = array('ks_form_id'=> 1);
        $ks_forms = $this->common_model->get_records(KS_FORM,'', '', '', $match);
        if(!empty($ks_forms))
        {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
        $this->session->set_userdata('ks_data', $sortsearchpage_data);
        setActiveSession('ks_data'); // set current Session active
        $data['header'] = array('menu_module' => 'ks' );
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/keysession';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : create yp edit page
      @Input 	:
      @Output	:
      @Date   : 13/07/2017
     */

    public function create($id) {
       //get ks form
       $match = array('ks_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(KS_FORM,'', '', '', $match);
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
       //echo htmlspecialchars_decode($pp_forms[0]['form_data']);exit;
       //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

       //pr($data['edit_data']);exit;
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
        $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');

        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : Insert ks form
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */
   public function insert()
   {
       //pr($_POST);exit;
        $postData = $this->input->post ();

        unset($postData['submit_ksform']);
        //get pp form
       $match = array('ks_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(KS_FORM,'', '', '', $match);
       if(!empty($ks_forms))
       {
            $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($ks_form_data as $row) {
                if(isset($row['name']))
                {
                
                    if($row['type'] == 'file')
                    {
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $ks_yp_data = $this->common_model->get_records(KEY_SESSION,array($row['name']), '', '', $match);
                      //delete img
                      if(!empty($postData['hidden_'.$row['name']]))
                      {
                          $delete_img = explode(',', $postData['hidden_'.$row['name']]);
                          $db_images = explode(',',$pp_yp_data[0][$filename]);
                          $differentedImage = array_diff ($db_images, $delete_img);
                          $ks_yp_data[0][$filename] = !empty($differentedImage)?implode(',',$differentedImage):'';
                          if(!empty($delete_img))
                          {
                              foreach ($delete_img as $img) {

                                if (file_exists ($this->config->item ('ks_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('ks_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('ks_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('ks_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                         //create dir and give permission
                        if (!is_dir($this->config->item('ks_base_url'))) {
                                mkdir($this->config->item('ks_base_url'), 0777, TRUE);
                        }

                        if (!is_dir($this->config->item('ks_base_big_url'))) {                                
                            mkdir($this->config->item('ks_base_big_url'), 0777, TRUE);
                        }
                        
                        
                        if (!is_dir($this->config->item('ks_base_big_url') . '/' . $postData['yp_id'])) {
                            mkdir($this->config->item('ks_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                        }
                        $file_view = $this->config->item ('ks_img_url').$postData['yp_id'];
                        //upload big image
                        $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                        //upload small image
                        $insertImagesData = array();
                        if(!empty($upload_data))
                        {
                          foreach ($upload_data as $imageFiles) {
                              if (!is_dir($this->config->item('ks_base_small_url'))) {                                        
                                    mkdir($this->config->item('ks_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('ks_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('ks_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                              $a = do_resize ($this->config->item ('ks_img_url') . $postData['yp_id'], $this->config->item ('ks_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                              array_push($insertImagesData,$imageFiles['file_name']);
                              if(!empty($insertImagesData))
                              {
                                $images = implode(',',$insertImagesData);
                              }
                          }
                         /* if(!empty($ks_yp_data[0][$filename]))
                          {
                            $images .=','.$ks_yp_data[0][$filename];
                          }*/
                          if(!empty($images))
                          {
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }


                        }
                        /*else
                        {
                          if(!empty($ks_yp_data[0][$filename]))
                          {
                            $data[$row['name']] = !empty($ks_yp_data[0][$filename])?$ks_yp_data[0][$filename]:'';
                          }
                        }*/
                    }
                    else{
                          if($row['type'] != 'button')
                          {
                            if(!empty($postData[$row['name']]))
                            {
                              if($row['type'] == 'checkbox-group')
                              {$data[$row['name']] = !empty($postData[$row['name']])?implode(',',$postData[$row['name']]):'';}
                              else{$data[$row['name']] = strip_slashes($postData[$row['name']]);}
                            }
                          }
                      }
                    
                }
            }
       }
       
        if(!empty($postData['ks_id']))
        {
             $data['ks_id'] = $postData['ks_id'];
             $data['yp_id'] = $postData['yp_id'];
             $main_user_data = $this->session->userdata('LOGGED_IN');
             $data['created_by'] = $main_user_data['ID'];

             $this->common_model->update(KEY_SESSION,$data,array('ks_id'=>$postData['ks_id']));
             /*$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>Successfully updated.</div>");*/
        }
        else
        {
            if(!empty($data))
            {
               $data['yp_id'] = $postData['yp_id'];
               $main_user_data = $this->session->userdata('LOGGED_IN');
               $data['created_by'] = $main_user_data['ID'];
               $this->common_model->insert(KEY_SESSION, $data);
               $data['ks_id'] = $this->db->insert_id();
            }
             /*$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>Successfully inserted.</div>");*/
        }
        if(!empty($data))
        {
          redirect('/' . $this->viewname .'/save_ks/'. $data['ks_id'].'/'.$data['yp_id']);
        }
        else
        {
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert key session details.</div>");
          redirect('/' . $this->viewname .'/create/'.$postData['yp_id']);
        }
   }
    /*
      @Author : Ritesh Rana
      @Desc   : Save ks form
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */
   public function save_ks($ks_id,$yp_id)
   {
        $data['yp_id'] = $yp_id;
        $data['ks_id'] = $ks_id;
        $data['main_content'] = '/save_ks';
        $this->parser->parse('layouts/DefaultTemplate', $data);
   }
   
   public function view($ks_id,$yp_id) {
       //get ks form
       $match = array('ks_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(KS_FORM,'', '', '', $match);
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
    //get YP information
        $match = "yp_id = " . $yp_id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get ks yp data
        $match = array('ks_id'=> $ks_id);
        $data['edit_data'] = $this->common_model->get_records(KEY_SESSION,'', '', '', $match);

        $data['ypid'] = $yp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/view';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
                           
}
