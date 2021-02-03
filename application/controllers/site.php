<?php

class Site extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('app_users_model');
        $this->load->model('app_model');
        $this->load->model('site_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('app_installed_model');
        $this->load->model('department_model');
        $this->load->model('form_results_model');
        $this->acl->buildACL();
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
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }
            else {
                redirect(base_url() . 'apps');
            }
        }
        $data['active_tab'] = 'home';
        $data['app_name'] = "";
        $data['pageTitle'] = PLATFORM_NAME;
        $this->load->view('templates/header_home', $data);
        $this->load->view('site/index', $data);
        $this->load->view('templates/footer_home');
    }

    public function getTeam() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }
        }

        $data['active_tab'] = 'team';
        $data['app_name'] = "";
        $data['pageTitle'] = "Our Team - ".PLATFORM_NAME;
        $this->load->view('templates/header_home', $data);
        $this->load->view('site/team', $data);
        $this->load->view('templates/footer_home');
    }
    
    public function getAbout() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }
        }

        $data['active_tab'] = 'about';
        $data['app_name'] = "";
        $data['pageTitle'] = "About Us - ".PLATFORM_NAME;
        $this->load->view('templates/header_home', $data);
        $this->load->view('site/about', $data);
        $this->load->view('templates/footer_home');
    }
    public function How_To_Use() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            }
        }
        $data['active_tab'] = 'howtouse';
        $data['app_name'] = "";
        $data['pageTitle'] = "How to use -".PLATFORM_NAME;
        $this->load->view('templates/header_home', $data);
        $this->load->view('site/howtouse', $data);
        $this->load->view('templates/footer_home');
    }

    public function Login_Popup() {

        $this->load->helper(array('form'));
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
        }
        $this->load->view('site/login_popup');
    }
    public function login_Check() {

        //$this->load->helper(array('form'));
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
        }
        $this->load->view('site/login');
    }
    /**
     * Function for saving application setting
     * @param integer $app_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function Site_Settings($slug) {
        $site_id = $slug;
        $site_settings = $this->site_model->get_settings($site_id);
        $data = $site_settings;
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        if ($this->session->userdata('logged_in')) {
            if ($this->input->post()) {
                //if app setting exist then update, other wise insert
                $app_settings = array(
                    'url' => trim($this->input->post('url')),
                    'directory_path' => trim($this->input->post('directory_path')),
                    'android_target' => trim($this->input->post('android_target')),
                    's3_access_key' => trim($this->input->post('s3_access_key')),
                    's3_secret_key' => trim($this->input->post('s3_secret_key')),
                    's3_bucket' => trim($this->input->post('s3_bucket')),
                );

                $this->db->where('id', $site_id);
                $this->db->update('site_settings', $app_settings);
                $this->session->set_flashdata('validate', array('message' => 'Setting saved successfully.', 'type' => 'success'));
                redirect(base_url());
            }
            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Site Settings-".PLATFORM_NAME;
            $data['app_name'] = "";
            $this->load->view('templates/header', $data);
            $this->load->view('site/site_settings', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }
    
     /**
     * Action for application adding
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function get_Register() {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $register_array = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'country' => $this->input->post('country'),
                );
                $this->db->insert('downloads', $register_array);
                $this->load->library('email');
                $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                $this->email->to($this->input->post('email'));
                $this->email->subject(PLATFORM_NAME.' Source Code Download link');
                $message = "<b>Welcome to ".PLATFORM_NAME."</b><br />";
                $message .= "Please click below link to Download code. <br /><br /><br /><a href='http://dataplug.itu.edu.pk/assets/dp_source_code.zip' style='background:none repeat scroll 0 0 #2DA5DA;border:medium none;color:#FFFFFF;cursor:pointer;outline:medium none;text-decoration:none;padding:5px;'/>Click Here</a>";
                $message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
                $message .= "<br /><b>".PLATFORM_NAME."</b>";
                $this->email->message($message);
                $this->email->set_mailtype('html');
                $this->email->send();
                header("Location:http://dataplug.itu.edu.pk/assets/dp_source_code.zip");
            }

            $data['active_tab'] = 'app';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            $this->load->view('site/register', $data);
    }
    
     /**
     * Action for application adding
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function getContact() {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');
                $this->load->library('email');
                $this->email->from(SUPPORT_EMAIL,SUPPORT_NAME);
                $this->email->to(SUPPORT_EMAIL);
                $this->email->subject($subject);
                $message = $email."<br />".$message;
                $this->email->message($message);
                $this->email->set_mailtype('html');
                $this->email->send();
                $this->email->from(SUPPORT_EMAIL,SUPPORT_NAME);
                $this->email->to($email);
                $this->email->subject("Email received");
                $this->email->message("We contact you very soon");
                $this->email->set_mailtype('html');
                $this->email->send();
                
                $this->session->set_flashdata('validate', array('message' => "Message sent successfuly", 'type' => 'success'));
                redirect(base_url());
            }
            $data['active_tab'] = 'contact';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            $this->load->view('templates/header_home', $data);
            $this->load->view('site/contact', $data);
    }

}
