<?php

class Complaint_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function addComplaint($record_array){
        $this->db->insert('complaint', $record_array);
        $complaint_id = $this->db->insert_id();
        return $complaint_id;
    }
    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getComplaint(
        $complaint_by_id=null,
        $limit=null,
        $length=null,
        $search0=null,
        $sort_column=null,
        $sort_order=null,
        $image=null,//search0
        $complaint_no=null,//search1
        $complaint_date_range=null, //search2
        $department=null,//search3
        $application=null,//search4
        $complaint_type=null,//search5
        $complaint_title=null,//search6
        $search5=null,//search7
        $complaint_status=null,//search8
        $complaint_by=null//search9 
        ) {
        
        $all_columns=array(
            'c.c_id',
            'c.c_date_time',
            'c.c_type',
            'c.c_status',
            'c.c_title',
            'c.c_complaint_by_id',
            '',
            '',
        );


        $this->db->select("c.* ,CONCAT_WS(' ',u.first_name ,u.last_name) as user_name,a.name app_name,d.name department_name,cp.cp_photo",false);
        $this->db->from('complaint c');
        $this->db->join('users u', 'u.id = c.c_complaint_by_id', 'left');
        $this->db->join('department d', 'd.id = c.c_department_id', 'left');
        $this->db->join('app a', 'a.id = c.c_app_id', 'left');
        $this->db->join('complaint_photo cp', 'cp.cp_complaint_id_Fk = c.c_id', 'left');
        if ($complaint_by_id) {
            $this->db->where('c.c_complaint_by_id', $complaint_by_id);
        }
        if($search0!=null) {
            $this->db->like('c.c_description', "$search0");
        }
        if($complaint_no!=null) {
            $this->db->where('c.c_id', "$complaint_no");
        }

        if($complaint_date_range!=null && $complaint_date_range!="~"){
            $dates=explode("~",$complaint_date_range);
            if($dates[0]=="") {
                $from= date("Y-m-d H:i:s");
            }else{
                $from=$dates[0]." 00:00:00";
            }
            if($dates[1]==""){
                $to= date("Y-m-d H:i:s");
            }else {
                $to=$dates[1]." 23:59:59";
            }
            $this->db->where('c.c_date_time >=',"$from");
            $this->db->where('c.c_date_time <=',"$to");
        }

        if($department!=null) {
            $this->db->like('d.name', "$department");
        }
        if($application!=null) {
            $this->db->like('a.id', "$application");
        }
        if($complaint_type!=null) {
            $this->db->like('c.c_type', "$complaint_type");
        }
        if($complaint_title!=null) {
            $this->db->like('c.c_title', "$complaint_title");
        }
        if($complaint_status!=null) {
            $this->db->like('c.c_status', "$complaint_status");
        }
        if($complaint_by!=null) {
            $this->db->where('c.c_complaint_by_id', "$complaint_by");
        }


        if($sort_column!=null && $sort_order!=null){
            $this->db->order_by("$all_columns[$sort_column]", ucwords($sort_order));
        }else{
            $this->db->order_by("c.c_date_time", 'DESC');
        }


        
        if($length!=-1) {
            if ($limit != null && $length != null) {
                $this->db->limit($length, $limit);
            }
        }
               
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getComplaintById($slug=null) {

        $this->db->select("c.* ,CONCAT_WS(' ',u.first_name ,u.last_name) as user_name,a.name app_name,d.name department_name,c.c_app_user_id",false);
        $this->db->from('complaint c');
        $this->db->join('users u', 'u.id = c.c_complaint_by_id', 'left');
        $this->db->join('department d', 'd.id = c.c_department_id', 'left');
        $this->db->join('app a', 'a.id = c.c_app_id', 'left');
        if ($slug) {
            $this->db->where('c.c_id', $slug);
        }
        
               
        $query = $this->db->get();
        return $query->result_array();
    }    

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getComplaintByAll() {

        $this->db->select("u.id,CONCAT_WS(' ',u.first_name ,u.last_name) as user_name",false);
        $this->db->distinct();
        $this->db->from('complaint c');
        $this->db->join('users u', 'u.id = c.c_complaint_by_id', 'left');
        
               
        $query = $this->db->get();
        return $query->result_array();
    }    

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getApplicationsAll() {

        $this->db->select("a.id,a.name as app_name",false);
        $this->db->distinct();
        $this->db->from('complaint c');
        $this->db->join('app a', 'a.id = c.c_app_id', 'left');
        
               
        $query = $this->db->get();
        return $query->result_array();
    }


    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getComplaintHistory($slug=null) {

        $this->db->select("ch.* ,CONCAT_WS(' ',u.first_name ,u.last_name) as user_name",false);
        $this->db->from('complaint_history ch');
        $this->db->join('users u', 'u.id = ch.ch_changed_by_id', 'left');
        $this->db->where('ch.ch_complaint_id_Fk', $slug);
        $this->db->order_by('ch.ch_datetime', 'DESC');
               
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppByDepartment($department_id) {

        $query = $this->db->get_where('app', array('department_id' => $department_id, 'is_deleted' => '0'));
        return $query->result_array();
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

        return  $this->db->count_all_results('app');

    }

    /**
     * 
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppByDepartmentForSuper($limit=null,$length=null,$search0=null,$search2=null,$search3=null,$sort_column=null,$sort_order=null) {
        $allColumns=array(
            "name",
            "",
            "",
            "",
            "created_datetime"
        );
//        $query = $this->db->get_where('app', array('is_deleted' => '0'));
        $this->db->select("a.id id,a.type,a.module_name, d.id department_id,a.icon, a.name name, a.created_datetime created_datetime, d.name department_name, CONCAT_WS(' ',u.first_name ,u.last_name) as user_name",FALSE);
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


        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $app_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appAlreadyExist($app_name, $department_id, $app_id = null) {

        if($app_name=='')
        {
            return false;
        }
        if ($app_id)
            $query = $this->db->get_where('app', array('name' => $app_name, 'id !=' => $app_id, 'is_deleted' => '0'));
        else
            $query = $this->db->get_where('app', array('name' => $app_name, 'is_deleted' => '0'));
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
    public function AppUser_Imei_Already_Exist($imei_no, $app_id) {

        $query = $this->db->get_where('app_users', array('imei_no' => $imei_no, 'app_id' => $app_id ,'is_deleted' => 0));

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
    public function AppUser_Login_Name_Already_Exist($login_user) {

        $query = $this->db->get_where('app_users', array('login_user' => $login_user ,'is_deleted' => 0));

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
    function getApps($department_id) {
//working on this 
        //$session_data = $this->session->userdata('logged_in');
        //$group_id = $session_data['login_group_id'];
        $query = $this->db->get_where('app', array('department_id' => $department_id, 'is_deleted' => '0'));

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
    function getAppUsers($department_id) {
//working on this 
        //$session_data = $this->session->userdata('logged_in');
        //$group_id = $session_data['login_group_id'];
        $query = $this->db->get_where('app_users', array('department_id' => $department_id, 'is_deleted' => '0'));

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
    function getAppSettings($app_id) {
        $query = $this->db->get_where('app_settings', array('app_id' => $app_id));
        return $query->row_array();
    }

    function getAppSettings_filters($app_id,$setting_type=null){
        if($setting_type==null){
            $query = $this->db->get_where('app_settings', array('app_id' => $app_id));
        }else {
            $query = $this->db->get_where('app_settings', array('app_id' => $app_id,'setting_type'=>$setting_type));
        }
        return $query->result_array();
    }

    function getFormColumn_settings($app_id){
        $query = $this->db->get_where('form_column_settings', array('app_id' => $app_id));
        return $query->row_array();
    }


    
     /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function geAssignedAppToUser($app_id) {

        $this->db->select('ua.id assigned_id,a.name app_name,d.name department_name,u.id user_id,u.first_name, u.last_name, u.parent_id, u.group_id, u.default_url, u.district');
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
    public function getUserAssignedApps($user_id) {
    
    
    	$this->db->select('ua.user_id user_id,a.id app_id,a.name app_name,a.module_name');
    	$this->db->from('users_app ua');
    	$this->db->join('app a','ua.app_id = a.id','left');
    	$this->db->where('ua.user_id', $user_id);
    	$this->db->where('a.is_deleted', '0');
    	$query = $this->db->get();
    
    	return $query->result_array();
    }

}

?>
