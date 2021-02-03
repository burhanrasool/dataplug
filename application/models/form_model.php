<?php

class Form_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getForm($slug = FALSE, $view_id = null) {


        if ($slug === FALSE) {
            $query = $this->db->get('form');
            return $query->result_array();
        }




        $this->db->select('form.*,fv.description as fv_description, fv.full_description as fv_full_description, fv.id as fvid, fv.post_url as fv_post_url');
        $this->db->from('form');
        if ($view_id) {
            $this->db->join('form_views fv', 'fv.form_id = form.id AND fv.view_id="' . $view_id . '"', 'left');
        } else {
            $this->db->join('form_views fv', 'fv.form_id = form.id', 'left');
        }
        $this->db->where('form.is_deleted', '0');
        $this->db->where('form.id', $slug);

        $query = $this->db->get();

        return $query->row_array();

//        $query = $this->db->get_where('form', array('id' => $slug));
//        return $query->row_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getFormByApp($app_id, $user_id = null) {
        $this->db->select('app.id as app_id, app.name as app_name,app.type,app.module_name, form.id as form_id, form.name as form_name,form.icon as form_icon, form.description,form.full_description,form.next,form.icon');
        $this->db->from('form');
        $this->db->join('app', 'app.id = form.app_id');

        $this->db->where('form.is_deleted', '0');
        $this->db->where('form.app_id', $app_id);
        if ($user_id) {
            $this->db->where('app.user_id', $user_id);
        }

        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    public function getFormByApPForAppSettings($app_id, $user_id = null) {
        $this->db->select('app.id as app_id, app.name as app_name, form.*');
        $this->db->from('form');
        $this->db->join('app', 'app.id = form.app_id');

        $this->db->where('form.is_deleted', '0');
        $this->db->where('form.app_id', $app_id);
        if ($user_id) {
            $this->db->where('app.user_id', $user_id);
        }

        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    /**
      New Instance for multiple forms
     */
    public function getFormList($app_id) {
        $this->db->select('id,app_id');
        $this->db->from('form');
        $this->db->where('is_deleted', '0');
        $this->db->where('app_id', $app_id);


        $query = $this->db->get();
//        echo $this->db->last_query(); die;
        return $query->result_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getFormByAppView($app_id, $view_id) {
        $this->db->select('app.id as app_id, app.name as app_name, form.id as form_id, form.name as form_name,form.icon as form_icon, fv.description,fv.full_description,form.next,form.icon,fv.id as fvid');
        $this->db->from('form');
        $this->db->join('app', 'app.id = form.app_id');
        $this->db->where('form.is_deleted', '0');
        $this->db->where('form.app_id', $app_id);
        $this->db->join('form_views fv', 'fv.form_id = form.id', 'left');
        $this->db->where('fv.view_id', $view_id);

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getEmptyAppForm($app_id) {
        $this->db->select('app.id as app_id, app.name as app_name, form.id as form_id, form.name as form_name, form.description,form.full_description,form.next');
        $this->db->from('form');
        $this->db->join('app', 'app.id = form.app_id', 'left outer');
        $this->db->where('form.app_id', $app_id);
        $this->db->where('form.description !=', '');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $form_name
     * @param type $app_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Form_Already_Exist($form_name, $app_id, $form_id = null) {

        if ($form_id) {
            $query = $this->db->get_where('form', array('name' => $form_name, 'app_id' => $app_id, 'id !=' => $form_id, 'is_deleted' => '0'));
        } else {
            $query = $this->db->get_where('form', array('name' => $form_name, 'app_id' => $app_id, 'is_deleted' => '0'));
        }
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get form filters for multiple
     * with for list 
     */
    public function getFormFilters($form_list = array()) {
        $lists = array();
        foreach ($form_list as $form_entity) {
            array_push($lists, $form_entity['form_id']);
        }
        $this->db->select('id,is_deleted,filter,description,possible_filters');
        $this->db->from('form');
        $this->db->where_in('form.id', $lists);
        $this->db->where('form.is_deleted', 0);

        $query = $this->db->get();
//        echo $this->db->last_query();
//        die;
        return $query->result_array();
    }

    /**
     * Get form filters for multiple
     * with for list etc for ajax call
     */
    public function getFormFiltersOnly($form_id) {

        $this->db->select('filter,possible_filters');
        $this->db->from('form');
        $this->db->where('form.id', $form_id);
        $this->db->where('form.is_deleted', 0);

        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getComments($app_id) {
        $this->db->select("ac.*, u.first_name  , u.last_name");
        $this->db->from('app_comments ac');
        $this->db->join('users u', 'ac.user_id = u.id');
        $this->db->where('ac.app_id', $app_id);
        $this->db->order_by('ac.created_datetime', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFormHistory($form_id,$view_id ,$history_id = null) {
    	$this->db->select('*');
    	$this->db->from('form_history');
    	$this->db->where('form_id', $form_id);
    	$this->db->where('view_id', $view_id);
    	
    	if($history_id){
    		$this->db->where('id', $history_id);
    		$query = $this->db->get();
    		return $query->row_array();
    	}
    	$this->db->order_by('created_datetime', 'DESC');
    
    	$query = $this->db->get();
    	return $query->result_array();
    }

    public function get_uc($form_id){
        $this->db->select('uc');
        $this->db->distinct();
        $this->db->from("zform_$form_id");
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pp($form_id){
        $this->db->select('pp');
        $this->db->distinct();
        $this->db->from("zform_$form_id");
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_na($form_id){
        $this->db->select('town');
        $this->db->distinct();
        $this->db->from("zform_$form_id");
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_form_column_values($all_columns,$form_id){
        $colum_val_arr=array();
        $vals_array=array();
//        echo "<pre>";
//        print_r($all_columns);die;
        if(!empty($all_columns[0])) {
            foreach ($all_columns as $key => $column) {
                if ($column != "sent_by") {
                    $this->db->select($column);
                    $this->db->distinct();
                    $this->db->from("zform_$form_id");
                    $this->db->where('is_deleted', 0);
                    $query = $this->db->get();
                    $result = $query->result_array();
                    foreach ($result as $key1 => $val) {
                        if(strrpos($val[$column],",")!=false && $column!='location') {
                            $val_li = explode(',', $val[$column]);
                            foreach ($val_li as $va) {
//                                if (!in_array($va, $vals_array)) {

                                    $vals_array[] = $va;
//                                }
                            }
                        }else{
//                            if (!in_array($val[$column], $vals_array)) {
                                $vals_array[] = $val[$column];
//                            }
                        }

                        //$vals_array[] = $val[$column];
                    }
                    $colum_val_arr[$column] = $vals_array;
                    unset($result);
                    unset($vals_array);
                    $vals_array=array();
                } else {
                    //get app_id...
                    $app_query = "select app_id from form where id='$form_id'";
                    $app_query = $this->db->query($app_query);
                    $app_result = $app_query->row_array();
                    $app_id = $app_result['app_id'];

                    //get user names and imei numbers...
                    $query = "SELECT fr.*,au.name as sent_by
                        FROM  zform_$form_id fr
                        JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id

                        ";
                    $query = $this->db->query($query);
                    $result = $query->result_array();
                    $sent_by_arr = array();
                    if (!empty($result)) {
                        foreach ($result as $key => $val) {
                            $sent_by_arr[$val['imei_no']] = $val['sent_by'];
                        }
                    }
                    $colum_val_arr[$column] = $sent_by_arr;

                }
            }
        }
//        echo "<pre>";
//        print_r($colum_val_arr);die;
        return $colum_val_arr;
    }

    public function get_column_settings($app_id){
        $this->db->select('columns');
        $this->db->from("form_column_settings");
        $this->db->where('app_id', $app_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
}


?>
