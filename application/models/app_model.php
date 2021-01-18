<?php

class App_model extends CI_Model {

    public function __construct() {
        $this->load->database();
}   

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app($slug = FALSE,$view_id = null) {
        if ($slug === FALSE) {
            $query = $this->db->get('app');
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }

        $this->db->select('app.*,av.description as av_description, av.full_description as av_full_description, av.id as avid');
        $this->db->from('app');
        if($view_id)
        {
            $this->db->join('app_views av', 'av.app_id = app.id AND av.view_id="'.$view_id.'"', 'left');
        }
        else
        {
            $this->db->join('app_views av', 'av.app_id = app.id AND av.view_id=0', 'left');
        }
        $this->db->where('app.is_deleted', '0');
        $this->db->where('app.id', $slug);

        $query = $this->db->get();

        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_by_department($department_id) {

        $query = $this->db->get_where('app', array('department_id' => $department_id, 'is_deleted' => '0'));
        return $query->result_array();
    }
    /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_by_user($user_id,$limit=null,$lenght=null,$search0=null,$sort_column=null,$sort_order=null) {
        $allColumns=array(
            "name",
            "",
            "created_datetime"
        );
        
        $this->db->select('a.*');
        $this->db->from('app a');
        $this->db->join('users_app ua', 'ua.app_id = a.id AND ua.user_id="'.$user_id.'"');
        $this->db->where('a.is_deleted', '0');
        if($search0!=null){
            $this->db->like('a.name', "$search0");
//            $this->db->or_like('a.name', "$search");
        }

        if($lenght!=null && $limit!=null) {
            $this->db->limit($lenght, $limit);
        }
        if($sort_column!=null && $sort_order!=null){
            $this->db->order_by($allColumns[$sort_column], ucwords($sort_order));
        }else{
            $this->db->order_by('id', 'DESC');
        }
        //$query = $this->db->get();
        
        //return $query->result_array();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }


    public function total_apps($user_id=null,$search0=null,$search2=null,$search3=null){
        $this->db->where ('app.is_deleted','0');
//                echo $search;die;
        if($search0!=null) {
            $this->db->like('app.name', "$search0");
        }
            if ($this->acl->hasSuperAdmin()) {

                $this->db->join('department d', 'app.department_id = d.id');
                $this->db->join('users u', 'app.user_id = u.id','left');


                if($search2!=null) {
                    $this->db->like('d.name', "$search2");
                }

                if($search3!=null) {
                    $name_arr=explode(" ",$search3);
                    $this->db->like('u.first_name', "$name_arr[0]");
                    if(isset($name_arr[1])) {
                        $this->db->like('u.last_name', "$name_arr[1]");
                    }
                }
            }

        if($user_id!=null){
            $this->db->join('users_app ua', 'ua.app_id = app.id AND ua.user_id="'.$user_id.'"');
        }

        $result =   $this->db->count_all_results('app');
        // $str = $this->db->last_query();
        // file_put_contents('./assets/data/query_executed.txt', $str.PHP_EOL , FILE_APPEND | LOCK_EX);
        return $result;

    }

    /**
     * 
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_by_department_for_super($limit=null,$length=null,$search0=null,$search2=null,$search3=null,$sort_column=null,$sort_order=null) {
        $allColumns=array(
            "name",
            "",
            "",
            "",
            "created_datetime"
        );
//        $query = $this->db->get_where('app', array('is_deleted' => '0'));
        $this->db->select(`a.id id,a.type,a.module_name, 
        d.id department_id,a.icon, a.name name, a.created_datetime
        created_datetime, d.name department_name,
        CONCAT_WS(' ',u.first_name ,u.last_name) as user_name",
        FALSE`);
        $this->db->from('app a');
        $this->db->join('department d', 'a.department_id = d.id');
        $this->db->join('users u', 'a.user_id = u.id','left');
        $this->db->where('a.is_deleted', '0');
        if($search0!=null) {
            $this->db->like('a.name', "$search0");
        }
        if($search2!=null) {
            $this->db->like('d.name', "$search2");
        }
        if($search3!=null) {
            $name_arr=explode(" ",$search3);
            $this->db->like('u.first_name', "$name_arr[0]");
            if(isset($name_arr[1])) {
                $this->db->like('u.last_name', "$name_arr[1]");
            }
        }

        if($sort_column!=null && $sort_order!=null){
            $this->db->order_by($allColumns[$sort_column], ucwords($sort_order));
        }
//        else{
            $this->db->order_by('a.created_datetime', 'DESC');
//        }

        if($limit!=null && $length!=null){
            $this->db->limit($length,$limit);
        }


        //$query = $this->db->get();
        //return $query->result_array();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    /**
     * 
     * @param type $app_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function app_already_exist($app_name, $department_id, $app_id = null) {

        if($app_name=='')
        {
            return false;
        }
        if ($app_id)
            $query = $this->db->get_where('app',
            array('name' => $app_name, 'id !=' => $app_id, 'is_deleted' => '0'));
        else
            $query = $this->db->get_where('app',
            array('name' => $app_name, 'is_deleted' => '0'));
        $exist = $query->result_array();

        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $app_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appuser_imei_already_exist($imei_no, $app_id) {

        $query = $this->db->get_where('app_users',
         array('imei_no' => $imei_no, 'app_id' => $app_id ,'is_deleted' => 0));

        $exist = $query->result_array();

        if ($exist) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 
     * @param type $app_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appuser_login_name_already_exist($login_user) {

        $query = $this->db->get_where('app_users',
         array('login_user' => $login_user ,'is_deleted' => 0));

        $exist = $query->result_array();

        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_apps($department_id) {
//working on this 
        //$session_data = $this->session->userdata('logged_in');
        //$group_id = $session_data['login_group_id'];
        $query = $this->db->get_where('app', array('department_id' =>
         $department_id, 'is_deleted' => '0'));

        $apps = array();

        if ($query->result()) {
            foreach ($query->result() as $app) {
                $apps[$app->id] = $app->name;
            }
            return $apps;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_app_users($department_id) {
//working on this 
        //$session_data = $this->session->userdata('logged_in');
        //$group_id = $session_data['login_group_id'];
        $query = $this->db->get_where('app_users',
         array('department_id' => $department_id,
        'is_deleted' => '0'));

        $apps = array();

        if ($query->result()) {
            foreach ($query->result() as $app) {
                $apps[$app->id] = $app->name;
            }
            return $apps;
        } else {
            return FALSE;
        }
    }
    function get_app_settings($app_id) {
        $query = $this->db->get_where('app_settings', array('app_id' => $app_id));
        return $query->row_array();
    }

    function get_app_settings_filters($app_id,$setting_type=null){
        if($setting_type==null){
            $query = $this->db->get_where('app_settings', array('app_id' => $app_id));
        }else {
            $query = $this->db->get_where('app_settings',
            array('app_id' => $app_id,'setting_type'=>$setting_type));
        }
        return $query->result_array();
    }

    function get_form_column_settings($app_id){
        $query = $this->db->get_where('form_column_settings',
        array('app_id' => $app_id));
        return $query->row_array();
    }


    
     /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_assigned_app_to_user($app_id) {

        $this->db->select(`ua.id assigned_id,a.name app_name,d.name department_name,
        u.id user_id,u.first_name, u.last_name, u.parent_id,
        u.group_id, u.default_url, u.district`);
        $this->db->from('users_app ua');
        $this->db->join('users u', 'u.id = ua.user_id', 'left');
        $this->db->join('app a', 'a.id = ua.app_id', 'left');
        $this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        $this->db->join('department d', 'u.department_id = d.id', 'left');

        $this->db->where('a.id', $app_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     *
     * @param type $user_id
     * @return type
     * @author Ubiadullah Balti <uabidcskiu@gmail.com>
     */
    public function get_user_assigned_apps($user_id) {
    
    
        $this->db->select(`ua.user_id user_id,a.id app_id,
        a.name app_name,a.module_name`);
    	$this->db->from('users_app ua');
    	$this->db->join('app a','ua.app_id = a.id','left');
    	$this->db->where('ua.user_id', $user_id);
    	$this->db->where('a.is_deleted', '0');
    	$query = $this->db->get();
    
    	return $query->result_array();
    }

}

?>