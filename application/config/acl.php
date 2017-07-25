<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Base Site URL
  |--------------------------------------------------------------------------
  |
  | URL to your CodeIgniter root. Typically this will be your base URL,
  | WITH a trailing slash:
  |
  | http://example.com/
  |
  | WARNING: You MUST set this value!
  |
  | If it is not set, then CodeIgniter will try guess the protocol and path
  | your installation, but due to security concerns the hostname will be set
  | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
  | The auto-detection mechanism exists only for convenience during
  | development and MUST NOT be used in production!
  |
  | If you need to allow multiple domains, remember that this file is still
  | a PHP script and you can easily do that on your own.
  |
 */
/**
 * User Module
 * [controller name][action] = array('respective function name for the Controller')
 */

//Admin
$config['Admin']['add'] = array('do_login', 'forgot_password', 'reset_password', 'add_new_password', 'check_user');
$config['Admin']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['Admin']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['Admin']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


//Admin/Dashboard
$config['Dashboard']['add'] = array('staffNotices','insertStaffNotices','download','schollHandover','insertSchollHandover','schollHandoverUploadFile');
$config['Dashboard']['delete'] = array('');
$config['Dashboard']['edit'] = array('');
$config['Dashboard']['view'] = array('index','staffNotices','insertStaffNotices','download','schollHandover','insertSchollHandover','schollHandoverUploadFile','HandoverFiledownload');

//User
$config['User']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['User']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['User']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['User']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


//OpsManager
$config['Director']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['Director']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['Director']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['Director']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//OpsManager
$config['OpsManager']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['OpsManager']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['OpsManager']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['OpsManager']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//HeadTeacher
$config['HeadTeacher']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['HeadTeacher']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['HeadTeacher']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['HeadTeacher']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//DeputyHead
$config['DeputyHead']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['DeputyHead']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['DeputyHead']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['DeputyHead']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//HeadTeacher
$config['Teacher']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['Teacher']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['Teacher']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['Teacher']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//HeadTeacher
$config['SafeguardingOfficer']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['SafeguardingOfficer']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['SafeguardingOfficer']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['SafeguardingOfficer']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Registered Manager
$config['RegisteredManager']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['RegisteredManager']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['RegisteredManager']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['RegisteredManager']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Team Leader
$config['TeamLeader']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['TeamLeader']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['TeamLeader']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['TeamLeader']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Shift Leader
$config['ShiftLeader']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['ShiftLeader']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['ShiftLeader']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['ShiftLeader']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Key Worker
$config['KeyWorker']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['KeyWorker']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['KeyWorker']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['KeyWorker']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Case Manager
$config['CaseManager']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['CaseManager']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['CaseManager']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['CaseManager']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Ncw Manager
$config['Ncw']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['Ncw']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['Ncw']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['Ncw']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');

//Ta Manager
$config['TaManager']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['TaManager']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['TaManager']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['TaManager']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


//Lsa Manager
$config['LsaManager']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount');
$config['LsaManager']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['LsaManager']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['LsaManager']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


$config['Home']['view'] = array('index', 'changeview', 'grantview', 'get_home_header', 'get_home_activity');
$config['Errors']['view'] = array('index');

// Rolemaster Module
$config['Rolemaster']['add'] = array('insertdata', 'add', 'addPermission', 'insertPerms', 'assignPermission', 'addModule', 'insertAssginPerms', 'insertModule', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['delete'] = array('deletedata', 'deletePerms', 'deleteAssignperms', 'deleteModuleData', 'permissionTab','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['edit'] = array('edit', 'updatedata', 'editPerms', 'updatePerms', 'editPermission', 'editModule', 'updateModule', 'insertAssginPerms', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['view'] = array('index', 'role_list', 'view_perms_to_role_list', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab');

//Module Master
$config['ModuleMaster']['add'] = array('add','insertModule', 'formValidation');
$config['ModuleMaster']['edit'] = array('edit', 'updateModule' ,'formValidation');
$config['ModuleMaster']['delete'] = array('deleteModuleData');
$config['ModuleMaster']['view'] = array('index');


//Form builder
$config['FormBuilder']['add'] = array('add','insertModule', 'formValidation');
$config['FormBuilder']['edit'] = array('edit', 'updateModule' ,'formValidation');
$config['FormBuilder']['delete'] = array('deleteModuleData');
$config['FormBuilder']['view'] = array('index');


//Young Person
$config['YoungPerson']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount', 'personal_info','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file');
$config['YoungPerson']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['YoungPerson']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail','personal_info','updatePersonalInfo','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file');
$config['YoungPerson']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail', 'personal_info','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file');


// Reports
$config['Reports']['add'] = array('showReportList','generateExcelFile','downloadExcelFile','generateExcelFileUrl','filterExcelData');
$config['Reports']['edit'] = array('showReportList','generateExcelFile','downloadExcelFile','generateExcelFileUrl','filterExcelData');
$config['Reports']['view'] = array('index','PP','IBP','RA','DOC','KS','DOCS','MODC','COMS');
$config['Reports']['delete'] = array();