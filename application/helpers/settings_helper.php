<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/array_helper.html
 */
// ------------------------------------------------------------------------


/*This function is for getting applicaiotn general settings from app_settings table
 Return type : Object
 @author Zahid Nadeem (zahidiubb@yahoo.com)
 */

if (!function_exists('get_app_general_settings')) {
	
	function get_app_general_settings($app_id) {
		$ci = &get_instance();
	
		$query = "SELECT * FROM app_settings where setting_type='GENERAL_SETTINGS' AND app_id=$app_id";
		$rec = $ci->db->query($query);
		$setting_array = $rec->row_array();
        if(count($setting_array)>0) {
            return json_decode($setting_array['filters']);
        }else{
            return array();
        }
	}
}

/*This function is for getting applicaiotn result view settings from app_settings table
 Return type : Object
 @author Zahid Nadeem (zahidiubb@yahoo.com)
 */

if (!function_exists('get_result_view_settings')) {
	
	function get_result_view_settings($app_id) {
		$ci = &get_instance();
	
		$query = "SELECT * FROM app_settings where setting_type='RESULT_VIEW_SETTINGS' AND app_id=$app_id";
		$rec = $ci->db->query($query);
		$setting_array = $rec->row_array();
        if(count($setting_array)>0) {
            return json_decode($setting_array['filters']);
        }else{
            return array();
        }
	}
}
/*This function is for getting applicaiotn map view settings from app_settings table
 Return type : Object
 @author Zahid Nadeem (zahidiubb@yahoo.com)
 */

if (!function_exists('get_map_view_settings')) {
	
	function get_map_view_settings($app_id) {
		$ci = &get_instance();
	
		$query = "SELECT * FROM app_settings where setting_type='MAP_VIEW_SETTINGS' AND app_id=$app_id";
		$rec = $ci->db->query($query);
		$setting_array = $rec->row_array();
        if(count($setting_array)>0) {
            return json_decode($setting_array['filters']);
        }else{
            return array();
        }
	}
}

/*This function is for getting applicaiotn graph view settings from app_settings table
 Return type : Object
 @author Zahid Nadeem (zahidiubb@yahoo.com)
 */

if (!function_exists('get_graph_view_settings')) {
	
	function get_graph_view_settings($app_id) {
		$ci = &get_instance();
	
		$query = "SELECT * FROM app_settings where setting_type='GRAPH_VIEW_SETTINGS' AND app_id=$app_id";
		$rec = $ci->db->query($query);
		$setting_array = $rec->row_array();
        if(count($setting_array)>0) {
            return json_decode($setting_array['filters']);
        }else{
            return array();
        }
	}
}

/*This function is for getting applicaiotn map view assigned Pins of every value
 Return type : Object
 @author Zahid Nadeem (zahidiubb@yahoo.com)
 */

if (!function_exists('get_map_pin_settings')) {
	
	function get_map_pin_settings($form_id) {
		$ci = &get_instance();
	
		$query = "SELECT * FROM map_pin_settings where form_id='$form_id'";
		$rec = $ci->db->query($query);
		$setting_array = $rec->row_array();
        if(count($setting_array)>0) {
            return json_decode($setting_array['pins'],true);
        }else{
            return array();
        }
	}
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */
