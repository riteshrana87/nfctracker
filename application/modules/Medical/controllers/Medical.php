<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Medical extends CI_Controller {

    function __construct() {

        parent::__construct();
        /* if (checkPermission('KeySession', 'view') == false) {
          redirect('/Dashboard');
          } */
        $this->viewname = $this->router->fetch_class();
        $this->method = 'mp_ajax';
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Documents Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 18/07/2017
     */

    public function index($id) {
        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get mi details
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['mi_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        //get mac details
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['mac_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

        //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['mac_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        //get mp form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get omi details
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['omi_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);
        
        //get omi form
        $match = array('omi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(OMI_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['omi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['miform_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
        
        //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['mi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        /*mp data start*/
        $config['per_page'] = '10';
        $data['perpage'] = '10';
        $data['searchtext'] = '';
        $sortfield = 'mp_id';
        $sortby = 'desc';
        $data['sortfield'] = $sortfield;
        $data['sortby'] = $sortby;
        $config['uri_segment'] = 4;
        $uri_segment = $this->uri->segment(4);
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/mp_ajax/'.$id;
        $table = MEDICAL_PROFESSIONALS . ' as mc';
        $where = array("mc.yp_id"=>$id);
        $fields = array("mc.*");
        
        if (!empty($searchtext)) {
            
        } else {
            $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }

        
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

        $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
        setActiveSession('professional_medication_session_data'); // set current Session active
        /*end mp data*/
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/medical';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : ajax mp data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function mp_ajax($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('professional_medication_session_data');
        }

        $searchsort_session = $this->session->userdata('professional_medication_session_data');
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
                $sortfield = 'administer_medication_id';
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
                $config['per_page'] = $perpage;
                $data['perpage'] = $perpage;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/mp_ajax/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICAL_PROFESSIONALS . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*");
        //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
        if (!empty($searchtext)) {
            
        } else {
            $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            
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

        $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
        setActiveSession('professional_medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        
        //get communication form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $this->load->view($this->viewname . '/mp_ajaxlist', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mi data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mi($ypid) {
      $postData = $this->input->post ();
      //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        //get mi details
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['edit_data'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);

        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mi';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert mi data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mi()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('mi_form_id'=> 1);
       $form_data = $this->common_model->get_records(MI_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_INOCULATIONS,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
      $match = array('yp_id'=> $postData['yp_id']);
      $check_data = $this->common_model->get_records(MEDICAL_INOCULATIONS,'', '', '', $match);

        if(!empty($check_data))
        {
             $this->common_model->update(MEDICAL_INOCULATIONS,$data,array('yp_id'=>$postData['yp_id']));
            
        }
        else
        {
            $data['yp_id'] = $postData['yp_id'];
            $this->common_model->insert(MEDICAL_INOCULATIONS, $data);
        }
        
        redirect('/' . $this->viewname .'/save_mi/'.$postData['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mi
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mi($id) {
          //get daily observation data
          $data = array(
          'header' => 'INOCULATIONS Updated',
          'detail' =>'The inoculations section of the Medical Information is now updated. Please check your editing carefully.',
          );
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
  @Author : Niral Patel
  @Desc   : Add omi data
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */

public function add_omi($ypid) {
  $postData = $this->input->post ();
  //get omi form
    $match = array('omi_form_id'=> 1);
    $formsdata = $this->common_model->get_records(OMI_FORM,'', '', '', $match);
    if(!empty($formsdata))
    {
        $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
    }

    //get omi details
    $match = "yp_id = " . $ypid;
    $fields = array("*");
    $data['edit_data'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);

    $data['ypid'] = $ypid;
    $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
    $data['main_content'] = '/add_omi';
    $this->parser->parse('layouts/DefaultTemplate', $data);
}
/*
  @Author : Niral Patel
  @Desc   : Insert omi data
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */
public function insert_omi()
{
    $postData = $this->input->post ();
    unset($postData['submit_ppform']);
    //get pp form
   $match = array('omi_form_id'=> 1);
   $form_data = $this->common_model->get_records(OMI_FORM,'', '', '', $match);
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
                  $pp_yp_data = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION,array($row['name']), '', '', $match);
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

                            if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                            }
                            if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                            }
                          } 
                      }
                  }
                 
                  if(!empty($_FILES[$filename]['name'][0]))                     
                  {
                      //create dir and give permission
                      if (!is_dir($this->config->item('medical_base_url'))) {
                              mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                      }

                      if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                          mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                      }
                      
                      
                      if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                          mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                      }
                      $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                      //upload big image
                      $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                      //upload small image
                      $insertImagesData = array();
                      if(!empty($upload_data))
                      {
                        foreach ($upload_data as $imageFiles) {
                            if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                            $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
  $match = array('yp_id'=> $postData['yp_id']);
  $check_data = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION,'', '', '', $match);

    if(!empty($check_data))
    {
         $this->common_model->update(OTHER_MEDICAL_INFORMATION,$data,array('yp_id'=>$postData['yp_id']));
        
    }
    else
    {
        $data['yp_id'] = $postData['yp_id'];
        $this->common_model->insert(OTHER_MEDICAL_INFORMATION, $data);
    }
    
    redirect('/' . $this->viewname .'/save_omi/'.$postData['yp_id']);
}
/*
  @Author : Niral Patel
  @Desc   : save data omi
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */
  public function save_omi($id) {
      $data = array(
      'header' => 'OTHER MEDICAL INFO Updated',
      'detail' =>'The other medical information section of the Medical Information is now updated. Please check your editing carefully.',
      );
      $data['yp_id'] = $id;
      $data['main_content'] = '/save_data';
      $this->parser->parse('layouts/DefaultTemplate', $data);
}
    /*
      @Author : Niral Patel
      @Desc   : Add mac data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mp($ypid) {
      $postData = $this->input->post ();
      //get mac form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mp';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert mp data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mp()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('mp_form_id'=> 1);
       $form_data = $this->common_model->get_records(MP_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            /*if(!empty($pp_yp_data[0][$filename]))
                            {
                              $images .=','.$pp_yp_data[0][$filename];
                            }*/
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

      $data['yp_id'] = $postData['yp_id'];
      $this->common_model->insert(MEDICAL_PROFESSIONALS, $data);
        
        redirect('/' . $this->viewname .'/save_mp/'.$postData['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mp
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mp($id) {
          //get daily observation data
          $data = array(
          'header' => 'CONSENTS Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
     /*
      @Author : Niral Patel
      @Desc   : Add mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */

    public function add_appointment($mp_id,$ypid) {
      
      //get mi details
      $match = array('mp_id'=> $mp_id);
      $data['mp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);
      //get mac form
      $match = array('mp_form_id'=> 1);
      $formsdata = $this->common_model->get_records(MP_FORM,'', '', '', $match);
      if(!empty($formsdata))
      {
          $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
      }

      $data['ypid'] = $ypid;
      $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
      $data['main_content'] = '/add_appointment';
      $this->parser->parse('layouts/DefaultTemplate', $data);
    }
     /*
      @Author : Niral Patel
      @Desc   : Insert mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */
     public function insert_appointment() {
        $postData = $this->input->post();
        
        $data = array(
            'mp_id'            => $postData['mp_id'],
            'yp_id'            => $postData['yp_id'],
            'appointment_date' => $postData['appointment_date'],
            'appointment_time' => $postData['appointment_time'],
            'comments'         => $postData['comments'],
            'created_at'       => datetimeformat(),
            'created_by'       => $this->session->userdata['LOGGED_IN']['ID']
        );
        
        $id = $this->common_model->insert(MEDICAL_PROFESSIONALS_APPOINTMENT, $data);
        redirect('/' . $this->viewname .'/save_appointment/'.$postData['yp_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : save data appointment
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */
      public function save_appointment($id) {
          $data = array(
          'header' => 'New Crisis Notice Added',
          'detail' => 'You have added a new Medical Appointment. Please check your editing carefully.',
          );
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mac data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mac($ypid) {
      $postData = $this->input->post ();
      //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        //get mac details
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['edit_data'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mac';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    public function insert_mac()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('mac_form_id'=> 1);
       $form_data = $this->common_model->get_records(MAC_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
      $match = array('yp_id'=> $postData['yp_id']);
      $check_data = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS,'', '', '', $match);

        if(!empty($check_data))
        {
             $this->common_model->update(MEDICAL_AUTHORISATIONS_CONSENTS,$data,array('yp_id'=>$postData['yp_id']));
            
        }
        else
        {
            $data['yp_id'] = $postData['yp_id'];
            $this->common_model->insert(MEDICAL_AUTHORISATIONS_CONSENTS, $data);
        }
        
        redirect('/' . $this->viewname .'/save_mac/'.$postData['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mac
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mac($id) {
          //get daily observation data
          $data = array(
          'header' => 'CONSENTS Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : create yp edit page
      @Input 	:
      @Output	:
      @Date   : 13/07/2017
    */
    
    public function create($id) {
        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        
        $data['medicalNumberId'] = $this->common_model->medicalnumber();
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/addinformation';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert Documents
      @Input    :
      @Output   :
      @Date   : 19/07/2017
     */

    public function insert() {
        $postData = $this->input->post();
        
        $data = array(
            'medical_number' => $this->input->post('medical_number'),
            'date_received' => $this->input->post('date_received'),
            'allergies_and_meds_not_to_be_used' => $this->input->post('allergies_and_meds_not_to_be_used'),
        );
        
        if (!empty($postData['mi_id'])) {
            $data['mi_id'] = $postData['mi_id'];
            $data['yp_id'] = $postData['yp_id'];
            $this->common_model->update(MEDICAL_INFORMATION, $data, array('mi_id' => $postData['mi_id']));
        } else {
            $data['yp_id'] = $postData['yp_id'];
            $this->common_model->insert(MEDICAL_INFORMATION, $data);
            $data['mi_id'] = $this->db->insert_id();
        }
        redirect('/' . $this->viewname .'/index/'. $data['yp_id']);
    }

    
     public function editMi($yp_id,$mi_id) {
        //get YP information
         
        $match = "yp_id = " . $yp_id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

         $match = "mi_id = " . $mi_id;
        $fields = array("*");
        $data['edit_record'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        
        $data['ypid'] = $yp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        
        $data['medicalNumberId'] = $this->common_model->medicalnumber();
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/addinformation';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : view mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function view_mc($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('mc_session_data');
        }

        $searchsort_session = $this->session->userdata('mc_session_data');
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
                $sortfield = 'communication_id';
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
        $table = MEDICAL_COMMUNICATION . ' as mc';
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

        $this->session->set_userdata('mc_session_data', $sortsearchpage_data);
        setActiveSession('mc_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //get communication form
        $match = array('mc_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MC_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/mc_ajaxlist', $data);
        } else {
            $data['main_content'] = '/mc_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mc($ypid) {
      $postData = $this->input->post ();
        //get mc form
        $match = array('mc_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MC_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mc';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mc()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('mc_form_id'=> 1);
       $form_data = $this->common_model->get_records(MC_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_COMMUNICATION,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                               if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            /*if(!empty($pp_yp_data[0][$filename]))
                            {
                              $images .=','.$pp_yp_data[0][$filename];
                            }*/
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

      $data['yp_id'] = $postData['yp_id'];
      $insert_id = $this->common_model->insert(MEDICAL_COMMUNICATION, $data);
        
        redirect('/' . $this->viewname .'/save_mc/'.$insert_id);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mc
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mc($id) {
          //get daily observation data
          $data = array(
          'header' => 'CONSENTS Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
           //get mc form
          $match = array('mc_form_id'=> 1);
          $formsdata = $this->common_model->get_records(MC_FORM,'', '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get mc details
          $match = "communication_id = " . $id;
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(MEDICAL_COMMUNICATION, $fields, '', '', $match);

          $data['yp_id'] = $id;
          $data['main_content'] = '/save_mc';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : view medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function medication($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('medication_session_data');
        }

        $searchsort_session = $this->session->userdata('medication_session_data');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICATION . ' as mc';
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

        $this->session->set_userdata('medication_session_data', $sortsearchpage_data);
        setActiveSession('medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/mc_ajaxlist', $data);
        } else {
            $data['main_content'] = '/medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    

    /*
      @Author : Niral Patel
      @Desc   : Insert medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function insert_medication()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('m_form_id'=> 1);
       $form_data = $this->common_model->get_records(M_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(MEDICATION,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                               if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            /*if(!empty($pp_yp_data[0][$filename]))
                            {
                              $images .=','.$pp_yp_data[0][$filename];
                            }*/
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

      $data['yp_id'] = $postData['yp_id'];
      $insert_id = $this->common_model->insert(MEDICATION, $data);
        
        redirect('/' . $this->viewname .'/save_medication/'.$insert_id);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data medication
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
      public function save_medication($id) {
          //get daily observation data
          $data = array(
          'header' => 'CONSENTS Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
           //get medication form
          $match = array('m_form_id'=> 1);
          $formsdata = $this->common_model->get_records(M_FORM,'', '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get medication details
          $match = "medication_id = " . $id;
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(MEDICATION, $fields, '', '', $match);

          $data['yp_id'] = $id;
          $data['main_content'] = '/save_medication';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
     /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function log_administer_medication($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('administer_medication_session_data');
        }

        $searchsort_session = $this->session->userdata('administer_medication_session_data');
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
                $sortfield = 'administer_medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/log_administer_medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ADMINISTER_MEDICATION . ' as mc';
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

        $this->session->set_userdata('administer_medication_session_data', $sortsearchpage_data);
        setActiveSession('administer_medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //get communication form
        $match = array('am_form_id'=> 1);
        $formsdata = $this->common_model->get_records(AM_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/administer_ajaxlist', $data);
        } else {
            $data['main_content'] = '/log_administer_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function administer_medication($ypid) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('administer_medication_session_data');
        }

        $searchsort_session = $this->session->userdata('administer_medication_session_data');
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
                $sortfield = 'administer_medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/administer_medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ADMINISTER_MEDICATION . ' as mc';
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

        $this->session->set_userdata('administer_medication_session_data', $sortsearchpage_data);
        setActiveSession('administer_medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //get communication form
        $match = array('am_form_id'=> 1);
        $formsdata = $this->common_model->get_records(AM_FORM,'', '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //pr($data['form_data']);exit;
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/administer_ajaxlist', $data);
        } else {
            $data['main_content'] = '/administer_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    

    /*
      @Author : Niral Patel
      @Desc   : Insert medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function insert_administer_medication()
    {
        $postData = $this->input->post ();
        unset($postData['submit_ppform']);
        //get pp form
       $match = array('am_form_id'=> 1);
       $form_data = $this->common_model->get_records(AM_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            /*if(!empty($pp_yp_data[0][$filename]))
                            {
                              $images .=','.$pp_yp_data[0][$filename];
                            }*/
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

      $data['yp_id'] = $postData['yp_id'];
      $insert_id = $this->common_model->insert(ADMINISTER_MEDICATION, $data);
        
        redirect('/' . $this->viewname .'/save_administer_medication/'.$insert_id);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data medication
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
      public function save_administer_medication($id) {
          //get daily observation data
          $data = array(
          'header' => 'CONSENTS Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
           //get medication form
          $match = array('am_form_id'=> 1);
          $formsdata = $this->common_model->get_records(AM_FORM,'', '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get medication details
          $match = "administer_medication_id = " . $id;
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(ADMINISTER_MEDICATION, $fields, '', '', $match);
          

          $data['yp_id'] = $id;
          $data['main_content'] = '/save_administer_medication';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }
}
