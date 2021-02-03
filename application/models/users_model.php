<?php

Class Users_model extends CI_Model {

    /**
     * 
     * @param type $username
     * @param type $password
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getLogin($username, $password) {
        $this->db->select('u.id id, u.email, u.username,u.first_name,u.last_name, u.password,d.id department_id,d.name department_name, u.parent_id, u.group_id, u.is_deleted, u.status,u.verification_code,u.default_url, u.district');
        $this->db->from('users u');
        $this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        $this->db->join('department d', 'u.department_id = d.id', 'left');
        $this->db->where('u.password', MD5($password));
        //$this->db->where('u.username', $username);
        $this->db->where('u.email', $username);
        $this->db->where('u.is_deleted', '0');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $parent_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getUsers($parent_id) {

        $this->db->select('u.id id, u.username,u.email,u.first_name,u.last_name,d.id department_id,d.name department_name, u.parent_id, u.group_id,ug.type group_name,u.status');
        $this->db->from('users u');
        $this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        $this->db->join('department d', 'u.department_id = d.id');

        if ($this->acl->hasSuperAdmin()) {
            $this->db->where('u.department_id !=', 0);
        } else {
            $this->db->where('u.parent_id', $parent_id);
        }
        $this->db->where('u.is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * 
     * @param type $parent_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getAllUsers() {

        $this->db->select('u.id id, u.username,u.email,u.first_name,u.last_name,u.department_id, u.parent_id, u.group_id,u.status');
        $this->db->from('users u');
        
        $this->db->where('u.is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getSuperAdmin() {

        $this->db->select('u.id id, u.username,u.email,u.first_name,u.last_name,u.parent_id, u.group_id');
        $this->db->from('users u');
        $this->db->where('u.department_id !=', 0);
        $this->db->where('u.is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 
     * @param type $user_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function getUserById($user_id) {

        $this->db->select('u.id id, u.username, u.email,u.first_name,u.last_name,d.id department_id,d.name department_name, u.parent_id, u.group_id,ug.type group_name, u.default_url, u.district');
        $this->db->from('users u');
        $this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        $this->db->join('department d', 'u.department_id = d.id', 'left');


        $this->db->where('u.id', $user_id);
        $this->db->where('u.is_deleted', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * 
     * @param type $user_name
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Username_Already_Exist($user_name) {

        $query = $this->db->get_where('users', array('username' => $user_name, 'is_deleted' => '0'));
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $email
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Email_Already_Exist($email) {

        $query = "SELECT *
                    FROM `users`
                    WHERE `email` LIKE '$email'
                    AND (`verification_code` IS NULL OR `verification_code` = '')
                    AND status = '1' AND is_deleted = '0'";
        $query = $this->db->query($query);

        $exist = $query->result_array();

        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $user_name
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getUserByUsername($user_name) {

        $query = $this->db->get_where('users', array('username' => $user_name, 'is_deleted' => '0'));
        $exist = $query->row_array();
        if ($exist) {
            return $exist;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $user_name
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getUserByEmail($email) {

        $query = $this->db->get_where('users', array('email' => $email, 'is_deleted' => '0'));
        $exist = $query->row_array();
        if ($exist) {
            return $exist;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $user_name
     * @param type $user_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function User_Name_Already_Exist($user_name, $user_id) {

        $query = $this->db->get_where('users', array('username' => $user_name, 'is_deleted' => '0', 'id !=' => $user_id));
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $user_name
     * @param type $user_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Email_Already_Exist_Edit($email, $user_id) {

        $query = $this->db->get_where('users', array('email' => $email, 'is_deleted' => '0', 'id !=' => $user_id));
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $password
     * @param type $user_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Current_Password_Check($password, $user_id) {

        $query = $this->db->get_where('users', array('password' => md5($password), 'is_deleted' => '0', 'id' => $user_id));
        $exist = $query->result_array();
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $data
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function addUser($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function Edit_User($user_id, $data) {
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

    /**
     * 
     * @param type $department_id
     * @param type $parent_id
     * @param type $group_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function groups($department_id, $parent_id, $group_id) {
        $this->db->select('ug.id,ug.type,d.name');
        $this->db->from('users_group ug');
        if ($this->acl->hasSuperAdmin()) {
            $this->db->join('department d', 'ug.department_id = d.id');
            $this->db->where('ug.department_id !=', 0);
        } else if (!$parent_id) {
            $this->db->join('department d', 'ug.department_id = d.id');
            $this->db->where('ug.department_id', $department_id);
            $this->db->where('ug.id !=', $group_id);
        } else {
            $this->db->join('department d', 'ug.department_id = d.id');
            $this->db->join('users u', 'ug.id = u.group_id', 'left');
            $this->db->where('ug.department_id', $department_id);
            $this->db->where('u.parent_id =', $parent_id);
            $this->db->where('ug.id !=', $group_id);
            //$this->db->where('u.id =',$parent_id);
        }
        $this->db->where('d.is_deleted =', '0');
        $this->db->order_by('ug.type', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * 
     * @param type $group_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function Remove_User_Permissions_By_Group($group_id) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('group_id', $group_id);

        $query = $this->db->get();
        $users = $query->result();

        foreach ($users as $user) {
            $this->db->delete('users_permissions', array('user_id' => $user->id));
        }
    }

    /**
     * 
     * @param type $group_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function addUserPermissionsByGroup($group_id) {

        $all_permissions = $this->get_group_permissions($group_id);

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('group_id', $group_id);

        $query = $this->db->get();
        $users = $query->result();

        foreach ($users as $user) {
            foreach ($all_permissions as $permission) {
                $data = array(
                    'user_id' => $user->id,
                    'module' => $permission->module,
                    'role' => $permission->role,
                );
                $this->db->insert('users_permissions', $data);
            }
        }
    }

    /**
     * 
     * @param type $group_id
     * @param type $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function add_user_permissions_by_user($group_id, $user_id) {

        $all_permissions = $this->get_group_permissions($group_id);

        foreach ($all_permissions as $permission) {
            $data = array(
                'user_id' => $user_id,
                'module' => $permission->module,
                'role' => $permission->role,
            );
            $this->db->insert('users_permissions', $data);
        }
    }

    /**
     * 
     * @param type $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function remove_user_permissions_by_user($user_id) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id);

        $query = $this->db->get();
        $users = $query->result();

        foreach ($users as $user) {
            $this->db->delete('users_permissions', array('user_id' => $user->id));
        }
    }

    /**
     * 
     * @param type $group_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function remove_group_permissions($group_id) {
        $this->db->delete('users_group_permissions', array('group_id' => $group_id));
    }

    /**
     * 
     * @param type $data
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function add_group_permissions($data) {

        $this->db->insert('users_group_permissions', $data);
    }

    /**
     * 
     * @param type $group_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_group_permissions($group_id) {

        $this->db->select('*');
        $this->db->from('users_group_permissions');
        $this->db->where('group_id', $group_id);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * 
     * @param type $group_name
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function group_already_exist($group_name, $department_id) {

        $query = $this->db->get_where('users_group', array('type' => $group_name, 'department_id' => $department_id));
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
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_parent_user($department_id) {

        $query = $this->db->get_where('users', array('parent_id' => '0', 'department_id' => $department_id,'is_deleted'=>'0'));
        return $query->row_array();
    }

    /**
     * 
     * @param type $department_id
     * @return boolean
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_groups($department_id) {

        $session_data = $this->session->userdata('logged_in');
        $group_id = $session_data['login_group_id'];
        $query = $this->db->get_where('users_group', array('department_id' => $department_id, 'id !=' => $group_id));

        $groups = array();

        if ($query->result()) {
            foreach ($query->result() as $group) {
                $groups[$group->id] = $group->type;
            }
            return $groups;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $group_id
     * @param type $modules
     * @param type $roles
     * @param type $skip
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function map_full_permissions($group_id, $modules, $roles, $skip) {
        foreach ($modules as $module) {
            foreach ($roles as $key => $role) {
                if (in_array($role, $skip[$module]))
                    continue;
                $data = array(
                    'group_id' => $group_id,
                    'module' => $module,
                    'role' => $role,
                );
                $this->db->insert('users_group_permissions', $data);
            }
        }
    }

    /**
     * 
     * @param type $parent_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_app_users($department_id) {

        $this->db->select('au.id id, u.name,u.town,u.imei_no,d.id department_id,d.name department_name, u.parent_id, u.group_id,ug.type group_name,u.status');
        $this->db->from('app_users au');
        $this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        $this->db->join('department d', 'u.department_id = d.id');

        if ($this->acl->hasSuperAdmin()) {
            $this->db->where('u.department_id !=', 0);
        } else {
            $this->db->where('u.department_id', $department_id);
        }
        $this->db->where('u.is_deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * 
     * @param type $department_id
     * @return type
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function get_user_by_department_id($department_id) {

        $this->db->select('u.id id, u.username, u.email,u.first_name,u.last_name, u.parent_id, u.group_id, u.default_url, u.district');
        $this->db->from('users u');
        //$this->db->join('users_group ug', 'u.group_id = ug.id', 'left');
        //$this->db->join('department d', 'u.department_id = d.id', 'left');


        $this->db->where('u.department_id', $department_id);
        $this->db->where('u.is_deleted', 0);
        $this->db->order_by('u.first_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

}

?>
