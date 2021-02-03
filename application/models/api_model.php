<?php

class Api_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getApi($slug = FALSE,$department_id='') {
        if ($slug === FALSE && $department_id==0) {
//            $this->db->select('api.*,department.name as name');
//            $this->db->from('api,department');
//            $this->db->where('department.id=api.department_id');
            $query=$this->db->query("select api.*,department.name as name
                                    from api
                                    LEFT JOIN department ON (department.id=api.department_id)  order by api.id desc");
//            $query = $this->db->get();
            return $query->result_array();
        }

        if(isset($department_id) && $department_id!=0){
            $query=$this->db->query("select api.*,department.name as name
                                    from api
                                    LEFT JOIN department ON (department.id=api.department_id) 
                                    WHERE api.department_id=".$department_id." order by api.id desc");

            //$this->db->where('api.department_id', $department_id);

            //$query = $this->db->get();

            return $query->result_array();
        }

        if($slug) {

            $this->db->select('*');
            $this->db->from('api');

            $this->db->where('api.id', $slug);
            $this->db->order_by("api.id", 'Desc');

            $query = $this->db->get();

            return $query->row_array();
        }
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
    /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppByUser($user_id) {

        
        $this->db->select('a.*');
        $this->db->from('app a');
        $this->db->join('users_app ua', 'ua.app_id = a.id AND ua.user_id="'.$user_id.'"');
        $this->db->where('a.is_deleted', '0');
        $query = $this->db->get();
        
        return $query->result_array();
    }

    /**
     * 
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAppByDepartment_for_super() {

        $query = $this->db->get_where('app', array('is_deleted' => '0'));
        $this->db->select("a.id id, d.id department_id,a.icon, a.name name, d.name department_name, CONCAT_WS(' ',u.first_name ,u.last_name) as user_name",FALSE);
        $this->db->from('app a');
        $this->db->join('department d', 'a.department_id = d.id');
        $this->db->join('users u', 'a.user_id = u.id','left');
        $this->db->where('a.is_deleted', '0');
        $this->db->order_by('id','DESC');

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
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_Apps($department_id) {
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
    
     /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getAssignedAppToUser($app_id) {

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

}

?>
