<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('department_model');
        $this->load->model('app_released_model');
        $this->load->model('app_model');
        $this->load->model('form_model');
//        if($this->acl->hasPermission('complaint','Access only complaint module')){
//            redirect(base_url() . 'complaintSystem');
//        }
        $sess_ar = $this->session->userdata('logged_in');
        if ($sess_ar['login_verification_code']!= '') {
           $this->session->set_flashdata('validate', array('message' => 'Limited time access , Your account not verified yet, please check your email and verify otherwise account will delete after 30 days.', 'type' => 'warning'));
        }
    }

    public function index() {

        
        if (!$this->acl->hasPermission('users', 'view')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'apps');
        }
        if (!$this->session->userdata('user_id')) {
            redirect(base_url() . 'guest');
        } else {
            redirect(base_url() . 'apps');
        }
    }

    /**
     * Action for new user signup
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function signup() {

        $this->load->library('form_validation');

        // field name, error message, validation rules

        $batch = array();
        if ($this->input->post()) {

            $required_if = $this->input->post('department_id') == 'new' ? '|required' : '';
            $this->form_validation->set_rules('department_name', 'Department Name', 'trim' . $required_if . '|min_length[1]|xss_clean|callback_department_name_exists');
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|xss_clean');
            //$this->form_validation->set_rules('username', 'User Name', 'trim|required|callback_username_not_available');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_not_available');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('conf_password', 'Password Confirmation', 'trim|required|matches[password]');

            if ($this->form_validation->run() == FALSE) {


                $batch = array($this->input->post('department_id'));
            } else {
                
                $department_id = $this->input->post('department_id');
                $department_info = $this->department_model->get_department($department_id);
                $group_id = $department_info['public_group'];
                $parent_id = 0;

                //map full permissions
                $modules = $this->config->config['modules'];
                $roles = $this->config->config['roles'];
                $skip = $this->config->config['skip'];
                $this->users_model->map_full_permissions($group_id, $modules, $roles, $skip);
                
                
                $varification_code = random_string('alnum', 50);
                $email = $this->input->post('email');
                $email_array = explode("@", $email);
                $data = array(
                    'department_id' => $department_id,
                    'parent_id' => $parent_id,
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'group_id' => $group_id,
                    'username' => $this->input->post('email'),
                    'email' => $this->input->post('email'),
                    'password' => md5($this->input->post('password')),
                    'verification_code' => $varification_code);

                if($department_id == 40){
                    $data['status'] = '1';
                }
                $query = $this->db->get_where('users', array('email' => $this->input->post('email'), 'status' => '0', 'is_deleted' => '0'));
                $exist = $query->row_array();

                if ($exist) {
                    $user_id = $exist['id'];
                    $this->users_model->edit_user($user_id, $data);
                } else {
                    $user_id = $this->users_model->add_user($data);
                }


                // if ($group_id) {
                //     $this->users_model->add_user_permissions_by_user($group_id, $user_id);
                // }
                addAppFromSession($department_id, $user_id);

                $this->session->set_flashdata('validate', array('message' => 'Verify your e-mail address to login', 'type' => 'success'));

                //send email to user for email varification
                $varification_url = base_url() . 'users/verify?email=' . $this->input->post('email') . '&new_email=' . $this->input->post('email') . '&code=' . $varification_code;
                $this->load->library('email');

                $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                $this->email->to($this->input->post('email'));

                $this->email->subject('Account verification');
                $message = "<b>Welcome to ".PLATFORM_NAME."</b><br />";
                $message .= "Please click below link to verify your account. <br /><br /><br /><a href='$varification_url' style='background:none repeat scroll 0 0 #2DA5DA;border:medium none;color:#FFFFFF;cursor:pointer;outline:medium none;text-decoration:none;padding:5px;'/>Verify</a>";
                $message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
                $message .= "<br /><b>".PLATFORM_NAME."</b>";

                $this->email->message($message);
                $this->email->set_mailtype('html');
                $this->email->send();

                //Send email to admin about new user signup
                $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                $this->email->to('zahidiubb@yahoo.com');
                $this->email->subject('New user signed up');
                $message = "New user has been signed up. <br />
                    User Name : " . $this->input->post('first_name') . " " . $this->input->post('last_name') . "<br />;
                    Email : " . $this->input->post('email') . "<br />";
                $this->email->message($message);

                $this->email->set_mailtype('html');
                $this->email->send();

                redirect(base_url().'guest');
                
            }
        }

        $departments = $this->department_model->get_public_department();

        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }


        $data['departments'] = $dep;
        $data['batch'] = $batch;
        $data['pageTitle'] = "Sign Up - ".PLATFORM_NAME;
        $data['active_tab'] = "";

        $this->load->view('templates/header_home', $data);
        $this->load->view('users/signup', $data);
        $this->load->view('templates/footer_home');
    }

    public function forgotpassword() {

        $this->load->library('form_validation');

        // field name, error message, validation rules

        $batch = array();
        if ($this->input->post()) {

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $email = $this->input->post('email');
                $forgot_password = random_string('alnum', 50);
                $user = $this->users_model->get_user_by_email($email);
                if ($user) {
                    $user_id = $user['id'];

                    $userdata = array(
                        'forgot_password' => $forgot_password
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $userdata);

                    //send email to user for email varification
                    $varification_url = base_url() . 'users/resetpassword?id=' . $user_id . '&email=' . $email . '&code=' . $forgot_password;
                    $this->load->library('email');

                    $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                    $this->email->to($this->input->post('email'));

                    $this->email->subject('Reset your password');
                    $message = "Dear user,<br /> Please click below link to reset password. <br /><br /><a href='$varification_url' style='background:none repeat scroll 0 0 #2DA5DA;border:medium none;color:#FFFFFF;cursor:pointer;outline:medium none;text-decoration:none;padding:5px;'/>Reset Password</a>";
                    $message .= "<br /><br /><br />Note: This is system generated e-mail. Please do not reply<br>";
                    $message .= "<br /><b>".PLATFORM_NAME."</b>";
                    $this->email->message($message);

                    $this->email->set_mailtype('html');
                    $this->email->send();
                    $this->session->set_flashdata('validate', array('message' => 'Reset password link has been sent to your email address.', 'type' => 'success'));
                } else {
                    $this->session->set_flashdata('validate', array('message' => 'This account does not exist.', 'type' => 'warning'));
                    redirect(base_url() . 'forgotpassword');
                }
                redirect(base_url() . 'guest');
            }
        }

        $data['pageTitle'] = "Forgot Password-".PLATFORM_NAME;
        $data['active_tab'] = "";

        $this->load->view('templates/header_home_blank', $data);
        $this->load->view('users/forgot_password');
        $this->load->view('templates/footer_home');
    }

    public function resetpassword() {

        $this->load->library('form_validation');

        // field name, error message, validation rules

        if (isset($_REQUEST['email']) and ! ($this->input->post())) {
            $code = $_REQUEST['code'];
            $email = $_REQUEST['email'];

            $data['email'] = $email;

            $user = $this->users_model->get_user_by_email($email);
            if ($user) {
                if ($user['forgot_password'] != $code) {
                    $this->session->set_flashdata('validate', array('message' => 'This link has been expired please resend the request.', 'type' => 'warning'));
                    redirect(base_url() . 'guest');
                } else {
                    $data['user_id'] = $user['id'];
                }
            } else {
                $this->session->set_flashdata('validate', array('message' => 'This user not available.', 'type' => 'warning'));
                redirect(base_url() . 'guest');
            }
        }
        if ($this->input->post()) {

            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('conf_password', 'Password Confirmation', 'trim|required|matches[password]');

            if ($this->form_validation->run() == FALSE) {

                $data['email'] = $this->input->post('email');
                $data['user_id'] = $this->input->post('user_id');
            } else {

                $forgot_password = random_string('alnum', 50);
                $password = $this->input->post('password');
                $user_id = $this->input->post('user_id');
                $email = $this->input->post('email');
                $userdata = array(
                    'password' => md5($password),
                    'forgot_password' => $forgot_password);
                $this->db->where('id', $user_id);
                $this->db->update('users', $userdata);

                $this->session->set_flashdata('validate', array('message' => 'Your password has been changed, please login and verify.', 'type' => 'success'));

                $this->load->library('email');
                $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                $this->email->to($email);
                $this->email->subject('Password Changed');
                $message = "Your password has been changed. New password is " . $password;
                $this->email->message($message);

                $this->email->set_mailtype('html');
                $this->email->send();

                redirect(base_url() . 'guest');
            }
        }

        $data['pageTitle'] = "Reset Password-".PLATFORM_NAME;
        $data['active_tab'] = "";
        $this->load->view('templates/header_home_blank', $data);
        $this->load->view('users/reset_password', $data);
        $this->load->view('templates/footer');
    }

    public function verify() {
        $code = $_REQUEST['code'];
        $email = str_replace(' ', '+', $_REQUEST['email']);
        $new_email = str_replace(' ', '+', $_REQUEST['new_email']);
        $query = $this->db->get_where('users', array('email' => $email, 'verification_code' => $code, 'is_deleted' => '0'));
        $exist = $query->row_array();

        if ($exist) {

            $data = array(
                'verification_code' => '',
                'email' => $new_email,
            	'username' => $new_email,
                'status' => '1'
            );
            $this->db->where('id', $exist['id']);
            $this->db->update('users', $data);
            $this->session->set_flashdata('validate', array('message' => 'Your account successfully verified', 'type' => 'success'));
           
            //Send email to admin about new user signup
            $this->load->library('email');
            $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
            $this->email->to($email);
            $this->email->subject('Your email verified');
            if ($exist['status']) {
                $message = "Your email has been verified successfully.";
            } else {
                $message = "Your email has been verified successfully.";
            }

            $this->email->message($message);
            $this->email->set_mailtype('html');
            $this->email->send();
            
            $sess_array = array();
            
            	if ($exist['is_deleted'] == 1) {
            		$this->session->set_flashdata('validate', array('message' => 'Your account has been deleted by admin.', 'type' => 'warning'));
            		redirect(base_url());
            	} else {
            		$this->session->set_flashdata('validate', array('message' => 'Your account successfully verified and logged in.', 'type' => 'success'));
            	}
            	
            	$query_department = $this->db->get_where('department', array('id' => $exist['department_id']));
            	$department_rec = $query_department->row_array();
            	
            		$sess_array = array(
            				'login_user_id' => $exist['id'],
            				'login_user_fullname' => $exist['first_name'] . ' ' . $exist['last_name'],
            				'login_username' => $exist['username'],
            				'login_department_id' => $exist['department_id'],
            				'login_department_name' => $department_rec['name'],
            				'login_parent_id' => $exist['parent_id'],
            				'login_group_id' => $exist['group_id'],
            				'login_default_url' => $exist['default_url'],
            				'login_district' => $exist['district'],
                            'login_verification_code' => $exist['verification_code']
            		);
            		
            
            
            	$this->session->set_userdata('logged_in', $sess_array);
                $this->acl->buildACL();
            	redirect(base_url() . 'apps');
            
            
            
        } else {
            $this->session->set_flashdata('validate', array('message' => 'Your email already verified', 'type' => 'warning'));
        }
        redirect(base_url() . 'guest');
    }
    public function autologin($slug) {
        
    	if ($this->session->userdata('logged_in')) {
    		if ($this->acl->hasSuperAdmin()) {
    			//$this->acl->clearACL();
    			$this->session->unset_userdata('logged_in');
    			$this->session->unset_userdata('view_session');
    			//session_destroy();

		        $query = $this->db->get_where('users', array('id' => $slug, 'is_deleted' => '0'));
		        $exist = $query->row_array();
		        $logary=array('action'=>'logout','description'=>'logout and auto login');
		        addlog($logary);
		
		        if ($exist) {
		            $sess_array = array();
		
		            $query_department = $this->db->get_where('department', array('id' => $exist['department_id']));
		            	$department_rec = $query_department->row_array();
		            	
		            		$sess_array = array(
		            				'login_user_id' => $exist['id'],
		            				'login_user_fullname' => $exist['first_name'] . ' ' . $exist['last_name'],
		            				'login_username' => $exist['username'],
		            				'login_department_id' => $exist['department_id'],
		            				'login_department_name' => $department_rec['name'],
		            				'login_parent_id' => $exist['parent_id'],
		            				'login_group_id' => $exist['group_id'],
		            				'login_default_url' => $exist['default_url'],
		            				'login_district' => $exist['district'],
                                    'login_verification_code' => $exist['verification_code']
		            		);
		            		            
		            	$this->session->set_userdata('logged_in', $sess_array);
                                $this->acl->buildACL();
		            	redirect(base_url() . 'apps');
		            	
		        } 
    		}
    	}
    }

    function department_name_exists($key) {
        if ($this->department_model->department_already_exist($key)) {
            $this->form_validation->set_message('department_name_exists', 'The %s already exists');
            return false;
        } else {
            return true;
        }
    }

    function username_not_available($key) {
        if ($this->users_model->username_already_exist($key)) {
            $this->form_validation->set_message('username_not_available', '%s not available');
            return false;
        } else {
            return true;
        }
    }

    function email_not_available($key) {
        if ($this->users_model->email_already_exist($key)) {
            $this->form_validation->set_message('email_not_available', '%s not available');
            return false;
        } else {
            return true;
        }
    }

    public function login() {

        $this->load->helper(array('form'));
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
        }
        $this->load->view('templates/header_login_page');
        $this->load->view('users/login');
        $this->load->view('templates/footer');
    }

    public function login_popup() {

        $this->load->helper(array('form'));
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
        }
        $this->load->view('users/login_popup');
    }

    /**
     * Function to validate users if users exist 
     * with entered email and password on 
     * login pop up
     * @authtor: ubaidullah.balti@itu.edu.pk (ubaidullah)
     */
    public function validate_users_login() {
        $email = $this->input->post('email',true);
        $password = $this->input->post('password',true);

        $result = $this->users_model->login($email, $password);
        $message = array();
        if ($result) {

            foreach ($result as $row) {
                if ($row->is_deleted == 1) {
                    $message['status'] = 'Your account has been deleted by admin';
                } else if ($row->verification_code != '' && $row->status == 0) {
                    $message['status'] = 'Your account not verified yet, please check your email and verify';
                } else if ($row->verification_code == '' && $row->status == 0) {
                    $message['status'] = 'Your account has been deactivaed or not approved yet';
                } else {
                    $message['status'] = 'success';
                }
            }
        } else {
            $message['status'] = 'Invalid Email or Password';
        }
        echo json_encode($message);
    }

    function logout() {
    	if ($this->session->userdata('logged_in')) {
	        $session_data = $this->session->userdata('logged_in');
	        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
	        $logary=array('action'=>'logout','description'=>'logout');
	        addlog($logary);
	        $this->acl->clearACL();
	        $this->session->unset_userdata('logged_in');
	        $this->session->unset_userdata('view_session');
	        session_destroy();
    	}
        $this->session->set_flashdata('validate', array('message' => 'You are successfully logged out.', 'type' => 'success'));
        redirect(base_url());
    }

    function login_confirm() {
        $refferer_url = $_SERVER['HTTP_REFERER'];

        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean|callback_check_database[$refferer_url]");

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            //$this->load->view('users/login');
            $this->session->set_flashdata('validate', array('message' => 'Invalid Email or Password. Please try again!', 'type' => 'warning'));
            //redirect($refferer_url);
            redirect(base_url() . 'guest');
        } else {
            //Go to private area
            $username = $this->input->post('username',true);
            $password = $this->input->post('password',true);
            $remember_me = $this->input->post('remember_me', NULL);
            if ($remember_me) {

                $expire = time() + 60 * 60 * 24 * 60;
                setcookie("remember_me", "on", $expire, "/", "");
                setcookie("remember_me_username", $username, $expire, "/", "");
                setcookie("remember_me_password", $password, $expire, "/", "");
            } else {

                $past = time() - 60 * 60 * 24 * 60;
                setcookie("remember_me", '', $past, "/", "");
                setcookie("remember_me_username", '', $past, "/", "");
                setcookie("remember_me_password", '', $past, "/", "");
            }
            $session_data = $this->session->userdata('logged_in');
            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary=array('action'=>'login','description'=>'login','after'=>'Successfull Login');
            addlog($logary);
            
            // if (!$this->acl->hasSuperAdmin()) {
            //     if($this->acl->hasPermission('complaint','Access only complaint module')){
            //         redirect(base_url() . 'complaint/index');
            //     }
            // }
            if ($session_data['login_default_url'] != '') {
                redirect($session_data['login_default_url']);
            } else {
                redirect(base_url() . 'apps');
            }
        }
    }

    function check_database($password, $refferer_url = null) {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('username',true);

        //query the database
        $result = $this->users_model->login($username, $password);

        if ($result) {


            $sess_array = array();
            foreach ($result as $row) {
            	
            	/* Get users application rights list start */
            	$user_assigned_rights = array();
            	$user_id = $row->id;
            	//$user_assigned_rights = $this->app_model->get_user_assigned_apps($user_id);
            	
                if ($row->is_deleted == 1) {
                    $this->session->set_flashdata('validate', array('message' => 'Your account has been deleted by admin.', 'type' => 'warning'));
                    redirect($refferer_url);
                } else if ($row->verification_code != '') {
                    $this->session->set_flashdata('validate', array('message' => 'Limited time access , Your account not verified yet, please check your email and verify otherwise account will delete after 30 days.', 'type' => 'warning'));
                    //redirect($refferer_url);
                } else if($row->status == 0) {
                    $this->session->set_flashdata('validate', array('message' => 'Your account has been deactivaed or not approved yet.', 'type' => 'warning'));
                    redirect($refferer_url);
                } else {
                    $this->session->set_flashdata('validate', array('message' => 'You have successfully logged in.', 'type' => 'success'));
                }
                if ($row->department_id) {
                    $sess_array = array(
                        'login_user_id' => $row->id,
                        'login_user_fullname' => $row->first_name . ' ' . $row->last_name,
                        'login_username' => $row->username,
                        'login_username_email' => $row->email,
                    	'login_password' => $row->password,
                        'login_department_id' => $row->department_id,
                        'login_department_name' => $row->department_name,
                        'login_parent_id' => $row->parent_id,
                        'login_group_id' => $row->group_id,
                        'login_default_url' => $row->default_url,
                        'login_district' => $row->district,
                        'login_verification_code' => $row->verification_code,
                    	'user_assigned_apps' => $user_assigned_rights
                    );
                    addAppFromSession($row->department_id, $row->id);
                } else {
                    $sess_array = array(
                        'login_user_id' => $row->id,
                        'login_user_fullname' => $row->first_name . ' ' . $row->last_name,
                        'login_username' => $row->username,
                        'login_username_email' => $row->email,
                    	'login_password' => $row->password,
                        'login_department_id' => '0',
                        'login_department_name' => 'Super Admin',
                        'login_parent_id' => $row->parent_id,
                        'login_group_id' => $row->group_id,
                        'login_default_url' => $row->default_url,
                        'login_district' => $row->district,
                        'login_verification_code' => $row->verification_code,
                    	'user_assigned_apps' => $user_assigned_rights
                    );
                }
                
               
                $this->session->set_userdata('logged_in', $sess_array);
                $this->acl->buildACL();
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }

    public function groups() {

        if (!$this->acl->hasPermission('groups', 'view')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'apps');
        }

        if ($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);

            $groups = $this->users_model->groups($data['login_department_id'], $data['login_parent_id'], $data['login_group_id']);

            foreach ($groups as $group) {

                if (isset($group->name)) {

                    $data['groups'][] = array(
                        'id' => $group->id,
                        'type' => $group->type,
                        'name' => $group->name,
                    );
                } else {
                    $data['groups'][] = array(
                        'id' => $group->id,
                        'type' => $group->type,
                    );
                }
            }
            $data['app_name'] = "";
            $data['pageTitle'] = "Groups - ".PLATFORM_NAME;
            $data['active_tab'] = 'groups-listing';
            $this->load->view('templates/header', $data);
            $this->load->view('users/groups', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }

    public function addgroup() {
        if (!$this->acl->hasPermission('groups', 'add')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'groups');
        }

        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $batch = array();
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            $departments = $this->department_model->get_department();

            if ($this->acl->hasSuperAdmin()) {
                $dep[''] = 'Select';
                $dep['new'] = 'Add New';
                foreach ($departments as $row) {
                    $dep[$row['id']] = $row['name'];
                }
                $data['departments'] = $dep;
            }

            if ($this->input->post()) {
                if ($this->acl->hasSuperAdmin()) {
                    $batch = array($this->input->post('department_id'));
                    $department_id = $this->input->post('department_id');
                    if ($department_id == 'new') {
                        $dep_array = array(
                            'name' => $this->input->post('department_name')
                        );
                    } else {
                        $this->form_validation->set_rules('group_name', 'Group', 'trim|required|xss_clean|callback_group_already_exist[' . $department_id . ']');
                    }
                    $required_if = $this->input->post('department_id') == 'new' ? '|required' : '';
                    $this->form_validation->set_rules('department_name', 'Department Name', 'trim' . $required_if . '|min_length[1]|xss_clean|callback_department_name_exists');
                    $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
                } else {
                    $this->form_validation->set_rules('group_name', 'Group', 'trim|required|xss_clean|callback_group_already_exist[' . $department_id . ']');
                }

                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    if ($this->acl->hasSuperAdmin()) {
                        if ($department_id == 'new') {
                            $this->db->insert('department', $dep_array);
                            $department_id = $this->db->insert_id();
                        }
                    }
                    $groupName = trim($this->input->post('group_name'));
                    $data = array(
                        'department_id' => $department_id,
                        'type' => $groupName
                    );
                    $this->db->insert('users_group', $data);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary=array('action'=>'insert','description'=>'add-group','after'=>  json_encode($data));
                    addlog($logary);
                    $this->session->set_flashdata('validate', array('message' => 'Group added successfully.', 'type' => 'success'));
                    redirect(base_url() . 'groups');
                }
            }

            $data['pageTitle'] = "Add Group-".PLATFORM_NAME;
            $data['batch'] = $batch;
            $data['active_tab'] = 'groups-add';
            $this->load->view('templates/header', $data);
            $this->load->view('users/add_group', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    function group_already_exist($group_name, $department_id) {

        if ($this->users_model->group_already_exist($group_name, $department_id)) {
            $this->form_validation->set_message('group_already_exist', '%s already exists');
            return false;
        } else {
            return true;
        }
    }

    public function grouppermission($slug) {
        if (!$this->acl->hasPermission('groups', 'edit')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'groups');
        }
        $this->load->library('form_validation');
        $group_id = $slug;
        $modules = $this->config->config['modules'];
        $roles = $this->config->config['roles'];
        $skip = $this->config->config['skip'];
        if ($this->session->userdata('logged_in')) {

            if ($this->input->post()) {

                //Remove old group permission
                $this->users_model->remove_group_permissions($group_id);

                foreach ($modules as $module) {
                    if ($this->input->post($module)) {
                        $postarray = $this->input->post($module);
                        foreach ($postarray as $checked) {
                            $insert_permission = array(
                                'group_id' => $group_id,
                                'module' => $module,
                                'role' => $checked
                            );
                            //add new group permissions
                            $this->users_model->add_group_permissions($insert_permission);
                        }
                    }
                }
                //First! Remove all users permissions which belongs to this group 
                $this->users_model->remove_user_permissions_by_group($group_id);

                // //Add new permissions to users which belongs to this group
                $this->users_model->add_user_permissions_by_group($group_id);

                $this->session->set_flashdata('validate', array('message' => 'Group permissions updated.', 'type' => 'success'));
                redirect(base_url() . 'groups');
            }

            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);

            $checked = $this->users_model->get_group_permissions($group_id);
            $already_checked = array();
            foreach ($checked as $value) {
                $already_checked[$value->module][$value->role] = 'yes';
            }


            $data['modules'] = $modules;
            $data['roles'] = $roles;
            $data['skip'] = $skip;
            $data['checked'] = $already_checked;
            $data['group_id'] = $group_id;
            $data['active_tab'] = 'groups';
            $data['pageTitle'] = "Add Group Permissions-".PLATFORM_NAME;

            $this->load->view('templates/header', $data);
            $this->load->view('users/groups_permissions', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function userlisting() {

        if ($this->session->userdata('logged_in')) {

            if (!$this->acl->hasPermission('users', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');

            session_to_page($session_data, $data);

            $users_listing = $this->users_model->get_users($session_data['login_user_id']);
            //$selected_app = $this->app_model->get_app($form_id);  
            $data['app_name'] = "";
            $data['users_listing'] = $users_listing;
            $data['active_tab'] = 'users-listing';
            $data['pageTitle'] = "Users-".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('users/users_listing', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function adduser() {

        if (!$this->acl->hasPermission('users', 'add')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'users');
        }
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);

        if (!$this->acl->hasSuperAdmin()) {
            $group_list = $this->users_model->get_groups($data['login_department_id']);
            $data['group_list'] = $group_list;
        }

        // field name, error message, validation rules

        $batch = array();
        if ($this->input->post()) {

            if ($this->acl->hasSuperAdmin()) {
                $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('group_id', 'Group', 'trim|required|xss_clean');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|xss_clean');
            //$this->form_validation->set_rules('username', 'User Name', 'trim|required|callback_username_not_available');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_not_available');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('conf_password', 'Password Confirmation', 'trim|required|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                $batch = array($this->input->post('department_id'));
            } else {

                $group_id = $this->input->post('group_id');
                if ($this->acl->hasSuperAdmin()) {
                    $department_id = $this->input->post('department_id');
                    //$parent_user = $this->users_model->get_parent_user($department_id);
                    $parent_id = 0;
//                    if ($parent_user) {
//                        $parent_id = $parent_user['id'];
//                    }
                } else {
                    $department_id = $session_data['login_department_id'];
                    $parent_id = $session_data['login_user_id'];
                }


                //check if someone is trying to create a superadmin from web...
                if($department_id==0){
                    $this->session->set_flashdata('message', '<font color=red>Wrong department selected</font>');
                    redirect(base_url() . 'add-new-user');

                }else {

                    $email = $this->input->post('email');
                    $email_array = explode('@', $email);
                    $data = array(
                        'department_id' => $department_id,
                        'parent_id' => $parent_id,
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'group_id' => $group_id,
                        'status' => '1',
                        'email' => $this->input->post('email'),
                        'username' => $email_array[0] . $email_array[1],
                        'password' => md5($this->input->post('password')));
                    $user_id = $this->users_model->add_user($data);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'insert', 'description' => 'add-user', 'after' => json_encode($data));
                    addlog($logary);
                    //$this->users_model->add_user_permissions_by_user($group_id, $user_id);
                    redirect(base_url() . 'users');
                }
            }
        }

        $departments = $this->department_model->get_department();

        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }
        $data['app_name'] = "";

        $data['departments'] = $dep;
        $data['batch'] = $batch;
        $data['active_tab'] = 'users-add';
        $data['pageTitle'] = "Add User-".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('users/add_user', $data);
        $this->load->view('templates/footer', $data);
    }

    public function editprofile($slug) {

        $user_id = $slug;
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $session_data = $this->session->userdata('logged_in');
        $login_user_id = $session_data['login_user_id'];
        if ($login_user_id != $user_id) {
            redirect(base_url() . 'user-profile/' . $login_user_id);
        }
        

        $user_rec = $this->users_model->get_user_by_id($user_id);
        $batch = array($user_rec['department_id']);

        $data = array(
            'user_id' => $user_rec['id'],
            'department_id' => $user_rec['department_id'],
            'first_name' => $user_rec['first_name'],
            'last_name' => $user_rec['last_name'],
            'group_id' => $user_rec['group_id'],
            'user_name' => $user_rec['username'],
            'email' => $user_rec['email'],
            'default_url' => $user_rec['default_url'],
        );
        $data['current_password'] = '';
        $data['new_password'] = '';
        $data['conf_new_password'] = '';
        $this->load->library('form_validation');

        session_to_page($session_data, $data);

        $department_id = $session_data['login_department_id'];
        if (!$this->acl->hasSuperAdmin()) {
            $group_list = $this->users_model->get_groups($data['login_department_id']);

            $data['group_list'] = $group_list;
        }

        // field name, error message, validation rules

        if ($this->input->post()) {
            if ($this->input->post('form_type') == 'form_info') {
                $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|xss_clean');
            } else if ($this->input->post('form_type') == 'form_email') {
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_not_available_edit[' . $user_id . ']');
                $this->form_validation->set_rules('default_url', 'URL', 'trim|valid_url_format|prep_url');
            } else if ($this->input->post('form_type') == 'form_password') {
                $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[4]|max_length[32]|callback_current_password[' . $user_id . ']');
                $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]|max_length[32]');
                $this->form_validation->set_rules('conf_new_password', 'Confirm New Password', 'trim|required|matches[new_password]');
            } else if ($this->input->post('form_type') == 'form_cancel') {
                
            }




            if ($this->form_validation->run() == FALSE) {
                if ($this->input->post('form_type') == 'form_password') {
                    $data['current_password'] = $this->input->post('current_password');
                    $data['new_password'] = $this->input->post('new_password');
                    $data['conf_new_password'] = $this->input->post('conf_new_password');
                }
            } else {

                if ($this->input->post('form_type') == 'form_info') {

                    $userdata = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'));

                    $this->users_model->edit_user($user_id, $userdata);
//                    $user_id = $this->users_model->add_user($data);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary=array('action'=>'update','description'=>'edit profile','after'=>  json_encode($data),'before'=>json_encode($user_rec));
                    addlog($logary);
                    $this->session->set_flashdata('validate', array('message' => 'Your personal information has been updated.', 'type' => 'success'));
                } else if ($this->input->post('form_type') == 'form_email') {

                    $userurl = array('default_url' => $this->input->post('default_url'));
                    $this->users_model->edit_user($user_id, $userurl);
                    if ($user_rec['email'] == $this->input->post('email')) {
                        $this->session->set_flashdata('validate', array('message' => 'You have not changed email.', 'type' => 'warning'));
                        redirect(base_url() . 'user-profile/' . $user_id);
                    }
                    $varification_code = random_string('alnum', 50);
                    $userdata = array('email' => $user_rec['email'], 'verification_code' => $varification_code, 'default_url' => $default_url);
                    $this->users_model->edit_user($user_id, $userdata);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary=array('action'=>'update','description'=>'edit profile - verification','after'=>  json_encode($userdata),'before'=>json_encode($user_rec));
                    addlog($logary);

                    //send email to user for email varification
                    $varification_url = base_url() . 'users/verify?email=' . $user_rec['email'] . '&new_email=' . $this->input->post('email') . '&code=' . $varification_code;
                    $this->load->library('email');

                    $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                    $this->email->to($this->input->post('email'));

                    $this->email->subject('Account verification');
                    $message = "Welcome to ".PLATFORM_NAME.",<br /> Please click below link to verify your email. <br />" . $varification_url;
                    $this->email->message($message);

                    $this->email->set_mailtype('html');
                    $this->email->send();
                    $this->session->set_flashdata('validate', array('message' => 'Your email has been changed, please verify your email account.', 'type' => 'success'));
                    redirect(base_url() . "apps");
                } else if ($this->input->post('form_type') == 'form_password') {

                    $userdata = array(
                        'password' => md5($this->input->post('new_password')));
                    $this->users_model->edit_user($user_id, $userdata);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary=array('action'=>'update','description'=>'edit profile - password change','after'=>  json_encode($userdata),'before'=>  json_encode($user_rec));
                    addlog($logary);

                    $this->load->library('email');

                    $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
                    $this->email->to($data['login_username']);

                    $this->email->subject('Change Password');
                    $message = "Welcome to ".PLATFORM_NAME.",<br /> Your password has been changed. New password is <b style='font-size:17px'> " . $this->input->post('new_password').'</b></b>';
                    $this->email->message($message);
                    $this->email->set_mailtype('html');
                    $this->email->send();

                    $this->session->set_flashdata('validate', array('message' => 'Your password has been changed.', 'type' => 'success'));
                } else if ($this->input->post('form_type') == 'form_cancel') {
                    
                }

                $this->session->set_flashdata('formtype',$this->input->post('form_type'));
                redirect(base_url() . 'user-profile/' . $user_id);
            }
        }

        $data['active_tab'] = 'users';
        $data['pageTitle'] = "Edit profile-".PLATFORM_NAME;
        $data['app_name'] = "";
        $data['formtype']=$this->input->post('form_type');
        $this->load->view('templates/header', $data);
        $this->load->view('users/edit_profile', $data);
        $this->load->view('templates/footer');
    }

    public function edituser($slug) {

        $user_id = $slug;


        if (!$this->acl->hasPermission('users', 'edit')) {
            $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
            redirect(base_url() . 'users');
        }
        $user_rec = $this->users_model->get_user_by_id($user_id);
        $batch = array($user_rec['department_id']);

        $data = array(
            'user_id' => $user_rec['id'],
            'department_id' => $user_rec['department_id'],
            'first_name' => $user_rec['first_name'],
            'last_name' => $user_rec['last_name'],
            'group_id' => $user_rec['group_id'],
            'username' => $this->input->post('email'),
            'email' => $user_rec['email'],
            'user_name' => $user_rec['username'],
            'default_url' => $user_rec['default_url'],
            'district' => $user_rec['district'],
        );
        $this->load->library('form_validation');
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $department_id = $session_data['login_department_id'];
        if (!$this->acl->hasSuperAdmin()) {
            $group_list = $this->users_model->get_groups($data['login_department_id']);

            $data['group_list'] = $group_list;
        }

        // field name, error message, validation rules

        if ($this->input->post()) {

            if ($this->acl->hasSuperAdmin()) {
                $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('group_id', 'Group', 'trim|required|xss_clean');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|xss_clean');
            //$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|callback_user_name_not_available[' . $user_id . ']');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_not_available_edit[' . $user_id . ']');
            $this->form_validation->set_rules('default_url', 'URL', 'trim|prep_url|valid_url_format|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $batch = array($this->input->post('department_id'));
                $data['group_id'] = $this->input->post('group_id');
            } else {
                $group_id = $this->input->post('group_id');
                if ($this->acl->hasSuperAdmin()) {
                    $department_id = $this->input->post('department_id');
                    if($user_rec['department_id']!=$department_id){
                        
                        $this->db->delete('users_app', array('user_id' => $user_rec['id']));
                    }
                }
                $data = array(
                    'department_id' => $department_id,
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'group_id' => $group_id,
                    'username' => $this->input->post('email'),
                    'email' => $this->input->post('email'),
                    'default_url' => $this->input->post('default_url'),
                    'district' => $this->input->post('district'),
                );
                if($this->input->post('password')!=""){
                	 $data['password'] = md5($this->input->post('password'));
                }
                $this->users_model->edit_user($user_id, $data);
                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary=array('action'=>'update','description'=>'edit user','after'=>  json_encode($data),'before'=>json_encode($user_rec));
                addlog($logary);

                // if ($group_id != $user_rec['group_id'])
                $this->users_model->remove_user_permissions_by_user($user_id);
                $this->users_model->add_user_permissions_by_user($group_id, $user_id);
                redirect(base_url() . 'users');
            }
        }

        $departments = $this->department_model->get_department();

        $dep[''] = 'Select';
        foreach ($departments as $row) {
            $dep[$row['id']] = $row['name'];
        }


        $data['departments'] = $dep;
        $data['batch'] = $batch;
        $data['active_tab'] = 'users-edit';
        $data['pageTitle'] = "Edit user-".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('users/edit_user', $data);
        $this->load->view('templates/footer', $data);
    }

    public function deleteuser($slug) {

        if ($this->session->userdata('logged_in')) {
            $user_id = $slug;

            if (!$this->acl->hasPermission('users', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'users');
            }

            $user_rec = $this->users_model->get_user_by_id($user_id);
            $data = array(
                'is_deleted' => '1'
            );
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary=array('action'=>'delete','description'=>'delete user','after'=>  json_encode($data),'before'=>$user_rec);
            addlog($logary);

            $this->session->set_flashdata('validate', array('message' => 'User has been deleted successfully.', 'type' => 'success'));
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function deleteAppUser($slug) {

        if ($this->session->userdata('logged_in')) {
            $user_id = $slug;

            if (!$this->acl->hasPermission('users', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'users');
            }

            $data = array(
                'is_deleted' => '1'
            );
            $this->db->where('id', $user_id);
            $this->db->update('app_users', $data);
            $this->session->set_flashdata('validate', array('message' => 'App User has been deleted successfully.', 'type' => 'success'));
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    function user_name_not_available($key, $user_id) {
        if ($this->users_model->user_name_already_exist($key, $user_id)) {
            $this->form_validation->set_message('user_name_not_available', '%s not available');
            return false;
        } else {
            return true;
        }
    }

    function email_not_available_edit($key, $user_id) {
        if ($this->users_model->email_already_exist_edit($key, $user_id)) {
            $this->form_validation->set_message('email_not_available_edit', '%s not available');
            return false;
        } else {
            return true;
        }
    }

    function current_password($key, $user_id) {
        if ($this->users_model->current_password_check($key, $user_id)) {

            return true;
        } else {
            $this->form_validation->set_message('current_password', '%s is invalid');
            return false;
        }
    }

    public function getgroups($department_id) {
        $this->load->model('users_model');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->users_model->get_groups($department_id)));
    }

    public function changestatus() {
        $this->load->model('users_model');
        $user_id = $this->input->post('id');
        $status = $this->input->post('status');
        $data = array(
            'status' => $status
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);

        $query = $this->db->get_where('users', array('id' => $user_id));
        $user_status = $query->row_array();
        $department_id = $user_status['department_id'];
        $toUser = $user_status['username'];

        //Send email to admin about new user signup
        $this->load->library('email');
        $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
        $this->email->to($toUser);
        if ($user_status['status'] == '1') {
            $this->email->subject('Account activated');
            $message = "Your account has been activated or approved by admin.";
        } else {
            $this->email->subject('Account deactivated');
            $message = "Your account has been deactivated by admin.";
        }

        $this->email->message($message);

        $this->email->set_mailtype('html');
        $this->email->send();
        exit;
    }

}
