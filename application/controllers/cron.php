<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Cron extends CI_Controller {

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

    public function run_Custom(){

        $this->post_missing_activity_record(true);

    }
    public function run_Every_Hour()
    {
        ignore_user_abort(1);
        set_time_limit(0);
        // $start_time = date('Y-m-d H:i:s');
        // $this->load->library('email');

        // $end_time = date('Y-m-d H:i:s');
        
        // $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
        // $this->email->to('zahid.nadeem@pitb.gov.pk');

        // $this->email->subject('CronJob executed');
        // $message = "<b>Welcome to ".PLATFORM_NAME."</b><br />";
        // $message .= "Your cronjob sucessfully started. <br /><br />Start time = ".$start_time;
        // $message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
        // $message .= "<br /><b>".PLATFORM_NAME."</b>";

        // $this->email->message($message);
        // $this->email->set_mailtype('html');
        // $this->email->send();



        $this->post_missing_activity_record();
        $this->image_move_in_local_folder();
        $this->image_move_nfs_to_nfs();
        //sleep(20);
        // $end_time = date('Y-m-d H:i:s');
        
        // $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
        // $this->email->to('zahid.nadeem@pitb.gov.pk');

        // $this->email->subject('CronJob executed');
        // $message = "<b>Welcome to ".PLATFORM_NAME."</b><br />";
        // $message .= "Your cronjob sucessfully executed. <br /><br />Start time = ".$start_time." ====== End Time=".$end_time;
        // $message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
        // $message .= "<br /><b>".PLATFORM_NAME."</b>";

        // $this->email->message($message);
        // $this->email->set_mailtype('html');
        // $this->email->send();
        //sleep(300);
        //$this->run_every_hour();
        exit;
    }       

    public function post_Missing_Activity_Record(){
        ini_set ( 'memory_limit', '-1' );
        ini_set('max_execution_time', 0);
        $datetime = date('Y-m-d H:i:s', strtotime("-3 hours"));
        $limit = 4000;

        $range_result = mysql_query( "SELECT * FROM cron");
        $range_row = mysql_fetch_object($range_result);
        $run_datetime = date('Y-m-d H:i:s');
        $last_row_id = 0;
        if($range_row){
            $last_row_id = $range_row->log_id;
           
            //$last_row_id++;
        }
       
        $query = "SELECT * FROM mobile_activity_log WHERE id >= $last_row_id and CAST(created_datetime AS DATE) < '$datetime' and error IS NULL limit $limit";
    //exit;

        
        //$query = "SELECT * FROM mobile_activity_log WHERE CAST(created_datetime AS DATE) < '$datetime' and app_id='269' limit 20000";
      
        $query_result = $this->db->query($query);
        $results = $query_result->result_array();
        if(count($results)>0){
            if($range_row){
                $last_rec_index = $results[count($results) - 1];
                $this->db->where('id', $range_row->id);
                $this->db->update('cron', array('log_id'=>$last_rec_index['id'] , 'run_datetime' => $run_datetime));
            }

        }else{


            $min_log = mysql_query( "SELECT min(id) as minid FROM mobile_activity_log where error IS NULL");
            $min_log_row = mysql_fetch_object($min_log);
            $last_row_id = 0;
            if($min_log_row){
                $last_row_id = $min_log_row->minid;
                //$run_datetime = date('Y-m-d H:i:s');
                if($range_row){
                    $this->db->where('id', $range_row->id);
                    $this->db->update('cron', array('log_id'=>$last_row_id , 'run_datetime' => $run_datetime));
                }
            }
            exit;
        }
        // print_r($last_rec_index);
        // exit;


        //$last_row_id = $last_row_id+$limit;




        $query_result->free_result();
        if(count($results) > 0){

            foreach ($results as $r_key => $r_value) {
                $form_data = json_decode($r_value ['form_data']);
                $imei_no = $r_value ['imei_no'];
                $location = $r_value ['location'];
                $activity_datetime = date('Y-m-d H:i:s', strtotime($r_value ['dateTime']));
                $created_datetime = date('Y-m-d H:i:s');
                $time_source = $r_value ['time_source'];
                $location_source = $r_value ['location_source'];
                $version_name = $r_value ['version_name'];
                $form_id = $r_value ['form_id'];
                $app_id = $r_value ['app_id'];
                //$app_id = $r_value ['app_id'];
                if($r_value ['error']=='submitted'){
                    $this->form_results_model->remove_mobile_activity($r_value['id']);
                    continue;
                }


                $form_info = $this->form_model->get_form($form_id);

                $take_picture = '';
                $caption_sequence = '';
                $record = array();
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


                $err_msg='';
                $uc_name = '';
                $town_name = '';
                $district_name = '';

                if ($location != '') {
                    $uc = getUcName($location); // Get UC name against location
                    if ($uc) {
                        $uc_name = strip_tags($uc);
                    }else{
                        $err_msg.="UC api return null, ";
                    }

                    $town = getTownName($location); // Get Town name against location
                    if ($town) {
                        $town_name = strip_tags($town);
                    }else{
                        $err_msg.="TOWN api return null, ";
                    }

                    $district = getDistrictName($location); // Get Town name against location
                    if ($district) {
                        $district_name = strip_tags($district);
                    }else{
                        $err_msg.="District api return null, ";
                    }
                }


                $data_result_new = array(
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
                if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab.gov') !== false) {
                    if($form_id == 21 || $form_id == 20)
                    {
                        $dataresultnew['created_datetime_partition']=$created_datetime;

                    }
                }

                $data_result_new1 = array_merge($dataresultnew, $record);


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

                $final_array = array();
                //print "<pre>";
                //print_r($dataresultnew1);
                foreach ($dataresultnew1 as $key => $value) {
                    $key = strtolower($key);
                    if(array_key_exists($key, $final_array)){
                        $final_array[$key] = $final_array[$key].','.$value;

                    }else{
                        $final_array[$key] = $value;

                    }

                }
                // print_r($final_array);
                // print "</pre>";
                //exit;
                try{
                    //echo $r_value['id'];
                    $ret_ins = $this->db->insert( 'zform_'.$form_id, $final_array );
                    
                    if(!$ret_ins){
                        $err_msg .= $this->db->_error_message();
                        $pos = strpos($err_msg, 'Duplicate Entry');
                        if ($pos !== false) {
                            $this->form_results_model->remove_mobile_activity($r_value['id']);
                            continue;
                        }
                        $this->form_results_model->update_mobile_activity($r_value['id'],array('error'=>$err_msg)); 
                        continue;
                    }
                    else{
                        $form_result_id_new = $this->db->insert_id();
                        echo $r_value['id'].' => Form Id = '.$form_id.'---App Id='.$app_id.' was Inserted Successfully and cleared cache <br />';
                        $this->form_results_model->remove_mobile_activity($r_value['id']);
                    }
                    
                }catch (Exception $e) {


                    //$err_msg .= $this->db->_error_message();
                        $pos = strpos($e->message(), 'Duplicate Entry');
                        if ($pos !== false) {
                            $this->form_results_model->remove_mobile_activity($r_value['id']);
                            continue;
                        }
                        $this->form_results_model->update_mobile_activity($r_value['id'],array('error'=>$e->message()));
                        continue;
                        
                        
                }

                if(!empty($sub_table_record)) {
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


                $images_array = json_decode($r_value['form_images'],true);
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

        }//end of count($results) > 0

    }


    public function Image_Move_In_Local_Folder(){

        //unlink('/NFS-Dataplug/images/immmm.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //unlink('/NFS-Dataplug/images/app_id_1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //rmdir('/NFS-Dataplug/images/app_id_1998');
        //rmdir('/NFS-Dataplug/live/dev');

        //copy("/var/www/vhosts/dataplug.itu.edu.pk/htdoc/assets/images/data/form-data/73954c617545b2d2336e57d3ccc5ad5e.jpg", "/NFS-Dataplug/images/immmm.jpg");

        //get image which save on local folder
       // exit;
        $this->db->select('*');
        $this->db->from('zform_images');
        if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab.gov') !== false) {
            $this->db->like('image', 'monitoring.punjab.gov.pk/assets');
        }else{
            $this->db->like('image', 'dataplug.itu.edu.pk/assets');
            //$this->db->like('image', 'godk.itu.edu.pk/assets');
        }
        $this->db->limit(1000000);
        $query1 = $this->db->get();
        $img_available1 = $query1->result_array();
        $query1->free_result();
        
        $image_array_post = array();
        $count_succ = 0;
        $count_fail = 0;
        if(!empty($img_available1)){
            foreach ($img_available1 as $image_data) {
                $image_id = $image_data['id'];
                $form_id = $image_data['form_id'];

                $app_query = "select app_id,is_deleted from form where id='$form_id'";
                $app_query = $this->db->query($app_query);
                $app_result = $app_query->row_array();
                $app_id = $app_result['app_id'];

                if($app_result['is_deleted'] == 0){
                    $url = $image_data['image'];

                    $url_explode = explode("/", $url);
                    $image_index = count($url_explode)-1;
                    $image_name = $url_explode[$image_index];
                    if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab.gov') !== false) {
                        $source_path = "/var/www/vhosts/monitoring.punjab.gov.pk/htdoc/assets/images/data/form-data/".$image_name;
                    }else{
                        $source_path = "/var/www/vhosts/dataplug.itu.edu.pk/htdoc/assets/images/data/form-data/".$image_name;
                        //$source_path = "/var/www/vhosts/godk.itu.edu.pk/htdoc/assets/images/data/form-data/".$image_name;
                    }
                    $dest_path = NFS_IMAGE_PATH."/app_id_$app_id/".$image_name;
                    if(file_exists($source_path)){
                        $count_succ++;
                        @mkdir(NFS_IMAGE_PATH.'/app_id_'.$app_id);
                        copy($source_path, $dest_path);

                        $this->db->where('id',$image_id);
                        $this->db->update('zform_images',array('image'=>$dest_path));
                        unlink($source_path);
                    }else{
                        if(file_exists($dest_path)){

                        }
                        else{
                            $this->db->delete('zform_images',array('id'=>$image_id));
                            $count_fail++;
                        }
                    }
                }
            }
        }

        echo "File transfered = ".$count_succ;
        echo "<br>File transfered failed and deleted = ".$count_fail;
    }    

    public function Image_Move_Nfs_To_Nfs(){

        //unlink('/NFS-Dataplug/images/immmm.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //unlink('/NFS-Dataplug/images/app_id_1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //rmdir('/NFS-Dataplug/images/app_id_1998');
        //rmdir('/NFS-Dataplug/live/dev');

        //copy("/var/www/vhosts/dataplug.itu.edu.pk/htdoc/assets/images/data/form-data/73954c617545b2d2336e57d3ccc5ad5e.jpg", "/NFS-Dataplug/images/immmm.jpg");

        //get image which save on local folder
       // exit;
        $this->db->select('*');
        $this->db->from('zform_images');
        if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab.gov') !== false) {
        
            $this->db->like('image', 'ppmrp-live.s3.amazonaws.com');
            $this->db->limit(300000,100000);
        }else{
            $this->db->like('image', 'dataplug-live.s3.amazonaws.com');
            $this->db->limit(100000);
        }
        $query1 = $this->db->get();
        $img_available1 = $query1->result_array();
        $query1->free_result();
        
        $image_array_post = array();
        $count_succ = 0;
        $count_fail = 0;
        if(!empty($img_available1)){
            foreach ($img_available1 as $image_data) {
                $image_id = $image_data['id'];
                $form_id = $image_data['form_id'];

                $app_query = "select app_id,is_deleted from form where id='$form_id'";
                $app_query = $this->db->query($app_query);
                $app_result = $app_query->row_array();
                $app_id = $app_result['app_id'];

                if($app_result['is_deleted'] == 0){
                    $url = $image_data['image'];

                    $url_explode = explode("/", $url);
                    $image_index = count($url_explode)-1;
                    $image_name = $url_explode[$image_index];
                    //$source_path = "/var/www/vhosts/monitoring.punjab.gov.pk/htdoc/assets/images/data/form-data/".$image_name;
                    if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab.gov') !== false) {
                        $source_path = "/NFS-PPMRP/s3/ppmrp-live/".$image_name;
                    }else{
                        $source_path = "/NFS-Dataplug/s3/dataplug-live/".$image_name;
                    }
                    $dest_path = NFS_IMAGE_PATH."/app_id_$app_id/".$image_name;
                    if(file_exists($source_path)){
                        $count_succ++;
                        @mkdir(NFS_IMAGE_PATH.'/app_id_'.$app_id);
                        copy($source_path, $dest_path);

                        $this->db->where('id',$image_id);
                        $this->db->update('zform_images',array('image'=>$dest_path));

                        //unlink($source_path);
                        //echo "File deleted because Successfully copied = ".$source_path." ==>".$dest_path;
                    }else{
                        if(file_exists($dest_path)){

                        }
                        else{
                            //$this->db->delete('zform_images',array('id'=>$image_id));
                            //echo "Image table record deleted because file not exist = ".$source_path;
                            $count_fail++;
                        }
                    }
                }
            }
        }

        echo "File transfered = ".$count_succ;
        echo "<br>File transfered failed = ".$count_fail;
    }

}
