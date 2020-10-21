<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Web extends CI_Controller {

    private $perPage = 25;
    private $perMap = 5000;

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('app_users_model');
        $this->load->model('app_installed_model');
        $this->load->model('form_model');
        $this->load->model('form_results_model');
        $this->load->model('app_released_model');

        $this->load->helper(array('form', 'url'));
        $this->load->helper('text');
        $this->load->helper('custome_helper');
        $this->load->library('Ajax_pagination');
        $this->load->library('parser');
        $this->load->library('image_lib');
        // if (!$this->acl->hasSuperAdmin()) {
        //     if($this->acl->hasPermission('complaint','Access only complaint module')){
        //         redirect(base_url() . 'complaintSystem');
        //     }
        // }
    }

    /**
     * Index Page for Form controller.
     * 
     * @param  $slug application id
     * @return void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function index($slug) {
        if (!$this->session->userdata('web_logged_in')) {
            redirect(base_url() . 'web/login/' . $slug);
        }
        $session_data = $this->session->userdata('web_logged_in');
       
        $result = explode("_", $slug);
        $app_id = $result[0];
        if($app_id != $session_data['login_app_id'])
        {
            echo 'This user has no access to get this url';
            exit;
        }
        $app_general_setting = get_app_general_settings($app_id);
        if ($app_general_setting->form_submission_web_link == 0) {
            echo 'This web link is not active yet. Please contact with Administrator';
            exit;
        }
        $form_id = 0;
        if (isset($result[1])) {
            $form_id = $result[1];
        }
        $app = $this->app_model->get_app($app_id);
        $data['app_name'] = $app['name'];
        $data['app_general_setting'] = $app_general_setting = get_app_general_settings($app_id);
        $data['app_id'] = $app_id;
        $data['form_id'] = $form_id;
        $data['full_description']=$app['full_description'];

        $forms = $this->form_model->get_form_by_app($app_id);
        if ($form_id != 0) {
            $selected_form = $this->form_model->get_form($form_id);
            $data['full_description'] = $selected_form['description'];
        } else if (count($forms) == 1) {
            $data['full_description'] = $forms[0]['description'];
            $data['form_id'] = $forms[0]['form_id'];
        } else if (count($forms) > 1) {
            if ($app['description'] == '') {
                $data['full_description'] = 'Landing page is empty, Please contact with Administrator for creating landing page for you';
            } else {
                $data['full_description'] = $app['description'];
            }
        }

        $data['active_tab'] = 'app';
        $data['slug'] = $slug;
        $this->load->view('web/index', $data);
    }
    
    /**
     * Index Page for Form controller.
     * 
     * @param  $slug application id
     * @return void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function test($slug) {

        if ($this->session->userdata('logged_in')) {
            $result = explode("_", $slug);
            $app_id = $result[0];

            $form_id = 0;
            if (isset($result[1])) {
                $form_id = $result[1];
            }
            $app = $this->app_model->get_app($app_id);
            $data['app_name'] = $app['name'];
            $data['app_general_setting'] = $app_general_setting = get_app_general_settings($app_id);
            $data['app_id'] = $app_id;
            $data['form_id'] = $form_id;
            $data['full_description']=$app['full_description'];
            $forms = $this->form_model->get_form_by_app($app_id);
            if ($form_id != 0) {
                $selected_form = $this->form_model->get_form($form_id);
                $data['full_description'] = $selected_form['description'];
            } else if (count($forms) == 1) {
                $data['full_description'] = $forms[0]['description'];
                $data['form_id'] = $forms[0]['form_id'];
            } else if (count($forms) > 1) {
                if ($app['description'] == '') {
                    $data['full_description'] = 'Landing page is empty, Please contact with Administrator for creating landing page for you';
                } else {
                    $data['full_description'] = $app['description'];
                }
            }

            $data['active_tab'] = 'app';
            $data['slug'] = $slug;
            $this->load->view('web/test', $data);
        }
        else{
            redirect(base_url());
        }
    }

    public function login($slug) {

        if ($this->input->post()) {
            $user_name = $this->input->post('login_user');
            $user_password = $this->input->post('login_password');
            $this->db->select('*');
            $this->db->from('app_users au');
            $this->db->where('au.login_user', $user_name);
            $this->db->where('au.login_password', $user_password);
            $query = $this->db->get();
            $user_data = $query->row_array();
            if ($user_data) {
                $sess_array = array(
                    'login_id' => $user_data['id'],
                    'login_name' => $user_data['name'],
                    'login_view_id' => $user_data['view_id'],
                    'login_app_id' => $user_data['app_id'],
                    'login_department_id' => $user_data['department_id'],
                    'login_user' => $user_data['login_user'],
                );

                $this->session->set_userdata('web_logged_in', $sess_array);
                redirect(base_url() . 'web/index/' . $slug);
            }
        }
        $data['id'] = $slug;
        $data['active_tab'] = 'Web User';
        $data['app_name'] = "";
        $data['pageTitle'] = PLATFORM_NAME;
        $this->load->view('web/login', $data);
    }

    public function logout($slug) {

        if ($this->session->userdata('web_logged_in')) {
            $this->session->unset_userdata('web_logged_in');
            session_destroy();
            redirect(base_url() . 'web/login/' . $slug);
        }
    }
    
    public function map($slug) {

        
            $imei_no = $_REQUEST['imei_no'];
            $date_time = $_REQUEST['date_time'];
            

            $data['app_id'] = $slug;
            $data['imei_no'] = $imei_no;
            $data['date_time'] = $date_time;
            $this->load->view('web/map', $data);
        
    }

}
