<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    function __construct() {

        parent::__construct();
        /* if (checkPermission('KeySession', 'view') == false) {
          redirect('/Dashboard');
          } */
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Documents Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 14/07/2017
     */

    public function index($id) {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('doca_data');
        }

        $searchsort_session = $this->session->userdata('doca_data');
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
                $sortfield = 'docs_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }

        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = YP_DOCUMENTS . ' as docs';
        $where = array("docs.yp_id" => $id, "docs.created_by" => $login_user_id);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,docs.*");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = docs.created_by');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR docs.created_date LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

            $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        //echo $this->db->last_query();exit;
//pr($data['edit_data']);exit;
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


        $match = array('yp_id' => $id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        $data['ypid'] = $id;
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $data['form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }

        $this->session->set_userdata('doca_data', $sortsearchpage_data);
        setActiveSession('doca_data'); // set current Session active
        $data['header'] = array('menu_module' => 'docs');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/documents';
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
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $data['docs_form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $match = "yp_id = " . $id;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
        $data['footerJs'][1] = base_url('uploads/custom/js/documents/documents.js');

        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function insert() {
        $postData = $this->input->post();
        unset($postData['submit_docsform']);
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $docs_form_data = json_decode($docs_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($docs_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('yp_id' => $postData['yp_id']);
                        $docs_yp_data = $this->common_model->get_records(YP_DOCUMENTS, array($row['name']), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $pp_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $ks_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {

                                    
                                    if (file_exists($this->config->item('docs_base_big_url') . '/' . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('docs_base_big_url') . '/' . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('docs_base_small_url') . '/' . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('docs_base_small_url') . '/' . $postData['yp_id'] . '/' . $img);
                                    }
                                    
                                    /*if (file_exists($this->config->item('docs_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('docs_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('docs_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('docs_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }*/
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {

                            if (!is_dir($this->config->item('docs_base_url'))) {
                                mkdir($this->config->item('docs_base_url'), 0777, TRUE);
                            }

                            if (!is_dir($this->config->item('docs_base_big_url'))) {                                
                                mkdir($this->config->item('docs_base_big_url'), 0777, TRUE);
                            }
                            
                            
                            if (!is_dir($this->config->item('docs_base_big_url') . '/' . $postData['yp_id'])) {
                                mkdir($this->config->item('docs_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                            }
                            
                            $file_view = $this->config->item('docs_img_url') . '/' . $postData['yp_id'];

                             //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);


                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                
                               // exit;
                                foreach ($upload_data as $imageFiles) {

                                    if (!is_dir($this->config->item('docs_base_small_url'))) {                                        
                                        mkdir($this->config->item('docs_base_small_url'), 0777, TRUE);
                                    }
                                    
                                    if (!is_dir($this->config->item('docs_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                        mkdir($this->config->item('docs_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                    }

                                   $bigDir = $this->config->item('docs_base_big_url') . '/' . $postData['yp_id'];
                                    $smallDir = $this->config->item('docs_base_small_url') . '/' . $postData['yp_id'];
                                    
                                    $a = do_resize($bigDir, $smallDir, $imageFiles['file_name']);
                                    
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                /*if (!empty($docs_yp_data[0][$filename])) {
                                    $images .= ',' . $docs_yp_data[0][$filename];
                                }*/
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        } else {
                            $data[$row['name']] = !empty($docs_yp_data[0][$filename]) ? $docs_yp_data[0][$filename] : '';
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } else {
                                $data[$row['name']] = strip_slashes($postData[$row['name']]);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($postData['docs_id'])) {
            $data['docs_id'] = $postData['docs_id'];
            $data['yp_id'] = $postData['yp_id'];
            $main_user_data = $this->session->userdata('LOGGED_IN');
            $data['created_by'] = $main_user_data['ID'];

            $this->common_model->update(YP_DOCUMENTS, $data, array('docs_id' => $postData['docs_id']));
        } else {
            $data['yp_id'] = $postData['yp_id'];
            $main_user_data = $this->session->userdata('LOGGED_IN');
            $data['created_by'] = $main_user_data['ID'];
            $data['created_date'] = datetimeformat();
            $this->common_model->insert(YP_DOCUMENTS, $data);
            $data['docs_id'] = $this->db->insert_id();
        }
        redirect('/' . $this->viewname . '/save_ks/' . $data['docs_id'] . '/' . $data['yp_id']);
    }

    public function view($id) {

        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $data['form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }
        //get YP docs
        $match = "docs_id = " . $id;
        $fields = array("*");
        $data['edit_data'] = $this->common_model->get_records(YP_DOCUMENTS, $fields, '', '', $match);
        //get YP information
        $match = "yp_id = " . $data['edit_data'][0]['yp_id'];
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);


        $data['ypid'] = $data['edit_data'][0]['yp_id'];
        $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/view';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Save Documents form
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function save_ks($docs_id, $yp_id) {
        $data['yp_id'] = $yp_id;
        $data['docs_id'] = $docs_id;
        $data['main_content'] = '/save_docs';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Download Documents functionality
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    function download($docs_id, $yp_id) {
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $form_data = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }
        $params['fields'] = ['*'];
        $params['table'] = YP_DOCUMENTS . ' as docs';
        $params['match_and'] = 'docs.docs_id=' . $docs_id . '';
        $cost_files = $this->common_model->get_records_array($params);
        if (count($cost_files) > 0) {

            $pth = file_get_contents($this->config->item('docs_img_base_url') . $yp_id . '/' . $cost_files[0][$form_data[0]['name']]);
            $this->load->helper('download');
            force_download($cost_files[0][$form_data[0]['name']], $pth);
        }
        redirect($this->module);
    }

}
