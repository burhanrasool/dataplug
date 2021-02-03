<?php

class Settings_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return value
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getParam($slug = FALSE) {
         $query = $this->db->get_where('settings', array('key' => $slug));
         $rec = $query->row_array();

        if ($rec) {
            return $rec['value'];
        } else {
            return FALSE;
        }
    }
}

?>
