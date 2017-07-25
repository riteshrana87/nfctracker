<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
  Author : Ritesh Rana
  Desc   : Define Constants Value
  Date   : 23/02/2017
 */
define('SAMPLE_TABLE', 'sampletable');
define('ERROR_DANGER_DIV', '<div class="alert alert-danger text-center">');
define('ERROR_SUCCESS_DIV', '<div class="alert alert-success text-center">');
define('ERROR_START_DIV', '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
/* Added  By Sanket */
define('ERROR_START_DIV_NEW', '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
define('ERROR_END_DIV', '</div>');
define('SITE_NAME', 'NFC Tracker');
/*
  Author : Ritesh Rana
  Desc   : Define Constants Value
  Date   : 23/02/2017
 */
define('LOG_MASTER', 'log_master');
define('ROLE_MASTER', 'role_master');

define('AAUTH_PERMS', 'aauth_perms');
define('AAUTH_PERMS_TO_ROLE', 'aauth_perm_to_group');
define('MODULE_MASTER', 'module_master');
define('PRODUCT_TAX_MASTER', 'product_tax_master');
define('CONFIG', 'config');
define('LOGIN', 'login');
define('LANGUAGE_MASTER', 'language_master');
define('CI_SESSION', 'ci_sessions');
define('COUNTRIES', 'countries');
define('RECORD_PER_PAGE', '10');
define('SALUTIONS_LIST', 'salutions_list');
define('PROFILE_PIC_UPLOAD_PATH', 'uploads/profile_photo');

define('PROFILE_PIC_HEIGHT', '36');
define('PROFILE_PIC_WIDTH', '36');
define('EMAIL_TEMPLATE_MASTER', 'email_template_master');


define('NO_OF_RECORDS_PER_PAGE', '10');

define('ALLOWED_ATTACHMENT_TYPE', "['jpg','jpeg','png','doc','docx','pdf','xls','xlsx']");
define('ALLOWED_IMAGE_ATTACHMENT_TYPE', "['jpg','jpeg','png']");
define('ALLOWED_MAX_FILE_SIZE', '8');

define('ADMIN_SITE', 'Admin');
define('CUSTOMER_TABLE', 'customers');
define('RA_FORM', 'ra_form');
define('YP_DETAILS', 'yp_details');
define('PLACING_AUTHORITY', 'placing_authority');
define('SOCIAL_WORKER_DETAILS', 'social_worker_details');
define('OVERVIEW_OF_YOUNG_PERSON', 'overview_of_young_person');
 
define('STAFF_NOTICES', 'staff_notices');
define('STAFF_NOTICES_UPLOADS', 'staff_notices_uploads');
define('SCHOOL_HANDOVER', 'school_handover');
define('SCHOOL_HANDOVER_FILE', 'school_handover_file');
define('PP_FORM', 'pp_form');
define('IBP_FORM', 'ibp_form');

define('PLACEMENT_PLAN', 'placement_plan');
define('PLACEMENT_PLAN_ARCHIVE', 'placement_plan_archive');
define('INDIVIDUAL_BEHAVIOUR_PLAN', 'individual_behaviour_plan');
define('IBP_ARCHIVE', 'ibp_archive');
define('RISK_ASSESSMENT', 'risk_assessment');
define('RISK_ASSESSMENT_ARCHIVE', 'risk_assessment_archive');
define('DAILY_OBSERVATIONS', 'daily_observations');
define('DO_STAFF_TRANSECTION', 'do_staff_transection');
define('DO_FOODCONSUMED', 'do_foodconsumed');
define('COMMUNICATION_LOG', 'communication_log');
define('MEDICAL_COMMUNICATION', 'medical_communication');
define('MEDICAL_AUTHORISATIONS_CONSENTS', 'medical_authorisations_consents');
define('MEDICAL_PROFESSIONALS', 'medical_professionals');
define('OTHER_MEDICAL_INFORMATION', 'other_medical_information');
define('MEDICAL_INOCULATIONS', 'medical_inoculations');
define('MEDICAL_PROFESSIONALS_APPOINTMENT', 'medical_professionals_appointment');
define('MEDICATION', 'medication');
define('ADMINISTER_MEDICATION', 'administer_medication');

define('DO_FORM', 'do_form');
define('KS_FORM', 'ks_form');
define('DOCS_FORM', 'docs_form');
define('MAC_FORM', 'mac_form');
define('MP_FORM', 'mp_form');
define('OMI_FORM', 'omi_form');
define('MI_FORM', 'mi_form');
define('MC_FORM', 'mc_form');
define('M_FORM', 'm_form');
define('AM_FORM', 'am_form');
define('COMS_FORM', 'coms_form');
define('OVERVIEW_FORM', 'overview_form');
define('FOOD_FORM', 'food_form');
define('KEY_SESSION', 'key_session');
define('YP_DOCUMENTS', 'yp_documents');
define('MEDICAL_INFORMATION', 'medical_information');