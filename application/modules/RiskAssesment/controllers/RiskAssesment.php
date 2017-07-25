<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RiskAssesment extends CI_Controller {

    function __construct() {

        parent::__construct();
        /*if (checkPermission('RiskAssesment', 'view') == false) {
            redirect('/Dashboard');
        }*/
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : RiskAssesment Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 12/07/2017
     */

    public function index($id) {
       
       //get pp form
       $match = array('ra_form_id'=> 1);
       $formsdata = $this->common_model->get_records(RA_FORM,'', '', '', $match);
       if(!empty($formsdata))
       {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
       }
        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get ibp yp data
        $match = array('yp_id'=> $id);
        $data['edit_data'] = $this->common_model->get_records(RISK_ASSESSMENT,'', '', '', $match);

        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/riskassesment/riskassesment.js');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/view';
        $this->parser->parse('layouts/DefaultTemplate', $data);
       
    }

    /*
      @Author : Niral Patel
      @Desc   : create RiskAssesment edit page
      @Input 	:
      @Output	:
      @Date   : 12/07/2017
     */

    public function edit($id) {
       //get pp form
       $match = array('ra_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(RA_FORM,'', '', '', $match);
       if(!empty($pp_forms))
       {
            $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
       }
       //get YP information
       $match = "yp_id = " . $id;
       $fields = array("*");
       $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

       //get pp yp data
       $match = array('yp_id'=> $id);
       $data['edit_data'] = $this->common_model->get_records(RISK_ASSESSMENT,'', '', '', $match);
       //pr($data['pp_yp_data']);exit;
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/riskassesment/riskassesment.js');
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
      @Date   : 12/07/2017
     */
   public function insert()
   {
       $postData = $this->input->post ();
       unset($postData['submit_ppform']);
       //get pp form
       $match = array('ra_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(RA_FORM,'', '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(RISK_ASSESSMENT,array($row['name']), '', '', $match);
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

                                if (file_exists ($this->config->item ('ra_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('ra_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('ra_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('ra_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                        if (!is_dir($this->config->item('ra_base_url'))) {
                                mkdir($this->config->item('ra_base_url'), 0777, TRUE);
                        }

                        if (!is_dir($this->config->item('ra_base_big_url'))) {                                
                            mkdir($this->config->item('ra_base_big_url'), 0777, TRUE);
                        }
                        
                        
                        if (!is_dir($this->config->item('ra_base_big_url') . '/' . $postData['yp_id'])) {
                            mkdir($this->config->item('ra_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                        }
                        $file_view = $this->config->item ('ra_img_url').$postData['yp_id'];
                        //upload big image
                        $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                        //upload small image
                        $insertImagesData = array();
                        if(!empty($upload_data))
                        {
                          foreach ($upload_data as $imageFiles) {
                              if (!is_dir($this->config->item('ra_base_small_url'))) {                                        
                                    mkdir($this->config->item('ra_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('ra_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('ra_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                              $a = do_resize ($this->config->item ('ra_img_url') . $postData['yp_id'], $this->config->item ('ra_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
       
        if(!empty($postData['risk_assesment_id']))
        {
             $data['risk_assesment_id'] = $postData['risk_assesment_id'];
             $data['yp_id'] = $postData['yp_id'];

             $this->common_model->update(RISK_ASSESSMENT,$data,array('risk_assesment_id'=>$postData['risk_assesment_id']));
             
        }
        else
        {
             $data['yp_id'] = $postData['yp_id'];
             $this->common_model->insert(RISK_ASSESSMENT, $data);
             
        }
        
        
        
        redirect('/' . $this->viewname .'/save_ra/'. $data['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : Save pp form
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
   public function save_ra($id)
   {
        $data['id'] = $id;
        $data['main_content'] = '/save_ra';
        $this->parser->parse('layouts/DefaultTemplate', $data);
   }
   public function archive($id)
   {
            //get archiv data
            $match = array('pa.yp_id'=> $id);
            $fields =array('pa.*,CONCAT(l.firstname," ", l.lastname) as create_name');
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= pa.created_by');
            $data['archivedata'] = $this->common_model->get_records(RISK_ASSESSMENT_ARCHIVE.' as pa',$fields, $join_tables, 'left', $match);

            $data['main_content'] = '/archive';
            $this->parser->parse('layouts/DefaultTemplate', $data);

            $searchtext = '';
            $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('yp_data');
            }

            $searchsort_session = $this->session->userdata('yp_data');
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
            $config['base_url'] = base_url() . $this->viewname . '/index';

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 3;
                $uri_segment = $this->uri->segment(3);
            }
            //Query

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = YP_DETAILS . ' as yp';
            $where = array("yp.is_deleted" => "'0'", "yp.created_by" => "'$login_user_id'");
            $fields = array("yp.yp_id, CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,yp.yp_lname,CONCAT(`firstname`,' ', `lastname`) as create_name, l.firstname, l.lastname,yp.created_date,yp.yp_initials");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= yp.created_by');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((CONCAT(`yp_fname`, \' \', `yp_lname`) LIKE "%' . $searchtext . '%" OR yp.yp_fname LIKE "%' . $searchtext . '%" OR yp.yp_lname LIKE "%' . $searchtext . '%") OR (CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%"  OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%")AND yp.is_deleted = "0")';
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('yp_data', $sortsearchpage_data);
            setActiveSession('yp_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/archive_ajax', $data);
            } else {
                $data['main_content'] = '/youngperson';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
   }
    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
    public function createarchive($id)
    {
       //get pp form
       $match = array('ra_form_id'=> 1);
       $pp_forms = $this->common_model->get_records(RA_FORM,'', '', '', $match);
       
       //get pp yp data
       $match = array('yp_id'=> $id);
       $pp_yp_data = $this->common_model->get_records(RISK_ASSESSMENT,'', '', '', $match);

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
            $this->common_model->insert(RISK_ASSESSMENT_ARCHIVE, $archive);
            redirect('/' . $this->viewname .'/archive/'. $id);
       }
       else
       {
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
            redirect('/' . $this->viewname .'/index/'. $id);
       }
    }                             
}
