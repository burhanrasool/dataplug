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

/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('session_to_page')) {

    function session_to_page($session, &$data) {
        foreach ($session as $key => $value) {
            $data[$key] = $value;
        }
    }

}

// ------------------------------------------------------------------------

/**
 * Random Element - Takes an array as input and returns a random element
 *
 * @access	public
 * @param	array
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('random_element')) {

    function random_element($array) {
        if (!is_array($array)) {
            return $array;
        }

        return $array[array_rand($array)];
    }

}

// --------------------------------------------------------------------

/**
 * Elements
 *
 * Returns only the array items specified.  Will return a default value if
 * it is not set.
 *
 * @access	public
 * @param	array
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('elements')) {

    function elements($items, $array, $default = FALSE) {
        $return = array();

        if (!is_array($items)) {
            $items = array($items);
        }

        foreach ($items as $item) {
            if (isset($array[$item])) {
                $return[$item] = $array[$item];
            } else {
                $return[$item] = $default;
            }
        }

        return $return;
    }

}

function addAppFromSession($department_id, $user_id) {

    $ci = &get_instance();

    $session_id = $ci->session->userdata['session_id'];
    $query = $ci->db->get_where('app_temp', array('session_id' => $session_id));
    $approws = $query->row_array();
    if ($approws) {
        $addapparray = array(
            'department_id' => $department_id,
            'user_id' => $user_id,
            'name' => $approws['name'],
            'description' => $approws['description'],
            'full_description' => $approws['full_description'],
            'icon' => 'icon',
        );
        $ci->db->insert('app', $addapparray);
        $app_id = $ci->db->insert_id();
        $appdata = array(
                        'user_id' => $user_id,
                        'app_id' => $app_id
                    );
        $ci->db->insert('users_app', $appdata);
        

        //upload app icon    
        $abs_path = './assets/images/data/form_icons/' . $app_id;
        $old = umask(0);
        @mkdir($abs_path, 0777);
        umask($old);
        $iconName = 'appicon_' . $app_id . '.png';

        if ($approws['icon']) {
            $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
            file_put_contents($abs_path . $iconName, $approws['icon']);
        } else {
            $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
            //$iconName = 'default_app.png';
            $from_path = FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png';
            /* Extract the filename */

            /* Save file wherever you want */
            file_put_contents($abs_path . $iconName, @file_get_contents($from_path));
        }
        $change_icon = array(
            'icon' => $iconName
        );
        $ci->db->where('id', $app_id);
        $ci->db->update('app', $change_icon);

//        $app_settings = array(
//            'app_id' => $app_id,
//            'latitude' => '31.58219141239757',
//            'longitude' => '73.7677001953125',
//            'zoom_level' => '7',
//            'map_type' => 'Pin',
//            'district_filter' => 'Off',
//            'uc_filter' => 'Off',
//            'app_language' => 'english'
//        );
//        $ci->db->insert('app_settings', $app_settings);






        //Form area
        $app_temp_id = $approws['id'];
        $query1 = $ci->db->get_where('form_temp', array('app_temp_id' => $app_temp_id));
        $formrows = $query1->result_array();

        $total_forms = 1;
        foreach ($formrows as $form) {
            $rand_key = random_string('alnum', 10);
            $addformarray = array(
                'app_id' => $app_id,
                'name' => $form['name'],
                'description' => $form['description'],
                'full_description' => $form['full_description'],
                'filter' => 'version_name',
                'possible_filters' => 'version_name',
                'next' => $rand_key,
                'security_key' => $rand_key,
            );
            $ci->db->insert('form', $addformarray);
            $form_id = $ci->db->insert_id();


            //upload form icon    
            $iconName = 'formicon_' . $form_id . '.png';
            if ($form['icon']) {
                $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
                file_put_contents($abs_path . $iconName, $form['icon']);
            } else {
                $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
                //$iconName = 'default_' . $total_forms . '.png';
                $from_path = FORM_IMG_DISPLAY_PATH . '../form_icons/default_' . $total_forms . '.png';
                file_put_contents($abs_path . $iconName, @file_get_contents($from_path));
                $total_forms++;
            }
            
            
            $file_name_html = "form_$form_id.html";
            $change_next = array(
                'next' => $file_name_html,
                'icon' => $iconName
            );
            $ci->db->where('id', $form_id);
            $ci->db->update('form', $change_next);

            updateDataBase($form_id, $form['description']);
        }
        $ci->db->delete('app_temp', array('session_id' => $session_id));
    }
}

function is_default_column($column_name){
    $column_name = strtolower($column_name);
    $fields_table = array(
        'id',
        'form_id',
        'activity_status',
        'form_version_date',
        'deviceTS',
        'imei_no',
        'location',
        'uc_name',
        'town_name',
        'district_name',
        'is_deleted',
        'version_name',
        'location_source',
        'time_source',
        'post_status',
        'activity_datetime',
        'created_datetime'
    );
    if (in_array($column_name, $fields_table)) {
        return true;
    }
    return false;
}

function updateDataBase($form_id, $description) {
    $ci = &get_instance();
    $ci->load->dbforge();
    $ci->load->model('form_results_model');
    
    $table_name = 'zform_' . $form_id;
    //$table_exist = $ci->form_results_model->check_table_exits($table_name);
    
	//Create table if not exist
    if (!(is_table_exist($table_name))) 
    {
        $fields_table = array(
            'id' => array('type' => 'INT', 'constraint' => 20, 'auto_increment' => TRUE),
            'form_id' => array('type' => 'INT', 'constraint' => 20),
            'activity_status' => array('type' => 'VARCHAR', 'constraint' => 10, 'NULL' => TRUE),
            'imei_no' => array('type' => 'VARCHAR', 'constraint' => 20),
            'location' => array('type' => 'VARCHAR', 'constraint' => 150, 'NULL' => TRUE),
            'uc_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'NULL' => TRUE),
            'town_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'NULL' => TRUE),
            'district_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'NULL' => TRUE),
            'is_deleted' => array('type' => 'tinyint', 'constraint' => 1, 'default' => '0'),
            'version_name' => array('type' => 'VARCHAR', 'constraint' => 10, 'NULL' => TRUE ),
            'location_source' => array('type' => 'VARCHAR', 'constraint' => 10, 'NULL' => TRUE),
            'time_source' => array('type' => 'VARCHAR', 'constraint' => 10, 'NULL' => TRUE),
            'post_status' => array('type' => "ENUM('yes', 'no')", 'default' => 'no'),
            'activity_datetime' => array('type' => 'timestamp', 'NULL' => TRUE)
        );

        
        $ci->dbforge->add_field($fields_table);
        $ci->dbforge->add_field("created_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        
        $ci->dbforge->add_key('id', TRUE);
        $ci->dbforge->add_key('imei_no');
        $ci->dbforge->create_table('zform_' . $form_id, TRUE);
        
        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
        $logary=array('action'=>'insert','description'=>'New Table created','form_id'=>$form_id,'after'=>  json_encode($fields_table));
        addlog($logary);
    }

    $description = "<!DOCTYPE html>" . $description;
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($description);
    libxml_clear_errors();
    libxml_use_internal_errors(false);

    $elements_name = array();
    $inputs = $dom->getElementsByTagName('input');
    foreach ($inputs as $div) {
        foreach ($div->attributes as $attr) {
            $name = $attr->nodeName;
            $value = $attr->nodeValue;
            if ($name == 'id') {
                if (!in_array($value, $elements_name)) {
                    array_push($elements_name, $value);
                }
            }
        }
    }

    $selects = $dom->getElementsByTagName('select');
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////// HASSAN ANWAR     ////////////////////////////
        ///////////////////////////////////////////////////////////////////////
    $columnNamesForOptions=array();
    foreach ($selects as $div) {
        $isMultiple=false;
        foreach ($div->attributes as $attr) {
            $name = $attr->nodeName;
            $value = $attr->nodeValue;
            if ($name == 'id') {
                if (!in_array($value, $elements_name)) {
                    array_push($elements_name, $value);
                }
            }
            if($form_id == '6421'){
                if($name=='name')
                {
                    $fieldName=$value;
                }
                if( $name=='multiple')
                {
                    $isMultiple=true;
                }
            }
        }
        if($form_id == '6421'){
            if($isMultiple)
            {
                $options = $div->getElementsByTagName('option');

                $optionInfo = array();
                foreach($options as $option) {
                    $value = $option->getAttribute('value');
                    if($value!='')
                    {
                        $text = $option->textContent;

                        $optionInfo[] = array(
                            'value' => $value,
                            'text' => $text,
                        );
                    
                    }

                }
                $columnNamesForOptions[$fieldName]=$optionInfo;
            }
        }
        
    }

        ///////////////////////////////////////////////////////////////////////
        ///////////////////////// HASSAN ANWAR     ////////////////////////////
        ///////////////////////////////////////////////////////////////////////



    $selects = $dom->getElementsByTagName('textarea');
    foreach ($selects as $div) {
        foreach ($div->attributes as $attr) {
            $name = $attr->nodeName;
            $value = $attr->nodeValue;
            if ($name == 'id') {
                if (!in_array($value, $elements_name)) {
                    array_push($elements_name, $value);
                }
            }
        }
    }
    $final_elements = array();
    $final_sub_elements = array();
    foreach ($elements_name as $element) {


        $div = $dom->getElementById($element);
        $rel = $div->getAttribute('rel');
        $name = $div->getAttribute('name');
        $id = $div->getAttribute('id');
        $subtable_id = $div->getAttribute('subtable_id');
        if ($subtable_id != '') {
            $table = $dom->getElementById($subtable_id);
            $table_name = $table->getAttribute('name');
            if (!array_key_exists($table_name, $final_sub_elements)) {
                $final_sub_elements[$table_name] = array();
            }
            if (!in_array($name, $final_sub_elements[$table_name])) {
                if (!($rel == 'skip' || $name=='row_key'|| $name == 'form_id' || $name == 'security_key' || $name == 'takepic')) {
                    array_push($final_sub_elements[$table_name], $name);
                }
            }
        } else {
            if (!in_array($name, $final_elements)) {
                if (!($rel == 'skip' || $name=='row_key' || $name == 'caption_sequence' || $name == 'form_id' || $name == 'security_key' || $name == 'takepic')) {
                    array_push($final_elements, $name);
                }
            }
        }
    }

    $fields_list = $ci->db->list_fields('zform_' . $form_id);
    $fields_list = array_map('strtolower', $fields_list);
    if (!(in_array(strtolower('activity_status'), $fields_list))) {
        $field = array('activity_status' => array('type' => 'VARCHAR', 'constraint' => 10, 'NULL' => TRUE));
        $ci->dbforge->add_column('zform_' . $form_id, $field, 'form_id');
        
    }
    
    $fields_list = $ci->db->list_fields('zform_' . $form_id);
    $fields_list = array_map('strtolower', $fields_list);
    $after_field = $fields_list[count($fields_list) - 13];
    foreach ($final_elements as $element) {
        if ($element != '') {
            $element = str_replace('[]', '', $element);
            if (!(in_array(strtolower($element), $fields_list))) {
                
                $fields_count = $ci->db->list_fields('zform_' . $form_id);
                $fields_count = array_map('strtolower', $fields_count);

                if(count($fields_count) < 90){
                    $field = array($element => array('type' => 'VARCHAR', 'constraint' => 200, 'NULL' => TRUE));
                    $ci->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                }else
                {
                    $field = array($element => array('type' => 'TEXT', 'NULL' => TRUE));
                    $ci->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                }
                
                $after_field = $element;

                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'insert', 'description' => 'Add new field into Table', 'form_id' => $form_id, 'before' => json_encode($fields_list), 'after' => json_encode($field));
                addlog($logary);
            }
        }
    }


    //For sub table creation
//      print_r($final_sub_elements);
//      exit;
    foreach ($final_sub_elements as $tabl => $elementslist) {
        if (!empty($elementslist)) {
            foreach ($elementslist as $sub_element) {

                $sub_element = str_replace('[]', '', $sub_element);
                $subtable_name = 'zform_' . $form_id . '_' . $tabl;
                //$subtable_exist = $ci->form_results_model->check_table_exits($subtable_name);
                //Create table if not exist
                if (!(is_table_exist($subtable_name))) {
                    $fields_subtable = array(
                        'id' => array('type' => 'INT', 'constraint' => 20, 'auto_increment' => TRUE),
                        'form_id' => array('type' => 'INT', 'constraint' => 20),
                        'zform_result_id' => array('type' => 'INT', 'constraint' => 20)
                    );


                    $ci->dbforge->add_field($fields_subtable);
                    $ci->dbforge->add_key('id', TRUE);
                    $ci->dbforge->create_table($subtable_name, TRUE);

                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'insert', 'description' => 'New Sub Table created', 'form_id' => $form_id, 'after' => json_encode($fields_subtable));
                    addlog($logary);
                }

                $fields_list_sub = $ci->db->list_fields($subtable_name);
                $fields_list_sub = array_map('strtolower', $fields_list_sub);

                $after_field_sub = $fields_list_sub[count($fields_list_sub) - 1];

                if (!(in_array(strtolower($sub_element), $fields_list_sub))) {

                    $field_sub = array($sub_element => array('type' => 'TEXT', 'NULL' => TRUE));
                    $ci->dbforge->add_column($subtable_name, $field_sub, $after_field_sub);
                    $after_field_sub = $sub_element;

                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'insert', 'description' => 'Add new field into Sub Table', 'form_id' => $form_id, 'before' => json_encode($fields_list), 'after' => json_encode($field));
                    addlog($logary);
                }
            }
        }//end if
    }

    if($form_id == '6421'){
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////// HASSAN ANWAR     ////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        
        
    $fields_list_option = $ci->db->list_fields('zform_' . $form_id);
    $fields_list_option = array_map('strtolower', $fields_list);
            

        foreach($columnNamesForOptions as $k=>$optionSet)
        {

            $fieldInfo = array(
                    'formId' => $form_id,
                    'fieldLabel' => $k,
                );
                $ci->db->insert('form_multiselectFieldLabels', $fieldInfo);
                $fieldInfoSaved = $ci->db->insert_id();


            foreach($optionSet as $o)
            {
                $columnName=$k.'_'.$o['value'];
                $columnName=str_replace(' ', '_', $columnName);

                if (!(in_array(strtolower($columnName), $fields_list_option))) {
                    $field = array($columnName => array('type' => 'tinyint','default' => 0,'constraint' => 1,'NULL' => TRUE));

                    $after_field=$k;

                    if($fieldInfoSaved>0)
                    {
                        $ci->dbforge->add_column('zform_' . $form_id, $field, $after_field);    
                    }
                }
            }

        }
    }

        ///////////////////////////////////////////////////////////////////////
        ///////////////////////// HASSAN ANWAR     ////////////////////////////
        ///////////////////////////////////////////////////////////////////////
}

//logary parameters : action, description, before, after, app_id, app_name, form_id, form_name
function addlog($logary) {
    $ci = &get_instance();
    if ($ci->session->userdata('logged_in')) {


        $session_values = $ci->session->userdata('logged_in');

        $changed_by_id = $session_values['login_user_id'];
        $changed_by_name = $session_values['login_user_fullname'];
        $department_id = $session_values['login_department_id'];
        $department_name = $session_values['login_department_name'];
        $action_type = $logary['action'];
        $action_description = $logary['description'];
        $before_record = (isset($logary['before'])) ? $logary['before'] : "";
        $after_record = (isset($logary['after'])) ? $logary['after'] : "";
        $app_id = (isset($logary['app_id'])) ? $logary['app_id'] : "0";
        $app_name = (isset($logary['app_name'])) ? $logary['app_name'] : "";
        $form_id = (isset($logary['form_id'])) ? $logary['form_id'] : "0";
        $form_name = (isset($logary['form_name'])) ? $logary['form_name'] : "";

        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $log_array = array(
            'changed_by_id' => $changed_by_id,
            'changed_by_name' => $changed_by_name,
            'action_type' => $action_type,
            'action_description' => $action_description,
            'controller' => $ci->router->class,
            'method' => $ci->router->method,
            'before_record' => $before_record,
            'after_record' => $after_record,
            'app_id' => $app_id,
            'app_name' => $app_name,
            'form_id' => $form_id,
            'form_name' => $form_name,
            'department_id' => $department_id,
            'department_name' => $department_name,
            'ip' => $ip
        );

        $ci->db->insert('log', $log_array);
    }
}

//This function for checking the table exestance into selected Database
function is_table_exist($table_name) {
    $ci = &get_instance();

    $query = "SHOW tables LIKE '$table_name'";
    $rec = $ci->db->query($query);
    $tab_array = $rec->result_array();
    if (count($tab_array) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

//This function for checking the table exestance into selected Database
function send_sms($phone_number, $sms_content,$language = 'english') {

    $model = array(
        'phone_no' => $phone_number,
        'sms_text' => $sms_content,
        'sec_key' => '23df1b21b3832ba9d85a95860f2f23d7',
        'sms_language' => $language
    );

    $post_string = http_build_query($model);
    $sms_url = "http://103.226.217.138/api/send_sms";
    $ch = curl_init(); // or die("Cannot init");
    curl_setopt($ch, CURLOPT_URL, $sms_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Length: ' . strlen($post_string))
    );

    $curl_response = curl_exec($ch); // 
    $gr = $curl_response;
    curl_close($ch);
    
}

//Add tracking table if not exist otherwise it will return only true. application id will attach with table name
function add_tracking_table($app_id){
    $ci = &get_instance();
    $ci->load->dbforge();
    
    $table_name = 'ztracking_'.$app_id;
    if (!(is_table_exist($table_name))) 
    {
        $fields_table = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE),
            'route_id' => array('type' => 'VARCHAR', 'constraint' => 20),
            'imei_no' => array('type' => 'VARCHAR', 'constraint' => 20, 'NULL' => TRUE),
            'gps_datetime' => array('type' => 'datetime'),
            'distanceCovered' => array('type' => 'VARCHAR', 'constraint' => 20, 'NULL' => TRUE),
            'distanceCoveredGeo' => array('type' => 'VARCHAR', 'constraint' => 20, 'NULL' => TRUE),
            'records' => array('type' => 'longtext','NULL' => TRUE),
            'created_datetime' => array('type' => 'timestamp'),
        );
        $ci->dbforge->add_field($fields_table);
        $ci->dbforge->add_key('id', TRUE);
        $ci->dbforge->add_key('imei_no');
        $ci->dbforge->add_key('route_id');
        $ci->dbforge->add_key('gps_datetime');
        $ci->dbforge->create_table($table_name, TRUE);
    }
    return true;
}


function post_record($base_url, $data_post_json) {
    $base_url = urldecode($base_url);
    $urlpost = $base_url;
    $fields_string = "form_data=" . $data_post_json;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlpost);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curl);
    curl_close($curl);
    
    $status_sent = false;
    if ($res === false) {
        $status_sent = false;
    } else {
        $res = json_decode($res, true);
        if (isset($res['status']) && $res['status'] == true) {
            $status_sent = true;
        } else {
            $status_sent = false;
        }
    }
    return $status_sent;
}

/**
 * Send record to other domain using CURL
 * 
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function post_record_dotnet($base_url, $fields_string) {
    $base_url = urldecode($base_url);
    $urlpost = $base_url;
    $fields_string = urldecode($fields_string);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlpost);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

    /**
     * Get Union Councel name again given location using third party API
     * 
     * @return boolen or name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getUcName($location) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);

        $url = "http://ucfinder.herokuapp.com/ajax/region_finder.json?lat=$lat&long=$long";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //$body = curl_exec($ch);
        if( ! $body = curl_exec($ch)) {
            return false;
        }else{
            $ucname = json_decode($body, 1);
            if ($ucname [0]) {
                return $ucname [1];
            } else {
                return false;
            }
        }
    }

    /**
     * Get town name again given location using third party API
     * 
     * @return boolean or town name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getTownName($location = null) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);
        $url = "http://ucfinder.herokuapp.com/ajax/town_finder.json?lat=$lat&long=$long";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //$body = curl_exec($ch);
        if( ! $body = curl_exec($ch)) {
            return false;
        }else{
            $townname = json_decode($body, 1);
            if ($townname != 'null_string') {
                return $townname;
            } else {
                return false;
            }
        }
    }

    /**
     * Get district name on given location using third party API
     * 
     * @return boolean or district name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getDistrictName($location = null) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);
        $url = "http://ucfinder.herokuapp.com/ajax/distict_finder.json?lat=$lat&long=$long";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $body = curl_exec($ch);
        if( ! $body = curl_exec($ch)) {
            return false;
        }else{
            $districtname = json_decode($body, 1);
            if ($districtname [0]) {
                return $districtname [1];
            } else {
                return false;
            }
        }
    }



function file_write($file_path_name,$file_content){

    $fhandle = fopen($file_path_name,"w");
    fwrite($fhandle,$file_content);
    fclose($fhandle);
}

function turn_around_time_calculation($turn_around_hours='4',$duty_start_time='09:30',$duty_end_time='17:30',$week_holidays='2'){
    

}

function get_image_path($file_name){
    $first_str = base64_encode('rootidr'.$file_name);
    $final_str = str_replace("==","twiiiyui=", $first_str);
    return base_url()."imageDisplay?e=".date('mYd')."&img=".$final_str."&t=".time();
}

function image_name_decode($file_name){

    $first_str = str_replace("twiiiyui=", "==", $file_name);
    $second_str = base64_decode($first_str);
   return $final_str = str_replace('rootidr', "", $second_str);
}
/* Location: ./system/helpers/array_helper.php */
