<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tempapi extends CI_Controller {
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

    
    //Reset the post status of all record of given application
    public function postreset(){
    	if(isset($_REQUEST['app_id']) && $_REQUEST['app_id'] != ''){
    		    		
    		$form_list = $this->form_model->get_form_by_app($_REQUEST['app_id']);
    		    		
    		foreach ($form_list as $forms){
    			$form_id = $forms['form_id'];
				$oldrecarray = array('post_status' => '0');
	    		$this->db->update('zform_'.$form_id, $oldrecarray);
    			echo "Form # ".$form_id." status reset successfully <br />";
    		}
    	}
    }
    
    
    //Re-Post the all record of given application which have not sent
    public function cardverificationapi(){
        $form_id = 10601;//$_REQUEST['api_id'];//10601
        $cardnumber = $_REQUEST['cardnumber'];
        $this->db->select('*');
        $this->db->from('zform_'.$form_id);
        $this->db->where('cardnumber', $cardnumber);
        $this->db->where('is_deleted', '0');
        $query = $this->db->get();
        $post_available = $query->result_array();
        $final_data = array();
        foreach ($post_available as $k_post => $v_post){

            $this->db->select('*');
            $this->db->from('zform_images');
            $this->db->where('zform_result_id', $v_post['id']);
            $this->db->where('form_id', $form_id);
            $query1 = $this->db->get();
            $img_available1 = $query1->result_array();
            
            $image_array_post = array();
            foreach ($img_available1 as $image_path) {
                $add_images = array(
                        'image' => get_image_path($image_path['image']),
                        'title' =>$image_path['title']
                );
                
                $image_array_post[] = $add_images;
            }
            if($img_available1){
                $v_post['image'] = $image_array_post;
                
            }
            else{
                $v_post['image'] ='';
            }
            $final_data[] = $v_post;
        }

        echo json_encode($final_data);
        exit;
        //http://www.dataplug.itu.edu.pk/app-form/10601
    }
    public function postmissingrecord(){
        //exit;
    	//print "<pre>";
    	if(isset($_REQUEST['app_id']) && $_REQUEST['app_id'] != ''){
    		
    		$form_list = $this->form_model->get_form_by_app($_REQUEST['app_id']);
    		    		
    		foreach ($form_list as $forms){
    			
    			$form_id = $forms['form_id'];
    			//if($form_id!=2248){continue;}
    			$form_info = $this->form_model->get_form($form_id);
    			//Send this record to other domains also
    			$post_url = '';
    			if (!empty($form_info['post_url'])) {
    				$post_url = $form_info['post_url'];
    			} else if (!empty($form_info['fv_post_url'])) {
    				$post_url = $form_info['fv_post_url'];
    			}
    			//echo $post_url;
    			
    			$this->db->select('*');
    			$this->db->from('zform_'.$form_id);
    			$this->db->where('post_status', 'no');
    			$this->db->where('is_deleted', '0');
    			//$this->db->order_by('id', 'DESC');
    			$this->db->limit('20000');
    			$query = $this->db->get();
    			
    			$post_available = $query->result_array();
    			$total_resent_rec = count($post_available);
    			$count_resesnd = 0 ;
    			echo "Total record need to resend on Form #".$form_id." = ".$total_resent_rec.'<br />';
    			
    			if ($total_resent_rec > 0) {
    				
    				foreach ($post_available as $k_post => $v_post){
    					$v_post['remote_record_id'] = $v_post['id'];
    					$this->db->select('*');
    					$this->db->from('zform_images');
    					$this->db->where('zform_result_id', $v_post['id']);
    					$this->db->where('form_id', $form_id);
    					$query1 = $this->db->get();
    					$img_available1 = $query1->result_array();
    					
    					$image_array_post = array();
    					foreach ($img_available1 as $image_path) {
    						$add_images = array(
    								'zform_result_id' => $image_path['zform_result_id'],
    								'form_id' => $image_path['form_id'],
    								'image' => get_image_path($image_path['image']),
    								'title' =>$image_path['title']
    						);
    						
    						$image_array_post[] = $add_images;
    					}
    					

    					if($img_available1){
	    					$v_post['image'] = $image_array_post;
	    					
    					}
    					else{
    						$v_post['image'] ='';
    					}
    					
    					unset($v_post['post_status']);
    					$v_post['security_key'] = $form_info['security_key'];
    					    	//print_r($data_post_json);
                                        //echo $post_url;
    					$data_post_json = json_encode($v_post);
    					$urlpost = urlencode($data_post_json);
    					
    					$fields_string = "form_data=" . $urlpost;
    					$curl = curl_init();
    					curl_setopt($curl,CURLOPT_URL, $post_url);
    					curl_setopt($curl,CURLOPT_POST, 1);
    					curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
    					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    					$res = curl_exec($curl);
    					
    					//echo "<br />".$form_id."=".$image_path['zform_result_id'].'=>';
                                        //print "<pre>";
    					//print_r($res);
                                         //$res = json_decode($res,true);
    					//print_r($res);
    					$status_sent = false;
                                        if(curl_errno($curl)){
                                            $status_sent = false;
                                        } else {
                                            $res = json_decode($res, true);
                                            if (isset($res['status']) && $res['status'] == true) {
                                                $status_sent = true;
                                            } else {
                                                $status_sent = false;
                                            }
                                        }
                                        curl_close($curl);
    					if($status_sent)
    					{
    						$count_resesnd++;
    						$oldrecarray = array('post_status' => 'yes');
	    					$this->db->where('id', $v_post['id']);
	    					$this->db->update('zform_'.$form_id, $oldrecarray);
    					}
    					//exit;
    					
    				}
    				echo "Record Resent successfully on form # ".$form_id." = ".$count_resesnd.'<br />';
    			}
    			//exit;
    		}
//     				$this->load->library('email');
    		
//     				$this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
//     				$this->email->to('zahid.nadeem@pitb.gov.pk');
    		
//     				$this->email->subject('Cron Executed');
//     				$message = "<b>Welcome to ".PLATFORM_NAME."</b><br />";
//     				$message .= $form_list;
//     				$message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
//     				$message .= "<br /><b>".PLATFORM_NAME."</b>";
    		
//     				$this->email->message($message);
//     				$this->email->set_mailtype('html');
//     				$this->email->send();
    		//exit;
    	}
    }
    
    /**
     * Send record to other domain using CURL
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function post_record($base_url, $data_post_json) {
    	$base_url = urldecode($base_url);
    	$urlpost = $base_url;
    	$fields_string = "form_data=" . $data_post_json;
    	//$urlpost = "http://zimaidarofficer.pitb.gov.pk/task/addAssignTask/?form_data={%22complaint_violations%22%3A%22Banner+Removing%22%2C%22complaint_comments%22%3A%22Guvku+hiffo+yhuj+yiyxg%22%2C%22town_id%22%3A%222%22%2C%22imei_no%22%3A%2225463215487%22%2C%22complaint_picture%22%3A%22%22%2C%22location%22%3A%222.56456%2C2.35468%22}";
    	$curl = curl_init();
    
    	curl_setopt($curl,CURLOPT_URL, $urlpost);
    	curl_setopt($curl,CURLOPT_POST, 1);
    	curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    	curl_exec($curl);
    	curl_close($curl);
    	//if ($server_output == "OK") {  } else {  }
    }

//     function zform_2206($formValues)
//     {
//     	unset($formValues->image);
//     	$rec = $this->db->query("SELECT id FROM zform_2206 WHERE remote_record_id='".$formValues->id."'")->row();
//     	if(count($rec)==0)
//     	{
//     		$res = $this->db->insert('zform_2206', $formValues);
//     		if(!$res)
//     			return json_encode(array('status'=>'true'));
//     		else
//     			return json_encode(array('status'=>'false'));
//     	}
//     	else
//     	{
//     		return json_encode(array('status'=>'true'));
//     	}
    
//     }
    
//     function zform_2206($formValues)
//     {
//     	unset($formValues->image);
//     	$rec = $this->db->query("SELECT id FROM zform_2206 WHERE remote_record_id='".$formValues->id."'")->row();
    	 
    	 
//     	if(count($rec)==0)
//     	{
    
    
//     		try{
//     			$this->db->insert('zform_2206', $formValues);
//     			return json_encode(array('status'=>'true'));
//     		}catch(Exception $e){
//     			return json_encode(array('status'=>'false'));
//     		}
//     	}
//     	else
//     	{
    
//     		return json_encode(array('status'=>'true'));
//     	}
//     }
    
    
    
    //Functions which used for android application communication - End

    
    //Functions which used for import CSV - Start

    /**
     * This is a specific application function, included here just for example, you make your function follow this.
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function importcsv() {

        $file_path = '/give your absolute path/htdoc/assets/data/filename.csv';
        $row = 1;
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                if ($row == 1) {
                    if ($data[0] != 'License No' && $data[1] != 'Store Name' && $data[2] != 'Store Address' && $data[3] != 'Town Name') {

                        print "<br />Its not a right formated file. <br />";
                        break;
                    }
                    $row++;
                } else {
                    $row++;
                    $record = array(
                        'Category' => 'any',
                        'Description' => $data[1],
                        'Address' => $data[2],
                    );


                    $record = json_encode($record);
                    $formdata = array(
                        'record' => $record,
                        'imei_no' => $data[5],
                        'location' => $data[4]
                    );
                    $this->db->insert('form_results', $formdata);
                }
            }
            fclose($handle);
        }
        exit;
    }    


    /**
     * This is a specific application function, included here just for example, you make your function follow this.
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function add_tubewell_polygon() {
        echo 'zahid';

        $file_path = 'assets/data/TWBaseFile.csv';
        $row = 1;
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                if ($row == 1) {
                    $row++;
                } else {
                    $loc = explode(",", $data[0]);
                    $lat = "";
                    $lng = "";
                    if(isset($loc[0]) && isset($loc[1])){
                        $lat = $loc[0];
                        $lng = $loc[1];
                    }
                    $matching_value = trim($data[1]);

                    $record = array(
                        'app_id' => '276',
                        'type' => "distance",
                        'name' => "MEA PHED - Tubewell",
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'matching_value' => $matching_value,
                    );
                    
                    $rec = $this->db->query("SELECT * FROM kml_poligon WHERE matching_value='".$matching_value."'")->row();
                     if(count($rec)==0)
                     {
                         $this->db->insert('kml_poligon', $record);
                     }
                   
                }
            }
            fclose($handle);
        }
        exit;
    }

    public function add_filteration_polygon() {
        echo 'zahid';

        $file_path = 'assets/data/FPBaseFile.csv';
        $row = 1;
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                if ($row == 1) {
                    $row++;
                } else {
                    $loc = explode(",", $data[0]);
                    $lat = "";
                    $lng = "";
                    if(isset($loc[0]) && isset($loc[1])){
                        $lat = $loc[0];
                        $lng = $loc[1];
                    }
                    $matching_value = trim($data[1]);

                    $record = array(
                        'app_id' => '276',
                        'type' => "distance",
                        'name' => "MEA PHED - Filteration",
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'matching_value' => $matching_value,
                    );
                     
                     $rec = $this->db->query("SELECT * FROM kml_poligon WHERE matching_value='".$matching_value."'")->row();
                     if(count($rec)==0)
                     {
                         $this->db->insert('kml_poligon', $record);
                     }
                }
            }
            fclose($handle);
        }
        exit;
    }

    /**
     * This is a specific application function, included here just for example, you make your function follow this.
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function importimeicsv() {

        $file_path = 'assets/data/imeinoschool.csv';
        $app_id = isset($_REQUEST['app_id']) ? $_REQUEST['app_id'] : 0;
        $row = 1;
        $total_insertion = 0;
        $restrict_app_id = array('2663', '2664');
        if (!in_array($app_id, $restrict_app_id)) {
            echo 'This api is not for this application';
            exit;
        }
        $query = $this->db->query("SELECT * FROM app_users WHERE app_id = '$app_id'");
        $myimeidata = $query->result_array();
        $already_exist = array();
        foreach ($myimeidata as $key => $myimei) {
            $already_exist[$key] = $myimei['imei_no'];
        }

        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {

                if ($row == 1) {
                    if ($data[0] != 'Full Name' && $data[1] != 'IMEI Number' && $data[2] != 'District' && $data[3] != 'CNIC') {

                        print "<br />Its not a right formated file. <br />";
                        break;
                    }
                    $row++;
                } else {

                    if (in_array($data[1], $already_exist)) {
                        continue;
                    } else {
                        $already_exist[] = $data[1];
                    }
                    $record_imei = array(
                        'app_id' => $app_id,
                        'department_id' => '26',
                        'view_id' => '0',
                        'name' => $data[0],
                        'town' => $data[5],
                        'district' => $data[2],
                        'imei_no' => $data[1],
                        'is_deleted' => '0',
                    );
                    $total_insertion++;
                    $this->db->insert('app_users', $record_imei);
                }
            }
            fclose($handle);
        }
        echo "Total Inserted records=" . $total_insertion;
        exit;
    }
    /**
     * This is a specific application function, included here just for example, you make your function follow this.
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function importdistancecsv() {

        $file_path = 'assets/data/Hospital_Banner_Monitoring.csv';
        $already_exist = array();
        $total_insertion = 0;
        $row=1;
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {

                if ($row == 1) {
                    if ($data[0] != 'app_id' && $data[1] != 'type' && $data[2] != 'District_Code') {

                        print "<br />Its not a right formated file. <br />";
                        break;
                    }
                    $row++;
                } else {

                    if (in_array($data[9], $already_exist)) {
                        continue;
                    } else {
                        $already_exist[] = $data[9];
                    }
                    $location = explode(',', $data[10]);
                    $record_imei = array(
                        'app_id' => $data[0],
                        'type' => $data[1],
                        'name' => 'Hospital Banner Monitoring',
                        'latitude' => $location[0],
                        'longitude' => $location[1],
                        'matching_value' => $data[9],
                        
                    );
                    $total_insertion++;
                    $this->db->insert('kml_poligon', $record_imei);
                }
            }
            fclose($handle);
        }
        echo "Total Inserted records=" . $total_insertion;
        exit;
    }

    

    //Create forms table if not exist
    public function tableformcreating() {
        $forms = $this->form_model->get_form();
        foreach ($forms as $form) {
            if ($form['is_deleted'] == '0') {
                $table_exist_bit = $this->form_results_model->check_table_exits('zform_' . $form['id']);

                if (!is_table_exist('zform_' . $form['id'])) {
                    updateDataBase($form['id'], $form['description']);
                }
            }
        }
    }

    //Move images to other table and remove old record
    public function moverecordimagetotable() {
        $table_headers_array = array();
        $final_record_array = array();
        //get record first
        $form_results = $this->form_results_model->get_form_results();
        foreach ($form_results as $rasult) {
            if ($rasult['is_deleted'] == '0') {
                //check if table not exist then create
                $table_exist_bit = $this->form_results_model->check_table_exits('zform_' . $rasult['form_id']);
                if (!is_table_exist('zform_' . $rasult['form_id'])) {
                    $form = $this->form_model->get_form($rasult['form_id']);
                    if ($form && $form['is_deleted'] == '0')
                        updateDataBase($form['id'], $form['description']);
                    else
                        continue;
                }

                //get fields list
                $column_list = $this->form_results_model->getTableHeadingsFromSchema('zform_' . $rasult['form_id']);
                foreach ($column_list as $key => $value) {
                    $header_value = $value['COLUMN_NAME'];
                    if (!in_array($header_value, $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array($header_value));
                    }
                }

                //move static fields
                $final_record_array = array(
                    'form_id' => $rasult['form_id'],
                    'imei_no' => $rasult['imei_no'],
                    'location' => $rasult['location'],
                    'uc_name' => $rasult['uc_name'],
                    'town_name' => $rasult['town_name'],
                    'created_datetime' => $rasult['created_datetime'],
                );

                //move record fields
                $recordarray = json_decode($rasult['record']);
                foreach ($recordarray as $reckey => $rec) {
                    if (in_array($reckey, $table_headers_array)) {
                        $final_record_array[$reckey] = $rec;
                    }
                }
                $this->db->insert('zform_' . $rasult['form_id'], $final_record_array);
                //get moved record id
                $form_result_id_new = $this->db->insert_id();


                //image move
                $image_array = array();
                $images_list = $this->form_results_model->getResultsImagesOld($rasult['id']);
                if ($images_list) {
                    foreach ($images_list as $imgvalue) {
                        $image_array['zform_result_id'] = $form_result_id_new;
                        $image_array['form_id'] = $rasult['form_id'];
                        $image_array['image'] = $imgvalue['image'];
                        $image_array['title'] = $imgvalue['title'];
                    }
                    $this->db->insert('zform_images', $image_array);
                }

                $oldrecarray = array('is_deleted' => '1');
                $this->db->where('id', $rasult['id']);
                $this->db->update('form_results', $oldrecarray);
            }
        }

//Remove the images
    }

    //api for adding missing column on all form tables
    function addmissingfield() {
        $this->load->dbforge();
        $forms = $this->form_model->get_form();
        foreach ($forms as $form) {
            if ($form['is_deleted'] == '0') {
                $form_id = $form['id'];
                //$table_exist_bit = $this->form_results_model->check_table_exits('zform_' . $form_id);

                if (is_table_exist('zform_' . $form_id)) {
                    $fields_list = $this->db->list_fields('zform_' . $form_id);
                    $fields_list = array_map('strtolower', $fields_list);
                    if (!(in_array('post_status', $fields_list))) {
                        $after_field = 'time_source';
                        $field = array('post_status' => array('type' => "ENUM('yes', 'no')", 'default' => 'no'));
                        $this->dbforge->add_column('zform_' . $form_id, $field, $after_field);
                    }
                }
            }
        }
    }



    public function movetracking() {
        die("Contact with Zahid Nadeem for activate this function");
        ini_set ( 'memory_limit', '-1' );
        ini_set('max_execution_time', 0);
        //if this routid already received then not saved
        $this->db->select('*');
        $this->db->from('mobile_tracking_log');
        $this->db->where('app_id', '8');
        $this->db->where('error', null);
        $this->db->limit(5000,0);
        $query = $this->db->get();
        $tracking_result= $query->result_array();

        $query->free_result();

        if(count($tracking_result)>0)
        {
            foreach ($tracking_result as $tr_key => $tr_value) {

                $tracking_inserted_temp_id = $tr_value['id'];

                $result = json_decode($tr_value['data_save'],true);
                $newDistanceGeo=0;
                $lastDistanceGeo=0; 
                $newDistance=0;
                $lastDistance=0;  
                foreach($result as $r)  
                {   
                  //print_r($r);
                  $distance=(float)$r['distance'];   
                  $d=$distance-$lastDistance;   
                  if($d>0)   {
                    $newDistance+=$distance-$lastDistance;   
                  }
                  $lastDistance=$distance;    

                  //print_r($r);
                  $distanceGeo=(float)$r['distanceGeo'];   
                  $dGeo=$distanceGeo-$lastDistanceGeo;   
                  if($dGeo>0)   {
                    $newDistanceGeo+=$distanceGeo-$lastDistanceGeo;   
                  }
                  $lastDistanceGeo=$distanceGeo;     
                }
                $gps_time = $result[0]['gpsTime'];
                $records = $tr_value['data_save'];
                $distanceCovered = $newDistance;
                $distanceCoveredGeo = $newDistanceGeo;

                
                $gps_time = date('Y-m-d H:i:s', strtotime($gps_time));
                $created_datetime = date('Y-m-d H:i:s');
                $dataresultnew = array(
                    'route_id' => $result[0]['routeId'],
                    'imei_no' => $result[0]['imei_no'],
                    'gps_datetime' => $gps_time,
                    'distanceCovered' => round($newDistance,2),
                    'distanceCoveredGeo' => round($distanceCoveredGeo,2),
                    'records' => $records,
                    'created_datetime' => $tr_value['created_datetime']
                );

                try {

                      $ret_ins = $this->db->insert('ztracking_8', $dataresultnew);
                      if (!$ret_ins) {
                        $err_msg = $this->db->_error_message();

                        $jsone_array = array('error' => $err_msg);
                        $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$err_msg));
                        //echo json_encode($jsone_array);
                        continue;
                    }
                    else{
                      $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>'submitted'));
                    }


                }catch (Exception $e) {
                        //$this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$e->message()));
                        echo "Zahid=".$e->message();
                        exit;
                }

            }

        }
   


        $jsone_array = array(
            'success' => 'Tracking Record re-submitted successfully!'
        );
        exit();
    }

    //Replacing specific string from form tables
    public function replacestring() {
        $this->db->query("UPDATE zform_1348 SET "
                . "Gender = replace( Gender, 'Select', 'No Answer' ), "
                . "Do_you_get_any_aid = replace( Do_you_get_any_aid, 'Select', 'No Answer' ), "
                . "Do_you_or_your_family_affected_by_flood = replace( Do_you_or_your_family_affected_by_flood, 'Select', 'No Answer' ), "
                . "From_which_institution_you_ged_aid = replace( From_which_institution_you_ged_aid, 'Select', 'No Answer' ),"
                . "Facilities_Available = replace( Facilities_Available, 'Select', 'No Answer' ),"
                . "What_You_Get_in_Cooked_Food = replace( What_You_Get_in_Cooked_Food, 'Select', 'No Answer' ),"
                . "Government_performed_well_compared_to_past = replace( Government_performed_well_compared_to_past, 'Select', 'No Answer' ),"
                . "Nature_of_financial_damage  = replace( Nature_of_financial_damage , 'Select', 'No Answer' ),"
                . "Interview_Point = replace( Interview_Point, 'Select', 'No Answer' )");
        echo 'Query executed successfully......';
    }

    
    //Add missing district name which get using location API
    public function adddistrictname() {
        $this->load->dbforge();
        $forms = $this->form_model->get_form();
        foreach ($forms as $form) {
            if ($form['is_deleted'] == '0') {
                $form_id = $form['id'];
                $table_exist_bit = $this->form_results_model->check_table_exits('zform_' . $form_id);

                if (is_table_exist('zform_' . $form_id)) {
                    $query = $this->db->get('zform_' . $form_id);
                    $all_results = $query->result_array();
                    foreach ($all_results as $res) {
                        if ($res['location'] && $res['district_name'] == '') {
                            $district_name = '';
                            $district = $this->getDistrictName($res['location']);
                            $district_name = strip_tags($district);
                            $add_district = array(
                                'district_name' => $district_name
                            );
                            $this->db->where('id', $res['id']);
                            $this->db->update('zform_' . $form_id, $add_district);
                        }
                    }
                }
            }
        }
    }

    

    public function removehouse() {

        $query = $this->db->query("SELECT
            GROUP_CONCAT(z1.id SEPARATOR ',') AS str,count(z1.id) as TotalRemoval
                    FROM
                        zform_2098 z1
                    INNER JOIN zform_2098 z2 ON z2.CNIC = z1.CNIC
                    WHERE
                       z1.id > z2.id
            and z1.is_deleted='0'           
            ");
        $mydata = $query->result_array();
        $duplicate_rec = 0;
        if ($mydata) {

            if ($mydata[0]['TotalRemoval'] > 0) {
                $removeId = $mydata[0]['str'];
                $this->db->query("UPDATE zform_2098
                        SET `is_deleted` = '1'
                        WHERE
                            id IN ($removeId)");
                $duplicate_rec = $mydata[0]['TotalRemoval'];
            }
        }
        $file_path = 'assets/data/cnicno.csv';
        $record = array();
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $row = 1;
            while (($data = fgetcsv($handle, 20000, ",")) !== FALSE) {
                if ($row == 1) {
                    $row++;
                } else {
                    array_push($record, $data[0]);
                }
            }
        }
        $query = $this->db->where('is_deleted', '0')->get('zform_2098');
        $all_results = $query->result_array();
        $irrelevant = 0;
        foreach ($all_results as $res) {
            if (!in_array($res['CNIC'], $record)) {
                $update_cnic = array(
                    'is_deleted' => '1'
                );
                $this->db->where('id', $res['id']);
                $this->db->update('zform_2098', $update_cnic);
                $irrelevant++;
            }
        }
        fclose($handle);
        echo "Duplicate Records Removed = " . $duplicate_rec;
        echo "<br />Irrelevant Records Removed = " . $mydata[0]['TotalRemoval'];
        echo "<br />Refresh this page till the number greter then zero....";
        exit;
    }

    public function removecrop() {

        $query = $this->db->query("SELECT
            GROUP_CONCAT(z1.id SEPARATOR ',') AS str,count(z1.id) as TotalRemoval
                    FROM
                        zform_2099 z1
                    INNER JOIN zform_2099 z2 ON z2.CNIC = z1.CNIC
                    WHERE
                       z1.id > z2.id
            and z1.is_deleted='0'           
            ");
        $mydata = $query->result_array();
        $duplicate_rec = 0;
        if ($mydata) {

            if ($mydata[0]['TotalRemoval'] > 0) {
                $removeId = $mydata[0]['str'];
                $this->db->query("UPDATE zform_2099
                        SET `is_deleted` = '1'
                        WHERE
                            id IN ($removeId)");
                $duplicate_rec = $mydata[0]['TotalRemoval'];
            }
        }
        $file_path = 'assets/data/cnicno.csv';
        $record = array();
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $row = 1;
            while (($data = fgetcsv($handle, 20000, ",")) !== FALSE) {
                if ($row == 1) {
                    $row++;
                } else {
                    array_push($record, $data[1]);
                }
            }
        }
        $query = $this->db->where('is_deleted', '0')->get('zform_2099');
        $all_results = $query->result_array();
        $irrelevant = 0;
        foreach ($all_results as $res) {
            if (!in_array($res['CNIC'], $record)) {
                $update_cnic = array(
                    'is_deleted' => '1'
                );
                $this->db->where('id', $res['id']);
                $this->db->update('zform_2099', $update_cnic);
                $irrelevant++;
            }
        }
        fclose($handle);
        echo "Duplicate Records Removed = " . $duplicate_rec;
        echo "<br />Irrelevant Records Removed = " . $mydata[0]['TotalRemoval'];
        echo "<br />Refresh this page till the number greter then zero....";

        exit;
    }

    public function assignuserapp() {


        $users_list = $this->users_model->get_all_users();
        foreach ($users_list as $userinfo) {
            if ($userinfo['department_id']) {

                $department_list = $this->department_model->get_department($userinfo['department_id']);

                if ($department_list['is_public'] != 'yes') {
                    $app_list_by_dep = $this->app_model->get_app_by_department($userinfo['department_id']);
                    if ($app_list_by_dep) {
                        foreach ($app_list_by_dep as $appinfo) {

                            $this->db->select('*');
                            $this->db->from('users_app u');

                            $this->db->where('u.user_id', $userinfo['id']);
                            $this->db->where('u.app_id', $appinfo['id']);
                            $query = $this->db->get();
                            $already_asign = $query->result_array();
                            if (!$already_asign) {
                                $asign_array = array('user_id' => $userinfo['id'], 'app_id' => $appinfo['id']);
                                $this->db->insert('users_app', $asign_array);
                            }
                        }
                    }
                }
            }
        }
    }

    public function assignuserapppublic() {

        $app_list = $this->app_model->get_app();
        foreach ($app_list as $appinfo) {
            if ($appinfo['user_id']) {

                $this->db->select('*');
                $this->db->from('users_app u');

                $this->db->where('u.user_id', $appinfo['user_id']);
                $this->db->where('u.app_id', $appinfo['id']);
                $query = $this->db->get();
                $already_asign = $query->result_array();

                if (!$already_asign) {
                    $asign_array = array('user_id' => $appinfo['user_id'], 'app_id' => $appinfo['id']);
                    $this->db->insert('users_app', $asign_array);
                }
            }
        }
    }

    
    //URL :http://www.dataplug.itu.edu.pk/api/getsecuritykey?app_id=1968
    public function getsecuritykey() {
        if (isset($_REQUEST['app_id'])) {
            $app_id = $_REQUEST['app_id'];
           
            $selected_app = $this->app_model->get_app($app_id);
            $app_name = $selected_app['name'];
            die(md5($app_name . $app_id));

            
        } else {
            $syn_data = array('data' => false);
            echo json_encode($syn_data);
        }
    }
    
    //for hospital watch app
    public function hospitalwatchapi() {
    	//if (isset($_REQUEST['app_id']) && isset($_REQUEST['last_date_stamp']) && isset($_REQUEST['security_token'])) {
    	if (isset($_REQUEST['app_id'])) {
    		$app_id = $_REQUEST['app_id'];
    		$last_date_stamp = isset($_REQUEST['last_date_stamp'])?$_REQUEST['last_date_stamp']:'';
    		$start_date_stamp = isset($_REQUEST['start_date_stamp'])?$_REQUEST['start_date_stamp']:'';
    		
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
    				$results = $this->form_results_model->syncDataFromRemoteServer($forms['form_id'], $last_date_stamp,$start_date_stamp);
    				$results_count += count($results);
    				$final_result = array_merge($final_result,$results);
    
    			}
    			
    			 $report_array = array();
    			foreach($final_result as $rec){
    				
    				$district=$rec['District'];
    				$hospital_name=$rec['Hospital_Name'];
    				$desigination=$rec['Identify_Yourself'];
    				
    				if (!isset($report_array[$district])) {
    					$report_array[$district] = array();
    				}
    				if (!isset($report_array[$district][$hospital_name])) {
    					$report_array[$district][$hospital_name] = array();
    				}
    				if (!isset($report_array[$district][$hospital_name][$desigination])) {
    					$report_array[$district][$hospital_name][$desigination] = array();
    					$report_array[$district][$hospital_name][$desigination]['visits']=0;
    				}
    				$report_array[$district][$hospital_name][$desigination]['visits'] = $report_array[$district][$hospital_name][$desigination]['visits']+1;
    				
    			}

    
    			$syn_data = array('data' => $report_array, 'total_visits' => $results_count);
    			echo json_encode($syn_data);
    		
    	} else {
    		$syn_data = array('data' => false);
    		echo json_encode($syn_data);
    	}
    }
    
    //URL :http://local.dataplug.itu.edu.pk/api/removedeletedformtable
    public function removedeletedformtable() {
        
        $this->load->dbforge();
        $this->db->select('*');
        $this->db->from('form');
        $this->db->where('is_deleted', '1');
        $query = $this->db->get();
        $form_listing = $query->result_array();
        $i=0;
        foreach($form_listing as $form)
        {
            $table_exist_bit = is_table_exist('zform_' . $form['id']);
            if ($table_exist_bit) {
                $i++;
                $this->dbforge->drop_table('zform_' . $form['id']);
                echo '<br />zform_' . $form['id'];
            }
        }
        echo 'Form Table Deleted='.$i;
    }
    
    //Below function is the example of Post_url save functionality functionality
    public function postsave(){
    
    	 
    	if($this->input->post('form_data')){
    		 
    		$complaint_form_data = json_decode($this->input->post('form_data'));
    		$form_id = $complaint_form_data->form_id;
    		//$remote_record_id = $complaint_form_data->remote_record_id;
    		$activity_Type = $complaint_form_data->Activity_Type;
    		$task_complaint_picture = $complaint_form_data->image;
    		$location = $complaint_form_data->location;
    		//Add your other field and columns which you need.
    
    		$asign_array = array('form_id' => $form_id, 'location' => $location,'Activity_Type'=>$activity_Type);
    		$this->db->insert('temp_test', $asign_array);
    		return true;
    		 
    	}
    	return false;
    }

    public function qrcodegenerator(){
    	 
    	$site_settings = $this->site_model->get_settings('1');
    	$directory_path = $site_settings['directory_path'];
    	 
    	$query = $this->db->query("SELECT * FROM app_released");
    	$released_app = $query->result_array();
    	 
    	foreach ($released_app as $key => $myrel) {
    
    		$apk_released_id = $myrel['id'];
    		$apk_file_name = $myrel['app_file'];
    		$file_name = explode('.', $apk_file_name);
    		$qr_code_file_name = $file_name[0].'.png';
    
    
    		$qr_file_src = base_url().'assets/android/apps/'.$apk_file_name;
    		$qr_file_dest = $directory_path.'/assets/android/qr_code/'.$qr_code_file_name;
    		$qr_code_command = "qrencode -o $qr_file_dest -s 6 $qr_file_src";
    		 
    		exec($qr_code_command, $outputqr, $co);
    		//insert release record to database
    		$data = array(
    				'qr_code_file' => $qr_code_file_name
    		);
    		$this->db->where('id', $apk_released_id);
    		$this->db->update('app_released', $data);
    	}
    }
    
    
    
    //URL :http://local.dataplug.itu.edu.pk/api/deleteoldapp
    
    //Remove all application which have no build and no record
    public function deleteoldapp() {
    	
    	
    	$this->load->dbforge();
    	//Get 3 month old apps which no build apk
    	$query = $this->db->query("SELECT a.*,(SELECT COUNT(*) FROM app_released ar WHERE ar.app_id=a.id) AS count_released FROM app a WHERE a.created_datetime < NOW() - INTERVAL 3 MONTH ORDER BY a.created_datetime desc");
    	$app_listing = $query->result_array();
    	$i=0;
    	
    	foreach($app_listing as $app)
    	{
    		if($app['count_released']==0){//Check apk build exist
    			
    			
    			$results_count = 0;
    			$all_forms = $this->form_model->get_form_by_app($app['id']);
    			
    			$results = array();
    			foreach ($all_forms as $forms) {
    				$table_exist_bit = is_table_exist('zform_' . $forms['form_id']);
    				if ($table_exist_bit) {
	    				$results = $this->form_results_model->syncDataFromRemoteServer($forms['form_id']);
	    				$results_count += count($results);
    				}
    			}
    			
	    		if($results_count == 0){//If any form of this app not record then going to delete app
	    			
	    			$data_app = array(
	    					'is_deleted' => '1'
	    			);
	    			$this->db->where('id', $app['id']);
	    			$this->db->update('app', $data_app);
	    			echo '<br />App Deleted = '.$app['id'];
	    		
		    		$this->db->select('*');
		    		$this->db->from('form');
		    		$this->db->where('app_id', $app['id']);
		    		$query = $this->db->get();
		    		$form_listing = $query->result_array();
		    		foreach($form_listing as $form)
		    		{
		    			$data_form = array(
		    					'is_deleted' => '1'
		    			);
		    			$this->db->where('id', $form['id']);
		    			$this->db->update('form', $data_form);
		    			echo '<br />Form Deleted = '.$form['id'];
		    			
		    			$table_exist_bit = is_table_exist('zform_' . $form['id']);
		    			if ($table_exist_bit) {
		    				
	    					$i++;
		    				$this->dbforge->drop_table('zform_' . $form['id']);
		    				echo '<br /><br />Table Deleted = zform_' . $form['id'];
		    			}
		    		}
	    		}
    		}

    	}
    	
    	echo '<br />Form Table Deleted='.$i;
    }
    
    
    //Remove all application which have no build and no record
    public function deleteoldappnorec() {
    	
    	
    	$this->load->dbforge();
    	//Get 3 month old apps which no build apk
    	$query = $this->db->query("SELECT a.*,(SELECT COUNT(*) FROM app_released ar WHERE ar.app_id=a.id) AS count_released FROM app a WHERE a.created_datetime < NOW() - INTERVAL 3 MONTH ORDER BY a.created_datetime desc");
    	$app_listing = $query->result_array();
    	$i=0;
    	
    	foreach($app_listing as $app)
    	{
    		//if($app['count_released']==0){//Check apk build exist
    			   			
    			$results_count = 0;
    			$all_forms = $this->form_model->get_form_by_app($app['id']);
    			
    			$results = array();
    			foreach ($all_forms as $forms) {
    				$table_exist_bit = is_table_exist('zform_' . $forms['form_id']);
    				if ($table_exist_bit) {
	    				$results = $this->form_results_model->syncDataFromRemoteServer($forms['form_id']);
	    				$results_count += count($results);
    				}
    			}
    			
	    		if($results_count == 0){//If any form of this app not record then going to delete app
	    			
	    			$data_app = array(
	    					'is_deleted' => '1'
	    			);
	    			$this->db->where('id', $app['id']);
	    			$this->db->update('app', $data_app);
	    			echo '<br />App Deleted = '.$app['id'];
	    		
		    		$this->db->select('*');
		    		$this->db->from('form');
		    		$this->db->where('app_id', $app['id']);
		    		$query = $this->db->get();
		    		$form_listing = $query->result_array();
		    		foreach($form_listing as $form)
		    		{
		    			$data_form = array(
		    					'is_deleted' => '1'
		    			);
		    			$this->db->where('id', $form['id']);
		    			$this->db->update('form', $data_form);
		    			echo '<br />Form Deleted = '.$form['id'];
		    			
		    			$table_exist_bit = is_table_exist('zform_' . $form['id']);
		    			if ($table_exist_bit) {
		    				
	    					$i++;
		    				$this->dbforge->drop_table('zform_' . $form['id']);
		    				echo '<br /><br />Table Deleted = zform_' . $form['id'];
		    			}
		    		}
	    		}
    		//}

    	}
    	
    	echo '<br />Form Table Deleted='.$i;
    }
    
    
    public function deletefiles() {

    	$path = './assets/android';
    	$this->rmdirectoryfiles($path);
    }
    
    function rmdirectoryfiles($path){
    	
    	$exclude =array('app_resources','apps','godk_android','godk_android_test','keystore','qr_code');
    		if (is_dir($path))
    			$dir_handle = opendir($path);
    		
	    		while($file = readdir($dir_handle)) {
	    			if(in_array($file, $exclude)){
	    				continue;
	    			}
	    			if ($file != "." && $file != "..") {
	    				if (!is_dir($path."/".$file))
	    					@unlink($path."/".$file);
	    				else
	    					$this->rmdirectoryfiles($path.'/'.$file);
	    					rmdir($path.'/'.$file);
	    			}
	    		}
    		closedir($dir_handle);
    		
    		return true;
    	
    }
    
    public function parse_kml(){
    	
    	print "<pre>";
    	$file_path = 'assets/kml/2015_cumulative_Flood_Indus_16Jul-13Aug.kml';
    	$xml = simplexml_load_file($file_path);
    	foreach($xml->Document->Folder->Placemark->MultiGeometry->Polygon as $polygon){
    		//print_r($polygon->outerBoundaryIs->LinearRing->coordinates[0]);
    		//$lat_long = explode(',',$polygon->outerBoundaryIs->LinearRing->coordinates[0]);
    		$lat_long = $polygon->outerBoundaryIs->LinearRing->coordinates[0];
    		$lat_long = str_replace(' ', '#', $lat_long);
    		$lat_long = str_replace(',', ' ', $lat_long);
    		$lat_long = str_replace('#', ',', $lat_long);
    		$poligon = 'POLYGON(('.$lat_long.'))';
    		
    		$asign_array = array('type' => '1', 'name' => 'kml','poligon'=>$poligon);
    		$this->db->insert('kml_poligon', $asign_array);
    		
    		
    	}
    	exit;
    }
    

    
    public function importgodkdata($slug){

    	$last_id = $slug;//this is a starting limit of next 1000 records.
    	ini_set('memory_limit', '-1');
    	ini_set('max_execution_time', 1200);
 		print "<pre>";
	    $url = file_get_contents("http://godk.itu.edu.pk/api/exportDataFormBasedLimit/?last_id=".$last_id);

	    $records = json_decode($url, 1);
	    
	    $inserted = 0;
	    foreach($records['data'] as $rec)
	    {
	    	
	    	$remote_id = $rec['remote_id'];
	    	$created_datetime = $rec['created_datetime'];
	    	
	    	//echo "SELECT * FROM zform_5202 WHERE imei_no = '$imei_no' AND created_datetime='$created_datetime'<br />";
	    	$query = $this->db->query("SELECT * FROM zform_5202 WHERE remote_id = '$remote_id'");
	    	$api_data = $query->row_array();
	    	if (count($api_data)<1) {
	    		//print_r($rec);
	    		$inserted++;
		    	$image = $rec['image'];
		    	if(isset($rec['is_take_picture']))
		    	{
		    		unset($rec['is_take_picture']);
		    	}
		    	if(isset($rec['Address']))
		    	{
		    		unset($rec['Address']);
		    	}
		    	unset($rec['image']);
		    	//unset($rec['remote_id']);
		    	unset($rec['is_deleted']);
		    	$rec['form_id']='5202';
		    	$rec['version_name']='1.1';
		    	$rec['location_source']='gps';
		    	$rec['time_source']='gps';
		    	$rec['activity_datetime']=$rec['created_datetime'];
		    	//print_r($rec);
		    	$this->db->insert('zform_5202', $rec);
		    	$form_result_id_new = $this->db->insert_id();
		    	
		    	$add_images = array(
		    			'zform_result_id' => $form_result_id_new,
		    			'form_id' => '5202',
		    			'image' => $image,
		    			'title' => ''
		    	);
		    	
		    	$this->db->insert('zform_images', $add_images);
	    	}
	    }
	    echo 'Inserted Record='.$inserted;
	    
    }
    public function importcsvdata(){

    	//$last_id = $slug;//this is a starting limit of next 1000 records.
    	ini_set('memory_limit', '-1');
    	ini_set('max_execution_time', 1200);
    	
    	
    	$file_path = 'assets/data/salik_schools_with_latitude_longitude.csv';
    	$row = 1;
    	if (($handle = fopen($file_path, "r")) !== FALSE) {
    		while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
    	
    			if ($row == 1) {
    				if ($data[0] != 'school_emis_code' && $data[1] != 'school_name' && $data[2] != 'school_latitude' && $data[3] != 'school_longitude') {
    	
    					print "<br />Its not a right formated file. <br />";
    					break;
    				}
    				$row++;
    			} else {
    				$row++;
    					
				    	$rec['form_id']='5211';
				    	$rec['location']=$data[2].','.$data[3];
				    	$rec['School_Name']=$data[1];
				    	$rec['school_emis_code']=$data[0];
				    	$rec['imei_no']='000000000000000';
				    	$rec['version_name']='1.1';
				    	$rec['location_source']='gps';
				    	$rec['time_source']='gps';
				    	$rec['activity_datetime']=date('Y-m-d H:i:s');
				    	$rec['created_datetime']=date('Y-m-d H:i:s');
				    	
				    	$this->db->insert('zform_5211', $rec);
				    	
				    	//$form_result_id_new = $this->db->insert_id();
    			}
    		}
    		fclose($handle);
    	}

	    echo 'Inserted Record='.$row;
	    
    }
    
    public function moveschoolrec() {
    	
    	exit;
    	$query = $this->db->query("SELECT * FROM zform_5202 WHERE  Category LIKE '%school%'");
	    	
    	$form_rec = $query->result_array();
    	foreach($form_rec as $rec)
    	{
    		$rec['form_id']='5206';
    		$id_res = $rec['id'];
    		unset($rec['id']);
    		$this->db->insert('zform_5206', $rec);
    		$form_result_id_new = $this->db->insert_id();
    		
    		
    		$query_img = $this->db->query("SELECT * FROM zform_images WHERE  form_id=5202 and  zform_result_id=".$id_res);
    		$img_data = $query_img->row_array();
    		if(count($img_data)>0){
	    		$add_images = array(
			    			'zform_result_id' => $form_result_id_new,
			    			'form_id' => '5206',
			    			'image' => $img_data['image'],
			    			'title' => $img_data['title']
			    	);
		    	
		    	$this->db->insert('zform_images', $add_images);
    		}
    	}

    }
    
    
    
    function deleteamazonimages(){

        $site_settings = $this->site_model->get_settings('1');
        $s3_access_key = $site_settings ['s3_access_key'];
        $s3_secret_key = $site_settings ['s3_secret_key'];
        // instantiate the class
        $this->load->library('S3');
        $s3 = new S3($s3_access_key, $s3_secret_key);
        
        //$this->load->dbforge();
        $forms = $this->form_model->get_form();
        $i=0;
        $deleted_images = 0;
        foreach ($forms as $form) {
            if ($form['is_deleted'] == '1') {
                $form_id = $form['id'];
                $table_exist_bit =
				$this->form_results_model->
				check_table_exits('zform_' . $form_id);

                if (is_table_exist('zform_' . $form_id)) {
                    $query_form = $this->db->query("SELECT * FROM zform_" . $form_id);
                    $form_results = $query_form->result_array();
                    if($form_results){
                        foreach ($form_results as $f_result) {
                            $query_form_img = $this->db->query("SELECT * FROM zform_images where form_id=".$form_id." AND zform_result_id=".$f_result['id']);
                            $form_result_img = $query_form_img->result_array();
                            if($form_result_img){
                                foreach ($form_result_img as $i_result) {
                                    if(strstr($i_result['image'], '.amazonaws')){

                                        $split1 = explode('//', $i_result['image']);
                                        $split2 = explode('.', $split1[1]);
                                        $split3 = explode('/', $split1[1]);
                                       
                                        $bucket_name = $split2[0];
                                        echo $image_name = $split3[count($split3)-1].'<br >';
                                        if ($s3_access_key != '' && $s3_secret_key != '') {
//                                            $deleted_result = $s3->deleteObject($bucket_name, $image_name);
//                                            if($deleted_result){
//                                                $this->db->delete('zform_images', array('id' => $i_result['id']));
//                                                $deleted_images++;
//                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        echo $deleted_images. " Images has been deleted on S3 server ";
    }
    function deleteamazonimagesdirect(){

        $site_settings = $this->site_model->get_settings('1');
        $s3_access_key = $site_settings ['s3_access_key'];
        $s3_secret_key = $site_settings ['s3_secret_key'];
        // instantiate the class
        $this->load->library('S3');
        $s3 = new S3($s3_access_key, $s3_secret_key);
        
        //$this->load->dbforge();
        $forms = $this->form_model->get_form();
        $i=0;
        $deleted_images = 0;
        
         $query_form_img = $this->db->query("SELECT * FROM zform_images");
         $form_result_img = $query_form_img->result_array();
         
         
         foreach ($form_result_img as $i_result) {
             if (!is_table_exist('zform_' . $i_result['form_id'])){
                    if(strstr($i_result['image'], '.amazonaws')){

                    $split1 = explode('//', $i_result['image']);
                    $split2 = explode('.', $split1[1]);
                    $split3 = explode('/', $split1[1]);

                    $bucket_name = $split2[0];
                    echo $image_name = $split3[count($split3)-1].'<br >';
                    if ($s3_access_key != '' && $s3_secret_key != '') {
    //                                            $deleted_result = $s3->deleteObject($bucket_name, $image_name);
    //                                            if($deleted_result){
    //                                                $this->db->delete('zform_images', array('id' => $i_result['id']));
    //                                                $deleted_images++;
    //                                            }
                    }
                }
                 
             }

        }
                            
                            
        foreach ($forms as $form) {
            if ($form['is_deleted'] == '1') {
                $form_id = $form['id'];

                if (is_table_exist('zform_' . $form_id)) {
                    $query_form = $this->db->query("SELECT * FROM zform_" . $form_id);
                    $form_results = $query_form->result_array();
                    if($form_results){
                        foreach ($form_results as $f_result) {
                            $query_form_img = $this->db->query("SELECT * FROM zform_images where form_id=".$form_id." AND zform_result_id=".$f_result['id']);
                            $form_result_img = $query_form_img->result_array();
                            if($form_result_img){
                                foreach ($form_result_img as $i_result) {
                                    if(strstr($i_result['image'], '.amazonaws')){

                                        $split1 = explode('//', $i_result['image']);
                                        $split2 = explode('.', $split1[1]);
                                        $split3 = explode('/', $split1[1]);
                                       
                                        $bucket_name = $split2[0];
                                        echo $image_name = $split3[count($split3)-1].'<br >';
                                        if ($s3_access_key != '' && $s3_secret_key != '') {
//                                            $deleted_result = $s3->deleteObject($bucket_name, $image_name);
//                                            if($deleted_result){
//                                                $this->db->delete('zform_images', array('id' => $i_result['id']));
//                                                $deleted_images++;
//                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        echo $deleted_images. " Images has been deleted on S3 server ";
    }
    
//    function remove_security_key($slug){
//        $form_id = 3748;//$slug;
//        $form_info = $this->form_model->get_form($form_id);
//        $this->db->select();
//        $this->db->from('zform_' . $form_id);
//        //$this->db->like('activity_type', 'W43oJig9Vw');
//        $this->db->where('id between 26004 and 27213');
//        //$this->db->limit(1000);
//        $query = $this->db->get();
//        $form_result_list = $query->result_array();
//        //print "<pre>";
////        print_r($form_result_list);
////        exit;
//        $skip_array = array('id','form_id','activity_status','imei_no','location','uc_name','town_name','district_name','is_deleted','version_name','location_source','time_source','post_status','activity_datetime','created_datetime');
//        foreach ($form_result_list as $fkey => $fv) {
//            //print_r($fv);
//            $up_array = array();
//            $rec_id = $fv['id'];
//            foreach($fv as $key =>$v){
//                //echo $key.'=>'.$v.'<br>';
//                if(in_array($key, $skip_array) || $v == ''){
//                    $up_array[$key] = $v;
//                }
//                elseif($form_info['security_key'] == ''){
//                    $vdcode = urldecode(base64_decode($v));
//                    $up_array[$key] = $vdcode;
//                }
//                elseif(strpos($v, $form_info['security_key']) !== FALSE){
//                    $vdcode = urldecode(base64_decode(str_replace($form_info['security_key'], '', $v)));
//                    $up_array[$key] = $vdcode;
//                }
//                else{
//                    $up_array[$key] = $v;
//                }
//            }
//            unset($up_array['id']);
//            //print_r($up_array);
//            
//            $this->db->where('id', $rec_id);
//            $this->db->update('zform_' . $form_id, $up_array);
//            
//        }
//        exit;
//        
//    }
    
    public function getlandfillsitedata() {
        //$api = $this->input->get('api');
        //$secret = $this->input->get('secret');
        $points = array();
        $query = $this->db->query("SELECT zf. * , GROUP_CONCAT( tl.point_location SEPARATOR '#' ) points
FROM `zform_356` zf
LEFT JOIN zform_356_Take_Location tl ON zf.id = tl.zform_result_id
GROUP BY zf.id");
        $point_data = $query->result_array();
        if ($point_data) {
           $points['records'] = $point_data;
        } else {
            $points['error'] = 'No data found';
        }


        //usort($options ['options'], 'cmpBySort');
        echo json_encode($points);
        exit();
    }
    public function getcontainerdata() {
        //$api = $this->input->get('api');
        //$secret = $this->input->get('secret');
        $points = array();
        $query = $this->db->query("SELECT * FROM `zform_335`");
        $point_data = $query->result_array();
        if ($point_data) {
           $points['records'] = $point_data;
        } else {
            $points['error'] = 'No data found';
        }


        //usort($options ['options'], 'cmpBySort');
        echo json_encode($points);
        exit();
    }
    public function deletebeforethreemonth(){
        $quer = $this->db->query("DELETE FROM `mobile_activity_log` WHERE DATE(`created_datetime`) < DATE(NOW() - INTERVAL 90 DAY) and error='submitted'");
    print_r($quer);
        die('Delete 3 month old data');
        
    }

    public function pestscancommafix() {
        $query = $this->db->query("SELECT * FROM zform_336_Select_Pest_Details WHERE  Pest_Disease_Name_loop LIKE '%,%'");
        $counter_added = 0;
        $counter_editable = 0;
        $form_rec = $query->result_array();
        $counter_editable = count($form_rec);
        foreach($form_rec as $rec)
        {
            $sub_rec = explode(',',$rec['Pest_Disease_Name_loop']);
            $id_res = $rec['id'];
            unset($rec['id']);

            foreach ($sub_rec as $key => $value) {
                if(!empty($value))
                {
                    $rec['Pest_Disease_Name_loop']=$value;
                    $this->db->insert('zform_336_Select_Pest_Details', $rec);
                    $counter_added++;
                }
                //print_r($rec);
            }
            $this->db->where('id', $id_res);
            $this->db->delete('zform_336_Select_Pest_Details');
        }
        echo "Total wrong activities : ".$counter_editable;
        echo "<br >Added new activities after correction : ".$counter_added;

    }
	
	/*
     * sendToRemoteServerCustomDataA
     * A custom function to get custom query data for evaccs-kpk from dataplug-pitb-server.
	 * Author : Rana Tariq Yaqub
	 * Datetime : 2017-10-12 12:51:51
     * */
	
	public function sendToRemoteServerCustomDataA() {
        ini_set('memory_limit', '-1');
        if (isset($_REQUEST ['app_id']) && isset($_REQUEST ['security_token'])) {
            $app_id = $_REQUEST ['app_id'];
            if(isset($_REQUEST ['from_date_stamp']) && !empty($_REQUEST ['from_date_stamp'])){
                $pos = strpos($_REQUEST ['from_date_stamp'], ':');
                $from_date_stamp = $_REQUEST ['from_date_stamp'];
                if ($pos === false) {
                    $from_date_stamp = $_REQUEST ['from_date_stamp']." 00:00:00";
                }
            }
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
                    $results = $this->form_results_model->syncDataFromRemoteServerCustomData($_REQUEST ['form_id'], $from_date_stamp, $to_date_stamp,$imei_no);
                    $results_count += count($results);
                    foreach ($results as $rec) {
						
						# No images are fetched in this query for this function.
                        // if ($rec ['zimages'] != '') {
                            // $ex_row = explode('#', $rec ['zimages']);
                            // $im_ar = array();
                            // foreach ($ex_row as $row) {
                                // if ($row != '') {
                                    // $ex_col = explode('|', $row);
                                    // (isset($ex_col [0])) ? $image = $ex_col [0] : $image = '';
                                    // (isset($ex_col [1])) ? $title = $ex_col [1] : $title = '';
                                    // $im_ar [] = array(
                                        // 'image' => $image,
                                        // 'title' => $title
                                    // );
                                // }
                            // }
                            // unset($rec ['zimages']);
                            // $rec ['images'] = $im_ar;
                        // }

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
                        $results = $this->form_results_model->
						syncDataFromRemoteServer($forms ['form_id'],
						$from_date_stamp, $to_date_stamp,$imei_no);
                        $results_count += count($results);
                        foreach ($results as $rec) {
                            
                            if ($rec ['zimages'] != '') {
                                $ex_row = explode('#', $rec ['zimages']);
                                $im_ar = array();
                                foreach ($ex_row as $row) {
                                    if ($row != '') {
                                        $ex_col = explode('|', $row);
                                        (isset($ex_col [0])) ? $image = $ex_col [0] : $image = '';
                                        (isset($ex_col [1])) ? $title = $ex_col [1] : $title = '';
                                        $im_ar [] = array(
                                            'image' => $image,
                                            'title' => $title
                                        );
                                    }
                                }
                                unset($rec ['zimages']);
                                $rec ['images'] = $im_ar;
                            }

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

    public function set_lhs_data()
    {



       
     //print "<pre>";

                
                $this->db->select('*');
                $this->db->from('zform_352');
                //$this->db->where('id', '12186');
                $this->db->like('B_Complex_Syp_Stock_Available', ',');
                //$this->db->order_by('id', 'DESC');
                $this->db->limit('10000');
                $query = $this->db->get();
                
                $post_available = $query->result_array();
                //print_r($post_available);
                // $total_resent_rec = count($post_available);
                // $count_resesnd = 0 ;
                // echo "Total record need to resend on Form #".$form_id." 
				//= ".$total_resent_rec.'<br />';
                
                //if ($total_resent_rec > 0) {
                $update = false;
                $total_updates = 0;
                    
                    foreach ($post_available as $k_post => $v_post){
                        //print_r($v_post);
                        
                        if (strpos($v_post['B_Complex_Syp_Stock_Available'], ',') !== FALSE){
                            $update = true;
                            $explode_bcomplex = explode(',', $v_post['B_Complex_Syp_Stock_Available']);
                            //print_r($explode_bcomplex);
                            $v_post['B_Complex_Syp_Stock_Available'] = $explode_bcomplex[0];
                            $v_post['B_Complex_Syp_Number_of_Days_Out_of_Stock'] = $explode_bcomplex[1];
                            $v_post['Oral_Pills_Stock_available'] = $explode_bcomplex[2];
                            $v_post['Oral_Pills_number_of_days_out_of_stock'] = $explode_bcomplex[3];
                            $v_post['Chlorehexidine_stock_Available'] = $explode_bcomplex[4];
                            $v_post['Chlorehexidine_number_of_days_out_of_stock'] = $explode_bcomplex[5];
                            $v_post['Condoms_stock_available'] = $explode_bcomplex[6];
                            $v_post['Condoms_number_of_days_out_of_stock'] = $explode_bcomplex[7];
                            $v_post['Contraceptive_Inj_stock_available'] = $explode_bcomplex[8];
                            $v_post['Contraceptive_Inj_number_of_days_out_of_stock'] 
							= $explode_bcomplex[9];
                            if(isset($explode_bcomplex[100]))
                                $v_post['Eye_Ointment_stock_available'] = $explode_bcomplex[10];

                        }                 

                        if (strpos($v_post['Iron_Tab_stock_available'], ',') !== FALSE){
                            $update = true;
                            $explode_iron = explode(',', $v_post['Iron_Tab_stock_available']);
                            //print_r($explode_iron);
                            $v_post['Iron_Tab_stock_available'] = $explode_iron[0];
                            $v_post['Iron_Tab_number_of_days_out_of_stock'] = $explode_iron[1];
                            $v_post['Number_of_eligible_couples_woman_aged_15_to_49_years']
							= $explode_iron[2];
                            $v_post['number_of_Total_FP_modern_method_users'] = $explode_iron[3];
                            $v_post['number_of_new_FP_users'] = $explode_iron[4];
                            $v_post['Number_of_1st_Injectables_new_Referrals'] = $explode_iron[5];
              

                        } 


                        $sam_under_six = '';

                        if($v_post['Number_of_Referrals_for_SAM_under_6_Month'] != ''){
                            $sam_under_six = $v_post['Number_of_Referrals_for_SAM_under_6_Month'];

                        }elseif($v_post['Number_of_Referrals_for_SAM_under_6_Month_orig'] != ''){
                            $sam_under_six = $v_post['Number_of_Referrals_for_SAM_under_6_Month_orig'];

                        }

                        if (strpos($sam_under_six, ',') !== FALSE){
                            $update = true;
                            $explode_sam = explode(',', $sam_under_six);
                            //print_r($explode_iron);
                            $v_post['Number_of_children_SAM_under_weight'] = $explode_sam[0];
                            $v_post['Number_of_Referrals_for_SAM_under_6_Month'] = $explode_sam[1];
                            $v_post['Number_of_Referrals_for_SAM_under_6_Month_orig'] = $explode_sam[1];

                        }


                        $muac = '';

                        if($v_post['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months']
						!= ''){
                            $muac = 
							$v_post['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months'];

                        }
						elseif($v_post
						['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months_orig']
						!= ''){
                            $muac = 
							$v_post['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months_orig'];

                        }

                        if (strpos($muac, ',') !== FALSE){
                            $update = true;
                            $explode_muac = explode(',', $muac);
                            //print_r($explode_iron);
                            $v_post['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months']
							= $explode_muac[0];
                            $v_post['Number_of_Children_MUAC_done_and_recorded_Aged_6_23_Months_orig'] 
							= $explode_muac[0];
                            $v_post['Number_of_Referrals_for_MAM_Aged_6_23_Months']
							= $explode_muac[1];

                        }

                        if($update){
                            //echo 'Updated';
                            $update = false;
                            $total_updates++;
                           
                            $this->db->where('id', $v_post['id']);
                            $this->db->update('zform_352', $v_post);
                        }
                        else{
                            //echo 'Not Updated';
                        }


                        //print_r($v_post);


                    }
                    echo 'Total Updated Records = '.$total_updates;

                    //print_r($post_available);
                //}
    }
 public function get_virtual_path($physical_path) {
        $physical_path = str_replace('\\', '/', $physical_path);  
        $document_root_path = str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"]);
        $path=  str_replace($document_root_path, "", $physical_path);
        $http = isset($_SERVER ['HTTPS']) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return  $http . $host . $path; 

    }

    public function image_move_in_local_folder(){

        //unlink('/NFS-Dataplug/images/immmm.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        //unlink('/NFS-Dataplug/images/1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg');
        
        //rmdir('/NFS-Dataplug/images/app_id_1998');
        //rmdir('/NFS-Dataplug/live/dev');     

        //get image which save on local folder
       // exit;
        $this->db->select('*');
        $this->db->from('zform_images');
        $this->db->like('image', 'dataplug.itu.edu.pk/assets');
        $this->db->limit(100000);
        $query1 = $this->db->get();
        $img_available1 = $query1->result_array();
        
        $image_array_post = array();
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
                $source_path = 
				"/var/www/vhosts/dataplug.itu.edu.pk/htdoc/assets/images/data/form-data/"
				.$image_name;
                if(file_exists($source_path)){
                    @mkdir(NFS_IMAGE_PATH.'/app_id_'.$app_id);
                    $file_name = NFS_IMAGE_PATH."/app_id_$app_id/".$image_name;
                    $dest_path = NFS_IMAGE_PATH."/app_id_$app_id/".$image_name;
                    copy($source_path, $dest_path);

                    $this->db->where('id',$image_id);
                    $this->db->update('zform_images',array('image'=>$file_name));
                    unlink($source_path);
                }
            }
        }
    }

    public function show_image(){

        // $img = base64_encode('27c7a6f7f7aaf0349cadd7386a0b16ef.jpg@@@@1998');
        // echo base64_decode($img);
        ?> <img src="<?php echo  
		get_image_path('/app_id_1998/27c7a6f7f7aaf0349cadd7386a0b16ef.jpg')?>" />
        <?php 
    }
    public function image_download_from_amazon(){
        //get images which save on s3
        //seperate image name
        //get app_id
        //get form_id
        //create app_id folder
        //download file on app_id folder
        //update url on db
        //remove file from s3
    }

    
}



