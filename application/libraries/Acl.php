<?php

class Acl {

    var $permissions = array();  //Array : Stores the permissions for the user
    var $super_admin;   
    var $ci;

    function __construct($config = array()) {

        $this->ci = &get_instance();
    }

    function buildACL() {
        $session_data = $this->ci->session->userdata('logged_in');
        
        $user_id = $session_data['login_user_id'];
        
        if(!$user_id)
            return false;
        $group_id = $session_data['login_group_id'];

        $this->ci->db->select('u.id id, u.username,u.first_name,u.last_name, u.password,d.id department_id,d.name department_name, u.parent_id, u.group_id, u.is_deleted, u.status,u.verification_code');
        $this->ci->db->from('users u');
        $this->ci->db->join('users_group ug', 'ug.id = u.group_id', 'left');
        $this->ci->db->join('department d', 'u.department_id = d.id', 'left');
        $this->ci->db->where('u.id', $user_id);
        $query = $this->ci->db->get();

        $perm = $query->row_array();

        if ($perm['department_id']) {
            $this->super_admin = false;
        } else {
            $this->super_admin = true;
        }



        $this->ci->db->select('*');
        $this->ci->db->from('users_group_permissions');
        $this->ci->db->where('group_id', $group_id);

        $query = $this->ci->db->get();

        $perm = $query->result();
        if ($perm) {
            foreach ($perm as $key => $value) {
                $this->permissions[$value->module][$value->role] = 'yes';
            }
        }
        $sess_array = array(
            'super_admin' => $this->super_admin,
            'permissions' => $this->permissions,
        );
        if($this->ci->session->unset_userdata('acl_session'))
        $this->ci->session->unset_userdata('acl_session');
        $this->ci->session->set_userdata('acl_session', $sess_array);
        
    }

    function clearACL() {
        if($this->ci->session->unset_userdata('acl_session'))
        $this->ci->session->unset_userdata('acl_session');
        unset($this->permissions);
        unset($this->super_admin);
        unset($this->ci);
    }

    /**
     * 
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function hasSuperAdmin() {
        $session_data = $this->ci->session->userdata('acl_session');
        $this->super_admin = $session_data['super_admin'];
        return $this->super_admin;
    }

    /**
     * 
     * @param type $module
     * @param type $role
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function hasPermission($module, $role) {
        $session_data = $this->ci->session->userdata('acl_session');
        $this->permissions = $session_data['permissions'];
        $this->super_admin = $session_data['super_admin'];
        if ($this->super_admin) {
            return true;
        } else {
            if (isset($this->permissions[$module][$role]))
                return true;
            else
                return false;
        }
    }

}

?>