<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */


$route['default_controller'] = "site";
$route['404_override'] = '';

/**
 * general routings
 */
$route['edit-departments/(:num)'] = "department/edit/$1";
$route['departments'] = "department/index";
$route['new-department'] = "department/add";
$route['add-group'] = "users/addgroup";
/**
 * user module routing
 */
$route['users'] = "users/userlisting";
$route['login'] = "users/login";
$route['groups'] = "users/groups";
$route['logout'] = "users/logout";
$route['add-new-user'] = "users/adduser";
$route['edit-user-account/(:num)'] = "users/edituser/$1";
$route['group-permission/(:num)'] = "users/grouppermission/$1";
$route['user-profile/(:num)'] = "users/editprofile/$1";
$route['forgotpassword'] = "users/forgotpassword";

/**
 * application routing 
 */

$route['add-new-app/(:num)'] = "app/add/$1";
$route['application-setting/(:num)'] = "app/newappsettings/$1";
$route['application-setting/(:num)/(:any)'] = "app/newappsettings/$1/$2";
$route['apps'] = "app/index";
$route[''] = "site/index";
$route['users-view'] = "app/appusersview";
$route['applicatioin-users'] = "app/appusers";
$route['applicatioin-users-import'] = "app/appusersimportcsv";
$route['assign-applicatioin-users/(:num)'] = "app/appassignusers/$1";
$route['app-landing-page/(:any)'] = "app/appbuilder/$1";
$route['app-comments/(:any)'] = "app/appcomments/$1";

/**
 * form routings
 */
$route['application-results/(:any)'] = "form/results/$1";
$route['application-subtable/(:any)'] = "form/show_subtable/$1";
$route['application-map/(:any)'] = "form/mapview/$1";
$route['single-record-map/(:any)'] = "form/mapview_single/$1";
$route['export-result/(:num)'] = "form/exportresults/$1";
$route['guest'] = "form/appbuilder/1";
$route['app-form/(:any)'] = "form/update/$1";
$route['form-move-view/(:any)'] = "form/movetoview/$1";
$route['NoApplication'] = "app/redirectFlashOnly";

/**
 * Site for landing page
 */
$route['OurTeam'] = 'site/team';
$route['About'] = 'site/about';
$route['Contact'] = 'site/contact';
$route['HowToUse'] = 'site/howtouse';


/**
 * for api maker routings
 */
$route['apimaker'] = "apimaker/index";
$route['apiappurl/(:num)'] = "apimaker/apiappurl/$1";
$route['add-new-api'] = "apimaker/add";
$route['createapiurl/(:num)'] = "apimaker/createurl/$1";
$route['edit-new-api/(:num)'] = "apimaker/edit/$1";


/**
 * for Complaint routings
 */
$route['complaintSystem'] = "complaint/index";
$route['add-new-complaint/(:num)'] = "complaint/add/$1";
$route['view-complaint/(:num)'] = "complaint/edit/$1";
$route['getComplaintType'] = "complaint/get_complaint_type";
$route['imageDisplay'] = "common/get_image_to_display";


/* End of file routes.php */
/* Location: ./application/config/routes.php */