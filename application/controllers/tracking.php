<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tracking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('form_results_model');
    }

    /**
     * This function is used for saving the tracking of single point
     * 
     * @return json
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Save_Tracking() {

        $app_id = $_REQUEST ['appId'];
        $imei_no = $_REQUEST ['imei_no'];
        $location = $_REQUEST ['location'];
        $lat = $_REQUEST ['lat'];
        $lng = $_REQUEST ['lng'];
        $accuracy = $_REQUEST ['accuracy'];
        $altitude = $_REQUEST ['altitude'];
        $speed = $_REQUEST ['speed'];
        $route_id = $_REQUEST ['routeId'];
        $gps_time = date('Y-m-d H:i:s', strtotime($_REQUEST ['gpsTime']));
        $device_tS = date('Y-m-d H:i:s', strtotime($_REQUEST ['deviceTS']));
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
            'created_datetime' => $created_datetime
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


        addTrackingTable($app_id);

        $ret_ins = $this->db->insert('ztracking_' . $app_id, $dataresultnew);

        if (!$ret_ins) {
            $err_msg = $this->db->_error_message();
            $this->form_results_model->update_mobile_tracking($tracking_inserted_id, array('error' => $err_msg));

            echo $jsone_array = array(
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
    public function Save_tracking_Bulk() {
        
        ini_set ( 'memory_limit', '-1' );
        $params = json_decode(file_get_contents('php://input'),true);       
        if(empty($params))    {     $params=$_REQUEST;    }
/*
print_r($params);
print_r($_REQUEST);
print_r($_FILES);
exit;*/



        $app_id = $params ['appId'];
        $imei_no = $params ['imei_no'];
        $route_id = $params ['routeId'];
        $tracking_records = (isset($params['records']))?$params['records']:'';
        if(isset($_FILES['trackingFile'])) {
                $abs_path = './assets/images/data/tracking_files';
                $config['upload_path'] = $abs_path;
                $config['file_name'] = $app_id.'_tracking_'.date('Y-m-d H:i:s');
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'txt';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('trackingFile')) {

                }else{
                  $file_data = $this->upload->data();
                  $tracking_records = file_get_contents($abs_path.'/'.$fileData['file_name']);

                  $tracking_temp = array(
                   'app_id' => $app_id,
                   'data_save' => $tracking_records,
                   'data_type' => 'bulk'
                  );
                  @unlink($abs_path.'/'.$fileData['file_name']);
                }


        }else{

           
            $tracking_temp = array(
             'app_id' => $app_id,
             'data_save' => $tracking_records,
             'data_type' => 'bulk'
            );

        }






      
        $tracking_inserted_temp_id = $this->form_results_model->save_mobile_tracking($tracking_temp);
        //If JSON currept then not save
        $result = json_decode($tracking_records,true);
        if ($result === null) {
            $err_msg = 'Invalid JSON';
            $jsone_array = array('error' => $err_msg);
            $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$err_msg));
            echo json_encode($jsone_array);
            exit;
        }
        
        if(empty($route_id) || $route_id=='' || strlen($route_id)<1){
          $route_id = $result[0]['routeId'];
        }

        addTrackingTable($app_id);
        
        //if this routid already received then not saved
        $this->db->select('*');
        $this->db->from('ztracking_' . $app_id);
        $this->db->where('route_id', $route_id);
        $this->db->where('imei_no', $imei_no);
        $query = $this->db->get();
        $tracking_result= $query->result_array();
        $query->free_result();
        if(count($tracking_result)>0)
        {
            $succ_msg = 'Duplicate JSON';
            $jsone_array = array('success' => $succ_msg);
            $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$succ_msg));
            echo json_encode($jsone_array);
            exit;
        }
        //exit;
        $new_distance_geo=0;
        $last_distance_geo=0; 
        $new_distance=0;
        $last_distance=0;  
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
       
        $gps_time = $params ['gpsTime'];
        $records = $tracking_records;
        $distance_covered = $newDistance;
        $distance_covered_geo = $newDistanceGeo;

        
        $gps_time = date('Y-m-d H:i:s', strtotime($gps_time));
        $created_datetime = date('Y-m-d H:i:s');
        $dataresultnew = array(
            'route_id' => $route_id,
            'imei_no' => $imei_no,
            'gps_datetime' => $gps_time,
            'distanceCovered' => round($newDistance,2),
            'distanceCoveredGeo' => round($distanceCoveredGeo,2),
            'records' => $records,
            'created_datetime' => $created_datetime
        );

try {

      $ret_ins = $this->db->insert('ztracking_' . $app_id, $dataresultnew);
      if (!$ret_ins) {
        $err_msg = $this->db->_error_message();

        $jsone_array = array('error' => $err_msg);
        $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$err_msg));
        echo json_encode($jsone_array);
        exit;
    }
    else{
      $this->form_results_model->remove_mobile_tracking($tracking_inserted_temp_id);
      //$this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>'submitted'));
    }


}catch (Exception $e) {
        $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$e->message()));
        echo json_encode($jsone_array);
        exit;
}









        



        $jsone_array = array(
            'success' => 'Tracking Record submitted successfully!'
        );
        echo json_encode($jsone_array);
        //$this->form_results_model->remove_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
        exit();
    }





        public function Test_Tracking_Bulk1() {
        
        // ini_set ( 'memory_limit', '-1' );
        // $params = json_decode(file_get_contents('php://input'),true);       
        // if(empty($params))    {     $params=$_REQUEST;    }
/*
print_r($params);
print_r($_REQUEST);
print_r($_FILES);
exit;*/





      
        // $tracking_inserted_temp_id = $this->form_results_model->save_mobile_tracking($tracking_temp);
        // //If JSON currept then not save
        // $result = json_decode($tracking_records,true);
        // if ($result === null) {
        //     $err_msg = 'Invalid JSON';
        //     $jsone_array = array('error' => $err_msg);
        //     $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$err_msg));
        //     echo json_encode($jsone_array);
        //     exit;
        // }
        
        // if(empty($route_id) || $route_id=='' || strlen($route_id)<1){
        //   $route_id = $result[0]['routeId'];
        // }

        // add_tracking_table($app_id);
        
        //if this routid already received then not saved
        $this->db->select('*');
        $this->db->from('ztracking_8');
        $this->db->where('id' ,'66268');
        $query = $this->db->get();
        $tracking_result= $query->result_array();
        $query->free_result();
        $result=$tracking_result[0]['records'];
        $result=json_decode($result,true);
        //exit;
        $new_distance_geo=0;
        $lasta_distance_geo=0; 
        $new_distance=0;
        $last_distance=0;  
        foreach($result as $r)  
        {   
          //print_r($r);
          $distance=(float)$r['distance'];   
          $d=$distance-$lastDistance;   
          if($d>0)   {
            $newDistance+=$distance-$lastDistance;   
          }
          echo $r['gpsTime']." =>> ".$newDistance." = ".$distance." - ".$lastDistance."<br />";
          echo $r['id']." -> ".$r['gpsTime']." : D:".$distance." LD:".$lastDistance." ND:".$newDistance."<br />";
          $lastDistance=$distance;    

          //print_r($r);
          $distanceGeo=(float)$r['distanceGeo'];   
          $dGeo=$distanceGeo-$lastDistanceGeo;   
          if($dGeo>0)   {
            $newDistanceGeo+=$distanceGeo-$lastDistanceGeo;   
          }
          $lastDistanceGeo=$distanceGeo;     
        }
       
        // $gps_time = $params ['gpsTime'];
        // $records = $tracking_records;
        $distanceCovered = $newDistance;
        $distanceCoveredGeo = $newDistanceGeo;

        
        // $gps_time = date('Y-m-d H:i:s', strtotime($gps_time));
        // $created_datetime = date('Y-m-d H:i:s');
        // $dataresultnew = array(
        //     'route_id' => $route_id,
        //     'imei_no' => $imei_no,
        //     'gps_datetime' => $gps_time,
        //     'distanceCovered' => round($newDistance,2),
        //     'distanceCoveredGeo' => round($distanceCoveredGeo,2),
        //     'created_datetime' => $created_datetime
        // );
        echo 'coverd='.$distanceCovered;
        echo 'New='.$distanceCoveredGeo;
        exit;

// try {

//       $ret_ins = $this->db->insert('ztracking_' . $app_id, $dataresultnew);
//       if (!$ret_ins) {
//         $err_msg = $this->db->_error_message();

//         $jsone_array = array('error' => $err_msg);
//         $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$err_msg));
//         echo json_encode($jsone_array);
//         exit;
//     }
//     else{
//       $this->form_results_model->remove_mobile_tracking($tracking_inserted_temp_id);
//       //$this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>'submitted'));
//     }


// }catch (Exception $e) {
//         $this->form_results_model->update_mobile_tracking($tracking_inserted_temp_id,array('error'=>$e->message()));
//         echo json_encode($jsone_array);
//         exit;
// }









        



        // $jsone_array = array(
        //     'success' => 'Tracking Record submitted successfully!'
        // );
        // echo json_encode($jsone_array);
        // //$this->form_results_model->remove_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
        // exit();
    }

// This function is for old logic    
    /*    public function savetrackingbulk() {

      $app_id = $_REQUEST ['appId'];
      $imei_no = $_REQUEST ['imei_no'];
      //rootid
      //date

      $records = json_decode($_REQUEST ['records'], true);
      $records['imei_no'] = $imei_no;

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
      unset($records['imei_no']);
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
      'created_datetime' => $created_datetime
      );
      $ret_ins = $this->db->insert('ztracking_' . $app_id, $dataresultnew);

      if (!$ret_ins) {
      $err_msg = $this->db->_error_message();
      $this->form_results_model->update_mobile_tracking($tracking_inserted_id, array('error' => $err_msg));

      echo $jsone_array = array(
      'error' => $err_msg
      );
      exit;
      }
      }


      //$this->form_results_model->remove_mobile_tracking($tracking_inserted_id,array('error'=>$err_msg));
      exit();
      }
     */
    public function Test_Bulk_Tracking($slug) {

        $this->db->select();
        $this->db->from('mobile_tracking_log');
        $this->db->where('id', $slug);
        //$this->db->limit(1000);
        $query = $this->db->get();
        $form_result_list = $query->result_array();
        print "<pre>";
        foreach ($form_result_list as $lkey => $lval) {
            $records = json_decode($lval ['data_save'], true);
//            print_r($records);
//            exit;
            unset($records['imei_no']);
            foreach ($records as $r_key => $r_value) {
                $gps_time = date('Y-m-d H:i:s', strtotime($r_value['gpsTime']));
                $device_ts = date('Y-m-d H:i:s', strtotime($r_value['deviceTS']));
                $created_datetime = date('Y-m-d H:i:s');
                $data_result_new = array(
                    'app_id' => $lval['app_id'],
                    'imei_no' => $r_value['imei_no'],
                    'location' => $r_value['location'],
                    'lat' => $r_value['lat'],
                    'lng' => $r_value['lng'],
                    'gpsTime' => $gpsTime,
                    'deviceTS' => $deviceTS,
                    'accuracy' => $r_value['accuracy'],
                    'altitude' => $r_value['altitude'],
                    'speed' => $r_value['speed'],
                    'route_id' => $r_value['routeId'],
                    'created_datetime' => $created_datetime
                );
                print_r($dataresultnew);
            }
        }

        exit();
    }

    public function getTrackingRec($app_id) {
        ini_set ( 'memory_limit', '-1' );
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");

        $table_name = 'ztracking_' . $app_id;
        if ($app_id != '' && is_table_exist($table_name)) {
            $que = ' 1=1 ';
            $app_id = $app_id;
            if (isset($_REQUEST ['imei_no']) && $_REQUEST ['imei_no'] != '') {
                $que .= " and imei_no ='" . $_REQUEST['imei_no'] . "'";
            }
            if (isset($_REQUEST ['date_time']) && $_REQUEST ['date_time'] != '') {
                $activity_datetime = date('Y-m-d', strtotime($_REQUEST ['date_time']));
                $que .= " and DATE(`gps_datetime`)='" . $activity_datetime . "'";
            }


            $qry_str = "SELECT * FROM $table_name WHERE $que order by gps_datetime desc";
            $query = $this->db->query($qry_str);
            $api_data = $query->result_array();
            //print "<pre>";
            //print_r($api_data);
            
            $out=array();
            $out['status']=false;
            $out['records']=array();
            foreach($api_data as $ad)
            {
                $r=$ad['records'];
                $r=json_decode($r,true);
//                echo "<pre>";
//                print_r($r);
//                exit;
                if(is_array($r))
                    $out['records']=array_merge($out['records'],$r);
                else
                {
//                    print_r($ad);
//                    var_dump($r);
                }
                //exit;
            }
            //exit;
            
            if (count($out['records'])>0)
            {
                $out['status']=true;
                echo json_encode($out);
//                echo json_encode(array("status" => true, "records" => json_encode($out)));
            }else
                echo json_encode(array("status" => false, "message" => "Record not found"));
        }
        else {
            echo json_encode(array("status" => false, "message" => "Please provide correct app_id"));
        }
        exit();
    }
    
    public function gettrackingcomplete($app_id) {
      ini_set ( 'memory_limit', '-1' );
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");

        $table_name = 'ztracking_' . $app_id;
        if ($app_id != '' && is_table_exist($table_name)) {
            $que = ' 1=1 ';
            $app_id = $app_id;
            if (isset($_REQUEST ['imei_no']) && $_REQUEST ['imei_no'] != '') {
                $que .= " and imei_no ='" . $_REQUEST['imei_no'] . "'";
            }
            if (isset($_REQUEST ['date_time']) && $_REQUEST ['date_time'] != '') {
                $activity_datetime = date('Y-m-d', strtotime($_REQUEST ['date_time']));
                $que .= " and DATE(`created_datetime`)='" . $activity_datetime . "'";
            }

            $qry_str = "SELECT * FROM $table_name WHERE $que order by gps_datetime desc";
            $query = $this->db->query($qry_str);
            $api_data = $query->result_array();
            //print "<pre>";
            //print_r($api_data);
            
            
            if (count($api_data)>0)
            {
                echo json_encode($api_data);
            }else
                echo json_encode(array("status" => false, "message" => "Record not found"));
        }
        else {
            echo json_encode(array("status" => false, "message" => "Please provide correct app_id"));
        }
        exit();
    }
    /* This function for old tracking logic
    public function gettrackingrec($app_id) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");

        $table_name = 'ztracking_' . $app_id;
        if ($app_id != '' && is_table_exist($table_name)) {
            $que = ' 1=1 ';
            $app_id = $app_id;
            if (isset($_REQUEST ['imei_no']) && $_REQUEST ['imei_no'] != '') {
                $que .= " and imei_no ='" . $_REQUEST['imei_no'] . "'";
            }
            if (isset($_REQUEST ['date_time']) && $_REQUEST ['date_time'] != '') {
                $activity_datetime = date('Y-m-d', strtotime($_REQUEST ['date_time']));
                $que .= " and created_datetime like '%" . $activity_datetime . "%'";
            }


            $qry_str = "SELECT * FROM $table_name WHERE $que order by deviceTS desc";
            $query = $this->db->query($qry_str);
            $api_data = $query->result_array();
            //print "<pre>";
            //print_r($api_data);
            if ($api_data)
                echo json_encode(array("status" => true, "records" => $api_data));
            else
                echo json_encode(array("status" => false, "message" => "Record not found"));
        }
        else {
            echo json_encode(array("status" => false, "message" => "Please provide correct app_id"));
        }
        exit();
    }*/

}
