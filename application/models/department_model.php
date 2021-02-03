<?php

class Department_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getDepartment($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->order_by('name', 'ASC')->get_where('department', array('is_deleted' => '0'));
            return $query->result_array();
        }

        $query = $this->db->get_where('department', array('id' => $slug, 'is_deleted' => '0'));
        return $query->row_array();
    }

    /**
     * 
     * @param type $slug
     * @return typeG
     * GET public department whose value 
     * is_public is yes
     */
    public function getPublicDepartment($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->order_by('name', 'ASC')->get_where('department', array('is_deleted' => '0', 'is_public' => 'yes'));
            return $query->result_array();
        }

        $query = $this->db->get_where('department', array('id' => $slug, 'is_deleted' => '0', 'is_public' => 'yes'));
        return $query->row_array();
    }

    /**
     * 
     * @param type $department_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Department_Already_Exist($department_name, $department_id = null) {

        if ($department_id) {
            $query = $this->db->get_where('department', array('name' => $department_name, 'is_deleted' => '0', 'id !=' => $department_id));
        } else {
            $query = $this->db->get_where('department', array('name' => $department_name, 'is_deleted' => '0'));
        }
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

}

?>
