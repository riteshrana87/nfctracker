<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PlacementPlan extends CI_Controller {

    function __construct() {

        parent::__construct();
        /*if (checkPermission('PlacementPlan', 'view') == false) {
            redirect('/Dashboard');
        }*/
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : PlacementPlan Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 7/07/2017
     */

    public function index($id) {
       
       //get pp form
       $match = array('pp_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
       if(!empty($pp_forms))
       {
            $data['pp_form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
       }

        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get pp yp data
        $match = array('yp_id'=> $id);
        $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);

        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/placementplan';
        $this->parser->parse('layouts/DefaultTemplate', $data);
       
    }

    /*
      @Author : Niral Patel
      @Desc   : create yp edit page
      @Input 	:
      @Output	:
      @Date   : 07/07/2017
     */

    public function edit($id) {
        //get pp form
        $match = array('pp_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
        if(!empty($pp_forms))
        {
            $data['pp_form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get pp yp data
        $match = array('yp_id'=> $id);
        $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
        $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');

        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/edit';
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
       $match = array('pp_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('pp_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('pp_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('pp_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('pp_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                        //create dir and give permission
                        if (!is_dir($this->config->item('pp_base_url'))) {
                                mkdir($this->config->item('pp_base_url'), 0777, TRUE);
                        }

                        if (!is_dir($this->config->item('pp_base_big_url'))) {                                
                            mkdir($this->config->item('pp_base_big_url'), 0777, TRUE);
                        }
                        
                        
                        if (!is_dir($this->config->item('pp_base_big_url') . '/' . $postData['yp_id'])) {
                            mkdir($this->config->item('pp_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                        }
                       
                        $file_view = $this->config->item ('pp_img_url').$postData['yp_id'];
                        //upload big image
                        $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                        //upload small image
                        $insertImagesData = array();
                        if(!empty($upload_data))
                        {
                          foreach ($upload_data as $imageFiles) {
                              if (!is_dir($this->config->item('pp_base_small_url'))) {                                        
                                    mkdir($this->config->item('pp_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('pp_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('pp_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                              $a = do_resize ($this->config->item ('pp_img_url') . $postData['yp_id'], $this->config->item ('pp_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
       
        if(!empty($postData['placement_plan_id']))
        {
             $data['placement_plan_id'] = $postData['placement_plan_id'];
             $data['yp_id'] = $postData['yp_id'];

             $this->common_model->update(PLACEMENT_PLAN,$data,array('placement_plan_id'=>$postData['placement_plan_id']));
        }
        else
        {
             $data['yp_id'] = $postData['yp_id'];
             $this->common_model->insert(PLACEMENT_PLAN, $data);
        }
        
        
        
        redirect('/' . $this->viewname .'/save_pp/'. $data['yp_id']);
   }
    /*
      @Author : Niral Patel
      @Desc   : Save pp form
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
   public function save_pp($id)
   {
        $data['id'] = $id;
        $data['main_content'] = '/save_pp';
        $this->parser->parse('layouts/DefaultTemplate', $data);
   }
    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 10/07/2017
     */
    public function createarchive($id)
    {
       //get pp form
       $match = array('pp_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
      
       //get pp yp data
       $match = array('yp_id'=> $id);
       $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);

       if(!empty($pp_forms))
       {
            $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
            $data = array();
            $i=0;
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] != 'button')
                    {
                        if($row['type'] == 'checkbox-group')
                        {$pp_form_data[$i]['value'] = implode(',',$pp_yp_data[0][$row['name']]);}
                        else{$pp_form_data[$i]['value'] = strip_slashes($pp_yp_data[0][$row['name']]);}
                    }
                }
                $i++;
            }
            $archive = array(
                'yp_id' => $id,
                'form_json_data' =>json_encode($pp_form_data, TRUE),
                'created_by'=>$this->session->userdata('LOGGED_IN')['ID'],
                'created_date'=>datetimeformat()
            );
            $this->common_model->insert(PLACEMENT_PLAN_ARCHIVE, $archive);
            redirect('/' . $this->viewname .'/archive/'. $id);
       }
       else
       {
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
            redirect('/' . $this->viewname .'/index/'. $id);
       }
    }                             
}
