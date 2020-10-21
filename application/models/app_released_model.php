<?php

class App_Released_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_released($slug = FALSE) {

        if ($slug === FALSE) {
            $query = $this->db->get('app_released');
            return $query->result_array();
        }

        $this->db->from('app_released');
        $this->db->where('app_id', $slug);
        $this->db->order_by("version", "desc");
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_latest_released($slug = FALSE) {

        $this->db->from('app_released');
        $this->db->where('app_id', $slug);
        $this->db->order_by("version", "desc");
        $this->db->limit(1);
        
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_max_released_version($slug = FALSE) {


        $this->db->select_max('version');
        $this->db->where('app_id', $slug);
        $query = $this->db->get('app_released');
        return $query->row()->version;
    }
    
    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_max_released_version_code($slug = FALSE) {


        $this->db->select_max('version_code');
        $this->db->where('app_id', $slug);
        $query = $this->db->get('app_released');
        return $query->row()->version_code;
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_version_against_version_code($app_id,$version_code) {

        $this->db->select('version');
        $this->db->where('app_id', $app_id);
        $this->db->where('version_code', $version_code);
        $query = $this->db->get('app_released');
        return $query->row()->version;
    }

}

?>