<?php

class Site_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getSettings($site_id) {
        $query = $this->db->get_where('site_settings', array('id' => $site_id));
        return $query->row_array();
    }

    /**
     * Return all log with pagination
     * @param type $limit,$start
     * @return type
     * @author: ubaidullah balti <ubaidcskiu@gmail.com>
     */
    public function getAllLog($limit, $start) {
        $this->db->select('*');
        $this->db->from('log');
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
//        echo $this->db->last_query(); die;
        return $query->result_array();
    }

    public function getAllLogAjax($limit=null,$length=null,$search0=null,$search1=null,$search2=null,$search3=null,$search6=null,$search7=null,$search10=null,$sort_column=null,$sort_order=null) {
        $allColumns=array("changed_by_name",
                        "department_name",
                        "action_type",
                        "action_description",
                        "before_record",
                        "after_record",
                        "app_name",
                        "form_name",
                        "controller",
                        "method",
                        "created_datetime");

        $this->db->select('*');
        $this->db->from('log');
        if($limit!=null && $length!=null){
            $this->db->limit($length,$limit);
        }

        if($search0!=null) {
            $this->db->like('changed_by_name', "$search0");
        }
        if($search1!=null) {
            $this->db->like('department_name', "$search1");
        }
        if($search2!=null) {
            $this->db->like('action_type', "$search2");
        }
        if($search3!=null){
            $this->db->like('action_description', "$search3");
        }
        if($search6!=null){
            $this->db->like('app_name', "$search6");
        }
        if($search7!=null){
            $this->db->like('form_name', "$search7");
        }

        if($search10!='' && $search10!=null && $search10!='~') {

            $date_arr=explode("~",$search10);
            $from=$date_arr[0];
            $to=$date_arr[1];
            if($to==''){
                $to=date("Y-m-d H:i:s");
            }else{
                $to=date("Y-m-d H:i:s",strtotime($to));
            }

            if($from==''){
                $from=date("Y-m-d H:i:s");
            }else{
                $from=date("Y-m-d H:i:s",strtotime($from));
            }
//            $this->db->where ("created_datetime BETWEEN '$from' AND '$to'");
            $this->db->where ("created_datetime BETWEEN '$from' AND '$to'");
        }

        if($sort_column!=null && $sort_order!=null){
            $this->db->order_by($allColumns[$sort_column], ucwords($sort_order));
        }else{
            $this->db->order_by('id', 'DESC');
        }

        $query = $this->db->get();
//        echo $this->db->last_query(); die;
        return $query->result_array();
    }

    /**
     * Return all log with counts
     * @return type
     * @author: ubaidullah balti <ubaidcskiu@gmail.com>
     */
    public function getAllLogCount($search0=null,$search1=null,$search2=null,$search3=null,$search6=null,$search7=null,$search10=null) {
        if($search0!=null) {
            $this->db->like('changed_by_name', "$search0");
        }
        if($search1!=null) {
            $this->db->like('department_name', "$search1");
        }
        if($search2!=null) {
            $this->db->like('action_type', "$search2");
        }
        if($search3!=null){
            $this->db->like('action_description', "$search3");
        }
        if($search6!=null){
            $this->db->like('app_name', "$search6");
        }
        if($search7!=null){
            $this->db->like('form_name', "$search7");
        }

        if($search10!='' && $search10!=null && $search10!='~') {
            $date_arr=explode("~",$search10);
            $from=$date_arr[0];
            $to=$date_arr[1];
            if($to==''){
                $to=date("Y-m-d H:i:s");
            }else{
                $to=date("Y-m-d H:i:s",strtotime($to));
            }

            if($from==''){
                $from=date("Y-m-d H:i:s");
            }else{
                $from=date("Y-m-d H:i:s",strtotime($from));
            }
            $this->db->where ("created_datetime BETWEEN '$from' AND '$to'");
        }
        return $table_row_count = $this->db->count_all_results('log');
    }

}

?>
