<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apimaker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('api_model');
        $this->load->model('department_model');
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
    public function index() {
//         this method was calling again and again by list so applied logic here
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }
            $api_list = $this->api_model->get_api(false,$department_id);
            foreach ($api_list as $api) {
                //get latest release and existence
                $data['apis'][] = array(
                    'id' => $api['id'],
                    'title' => $api['title'],
                    'name' => $api['name'],
                    'file_name' => $api['file_name'],
                );
            }
            $data['active_tab'] = 'api_index';
            $data['app_name'] = "";
            $data['pageTitle'] = "Dropdown API Maker-".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('apimaker/index', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function createurl($slug) {
//         this method was calling again and again by list so applied logic here
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');
            $api_id = $slug;
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $api_rec = $this->api_model->get_api($api_id);
            $file_path = "assets/data/".$api_rec['file_name'];
            $row = 1;
            $options = array();
            $exist_array = array();
            $data['fields_name']='';
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                while (($datas = fgetcsv($handle, 15000, ",")) !== FALSE) {
                    if ($row == 1) {
                        $data['fields_name']=$datas;
                        $row++;
                    } 
                    break;
                }
            }

            $data['api_id'] = $api_id;
            $data['api_secret'] = $api_rec['secret_key'];
            $data['api_title'] = $api_rec['title'];
            $data['active_tab'] = 'api_create_url';
            $data['app_name'] = "";
            $data['pageTitle'] = "API Maker-".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('apimaker/createurl', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    public function apiappurl($slug) {
//         this method was calling again and again by list so applied logic here
        //exit;
        if ($this->session->userdata('logged_in')) {
            //$this->session->unset_userdata('view');
            $app_id = $slug;
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            
            $selected_app = $this->app_model->get_app($app_id);
            $app_name = $selected_app['name'];
            $secret_key = md5($app_name . $app_id);

            $data['app_id'] = $app_id;
            $data['app_secret'] = $secret_key;
            $data['active_tab'] = 'api_app_url';
            $data['app_name'] = "";
            $data['pageTitle'] = "Create API for export application - ".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('apimaker/apiappurl', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    /**
     * Action for application adding
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function add() {
        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {

            $batch = array();
            $session_data = $this->session->userdata('logged_in');

            if (!$this->acl->hasPermission('app', 'add')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . '');
            }
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            if ($this->input->post()) {
                $this->form_validation->set_rules('api_title', 'Api', 'trim|required|xss_clean|callback_app_already_exist[' . $department_id . ']');
                if ($this->form_validation->run() == FALSE) {
                    $this->session->set_flashdata('validate', array('message' => 'Please enter the Required Fields', 'type' => 'error'));
                    redirect(base_url());
                } else {
                    $rand_key = random_string('alnum', 10);
                    $api_title = trim($this->input->post('api_title'));
                    if($department_id==0) {
                        $department_id = trim($this->input->post('department_id'));
                    }
                    $apidata = array(
                        'title' => $api_title,
                        'secret_key' => $rand_key,
                        'department_id' => $department_id
                    );
                    $this->db->insert('api', $apidata);
                    $api_id = $this->db->insert_id();
                    //upload api file   
                    $abs_path = './assets/data/';
                    $old = umask(0);
                    @mkdir($abs_path, 0777);
                    umask($old);
                    if ($_FILES['userfile_addapi']['name'] != '') {
                    	$file_name = preg_replace("/[^A-Za-z0-9\.]/", "_",$_FILES['userfile_addapi']['name']);
                        $iconName = $api_id.'_'.$file_name;
                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $iconName;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'csv';
                        $config["max_size"] = 15360;
                        //$config["max_width"] = 400;
                        //$config["max_height"] = 400;
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('userfile_addapi')) {
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your app.', 'type' => 'warning'));
                        } else {
                            //success
                        }
                    } 

                    $change_file = array(
                        'file_name' => $iconName,
                    );
                    $this->db->where('id', $api_id);
                    $this->db->update('api', $change_file);
                    $this->session->set_flashdata('validate', array('message' => 'API added successfully.', 'type' => 'success'));
                    redirect(base_url() . 'apimaker/index');
                    
                }
            }

            if($department_id==0){
                $departments=$this->department_model->get_department();
                $data['departments'] = $departments;
            }
            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            $data['department_id'] = $department_id;
            $this->load->view('apimaker/add', $data);
            
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Action for edit application
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function edit($slug) {
        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $api_id = $slug;
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            $api_rec = $this->api_model->get_api($api_id);
            if ($this->input->post()) {
                    $api_title = trim($this->input->post('api_title'));
                    if($department_id==0) {
                        $department_id = trim($this->input->post('department_id'));
                    }
                    $apidata = array(
                        'title' => $api_title,
                        'department_id' => $department_id,
                    );
                    $this->db->where('id', $api_id);
                    $this->db->update('api', $apidata);
                    //upload api file   
                    $abs_path = './assets/data/';
                    $old = umask(0);
                    @mkdir($abs_path, 0777);
                    umask($old);
                    if ($_FILES['userfile_addapi']['name'] != '') {
                        $iconName = $api_id.'_'.$_FILES['userfile_addapi']['name'];
                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $iconName;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'csv';
                        $config["max_size"] = 15360;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('userfile_addapi')) {
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your app.', 'type' => 'warning'));
                        } else {
                            unlink('./assets/data/'.$api_rec['file_name']);
                            //success
                        }
                        $change_file = array(
                            'file_name' => $iconName,
                        );
                        $this->db->where('id', $api_id);
                        $this->db->update('api', $change_file);
                    } 
                    
                    $this->session->set_flashdata('validate', array('message' => 'API Updated successfully.', 'type' => 'success'));
                    redirect(base_url() . 'apimaker/index');
            }
            if($department_id==0){
                $departments=$this->department_model->get_department();
                $data['departments'] = $departments;
            }

            $data['api_id'] = $api_id;
            $data['api_title'] = $api_rec['title'];
            $data['saved_department_id'] = $api_rec['department_id'];
            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            $data['department_id'] = $department_id;
            $this->load->view('apimaker/edit', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Function for deleting application
     * @param string $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function delete($slug) {
        if ($this->session->userdata('logged_in')) {
            $api_id = $slug;
            $api_rec = $this->api_model->get_api($api_id);
            $this->db->where('id', $api_id);
            $this->db->delete('api');
            unlink('./assets/data/'.$api_rec['file_name']);
            $this->session->set_flashdata('validate', array('message' => 'API deleted successfully.', 'type' => 'success'));
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }
}