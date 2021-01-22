<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('department_model');
        $this->load->model('app_model');
        $this->load->model('users_model');
        $this->load->model('site_model');
        $this->load->model('app_users_model');
        $this->load->model('app_installed_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('form_results_model');
    }

    /**
     * This function is used for updating the android application when
     user click on refresh button that available on android application title bar.
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function updateforms() {
        if (isset($_REQUEST ['app_id_cross'])) {
            $app_id = $_REQUEST ['app_id_cross'];
        } else {
            $app_id = $_REQUEST ['app_id'];
        }
        @$imei_no = $_REQUEST ['imei_no'];
        $jsone_array = array();
        $already_installed = $this->app_installed_model->get_app_installed($app_id,
            $imei_no);
        if ($already_installed) {
            //$change_status = $already_installed ['change_status'];
            $jsone_array = $this->get_forms($app_id, $imei_no);
        } else {
            $install_array = array(
                'app_id' => $app_id,
                'imei_no' => $imei_no,
                'change_status' => '1'
            );
            $this->install_app($install_array);
            $jsone_array = $this->get_forms($app_id, $imei_no);
        }
        echo json_encode($jsone_array);
        exit();
    }

    /**
     * This function is used for saving the installed application
     device imei#, for further updation of application.
     * 
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function install_app($data = array()) {
        $this->db->insert('app_installed', $data);
    }

    /**
     * This function is used forgetting forms and its icons for updation process
     * 
     * @return array
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_forms($app_id, $imei_no) {
        // get imei_no against view_id if multiple
        //views available of a single application
        $views_list = $this->app_users_model->get_view_id_by_imei_no($app_id,
            $imei_no);
        $view_id = '';
        if ($views_list && $views_list ['view_id'] != '0') {
            $view_id = $views_list ['view_id'];
            $forms = $this->form_model->get_form_by_app_view($app_id, $view_id);
        } else {
            $forms = $this->form_model->get_form_by_app($app_id);
        }
        $image_array = array();
        $form_array = array();
        $total_forms = count($forms);

        // if multiple form available then make landing page
        //which consist form icon with links. Otherwise launch form directly
        if ($total_forms > 1) {
            $selected_app = $this->app_model->get_app($app_id, $view_id);
            $image_array [] = array(
                'image_url' => FORM_IMG_DISPLAY_PATH . '../form_icons/' 
                    . $app_id . '/' . $selected_app ['icon'],
                'image_name' => $selected_app ['icon']
            );

            if ($selected_app ['avid']) {
               
                $formFullDescription = $selected_app ['av_full_description'];
            } else {
                $formFullDescription = $selected_app ['full_description'];
            }
            $fileName = 'index.html';
            $form_array [] = array(
                'form_id' => '0',
                'form_name' => 'noname',
                'form_description' => $formFullDescription,
                'file_name' => $fileName,
                'file_url' => ''
            );
        }

        $fileName = '';
        foreach ($forms as $form) {
            $formId = $form ['form_id'];
            $formName = $form ['form_name'];
            $image_array [] = array(
                'image_url' => FORM_IMG_DISPLAY_PATH . '../form_icons/' 
                    . $app_id . '/' . $form ['form_icon'],
                'image_name' => $form ['form_icon']
            );
            $formFullDescription = $form ['full_description'];
            
            if($view_id){
                $file_name_html = $view_id.'_'.'form_'.$form['form_id'].'.html';
            }
            else{
                $file_name_html = 'form_'.$form['form_id'].'.html';
            }
            $html_file = '';
            if (file_exists('./assets/images/data/form_icons/' 
                . $app_id . '/' . $file_name_html)) {
                $html_file = FORM_IMG_DISPLAY_PATH . '../form_icons/' ;
                $html_file .= $app_id . '/' . $file_name_html;
            }

            $released = $this->app_released_model->get_latest_released($app_id);
            //if($app_id=='269' || $app_id=='8' || $app_id=='288')
            if($released['created_datetime'] > '2017-03-01 00:00:00')
            {
                $formFullDescription ="";
            }
            
            if ($total_forms == 1) {
                $fileName = 'index.html';
            } else {
                $fileName = $form ['next'];
            }
            $form_array [] = array(
                'form_id' => $formId,
                'form_name' => $formName,
                'form_description' => $formFullDescription,
                'file_name' => $fileName,
                'file_url' => $html_file
            );
            
        }

        // JS
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/common.js',
            'file_name' => 'common.js'
        );
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jquery-ui.min.js',
            'file_name' => 'jquery-ui.min.js'
        );

        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/timedev.js',
            'file_name' => 'timedev.js'
        );
 

        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jquerylatest.js',
            'file_name' => 'jquerylatest.js'
        );
 
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jquery-ui-autocomplete.js',
            'file_name' => 'jquery-ui-autocomplete.js'
        );
 
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jqui.js',
            'file_name' => 'jqui.js'
        );
   
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/datebox.js',
            'file_name' => 'datebox.js'
        );
      
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/dateformatting.js',
            'file_name' => 'dateformatting.js'
        );
         
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/timehelper.js',
            'file_name' => 'timehelper.js'
        );
            
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/bs.js',
            'file_name' => 'bs.js'
        );            
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jquery-1.12.4.min.js',
            'file_name' => 'jquery-1.12.4.min.js'
        );        

        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/jquery.select-to-autocomplete.min.js',
            'file_name' => 'jquery.select-to-autocomplete.min.js'
        );
   
 


        // CSS
        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/common.css',
            'file_name' => 'common.css'
        );        

        $file_array [] = array(
            'form_id' => '0',
            'form_name' => 'noname',
            'file_url' => base_url() . 'assets/android/app_resources/loading.gif',
            'file_name' => 'loading.gif'
        );

        $form_array_final = array(
            'forms' => $form_array
        );
        $image_array_final = array(
            'images' => $image_array
        );
        $file_array_final = array(
            'files' => $file_array
        );
        $app_general_setting = get_app_general_settings($app_id);
        $prefrences = array();
        if(isset($app_general_setting->has_geo_fencing)){
            
            $prefrences['IS_SECURE_APP']=($app_general_setting->secured_apk==1)?'YES':'NO';
            $prefrences['showHighResOption']=($app_general_setting->high_resolution_image==1)?'YES':'NO';
            $prefrences['PersistImagesOnDevice']=($app_general_setting->persist_images_on_device==1)?'YES':'NO';
            $prefrences['BackgroundUpdate']=($app_general_setting->background_update==1)?'YES':'NO';
            $prefrences['ForceUpdate']=($app_general_setting->force_update==1)?'YES':'NO';
            $prefrences['EnableAutoTime']=($app_general_setting->enable_auto_time==1)?'YES':'NO';
            $prefrences['TrackingStatus']=($app_general_setting->tracking_status==1)?'YES':'NO';
            $prefrences['TrackingInterval']=(isset($app_general_setting->tracking_interval))?$app_general_setting->tracking_interval:'5';
            $prefrences['TrackingDistance']=(isset($app_general_setting->tracking_distance))?$app_general_setting->tracking_distance:'100';
            $prefrences['DebugTracking']=(isset($app_general_setting->debug_tracking) && $app_general_setting->debug_tracking == 1)?'YES':'NO';
            $prefrences['hasGeoFencing']=(isset($app_general_setting->has_geo_fencing) && $app_general_setting->has_geo_fencing == 1)?'YES':'NO';
            $prefrences['DebugGeoFencing']=(isset($app_general_setting->debug_geo_fencing) && $app_general_setting->debug_geo_fencing == 1)?'YES':'NO';
            //Get geoFence from App user table
            $prefrences['geoFence']="[{'lng':74.33375,'lat':31.50282},{'lng':72.32271,'lat':31.49976},{'lng':74.3286,'lat':31.48541},{'lng':74.3474,'lat':30.48577},{'lng':74.33764,'lat':34.5049}]";
            
        }
        else{
            $prefrences = new stdClass();
        }
        $p = array('preferences' => $prefrences);
        $form_array_return = array_merge($image_array_final, $form_array_final, $file_array_final,$p);
        return $form_array_return;
    }

    /**
     * This function is also used for updating the android application when user click on refresh button that available on android application title bar.
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function syncdevice() {
        $version_code = $_REQUEST ['version_code'];
        $app_id = $_REQUEST ['app_id'];
        $imei_no = $_REQUEST ['imei_no'];
        $jsone_array = array();
        $latest_release = $this->app_released_model->get_latest_released($app_id);
        
        $version_installed = $this->app_released_model->get_version_against_version_code($app_id,$version_code);
        
        $already_installed = $this->app_installed_model->get_app_installed($app_id, $imei_no);

        if ($already_installed) {

            //Update the version and datatime
            $created_datetime = date('Y-m-d H:i:s');
            $install_array = array(
                'app_id' => $app_id,
                'imei_no' => $imei_no,
                'change_status' => '1',
                'app_version' => $version_installed,
                'created_datetime' => $created_datetime,
            );
            //$this->install_app($install_array);
            $this->db->where('app_id',$app_id);
            $this->db->where('imei_no',$imei_no);
            $this->db->update('app_installed', $install_array);

        } else {
            $install_array = array(
                'app_id' => $app_id,
                'imei_no' => $imei_no,
                'change_status' => '1',
                'app_version' => $version_installed
            );
            $this->db->insert('app_installed', $install_array);
        }







//        $txt = $imei_no; 
//        $myfile = file_put_contents('./assets/data/synclogs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

        // Temp code for evaccs - Start
        //new version for evacc only update the specific imei
//        $imei_array = array('865281025760638','863963024004108','357943061037149','357944061037147','354435054188099','862120024354527','863963020962283','863963020941048','863963020962481','863963020956806','863963020970708','863963020962275','866356021218457','862866027099515','862866027109728','355794061492707','356222070208939','863963020985607','863963020962747','863963020942285','860557023831363','863963020962671','863963020971144','863963024006137','864532028186531','863963023610939','863963023610954','863963024022092','863963024028115','863963024008117','353861060451976','353861060451976','863963023555621','863963023616316','863963023586709','863963023571420','863963023586972','863963023579407','863963023587822','863963023587145','863963023586170','863963023586279','863963023555050','863963023579563','863963023633345','869358028906693','863963023557213','863963023556579','863963023555860','352328071131675','863963023612133','863963023257905','865281025813742','863963023579449','355451509524845','863963024014362','359154050754672');        
////$imei_array = array('863963024004108','354435054188099');
//        if($app_id == '9'){
//            if(in_array($imei_no, $imei_array)){
//                if ($version_code != $latest_release['version_code']) {
//                    $file = base_url() . 'assets/android/apps/' . $latest_release['app_file'];
//                    $jsone_array = array(
//                        'status' => '1', // status for new version
//                        'url' => $file,
//                        'version' => $latest_release['version_code']
//                    );
//                } else {
//                    $jsone_array = array(
//                        'status' => '2', // status for form change
//                        'url' => ''
//                    );
//                }
//                
//            }
//            else{
//                $jsone_array = array(
//                        'status' => '2', // status for form change
//                        'url' => ''
//                    );
//            }
//            
//            echo json_encode($jsone_array);
//            exit();
//            
//        }
        // Temp code for evaccs - End
        
        
        if ($version_code != $latest_release ['version_code']) {
            $file = base_url() . 'assets/android/apps/' . $latest_release ['app_file'];
            $jsone_array = array(
                'status' => '1', // status for new version
                'url' => $file,
                'version' => $latest_release['version_code']
            );
        } else {
            $jsone_array = array(
                'status' => '2', // status for form change
                'url' => ''
            );
        }
        echo json_encode($jsone_array);
        exit();
    }

    /**
     * This function is used for saving the record which sent from android application
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function saverecords() {

        $form_data = json_decode($_REQUEST ['form_data']);
        $imei_no = $_REQUEST ['imei_no'];
        $location = $_REQUEST ['location'];
        $dateTime_device = $_REQUEST ['dateTime'];
        //$activity_datetime = date('Y-m-d H:i:s', strtotime($_REQUEST ['dateTime']));
        if (strpos($dateTime_device, ':') < 12) {
            $pattern = '#(\d+):(\d+):(\d+) (\d+):(\d+):(\d+)#';
            $replacement = '$1-$2-$3 $4:$5:$6';
            $activity_datetime = preg_replace($pattern, $replacement, $dateTime_device);
            $activity_datetime = date('Y-m-d H:i:s', strtotime($activity_datetime));
        } else if (strpos($dateTime_device, '/') < 12) {
            $pattern = '#(\d+)/(\d+)/(\d+) (\d+):(\d+):(\d+)#';
            $replacement = '$1-$2-$3 $4:$5:$6';
            $activity_datetime = preg_replace($pattern, $replacement, $dateTime_device);
            $activity_datetime = date('Y-m-d H:i:s', strtotime($activity_datetime));
        } else {
            $activity_datetime = date('Y-m-d H:i:s', strtotime($_REQUEST ['dateTime']));
        }

        $time_source = '';
        $location_source = '';
        if (isset($_REQUEST ['time_source'])) {
            $time_source = $_REQUEST ['time_source'];
        }
        if (isset($_REQUEST ['location_source'])) {
            $location_source = $_REQUEST ['location_source'];
        }
        $version_name = $_REQUEST ['version_name'];
        $captions_images = array();
        $sub_table_record = array();
        $record = array();
        $form_id = '';
        $security_key = false;
        $created_datetime = date('Y-m-d H:i:s');
        $str = '';
        
        if(strtotime($activity_datetime) > strtotime($created_datetime)){
            $activity_datetime = $created_datetime;
        }
        
        $form_id = $form_data->form_id;
        
        
        //Stop activity saving if already saved
//        $activity_aready_exist = $this->db->get_where('mobile_activity_log', array('form_id' => $form_id, 'imei_no' => $imei_no,'form_data' => $_REQUEST['form_data']))->row_array();
//        if ($activity_aready_exist) {
//            $jsone_array = array(
//                'success' => 'This activity already submitted.'
//            );
//            echo json_encode($jsone_array);
//            exit();
//        }
//        $activity_aready_exist = $this->db->get_where('zform_'.$form_id, array('form_id' => $form_id, 'imei_no' => $imei_no,'activity_datetime' => $activity_datetime))->row_array();
//        if ($activity_aready_exist) {
//            $jsone_array = array(
//                'success' => 'This activity already submitted.'
//            );
//            echo json_encode($jsone_array);
//            exit();
//        }
        
        //If form removed but user not update his application
        try{
            $form_info = $this->form_model->get_form($form_id);
        }catch (Exception $e) {
            $jsone_array = array(
                'error' => $e->message()
            );
            echo json_encode($jsone_array);
            exit;
        }

        if (!$form_info) {
            $jsone_array = array(
                'error' => 'This form has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }        
        $app_id = $form_info ['app_id'];


        //$app_id = $form_info ['app_id'];
        // Temprary block the record sending
        $app_general_setting = get_app_general_settings($app_id);


        //Minimum version to accept activity
        if(isset($app_general_setting->minimum_version_to_accept) && $app_general_setting->minimum_version_to_accept != ''){

            if ((float)$version_name < (float)$app_general_setting->minimum_version_to_accept) {
                $jsone_array = array(
                    'error' => 'Please refresh the application version, This is old version and not allow to submit data.'
                );
                echo json_encode($jsone_array);
                exit();
            }


        }


        if (isset($app_general_setting->record_stop_sending) && $app_general_setting->record_stop_sending == 1) {
            $error_message = "Record receiving service currently not available. Record saved localy. Please Try later";
            if ($app_general_setting->message_stop_sending_record != '') {
                $error_message = $app_general_setting->message_stop_sending_record;
            }
            $jsone_array = array(
                'error' => $error_message
            );
            echo json_encode($jsone_array);
            exit();
        }

        $app_info = $this->app_model->get_app($app_id);
        if (!$app_info) {
            $jsone_array = array(
                'error' => 'This application has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }
        
        if($imei_no!==''){
            $authorized = $this->app_model->appuser_imei_already_exist($imei_no, $app_id);
            if (isset($app_general_setting->only_authorized) && $app_general_setting->only_authorized == 1 && !$authorized) {
                $jsone_array = array(
                    'error' => 'You are not authorized'
                );
                echo json_encode($jsone_array);
                exit();
            }
        }
        
        $activity_temp = array(
            'app_id' => $app_id,
            'form_id' => $form_id,
            'form_data' => $_REQUEST['form_data'],
            'imei_no' => $_REQUEST['imei_no'],
            'location' => $_REQUEST['location'],
            'dateTime' => $_REQUEST['dateTime'],
            'time_source' => $_REQUEST['time_source'],
            'location_source' => $_REQUEST['location_source'],
            'version_name' => $_REQUEST['version_name']
            );
        $activity_inserted_id = $this->form_results_model->save_mobile_activity($activity_temp);
        


        $direct_save = true;
        if (isset($app_general_setting->direct_save) && $app_general_setting->direct_save == 1)
        {
            $direct_save = false;
        }


        
        $take_picture = '';
        $caption_sequence = '';
        foreach ($form_data as $key => $v) {
            $cap_first = explode('-', $key);
            if (is_array($v)) {
                $subtable_name = 'zform_' . $form_id . '_' . $key;
                if (is_table_exist($subtable_name)) {
                    foreach ($v as $varray) {
                        $element_value = explode('&', $varray);
                        $sub_record = array();
                        foreach ($element_value as $sep_element_value) {
                            if ($sep_element_value != '') {
                                $element_value_sub = explode(':', $sep_element_value);
                                $temp_rry = array(
                                    $element_value_sub [0] => $element_value_sub [1]
                                );
                                $sub_record = array_merge($sub_record, $temp_rry);
                            }
                        }
                        $sub_table_record [$key] [] = $sub_record;
                    }
                    $record[$key] = 'SHOW RECORDS';
                }
            } else if ($cap_first [0] == 'caption') {
                $captions_images[$key] = $v;
            } else if ($key == 'caption_sequence') {
                $caption_sequence = urldecode($v);
            } elseif ($key == 'form_id' || $key == 'row_key' || $key == 'security_key' || $key == "dateTime" || $key == "landing_page" || $key == "is_take_picture" || $key == 'form_icon_name') {
                
            } else {

                if(empty($form_info['security_key'])){
                    $vdcode = urldecode(base64_decode($v));
                }
                else if(strpos($v, $form_info['security_key']) !== FALSE){
                    $vdcode = urldecode(base64_decode(str_replace($form_info['security_key'], '', $v)));
                }
                else {
                    $vdcode = urldecode($v);
                }

                $tempary = array(
                    $key => $vdcode
                );
                $record = array_merge($record, $tempary);
            }
        }

        if($form_id == '10601'){
            $match_exist_field_value = $record['cardnumber'];
            //$activity_aready_exist = $this->db->get_where('zform_10601', array('cardnumber' => $match_exist_field_value))->row_array();
            $query=$this->db->query("select *
                                    from zform_10601
                                    where cardnumber ='$match_exist_field_value' and is_deleted='0'");
            $activity_aready_exist = $query->row_array();
            //print_r($activity_aready_exist);
            //echo $this->db->last_query().'=======';
            //echo count($activity_aready_exist);
            if(!empty($activity_aready_exist)) {
               $this->form_results_model->update_mobile_activity($activity_inserted_id,array('error'=>  'This card number already entered.'));
               $jsone_array = array(
                   'success' => 'This card number already uploaded and entered.'
               );
               echo json_encode($jsone_array);
               exit();
            }
        }

        $warning_message = '';
        $app_map_view_setting = get_map_view_settings($app_id);
        if(isset($app_map_view_setting->map_distance_mapping) && $app_map_view_setting->map_distance_mapping)//if Distance maping on then call this block
        {
            $rectemp = $record; 

                $kkkkk = array(
                    'form_id' => $form_id,
                    'imei_no' => $imei_no,
                    'location' => $location,
                );

                    $matching_array_temp = array_merge($rectemp, $kkkkk);
                    $saved_distance=500;
                    if($app_map_view_setting->distance !== ''){//if distance not given then default distance will assign as 500
                        $saved_distance=$app_map_view_setting->distance;
                    }


                    $matching_value = '';
                    $matching_field_array = explode(",", $app_map_view_setting->matching_field);
                    foreach ($matching_field_array as $key => $ma_value) {
                        if(isset($matching_array_temp[$ma_value])){
                            $matching_value = $matching_array_temp[$ma_value]; 
                        }
                    }


                    $matching_value = $matching_array_temp[$app_map_view_setting->matching_field]; //this field name getting from setting and getting value from received json
                    $kml_poligon_rec = $this->db->get_where('kml_poligon', array('app_id' => $app_id, 'type' => 'distence','matching_value' => $matching_value))->row_array();
                    
                    if(!empty($kml_poligon_rec)){
                        $lat_long = explode(',', $location);//Received location from mobile device
                        $distance_from_center = lan_lng_distance($kml_poligon_rec['latitude'], $kml_poligon_rec['longitude'],$lat_long[0], $lat_long[1]);
                        if($distance_from_center > $saved_distance)
                        {
                            $jsone_array = array(
                                'success' => 'Your location is not valid.'
                            );
                            echo json_encode($jsone_array);
                            exit();
                        }
                        
                        
                    }
        }


        
        if($direct_save){
        
            header("Content-Length: 1"); 
            header("HTTP/1.1 200 OK");
            $jsone_array = array(
                'success' => 'Record submitted successfully!.'
            );
            
            ob_end_clean();
             header("Connection: close\r\n");
             header("Content-Encoding: none\r\n");
             ignore_user_abort(true); // optional
             ob_start();
             echo json_encode($jsone_array);
             $size = ob_get_length();
             header("Content-Length: $size");
             ob_end_flush();     // Strange behaviour, will not work
             flush();            // Unless both are called !
             ob_end_clean();
             //do processing here
             sleep(1);
        
        }


        /*if(isset($_FILES)){
            $total_files = count($_FILES);
        }*/
        $this->load->library('S3');
        $site_settings = $this->site_model->get_settings('1');
        $s3_access_key = $site_settings ['s3_access_key'];
        $s3_secret_key = $site_settings ['s3_secret_key'];
        $s3_bucket = $site_settings ['s3_bucket'];

        $cap_id = 0;
        $images_array = array();
        $images_title = array();
        $cap_seq_array = explode(",", $caption_sequence);
        foreach ($_FILES as $picture_key => $picture_value) 
        {

            $image_title = '';
            
            if ($s3_access_key != '' && $s3_secret_key != '' && $s3_bucket != '') {
                $bucket_name = $s3_bucket;
                
                $s3 = new S3($s3_access_key, $s3_secret_key);
                $imgName = '';
                $rand_name = random_string('alnum', 10);
                $imgName = $form_id . "_" . $rand_name . '.jpg';
                $fileTempName = $_FILES [$picture_key] ['tmp_name'];

                $image_title='';
                foreach ($cap_seq_array as $ckey => $cvalue) {
                    if (strpos($_FILES [$picture_key] ['name'], $cvalue) !== false) {
                        $image_title = $captions_images[$cvalue];
                        break;
                    }
                }
                
                if($image_title==''){
                    if(isset($captions_images[$cap_seq_array[$cap_id]]))
                        $image_title = urldecode($captions_images[$cap_seq_array[$cap_id]]);
                }

                // move the file
                if ($s3->putObjectFile($fileTempName, $bucket_name, $imgName, S3::ACL_PUBLIC_READ)) {
                    $imgName = 'http://' . $bucket_name . '.s3.amazonaws.com/' . $imgName;
                    $temp_array = array(
                        'image' => $imgName,
                        'title' => $image_title
                    );
                    $images_array[] = $temp_array;
                }
                else{
                    
                    //$path = './assets/images/data/form-data/';
                    $path = NFS_IMAGE_PATH.'/app_id_'.$app_id;
                    @mkdir($path);
                    $config ['upload_path'] = $path;
                    $config ['allowed_types'] = 'png|gif|jpg|jpeg';
                    $config ['max_size'] = '4000';
                    $config ['encrypt_name'] = true;
                    $config ['file_name'] = $imgName;

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload($picture_key)) {
                        $error_before = array(
                            'error' => $this->upload->display_errors()
                        );
                    } else {
                        $data = array(
                            'upload_data' => $this->upload->data()
                        );
                        $error_before = array(
                            'file' => $data ['upload_data'] ['file_name']
                        );
                        //$imgName = base_url() . 'assets/images/data/form-data/' . $data ['upload_data'] ['file_name'];
                        $imgName = NFS_IMAGE_PATH.'/app_id_'.$app_id.'/'.$data ['upload_data'] ['file_name'];
                        $temp_array = array(
                            'image' => $imgName,
                            'title' => $image_title
                        );
                        $images_array[] = $temp_array;
                    }
                }
            } 
            else 
            {
                $rand_name = random_string('alnum', 10);
                $imgName = $form_id . "_" . $rand_name . '.jpg';
                //$path = './assets/images/data/form-data/';
                $path = NFS_IMAGE_PATH.'/app_id_'.$app_id;
                @mkdir($path);
                $config ['upload_path'] = $path;
                $config ['allowed_types'] = 'png|gif|jpg|jpeg';
                $config ['max_size'] = '4000';
                $config ['encrypt_name'] = true;
                $config ['file_name'] = $imgName;
                $image_title='';
                foreach ($cap_seq_array as $ckey => $cvalue) {
                    if (strpos($_FILES [$picture_key] ['name'], $cvalue) !== false) {
                        $image_title = $captions_images[$cvalue];
                        break;
                    }
                }
                
                if($image_title==''){
                    if(isset($captions_images[$cap_seq_array[$cap_id]]))
                        $image_title = urldecode($captions_images[$cap_seq_array[$cap_id]]);
                }
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload($picture_key)) {
                    $error_before = array(
                        'error' => $this->upload->display_errors()
                    );
                } else {
                    $data = array(
                        'upload_data' => $this->upload->data()
                    );
                    $error_before = array(
                        'file' => $data ['upload_data'] ['file_name']
                    );
                    $imgName = NFS_IMAGE_PATH.'/app_id_'.$app_id.'/'.$data ['upload_data'] ['file_name'];
                    //$imgName = base_url() . 'assets/images/data/form-data/' . $data ['upload_data'] ['file_name'];
                    $temp_array = array(
                        'image' => $imgName,
                        'title' => $image_title
                    );
                    $images_array [] = $temp_array;
                }
                
            }
            $cap_id++;
        }

        $this->form_results_model->update_mobile_activity($activity_inserted_id,array('form_images'=>  json_encode($images_array)));
        
        $err_msg='';
        $uc_name = '';
        $town_name = '';
        $district_name = '';

        if ($location != '') {
            $uc = $this->getUcName($location); // Get UC name against location
            if ($uc) {
                $uc_name = strip_tags($uc);
            }else{
                $err_msg.="UC api return null, ";
            }

            $town = $this->getTownName($location); // Get Town name against location
            if ($town) {
                $town_name = strip_tags($town);
            }else{
                $err_msg.="TOWN api return null, ";
            }

            $district = $this->getDistrictName($location); // Get Town name against location
            if ($district) {
                $district_name = strip_tags($district);
            }else{
                $err_msg.="District api return null, ";
            }
        }

        $dataresultnew = array(
            'form_id' => $form_id,
            'imei_no' => $imei_no,
            'location' => $location,
            'uc_name' => $uc_name,
            'town_name' => $town_name,
            'district_name' => $district_name,
            'is_deleted' => '0',
            'version_name' => $version_name,
            'location_source' => $location_source,
            'time_source' => $time_source,
            'activity_datetime' => $activity_datetime,
            'created_datetime' => $created_datetime
        );

        //this is for evaccs partition

        if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab') !== false) {
            if($form_id == 21 || $form_id == 20)
            {
                $dataresultnew['created_datetime_partition']=$created_datetime;

            }
        }
        $dataresultnew1 = array_merge($dataresultnew, $record);
        
        
        
       // try{
        //Adding new field if not exist
            $this->load->dbforge();
            $fields_list = $this->db->list_fields('zform_' . $form_id);
            $fields_list = array_map('strtolower', $fields_list);
            $after_field = $fields_list[count($fields_list) - 13];
            foreach ($dataresultnew1 as $element => $ele_val) {
                if ($element != '') {
                    $element = str_replace('[]', '', $element);
                    if (!(in_array(strtolower($element), $fields_list))) {
                        $fields_count = $this->db->list_fields('zform_' . $form_id);
                        $fields_count = array_map('strtolower', $fields_count);
                        if(count($fields_count) < 90){
                            $field = array($element => array('type' => 'VARCHAR', 'constraint' => 200, 'NULL' => TRUE));
                            $this->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                        }else
                        {
                            $field = array($element => array('type' => 'TEXT', 'NULL' => TRUE));
                            $this->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                        }
                        $after_field = $element;
                    }
                }
            }
            //$err_msg .=$this->db->_error_message();
            
        
        
        
        
        if ($app_id == '5316') {

            ///////////////////////////////////////////////////////////////////////
            ///////////////////////// HASSAN ANWAR     ////////////////////////////
            ///////////////////////////////////////////////////////////////////////

            $multiChoices = $this->db->query("Select * from form_multiselectFieldLabels where formId='$form_id'");
            foreach($multiChoices->result() as $row)
            {
                $fd=(array) $dataresultnew1;

                $label=$row->fieldLabel;
                $choicesSent= $dataresultnew1[$label];
                $choicesSent=explode(',',$choicesSent);

                foreach($choicesSent as $choice)
                {
                    $choice=str_replace(' ', '_', $choice);
                    $dataresultnew1[$label.'_'.$choice]='1';
                }

            }


            ///////////////////////////////////////////////////////////////////////
            ///////////////////////// HASSAN ANWAR     ////////////////////////////
            ///////////////////////////////////////////////////////////////////////
        }
         
        $this->form_results_model->update_mobile_activity($activity_inserted_id,array('form_data_decoded'=>  json_encode($dataresultnew1)));

        $final_array = array();
        foreach ($dataresultnew1 as $key => $value) {
            $key = strtolower($key);
            if(array_key_exists($key, $final_array)){
                $final_array[$key] = $final_array[$key].','.$value;

            }else{
                $final_array[$key] = $value;

            }

        }

        try{
            $ret_ins = $this->db->insert( 'zform_'.$form_id, $final_array );
            
            if(!$ret_ins){
                $err_msg .= $this->db->_error_message();
                $this->form_results_model->update_mobile_activity($activity_inserted_id,array('error'=>$err_msg));
    //            $jsone_array = array (
    //                        'error' => $err_msg
    //            );
    //            echo json_encode ( $jsone_array );
                exit();
            }
            $form_result_id_new = $this->db->insert_id();
        }catch (Exception $e) {
                $this->form_results_model->update_mobile_activity($activity_inserted_id,array('error'=>$e->message()));
                echo json_encode($jsone_array);
                exit;
        }
                    
              
        
        if (!empty($sub_table_record)) {
            foreach ($sub_table_record as $sb_key => $sb_value) {
                $subtable_name = 'zform_' . $form_id . '_' . $sb_key;
                foreach ($sb_value as $sub_array) {
                    $sub_comon_fields = array();
                    foreach ($sub_array as $fild_key => $filds) {
                        $sub_fild_ary = array(
                            $fild_key => $filds
                        );
                        $sub_comon_fields = array_merge($sub_comon_fields, $sub_fild_ary);
                    }
                    $sub_comon_ary = array(
                        'form_id' => $form_id,
                        'zform_result_id' => $form_result_id_new
                    );
                    $sub_comon_fields = array_merge($sub_comon_fields, $sub_comon_ary);
                    $this->db->insert($subtable_name, $sub_comon_fields);
                }
            }
        }

        $image_array_post = array();
        if (!empty($images_array)) {
            foreach ($images_array as $image_path) {

                $add_images = array(
                    'zform_result_id' => $form_result_id_new,
                    'form_id' => $form_id,
                    'image' => $image_path ['image']
                );
                if(strpos($image_path['title'], $form_info['security_key']) !== FALSE){
                    $add_images ['title'] = urldecode(base64_decode(str_replace($form_info ['security_key'], '', $image_path['title'])));
                } else {
                    $add_images ['title'] = urldecode($image_path ['title']);
                }
                $this->db->insert('zform_images', $add_images);
                $add_images['image'] = get_image_path($image_path ['image']);
                $image_array_post [] = $add_images;
            }
        } else {
            $add_images = array(
                'image' => '',
                'title' => ''
            );
        }

        // Send this record to other domains also
        $post_url = '';
        if (!empty($form_info ['post_url'])) {
            $post_url = $form_info ['post_url'];
        } else if (!empty($form_info ['fv_post_url'])) {
            $post_url = $form_info ['fv_post_url'];
        }

        // this code is used for sending record to other domain
        if ($post_url) {
            if ($form_id == '4575' && strpos($_SERVER ['SERVER_NAME'], 'dataplug.itu') !== false) {
                $tempary = array(
                    'imei_no' => $imei_no,
                    'image_url' => $add_images ['image'],
                    'image_title' => $add_images ['title'],
                    'location' => $location,
                    'form_id' => $form_id,
                    'remote_record_id' => $form_result_id_new,
                    'security_key' => $form_info ['security_key']
                );

                $record11 = array_merge($final_array, $tempary);
                $fields_string = '';

                foreach ($record11 as $rec_key => $rec_val) {
                    $fields_string .= $rec_key . "=" . $rec_val . "&";
                }
                $fields_string = substr($fields_string, 0, - 1);
                // $data_post_json = json_encode($record11);
                $urlpost = urlencode($fields_string);
                $post_url = urlencode($post_url);
                $response = post_record_dotnet($post_url, $urlpost);

                if ($response ['status'] == true) {
                    $oldrecarray = array(
                        'post_status' => 'yes'
                    );
                    $this->db->where('id', $form_result_id_new);
                    $this->db->update('zform_4575', $oldrecarray);
                }
            } else {
                $tempary = array(
                    'imei_no' => $imei_no,
                    'image' => $image_array_post,
                    'location' => $location,
                    'form_id' => $form_id,
                    'remote_record_id' => $form_result_id_new,
                    'security_key' => $form_info ['security_key']
                );
                $record11 = array_merge($final_array, $tempary);
                $data_post_json = json_encode($record11);
                $urlpost = urlencode($data_post_json);
                $post_url = urlencode($post_url);
                $res = post_record($post_url, $urlpost);

                if ($res) {
                    $oldrecarray = array(
                        'post_status' => 'yes'
                    );
                    $this->db->where('id', $form_result_id_new);
                    $this->db->update('zform_' . $form_id, $oldrecarray);
                }
            }
        }

        // Just for DataPlug
        if (strpos($_SERVER ['SERVER_NAME'], 'dataplug.itu') !== false) {
            // Custom work for specific application
            if ($app_id == '3866') {
                app3866($app_id, $imei_no, $final_array);
            }
            if ($app_id == '3882' || $app_id == '3883') {
                // app3883($app_id,$imei_no,$dataresultnew1);
            }
        }
        //$this->form_results_model->update_mobile_activity($activity_inserted_id,array('error'=>'submitted'));
        $this->form_results_model->remove_mobile_activity($activity_inserted_id);
        $jsone_array = array(
            'success' => 'Record submitted successfully!.'
        );
        echo json_encode($jsone_array);
        exit();
    }
    public function saverecordscron() {
        //ob_get_level();
        //ob_start();
        ini_set ( 'memory_limit', '-1' );
        $unsent_activity_array = $this->db->order_by('id', 'ASC')->get_where('mobile_activity_log',array(),100)->result_array();
        //echo count($unsent_activity_array);
        //exit;
        $i = 0;
        foreach ($unsent_activity_array as $activity) {
            $i++;
//            
//            echo $i . ' = Processing....<br />';
//            ob_flush();
//        flush();
//        sleep(2);
//            continue;
           
            $form_data = json_decode($activity['form_data']);
            $images_array = json_decode($activity['form_images'],true);
            $imei_no = $activity['imei_no'];
            $location = $activity['location'];
            $dateTime_device = $activity['dateTime'];
            $time_source = $activity['time_source'];
            $location_source = $activity['location_source'];
            $version_name = $activity['version_name'];
            $created_datetime = $activity['created_datetime'];
            $security_key = true;
            $form_id = $activity['form_id'];
            $activity_inserted_id = $activity['id'];

            //$activity_datetime = date('Y-m-d H:i:s', strtotime($_REQUEST ['dateTime']));
            if (strpos($dateTime_device, ':') < 12) {
                $pattern = '#(\d+):(\d+):(\d+) (\d+):(\d+):(\d+)#';
                $replacement = '$1-$2-$3 $4:$5:$6';
                $activity_datetime = preg_replace($pattern, $replacement, $dateTime_device);
                $activity_datetime = date('Y-m-d H:i:s', strtotime($activity_datetime));
            } else if (strpos($dateTime_device, '/') < 12) {
                $pattern = '#(\d+)/(\d+)/(\d+) (\d+):(\d+):(\d+)#';
                $replacement = '$1-$2-$3 $4:$5:$6';
                $activity_datetime = preg_replace($pattern, $replacement, $dateTime_device);
                $activity_datetime = date('Y-m-d H:i:s', strtotime($activity_datetime));
            } else {
                $activity_datetime = date('Y-m-d H:i:s', strtotime($activity['dateTime']));
            }
            
            
            $captions_images = array();
            $sub_table_record = array();
            $record = array();
            

            //If form removed but user not update his application
            $form_info = $this->form_model->get_form($form_id);
            $app_id = $form_info ['app_id'];
            //$security_key = $form_info ['security_key'];


//print_r($form_data);
            //parsing and decripting data
            $take_picture = '';
            foreach ($form_data as $key => $v) {
                $cap_first = explode('-', $key);
                if (is_array($v)) {
                    $subtable_name = 'zform_' . $form_id . '_' . $key;
                    if (is_table_exist($subtable_name)) {
                        foreach ($v as $varray) {
                            $element_value = explode('&', $varray);
                            $sub_record = array();
                            foreach ($element_value as $sep_element_value) {
                                if ($sep_element_value != '') {
                                    $element_value_sub = explode(':', $sep_element_value);
                                    $temp_rry = array(
                                        $element_value_sub [0] => $element_value_sub [1]
                                    );
                                    $sub_record = array_merge($sub_record, $temp_rry);
                                }
                            }
                            $sub_table_record [$key] [] = $sub_record;
                        }
                        $record[$key] = 'SHOW RECORDS';
                    }
                } else if ($cap_first [0] == 'caption') {
                    $tempary_cap = array(
                        $key => $v
                    );
                    $captions_images = array_merge($captions_images, $tempary_cap);
                } elseif ($key == 'form_id' || $key == 'security_key' || $key == "dateTime" || $key == "landing_page" || $key == "is_take_picture" || $key == 'form_icon_name') {
                    
                } else {

                if(strpos($v, $form_info['security_key']) !== FALSE){
                    $vdcode = urldecode(base64_decode(str_replace($form_info['security_key'], '', $v)));
                }
                else {
                    $vdcode = urldecode($v);
                }
                    $tempary = array(
                        $key => $vdcode
                    );
                    $record = array_merge($record, $tempary);
                }
            }

            //adding image part here


            $err_msg = '';
            $uc_name = '';
            $town_name = '';
            $district_name = '';

            if ($location != '') {
                $uc = $this->getUcName($location); // Get UC name against location
                if ($uc) {
                    $uc_name = strip_tags($uc);
                } else {
                    $err_msg.="UC api return null, ";
                }

                $town = $this->getTownName($location); // Get Town name against location
                if ($town) {
                    $town_name = strip_tags($town);
                } else {
                    $err_msg.="TOWN api return null, ";
                }

                $district = $this->getDistrictName($location); // Get Town name against location
                if ($district) {
                    $district_name = strip_tags($district);
                } else {
                    $err_msg.="District api return null, ";
                }
            }

            $dataresultnew = array(
                'form_id' => $form_id,
                'imei_no' => $imei_no,
                'location' => $location,
                'uc_name' => $uc_name,
                'town_name' => $town_name,
                'district_name' => $district_name,
                'is_deleted' => '0',
                'version_name' => $version_name,
                'location_source' => $location_source,
                'time_source' => $time_source,
                'activity_datetime' => $activity_datetime,
                'created_datetime' => $created_datetime
            );
            $dataresultnew1 = array_merge($dataresultnew, $record);



            //Adding new field if not exist
            $this->load->dbforge();
            $fields_list = $this->db->list_fields('zform_' . $form_id);
            $fields_list = array_map('strtolower', $fields_list);
            $after_field = $fields_list[count($fields_list) - 13];
            foreach ($dataresultnew1 as $element => $ele_val) {
                if ($element != '') {
                    $element = str_replace('[]', '', $element);
                    if (!(in_array(strtolower($element), $fields_list))) {
                        $fields_count = $this->db->list_fields('zform_' . $form_id);
                        $fields_count = array_map('strtolower', $fields_count);
                        if (count($fields_count) < 90) {
                            $field = array($element => array('type' => 'VARCHAR', 'constraint' => 200, 'NULL' => TRUE));
                            $this->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                        } else {
                            $field = array($element => array('type' => 'TEXT', 'NULL' => TRUE));
                            $this->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                        }
                        $after_field = $element;
                    }
                }
            }


            $this->form_results_model->update_mobile_activity($activity_inserted_id, array('form_data_decoded' => json_encode($dataresultnew1)));
            $ret_ins = $this->db->insert('zform_' . $form_id, $dataresultnew1);
            $form_result_id_new = $this->db->insert_id();
            if (!$ret_ins) {
                $err_msg .= 'Record Not Submitted. ' . $this->db->_error_message() . '.                         "Please Refresh your application"';
                $this->form_results_model->update_mobile_activity($activity_inserted_id, array('error' => $err_msg));

                continue;
            }

            if (!empty($sub_table_record)) {
                foreach ($sub_table_record as $sb_key => $sb_value) {
                    $subtable_name = 'zform_' . $form_id . '_' . $sb_key;
                    foreach ($sb_value as $sub_array) {
                        $sub_comon_fields = array();
                        foreach ($sub_array as $fild_key => $filds) {
                            $sub_fild_ary = array(
                                $fild_key => $filds
                            );
                            $sub_comon_fields = array_merge($sub_comon_fields, $sub_fild_ary);
                        }
                        $sub_comon_ary = array(
                            'form_id' => $form_id,
                            'zform_result_id' => $form_result_id_new
                        );
                        $sub_comon_fields = array_merge($sub_comon_fields, $sub_comon_ary);
                        $this->db->insert($subtable_name, $sub_comon_fields);
                    }
                }
            }

            $image_array_post = array();
            if (!empty($images_array)) {
                foreach ($images_array as $image_path) {

                    $add_images = array(
                        'zform_result_id' => $form_result_id_new,
                        'form_id' => $form_id,
                        'image' => $image_path['image']
                    );
                    if(strpos($image_path ['title'], $form_info['security_key']) !== FALSE){
                        $add_images ['title'] = urldecode(base64_decode(str_replace($form_info ['security_key'], '', $image_path ['title'])));
                    } else {
                        $add_images ['title'] = urldecode($image_path ['title']);
                    }
                    $this->db->insert('zform_images', $add_images);
                    $image_array_post [] = $add_images;
                }
            } else {
                $add_images = array(
                    'image' => '',
                    'title' => ''
                );
            }

            // Send this record to other domains also
            $post_url = '';
            if (!empty($form_info ['post_url'])) {
                $post_url = $form_info ['post_url'];
            } else if (!empty($form_info ['fv_post_url'])) {
                $post_url = $form_info ['fv_post_url'];
            }

            // this code is used for sending record to other domain
            if ($post_url) {
                    $tempary = array(
                        'imei_no' => $imei_no,
                        'image' => $image_array_post,
                        'location' => $location,
                        'form_id' => $form_id,
                        'remote_record_id' => $form_result_id_new,
                        'security_key' => $form_info ['security_key']
                    );
                    $record11 = array_merge($dataresultnew1, $tempary);
                    $data_post_json = json_encode($record11);
                    $urlpost = urlencode($data_post_json);
                    $post_url = urlencode($post_url);
                    $res = post_record($post_url, $urlpost);

                    if ($res) {
                        $oldrecarray = array(
                            'post_status' => 'yes'
                        );
                        $this->db->where('id', $form_result_id_new);
                        $this->db->update('zform_' . $form_id, $oldrecarray);
                    }
                
            }
            $this->form_results_model->remove_mobile_activity($activity_inserted_id);
            echo $i . ' = Completed<br />';
//            ob_flush();
//            flush();
//            ob_end_flush();
        }//end of main loop
    //ob_end_flush();
        
    }

    public function saverecordsharzindagi() {
        $form_data = json_decode($_REQUEST['form_data'],true);
        $form_id=$form_data['form_id'];
        $imei_no=$form_data['imei_no'];
        $location=$form_data['location'];
        $location_source=$form_data['location_source'];
        $time_source=$form_data['time_source'];
        $activity_datetime=$form_data['activity_datetime'];
        
        $activity_temp = array(
            'app_id' => '9',
            'form_id' => $form_id,
            'form_data' => $_REQUEST['form_data'],
            'imei_no' => $imei_no,
            'location' => $location,
            'dateTime' => $activity_datetime,
            'time_source' => $time_source,
            'location_source' => $location_source,
            'version_name' => 'evaccs3',
            'form_data_decoded'=>  json_encode($_REQUEST['form_data']),
            'error'=>'submitted'
            );
        $this->form_results_model->save_mobile_activity($activity_temp);
        
        

        $captions_images = array();
        $sub_table_record = array();
        $record = array();

        $security_key = false;
        if (isset($_REQUEST ['security_key'])) {
            $security_key = $_REQUEST ['security_key'];
        }
        $created_datetime = date('Y-m-d H:i:s');
        $str = '';

        //get security key from form and check security is valid or not???
        $query=$this->db->query("select security_key
                                    from form
                                    where id ='$form_id'");
        $security_key_result = $query->row_array();
        $saved_security_key=$security_key_result['security_key'];
        if($security_key != $saved_security_key){
            $jsone_array = array(
                'error' => 'Security key does not match'
            );
            echo json_encode($jsone_array);
            exit();
        }

        //Stop activity saving if already saved
        $activity_aready_exist = $this->db->get_where('zform_'.$form_id, array('form_id' => $form_id, 'imei_no' => $imei_no,'activity_datetime' => $activity_datetime))->row_array();
        if ($activity_aready_exist) {
            $jsone_array = array(
                'success' => 'This activity already submitted.'
            );
            echo json_encode($jsone_array);
            exit();
        }

        //If form removed but user not update his application
        $form_info = $this->form_model->get_form($form_id);
        if (!$form_info) {
            $jsone_array = array(
                'error' => 'This form has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }
        $app_id = $form_info ['app_id'];
        // Temprary block the record sending
        $app_general_setting = get_app_general_settings($app_id);
        if (isset($app_general_setting->record_stop_sending) && $app_general_setting->record_stop_sending == 1) {
            $error_message = "Record receiving service currently not available. Please Try later";
            if ($app_general_setting->message_stop_sending_record != '') {
                $error_message = $app_general_setting->message_stop_sending_record;
            }
            $jsone_array = array(
                'error' => $error_message
            );
            echo json_encode($jsone_array);
            exit();
        }

        $app_info = $this->app_model->get_app($app_id);
        if (!$app_info) {
            $jsone_array = array(
                'error' => 'This application has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }

        $authorized = $this->app_model->appuser_imei_already_exist($imei_no, $app_id);
        if ($app_general_setting->only_authorized == 1 && !$authorized) {
            $jsone_array = array(
                'error' => 'This IMEI# not authorized to sumbit record'
            );
            echo json_encode($jsone_array);
            exit();
        }
       
        $ret_ins = $this->db->insert( 'zform_'.$form_id, $form_data );
        $form_result_id_new = $this->db->insert_id();

        $images_array = array();
        $site_settings = $this->site_model->get_settings('1');
        $s3_access_key = $site_settings ['s3_access_key'];
        $s3_secret_key = $site_settings ['s3_secret_key'];
        $s3_bucket = $site_settings ['s3_bucket'];
        $image_title = $form_info['name'];
        if ($s3_access_key != '' && $s3_secret_key != '' && $s3_bucket != '') {
            $bucket_name = $s3_bucket;
            // instantiate the class
            $this->load->library('S3');
            $s3 = new S3($s3_access_key, $s3_secret_key);
            $imgName = '';
            for ($file = 1; $file <= 5; $file ++) {
                if (isset($_FILES ['picture_file_' . $file])) {
                    $rand_name = random_string('alnum', 10);
                    $imgName = $form_id . "_" . $rand_name . '.jpg';
                    $fileTempName = $_FILES ['picture_file_' . $file] ['tmp_name'];
                    
                    
                    // move the file
                    if ($s3->putObjectFile($fileTempName, $bucket_name, $imgName, S3::ACL_PUBLIC_READ)) {
                        $imgName = 'http://' . $bucket_name . '.s3.amazonaws.com/' . $imgName;
                        $temp_array = array(
                            'image' => $imgName,
                            'title' => $image_title
                        );
                        $images_array [] = $temp_array;
                    }
                    else{
                        
                        if (isset($_FILES ['picture_file_' . $file])) {
                            $path = './assets/images/data/form-data/';
                            $config ['upload_path'] = $path;
                            $config ['allowed_types'] = 'png|gif|jpg|jpeg';
                            $config ['max_size'] = '4000';
                            $config ['encrypt_name'] = true;
                            $config ['file_name'] = $imgName;


                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('picture_file_' . $file)) {
                                $error_before = array(
                                    'error' => $this->upload->display_errors()
                                );
                            } else {
                                $data = array(
                                    'upload_data' => $this->upload->data()
                                );
                                $error_before = array(
                                    'file' => $data ['upload_data'] ['file_name']
                                );
                                $imgName = base_url() . 'assets/images/data/form-data/' . $data ['upload_data'] ['file_name'];
                                $temp_array = array(
                                    'image' => $imgName,
                                    'title' => $image_title
                                );
                                $images_array [] = $temp_array;
                            }
                        }
                        
                    }
                }
            }
        } else {
            for ($file = 1; $file <= 5; $file ++) {
                if (isset($_FILES ['picture_file_' . $file])) {
                    $rand_name = random_string('alnum', 10);
                    $imgName = $form_id . "_" . $rand_name . '.jpg';
                    $path = './assets/images/data/form-data/';
                    $config ['upload_path'] = $path;
                    $config ['allowed_types'] = 'png|gif|jpg|jpeg';
                    $config ['max_size'] = '4000';
                    $config ['encrypt_name'] = true;
                    $config ['file_name'] = $imgName;

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('picture_file_' . $file)) {
                        $error_before = array(
                            'error' => $this->upload->display_errors()
                        );
                    } else {
                        $data = array(
                            'upload_data' => $this->upload->data()
                        );
                        $error_before = array(
                            'file' => $data ['upload_data'] ['file_name']
                        );
                        $imgName = base_url() . 'assets/images/data/form-data/' . $data ['upload_data'] ['file_name'];
                        $temp_array = array(
                            'image' => $imgName,
                            'title' => $image_title
                        );
                        $images_array [] = $temp_array;
                    }
                }
            }
        }
        
        $image_array_post = array();
        if (!empty($images_array)) {
            foreach ($images_array as $image_path) {

                $add_images = array(
                    'zform_result_id' => $form_result_id_new,
                    'form_id' => $form_id,
                    'image' => $image_path ['image'],
                    'title' => $image_path ['title']
                );
                $this->db->insert('zform_images', $add_images);
                $image_array_post [] = $add_images;
            }
        } 
        
        

        $jsone_array = array(
            'success' => 'Record submitted successfully. '
        );
        echo json_encode($jsone_array);
        exit();
    }

    /**
     * This function is used for saving the record which sent from android application
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function saverecordsweb() {
        $form_data = json_decode($_REQUEST ['form_data']);

        $session_data = $this->session->userdata('web_logged_in');
        $imei_no = $session_data['login_user'];

        $location = '4.3423434,34.53432423';//$_REQUEST ['location'];
        $activity_datetime = date('Y-m-d H:i:s');

        $time_source = 'web';
        $location_source = 'web';
        if (isset($_REQUEST ['time_source'])) {
            $time_source = $_REQUEST ['time_source'];
        }
        if (isset($_REQUEST ['location_source'])) {
            $location_source = $_REQUEST ['location_source'];
        }
        $version_name = 'web';
        $captions_images = array();
        $sub_table_record = array();
        $record = array();
        $form_id = '';
        $security_key = false;
        $created_datetime = date('Y-m-d H:i:s');
        $str = '';
        foreach ($form_data as $key1 => $v1) {
            if ($key1 == 'security_key') {
                $security_key = true;
            } else if ($key1 == 'form_id') {
                $form_id = $v1;
            }
        }
        
        //Stop activity saving if already saved
        $activity_aready_exist = $this->db->get_where('zform_'.$form_id, array('form_id' => $form_id, 'imei_no' => $imei_no,'activity_datetime' => $activity_datetime))->row_array();
        if ($activity_aready_exist) {
            $jsone_array = array(
                'success' => 'This activity already submitted.'
            );
            echo json_encode($jsone_array);
            exit();
        }
        
        //If form removed but user not update his application
        $form_info = $this->form_model->get_form($form_id);
        if (!$form_info) {
            $jsone_array = array(
                'error' => 'This form has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }
        $app_id = $form_info ['app_id'];
        // Temprary block the record sending
        $app_general_setting = get_app_general_settings($app_id);
        if (isset($app_general_setting->record_stop_sending) && $app_general_setting->record_stop_sending == 1) {
            $error_message = "Record receiving service currently not available. Please Try later";
            if ($app_general_setting->message_stop_sending_record != '') {
                $error_message = $app_general_setting->message_stop_sending_record;
            }
            $jsone_array = array(
                'error' => $error_message
            );
            echo json_encode($jsone_array);
            exit();
        }

        $app_info = $this->app_model->get_app($app_id);
        if (!$app_info) {
            $jsone_array = array(
                'error' => 'This application has been removed from server.'
            );
            echo json_encode($jsone_array);
            exit();
        }

/*        $authorized = $this->app_model->appuser_imei_already_exist($imei_no, $app_id);
        if ($app_general_setting->only_authorized == 1 && !$authorized) {
            $jsone_array = array(
                'error' => 'You are not authorized'
            );
            echo json_encode($jsone_array);
            exit();
        }*/
        $take_picture = '';
        foreach ($form_data as $key => $v) {
            $cap_first = explode('-', $key);
            if (is_array($v)) {
                $subtable_name = 'zform_' . $form_id . '_' . $key;
                if (is_table_exist($subtable_name)) {
                    foreach ($v as $varray) {
                        $element_value = explode('&', $varray);
                        $sub_record = array();
                        foreach ($element_value as $sep_element_value) {
                            if ($sep_element_value != '') {
                                $element_value_sub = explode(':', $sep_element_value);
                                $temp_rry = array(
                                    $element_value_sub [0] => $element_value_sub [1]
                                );
                                $sub_record = array_merge($sub_record, $temp_rry);
                            }
                        }
                        $sub_table_record [$key] [] = $sub_record;
                    }
                    $record[$key] = 'SHOW RECORDS';
                }
            } else if ($cap_first [0] == 'caption') {
                $tempary_cap = array(
                    $key => $v
                );
                $captions_images = array_merge($captions_images, $tempary_cap);
            } elseif ($key == 'form_id' || $key == 'security_key' || $key == "dateTime" || $key == "landing_page" || $key == "is_take_picture" || $key == 'form_icon_name') {
                
            } else {

                if ($security_key) {
                    $vdcode = urldecode(base64_decode(str_replace($form_info ['security_key'], '', $v)));
                } else {
                    $vdcode = urldecode($v);
                }
                $tempary = array(
                    $key => $vdcode
                );
                $record = array_merge($record, $tempary);
            }
        }
        $warning_message = '';
        $app_map_view_setting = get_map_view_settings($app_id);
        if(isset($app_map_view_setting->map_distance_mapping) && $app_map_view_setting->map_distance_mapping)//if Distance maping on then call this block
        {
                    $saved_distance=500;
                    if($app_map_view_setting->distance !== ''){//if distance not given then default distance will assign as 500
                        $saved_distance=$app_map_view_setting->distance;
                    }
                    $matching_value = $record[$app_map_view_setting->matching_field]; //this field name getting from setting and getting value from received json
                    $kml_poligon_rec = $this->db->get_where('kml_poligon', array('app_id' => $app_id, 'type' => 'distence','matching_value' => $matching_value))->row_array();
                    
                    if(!empty($kml_poligon_rec)){
                        $lat_long = explode(',', $location);//Received location from mobile device
                        $distance_from_center = lan_lng_distance($kml_poligon_rec['latitude'], $kml_poligon_rec['longitude'],$lat_long[0], $lat_long[1]);
                        if($distance_from_center > $saved_distance)
                        {
                            $warning_message  = 'Your location mismatched. ';
                        }
                        else{
                            $warning_message  = 'You are on right location' ;
                        }
                    }
        }



        $images_array = array();
        $site_settings = $this->site_model->get_settings('1');
        $s3_access_key = $site_settings ['s3_access_key'];
        $s3_secret_key = $site_settings ['s3_secret_key'];
        $s3_bucket = $site_settings ['s3_bucket'];
        if ($s3_access_key != '' && $s3_secret_key != '' && $s3_bucket != '') {
            $bucket_name = $s3_bucket;
            // instantiate the class
            $this->load->library('S3');
            $s3 = new S3($s3_access_key, $s3_secret_key);
            $imgName = '';
            for ($file = 1; $file <= 5; $file ++) {
                if (isset($_FILES ['picture_file_' . $file])) {
                    $rand_name = random_string('alnum', 10);
                    $imgName = $form_id . "_" . $rand_name . '.jpg';
                    $fileTempName = $_FILES ['picture_file_' . $file] ['tmp_name'];
                    $title_id = $_FILES ['picture_file_' . $file] ['name'];
                    $title_id = explode('-', $title_id);
                    $image_title = '';
                    if (array_key_exists('1', $title_id)) {
                        if (isset($captions_images ["caption-$title_id[1]"])) {
                            $image_title = urldecode($captions_images ["caption-$title_id[1]"]);
                        }
                    }
                    // move the file
                    if ($s3->putObjectFile($fileTempName, $bucket_name, $imgName, S3::ACL_PUBLIC_READ)) {
                        $imgName = 'http://' . $bucket_name . '.s3.amazonaws.com/' . $imgName;
                        $temp_array = array(
                            'image' => $imgName,
                            'title' => $image_title
                        );
                        $images_array [] = $temp_array;
                    }
                }
            }
        } else {
            for ($file = 1; $file <= 5; $file ++) {
                if (isset($_FILES ['picture_file_' . $file])) {
                    $rand_name = random_string('alnum', 10);
                    $imgName = $form_id . "_" . $rand_name . '.jpg';
                    //$path = './assets/images/data/form-data/';
                    $path = NFS_IMAGE_PATH.'/app_id_'.$app_id;
                    @mkdir($path);
                    $config ['upload_path'] = $path;
                    $config ['allowed_types'] = 'png|gif|jpg|jpeg';
                    $config ['max_size'] = '4000';
                    $config ['encrypt_name'] = true;
                    $config ['file_name'] = $imgName;

                    $title_id = $_FILES ['picture_file_' . $file] ['name'];
                    $title_id = explode('-', $title_id);
                    $image_title = '';
                    if (array_key_exists('1', $title_id)) {
                        if (isset($captions_images ["caption-$title_id[1]"])) {
                            $image_title = urldecode($captions_images ["caption-$title_id[1]"]);
                        }
                    }

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('picture_file_' . $file)) {
                        $error_before = array(
                            'error' => $this->upload->display_errors()
                        );
                    } else {
                        $data = array(
                            'upload_data' => $this->upload->data()
                        );
                        $error_before = array(
                            'file' => $data ['upload_data'] ['file_name']
                        );
                        $imgName = NFS_IMAGE_PATH.'/app_id_'.$app_id .'/'. $data ['upload_data'] ['file_name'];
                        $temp_array = array(
                            'image' => $imgName,
                            'title' => $image_title
                        );
                        $images_array [] = $temp_array;
                    }
                }
            }
        }

        $uc_name = '';
        $town_name = '';
        $district_name = '';

        if ($location != '') {
            $uc = $this->getUcName($location); // Get UC name against location
            if ($uc) {
                $uc_name = strip_tags($uc);
            }

            $town = $this->getTownName($location); // Get Town name against location
            if ($town) {
                $town_name = strip_tags($town);
            }

            $district = $this->getDistrictName($location); // Get Town name against location
            if ($district) {
                $district_name = strip_tags($district);
            }
        }

        $dataresultnew = array(
            'form_id' => $form_id,
            'imei_no' => $imei_no,
            'location' => $location,
            'uc_name' => $uc_name,
            'town_name' => $town_name,
            'district_name' => $district_name,
            'is_deleted' => '0',
            'version_name' => $version_name,
            'location_source' => $location_source,
            'time_source' => $time_source,
            'activity_datetime' => $activity_datetime,
            'created_datetime' => $created_datetime
        );
        $dataresultnew1 = array_merge($dataresultnew, $record);
        
        if ($app_id == '5316') {

            ///////////////////////////////////////////////////////////////////////
            ///////////////////////// HASSAN ANWAR     ////////////////////////////
            ///////////////////////////////////////////////////////////////////////

            $multiChoices = $this->db->query("Select * from form_multiselectFieldLabels where formId='$form_id'");
            foreach($multiChoices->result() as $row)
            {
                $fd=(array) $dataresultnew1;

                $label=$row->fieldLabel;
                $choicesSent= $dataresultnew1[$label];
                $choicesSent=explode(',',$choicesSent);

                foreach($choicesSent as $choice)
                {
                    $choice=str_replace(' ', '_', $choice);
                    $dataresultnew1[$label.'_'.$choice]='1';
                }

            }


            ///////////////////////////////////////////////////////////////////////
            ///////////////////////// HASSAN ANWAR     ////////////////////////////
            ///////////////////////////////////////////////////////////////////////
        }
        $ret_ins = $this->db->insert( 'zform_'.$form_id, $dataresultnew1 );
        $form_result_id_new = $this->db->insert_id();
        if(!$ret_ins){
            //Mobile activity log - Start
            $mobile_log = array(
                'imei_no' => $imei_no,
                'form_id' => $form_id,
                'app_id' => $app_id,
                'record' => json_encode($dataresultnew1)
            );
            $this->db->insert( 'mobile_activity_log', $mobile_log);
            //Mobile activity log - End
            $err_msg = 'Record Not Submitted. '.$this->db->_error_message().'.                         "Please Refresh your application"';
            $jsone_array = array (
                        'error' => $err_msg
            );
            echo json_encode ( $jsone_array );
            exit();
        }
                    
                
        
        if (!empty($sub_table_record)) {
            foreach ($sub_table_record as $sb_key => $sb_value) {
                $subtable_name = 'zform_' . $form_id . '_' . $sb_key;
                foreach ($sb_value as $sub_array) {
                    $sub_comon_fields = array();
                    foreach ($sub_array as $fild_key => $filds) {
                        $sub_fild_ary = array(
                            $fild_key => $filds
                        );
                        $sub_comon_fields = array_merge($sub_comon_fields, $sub_fild_ary);
                    }
                    $sub_comon_ary = array(
                        'form_id' => $form_id,
                        'zform_result_id' => $form_result_id_new
                    );
                    $sub_comon_fields = array_merge($sub_comon_fields, $sub_comon_ary);
                    $this->db->insert($subtable_name, $sub_comon_fields);
                }
            }
        }

        $image_array_post = array();
        if (!empty($images_array)) {
            foreach ($images_array as $image_path) {

                $add_images = array(
                    'zform_result_id' => $form_result_id_new,
                    'form_id' => $form_id,
                    'image' => $image_path ['image']
                );
                if ($security_key) {
                    $add_images ['title'] = urldecode(base64_decode(str_replace($form_info ['security_key'], '', $image_path ['title'])));
                } else {
                    $add_images ['title'] = urldecode($image_path ['title']);
                }
                $this->db->insert('zform_images', $add_images);
                $image_array_post [] = $add_images;
            }
        } else {
            $add_images = array(
                'image' => '',
                'title' => ''
            );
        }

        // Send this record to other domains also
        $post_url = '';
        if (!empty($form_info ['post_url'])) {
            $post_url = $form_info ['post_url'];
        } else if (!empty($form_info ['fv_post_url'])) {
            $post_url = $form_info ['fv_post_url'];
        }

        // this code is used for sending record to other domain
        if ($post_url) {
            if ($form_id == '4575' && 
                strpos($_SERVER ['SERVER_NAME'], 'dataplug.itu') !== false) {
                $tempary = array(
                    'imei_no' => $imei_no,
                    'image_url' => $add_images ['image'],
                    'image_title' => $add_images ['title'],
                    'location' => $location,
                    'form_id' => $form_id,
                    'remote_record_id' => $form_result_id_new,
                    'security_key' => $form_info ['security_key']
                );

                $record11 = array_merge($dataresultnew1, $tempary);
                $fields_string = '';

                foreach ($record11 as $rec_key => $rec_val) {
                    $fields_string .= $rec_key . "=" . $rec_val . "&";
                }
                $fields_string = substr($fields_string, 0, - 1);
                // $data_post_json = json_encode($record11);
                $urlpost = urlencode($fields_string);
                $post_url = urlencode($post_url);
                $response = post_record_dotnet($post_url, $urlpost);

                if ($response ['status'] == true) {
                    $oldrecarray = array(
                        'post_status' => 'yes'
                    );
                    $this->db->where('id', $form_result_id_new);
                    $this->db->update('zform_4575', $oldrecarray);
                }
            } else {
                $tempary = array(
                    'imei_no' => $imei_no,
                    'image' => $image_array_post,
                    'location' => $location,
                    'form_id' => $form_id,
                    'remote_record_id' => $form_result_id_new,
                    'security_key' => $form_info ['security_key']
                );
                $record11 = array_merge($dataresultnew1, $tempary);
                $data_post_json = json_encode($record11);
                $urlpost = urlencode($data_post_json);
                $post_url = urlencode($post_url);
                $res = post_record($post_url, $urlpost);

                if ($res) {
                    $oldrecarray = array(
                        'post_status' => 'yes'
                    );
                    $this->db->where('id', $form_result_id_new);
                    $this->db->update('zform_' . $form_id, $oldrecarray);
                }
            }
        }


        $jsone_array = array(
            'success' => 'Record submitted successfully. '.$warning_message
        );
        echo json_encode($jsone_array);
        exit();
    }

    



    /**
     * Get Union Councel name again given location using third party API
     * 
     * @return boolen or name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getUcName($location) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);

        $url = "http://ucfinder.herokuapp.com/"
        $url .= "ajax/region_finder.json?lat=$lat&long=$long";
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
    public function getTownName($location = null) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);
        $url = "http://ucfinder.herokuapp.com/"
        $url .= "ajax/town_finder.json?lat=$lat&long=$long";
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
    public function getDistrictName($location = null) {
        $loc = explode(',', $location);
        $lat = trim($loc [0]);
        $long = trim($loc [1]);
        $url = "http://ucfinder.herokuapp.com/";
        $url .= "ajax/distict_finder.json?lat=$lat&long=$long";
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

    /**
     * For call other domain url using CURL
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getOptions() {
        $url_domain = $this->input->post('url_cross_domain');
        $curl = curl_init($url_domain);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        echo $data = curl_exec($curl);
        exit();
    }

    /**
     * Create drowdown using CSV for form builder
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    // http://www.dataplug.itu.edu.pk/api/getoptionapi?api=1&secret=fdkj378jk3g
    // http://www.dataplug.itu.edu.pk/api/getoptionapi?api=2&secret=k4743mn7hjr
    public function getoptionapi() {
        $api = $this->input->get('api');
        $secret = $this->input->get('secret');
        $query = $this->db->query("SELECT * FROM api WHERE id = 
        '$api' AND secret_key='$secret'");
        $api_data = $query->row_array();
        if ($api_data) {
            $csv_file_name = $api_data ['file_name'];
            $parent_name = $this->input->get('parent_value');
            $parent_optional_name = 'parent_name_array';
            $child_name = $this->input->get('value');
            $file_path = "assets/data/$csv_file_name";
            $row = 1;
            $options = array();
            $existArray = array();
            $heading_row = array();
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 15000, ",")) !== FALSE) {
                    if ($row == 1) {
                        $heading_row = $data;
                        if ($parent_name != '') {
                            $parent_index = array_search($parent_name, $data);
                        }

                        $child_index = array_search($child_name, $data);
                        $row ++;
                    } else {
                        if ($parent_name != '') {
                            $parent_optional_name = trim($data [$parent_index]);
                        }
                        if (!isset($existArray [$parent_optional_name])) {
                            $existArray [$parent_optional_name] = array();
                        }
                        if (!in_array(strtolower(trim($data [$child_index])),
                            $existArray [$parent_optional_name])) {
                            array_push($existArray [$parent_optional_name],
                            strtolower(trim($data [$child_index])));
                            $option_parent_value = '';
                            if ($parent_name != '') {
                                $option_parent_value = trim($data [$parent_index]);
                            }
                            $option_display_value = $option_value = trim($data [$child_index]);
                            if(isset($heading_row[$child_index+1]) && 
                            $heading_row[$child_index+1]=='display_value_'.$heading_row[$child_index]){
                                $option_display_value = trim($data[$child_index+1]);
                            }
                            $record = array(
                                'id' => $row,
                                'value' => $option_value,
                                'display_value' => $option_display_value,
                                'parent_value' => $option_parent_value
                            );
                            $options ['options'] [] = $record;
                        }
                    }
                    $row ++;
                }
            }
            fclose($handle);
        } else {
            $options ['error'] = 'You are calling wrong api, Please contact with Administrator';
        }

        function cmpBySort($a, $b) {
            return $a ['value'] > $b ['value'];
        }
        $sort = $this->input->get('sort');
        if($sort != 'off'){
            usort($options ['options'], 'cmpBySort');
        }
        echo json_encode($options);
        exit();
    }

    /*
     * Api for sending data to
     * remote server
     * @author:ubaidcskiu@gmail.com
     */

    // URL : http://www.dataplug.itu.edu.pk/api/syncDataRemotely?app_id=1293&security_token=1ae473a61dbe13cb9ec199e9c2361956&last_date_stamp=
    public function syncDataRemotely() {
        if (isset($_REQUEST ['app_id']) && isset($_REQUEST ['last_date_stamp'])
            && isset($_REQUEST ['security_token'])) {
            $app_id = $_REQUEST ['app_id'];
            $last_date_stamp = $_REQUEST ['last_date_stamp'];
            $security_token = $_REQUEST ['security_token']; // 1ae473a61dbe13cb9ec199e9c2361956
            if ($security_token == md5($app_id . 'floodrelif')) {

                /**
                 * multiple form handling system statrs *
                 */
                $forms_list = array();
                $all_forms = $this->form_model->get_form_by_app($app_id);
                foreach ($all_forms as $forms) {
                    $forms_list [] = array(
                        'form_id' => $forms ['form_id'],
                        'form_name' => $forms ['form_name']
                    );
                }

                /**
                 * in case of post of form filters *
                 */
                $results = $this->form_results_model->syncDataFromRemoteServer($forms_list, $last_date_stamp);
                $results_count = count($results);
                $syn_data = array(
                    'data' => $results,
                    'result_counts' => $results_count
                );
                echo json_encode($syn_data);
            } else {
                $syn_data = array(
                    'data' => false
                );
                echo json_encode($syn_data);
            }
        } else {
            $syn_data = array(
                'data' => false
            );
            echo json_encode($syn_data);
        }
    }

    /*
     * Import Data form remort server using curl
     * based on remote applicatoin form_id
     * communicating with godk exportDataFormBased
     * @author:ubaidullah.balti@itu.edu.pk
     * http://www.dataplug.itu.edu.pk/api/importDataFormBased?local_form_id=1779&remote_form_id=280
     */

    public function importDataFormBased() {
        if (isset($_REQUEST ['local_form_id'])
            && isset($_REQUEST ['remote_form_id'])) {
            $local_form_id = $_REQUEST ['local_form_id'];
            $table_name = 'zform_' . $local_form_id;
            $remote_form_id = $_REQUEST ['remote_form_id'];
            $max_remote_id = 0;//$this->form_results_model->return_max_remote_id($table_name);
            if (isset($max_remote_id [0] ['max_remote_id'])) {
                $last_id = $max_remote_id [0] ['max_remote_id'];
            } else {
                $last_id = 0;
            }

            $url = "http://godk.itu.edu.pk/api/exportDataFormBased?";
            $url .= "form_id=$remote_form_id&last_id=$last_id";
            $ch = curl_init() or die("Cannot init");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $body = curl_exec($ch) or die("cannot execute");
            $results = json_decode($body, 1);
            $table_headers_array = array(
                'created_datetime'
            );

            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            foreach ($schema_list as $key => $value) {
                $header_value = $value ['COLUMN_NAME'];
                if ($header_value != 'created_datetime') {
                    if (!in_array($header_value, $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array(
                            $header_value
                                ));
                    }
                }
            }

            if ($results ['data']) {
                $records = $results ['data'];

                foreach ($records as $key => $rec) {
                    $formdata = array();
                    foreach ($table_headers_array as $column) {
                        if (key_exists($column, $rec)) {

                            $formdata = array_merge($formdata, array(
                                'form_id' => $local_form_id,
                                //'remote_id' => $rec ['remote_id'],
                                $column => $rec [$column]
                                    ));
                        }
                    }
                    if (isset($formdata)) {
                        $this->db->insert($table_name, $formdata);
                        $record_id = $this->db->insert_id();
                        if (count($rec['image'])>0) {
                            foreach ($rec['image'] as $img_key => $img_value) {
                                $record_image_data = array(
                                'zform_result_id' => $record_id,
                                'form_id' => $local_form_id,
                                'image' => $img_value['image'],
                                'title' => $img_value['title']
                            );
                            $this->db->insert('zform_images', $record_image_data);
                                
                            }
                            
                        }
                    }
                }
                echo 'Done';
                exit();
            } else {
                echo 'No Records';
                exit();
            }
        } else {
            echo 'Invalid Params';
            exit();
        }
    }

    //for hospital watch app
    public function hospitalwatchapi() {
        //if (isset($_REQUEST['app_id']) && isset($_REQUEST['last_date_stamp'])
        //&& isset($_REQUEST['security_token'])) {
        if (isset($_REQUEST['app_id'])) {
            $app_id = $_REQUEST['app_id'];
            $last_date_stamp = isset($_REQUEST['last_date_stamp']) ? $_REQUEST['last_date_stamp'] : '';
            $start_date_stamp = isset($_REQUEST['start_date_stamp']) ? $_REQUEST['start_date_stamp'] : '';

            $selected_app = $this->app_model->get_app($app_id);
            $app_name = $selected_app['name'];
            //            die(md5($app_name . $app_id));



            /** multiple form handling system statrs * */
            $final_result = array();
            $results_count = 0;
            $all_forms = $this->form_model->get_form_by_app($app_id);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
                /** in case of post of form filters * */
                $results = $this->form_results_model->syncDataFromRemoteServer($forms['form_id'], $last_date_stamp, $start_date_stamp);
                $results_count += count($results);
                $final_result = array_merge($final_result, $results);
            }

            $report_array = array();
            foreach ($final_result as $rec) {

                $district = $rec['District'];
                $hospital_name = $rec['Hospital_Name'];
                $desigination = $rec['Identify_Yourself'];

                if (!isset($report_array[$district])) {
                    $report_array[$district] = array();
                }
                if (!isset($report_array[$district][$hospital_name])) {
                    $report_array[$district][$hospital_name] = array();
                }
                if (!isset($report_array[$district][$hospital_name][$desigination])) {
                    $report_array[$district][$hospital_name][$desigination] = array();
                    $report_array[$district][$hospital_name][$desigination]['visits'] = 0;
                }
                $report_array[$district][$hospital_name][$desigination]['visits'] = $report_array[$district][$hospital_name][$desigination]['visits'] + 1;
            }


            $syn_data = array('data' => $report_array, 'total_visits' => $results_count);
            echo json_encode($syn_data);
        } else {
            $syn_data = array('data' => false);
            echo json_encode($syn_data);
        }
    }

    public function sendToRemoteServer() {
        ini_set('memory_limit', '-1');
        if (isset($_REQUEST ['app_id']) && isset($_REQUEST ['security_token'])) {
            $app_id = $_REQUEST ['app_id'];
            $from_date_stamp = "";
            if(isset($_REQUEST ['from_date_stamp']) && !empty($_REQUEST ['from_date_stamp'])){
                $pos = strpos($_REQUEST ['from_date_stamp'], ':');
                $from_date_stamp = $_REQUEST ['from_date_stamp'];
                if ($pos === false) {
                    $from_date_stamp = $_REQUEST ['from_date_stamp']." 00:00:00";
                }
            }
            $to_date_stamp = "";
            if(isset($_REQUEST ['to_date_stamp']) && !empty($_REQUEST ['to_date_stamp'])){
                $pos = strpos($_REQUEST ['to_date_stamp'], ':');
                $to_date_stamp = $_REQUEST ['to_date_stamp'];
                if ($pos === false) {
                    $to_date_stamp = $_REQUEST ['to_date_stamp']." 23:59:59";
                }
            }
            
            // $from_date_stamp = isset($_REQUEST ['from_date_stamp']) ? $_REQUEST ['from_date_stamp'] : '';
            // $to_date_stamp = isset($_REQUEST ['to_date_stamp']) ? $_REQUEST ['to_date_stamp'] : '';
            $imei_no = isset($_REQUEST ['imei_no']) ? $_REQUEST ['imei_no'] : null;
            $security_token = $_REQUEST ['security_token']; // 954223eaaec107c5d7965978c9665e64

            $selected_app = $this->app_model->get_app($app_id);
            $app_name = $selected_app ['name'];

            if ($security_token == md5($app_name . $app_id)) {
                $final_result = array();
                $results_count = 0;
                if( isset($_REQUEST ['form_id']) && !empty($_REQUEST ['form_id']) ){
                    $results = $this->form_results_model->syncDataFromRemoteServer($_REQUEST ['form_id'], $from_date_stamp, $to_date_stamp,$imei_no);
                    $results_count += count($results);
                    foreach ($results as $rec) {
                        $rec ['images'] = array();
                        if ($rec ['zimages'] != '') {
                            $ex_row = explode('#', $rec ['zimages']);
                            $im_ar = array();
                            foreach ($ex_row as $row) {
                                if ($row != '') {
                                    $ex_col = explode('|', $row);
                                    (isset($ex_col [0])) ? $image = $ex_col [0] : $image = '';
                                    (isset($ex_col [1])) ? $title = $ex_col [1] : $title = '';
                                    $im_ar [] = array(
                                        'image' => get_image_path($image),
                                        'title' => $title
                                    );
                                }
                            }
                            $rec ['images'] = $im_ar;
                        }
                            unset($rec ['zimages']);

                        //Adding sub tables in json format
                        $table_name = 'zform_'.$rec ['form_id'];
                        foreach ($rec as $rec_k => $rec_v) {
                            
                            if($rec_v == 'SHOW RECORDS'){
                                $sub_table = $table_name.'_'.$rec_k;
                                $query_str = "SELECT *
                                        FROM $sub_table st
                                        WHERE  st.zform_result_id =".$rec['id'];
                                        $query = $this->db->query($query_str);
                                        if($query->result_array())
                                            $rec[$rec_k] = json_encode($query->result_array());
                                        else
                                            $rec[$rec_k] = "";
                            }
                            
                        }
                        $final_result [] = $rec;
                        
                    }
                }else{
                    /**
                    * multiple form handling system statrs *
                    */
                    $all_forms = $this->form_model->get_form_by_app($app_id);
                    foreach ($all_forms as $forms) {
                
                        $forms_list [] = array(
                            'form_id' => $forms ['form_id'],
                            'form_name' => $forms ['form_name']
                        );
                        $results = $this->form_results_model->syncDataFromRemoteServer($forms ['form_id'], $from_date_stamp, $to_date_stamp,$imei_no);
                        $results_count += count($results);
                        foreach ($results as $rec) {
                            $rec['images'] = array();
                            if ($rec ['zimages'] != '') {
                                $ex_row = explode('#', $rec ['zimages']);
                                $im_ar = array();
                                foreach ($ex_row as $row) {
                                    if ($row != '') {
                                        $ex_col = explode('|', $row);
                                        (isset($ex_col [0])) ? $image = $ex_col [0] : $image = '';
                                        (isset($ex_col [1])) ? $title = $ex_col [1] : $title = '';
                                        $im_ar [] = array(
                                            'image' => get_image_path($image),
                                            'title' => $title
                                        );
                                    }
                                }
                                $rec ['images'] = $im_ar;
                            }
                                unset($rec ['zimages']);

                            //Adding sub tables in json format
                            $table_name = 'zform_'.$rec ['form_id'];
                            foreach ($rec as $rec_k => $rec_v) {
                                
                                if($rec_v == 'SHOW RECORDS'){
                                    $sub_table = $table_name.'_'.$rec_k;
                                    $query_str = "SELECT *
                                            FROM $sub_table st
                                            WHERE  st.zform_result_id =".$rec['id'];
                                            $query = $this->db->query($query_str);
                                            if($query->result_array())
                                                $rec[$rec_k] = json_encode($query->result_array());
                                            else
                                                $rec[$rec_k] = "";
                                }
                                
                            }
                            $final_result [] = $rec;
                            
                        }
                    
                    }
                }
                $syn_data = array(
                    'data' => $final_result,
                    'result_counts' => $results_count
                );
                echo json_encode($syn_data);
            } else {
                $syn_data = array(
                    'data' => false
                );
                echo json_encode($syn_data);
            }
        } else {
            $syn_data = array(
                'data' => false
            );
            echo json_encode($syn_data);
        }
    }

    public function update_application_general_settings() {
        $query = $this->db->query("select * from app_settings LEFT JOIN app ON (app.id=app_settings.app_id)");
        $result = $query->result_array();

        foreach ($result as $key => $val) {
            $general_settings = array();
            echo $app_id = $val ["app_id"];
            echo " ";
            echo $is_secured = (isset($val ['is_secure']) && $val ['is_secure'] == 'yes') ? 1 : 0;
            echo " - ";
            echo $is_authorized = (isset($val ['is_authorized']) && $val ['is_authorized'] == '1') ? 1 : 0;
            echo " -> ";
            $screen_view = (isset($val ['default_view_builder'])) ? $val ['default_view_builder'] : 4;
            $app_language = (isset($val ['app_language'])) ? $val ['app_language'] : "english";

            $general_settings ['setting_type'] = "general_settings";
            $general_settings ['secured_apk'] = $is_secured;
            $general_settings ['only_authorized'] = $is_authorized;
            $general_settings ['app_language'] = $app_language;
            $general_settings ['record_stop_sending'] = "0";
            $general_settings ['message_stop_sending_record'] = "";
            $general_settings ['screen_view'] = "$screen_view";
            $general_settings ['upgrade_from_google_play'] = 0;
            $general_settings ['filters'] = array();
            echo $json_string = json_encode($general_settings);

            $this->db->set("filters", $json_string);
            $this->db->where("app_id", $app_id);
            $this->db->where("setting_type", "GENERAL_SETTINGS");
            $this->db->update("app_settings");
            // get settings from
            echo "<br>";
        }
    }

    public function update_application_list_view_settings() {
        $query = $this->db->query("select * from app_settings");
        $result = $query->result_array();
        foreach ($result as $key => $val) {
            $list_view_settings = array();
            echo $app_id = $val ["app_id"];
            $list_view_settings ['setting_type'] = "result_view_settings";
            $list_view_settings ['district_filter'] = 0;
            $list_view_settings ['uc_filter'] = 0;
            $list_view_settings ['sent_by_filter'] = 0;
            $list_view_settings ['filters'] = array();
            echo $json_string = json_encode($list_view_settings);
            $data_array = array(
                'app_id' => $app_id,
                'setting_type' => 'RESULT_VIEW_SETTINGS',
                'filters' => $json_string
            );
            $app_exist = $this->db->query("select * from app_settings where app_id=$app_id AND setting_type='RESULT_VIEW_SETTINGS'");
            $app_exist1 = $app_exist->result_array();
            if (!empty($app_exist1)) {
                $this->db->set("filters", $json_string);
                $this->db->where("app_id", $app_id);
                $this->db->where("setting_type", "RESULT_VIEW_SETTINGS");
                $this->db->update("app_settings");
            } else {
                $this->db->insert("app_settings", $data_array);
            }
            // get settings from
            echo "<br>";
        }
    }

    public function update_application_map_view_settings() {
        $query = $this->db->query("select * from app_settings");
        $result = $query->result_array();

        foreach ($result as $key => $val) {
            $list_view_settings = array();
            echo $app_id = $val ["app_id"];

            $list_view_settings ['setting_type'] = "map_view_settings";
            $list_view_settings ['default_latitude'] = $val ['latitude'];
            $list_view_settings ['default_longitude'] = $val ['longitude'];
            $list_view_settings ['default_zoom_level'] = $val ['zoom_level'];
            $list_view_settings ['map_type_filter'] = 0;
            $list_view_settings ['district_filter'] = 0;
            $list_view_settings ['uc_filter'] = 0;
            $list_view_settings ['sent_by_filter'] = 0;
            $list_view_settings ['district_wise_filter'] = 0;
            $list_view_settings ['filters'] = array();
            echo $json_string = json_encode($list_view_settings);
            $data_array = array(
                'app_id' => $app_id,
                'setting_type' => 'MAP_VIEW_SETTINGS',
                'filters' => $json_string
            );
            $app_exist = $this->db->query("select * from app_settings where app_id=$app_id AND setting_type='MAP_VIEW_SETTINGS'");
            $app_exist1 = $app_exist->result_array();
            if (!empty($app_exist1)) {
                $this->db->set("filters", $json_string);
                $this->db->where("app_id", $app_id);
                $this->db->where("setting_type", "MAP_VIEW_SETTINGS");
                $this->db->update("app_settings");
            } else {
                $this->db->insert("app_settings", $data_array);
            }
            // get settings from
            echo "<br>";
        }
    }

    public function update_application_graph_view_settings() {
        $query = $this->db->query("select * from app_settings");
        $result = $query->result_array();
        foreach ($result as $key => $val) {
            $list_view_settings = array();
            echo $app_id = $val ["app_id"];
            $list_view_settings ['setting_type'] = "graph_view_settings";
            $list_view_settings ['district_filter'] = 0;
            $list_view_settings ['uc_filter'] = 0;
            $list_view_settings ['sent_by_filter'] = 0;
            $list_view_settings ['district_wise_report'] = 0;
            $list_view_settings ['pie_chart_of_selected_filters'] = 0;
            $list_view_settings ['filters'] = array();
            echo $json_string = json_encode($list_view_settings);
            $data_array = array(
                'app_id' => $app_id,
                'setting_type' => 'GRAPH_VIEW_SETTINGS',
                'filters' => $json_string
            );
            $app_exist = $this->db->query("select * from app_settings where app_id=$app_id AND setting_type='GRAPH_VIEW_SETTINGS'");
            $app_exist1 = $app_exist->result_array();
            if (!empty($app_exist1)) {
                $this->db->set("filters", $json_string);
                $this->db->where("app_id", $app_id);
                $this->db->where("setting_type", "GRAPH_VIEW_SETTINGS");
                $this->db->update("app_settings");
            } else {
                $this->db->insert("app_settings", $data_array);
            }
            // get settings from
            echo "<br>";
        }
    }
    
    
    public function addtownnameapi() {
        if(isset($_REQUEST['form_id'])){
            $form_id = $_REQUEST['form_id'];
            
            $query = $this->db->get('zform_' . $form_id);
            $all_results = $query->result_array();
            foreach ($all_results as $res) {
                if ($res['location'] !='' && $res['town_name'] == '') {
                    $town_name = '';
                    $town = $this->getTownName($res['location']);
                    $town_name = strip_tags($town);
                    $add_town = array(
                        'town_name' => $town_name
                    );
                    $this->db->where('id', $res['id']);
                    $this->db->update('zform_' . $form_id, $add_town);
                }
            }  
        }
        else{
            echo "Parameters Invalid";
        }
        
    }
     
    function remove_security_key($slug){
        $form_id = 15;//$slug;
        $form_info = $this->form_model->get_form($form_id);
        $this->db->select();
        $this->db->from('zform_' . $form_id);
        $this->db->like('activity_type', 'W43oJig9Vw');
        $this->db->limit(1000);
        $query = $this->db->get();
        $form_result_list = $query->result_array();
        //print "<pre>";
        //print_r($form_result_list);
        //exit;
        foreach ($form_result_list as $fkey => $fv) {
            //print_r($fv);
            $up_array = array();
            $rec_id = $fv['id'];
            foreach($fv as $key =>$v){
                if(strpos($v, $form_info['security_key']) !== FALSE){
                    $vdcode = urldecode(base64_decode(str_replace($form_info['security_key'], '', $v)));
                    $up_array[$key] = $vdcode;
                }
                else{
                    $up_array[$key] = $v;
                }
            }
            unset($up_array['id']);
            //print_r($up_array);
            $this->db->where('id', $rec_id);
            $this->db->update('zform_' . $form_id, $up_array);
        
        }
        exit;
        
    }
    
    /**
     * This function is used for saving the record which sent from android application
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function savetracking() {

        $app_id = $_REQUEST ['appId'];
        $imei_no = $_REQUEST ['imei_no'];
        $location = $_REQUEST ['location'];
        $lat = $_REQUEST ['lat'];
        $lng = $_REQUEST ['lng'];
        $accuracy = $_REQUEST ['accuracy'];
        $altitude = $_REQUEST ['altitude'];
        $speed = $_REQUEST ['speed'];
        $route_id = $_REQUEST ['routeId'];
        $gpsTime = date('Y-m-d H:i:s', strtotime($_REQUEST ['gpsTime']));
        $deviceTS = date('Y-m-d H:i:s', strtotime($_REQUEST ['deviceTS']));
        $created_datetime = date('Y-m-d H:i:s');
        $dataresultnew = array(
            'app_id' => $app_id,
            'imei_no' => $imei_no,
            'location' => $location,
            'lat' => $lat,
            'lng' => $lng,
            'gpsTime' => $gpsTime,
            'deviceTS' => $deviceTS,
            'accuracy' => $accuracy,
            'altitude' => $altitude,
            'speed' => $speed,
            'route_id' => $route_id,
            'created_datetime'=>$created_datetime
        );
        
        
        $tracking_temp = array(
            'app_id' => $app_id,
            'data_save' => json_encode($dataresultnew),
            'data_type' => 'single'
            
        );
        $tracking_inserted_id = $this->form_results_model->save_mobile_tracking($tracking_temp);
        
        

        header("Content-Length: 1"); 
        header("HTTP/1.1 200 OK");
        $jsone_array = array(
            'success' => 'Tracking Record submitted successfully!'
        );
        
        ob_end_clean();
         header("Connection: close\r\n");
         header("Content-Encoding: none\r\n");
         ignore_user_abort(true); // optional
         ob_start();
         echo json_encode($jsone_array);
         $size = ob_get_length();
         header("Content-Length: $size");
         ob_end_flush();     // Strange behaviour, will not work
         flush();            // Unless both are called !
         ob_end_clean();
         //do processing here
         sleep(1);
        
         
        add_tracking_table($app_id);
        
        $ret_ins = $this->db->insert( 'ztracking_'.$app_id, $dataresultnew );
                  
        if(!$ret_ins){
            $err_msg = $this->db->_error_message();
            $this->form_results_model->update_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));

            echo $jsone_array = array (
                    'error' => $err_msg
            );
        }
        //$this->form_results_model->remove_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
        exit();
    }
    /**
     * This function is used for saving the record which sent from android application
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function savetrackingbulk() {

        $app_id = $_REQUEST ['appId'];
        $imei_no = $_REQUEST ['imei_no'];
        
        
        $records = json_decode($_REQUEST ['records']);
        $records['imei_no']=$imei_no;
        
        $tracking_temp = array(
            'app_id' => $app_id,
            'data_save' => json_encode($records),
            'data_type' => 'bulk'
            
        );
        $tracking_inserted_id = $this->form_results_model->save_mobile_tracking($tracking_temp);

        header("Content-Length: 1"); 
        header("HTTP/1.1 200 OK");
        $jsone_array = array(
            'success' => 'Tracking Record submitted successfully!'
        );
        
        ob_end_clean();
         header("Connection: close\r\n");
         header("Content-Encoding: none\r\n");
         ignore_user_abort(true); // optional
         ob_start();
         echo json_encode($jsone_array);
         $size = ob_get_length();
         header("Content-Length: $size");
         ob_end_flush();     // Strange behaviour, will not work
         flush();            // Unless both are called !
         ob_end_clean();
         //do processing here
         sleep(1);
        
        add_tracking_table($app_id);
        foreach ($records as $r_key => $r_value) {
            $gpsTime = date('Y-m-d H:i:s', strtotime($r_value['gpsTime']));
            $deviceTS = date('Y-m-d H:i:s', strtotime($r_value['deviceTS']));
            $created_datetime = date('Y-m-d H:i:s');
            $dataresultnew = array(
                'app_id' => $app_id,
                'imei_no' => $imei_no,
                'location' => $r_value['location'],
                'lat' => $r_value['lat'],
                'lng' => $r_value['lng'],
                'gpsTime' => $gpsTime,
                'deviceTS' => $deviceTS,
                'accuracy' => $r_value['accuracy'],
                'altitude' => $r_value['altitude'],
                'speed' => $r_value['speed'],
                'route_id' => $r_value['routeId'],
                'created_datetime'=>$created_datetime
            );
            $ret_ins = $this->db->insert( 'ztracking_'.$app_id, $dataresultnew );
                  
            if(!$ret_ins){
                $err_msg = $this->db->_error_message();
                $this->form_results_model->update_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
                
                echo $jsone_array = array (
                        'error' => $err_msg
                );
                exit;
            }
        }
         
                
        //$this->form_results_model->remove_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
        exit();
    }
        /**
     * Action for edit application users
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function editAppUser() {
        
        //
        
        if ($this->input->post()) {
            $type = $this->input->post('type');//new or update
            $imei_no = $this->input->post('imei_no');
            $app_id = $this->input->post('app_id');
            $data = array(
                'app_id' => $this->input->post('app_id'),
                'name' => $this->input->post('user_name'),
                'town' => $this->input->post('town'),
                'district' => $this->input->post('user_district'),
                'imei_no' => $this->input->post('imei_no'),
                'view_id' => $this->input->post('view_id'),
                'mobile_number' => $this->input->post('mobile_number'),
                'login_user' => $this->input->post('login_user'),
                'login_password' => $this->input->post('login_password'),
            );
            
            if($type == 'update')
            {
                $old_imei_no = $this->input->post('old_imei_no');
                $query = $this->db->query("SELECT * FROM app_users WHERE is_deleted=0 AND app_id = '$app_id' AND imei_no='$old_imei_no'");
                $current_rec = $query->row_array();
                $app_user_id = $current_rec['id'];
                                
                $this->db->where('id', $app_user_id);
                $this->db->update('app_users', $data);
                
                //Add form records updation part
                $new_imei_no = $this->input->post('imei_no');
                $imei_update_array = array('imei_no'=>$new_imei_no);
                $all_forms = $this->form_model->get_form_by_app($app_id);
                foreach ($all_forms as $forms) {
                    $form_id = $forms['form_id'];
                    $this->db->where('imei_no', $old_imei_no);
                    $this->db->update('zform_'.$form_id, $imei_update_array);
                }
            }else{
                $this->db->insert('app_users', $data);
            }
        }
    }
}