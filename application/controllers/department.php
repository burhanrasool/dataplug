<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('app_installed_model');
        $this->load->model('department_model');
        $this->load->model('form_model');
        $this->load->model('users_model');
        $this->load->model('form_results_model');
        $this->load->model('app_released_model');
        // if (!$this->acl->hasSuperAdmin()) {
        //     if($this->acl->hasPermission('complaint','Access only complaint module')){
        //         redirect(base_url() . 'complaintSystem');
        //     }
        // }
        $sess_ar = $this->session->userdata('logged_in');
        if ($sess_ar['login_verification_code']!= '') {
           $this->session->set_flashdata('validate', array('message' => 'Limited time access , Your account not verified yet, please check your email and verify otherwise account will delete after 30 days.', 'type' => 'warning'));
        }
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function getIndex() {
        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('department', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'department/index');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $data['app_name'] = "";
            $data['departments'] = $this->department_model->get_department();
            $data['pageTitle'] = "Departments-Government Open Data Kit";
            $data['active_tab'] = 'department-index';
            $this->load->view('templates/header', $data);
            $this->load->view('department/index', $data);
            $this->load->view('templates/footer',$data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    
     /**
     * Action for adding department
     * @param integer $department_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function add() {
        if ($this->session->userdata('logged_in')) {
            $this->load->library('form_validation');
            if (!$this->acl->hasPermission('department', 'add')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'department');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($this->input->post('department_name')) {
                $department_name = trim($this->input->post('department_name'));
                $is_public = trim($this->input->post('is_public'));
                if ($this->department_model->department_already_exist($department_name)) {
                    $this->session->set_flashdata('validate', array('message' => 'This department name already exist', 'type' => 'error'));
                    redirect(base_url() . 'new-department');
                }
                $data = array(
                    'name' => $department_name,
                    'is_public' => $is_public
                );
                $this->db->insert('department', $data);
                $this->session->set_flashdata('validate', array('message' => 'New Department added successfully.', 'type' => 'success'));
                redirect(base_url() . 'department');
            }
            $data['app_name'] = "";
            $data['active_tab'] = 'department';
            $data['pageTitle'] = "Add Department-Government Open Data Kit";
            $this->load->view('department/add', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

     /**
     * Action for edit department
     * @param integer $department_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function edit($slug) {
        $this->load->helper(array('form'));
        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('department', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'department');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $group_list = $this->users_model->get_groups($slug);
            $data['group_list'] = $group_list;
            if ($this->input->post('department_name')) {
                $department_name = trim($this->input->post('department_name'));
                $is_public = trim($this->input->post('is_public'));
                $public_group = trim($this->input->post('group_id'));

                if ($this->department_model->department_already_exist($department_name, $slug)) {
                    $this->session->set_flashdata('validate', array('message' => 'This department name already exist', 'type' => 'error'));
                    redirect(base_url() . 'department/edit/' . $slug);
                }
                $data = array(
                    'name' => $department_name,
                    'is_public' => $is_public,
                    'public_group' => $public_group
                );
                $this->db->where('id', $slug);
                $this->db->update('department', $data);

                $this->session->set_flashdata('validate', array('message' => 'Department updated successfully.', 'type' => 'success'));
                redirect(base_url() . 'department');
            }
            $department_rec = $this->department_model->get_department($slug);
            $data['name'] = $department_rec['name'];
            $data['is_public'] = $department_rec['is_public'];
            $data['id'] = $department_rec['id'];
            $data['group_id'] = $department_rec['public_group'];
            $data['active_tab'] = 'department';
            $data['pageTitle'] = 'Edit ' . $department_rec['name'] . " Department-Government Open Data Kit";
            $this->load->view('templates/header', $data);
            $this->load->view('department/edit', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'login');
        }
    }

     /**
     * Action for delete department
     * @param integer $department_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function delete($slug) {
        if ($this->session->userdata('logged_in')) {
            $department_id = $slug;
            if (!$this->acl->hasPermission('department', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'department/index');
            }
            $data = array(
                'is_deleted' => '1'
            );
            $this->db->where('id', $department_id);
            $this->db->update('department', $data);

            //Delete all users of this department
            $datauser = array(
                'is_deleted' => '1'
            );
            $this->db->where('department_id', $department_id);
            $this->db->update('users', $datauser);

            //delete all app and forms of this department
            $app_department = $this->app_model->get_app_by_department($department_id);
            foreach ($app_department as $app) {
                $app_id = $app['id'];

                //Delete all form of this department applications
                $dataform = array(
                    'is_deleted' => '1'
                );
                $this->db->where('app_id', $app_id);
                $this->db->update('form', $dataform);

                //Delete all applications of this department
                $dataapp = array(
                    'is_deleted' => '1'
                );
                $this->db->where('id', $app_id);
                $this->db->update('app', $dataapp);
            }
            $this->session->set_flashdata('validate', array('message' => 'Department deleted successfully.', 'type' => 'success'));
            redirect(base_url() . 'department/index');
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    
     /**
     * Function for checking department existence
     * @param integer $department_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function Dep_Already_Exist() {
        $dep_name = trim($this->input->post('dep_name'));
        if ($this->department_model->department_already_exist($dep_name)) {
            echo true;
        } else {
            echo false;
        }
    }

}
