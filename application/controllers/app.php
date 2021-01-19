<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class App extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
        $this->load->model('users_model');
        $this->load->model('app_users_model');
        $this->load->model('app_model');
        $this->load->model('site_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('app_installed_model');
        $this->load->model('department_model');
        $this->load->model('form_results_model');
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
        
        if (strpos($_SERVER ['SERVER_NAME'], 'monitoring.punjab') !== false) {
            if ($this->uri->segment(1) != 'apps') {
                exit();
            }
        }
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');

            $session_data = $this->session->userdata('logged_in');
            $data = $session_data;
            //session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }

            
//            echo '<pre>'; print_r($data); die;
            $data['active_tab'] = 'app_index';
            $data['app_name'] = "";
            $data['pageTitle'] = "Applications-".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('app/index', $data);
//            $this->load->view('app/index_test', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }



// This method is loading all apps in datatable via ajax call....
    public function ajaxApps(){

        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');

            $session_data = $this->session->userdata('logged_in');

            $data = $session_data;
            //session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }

            if ($this->acl->hasSuperAdmin()) {
                $apps = $this->app_model->get_app_by_department_for_super($_GET['iDisplayStart'],$_GET['iDisplayLength'],$_GET['sSearch_0'],$_GET['sSearch_2'],$_GET['sSearch_3'],$_GET['iSortCol_0'],$_GET['sSortDir_0']);
                $total_apps = $this->app_model->total_apps(null,$_GET['sSearch_0'], $_GET['sSearch_2'], $_GET['sSearch_3']);
            } else {
                $apps = $this->app_model->get_app_by_user($data['login_user_id'],$_GET['iDisplayStart'],$_GET['iDisplayLength'],$_GET['sSearch_0'],$_GET['iSortCol_0'],$_GET['sSortDir_0']);
                $total_apps = $this->app_model->total_apps($data['login_user_id'], $_GET['sSearch_0'], $_GET['sSearch_2'], $_GET['sSearch_3']);
            }
//            $data2=array();
            //GET TOAL APPS...


            $data2= array("sEcho" => intval($_GET['sEcho']),
                "iTotalRecords" => $total_apps,
                "iTotalDisplayRecords" => $total_apps,);
            foreach ($apps as $app) {
                $formbyapp = $this->form_model->get_form_by_app($app['id']);

                // $form_id = '';
                // if (isset($formbyapp[0]['form_id'])) {
                //     $form_id = $formbyapp[0]['form_id'];
                // }
                // if ($formbyapp) {
                //     $empty_form = 'no';
                // }
                // $forms = $this->form_model->get_empty_app_form($app['id']);
                // if ($forms) {
                //     $empty_form = 'no';
                // } else {
                //     $empty_form = 'yes';
                // }
                $released = '';
                $released = $this->app_released_model->get_latest_released($app['id']);

            	$filename = '';
                $qr_code_file = '';
                $app['qr_code_file']='';
                $app['app_file'] = '';
                if ($released) {
                	 $filename = $released['app_file'];
                    $app['app_file'] = $filename;
                    $app['qr_code_file'] = $released['qr_code_file'];
                }

                $department_name = '';
                $user_name = '';
                if (isset($app['department_name']))
                    $department_name = $app['department_name'];
                if (isset($app['user_name']))
                    $user_name = $app['user_name'];




                $forms_list = array();
                //$all_forms = $this->form_model->get_form_by_app($app['id']);
                $results_count=0;
                foreach ($formbyapp as $forms) {
                    //$forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
                    //$table_exist_bit = $this->form_results_model->check_table_exits('zform_' . $forms['form_id']);
                    //if($table_exist_bit['count(*)']==1){
                    if(is_table_exist('zform_' . $forms['form_id'])){
                        //get count of records...
                        $total=$this->form_results_model->find_record_count('zform_' . $forms['form_id']);
                        $results_count+=$total;
                    }
                    if(!is_table_exist('zform_' . $forms['form_id'])) {
                        updateDataBase($forms['form_id'], $forms['description']);
                    }
                }
                // if ($forms_list) {

                //     $results_count = $this->form_results_model->get_result_is_empty($forms_list);
                // } else {
                //     $results_count = 0;
                // }
                //making action column html


                //get latest release and existence
                $data2['aaData'][] = array(
//                    'id' => $app['id'],
//                    'department_id' => $app['department_id'],
                    'name' => $this->create_app_name($app,$results_count), //$app['name'],
                    'icon' => $this->create_icon_image($app,$results_count),//$app['icon'],
                    'qr_code_file' => $this->create_qr_code_image($app),//$app['icon'],
                    'action' => $this->create_action_buttons($app),
                    'department_name' => $department_name,
                    'user_name' => $user_name,
                    'created_datetime' => $app['created_datetime'],
//                    'app_file' => $filename,
//                    'empty_form' => $empty_form,
//                    'form_id' => $form_id,
                );

            }

            if(count($apps)==0){
                //echo json_encode(array('aaData'=>''));
                echo json_encode(array(                
                    //"sEcho" => 0,                
                    "iTotalRecords" => "0",                
                    "iTotalDisplayRecords" => "0",                
                    "aaData" => array()            
                    ));
            }else {
                echo json_encode($data2);
            }

        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }










    /**
     * Action for application users
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appassignusers($slug) {
        $app_id = $slug;
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        $appinf = $this->app_model->get_app($app_id);
        $usr_list = $this->users_model->get_user_by_department_id($appinf['department_id']);
        $data['usr_list'] = $usr_list;
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_id', 'User Name', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $user_id = $this->input->post('user_id');
                $this->db->select('*');
                $this->db->from('users_app u');
                $this->db->where('u.user_id', $user_id);
                $this->db->where('u.app_id', $app_id);
                $query = $this->db->get();
                $already_asign = $query->result_array();
                if (!$already_asign) {
                    $appdata = array(
                        'user_id' => $user_id,
                        'app_id' => $app_id
                    );
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'insert', 'description' => 'Assigned application to other user', 'after' => json_encode($appdata), 'app_id' => $app_id,);
                    addlog($logary);
                    $this->db->insert('users_app', $appdata);
                    $this->session->set_flashdata('validate', array('message' => 'Application Assigned to User successfully', 'type' => 'success'));
                    redirect(base_url() . 'assign-applicatioin-users/' . $app_id);
                } else {
                    $this->session->set_flashdata('validate', array('message' => 'Application already assigned to this user', 'type' => 'error'));
                    redirect(base_url() . 'assign-applicatioin-users/' . $app_id);
                }
            }
        }


        $app_user_list = $this->app_model->get_assigned_app_to_user($app_id);
        $data['app_user_list'] = $app_user_list;
        $selected_app = $this->app_model->get_app($app_id);
        $data['app_name'] = $selected_app['name'];
        $data['name'] = $selected_app['name'];
        $data['app_id'] = $app_id;
        $data['active_tab'] = 'app-users';
        $data['pageTitle'] = "Add User-".PLATFORM_NAME;
        $this->load->view('templates/header', $data);
        $this->load->view('app/app_assign_users', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Function for deleting application
     * @param string $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function deleteasignuser($slug, $app_id) {
        if ($this->session->userdata('logged_in')) {

            $asigned_id = $slug;

            $this->db->delete('users_app', array('id' => $asigned_id));

            $this->session->set_flashdata('validate', array('message' => 'Asigned user deleted', 'type' => 'success'));
            redirect(base_url() . 'assign-applicatioin-users/' . $app_id);
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Action for application users
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appusers() {
        if (!$this->acl->hasPermission('app_users', 'view')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect('/');
        }
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        if (!$this->acl->hasSuperAdmin()) {

            $app_list = $this->app_model->get_app_by_user($data['login_user_id']);
            $data['app_list'] = $app_list;
        }

        $batch = array();
        if ($this->input->post()) {
            $app_id = $this->input->post('app_id');
            if ($this->acl->hasSuperAdmin()) {
                $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('app_id', 'App Id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('name', 'First Name', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('town', 'district', 'Last Name', 'trim|required|min_length[1]|xss_clean');
            if($this->input->post('login_user') == '')
            {
                $this->form_validation->set_rules('imei_no', 'IMEI #', 'trim|required|callback_appuser_imei_already_exist[' . $app_id . ']');
            }
            else{
                
                $this->form_validation->set_rules('login_user', 'User Name', 'trim|required|callback_appuser_login_name_already_exist');
            }

            if ($this->form_validation->run() == FALSE) {
                $batch = array($this->input->post('department_id'));
                $this->session->set_flashdata('validate', array('message' => "User must enter required field(s).", 'type' => 'warning'));
            } else {
                if ($this->acl->hasSuperAdmin()) {
                    $department_id = $this->input->post('department_id');
                }
                $view_id = $this->input->post('view_id');

                $imei_no = $this->input->post('imei_no');
                if($this->input->post('imei_no')=='')
                {
                    $imei_no = $this->input->post('login_user');
                }

                $appdata = array(
                    'app_id' => $app_id,
                    'department_id' => $department_id,
                    'view_id' => $view_id,
                    'name' => $this->input->post('name'),
                    'town' => $this->input->post('town'),
                    'district' => $this->input->post('district'),
                    'imei_no' => $imei_no,
                    'cnic' => $this->input->post('cnic'),
                    'login_user' => $this->input->post('login_user'),
                    'login_password' => $this->input->post('login_password'),
                    'mobile_number' => $this->input->post('mobile_number')
                );
                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'insert', 'description' => 'app-users', 'after' => json_encode($appdata), 'app_id' => $app_id,);
                addlog($logary);

                $this->db->insert('app_users', $appdata);
                $this->session->set_flashdata('validate', array('message' => 'Application User added successfully', 'type' => 'success'));
                redirect(base_url() . 'applicatioin-users');
            }
        }

        $departments = $this->department_model->get_department();

        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }
        if ($this->acl->hasSuperAdmin()) {
            $app_user_list = $this->app_users_model->get_app_user_listing($data['login_department_id']);
        }else{
            $app_user_list = $this->app_users_model->get_app_user_listing($data['login_department_id'],null,null,null,null,null,$app_list);
        }
        $data['app_user_list'] = $app_user_list;
        $data['departments'] = $dep;
        $data['batch'] = $batch;
        $data['active_tab'] = 'app-users';
        $data['pageTitle'] = "Add User-".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('app/app_users', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Action for ajax application users
     * @author  Irfan Javed<irfanjvd@gamil.com>
     */
    public function ajaxappusers() {

        if (!$this->acl->hasPermission('app_users', 'view')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect('/');
        }
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        $app_list = array();
        if (!$this->acl->hasSuperAdmin()) {

            $app_list = $this->app_model->get_app_by_user($data['login_user_id']);
            $data['app_list'] = $app_list;
        }

        // field name, error message, validation rules

        $batch = array();
        $departments = $this->department_model->get_department();
        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }

        $app_user_list = $this->app_users_model->get_app_user_listing($data['login_department_id'],$_GET['iDisplayStart'],$_GET['iDisplayLength'],$_GET['sSearch'],$_GET['iSortCol_0'],$_GET['sSortDir_0'],$app_list);
        $total_apps_users = $this->app_users_model->get_app_user_total($data['login_department_id'],$_GET['sSearch']);
//        making array for ajax datatable...
        $data2= array("sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_apps_users,
            "iTotalDisplayRecords" => $total_apps_users,);

        foreach ($app_user_list as $key => $val) {
            $data2['aaData'][] = array(
                'app_id' => $val['app_name'],
                'deoartment_id' => $val['department_name'],
                'view_id' => $val['view_name'],
                'name' => $val['user_name'],
                'district' => $val['district'],
                'town' => $val['user_town'],
                'imei_no' => $val['imei_no'],
                'cnic' => $val['cnic'],
                'mobile_number' => $val['mobile_number'],
                'login_user' => $val['login_user'],
                'login_password' => $val['login_password'],
                'action' => $this->create_user_apps_action_buttons($val),

            );

        }

        if($total_apps_users==0){
            //echo json_encode(array('aaData'=>''));
            echo json_encode(array(                
                    //"sEcho" => 0,                
                    "iTotalRecords" => "0",                
                    "iTotalDisplayRecords" => "0",                
                    "aaData" => array()            
                    ));
        }else {
            echo json_encode($data2);
        }


    }
    public function appusersimportcsv(){

             if (!$this->acl->hasPermission('app_users', 'add')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect('/');
            }
            $this->load->library('form_validation');
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            if (!$this->acl->hasSuperAdmin()) {
                $app_list = $this->app_model->get_app_by_user($data['login_user_id']);
                $data['app_list'] = $app_list;
            }
            if ($this->input->post()) {
                if ($this->acl->hasSuperAdmin()) {
                        $this->form_validation->set_rules('department_id_import', 'Department', 'trim|required|xss_clean');
                }
                $this->form_validation->set_rules('app_id_import', 'App Id', 'trim|required|xss_clean');

                if ($this->form_validation->run() == FALSE) {
                    $batch = array($this->input->post('department_id_import'));
                    $this->session->set_flashdata('validate', array('message' => "User must enter required field(s).", 'type' => 'warning'));
                    redirect(base_url() . 'applicatioin-users');
                } else {

                    $department_id = $session_data['login_department_id'];
                    if ($this->acl->hasSuperAdmin()) {
                        $department_id = $this->input->post('department_id_import');
                    }
                    $data['department_id_import'] = $department_id;
                    $app_id = $data['app_id_import'] = $this->input->post('app_id_import');
                    $data['view_id_import'] = $this->input->post('view_id_import');

                    $fileName = 'import_imei_user_' . $app_id . '.csv';
                    if ($_FILES['user_import']['name'] != '') {
                        //upload form icon
                        $abs_path = './assets/data/';
                        $old = umask(0);
                        @mkdir($abs_path, 0777);
                        umask($old);

                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $fileName;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'csv';
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('user_import')) {

                            $this->session->set_flashdata('validate', array('message' => 'File uploading issue, Check format of file or remove special character and spaces into file name', 'type' => 'error'));
                            redirect(base_url() . 'applicatioin-users');
                            
                        } else {
                            $file_path = './assets/data/'.$fileName;
                            $data['upload_file_path'] = $file_path;

                            $row = 1;
                            $options = array();
                            $existArray = array();
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

                            //$data['api_id'] = $api_id;
                            //data['api_secret'] = $api_rec['secret_key'];
                            //$data['api_title'] = $api_rec['title'];
                            $data['active_tab'] = 'app_user_import';
                            $data['app_name'] = "";
                            $data['pageTitle'] = "Import application user -".PLATFORM_NAME;
                            $this->load->view('templates/header', $data);
                            $this->load->view('app/appusersimportcsv', $data);
                            $this->load->view('templates/footer', $data);
                        }

                    }
                    else{
                        $this->session->set_flashdata('validate', array('message' => "Select CSV file", 'type' => 'warning'));
                        redirect(base_url() . 'applicatioin-users');
                    }
                }
            }
            else{
                redirect(base_url() . 'applicatioin-users');
            }

    }

    /**
     * Action for application users
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function import_app_user_csv() {
       

        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);

        // field name, error message, validation rules

        $batch = array();
        if ($this->input->post()) {
            
            $app_id = $this->input->post('app_id_import');

            $user_name_index = $this->input->post('user_name');
            $imei_no_index = $this->input->post('imei_no');
            $district_index = $this->input->post('district');
            $town_index = $this->input->post('town');
            $cnic_index = $this->input->post('cnic');
            $mobile_number_index = $this->input->post('mobile_number');
            $mobile_network_index = $this->input->post('mobile_network');
            $login_user_index = $this->input->post('login_user');
            $login_password_index = $this->input->post('login_password');

        
            $department_id = $this->input->post('department_id_import');
            $view_id = $this->input->post('view_id_import');
    		//success
    		$file_path = $this->input->post('upload_file_path');
    		$row = 1;
    		$total_insertion = 0;
    		$query = $this->db->query("SELECT * FROM app_users WHERE is_deleted=0 AND app_id = '$app_id'");
    		$myimeidata = $query->result_array();
    		$already_exist = array();
    		foreach ($myimeidata as $key => $myimei) {
                        if(!empty($myimei['imei_no'])){
                            $already_exist[] = $myimei['imei_no'];
                        }
                        else if(!empty($myimei['login_user'])) {
                            $already_exist[] = $myimei['login_user'];
                        }
    		}
    		$already_ex='';
    		$format_error = false;
    		if (($handle = fopen($file_path, "r")) !== FALSE) {
    			while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
    				if ($row == 1) {
    					$row++;
    				} else {
                                        
                        if(!empty($data[$imei_no_index])){
                            $unique_identifi = $data[$imei_no_index];
                            $query = $this->db->query("SELECT * FROM app_users WHERE is_deleted=0 AND app_id = '$app_id' AND imei_no='$unique_identifi'");
                        }
                        elseif(!empty($data[$login_user_index])){
                            $unique_identifi = $data[$login_user_index];
                            $query = $this->db->query("SELECT * FROM app_users WHERE is_deleted=0 AND app_id = '$app_id' AND login_user='$unique_identifi'");
                        }
                        $current_rec = $query->row_array();
                        if (in_array($unique_identifi, $already_exist)) {
                                $record_imei = array(
                                                'app_id' => $app_id,
                                                'department_id' => $current_rec['department_id'],
                                                'view_id' => $current_rec['view_id'],
                                                'name' => $data[$user_name_index],
                                                'town' => $data[$town_index],
                                                'district' => $data[$district_index],
                                                'imei_no' => $data[$imei_no_index],
                                                'cnic' => isset($data[$cnic_index])?$data[$cnic_index]:'',
                                                'mobile_number' => $data[$mobile_number_index],
                                                'is_deleted' => '0',
                                                'login_user' => isset($data[$login_user_index])?$data[$login_user_index]:'',
                                                'login_password' => isset($data[$login_password_index])?$data[$login_password_index]:'',
                                                'mobile_network' => isset($data[$mobile_network_index])?$data[$mobile_network_index]:'',
                                );

                                $this->db->where('id', $current_rec['id']);
                                $this->db->update('app_users', $record_imei);
                                $already_ex.=$unique_identifi.',';
                                continue;
                        } else {
                                $already_exist[] = $unique_identifi;
                                $record_imei = array(
                                                'app_id' => $app_id,
                                                'department_id' => $department_id,
                                                'view_id' => $view_id,
                                                'name' => $data[$user_name_index],
                                                'town' => $data[$town_index],
                                                'district' => $data[$district_index],
                                                'imei_no' => $data[$imei_no_index],
                                                'cnic' => isset($data[$cnic_index])?$data[$cnic_index]:'',
                                                'mobile_number' => $data[$mobile_number_index],
                                                'is_deleted' => '0',
                                                'login_user' => isset($data[$login_user_index])?$data[$login_user_index]:'',
                                                'login_password' => isset($data[$login_password_index])?$data[$login_password_index]:'',
                                                'mobile_network' => isset($data[$mobile_network_index])?$data[$mobile_network_index]:'',
                                );

                                $total_insertion++;
                                $this->db->insert('app_users', $record_imei);
                        }
                        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                        $logary = array('action' => 'insert', 'description' => 'app users using import CSV', 'after' => json_encode($record_imei), 'app_id' => $app_id,);
                        addlog($logary);
    				}
    			}
    			if($already_ex!=''){
    				$already_ex=", Already Registered Users but Updated : ".$already_ex;
    			}
    			
    			$this->session->set_flashdata('validate', array('message' => 'Application User imported successfully, Total Inserted Users = '.$total_insertion.$already_ex, 'type' => 'success'));
    			
    			fclose($handle);
    		}
        }
        redirect(base_url() . 'applicatioin-users');
        
    }

    /**
     * Action for edit application users
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function editAppUser($slug) {
        $user_id = $slug;
        if (!$this->acl->hasPermission('app_users', 'edit')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'users');
        }
        $user_rec = $this->app_users_model->get_app_user_by_id($user_id);

        $view_lists = $this->app_users_model->get_app_views($user_rec['app_id']);
        $app_id = $user_rec['app_id'];
        $data = array(
            'user_id' => $user_rec['user_id'],
            'user_name' => $user_rec['user_name'],
            'town' => $user_rec['town'],
            'imei_no' => $user_rec['imei_no'],
            'cnic' => $user_rec['cnic'],
            'department_id' => $user_rec['department_id'],
            'department_name' => $user_rec['department_name'],
            'user_district' => $user_rec['user_district'],
            'view_id' => $user_rec['view_id'],
            'view_list' => $view_lists,
            'mobile_number' => $user_rec['mobile_number'],
            'login_user' => $user_rec['login_user'],
            'login_password' => $user_rec['login_password'],
        );
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_name', 'User', 'trim|required|xss_clean');
            if($this->input->post('login_user') == '')
                $this->form_validation->set_rules('imei_no', 'IMEI NO', 'trim|required|xss_clean');
            else
                $this->form_validation->set_rules('login_user', 'User Name', 'trim|required|callback_appuser_login_name_already_exist');
            //$this->form_validation->set_rules('mobile_number', 'Mobile NO', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('town', 'Town  Name', 'trim|required|min_length[1]|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                redirect(base_url() . 'app/editAppUser/' . $slug);
            } else {
                $data = array(
                    'name' => $this->input->post('user_name'),
                    'town' => $this->input->post('town'),
                    'district' => $this->input->post('user_district'),
                    'imei_no' => $this->input->post('imei_no'),
                    'cnic' => $this->input->post('cnic'),
                    'view_id' => $this->input->post('view_id'),
                    'mobile_number' => $this->input->post('mobile_number'),
                    'login_user' => $this->input->post('login_user'),
                    'login_password' => $this->input->post('login_password'),
                );
                $this->db->where('id', $slug);
                $this->db->update('app_users', $data);
                
                
                //Add form records updation part
                $old_imei_no = $user_rec['imei_no'];
                $new_imei_no = $this->input->post('imei_no');
                $imei_update_array = array('imei_no'=>$new_imei_no);
                $all_forms = $this->form_model->get_form_by_app($app_id);
                foreach ($all_forms as $forms) {
                    $form_id = $forms['form_id'];
                    $this->db->where('imei_no', $old_imei_no);
                    $this->db->update('zform_'.$form_id, $imei_update_array);
                }
                

                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'update', 'description' => 'Edit application user', 'after' => json_encode($data), 'before' => json_encode($user_rec));
                addlog($logary);
                $this->session->set_flashdata('validate', array('message' => 'Application User Updated Successfully.', 'type' => 'success'));
                redirect(base_url() . 'applicatioin-users');
            }
        }

        $departments = $this->department_model->get_department();
        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }

        $data['departments'] = $dep;
        $data['active_tab'] = 'users';
        $data['pageTitle'] = "Edit user-".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('app/edit_app_user', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Action for application user views
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appusersview() {

        if (!$this->acl->hasPermission('users', 'add')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect('/');
        }
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        if (!$this->acl->hasSuperAdmin()) {
            $app_list = $this->app_model->get_app_by_user($data['login_user_id']);
            $data['app_list'] = $app_list;
        }

        // field name, error message, validation rules
        $batch = array();
        if ($this->input->post()) {

            $app_id = $this->input->post('app_id');
            if ($this->acl->hasSuperAdmin()) {
                $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('app_id', 'App Id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('name', 'First Name', 'trim|required|min_length[1]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $batch = array($this->input->post('department_id'));
            } else {
                if ($this->acl->hasSuperAdmin()) {
                    $department_id = $this->input->post('department_id');
                }
                $appviewdata = array(
                    'app_id' => $app_id,
                    'name' => $this->input->post('name'),
                );

                $this->db->insert('app_users_view', $appviewdata);
                $this->session->set_flashdata('validate', array('message' => 'User view added successfully', 'type' => 'success'));
                redirect(base_url() . 'app/appusersview');
            }
        }

        $departments = $this->department_model->get_department();
        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }

        $app_views_list = $this->app_users_model->get_views_listing($data['login_department_id']);
        $data['app_views_list'] = $app_views_list;

        $data['departments'] = $dep;
        $data['batch'] = $batch;
        $data['active_tab'] = 'app-users-views';
        $data['pageTitle'] = "Add User View - ".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('app/app_users_view', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Function for application user registration
     * @param string $imei_no
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function appuser_imei_already_exist($imei_no, $app_id) {
        if ($this->app_model->appuser_imei_already_exist($imei_no, $app_id)) {
            $this->form_validation->set_message('appuser_imei_already_exist', 'The %s already registered for this app.');
            return false;
        } else {
            return true;
        }
    }
    /**
     * Function for application user registration
     * @param string $imei_no
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function appuser_login_name_already_exist($login_user, $app_id) {
        if ($this->app_model->appuser_login_name_already_exist($login_user, $app_id)) {
            $this->form_validation->set_message('appuser_login_name_already_exist', 'The %s not available.');
            return false;
        } else {
            return true;
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

            $departments = $this->department_model->get_department();
            if ($this->acl->hasSuperAdmin()) {
                $dep[''] = 'Select';
                foreach ($departments as $row) {
                    $dep[$row['id']] = $row['name'];
                }
                $data['departments'] = $dep;
            }

            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];

            if ($this->input->post()) {

                if ($this->acl->hasSuperAdmin()) {
                    $batch = array($this->input->post('department_id'));
                    $department_id = $this->input->post('department_id');
                    if ($department_id == 'new') {
                        $dep_array = array(
                            'name' => $this->input->post('department_name')
                        );
                    } else {
                        $this->form_validation->set_rules('app_name', 'App', 'trim|required|xss_clean|callback_app_already_exist[' . $department_id . ']');
                    }
                    $required_if = $this->input->post('department_id') == 'new' ? '|required' : '';
                    $this->form_validation->set_rules('department_name', 'Department Name', 'trim' . $required_if . '|min_length[4]|xss_clean|callback_department_name_exists');
                    $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
                } else {
                    $this->form_validation->set_rules('app_name', 'App', 'trim|required|xss_clean|callback_app_already_exist[' . $department_id . ']');
                }

                if ($this->form_validation->run() == FALSE) {
                    $this->session->set_flashdata('validate', array('message' => 'Please enter the Required Fields', 'type' => 'error'));
                    redirect(base_url());
                } else {
                    $new = false;
                    $user_id = $data['login_user_id'];
                    if ($this->acl->hasSuperAdmin()) {
                        $user_id = 0;
                        if ($department_id == 'new') {
                            $this->db->insert('department', $dep_array);
                            $department_id = $this->db->insert_id();
                            $new = true;
                        }
                    }

                    $appName = trim($this->input->post('app_name'));
                    $appdata = array(
                        'department_id' => $department_id,
                        'user_id' => $data['login_user_id'],
                        'description' => '',
                        'full_description' => '',
                        'icon' => '',
                        'name' => $appName
                    );
                    $this->db->insert('app', $appdata);
                    $app_id = $this->db->insert_id();

                    if (!$this->acl->hasSuperAdmin()) {
                        $asign_array = array('user_id' => $session_data['login_user_id'], 'app_id' => $app_id);
                        $this->db->insert('users_app', $asign_array);
                    }

                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'insert', 'description' => 'add new application', 'after' => json_encode($appdata), 'app_id' => $app_id, 'app_name' => $appName);
                    addlog($logary);

                    //upload app icon
                    $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
                    $old = umask(0);
                    @mkdir($abs_path, 0777);
                    umask($old);
                    $iconName = 'appicon_' . $app_id . '.png';
                    if ($_FILES['userfile_addapp']['name'] != '') {
                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $iconName;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'png';
                        $config["max_size"] = 1024;
//                        $config["max_width"] = 400;
//                        $config["max_height"] = 400;
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('userfile_addapp')) {
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your app.', 'type' => 'warning'));
                        } else {
                            //success
                            $imageData=$this->upload->data();
//                            $this->load->library('image_lib');
                            $config['overwrite'] = TRUE;
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $imageData['full_path'];
                            $config['new_image'] = $imageData['full_path'];
//                            $config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 200;
                            $config['height'] = 200;
                            $this->load->library('image_lib', $config);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                        }
                    } else {
                        $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
                        $from_path = FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png';
                        @copy($from_path,$abs_path.$iconName);
                        
                        $from_path_splash = FORM_IMG_DISPLAY_PATH . '../form_icons/splash.png';
                        @copy($from_path_splash, $abs_path.'splash.png');
                    }

                    $change_icon = array(
                        'icon' => $iconName,
                        'splash_icon' => 'splash.png',
                        'user_id' => $data['login_user_id']
                    );
                    $this->db->where('id', $app_id);
                    $this->db->update('app', $change_icon);

                    //Old app setting
                    $general_settings = array(
                        'app_id' => $app_id,'latitude' => '31.58219141239757','longitude' => '73.7677001953125','zoom_level' => '7','map_type' => 'Pin','district_filter' => 'Off','uc_filter' => 'Off','app_language' => 'english',
                    	'setting_type'=>'GENERAL_SETTINGS',
                    	'filters'=>GENERAL_SETTINGS
                    );

                    $result_view_settings = array(
                        'app_id' => $app_id,
                        'setting_type'=>'RESULT_VIEW_SETTINGS',
                        'filters'=>RESULT_VIEW_SETTINGS
                    );

                    $map_view_settings = array(
                        'app_id' => $app_id,
                        'setting_type'=>'MAP_VIEW_SETTINGS',
                        'filters'=>MAP_VIEW_SETTINGS
                    );

                    $graph_view_settings = array(
                        'app_id' => $app_id,
                        'setting_type'=>'GRAPH_VIEW_SETTINGS',
                        'filters'=>GRAPH_VIEW_SETTINGS
                    );

                    $this->db->insert('app_settings', $general_settings);
                    $this->db->insert('app_settings', $result_view_settings);
                    $this->db->insert('app_settings', $map_view_settings);
                    $this->db->insert('app_settings', $graph_view_settings);

                    //new app setting

                    if ($new) {
                        $dep_id = $this->db->insert_id();
                        $groupdata = array(
                            'department_id' => $dep_id,
                            'type' => 'admin'
                        );
                        $this->db->insert('users_group', $groupdata);
                    }

                    $this->session->set_flashdata('validate', array('message' => 'Application added successfully.', 'type' => 'success'));
                    if ($this->acl->hasPermission('app', 'edit')) {
                        redirect(base_url() . 'app-landing-page/' . $app_id);
                    } else {
                        redirect(base_url());
                    }
                }
            }

            $data['batch'] = $batch;
            if ($this->acl->hasSuperAdmin()) {
                $data['app'] = $this->app_model->get_app_by_department_for_super();
            } else {
                $data['app'] = $this->app_model->get_app_by_user($data['login_user_id']);
            }
            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            $this->load->view('app/add', $data);
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
        $app_id = $slug;
        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            if (!$this->acl->hasPermission('form', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . '');
            }

            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            if ($this->input->post()) {
                $appName = trim($this->input->post('app_name'));
                $selected_app = $this->app_model->get_app($app_id);
                if ($appName == '') {
                    $this->session->set_flashdata('validate', array('message' => 'Application name should not be empty.', 'type' => 'error'));
                    redirect(base_url() . 'app-landing-page/' . $app_id);
                }
                if ($this->app_model->app_already_exist($appName, $selected_app['department_id'], $app_id)) {
                    $this->session->set_flashdata('validate', array('message' => 'This application name already exist for this department', 'type' => 'error'));
                    redirect(base_url() . 'app-landing-page/' . $app_id);
                }
                
                $iconName = $selected_app['icon'];
                $splash_icon = $selected_app['splash_icon'];
                //upload form icon
                $abs_path = './assets/images/data/form_icons/' . $app_id;
                $old = umask(0);
                @mkdir($abs_path, 0777);
                umask($old);
                if (!empty($_FILES['userfile']['name']))
                {
                        $iconName = 'appicon_' . $app_id . '.png';
                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $iconName;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'png';
                        $config["max_size"] = 1024;
        //                $config["max_width"] = 400;
        //                $config["max_height"] = 400;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload()) {
                            $iconName = $selected_app['icon'];
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your app.', 'type' => 'warning'));
                        } else {
                            //success
                            $imageData=$this->upload->data();
        //                            $this->load->library('image_lib');
                            $config['overwrite'] = TRUE;
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $imageData['full_path'];
                            $config['new_image'] = $imageData['full_path'];
        //                            $config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 200;
                            $config['height'] = 200;


                            $this->load->library('image_lib', $config);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                        }
                }
                
                if (!empty($_FILES['splashfile']['name']))
                {
                        $splash_icon = 'splash.png';
                        $config['upload_path'] = $abs_path;
                        $config['file_name'] = $splash_icon;
                        $config['overwrite'] = TRUE;
                        $config["allowed_types"] = 'png';
                        $config["max_size"] = 3024;
//                        $config["min_width"] = 720;
//                        $config["min_height"] = 1280;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('splashfile')) {
                            $splash_icon = $selected_app['splash_icon'];
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default splash screen has been embeded with your app.', 'type' => 'warning'));
                        } else {
                            //success
                            $imageData=$this->upload->data();
        //                            $this->load->library('image_lib');
                            $config['overwrite'] = TRUE;
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $imageData['full_path'];
                            $config['new_image'] = $imageData['full_path'];
        //                            $config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 720;
                            $config['height'] = 1280;


                            $this->load->library('image_lib', $config);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                        }
                }
                $is_secure = $this->input->post('is_secure');
                if ($this->input->post('is_authorized'))
                    $is_authorized = '1';
                else
                    $is_authorized = '0';

                $change_icon = array(
                    'name' => $appName,
                    'icon' => $iconName,
                    'splash_icon' => $splash_icon,
                    'is_authorized' => $is_authorized,
                    'is_secure' => $is_secure
                );
                $this->db->where('id', $app_id);
                $this->db->update('app', $change_icon);

                $selected_app['description'] = '';
                $selected_app['full_description'] = '';
                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'update', 'description' => 'edit application', 'before' => json_encode($selected_app), 'after' => json_encode($change_icon), 'app_id' => $app_id, 'app_name' => $selected_app['name']);
                addlog($logary);
                if ($this->input->post('is_edit') == '1') {
                    $this->session->set_flashdata('validate', array('message' => 'Android application created successfully.', 'type' => 'success'));
                    redirect(base_url() . 'app/createapk/' . $app_id);
                } else {
                    $this->session->set_flashdata('validate', array('message' => 'Application edited successfully.', 'type' => 'success'));
                    redirect(base_url() . 'app-landing-page/' . $app_id);
                }
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Action popup for edit application
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function editpopup($slug) {

        $app_id = $slug;
        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            if (!$this->acl->hasPermission('form', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            if ($this->input->post('app_name')) {
                $appName = trim($this->input->post('app_name'));
                $selected_app = $this->app_model->get_app($app_id);
                if ($this->app_model->app_already_exist($appName, $selected_app['department_id'], $app_id)) {
                    $this->session->set_flashdata('validate', array('message' => 'This application name already exist for this department', 'type' => 'error'));
                    redirect(base_url() . 'app-landing-page/' . $app_id);
                }
                //upload form icon
                $abs_path = './assets/images/data/form_icons/' . $app_id;
                $old = umask(0);
                @mkdir($abs_path, 0777);
                umask($old);
                $iconName = 'appicon_' . $app_id . '.png';
                if (!empty($_FILES['userfile_popup']['name']))
                {
                    $config['upload_path'] = $abs_path;
                    $config['file_name'] = $iconName;
                    $config['overwrite'] = TRUE;
                    $config["allowed_types"] = 'png';
                    $config["max_size"] = 1024;
                    $config["max_width"] = 400;
                    $config["max_height"] = 400;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('userfile_popup')) {
                        $iconName = $selected_app['icon'];
                        $this->data['error'] = $this->upload->display_errors();
                        $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your app.', 'type' => 'warning'));
                    } else {
                        //success
                        //success
                            $iconData=$this->upload->data();
        //$this->load->library('image_lib');
                            $config['overwrite'] = TRUE;
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $iconData['full_path'];
                            $config['new_image'] = $iconData['full_path'];
        //$config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 200;
                            $config['height'] = 200;


                            $this->load->library('image_lib', $config);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                    }
                }
                if (!empty($_FILES['splashfile']['name']))
                {
                        $splash_icon = 'splash.png';
                        $config1['upload_path'] = $abs_path;
                        $config1['file_name'] = $splash_icon;
                        $config1['overwrite'] = TRUE;
                        $config1["allowed_types"] = 'png';
                        $config1["max_size"] = 3048;
                        //$config["min_width"] = 720;
                        //$config["min_height"] = 1280;
                        $this->load->library('upload', $config1);
                        if (!$this->upload->do_upload('splashfile')) {
                            $splash_icon = $selected_app['splash_icon'];
                            $this->data['error'] = $this->upload->display_errors();
                            $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default splash screen has been embeded with your app.', 'type' => 'warning'));
                            redirect(base_url() . 'app-landing-page/' . $app_id);
                        } else {
                            //success
                            $imageData=$this->upload->data();
        //$this->load->library('image_lib');
                            $config1['overwrite'] = TRUE;
                            $config1['image_library'] = 'gd2';
                            $config1['source_image'] = $imageData['full_path'];
                            $config1['new_image'] = $imageData['full_path'];
        //$config['create_thumb'] = TRUE;
                            $config1['maintain_ratio'] = FALSE;
                            $config1['width'] = 720;
                            $config1['height'] = 1280;


                            $this->load->library('image_lib', $config1);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                        }
                }

                $change_icon = array(
                    'name' => $appName,
                    'icon' => $iconName,
                    'splash_icon' => $splash_icon,

                );
                $this->db->where('id', $app_id);
                $this->db->update('app', $change_icon);

                $this->session->set_flashdata('validate', array('message' => 'Application edited successfully.', 'type' => 'success'));
                redirect(base_url() . 'app-landing-page/' . $app_id);
            }

            $selected_app = $this->app_model->get_app($app_id);

            $data['name'] = $selected_app['name'];
            $data['icon'] = $selected_app['icon'];
            $data['splash_icon'] = 'splash.png';
            $data['is_authorized'] = $selected_app['is_authorized'];
            $data['is_secure'] = $selected_app['is_secure'];
            $data['app_id'] = $app_id;

            $data['pageTitle'] = $data['name'] . "-Application-".PLATFORM_NAME;
            $this->load->view('app/edit_popup', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Action for design landing page
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appbuilder($slug) {

        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $app_id = $slug;

            $request_app = $this->app_model->get_app($app_id);
            if (!$request_app) {
                $this->session->set_flashdata('validate', array('message' => "Application has been removed", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $data['super_app_user'] = 'no';
            if (!$this->acl->hasSuperAdmin()) {
                $login_data = $this->session->userdata('logged_in');
                if ($request_app['department_id'] != $login_data['login_department_id']) {
                    $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                    redirect(base_url() . 'apps');
                }
                if ($request_app['user_id'] == $login_data['login_user_id']) {
                    $data['super_app_user'] = 'yes';
                }
            } else {
                $data['super_app_user'] = 'yes';
            }

            $view_list = $this->app_users_model->get_app_views($app_id);
            $data['view_list'] = $view_list;

            if ($this->input->post('view_id')) {
                $view_id = $this->input->post('view_id');
                if ($view_id == 'default') {
                    $view_id = 0;
                }
                $sess_array = array('view_id' => $view_id, 'app_id' => $app_id);
                $this->session->set_userdata('view_session', $sess_array);
                $data['view_id'] = $this->input->post('view_id');
            }
            $view_id = 0;
            if ($this->session->userdata('view_session')) {
                $session_view_data = $this->session->userdata('view_session');
                if ($session_view_data['app_id'] != $app_id) {
                    $view_id = 0;
                } else {
                    $view_id = $session_view_data['view_id'];
                }
            }
            $data['view_id'] = $view_id;

            //get all forms and its icon form left panel
            if ($view_id) {
                $allform = $this->form_model->get_form_by_app_view($app_id, $view_id);
            } else {
                $allform = $this->form_model->get_form_by_app($app_id);
            }
            $formdata = array();
            foreach ($allform as $formvalue) {
                $formdata[] = array(
                    'form_id' => $formvalue['form_id'],
                    'title' => $formvalue['form_name'],
                    'icon' => $formvalue['form_icon'],
                    'des' => $formvalue['full_description'],
                    'linkfile' => $formvalue['next'],
                );
            }
            $data['forms'] = $formdata;

            if (count($formdata) == 1) {
                redirect(base_url() . 'app-form/' . $formdata[0]['form_id']);
            }

            if (!$this->acl->hasPermission('form', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }

            if ($this->input->post('apphtml')) {

                $description = $this->input->post('apphtml');
                $full_description = $this->get_full_description($description);
                if ($view_id) {
                    if ($this->app_users_model->get_app_views_existance($app_id, $view_id)) {
                        $dataview = array(
                            'description' => $description,
                            'full_description' => $full_description
                        );
                        $this->db->where('app_id', $app_id);
                        $this->db->where('view_id', $view_id);
                        $this->db->update('app_views', $dataview);
                    } else {
                        $dataview = array(
                            'app_id' => $app_id,
                            'view_id' => $view_id,
                            'description' => $description,
                            'full_description' => $full_description
                        );
                        $this->db->insert('app_views', $dataview);
                    }
                } else {
                    $dataview = array(
                        'description' => $description,
                        'full_description' => $full_description
                    );
                    $this->db->where('id', $app_id);
                    $this->db->update('app', $dataview);
                }

                $this->change_installed_app_status($app_id);
            }
            $selected_app = $this->app_model->get_app($app_id);
            $data['app_name'] = $selected_app['name'];
            $selected_app = $this->app_model->get_app($app_id, $view_id);
            if ($view_id) {
                if (isset($selected_app['avid']) && !empty($selected_app['avid'])) {
                    $data['description'] = $selected_app['av_description'];
                } else {
                    $data['description'] = $selected_app['description'];
                }
            } else {
                $data['description'] = $selected_app['description'];
            }
            $data['name'] = $selected_app['name'];
            $data['icon'] = $selected_app['icon'];
            $data['splash_icon'] = 'splash.png';
            

            $data['app_id'] = $app_id;
//            $settings_exist = $this->app_model->get_app_settings($app_id);
            $settings_exist=get_app_general_settings($app_id);

            $data['app_settings'] = $settings_exist;

            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $data['pageTitle'] = $data['name'] . "-Application-".PLATFORM_NAME;
            $data['active_tab'] = 'app_form_build';


            $this->load->view('templates/form_builder_header', $data);
            $this->load->view('app/app_builder', $data);
            $this->load->view('templates/footer');

        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Action for change installed application status
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function change_installed_app_status($app_id) {
        //change status update
        $all_installed = $this->app_installed_model->get_app_installed_byappid($app_id);
        if ($all_installed) {
            foreach ($all_installed as $app) {
                $change_status = array(
                    'change_status' => '1'
                );
                $this->db->where('id', $app['id']);
                $this->db->update('app_installed', $change_status);
            }
        }
        $change_status_release = array(
            'change_status' => '1'
        );
        $this->db->where('app_id', $app_id);
        $this->db->update('app_released', $change_status_release);
    }

    /**
     * Function for checking department existence
     * @param string $department_name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function department_name_exists($key) {
        if ($this->department_model->department_already_exist($key)) {
            $this->form_validation->set_message('department_name_exists', 'The %s already exists');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Function for checking application existence against department
     * @param string $department_name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function app_already_exist($app_name, $department_id) {
        if ($this->app_model->app_already_exist($app_name, $department_id)) {
            $this->form_validation->set_message('app_already_exist', 'The %s already exists');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Function for checking application existence
     * @param string $department_name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function check_app_name_availability() {
        $app_name = $this->input->post('app_name');
        $department_id = $this->input->post('department_id');

        if ($this->app_model->app_already_exist($app_name, $department_id)) {
            $jsone_array = array(
                'status' => '0', //No availability
                'message' => 'Application name already taken',
            );
        } else {
            $jsone_array = array(
                'status' => '1', //Name availabile
                'message' => 'Application name available',
            );
        }
        echo json_encode($jsone_array);
    }

    /**
     * Function for deleting application
     * @param string $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function delete($slug) {
        if ($this->session->userdata('logged_in')) {
            $app_id = $slug;
            if (!$this->acl->hasPermission('app', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $data = array(
                'is_deleted' => '1'
            );
            $this->db->where('id', $app_id);
            $this->db->update('app', $data);
            $this->db->select('*');
            $this->db->from('form');
            $this->db->where('app_id', $app_id);
            $query = $this->db->get();
            $form_listing = $query->result_array();
            foreach($form_listing as $form)
            {
            	$data_form = array(
            			'is_deleted' => '1'
            	);
            	$this->db->where('id', $form['id']);
            	$this->db->update('form', $data_form);
            }

            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary = array('action' => 'delete', 'description' => 'delete application', 'after' => json_encode($data), 'app_id' => $app_id, 'app_name' => $appName);
            addlog($logary);

            $this->session->set_flashdata('validate', array('message' => 'Application deleted successfully.', 'type' => 'success'));
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Function for saving released application version
     * @param string $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function releasedapk($slug) {
        if ($this->session->userdata('logged_in')) {
            $app_id = $slug;
            $app = $this->app_model->get_app($app_id);
            $data['app_name'] = $app['name'];
            $data['app_id'] = $app_id;
            if (!$this->acl->hasPermission('app', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url());
            }

            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $data['app'] = $this->app_released_model->get_app_released($app_id);
            $data['pageTitle'] = $app['name'] . " Version Listing-".PLATFORM_NAME;
            $data['active_tab'] = 'app_released';
            $this->load->view('templates/header', $data);
            $this->load->view('app/released_apk', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Function for Building android application
     * @param string $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function createapk($slug) {
        if ($this->session->userdata('logged_in')) {
            //for live
            //change directory path
            //change target 4 for live
            if (!$this->acl->hasPermission('app', 'build')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            ignore_user_abort(true);
            set_time_limit(0);
            $app_id = $slug;
            $app_general_setting = get_app_general_settings($app_id);
            $this->db->insert('app_build_request', array('app_id' => $app_id));
            $this->buildSleep($app_id);
            $site_settings = $this->site_model->get_settings('1');
            $directory_path = $site_settings['directory_path'];
            $target = $site_settings['android_target'];
            $platform = PLATFORM_PACKAGE;

            $dataq = $this->app_model->get_app($app_id);

            //version code calculation for app updation purpose
            $version_code = $this->app_released_model->get_max_released_version_code($app_id);
            if ($version_code) {
                $version_code = $version_code + 1;
            } else {
                $version_code = '1';
            }

            //version calculation for app name purpose
            $version = $this->app_released_model->get_max_released_version($app_id);
            if ($version) {
                $version = $version + 0.1;
            } else {
                $version = '1.0';
            }

            $is_secure = ($app_general_setting->secured_apk == 1)?'yes':'no';
            $high_resolution_image = 'NO';
            if(isset($app_general_setting->high_resolution_image) && $app_general_setting->high_resolution_image == 1){
                $high_resolution_image = 'YES';
            }
            $persist_images_on_device = 'YES';//If image delete after activity
            if(isset($app_general_setting->persist_images_on_device) && $app_general_setting->persist_images_on_device == 1){
                $persist_images_on_device = 'NO';//if image not delete after activity
            }
            
            
            $background_update = 'YES';//If image delete after activity
            if(isset($app_general_setting->background_update) && $app_general_setting->background_update == 0){
                $background_update = 'NO';//if image not delete after activity
            }
            $force_update = 'YES';//If image delete after activity
            if(isset($app_general_setting->force_update) && $app_general_setting->force_update == 0){
                $force_update = 'NO';//if image not delete after activity
            }
            $enable_auto_time = 'YES';
            if(isset($app_general_setting->enable_auto_time) && $app_general_setting->enable_auto_time == 0){
                $enable_auto_time = 'NO';
            }
           
            
            $tracking_status = 'YES';
            if(isset($app_general_setting->tracking_status) && $app_general_setting->tracking_status == 0){
                $tracking_status = 'NO';
            }
            $tracking_interval = 5;
            if(isset($app_general_setting->tracking_interval)){
                $tracking_interval = $app_general_setting->tracking_interval;
            }
            $tracking_distance = 100;
            if(isset($app_general_setting->tracking_distance)){
                $tracking_distance = $app_general_setting->tracking_distance;
            }
             $debug_tracking = 'NO';
            if(isset($app_general_setting->debug_tracking) && $app_general_setting->debug_tracking == 1){
                $debug_tracking = 'YES';
            }
            $has_geo_fencing = 'NO';
            if(isset($app_general_setting->has_geo_fencing) && $app_general_setting->has_geo_fencing == 1){
                $has_geo_fencing = 'YES';
            }
            $debug_geo_fencing = 'NO';
            if(isset($app_general_setting->debug_geo_fencing) && $app_general_setting->debug_geo_fencing == 1){
                $debug_geo_fencing = 'YES';
            }
            
            $new_appname = trim($dataq['name']);
            $appname = preg_replace("/[^A-Za-z0-9]/", "", strtolower($new_appname));
            $folderName = rand(2, time());
            $newProjectFolder = $appname . $folderName;

            //create new folder for this appname
            $src = $directory_path . '/assets/android/godk_android';
            $dst = $directory_path . '/assets/android/' . $newProjectFolder;
            $this->recurse_copy($src, $dst);
            //exit;
            //change appname and add app_id

            $path_string = $dst . '/res/values/strings.xml';
            $string_array = array(
                'app_id'=>$app_id,
                'path_string'=>$path_string,
                'app_name'=>$new_appname,
                'IS_SECURE_APP'=>$is_secure,
                'showHighResOption'=>$high_resolution_image,
                'PersistImagesOnDevice'=>$persist_images_on_device,
                'BackgroundUpdate'=>$background_update,
                'ForceUpdate'=>$force_update,
                'EnableAutoTime'=>$enable_auto_time,
                'TrackingStatus'=>$tracking_status,
                'TrackingInterval'=>$tracking_interval,
                'TrackingDistance'=>$tracking_distance,
                'DebugTracking'=>$debug_tracking,
                'hasGeoFencing'=>$has_geo_fencing,
                'DebugGeoFencing'=>$debug_geo_fencing,
            );
            $this->change_string_file($string_array);

            //change package name
            $path_string = $dst . '/AndroidManifest.xml';
            $package = 'com.government.appid' . $app_id . $platform . '.datakit.ui';
            $this->change_manifest_file($path_string, $package, $version, $version_code);

            if (file_exists("$directory_path/assets/images/data/form_icons/$app_id/appicon_$app_id.png")) {
                copy("$directory_path/assets/images/data/form_icons/$app_id/appicon_$app_id.png", "$dst/res/drawable/icon.png");
                copy("$directory_path/assets/images/data/form_icons/$app_id/splash.png", "$dst/res/drawable/splash.png");
            }

            //Copy all app_resources on build time
            $app_resource_src= $directory_path."/assets/android/app_resources";
            $app_resource_dst = $directory_path."/assets/android/$newProjectFolder/assets/HTML";
            $this->recurse_copy($app_resource_src, $app_resource_dst);

            //if build is secure then form will not create on build time
            if ($is_secure == 'no') {
                $forms = $this->form_model->get_form_by_app($app_id);
                $total_forms = count($forms);
                if ($total_forms > 1) {
                    $selected_app = $this->app_model->get_app($app_id);
                    $appFullDescription = $selected_app['full_description'];
                    $app_file = "$directory_path/assets/android/$newProjectFolder/assets/HTML/index.html";
                    $Handleapp = fopen($app_file, 'w');
                    fwrite($Handleapp, $appFullDescription);
                    fclose($Handleapp);
                }
                foreach ($forms as $form) {
                    $formId = $form['form_id'];
                    $formName = $form['form_name'];
                    $formDescription = $form['full_description'];
                    if ($total_forms == 1) {
                        $File = "$directory_path/assets/android/$newProjectFolder/assets/HTML/index.html";
                    } else {
                        $File = "$directory_path/assets/android/$newProjectFolder/assets/HTML/" . $form['next'];
                    }
                    $Handle = fopen($File, 'w');
                    fwrite($Handle, $formDescription);
                    fclose($Handle);
                }
            }

            //copy the all image files
            $src1 = $directory_path . '/assets/images/data/form_icons/' . $app_id;
            $dst1 = $directory_path . "/assets/android/$newProjectFolder/assets/HTML";
            $this->recurse_copy($src1, $dst1);

            //Change directory
            $path = $directory_path . '/assets/android/' . $newProjectFolder;
            chdir($path);
            $cmd = "pushd $path";
            exec($cmd);

            //update build.xml file for new app//For windows
            $envpath = 'C:\ant\bin\ant;C:\Program Files\Java\jdk1.7.0_51\bin\;C:\adt\sdk\tools';
            exec($envpath);

            //For Linux 1 for local and 4 for live
            $command = "android update project --target $target --name $appname --path $directory_path/assets/android/" . $newProjectFolder;

            exec($command, $output, $co);

            //Build new apk file
            //$command = "ant debug";
            $command = "ant release";
            exec($command, $output2, $co);

            //copy the apk file to app repository
            $newFileName = $appname . '-' . $app_id . '-' . $version . 'v.apk';
            copy("$directory_path/assets/android/$newProjectFolder/bin/$appname-release-unsigned.apk", "$directory_path/assets/android/apps/$appname-release-unsigned.apk");
            rename("$directory_path/assets/android/apps/$appname-release-unsigned.apk", "$directory_path/assets/android/apps/unaligned_$newFileName");

            //$keystore_command = "keytool -genkey -v -keystore $directory_path/assets/android/keystore/a_$app_id.keystore -alias DataPlug -keyalg RSA -keysize 2048 -validity 10000";
            $signing_command = "jarsigner -verbose -digestalg SHA1 -sigalg MD5withRSA -keystore $directory_path/assets/android/keystore/DataPlug.keystore -storepass dataplug_pitb -keypass dataplug_pitb $directory_path/assets/android/apps/unaligned_$newFileName DataPlug";
            exec($signing_command, $output3, $co);

            //$unalignedFileName = $appname .'-'. $app_id . '-' . $version . 'v-unaligned.apk';
            //$signing_command = "jarsigner -verbose -keystore $directory_path/assets/android/keystore/DataPlug.keystore -storepass dataplug_pitb -keypass dataplug_pitb $directory_path/assets/android/apps/$newFileName DataPlug";
            $zipaligned_command = "zipalign -v 4 $directory_path/assets/android/apps/unaligned_$newFileName $directory_path/assets/android/apps/$newFileName";
            exec($zipaligned_command, $output4, $co);
            unlink("$directory_path/assets/android/apps/unaligned_$newFileName");

            //QR code generation part, for this install QREncode package on server

            $qr_code_file_name = $appname . '-' . $app_id . '-' . $version . 'v.png';
            $qr_file_src = base_url().'assets/android/apps/'.$newFileName;
            $qr_file_dest = $directory_path.'/assets/android/qr_code/'.$qr_code_file_name;
            $qr_code_command = "qrencode -o $qr_file_dest -s 6 $qr_file_src";
            exec($qr_code_command, $outputqr, $co);

            //insert release record to database
            $data = array(
                'app_id' => $app_id,
                'app_name' => $new_appname,
                'version_code' => $version_code,
                'version' => $version,
                'app_file' => $newFileName,
                'qr_code_file' => $qr_code_file_name,
                'release_note' => '1.0'
            );
            $this->db->insert('app_released', $data);
            $this->db->delete('app_build_request', array('app_id' => $app_id));
            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary = array('action' => 'build', 'description' => 'Build New version of application', 'after' => json_encode($data), 'app_id' => $app_id, 'app_name' => $new_appname);
            addlog($logary);

            //delete new folder for this appname
            $this->recurse_delete($dst, true);
            $this->session->set_flashdata('validate', array('message' => 'Android application created successfully.', 'type' => 'success'));
            //redirect(base_url() . 'app/releasedapk/' . $app_id);
            redirect(base_url() . 'apps');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     * Function for copy the all android code in new directory for building process
     * @param string $src Source path
     * @param string $det destination path
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function recurse_copy($src, $dst) {
        $dir = opendir($src);
        $old = umask(0);
        @mkdir($dst, 0777);
        umask($old);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Function for delete the all android code that copied in new folder after building application
     * @param string $dir Source path
     * @param string $deleteRootToo destination path
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function recurse_delete($dir, $deleteRootToo) {
        if (!$dh = @opendir($dir)) {
            return;
        }
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }
            if (!@unlink($dir . '/' . $obj)) {
                $this->recurse_delete($dir . '/' . $obj, true);
            }
        }
        closedir($dh);
        if ($deleteRootToo) {
            @rmdir($dir);
        }
        return;
    }

    /**
     * Function for changing the android string file
     * @param string $path Source path
     * @param integer $app_id
     * @param integer $app_name
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function change_string_file($parray) {
        $app_name = preg_replace("/[^A-Za-z0-9]/", " ", $parray['app_name']);
        $base_url = base_url();
        $version_update_url = $base_url . "api/syncdevice";
        if($parray['app_id'] == '13692')
        {
            $form_submit_url = "http://175.107.16.188:8081/fbrltu/Api/saverecords";
        }
        else if($parray['app_id'] == '13696')
        {
            $form_submit_url = "http://175.107.16.188:8081/dev/Api/saverecords";
        }
        else if($parray['app_id'] == '13739')
        {
            $form_submit_url = "http://202.142.188.174/fbrltu/Api/saverecords";
        }
        else if($parray['app_id'] == '13747')
        {
            $form_submit_url = "http://58.65.205.180/fbrltu/Api/saverecords";
        }
        else{
            $form_submit_url = $base_url . "api/saverecords";
        }
        
        $form_update_url = $base_url . "api/updateforms";
        $save_tracking_url = $base_url . "tracking/savetracking";
        $save_tracking_bulk_url = $base_url . "tracking/savetrackingbulk";
        $data = simplexml_load_file($parray['path_string']);
        $data->string[0] = $app_name; //index 0 for app_name
        $data->string[1] = $parray['app_id']; //index 1 for app_id
        $data->string[2] = $version_update_url; //index 2 for 
        $data->string[3] = $form_submit_url; //index 3 for syncdevice
        $data->string[4] = $form_update_url; //index 4 for updateforms
        $data->string[5] = $save_tracking_url; //index 5 for trackingurl
        $data->string[6] = $save_tracking_bulk_url; //index 6 for trackingurlbulk
        $data->string[7] = $parray['IS_SECURE_APP']; //index 7 for is secure apk
        $data->string[8] = $parray['showHighResOption']; //index 8 for high resolution image setting
        $data->string[9] = $parray['PersistImagesOnDevice']; //index 9 for presist images on device setting
        $data->string[10] = $parray['BackgroundUpdate']; //index 10 for background update
        $data->string[11] = $parray['ForceUpdate']; //index 11 for force update
        $data->string[12] = $parray['EnableAutoTime']; //index 12 for enable auto time
        $data->string[13] =  $parray['TrackingStatus'];//index 13 for TrackingStatus
        $data->string[14] =  $parray['TrackingInterval'];//index 14 for TrackingInterval
        $data->string[15] =  $parray['TrackingDistance'];//index 15 for TrackingDistance
        $data->string[16] =  $parray['DebugTracking'];//index 16 for DebugTracking
        $data->string[17] =  $parray['hasGeoFencing'];//index 17 for hasGeoFencing
        $data->string[18] =  $parray['DebugGeoFencing'];//index 17 for hasGeoFencing
        $data->asXML($parray['path_string']);
    }

    /**
     * Function for changing the app name and package on manifest file
     * @param string $path Source path
     * @param string $package
     * @param string $version
     * @param integer $version_code
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    function change_manifest_file($path, $package, $version, $version_code) {

        $data = simplexml_load_file($path);
        $data['package'] = $package;
        $data['android:versionCode'] = $version_code;
        $data['android:versionName'] = $version;
        $data->asXML($path);
    }

    /**
     * Function for saving application setting
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appsettings($slug) {
        $app_id = $slug;
        $settings_exist = $this->app_model->get_app_settings($app_id);
        if ($settings_exist) {
            $data['latitude'] = $settings_exist['latitude'];
            $data['longitude'] = $settings_exist['longitude'];
            $data['zoom_level'] = $settings_exist['zoom_level'];
            $data['map_type'] = $settings_exist['map_type'];
            $data['district_filter'] = $settings_exist['district_filter'];
            $data['sent_by_filter'] = $settings_exist['sent_by_filter'];
            $data['uc_filter'] = $settings_exist['uc_filter'];
            $data['app_language'] = $settings_exist['app_language'];
            $data['map_type_filter'] = $settings_exist['map_type_filter'];
        } else {
            $data['latitude'] = '31.58219141239757';
            $data['longitude'] = '73.7677001953125';
            $data['zoom_level'] = '7';
            $data['map_type'] = 'Heat';
            $data['district_filter'] = 'Off';
            $data['sent_by_filter'] = 'Off';
            $data['uc_filter'] = 'Off';
            $data['app_language'] = 'english';
            $data['map_type_filter'] = 'Off';
        }
        $selected_app = $this->app_model->get_app($app_id);

        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $batch = array();
            $session_data = $this->session->userdata('logged_in');


            if (!$this->acl->hasPermission('form', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }


            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];

            if ($this->input->post()) {
                $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|xss_clean');
                $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|xss_clean');
                $this->form_validation->set_rules('zoom_level', 'Zoom Level', 'trim|required|xss_clean');

                if ($this->form_validation->run() == FALSE) {

                    $data['latitude'] = trim($this->input->post('latitude'));
                    $data['longitude'] = trim($this->input->post('longitude'));
                    $data['zoom_level'] = trim($this->input->post('zoom_level'));
                    $data['map_type'] = trim($this->input->post('map_type'));
                    $data['map_type_filter'] = trim($this->input->post('map_type_filter'));
                    $data['district_filter'] = trim($this->input->post('district_filter'));
                    $data['sent_by_filter'] = trim($this->input->post('sent_by_filter'));
                    $data['uc_filter'] = trim($this->input->post('uc_filter'));
                    $data['app_language'] = trim($this->input->post('app_language'));
                } else {

                    //if app setting exist then update, other wise insert

                    $latitude = trim($this->input->post('latitude'));
                    $longitude = trim($this->input->post('longitude'));
                    $zoom_level = trim($this->input->post('zoom_level'));
                    $map_type = trim($this->input->post('map_type'));
                    $map_type_filter = trim($this->input->post('map_type_filter'));
                    $district_filter = trim($this->input->post('district_filter'));
                    $sent_by_filter = trim($this->input->post('sent_by_filter'));
                    $uc_filter = trim($this->input->post('uc_filter'));
                    $app_language = trim($this->input->post('app_language'));
                    if ($settings_exist) {
                        $app_settings = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'zoom_level' => $zoom_level,
                            'map_type' => $map_type,
                            'map_type_filter' => $map_type_filter,
                            'district_filter' => $district_filter,
                            'sent_by_filter' => $sent_by_filter,
                            'uc_filter' => $uc_filter,
                            'app_language' => $app_language,
                        );

                        $this->db->where('app_id', $app_id);
                        $this->db->update('app_settings', $app_settings);
                        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                        $logary = array('action' => 'update', 'description' => 'Change application setting', 'after' => json_encode($app_settings), 'before' => json_encode($settings_exist), 'app_id' => $app_id, 'app_name' => $selected_app['name']);
                        addlog($logary);
                    } else {
                        $app_settings = array(
                            'app_id' => $app_id,
                            'latitude' => '31.58219141239757',
                            'longitude' => '73.7677001953125',
                            'zoom_level' => '7',
                            'map_type' => 'Pin',
                            'district_filter' => 'Off',
                            'sent_by_filter' => 'Off',
                            'uc_filter' => 'Off',
                            'app_language' => 'english',
                            'map_type_filter' => 'Off'
                        );
                        $this->db->insert('app_settings', $app_settings);
                        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                        $logary = array('action' => 'insert', 'description' => 'Add application settings', 'after' => json_encode($app_settings), 'app_id' => $app_id, 'app_name' => $selected_app['name']);
                        addlog($logary);
                    }

                    $this->session->set_flashdata('validate', array('message' => 'Setting saved successfully.', 'type' => 'success'));
                    redirect(base_url() . 'app-landing-page/' . $app_id);
                }
            }

            if ($this->acl->hasSuperAdmin()) {
                $data['app'] = $this->app_model->get_app_by_department_for_super();
            } else {
                $data['app'] = $this->app_model->get_app_by_user($data['login_user_id']);
            }
            $data['active_tab'] = 'app-settings';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_id'] = $app_id;
            $data['app_name'] = $selected_app['name'];

            $this->load->view('templates/header', $data);
            $this->load->view('app/app_settings', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    //function is for applicagtion settings list view, map view, graph view etc...

    public function newappsettings($slug,$iframe=''){
        ini_set ( 'memory_limit', '-1' );
        $app_id = $slug;
//        get app all users

        $all_users=$this->app_users_model->get_app_users_app_based($app_id);
        $filters_array=array();
        $first_form_columns='';
        $settings_exist = $this->app_model->get_app_settings_filters($app_id);
//        get form settings first get all forms...
        $app_forms = $this->form_model->get_form_by_app_for_app_settings($app_id);
        $final_forms=array();
        $possible_and_defaults=array();
        $possible_filters_array='';
        $required_fields='';
        $schema_list='';
        $i=1;
        $general_settings_filter='';
        $form_settings_filter='';
        $result_view_settings_filter='';
        $map_view_settings_filter='';
        $graph_view_settings_filter='';
        $sms_settings_filter='';
        $all_forms_columns=array();
        foreach($app_forms as $key=>$val){
//            get table columns name...
            $possible_filter_selected=$val['possible_filters'];
            $default_filter_selected=$val['filter'];
            $table_columns=$this->form_results_model->getTableHeadingsFromSchema("zform_".$val['id']);
            $all_columns=array();
            foreach($table_columns as $columns){
                $all_columns[]=$columns["Field"];
            }
            $all_forms_columns[$val['id']]=$all_columns;

            $exclude_array = array('id', 'form_id','location_source','activity_datetime','created_datetime');
            $possible_filters_array=array_diff($all_columns,$exclude_array);
            if($i==1) {
                $first_form_columns = $possible_filters_array;
            }
            $possible_and_defaults[$val['id']]['possible_filter_selected']=$possible_filter_selected;
            $possible_and_defaults[$val['id']]['default_filter_selected']=$default_filter_selected;
            $filters_array[$val['id']]=$possible_filters_array+array("sent_by");
            $final_forms[$val['name']]=$val;
            $i++;
        }

        if(!empty($settings_exist)){
            $settings_exist_new=array();
            foreach($settings_exist as $key=>$val){
                $settings_exist_new[$val['setting_type']]=$val;
            }

            $result1=array_key_exists("GENERAL_SETTINGS",$settings_exist_new);
            if($result1!=''){
                $general_settings_filter=$settings_exist_new['GENERAL_SETTINGS']['filters'];
            }
            $result2=array_key_exists("FORM_SETTINGS",$settings_exist_new);
            if($result2!=''){
                $form_settings_filter=$settings_exist_new['FORM_SETTINGS']['filters'];
            }
            $result3=array_key_exists("RESULT_VIEW_SETTINGS",$settings_exist_new);
            if($result3!=''){
                $result_view_settings_filter=$settings_exist_new['RESULT_VIEW_SETTINGS']['filters'];
            }
            $result4=array_key_exists("MAP_VIEW_SETTINGS",$settings_exist_new);
            if($result4!=''){
                $map_view_settings_filter=$settings_exist_new['MAP_VIEW_SETTINGS']['filters'];
            }
            $result5=array_key_exists("GRAPH_VIEW_SETTINGS",$settings_exist_new);
            if($result5!=''){
                $graph_view_settings_filter=$settings_exist_new['GRAPH_VIEW_SETTINGS']['filters'];
            }
            $result6=array_key_exists("SMS_SETTINGS",$settings_exist_new);
            if($result6!=''){
                $sms_settings_filter=$settings_exist_new['SMS_SETTINGS']['filters'];
            }

        }

        if(!empty($_POST)){
            $setting_type=$_POST['setting_type'];
            if($setting_type=="sms_settings"){
                $selected_app = $this->app_model->get_app($app_id);
                $application_name=$selected_app['name'];
                $users=$_POST['users'];
                $message=$_POST['message'];
                $settings_exist = $this->app_model->get_app_settings_filters($app_id, $setting_type);
                if (empty($settings_exist)) {
                    $action = "insert";
                } else {
                    $action = "update";
                }
                if(!isset($_POST['filters'])){
                    $_POST['filters']=array();
                }
                $json_string = json_encode($_POST);
                $app_settings = array(
                    'setting_type' => $setting_type,
                    'app_id' => $app_id,
                    'filters' => $json_string
                );
                if ($action == "update") {
                    $this->db->where('app_id', $app_id);
                    $this->db->where('setting_type', $setting_type);
                }
//                Send message
                foreach($users as $val){
                    $result=explode("_",$val);
                    $mobile_number=$result[0];
                    $name=$result[1];
                    $message_to_send="Dear $name ,\r \n Application : $application_name\r \n$message\r \n From: ".PLATFORM_NAME." Support Team";
                    if($mobile_number!=''){
                        //send message
                        send_sms($mobile_number,$message_to_send);
                    }
                }

                if ($this->db->$action('app_settings', $app_settings)) {
                    echo "success";
                }

            }elseif($setting_type=="form_settings"){
                $form_id=$_POST['form_id'];
                $post_url=$_POST['post_url'];
                $row_key=$_POST['row_key'];
//                $possible_filters=implode(",",$_POST['possible_filters']);
                $default_filter=$_POST['default_filter'];

                $form_values=array(
                    'post_url'=>$post_url,
//                    'possible_filters'=>$possible_filters,
                    'filter'=>$default_filter,
                    'row_key'=>$row_key,
                );
                $this->db->where('id', $form_id);
                $this->db->update('form', $form_values);
                echo "success";
            }elseif($setting_type=="form_column_settings"){
//                echo "<pre>";
//                print_r($_POST);die;
                $app_id=$_POST['app_id'];
                $form_id=$_POST['form_id'];
                $form_column_settings_exist = $this->app_model->get_form_column_settings($app_id);
                if(!empty($form_column_settings_exist)) {
                    $form_column_settings_exist_db = (array)json_decode($form_column_settings_exist['columns'],true);
                    if (array_key_exists($form_id, $form_column_settings_exist_db)) {
                        unset($form_column_settings_exist[$form_id]);
                    }else{
//                        $form_column_settings_exist=json_decode($form_column_settings_exist['columns'], true);
                    }
                }
//                echo "<pre>";
//                $columns=$form_column_settings_exist['columns'];
//                print_r(json_decode($columns,true));die;
                if (empty($form_column_settings_exist)) {
                    $action = "insert";
                }else{
                    $action = "update";
                }

                if ($action == "update") {
                    $this->db->where('app_id', $app_id);
                }
                $final_arr=array_merge($_POST['columns'],$_POST['order'],$_POST['visible']);

                $form_column_settings_exist_db[$form_id] = array(
                    'columns' => $_POST['columns'],
                    'order' => $_POST['order'],
                    'visible' => $_POST['visible']
                );

                $json_string=json_encode($form_column_settings_exist_db);
                $columns=array(
                    'app_id'=>$app_id,
                    'columns'=>$json_string

                );

                if ($this->db->$action('form_column_settings', $columns)) {
                    echo "success";
                }

            }else{
                $form_id=(isset($_POST['form'])) ? $_POST['form']:'';
                $filters=(isset($_POST['filters'])) ? $_POST['filters']:'';
                unset($_POST['filters']);
                if(!isset($_POST['filters'])){
                    $_POST['filters']=array();
                }

                $settings_exist = $this->app_model->get_app_settings_filters($app_id, $setting_type);
                if (empty($settings_exist)) {
                    $action = "insert";
                    $_POST['filters'][$form_id]=$filters;
                } else {
                    $action = "update";
                    $exist_filters=json_decode($settings_exist[0]['filters'],true);
                    $exist_form_filters=$exist_filters['filters'];
                        if (array_key_exists($form_id,$exist_form_filters)) {
                            $exist_form_filters[$form_id] = $filters;
                        }else{
                            $exist_form_filters[$form_id] = $filters;
                        }

                    $_POST['filters']=$exist_form_filters;
                }

                $json_string = json_encode($_POST);
                $app_settings = array(
                    'setting_type' => $setting_type,
                    'app_id' => $app_id,
                    'filters' => $json_string
                );
                if ($action == "update") {
                    $this->db->where('app_id', $app_id);
                    $this->db->where('setting_type', $setting_type);
                }

                if ($this->db->$action('app_settings', $app_settings)) {
                    echo "success";
                }
            }
        }else {

            //get froms of this app...
            $all_forms=$this->form_model->get_form_by_app($app_id);
            //first form...
            foreach($all_forms as $key=>$val){
                $form_id=$val['form_id'];
                $fields=$this->form_results_model->getTableHeadingsFromSchema("zform_".$form_id);
                $exclude_array = array('id', 'form_id', 'is_deleted', 'location_source', 'created_datetime');
                $filterd_fileds=array();
                $required_fields=array();
                $table_name="zform_$form_id";
                foreach($fields as $key1=>$list){
                    if(!in_array($list['Field'],$exclude_array)){
                        $column=$list['Field'];
                        //get values of this field...
//                        $column_result=$this->db->query("SELECT $column from  $table_name")->result_array();
                        $col_result_arr=array();
                        $filterd_fileds[$list['Field']]=$col_result_arr;
                    }
                }
                $required_fields[$form_id]=$filterd_fileds;

                $table_result = $this->form_results_model->getTableHeadingsFromSchema($table_name);
                $schema_columns=array();
                foreach($table_result as $key=>$val){
                    if(!in_array($val['COLUMN_NAME'],$exclude_array)) {
                        $schema_columns[$val['COLUMN_NAME']] = $val['COLUMN_NAME'];
                    }
                }
                $schema_list[$form_id]=$schema_columns;
            }

            $selected_app = $this->app_model->get_app($app_id);
            //get column settings for this app...
            $column_settings=$this->form_model->get_column_settings($app_id);

            if(!empty($column_settings)){
                $column_settings=json_decode($column_settings['columns'],true);
            }
//            echo "<pre>";
//            print_r($column_settings);die;
            $this->load->library('form_validation');
            if ($this->session->userdata('logged_in')) {
                $batch = array();
                $session_data = $this->session->userdata('logged_in');
                if (!$this->acl->hasPermission('form', 'view')) {
                    $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                    redirect(base_url() . 'apps');
                }
                $app_r = array(''=>'Accept All Versions');
                $app_released = $this->app_released_model->get_app_released($app_id);
                foreach ($app_released as $ap_key => $ap_value) {
                    $app_r[$ap_value['version']]=$ap_value['version'];
                   
                }

                session_to_page($session_data, $data);
                $department_id = $session_data['login_department_id'];
                $data['app_r'] = $app_r;
                $data['app_name'] = $selected_app['name'];
                $data['active_tab'] = 'app-settings';
                $data['pageTitle'] = "App Settings-" . PLATFORM_NAME;
                $data['app_id'] = $app_id;
                $data['general_settings_filter'] = $general_settings_filter;
                $data['form_settings_filter'] = $form_settings_filter;
                $data['result_view_settings_filter'] = $result_view_settings_filter;
                $data['map_view_settings_filter'] = $map_view_settings_filter;
                $data['graph_view_settings_filter'] = $graph_view_settings_filter;
                $data['sms_settings_filter'] = $sms_settings_filter;
                $data['final_forms'] = $final_forms;
                $data['filters_array'] = $filters_array;
                $data['possible_and_defaults'] = $possible_and_defaults;
                $data['possible_filters_array'] = $possible_filters_array;
                $data['first_form_columns'] = $first_form_columns;
                $data['all_users'] = $all_users;
                $data['required_fields'] = $required_fields;
                $data['schema_list'] = $schema_list;
                $data['all_forms_columns'] = $all_forms_columns;
                $data['column_settings'] = $column_settings;
                if($iframe!=''){
                    $data['selected_tab']=$iframe;
                }
//                echo "<pre>";
//                print_r($_REQUEST);die;
                if($iframe=='') {
                    $this->load->view('templates/header', $data);
                }
                $this->load->view('app/new_app_settings', $data);
                $this->load->view('templates/footer');
            } else {
                //If no session, redirect to login page
                redirect(base_url());
            }
        }
    }




    public function newformsettings($slug,$iframe=''){
        ini_set ( 'memory_limit', '-1' );
        $app_id = $slug;
//        get app all users

        $all_users=$this->app_users_model->get_app_users_app_based($app_id);
        $filters_array=array();
        $first_form_columns='';
        $settings_exist = $this->app_model->get_app_settings_filters($app_id);
//        get form settings first get all forms...
        $app_forms = $this->form_model->get_form_by_app_for_app_settings($app_id);
        $final_forms=array();
        $possible_and_defaults=array();
        $possible_filters_array='';
        $required_fields='';
        $schema_list='';
        $i=1;
        $general_settings_filter='';
        $form_settings_filter='';
        $result_view_settings_filter='';
        $map_view_settings_filter='';
        $graph_view_settings_filter='';
        $sms_settings_filter='';
        $all_forms_columns=array();
        foreach($app_forms as $key=>$val){
//            get table columns name...
            $possible_filter_selected=$val['possible_filters'];
            $default_filter_selected=$val['filter'];
            $table_columns=$this->form_results_model->getTableHeadingsFromSchema("zform_".$val['id']);
            $all_columns=array();
            foreach($table_columns as $columns){
                $all_columns[]=$columns["Field"];
            }
            $all_forms_columns[$val['id']]=$all_columns;

            $exclude_array = array('id', 'form_id','location_source','activity_datetime','created_datetime');
            $possible_filters_array=array_diff($all_columns,$exclude_array);
            if($i==1) {
                $first_form_columns = $possible_filters_array;
            }
            $possible_and_defaults[$val['id']]['possible_filter_selected']=$possible_filter_selected;
            $possible_and_defaults[$val['id']]['default_filter_selected']=$default_filter_selected;
            $filters_array[$val['id']]=$possible_filters_array+array("sent_by");
            $final_forms[$val['name']]=$val;
            $i++;
        }

        if(!empty($settings_exist)){
            $settings_exist_new=array();
            foreach($settings_exist as $key=>$val){
                $settings_exist_new[$val['setting_type']]=$val;
            }

            $result1=array_key_exists("GENERAL_SETTINGS",$settings_exist_new);
            if($result1!=''){
                $general_settings_filter=$settings_exist_new['GENERAL_SETTINGS']['filters'];
            }
            $result2=array_key_exists("FORM_SETTINGS",$settings_exist_new);
            if($result2!=''){
                $form_settings_filter=$settings_exist_new['FORM_SETTINGS']['filters'];
            }
            $result3=array_key_exists("RESULT_VIEW_SETTINGS",$settings_exist_new);
            if($result3!=''){
                $result_view_settings_filter=$settings_exist_new['RESULT_VIEW_SETTINGS']['filters'];
            }
            $result4=array_key_exists("MAP_VIEW_SETTINGS",$settings_exist_new);
            if($result4!=''){
                $map_view_settings_filter=$settings_exist_new['MAP_VIEW_SETTINGS']['filters'];
            }
            $result5=array_key_exists("GRAPH_VIEW_SETTINGS",$settings_exist_new);
            if($result5!=''){
                $graph_view_settings_filter=$settings_exist_new['GRAPH_VIEW_SETTINGS']['filters'];
            }
            $result6=array_key_exists("SMS_SETTINGS",$settings_exist_new);
            if($result6!=''){
                $sms_settings_filter=$settings_exist_new['SMS_SETTINGS']['filters'];
            }

        }

       
        
        


            //get froms of this app...
            $all_forms=$this->form_model->get_form_by_app($app_id);
            //first form...
            foreach($all_forms as $key=>$val){
                $form_id=$val['form_id'];
                $fields=$this->form_results_model->getTableHeadingsFromSchema("zform_".$form_id);
                $exclude_array = array('id', 'form_id', 'is_deleted', 'location_source', 'created_datetime');
                $filterd_fileds=array();
                $required_fields=array();
                $table_name="zform_$form_id";
                foreach($fields as $key1=>$list){
                    if(!in_array($list['Field'],$exclude_array)){
                        $column=$list['Field'];
                        //get values of this field...
                        $col_result_arr=array();
                        $filterd_fileds[$list['Field']]=$col_result_arr;
                    }
                }
                $required_fields[$form_id]=$filterd_fileds;

                $table_result = $this->form_results_model->getTableHeadingsFromSchema($table_name);
                $schema_columns=array();
                foreach($table_result as $key=>$val){
                    if(!in_array($val['COLUMN_NAME'],$exclude_array)) {
                        $schema_columns[$val['COLUMN_NAME']] = $val['COLUMN_NAME'];
                    }
                }
                $schema_list[$form_id]=$schema_columns;
            }

            $selected_app = $this->app_model->get_app($app_id);
            //get column settings for this app...
            $column_settings=$this->form_model->get_column_settings($app_id);

            if(!empty($column_settings)){
                $column_settings=json_decode($column_settings['columns'],true);
            }
            $this->load->library('form_validation');
            if ($this->session->userdata('logged_in')) {
                $batch = array();
                $session_data = $this->session->userdata('logged_in');
                if (!$this->acl->hasPermission('form', 'view')) {
                    $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                    redirect(base_url() . 'apps');
                }


                session_to_page($session_data, $data);
                $department_id = $session_data['login_department_id'];
                $data['app_name'] = $selected_app['name'];
                $data['active_tab'] = 'app-settings';
                $data['pageTitle'] = "App Settings-" . PLATFORM_NAME;
                $data['app_id'] = $app_id;
                $data['general_settings_filter'] = $general_settings_filter;
                $data['form_settings_filter'] = $form_settings_filter;
                $data['result_view_settings_filter'] = $result_view_settings_filter;
                $data['map_view_settings_filter'] = $map_view_settings_filter;
                $data['graph_view_settings_filter'] = $graph_view_settings_filter;
                $data['sms_settings_filter'] = $sms_settings_filter;
                $data['final_forms'] = $final_forms;
                $data['filters_array'] = $filters_array;
                $data['possible_and_defaults'] = $possible_and_defaults;
                $data['possible_filters_array'] = $possible_filters_array;
                $data['first_form_columns'] = $first_form_columns;
                $data['all_users'] = $all_users;
                $data['required_fields'] = $required_fields;
                $data['schema_list'] = $schema_list;
                $data['all_forms_columns'] = $all_forms_columns;
                $data['column_settings'] = $column_settings;
                
                $this->load->view('app/new_form_setting_ajax', $data);
                
            } 
            //$this->load->view('app/new_form_setting_ajax', $data);

    }

    //get categories for reporting filters...
    public function get_field_values(){
        $form_id=$_POST['form_id'];
        $column=$_POST['column'];
        $value=$_POST['value'];
        $table_name="zform_".$form_id;
        $result=$this->db->query("select distinct $column from $table_name where $column LIKE '%$value%'")->result_array();
        $i=0;
        $options=array();
        foreach($result as $key=>$val){
            $field_value = str_replace(" ", "_", $val[$column]);
            $options[$i]['value']=$field_value;
            $options[$i]['label']=$val[$column];
            $i++;
        }
        if(empty($options)){
            $options[0]['value']="";
            $options[0]['label']="No Result Found";

        }
        echo  json_encode($options);
    }

    public function get_map_pins(){
        echo $field="
                <div class='row'>
                    <label for='d1_textfield'>Assign pin to value</label>
                    <div>
                        <input typpe='text' name='fieldvlaue' class='myfield textBoxLogin' id='myfield'>
                    </div>
                </div>

                    ";

    }

    public function get_name_pin(){
        if($_POST['value']==""){

        }else {
            $value = str_replace("_", " ", $_POST['value']);
            $column = $_POST['column'];
            $form_id = $_POST['form_id'];
            $field_value = str_replace(" ", "_", $_POST['value']);
            $pins = '';
            $pins .= '<div class="row"><label for="d1_textfield">' . $value . '</label><div><select name="pins[]" id="pins" class="icon-menu">
            <option value="">Select One</option>';
            $site_settings = $this->site_model->get_settings('1');
            $directory = $site_settings['directory_path'] . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "map_pins" . DIRECTORY_SEPARATOR;
            $images = glob($directory . "*.png");

            foreach ($images as $key => $val) {
                $image_result = explode(DIRECTORY_SEPARATOR, $val);
                $image_name = end($image_result);

                $image_url = base_url() . "assets/images/map_pins/$image_name";
                $field_value = str_replace(" ", "_", $field_value);
                $selected = '';
                $saved_pins = $this->get_saved_pins($form_id);

                if (!empty($saved_pins) && isset($saved_pins[$column][$field_value])) {
                    if ($image_name == $saved_pins[$column][$field_value]) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                }

                $pins .= "<option data-imagesrc=\"$image_url\" value=\"$image_name\" $selected></option>";
            }

            $pins .= "</select>
            <input type='hidden' name='column' value='$column'>
            <input type='hidden' name='form_id' value='$form_id'>
            <input type='hidden' name='field_value' value='$field_value'>
            </div>
            </div>";
            echo $pins;
        }
    }

    public function get_saved_pins($form_id){
        $result=$this->db->query("select pins from map_pin_settings where form_id='$form_id'")->result_array();
        if(!empty($result)) {
            return json_decode($result[0]['pins'], true);
        }
    }

    public function get_saved_pins_html(){
        $form_id=$_POST['form_id'];
        $result=$this->db->query("select pins from map_pin_settings where form_id='$form_id'")->result_array();
        if(!empty($result)) {
            $pins_data=json_decode($result[0]['pins'], true);
            $table="<div class='row'><table>";
            foreach($pins_data as $key=>$val){
                $key_new=strtoupper(str_replace("_"," ",$key));
                $table.="<tr><th colspan='2'>$key_new</th></tr>";
                foreach($val as $field=>$img){
                    $field=str_replace("_"," ",$field);
                    $image_url=base_url()."assets/images/map_pins/$img";
                    $table.="
                        <tr style='padding: 5px;'>
                            <td>$field</td>
                            <td>
                                <img onclick=\"change_filter('$key','$field')\" src='$image_url' title='Click icon to edit' style='cursor:pointer'>
                                </td>
                        </tr>
                        ";
                }

            }
            $table.="</table></div>";
            echo $table;
        }
    }

    public function save_pin_settings(){
        if(!empty($_POST)) {
            $field_name = $_POST['column'];
            $form_id = $_POST['form_id'];
            $field_value = $_POST['field_value'];
            $pin = $_POST['pins'];
            $pins_arr = array();

            $pins_arr[$field_name][$field_value] = $pin[0];

            $json_pins=json_encode($pins_arr);
            $form_result=$this->db->query("select * from map_pin_settings where form_id='$form_id'")->result_array();
            if(count($form_result)==0) {
                if($this->db->query("insert into map_pin_settings (`form_id`,`pins`) values('$form_id','$json_pins')")){
                    echo "success";
                }
            }else{
                //update scenario
                $result=json_decode($form_result[0]['pins'],true);
                if(array_key_exists($field_name,$result)){
                    $saved_pin_arr=$result[$field_name];
                    if(array_key_exists($field_value,$saved_pin_arr)){
                        $saved_pin_arr[$field_value]=$pin[0];
                    }else{
                        $saved_pin_arr[$field_value]=$pin[0];
                    }
                    $result[$field_name]=$saved_pin_arr;
                    $final_result=$result;

                }else{
                    $result[$field_name][$field_value]=$pin[0];
                    $final_result=$result;
                }

                $json_pins=json_encode($final_result);
                if($this->db->query("update map_pin_settings set pins='$json_pins' where form_id='$form_id'")){
                    echo "success";
                }

            }

        }
    }

    public function acl_settings(){
        if ($this->session->userdata('logged_in')) {
            $batch = array();
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            //get user groups data ...
            $this->db->select('*');
            $this->db->from('users_group');
            $query = $this->db->get();
            $user_groups=$query->result_array();
            //get users data...
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('is_deleted', 0);
            $query = $this->db->get();
            $user_data=$query->result_array();
            //get controllers and their methods ...
            $controller_n_methods=$this->get_controllers_and_methods();

            $data['users_group']=$user_groups;
            $data['users_data']=$user_data;
            $data['controller_n_methods']=$controller_n_methods;
            $data['active_tab'] = 'app-settings';
            $data['pageTitle'] = "App Settings-" . PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('app/acl_settings', $data);
            $this->load->view('templates/footer');
        }
    }

    public function get_form_columns(){
        $form_id=$_POST['form_id'];
        $form_id_arr=array(array("form_id"=>$form_id));
        $filters_result=json_decode($_POST['filters'],true);
        if(isset($filters_result[$form_id]) && $filters_result[$form_id]!="") {
            $filters = $filters_result[$form_id];
        }else{
            $filters=array();
        }


        $form_view_saved_form_id=$_POST['form_view_saved_form_id'];
//        $table_columns=$this->form_model->get_form_filters($form_id_arr);
        $table_columns=$this->form_results_model->getTableHeadingsFromSchema("zform_".$form_id);

//        $table_filters=$table_columns[0]['possible_filters'];
//        $table_filters=explode(",",$table_filters);

        $all_columns=array();
//        foreach($table_filters as $key=>$columns){
        foreach($table_columns as $key=>$columns){
//            $all_columns[]=$columns;
            $all_columns[]=$columns['COLUMN_NAME'];
        }
        $all_columns[]='sent_by';

        $exclude_array = array('id', 'form_id','location_source', 'activity_datetime','created_datetime','is_deleted');
        $possible_filters_array=array_diff($all_columns,$exclude_array);
        $option_string='';
        $final_array=array();
        $selected_options=array();

        foreach($possible_filters_array as $key=>$val) {
                if (in_array($val, $filters)) {
                    $selected_options[]=$val;
                }
                $final_array[$val] = $val;
        }

        header('Content-Type: application/x-json; charset=utf-8');
        $final_json = array('category' => $final_array, 'selected_options' => $selected_options);
        echo json_encode($final_json);
    }

    /**
     * Function for saving application comments
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function appcomments($slug) {
        $app_id = $slug;
        $data['app_comments'] = $this->form_model->get_comments($app_id);
        $selected_app = $this->app_model->get_app($app_id);
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_id'] = $app_id;
            $data['app_name'] = $selected_app['name'];
            $this->load->view('templates/header', $data);
            $this->load->view('app/app_comments', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    function get_full_description($description) {
        $full_description = '<!DOCTYPE html>
                            <html lang="en">
                            <head>
                            <meta charset="utf-8">
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <title>:: '.PLATFORM_NAME.'</title>

                            <link href="bootstrap.min.css" rel="stylesheet">
                            <link href="common.css" rel="stylesheet">
                            <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                            <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
                            <!--[if lt IE 9]>
                                  <script src="html5shiv.min.js"></script>
                                  <script src="respond.min.js"></script>
                                <![endif]-->
                            <script src="jquery.min.js"></script>
                            <script src="bootstrap.min.js"></script>
                            <script src="bootstrap.js"></script>
                            <script src="jquery-2.0.2.min.js"></script>
                            <script src="jquery.js"></script>
                            <script src="jquery-ui-autocomplete.js"></script>
                            <script src="jquery.select-to-autocomplete.min.js"></script>
                            <script src="common.js"></script>
                            <style>
                            .field {
                                float: left;
                              text-align:center;
                                width: 33%;
                              }
                              </style>
                            </head>
                            <body>
                            <div id="form-builder"  class="container">' . $description . '</div>
                                    </body>
                                    </html>';
        return $full_description;
    }

    public function getapps($department_id) {
        $this->load->model('app_model');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->app_model->get_apps($department_id)));
    }

    public function getappviews($app_id) {
        $this->load->model('app_users_model');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->app_users_model->get_app_views($app_id)));
    }

    public function redirectFlashOnly() {
        $this->session->set_flashdata('validate', array('message' => 'No Application Exists', 'type' => 'error'));
        redirect(base_url() . 'apps');
    }

    public function buildSleep($app_id) {
        $query = $this->db->get('app_build_request');
        $query->result_array();
        $total_rec = $query->num_rows();
        $max_request = 5;
        $execution_second = 60;
        $j = 0;
        for ($i = $max_request; $i < $total_rec; $i = $i + $max_request) {
            $j++;
        }
        if ($j != 0) {
            $sleap_time = $j * $execution_second;
            sleep($sleap_time);
        }
    }

    public function calculatetimebuild() {
        $query = $this->db->get('app_build_request');
        $query->result_array();
        $total_rec = $query->num_rows();
        $max_request = 5;
        $sleap_time = $execution_second = 60;
        $j = 0;
        for ($i = $max_request; $i < $total_rec; $i = $i + $max_request) {
            $j++;
        }
        if ($j != 0) {
            $sleap_time = $j * $execution_second;
        }
        echo $sleap_time;
        exit();
    }

    /**
     *
     * @param type $slug
     * Delete app users
     * @author:ubaidullah.balti
     */
    public function appusersviewdelete($slug) {
        if ($this->session->userdata('logged_in')) {
            $app_view_id = $slug;
            if (!$this->acl->hasPermission('app', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $this->db->delete('app_users_view', array('id' => $app_view_id));
            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary = array('action' => 'delete', 'description' => 'Delete user view', 'after' => 'user_view=' . $app_view_id);
            addlog($logary);
            $this->session->set_flashdata('validate', array('message' => 'Application deleted successfully.', 'type' => 'success'));
            redirect(base_url() . 'app/appusersview');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    /**
     *
     * @param type $slug
     * Delete app users
     * @author:ubaidullah.balti
     */
    public function downloadapk() {
        $fullPath = urldecode($_REQUEST['app_file']);
        if ($fullPath) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);

            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename=' . $path_parts["basename"]);
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Description: File Transfer");
            //header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . $fsize);

        }
    }

    /**
     * Function for checking configuration of building android application
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function testapk() {
        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasSuperAdmin()) {
                echo "Please login with Super Admin to access this URL";
                exit;
            }
            print "<pre>";
            ignore_user_abort(true);
            set_time_limit(0);

            $site_settings = $this->site_model->get_settings('1');
            $directory_path = $site_settings['directory_path'];
            $target = '18'; //$site_settings['android_target'];
            $platform = 'testapkchecking';
            $appname = 'testapp';
            //Change directory
            $path = $directory_path . '/assets/android/godk_android_test';
            chdir($path);
            echo $cmd = "pushd $path";
            print "<br />";
            exec($cmd, $output, $co);
            if ($output) {
                print_r($output);
            }
            print "<br />";

            //update build.xml file for new app//For windows
            echo $envpath = 'C:\ant\bin\ant;C:\Program Files\Java\jdk1.7.0_51\bin\;C:\adt\sdk\tools';
            print "<br />";
            exec($envpath, $output1, $co);
            if ($output1) {
                print_r($output1);
            }
            print "<br />";
            //For Linux 1 for local and 4 for live
            echo $command = "android update project --target $target --name $appname --path $path";
            print "<br />";
            exec($command, $output2, $co);
            if ($output2) {
                print_r($output2);
            }
            print "<br />";
            //Build new apk file
            //$command = "ant debug";
            echo $command = "ant release";
            print "<br />";
            exec($command, $output3, $co);
            if ($output3) {
                print_r($output3);
            }
            print "<br />";
            //print "<pre>";
            //print_r($output2);
            //exit;
            //copy the apk file to app repository
            echo 'Copping APK file after building.....';
            print "<br />";
            copy("$directory_path/assets/android/godk_android_test/bin/$appname-release-unsigned.apk", "$directory_path/assets/android/apps/$appname-release-unsigned.apk");
            echo 'Renaming APK file after building.....';
            print "<br />";
            rename("$directory_path/assets/android/apps/$appname-release-unsigned.apk", "$directory_path/assets/android/apps/unaligned_testapp.apk");
            echo 'Deleting APK file from bin.....';
            print "<br />";
            unlink("$directory_path/assets/android/godk_android_test/bin/$appname-release-unsigned.apk");

            //$keystore_command = "keytool -genkey -v -keystore $directory_path/assets/android/keystore/a_$app_id.keystore -alias DataPlug -keyalg RSA -keysize 2048 -validity 10000";
            echo $signing_command = "jarsigner -verbose -keystore $directory_path/assets/android/keystore/DataPlug.keystore -storepass dataplug_pitb -keypass dataplug_pitb $directory_path/assets/android/apps/unaligned_testapp.apk DataPlug";
            print "<br />";
            exec($signing_command, $output4, $co);
            if ($output4) {
                print_r($output4);
            }
            print "<br />";
            //$unalignedFileName = $appname .'-'. $app_id . '-' . $version . 'v-unaligned.apk';
            //$signing_command = "jarsigner -verbose -keystore $directory_path/assets/android/keystore/DataPlug.keystore -storepass dataplug_pitb -keypass dataplug_pitb $directory_path/assets/android/apps/$newFileName DataPlug";
            echo $zipaligned_command = "zipalign -v 4 $directory_path/assets/android/apps/unaligned_testapp.apk $directory_path/assets/android/apps/testapp.apk";
            print "<br />";
            exec($zipaligned_command, $output5, $co);
            if ($output5) {
                print_r($output5);
            }
            print "<br />";
            unlink("$directory_path/assets/android
			/godk_android_test/bin/unaligned_testapp.apk");
            //array parameters : action, description, 
			//before, after, app_id, app_name, form_id, form_name
            $logary = array('action' => 'build', 
			'description' => 'Build test APK', 'after' => json_encode('Build apk'));
            addlog($logary);
            exit;
            //redirect(base_url() . 'app/testapk');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    public function create_action_buttons($app)
    {
        $appId=$app['id'];
        $result='';
        if ($this->acl->hasPermission('form', 'edit')) {
            $result .= "<a style='padding:2px;' href='
			".base_url()."application-setting/".$appId." '><img src='".base_url()."
			assets/images/settings-ico.png' alt='Settings' title=''Settings'/></a>";
        }

        if ($this->acl->hasPermission('form', 'edit')) {
            $result .= "<a style='padding:2px;' href='
			".base_url()."app-landing-page/".$appId." '><img src='
			".base_url()."assets/images/tableLink1.png' alt='edit' title=''Edit'/></a>";
        }

        if ($this->acl->hasPermission('app', 'delete')) {
            $result .= "<a style='padding:2px;' href='javascript:void(0)'><img src='
			".base_url()."assets/images/tableLink3.png' alt='delete' id ='
			delete_app' title='Delete' app_id ='".$appId."' /></a>";
        }

        if (isset($app['app_file']) && $app['app_file'] !='') {
            $result .= "<a style='padding:2px;' href='
			".base_url()."app/releasedapk/".$appId."'>
            <img style='' src='
			".base_url()."assets/images/version.png' alt='' title='Version History' />
            </a>

            <a style='padding:2px;' href='
			".base_url()."assets/android/apps/".$app['app_file']."'>
            <img src='".base_url()."assets/images/tableLink6.png' alt='' title='Download'/>
            </a>
            ";
        }



        return $result;
    }

    //creating app users action buttons for datatable...
    public function create_user_apps_action_buttons($app)
    {
        $result='';
        if ($this->acl->hasPermission('app_users', 'edit')) {
            $result .= "<a style='padding:2px;' href='
			".base_url()."app/editAppUser/".$app['user_id']." '>
            <img src='".base_url()."assets/images/tableLink1.png' alt='edit' title='Edit'/>
            </a>";
        }

        // if ($this->acl->hasPermission('app_users', 'delete')) {
        //     $result .= "<a style='padding:2px;' href='javascript:void(0)'>
        //     <img src='".base_url()."assets/images/tableLink3.png' alt='edit' id='delete_user' user_id='".$app['user_id']."' title='Delete User'/>
        //     </a>";
        // }
        return $result;
    }

    //create application icon for datatable view...
    public function create_icon_image($app,$result_count){
        if ($result_count > 0) {
        $result='
            <a style="padding-left:0px;" href="'.base_url().'application-results/'.$app['id'].'">
                <img class="formIconsUpload" src="'.FORM_IMG_DISPLAY_PATH.'../form_icons/'.$app['id'].'/'.$app['icon'].'" alt="" />
            </a>';

        } else {
            $result='
            <a style="padding-left:0px; cursor: default" href="javascript:void(0)">
                <img class="formIconsUpload" src="'.FORM_IMG_DISPLAY_PATH.'../form_icons/'.$app['id'].'/'.$app['icon'].'" alt="" />
            </a>';
        }
        return $result;
    }

    //create application qr code for datatable view...
    public function create_qr_code_image($app){
        $result = '';
        if($app['qr_code_file']!=''){
        	$filename = './assets/android/qr_code/'.$app['qr_code_file'];
        	
        	if(file_exists($filename)){
        		$result='
                <a style="padding-left:0px;" rel="lightbox" href="'.FORM_IMG_DISPLAY_PATH.'../../../android/qr_code/'.$app['qr_code_file'].'">
                    <img class="formIconsUpload" src="'.FORM_IMG_DISPLAY_PATH.'../../../android/qr_code/'.$app['qr_code_file'].'" alt="" />
                </a>';
        	}
        }
        return $result;
    }

    public function create_app_name($app,$result_count,$total_records=0){
    	if($app['type']=='external' && $app['module_name']!=''){
    		$app_name = preg_replace('/[^A-Za-z0-9]/', '-', $app['name']);
    		$slug = $app_name . '-' . $app['id'];
    		$module=$app['module_name'];
    		return $result='<a style="padding-left:0px;" href="'.base_url().$module.'"><b>'.htmlspecialchars($app_name).'</b></a>';
    	}else
        if ($result_count > 0) {
            $app_name = preg_replace('/[^A-Za-z0-9]/', '-', $app['name']);
            $slug = htmlspecialchars($app_name . '-' . $app['id']);
            return $result='<a style="padding-left:0px;" href="'.base_url().'application-results/'.$slug.'"><b style="color:brown">'.$app_name.'</b></a>'."($result_count) ";

        } else {
            return htmlspecialchars($app['name']);
            $app_name = preg_replace('/[^A-Za-z0-9]/', '-', $app['name']);
            $slug = $app_name . '-' . $app['id'];
        }
    }

    public function get_departments(){
        $query=$this->db->query("select DISTINCT name from department where is_deleted=0");
        $result=$query->result_array();
        $string='';
        foreach($result as $key=>$val) {
            $string.=$val['name'].',';
        }
        $final_string=rtrim($string,',');
        echo $final_string;
    }

    function get_controllers_and_methods() {
        $this->load->library('allcontrollerlist'); // Load the library
        return $this->allcontrollerlist->getControllers();
    }

    function get_methods_options() {
        $controller=$_POST['controller'];
        $this->load->library('allcontrollerlist'); // Load the library
        $list=$this->allcontrollerlist->getControllers();
        $methods=$list[$controller];
        echo "<pre>";
        print_r($methods);
    }

    function search_dynamic_filters(){
        $search=$_GET['name'];
        $search_word=trim($_GET['search']);
        $search_arr=explode("-",$search);
        $column=$search_arr[0];
        $form_id=$search_arr[1];
        $table="zform_$form_id";
        $result=$this->form_results_model->get_dynamic_results($column,$table,$search_word);

        $name_array=array();
        if($column=='location') {
            foreach ($result as $key => $val) {
                $name_array[] = array("value" => str_replace(
				" ", " ", $val[$column]), 'text' => $val[$column]);

            }
        }else{
            foreach ($result as $key => $val) {
                if(strrpos($val[$column],",")!=false) {
                    $val_li = explode(',', $val[$column]);
                    foreach ($val_li as $va) {
//                        $found = $this->in_array_r("$va", $name_array);
//                        if($found==0) {
                            $name_array[] = array("value" => str_replace(" ", " ", $va), 'text' => $va);
//                        }
                    }
                }else {
                    if($column=="sent_by"){
                        $name_array[] = array("value" => str_replace(
						" ", " ", $val['imei_no']), 'text' => $val[$column]);
                    }else {
                        $name_array[] = array("value" => str_replace(
						" ", " ", $val[$column]), 'text' => $val[$column]);
                    }
                }

            }
        }
        $name_array=array_unique($name_array,SORT_REGULAR);
        echo json_encode($name_array);

    }

    function in_array_r($item , $array){
        return preg_match('/"'.$item.'"/i' , json_encode($array));
    }

}
