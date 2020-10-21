<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To dump a variable 
 */

function dump($variable, $die = 0) {
    echo '<pre>';
    print_r($variable);
    echo '<pre>';
    if ($die == 0) {
        die;
    }
}

function app3866($app_id,$imei_no,$record) {
	
	$ci = &get_instance();
	$sms_super_admin = array('03336566326','03004292969','03349971431','03425258699');
	$sms_sending_list = array(
			'Lahore'=>array('03014734115','03224105788'),
			'ATTOCK'=>array('03225095596'),
			'Bhakkar'=>array('03338043041'),
			'Khushab'=>array('03006226994'),
			'Mianwali'=>array('03006090177'),
			'OKARA'=>array('03008997559'),
			'BAHAWALNAGAR'=>array('03347007428'),
			'BAHAWALPUR'=>array('03336012343'),
			'RAHIM YAR KHAN'=>array('03014010095'),
			'DG Khan'=>array('03013940335'),
			'Layyah'=>array('03067680734'),
			'Muzaffar Garh'=>array('03013940335'),
			'Rajanpur'=>array('03426434539'),
			'FAISALABAD'=>array('03006349654'),
			'Jhang'=>array('03007569538'),
			'TOBA TEK SINGH'=>array('03017733122'),
			'Gujranwala'=>array('03456606535'),
			'Gujrat'=>array('03228409512'),
			'Hafizabad'=>array('03214094900'),
			'MANDI BAHAUDDIN'=>array('03219564309'),
			'NAROWAL'=>array('03436467401'),
			'SIALKOT'=>array('03456662725'),
			'Kasur'=>array('03336104076'),
			'Sheikhupura'=>array('03007837696'),
			'Nankana Sahib'=>array('03009470371'),
			'Khanewal'=>array('03337644363'),
			'Lodhran'=>array('03346654082'),
			'Multan'=>array('03216122322','03006520660','03438753642'),
			'PAKPATTAN'=>array('03226936079'),
			'SAHIWAL'=>array('03236462689'),
			'Vehari'=>array('03027399727'),
			'Chakwal'=>array('03335116527'),
			'JHELUM'=>array(),
			'RAWALPINDI'=>array('03345343058'),
			'Sargodha'=>array('03006058878','03009481907'),
			'Chiniot'=>array('03424206484'),
	);
	
	$app_users_for_sms = $ci->db->get_where('app_users', array('imei_no' => $imei_no, 'app_id' => $app_id ,'is_deleted' => 0))->row_array();
	$message_to_send="Dear Sir/Madam ,\r\n \r\nThe following perceived threat has been found in ".$app_users_for_sms['district'].". \r\nPerceived threat : ".$record['Perceived_Threat']." \r\nComments : ".$record['Comments']." \r\n \r\n Regards,\r\n Civil Defence";
	if($sms_sending_list[$app_users_for_sms['district']]){
		foreach($sms_sending_list[$app_users_for_sms['district']] as $mobile_number){
			send_sms($mobile_number,$message_to_send);
		}
	}
	 
	foreach($sms_super_admin as $mobile_number_super){
		send_sms($mobile_number_super,$message_to_send);
	}
}

function app3883($app_id,$imei_no,$record){
	$ci = &get_instance();
		
	$location = explode(',',$record['location']);
	$query = "SELECT WITHIN( GEOMFROMTEXT( 'POINT($location[1] $location[0])' ) , GEOMFROMTEXT(kml_poligon.poligon) ) AS 'geo_status' FROM kml_poligon";
	$rec = $ci->db->query($query);
	$poli_array = $rec->result_array();
	$poli_status = 0;
	foreach($poli_array as $poli){
		if($poli['geo_status']=='1'){
			$poli_status=1;
			break;
		}
	
	}
	
	if($poli_status==0){
	
		$ci->load->library('email');
		$ci->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
		$ci->email->to('pdma2015rehabilitation@gmail.com,floods2015@punjab.gov.pk,dgpdmalahore@gmail.com');
		$ci->email->cc('mansoor.rehman@pitb.gov.pk,salik.farooq@pitb.gov.pk,zahid.nadeem@pitb.gov.pk');
		
		$ci->email->subject('Damage Assessment Survey - Outlying Activity Pins');
		$message = "Dear sir,<br />This email message is to inform that the following damage assessment survey has been conducted outside the defined inundated area boundaries:";
		$message .= "<br /><br />District name: ".$record['district_name'];
		$message .= "<br />Tehsil name: ".$record['Tehsil'];
		$message .= "<br />Patwar Circle: ".$record['Patwar_Circle'];
		$message .= "<br />Mouza: ".$record['Mauza'];
		$message .= "<br />Citizen Name: ".$record['name'];
		$message .= "<br />GPS Coordinates: ".$record['location']."<br>";
		$message .= "<br />Note: This is system generated e-mail. Please do not reply";
		$message .= "<br /><b>".PLATFORM_NAME."</b>";
		$ci->email->message($message);
		
		$ci->email->set_mailtype('html');
		$ci->email->send();
		$message_to_send="Dear Sir,\r\n \r\nThis message is to inform that the following damage assessment survey has been conducted outside the defined inundated area boundaries:\r\nDistrict name: ".$record['district_name']." \r\nTehsil name: ".$record['Tehsil'].". \r\nPatwar Circle: ".$record['Patwar_Circle']."\r\nMouza: ".$record['Mauza']." \r\n GPS Coordinates: ".$record['location'];
		//send_sms('03336566326', $message_to_send);
	}
	
	
	
	
	//"SELECT WITHIN( GEOMFROMTEXT( 'POINT(69.697265625 28.195312500000057)' ) , GEOMFROMTEXT(kml_poligon.poligon) ) AS `geo_status` FROM kml_poligon";
}

function lan_lng_distance($center_latitude, $center_longitude, $latitude, $longitude, $unit='') {

    $theta = $center_longitude - $longitude;
    $dist = sin(deg2rad($center_latitude)) * sin(deg2rad($latitude)) +  cos(deg2rad($center_latitude)) * cos(deg2rad($latitude)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
//    $unit = strtoupper($unit);
    return ($miles * 1.609344 * 1000);
//    if ($unit == "K") {
//        return ($miles * 1.609344);
//    } else if ($unit == "N") {
//        return ($miles * 0.8684);
//    } else {
//        return $miles;
//    }
}