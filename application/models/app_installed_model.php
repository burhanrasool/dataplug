<?php

class App_Installed_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $app_id
     * @param type $imei_no
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppInstalled($app_id,$imei_no) {

        $query = $this->db->get_where('app_installed', array('app_id' => $app_id,'imei_no' => $imei_no));
       
        return $query->row_array();
       
    }
    
    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppInstalledByAppId($app_id) {
        
        $query = $this->db->get_where('app_installed', array('app_id' => $app_id));
        return $query->result_array();
    }

}

?>
