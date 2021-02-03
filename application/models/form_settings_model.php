<?php

class Form_Settings_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return value
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getFieldForReports($form_id) {
        $this->db->select('*');
        $this->db->from('form_field_settings');
        $this->db->where('field_on_result', '1');
        $this->db->where('form_id', $form_id);
        $this->db->order_by("sort_order", "asc");
        $query = $this->db->get();
        $field_list = $query->result_array();
            
        $field_array = array();
        foreach ($field_list as $key => $value) {
            $field_value = $value['field_name'];
            if (!in_array($field_value, $field_array)) {
                $field_array = array_merge($field_array, array($field_value));
            }
        }
        return $field_array;

        
    }
    /**
     * 
     * @param type $slug
     * @return value
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getFilterForReports($form_id) {
        $this->db->select('*');
        $this->db->from('form_field_settings');
        $this->db->where('filter_on_result', '1');
        $this->db->where('form_id', $form_id);
        $this->db->order_by("sort_order", "asc");
 
        $query = $this->db->get();
        $field_list = $query->result_array();
       
        $field_array = array();
        foreach ($field_list as $field) {
                $field_array = array_merge($field_array, array($field['field_name']));
        }
//        print "<pre>";
//        print_r($field_array);
//        exit;
        return $field_array;
    }
    
    /**
     * 
     * @param type $slug
     * @return value
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function addNewField($form_id,$field_name) {
        $data=array(
            "form_id"=>$form_id,
            "field_name"=>$field_name,
            "field_show_name"=>trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', urldecode($field_name)))
        );
        $this->db->insert('form_field_settings', $data);
        return $this->db->insert_id();
    }
}

?>
