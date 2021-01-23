<?php

class App_Users_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 
     * @param type $slug
     * @return type
     * @ubaid
     */
    public function get_towns($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('app_users');
            return $query->result_array();
        }

        $query = $this->db->get_where('app_users', array('app_id' => $slug));
        return $query->result_array();
    }

    /**
     * 
     * @param type $app_id $town_name
     * @return type
     * @author ubaid
     */
    public function get_imei($app_id, $town_name) {
        $town_name = rawurldecode($town_name);
        $query = $this->db->get_where('app_users', 
        array('app_id' => $app_id, 'town' => $town_name));

        $apps = array();

        if ($query->result()) {
            foreach ($query->result() as $app) {
                $apps[$app->imei_no] = $app->imei_no;
            }
            return $apps;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_views($app_id) {

        $query = $this->db->get_where('app_users_view', array('app_id' => $app_id));

        $app_views = array();

        if ($query->result()) {
            foreach ($query->result() as $app) {
                $app_views[$app->id] = $app->name;
            }
            return $app_views;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_views_existance($app_id, $view_id) {

        $query = $this->db->get_where('app_views', 
        array('app_id' => $app_id, 'view_id' => $view_id));

        if ($query->result()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_form_views_existance($form_id, $view_id) {

        $query = $this->db->get_where('form_views', 
        array('form_id' => $form_id, 'view_id' => $view_id));

        if ($query->result()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_view_id_by_imei_no($app_id, $imei_no) {

        $query = $this->db->get_where('app_users', 
        array('app_id' => $app_id, 'imei_no' => $imei_no));

        return $query->row_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_views_listing($department_id) {

        //$query = $this->db->get_where('app_users_view', array('is_deleted' => '0'));
        $this->db->select(`a.name app_name, auv.name view_name,
         auv.id view_id, d.name department_name`);
        $this->db->from('app_users_view auv');
        $this->db->join('app a', 'a.id = auv.app_id');
        if ($department_id) {
            $this->db->join('department d', 'a.department_id = d.id');
            $this->db->where('a.department_id', $department_id);
        } else {

            $this->db->join('department d', 'd.id=a.department_id');
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $app_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_app_user_listing($department_id,$limit=null,$length=null,
    $search=null,$sort_column=null,$sort_order=null,$app_list=null) {
        $allColumns=array(
            "a.name",
            "d.name",
            "",
            "au.name",
            "au.district",
            "au.town",
            "au.imei_no",
            "au.cnic",
            "au.mobile_number",
            "au.login_user",
            "au.login_password",

        );
        //$query = $this->db->get_where('app_users_view', array('is_deleted' => '0'));
        $this->db->select(`a.name app_name, au.district,au.name user_name, 
        au.id user_id, au.town user_town, au.imei_no,au.cnic, au.mobile_number,
         auv.name view_name, d.name department_name,au.login_user,au.login_password`);
        $this->db->from('app_users au');
        $this->db->join('app a', 'a.id = au.app_id');
        $this->db->join('app_users_view auv', 'au.view_id = auv.id', 'left');


        if ($department_id) {
            $this->db->join('department d', 'au.department_id = d.id');
            $this->db->where('a.department_id', $department_id);
        } else {

            $this->db->join('department d', 'd.id=au.department_id');
        }

        
        if($app_list!=null){
            $app_id_array = array();
            foreach($app_list as $key => $val){

                $app_id_array[] = $val['id'];
            }
            
            $this->db->where_in('au.app_id', $app_id_array);
        }

        if($search!=null){
            $like="(a.name LIKE '%$search%' OR
                    d.name LIKE '%$search%' OR
                    au.name LIKE '%$search%' OR
                    au.district LIKE '%$search%' OR
                    au.town LIKE '%$search%' OR
                    au.imei_no LIKE '%$search%' OR
                    au.cnic LIKE '%$search%' OR
                    au.mobile_number LIKE '%$search%')";
            $this->db->where($like);

//            $this->db->like('a.name',$search);
//            $this->db->or_like('d.name',$search);
//            $this->db->or_like('au.name',$search);
//            $this->db->or_like('au.district',$search);
//            $this->db->or_like('au.town',$search);
//            $this->db->or_like('au.imei_no',$search);
        }

        $this->db->where('au.is_deleted', '0');

        if($sort_column!=null && $sort_order!=null){
            $this->db->order_by($allColumns[$sort_column], ucwords($sort_order));
        }

        if($length!=null && $limit!=null) {
            $this->db->limit($length, $limit);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_app_user_total($department_id,$search) {

        //$query = $this->db->get_where('app_users_view', array('is_deleted' => '0'));
        $this->db->select(`a.name app_name, au.district,au.name user_name, au.id 
        user_id, au.town user_town, au.imei_no,au.cnic,au.mobile_number, auv.name
         view_name, d.name department_name`);
        $this->db->from('app_users au');
        $this->db->join('app a', 'a.id = au.app_id');
        $this->db->join('app_users_view auv', 'au.view_id = auv.id', 'left');


        if ($department_id) {
            $this->db->join('department d', 'au.department_id = d.id');
            $this->db->where('a.department_id', $department_id);
        } else {

            $this->db->join('department d', 'd.id=au.department_id');
        }

        if($search!=null){
            $like="(a.name LIKE '%$search%' OR
                    d.name LIKE '%$search%' OR
                    au.name LIKE '%$search%' OR
                    au.district LIKE '%$search%' OR
                    au.town LIKE '%$search%' OR
                    au.imei_no LIKE '%$search%' OR
                    au.cnic LIKE '%$search%' OR
                    au.mobile_number LIKE '%$search%')";
            $this->db->where($like);

//            $this->db->or_like('a.name',$search);
//            $this->db->or_like('d.name',$search);
//            $this->db->or_like('au.name',$search);
//            $this->db->or_like('au.district',$search);
//            $this->db->or_like('au.town',$search);
//            $this->db->or_like('au.imei_no',$search);
        }
        $this->db->where('au.is_deleted', '0');
        return $query = $this->db->count_all_results();
//        return $query->count_all();
    }

    /**
     * 
     * @param type $user_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_app_user_by_id($user_id) {

        $this->db->select(`u.id user_id,u.view_id view_id, u.name user_name,
        u.app_id,u.town,u.imei_no,u.cnic,u.mobile_number,u.mobile_network,d.id department_id,d.name
         department_name,u.district user_district,u.login_user,u.login_password`);
        $this->db->from('app_users u');
        $this->db->join('department d', 'u.department_id = d.id', 'left');


        $this->db->where('u.id', $user_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * get app users based on 
     * app id
     * ubd
     */
    public function get_app_users_app_based($app_id, $login_district = NULL) {
        $this->db->select(`au.name user_name,au.district,au.app_id app_id,
         au.id user_id, au.town user_town, au.imei_no,au.mobile_number,au.cnic`);
        $this->db->from('app_users au');
        $this->db->where('au.app_id', $app_id);
        $this->db->where('au.is_deleted', 0);
        if ($login_district) {
            $this->db->where('au.district', $login_district);
        }
        $this->db->group_by('au.imei_no');


        $query = $this->db->get();
        return $query->result_array();
    }

        /**
     * get app users based on 
     * app id
     * ubd
     */
    public function get_app_user_by_cnic($app_id, $cnic) {
        $this->db->select(`au.name user_name,au.district,au.app_id app_id,
         au.id user_id, au.town user_town, au.imei_no,au.mobile_number,au.cnic`);
        $this->db->from('app_users au');
        $this->db->where('au.app_id', $app_id);
        $this->db->where('au.cnic', $cnic);

        $query = $this->db->get();
        return $query->row_array();
    }

}

?>