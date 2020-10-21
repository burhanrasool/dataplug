<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('PLATFORM_PACKAGE', '');// "" empty for live, "devtest" for dev server, "localtest" for localhost
define('PLATFORM_NAME', 'DataPlug');
define('FOOTER_TEXT', "All Rights Reserved &copy; 2013 - ".date('Y')." - DataPlug By ITU Government of Punjab - Pakistan.");

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

//custom defination for godk

//Path settings
defined('FORM_IMG_ABS_PATH')
    || define('FORM_IMG_ABS_PATH', FCPATH.'assets\images\data\form-data\\');

defined('FORM_IMG_DISPLAY_PATH')
    || define('FORM_IMG_DISPLAY_PATH', BASEURL.'/assets/images/data/form-data/');


//email settings
define('SUPPORT_EMAIL',		'dataplugsupport@itu.edu.pk');
define('SUPPORT_NAME',		'DataPlug SUPPORT TEAM');

$general_setting = '{"setting_type":"general_settings","secured_apk":"0","only_authorized":"0","app_language":"english","record_stop_sending":"0","message_stop_sending_record":"","screen_view":"4","upgrade_from_google_play":"0","filters":[]}';
define('GENERAL_SETTINGS',$general_setting);

$result_view_settings = '{"setting_type":"result_view_settings","district_filter":"0","uc_filter":"0","sent_by_filter":"0","form":"4744","filters":["location","uc_name"],"selectItem":"uc_name"}';
define('RESULT_VIEW_SETTINGS',$result_view_settings);

$map_view_settings = '{"setting_type":"map_view_settings","default_latitude":"31.4795037","default_longitude":"74.3418914","default_zoom_level":"7","map_type_filter":"0","district_filter":"0","uc_filter":"0","sent_by_filter":"0","district_wise_filter":"0","form":"4744","filters":["location"],"selectItem":"location"}';
define('MAP_VIEW_SETTINGS',$map_view_settings);

$graph_view_settings = '{"setting_type":"graph_view_settings","district_filter":"0","uc_filter":"0","sent_by_filter":"0","form":"4744","filters":["imei_no"],"selectItem":"imei_no","district_wise_report":"0","pie_chart_of_selected_filters":"0"}';
define('GRAPH_VIEW_SETTINGS',$graph_view_settings);









