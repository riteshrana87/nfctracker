<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library('Ajax_pagination');
        $this->perPage = 10;
        $this->load->library('excel');
    }

    public function index() {

        $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Get  Young Person Name List
      @Input  :
      @Output :
      @Date   : 19th July 2017
     */

    private function getYpList() {

        $tableName = YP_DETAILS . ' as yp';
        $fields = array('yp.yp_id, CONCAT(yp.yp_fname," ",yp.yp_lname) as ypName');

        $whereCond = array('yp.status' => 'active', 'yp.is_deleted' => '0');

        $data['information'] = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', '', '');

        return $data['information'];
    }

    /**
     * @Author : Maitrak Modi    
     * @Desc: get Header and field Listing
     * @return string
     * @Date   : 24th July 2017
     */
    private function getHeaderFieldName($tableName = '', $match_Id = '', $type = '') {
        $returnArray = array();
        if (!empty($tableName) && !empty($match_Id) && !empty($type)) {

            $match = array($match_Id => 1);
            $pp_forms = $this->common_model->get_records($tableName, '', '', '', $match);

            if (!empty($pp_forms)) {

                $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
                //pr($data['pp_form_data']); exit;
                $passRow = '';

                if (!empty($data['form_data'])) {

                    foreach ($data['form_data'] as $ppHeader) {

                        if ($ppHeader['type'] == 'header') {
                            continue;
                        }

                        $passHeaderArray[] = $ppHeader['label'];
                        $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                    }
                }
                $passHeaderArray[] = 'First Name';
                $passHeaderArray[] = 'last Name';
                $passHeaderArray[] = 'Age';
                $passHeaderArray[] = 'Date of Birth';
            }

            $returnArray = array(
                'headerName' => $passHeaderArray,
                'passField' => $passRow
            );
        }
        return $returnArray;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Show Report Listing based on the filter
      @Input  :
      @Output :
      @Date   : 19th July 2017
     */

    public function showReportList() {

        $reportType = $this->input->post('reportType');
        $ypId = $this->input->post('yp_name');
        $startDate = (!empty($this->input->post('admin_report_start_date'))) ? date('Y-m-d', strtotime($this->input->post('admin_report_start_date'))) : '';
        $endDate = (!empty($this->input->post('admin_report_end_date'))) ? date('Y-m-d', strtotime($this->input->post('admin_report_end_date'))) : '';

        if (!empty($reportType)) {

            $config['per_page'] = NO_OF_RECORDS_PER_PAGE;
            $data['perpage'] = NO_OF_RECORDS_PER_PAGE;

            $config['first_link'] = 'First';
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);

            $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/showReportList/';

            if ($reportType == 'PP') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                //pr($data['passHeaderArray']);
                //exit;
                $data['information'] = $this->getPPData($filterData, $totalRows = false); // get PP List Data
                // pr($data['information']);
                $config['total_rows'] = $this->getPPData($filterData, $totalRows = true); // get PP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;

                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'IBP') {
               
                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                //pr($data['passHeaderArray']);
                //exit;
                $data['information'] = $this->getIBPData($filterData, $totalRows = false); // get PP List Data
                // pr($data['information']);
                $config['total_rows'] = $this->getIBPData($filterData, $totalRows = true); // get PP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;

                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'RA') {
                
                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                //pr($data['passHeaderArray']);
                //exit;
                $data['information'] = $this->getRAData($filterData, $totalRows = false); // get PP List Data
                // pr($data['information']);
                $config['total_rows'] = $this->getRAData($filterData, $totalRows = true); // get PP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;

                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'KS') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                //pr($data['passHeaderArray']);
                //exit;
                $data['information'] = $this->getKSData($filterData, $totalRows = false); // get PP List Data
                // pr($data['information']);
                $config['total_rows'] = $this->getKSData($filterData, $totalRows = true); // get PP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;

                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }
        } else {
            echo "Please Select Reoprt Type";
        }
    }

    /*     * ********************************************** Start - PP ********************************************************* */
    /*
      @Author : Maitrak Modi
      @Desc   : Reports for PP
      @Input  :
      @Output :
      @Date   : 19th July 2017
     */

    public function PP() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/PP';

        $data['reportType'] = 'PP';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'pp');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ppReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Maitrak Modi
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th July 2017
     */
    private function getPPData($filterData, $totalRows = false) {

        $headerAndFieldData = $this->getHeaderFieldName(PP_FORM, 'pp_form_id', 'pp'); // Header and PassField Data
        //pr($headerAndFieldData); exit;

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = PLACEMENT_PLAN . ' as pp';

            $fieldString = $headerAndFieldData['passField'];

            $fields = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.age, ypd.date_of_birth');

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'pp.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND pp.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], '', '', '', '');
                // echo $this->db->last_query(); exit;
                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', '', '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    /*     * ********************************************** End - PP ********************************************************* */


    /*     * ********************************************** Start - IBP ********************************************************* */
    /*
      @Author : Maitrak Modi
      @Desc   : Reports for IBP
      @Input  :
      @Output :
      @Date   : 24th July 2017
     */

    public function IBP() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/IBP';

        $data['reportType'] = 'IBP';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ibp');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ppReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Maitrak Modi
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th July 2017
     */
    private function getIBPData($filterData, $totalRows = false) {

        $headerAndFieldData = $this->getHeaderFieldName(IBP_FORM, 'ibp_form_id', 'ibp'); // Header and PassField Data
        //pr($headerAndFieldData); exit;

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = INDIVIDUAL_BEHAVIOUR_PLAN . ' as ibp';

            $fieldString = $headerAndFieldData['passField'];

            $fields = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.age, ypd.date_of_birth');

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ibp.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ibp.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], '', '', '', '');
                // echo $this->db->last_query(); exit;
                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', '', '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    /*     * ********************************************** End - IBP ********************************************************* */

    /*     * ********************************************** Start - RA ********************************************************* */
    /*
      @Author : Maitrak Modi
      @Desc   : Reports for RA
      @Input  :
      @Output :
      @Date   : 24th July 2017
     */

    public function RA() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/RA';

        $data['reportType'] = 'RA';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ra');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ppReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Maitrak Modi
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th July 2017
     */
    private function getRAData($filterData, $totalRows = false) {

        $headerAndFieldData = $this->getHeaderFieldName(RA_FORM, 'ra_form_id', 'ra'); // Header and PassField Data
        //pr($headerAndFieldData); exit;

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = RISK_ASSESSMENT . ' as ra';

            $fieldString = $headerAndFieldData['passField'];

            $fields = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.age, ypd.date_of_birth');

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ra.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ra.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], '', '', '', '');
                // echo $this->db->last_query(); exit;
                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', '', '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    /*     * ********************************************** End - RA ********************************************************* */

    /*     * ********************************************** Start - RA ********************************************************* */
    /*
      @Author : Maitrak Modi
      @Desc   : Reports for RA
      @Input  :
      @Output :
      @Date   : 24th July 2017
     */

    public function KS() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/KS';

        $data['reportType'] = 'KS';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ks');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ppReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Maitrak Modi
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th July 2017
     */
    private function getKSData($filterData, $totalRows = false) {

        $headerAndFieldData = $this->getHeaderFieldName(KS_FORM, 'ks_form_id', 'ks'); // Header and PassField Data
        //pr($headerAndFieldData); exit;

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = KEY_SESSION . ' as ks';

            $fieldString = $headerAndFieldData['passField'];

            $fields = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.age, ypd.date_of_birth');

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ks.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ks.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], '', '', '', '');
                // echo $this->db->last_query(); exit;
                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', '', '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    /*     * ********************************************** End - KS ********************************************************* */

    /*
     * @Author : Maitrak Modi
     * @Desc   : generate query string for download file
     * @Input 	:
     * @Output	:
     * @Date   : 21st July 2017
     */

    public function generateExcelFileUrl() {

        $reportType = $this->input->post('reportType');
        $ypId = $this->input->post('yp_name');
        $startDate = (!empty($this->input->post('admin_report_start_date'))) ? date('Y-m-d', strtotime($this->input->post('admin_report_start_date'))) : '';
        $endDate = (!empty($this->input->post('admin_report_end_date'))) ? date('Y-m-d', strtotime($this->input->post('admin_report_end_date'))) : '';

        $filterData = array(
            'yp_id' => $ypId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'reportType' => $reportType,
        );

        $queryString = http_build_query($filterData);

        echo base_url('Admin/Reports/filterExcelData') . '?' . $queryString;
        exit;
    }

    /*
     * @Author : Maitrak Modi
     * @Desc   : Based on query string filter data
     * @Input 	:
     * @Output	:
     * @Date   : 21st July 2017
     */

    public function filterExcelData() {

        $reportType = $this->input->get('reportType');
        $ypId = $this->input->get('yp_id');
        $startDate = (!empty($this->input->get('startDate'))) ? date('Y-m-d', strtotime($this->input->get('startDate'))) : '';
        $endDate = (!empty($this->input->get('endDate'))) ? date('Y-m-d', strtotime($this->input->get('endDate'))) : '';

        if (!empty($reportType)) {

            $filterFinalData = array(
                'yp_id' => $ypId,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'reportType' => $reportType,
                'uri_segment' => '',
                'perPage' => ''
            );

            $this->generateExcelFile($filterFinalData); //Call Function for generate excel file
        } else {
            echo "Invalid URL";
            exit;
        }
    }

    /*
     * @Author : Maitrak Modi
     * @Desc   : Generate Report based on the data
     * @Input 	:
     * @Output	:
     * @Date   : 21st July 2017
     */

    protected function generateExcelFile($filterFinalData) {

        $reportType = $filterFinalData['reportType'];

        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);

        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($reportType);

        if ($reportType == 'PP') {

            $recordData = $this->getPPData($filterFinalData, $totalRows = false); // get PP List Data
            //  pr($recordData);
            //  exit;
        }

        if ($reportType == 'IBP') {

            $recordData = $this->getIBPData($filterFinalData, $totalRows = false); // get PP List Data
            //  pr($recordData);
            //  exit;
        }

        if ($reportType == 'RA') {

            $recordData = $this->getRAData($filterFinalData, $totalRows = false); // get PP List Data
            //  pr($recordData);
            //  exit;
        }

        if ($reportType == 'KS') {

            $recordData = $this->getKSData($filterFinalData, $totalRows = false); // get PP List Data
            //  pr($recordData);
            //  exit;
        }
        $exceldataHeader = "";
        $exceldataValue = "";
        $headerCount = 1;

        foreach ($recordData['informationData'] as $rowValue) {

            if ($headerCount === 1) {
                $exceldataHeader[] = $recordData['headerData']; //array_keys($rowValue); // Set Header of the generated Excel File
                //continue;
            }
            $exceldataValue[] = $rowValue; // Set values
            $headerCount++;
        }

        // pr($exceldataHeader);
        //pr($exceldataValue);
        //exit;
        //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldataHeader, Null, 'A1'); // Set Header Data
        $this->excel->getActiveSheet()->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data

        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);
        $fileName = $reportType . date('Y-m-d H:i:s') . '.xls'; // Generate file name

        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file function call
    }

    /**
     * @Autor Maitrak Modi
     * @Desc downloadExcelfile
     * @param type $excel     
     * @return type
     * @Date 6th June 2017
     */
    Protected function downloadExcelFile($objExcel, $fileName) {

        $this->excel = $objExcel;

        //$filename = 'PHPExcelDemo.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;
                        filename = "' . $fileName . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        ob_end_clean();
        ob_start();

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

        exit;
    }

}
