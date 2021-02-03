<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Complaint extends CI_Controller {
    
    public function __construct() {

        parent::__construct();
        $this->load->dbforge();
        $this->load->model('users_model');
        $this->load->model('app_users_model');
        $this->load->model('app_model');
        $this->load->model('complaint_model');
        $this->load->model('site_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('app_installed_model');
        $this->load->model('department_model');
        $this->load->model('form_results_model');
    }
    
    public function getIndex() {


        
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');

            $session_data = $this->session->userdata('logged_in');

            session_to_page($session_data, $data);
            // if ($session_data['login_default_url'] != '') {
            //     redirect($session_data['login_default_url']);
            // }

            $data['active_tab'] = 'complaint_index';
            $data['app_name'] = "";
            $data['pageTitle'] = "Applications-".PLATFORM_NAME;
            $this->load->view('templates/header', $data);
            $this->load->view('complaint/index', $data);
//            $this->load->view('app/index_test', $data);
            $this->load->view('templates/footer', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    
    // This method is loading all apps in datatable via ajax call....
    public function ajaxComplaints(){

        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('view');

            $session_data = $this->session->userdata('logged_in');

            session_to_page($session_data, $data);
            // if ($session_data['login_default_url'] != '') {
            //     redirect($session_data['login_default_url']);
            // }

            if($this->acl->hasPermission('complaint','view all complaints'))
            {
                $apps = $this->complaint_model->get_complaint(null,$_GET['iDisplayStart'], $_GET['iDisplayLength'], $_GET['sSearch'], $_GET['iSortCol_0'], $_GET['sSortDir_0'], $_GET['sSearch_0'], $_GET['sSearch_1'], $_GET['sSearch_2'], $_GET['sSearch_3'], $_GET['sSearch_4'], $_GET['sSearch_5'], $_GET['sSearch_6'], $_GET['sSearch_7'], $_GET['sSearch_8'], $_GET['sSearch_9']);

                $get_all_complaints = $this->complaint_model->get_complaint(null,null, null, $_GET['sSearch'], $_GET['iSortCol_0'], $_GET['sSortDir_0'], $_GET['sSearch_0'], $_GET['sSearch_1'], $_GET['sSearch_2'], $_GET['sSearch_3'], $_GET['sSearch_4'], $_GET['sSearch_5'], $_GET['sSearch_6'], $_GET['sSearch_7'], $_GET['sSearch_8'], $_GET['sSearch_9']);
                $total_complaints = count($get_all_complaints);
            }
            else{

                $apps = $this->complaint_model->get_complaint($data['login_user_id'],$_GET['iDisplayStart'], $_GET['iDisplayLength'], $_GET['sSearch'], $_GET['iSortCol_0'], $_GET['sSortDir_0'], $_GET['sSearch_0'], $_GET['sSearch_1'], $_GET['sSearch_2'], $_GET['sSearch_3'], $_GET['sSearch_4'], $_GET['sSearch_5'], $_GET['sSearch_6'], $_GET['sSearch_7'], $_GET['sSearch_8'], $_GET['sSearch_9']);

                $get_all_complaints = $this->complaint_model->get_complaint($data['login_user_id'],null, null, $_GET['sSearch'], $_GET['iSortCol_0'], $_GET['sSortDir_0'], $_GET['sSearch_0'], $_GET['sSearch_1'], $_GET['sSearch_2'], $_GET['sSearch_3'], $_GET['sSearch_4'], $_GET['sSearch_5'], $_GET['sSearch_6'], $_GET['sSearch_7'], $_GET['sSearch_8'], $_GET['sSearch_9']);
                $total_complaints = count($get_all_complaints);


                //$apps = $this->complaint_model->get_complaint_by_user($data['login_user_id']);
            }
            
            
            $data2= array(
                "sEcho" => intval($_GET['sEcho']),
                "iTotalRecords" => $total_complaints,
                "iTotalDisplayRecords" => $total_complaints,
                );



            foreach ($apps as $app) {

                $data2['aaData'][] = array(
                    'image' => $this->create_icon_image($app['cp_photo'],$app['c_id']),
                    'c_id' => $app['c_id'],
                    'c_date_time' => $app['c_date_time'],
                    'department_name' => $app['department_name'],
                    'app_name' => $app['app_name'],
                    'c_type' => $app['c_type'],
                    'c_title' => $app['c_title'],
                    'c_description' => $app['c_description'],
                    'c_status' => $app['c_status'],
                    'user_name' => $app['user_name'],
                    'action' => $this->create_action_buttons($app),
                );

            }

            if(count($apps)==0){
                echo json_encode(array(                
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


        //create application icon for datatable view...
    public function create_Icon_IAmage($image,$c_id){
        $result='';
        if ($image) {
        $result='
            <a style="padding-left:0px;" rel="lightbox['.$c_id.']" href="'.FORM_IMG_DISPLAY_PATH.'../../../../assets/complaints/'.$image.'">
                <img class="formIconsUpload" src="'.FORM_IMG_DISPLAY_PATH.'../../../../assets/complaints/'.$image.'" alt="" />
            </a>';

        }
        return $result;
    }
    
    /**
     * Action for application adding
     * @param integer $user_id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function add() {

        //send_sms("03336566326","Test sms to zahid");
        //send_sms("03354665883","Test sms to sohail");
        //send_sms("03454094853","Test sms to irfan");
        $this->load->library('form_validation');
        if ($this->session->userdata('logged_in')) {
            $batch = array();
            $session_data = $this->session->userdata('logged_in');

//            if (!$this->acl->hasPermission('app', 'add')) {
//                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
//                redirect(base_url() . '');
//            }


            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];

            $app_l = array();
            $app_list = $this->app_model->get_app_by_department($department_id);
             if ($app_list) {
                foreach ($app_list as $row) {
                    $app_l[] = array('id'=>$row['id'],'name'=>$row['name']);
                }
                
            }
            $data['app_list'] = $app_l;
            
            
            
            if ($this->input->post()) {

                $app_id = $this->input->post('app_id');
                $app_user_id = $this->input->post('app_user_id');

                $app_user_detail = $this->app_users_model->get_app_user_by_id($app_user_id);
                $app_user_info = '';
                if($app_user_detail)
                    $app_user_info = " <br>Mobile User Info : <br> User Name : ".$app_user_detail['user_name'].", IMEI# : ".$app_user_detail['imei_no'].", Mobile # : ".$app_user_detail['mobile_number'];

                $complaints = $this->input->post('complaints');
                $complaint_type = $this->input->post('complaint_type');
               
                $complaint_array = array(
                    'c_type'=>$complaint_type,
                    'c_app_id'=>$app_id,
                    'c_app_user_id'=>$app_user_id,
                    'c_complaint_by_id'=>$session_data['login_user_id'],
                    'c_department_id'=>$session_data['login_department_id'],
                    'c_status'=>'pending',
                    'c_priority'=>'1',
                );
                $abs_path = './assets/complaints/';
                
                foreach ($complaints as $complaint_key => $complaint) {
                    $description = '';
                    $resolution_time = '';

                    if($complaint=='Duplicate SIM'){
                        $description = 'Address : '.$this->input->post('duplicate_sim_address');
                        $description .= '<br />Reason : '.$this->input->post('duplicate_sim_reason');
                        $complaint_array['duplicate_sim_address']=$this->input->post('duplicate_sim_address');
                        $complaint_array['duplicate_sim_reason']=$this->input->post('duplicate_sim_reason');
                        $resolution_time = "1 Working Day";
                        //$send_email = true;
                    }
                    elseif($complaint=='SIM blocked'){
                        $description = "Sim has been blocked";
                        $resolution_time = "30 Minutes";
                        //$send_email = true;
                    }
                    elseif($complaint=='Internet & Balance Issue'){
                        $description = "Internet and balance issue from ".$this->input->post('internet_issue_from_date');
                        $description .= ' to'.$this->input->post('internet_issue_to_date');

                        $complaint_array['internet_issue_from_date']=$this->input->post('internet_issue_from_date');
                        $complaint_array['internet_issue_to_date']=$this->input->post('internet_issue_to_date');
                        $resolution_time = "4 Hours";
                        //$send_email = true;

                    }
                    elseif($complaint=='Signal Problem'){
                        $description = "Signal problem in following area. <br> District : ".$this->input->post('signal_problem_district');
                        $description .= "<br>Tehsil : ".$this->input->post('signal_problem_tehsil');
                        $description .= "<br>Markaz : ".$this->input->post('signal_problem_markaz');
                        $description .= "<br>UC : ".$this->input->post('signal_problem_uc');
                        $description .= "<br>Village : ".$this->input->post('signal_problem_village');

                        $complaint_array['signal_problem_district']=$this->input->post('signal_problem_district');
                        $complaint_array['signal_problem_tehsil']=$this->input->post('signal_problem_tehsil');
                        $complaint_array['signal_problem_markaz']=$this->input->post('signal_problem_markaz');
                        $complaint_array['signal_problem_uc']=$this->input->post('signal_problem_uc');
                        $complaint_array['signal_problem_village']=$this->input->post('signal_problem_village');
                        $resolution_time = "1 Working Day";
                        //$send_email = true;

                    }
                    elseif($complaint=='Balance Deduction'){
                        $description = "Balance received on ".$this->input->post('balance_received_date');
                        $description .= "<br> Balance deduction on ".$this->input->post('balance_deduction_date');

                        $complaint_array['balance_received_date']=$this->input->post('balance_received_date');
                        $complaint_array['balance_deduction_date']=$this->input->post('balance_deduction_date');
                        $resolution_time = "4 Hours";
                        //$send_email = true;
                    }
                    elseif($complaint=='Sim Mapping/Activation'){
                        $description = $this->input->post('sim_mapping_activation_comments')."<br />ICCID will add in change status page after resolve the issue";
                        $complaint_array['sim_mapping_activation_comments']=$this->input->post('sim_mapping_activation_comments');
                        $resolution_time = "2 Hours";
                        //$send_email = true;

                    }
                    elseif($complaint=='Ownership Change'){
                        $description = "New User Name : ".$this->input->post('ownership_user_name');
                        $description .= "<br> New User CNIC : ".$this->input->post('ownership_cnic');
                        $description .= "<br> User Designation : ".$this->input->post('ownership_designation');
                        $description .= "<br> User Place :".$this->input->post('ownership_place');

                        $complaint_array['ownership_user_name']=$this->input->post('ownership_user_name');
                        $complaint_array['ownership_cnic']=$this->input->post('ownership_cnic');
                        $complaint_array['ownership_designation']=$this->input->post('ownership_designation');
                        $complaint_array['ownership_place']=$this->input->post('ownership_place');
                        $resolution_time = "2 Hours";
                        
                    }
                    elseif($complaint=='User Status Change'){
                        $description = "User status change as : ".$this->input->post('user_status_change');
                        $complaint_array['user_status_change']=$this->input->post('user_status_change');
                        $resolution_time = "2 Hours";
                    }
                    elseif($complaint=='IMEI Update'){
                        $description = "Change IMEI# as : ".$this->input->post('imei_update');
                        $complaint_array['imei_update']=$this->input->post('imei_update');
                        $resolution_time = "2 Hours";
                    }
                    elseif($complaint=='Mark Leave'){
                        $description = "Leave Type : ".$this->input->post('leave_type');
                        $description .= "<br>Leave From : ".$this->input->post('leave_from_date');
                        $description .= " to ".$this->input->post('leave_to_date');
                        $description .= "<br>Document".$this->input->post('leave_approved_doc');
                        $complaint_array['leave_type']=$this->input->post('leave_type');
                        $complaint_array['leave_from_date']=$this->input->post('leave_from_date');
                        $complaint_array['leave_to_date']=$this->input->post('leave_to_date');
                        $resolution_time = "2 Hours";
                        
                        if ($_FILES['leave_approved_doc']['name'] != '') {
                            $config['upload_path'] = $abs_path;
                            $config['overwrite'] = FALSE;
                            $config["allowed_types"] = 'png|jpg';
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('leave_approved_doc')) {
                                $this->data['error'] = $this->upload->display_errors();
                                $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                            } else {
                                $imageData = $this->upload->data();
                                $fileName = $imageData['file_name'];
                                $image_array=array('cp_photo'=>$fileName);
                            }
                        }

                    }
                    elseif($complaint=='Login Credentials Issues'){
                        $description = "User Name : ".$this->input->post('login_user_name');
                        $description .= "<br>Old Password : ".$this->input->post('login_old_password');
                        $description .= "<br>New Password : ".$this->input->post('login_new_password');
                        $description .= "<br>Login credentials issue reason : ".$this->input->post('login_issue_reason');
                        $complaint_array['login_user_name']=$this->input->post('login_user_name');
                        $complaint_array['login_old_password']=$this->input->post('login_old_password');
                        $complaint_array['login_new_password']=$this->input->post('login_new_password');
                        $complaint_array['login_issue_reason']=$this->input->post('login_issue_reason');
                        $resolution_time = "1 Working Day";
                    }
                    elseif($complaint=='User Transferred'){
                        $description = "Transfered to following area :<br>District : ".$this->input->post('transfered_district');
                        $description .= "<br>Tehsil : ".$this->input->post('transfered_tehsil');
                        $description .= "<br>Markaz : ".$this->input->post('transfered_markaz');
                        $description .= "<br>UC : ".$this->input->post('transfered_uc');
                        $description .= "<br>Village : ".$this->input->post('transfered_village');
                        
                        $complaint_array['transfered_district']=$this->input->post('transfered_district');
                        $complaint_array['transfered_tehsil']=$this->input->post('transfered_tehsil');
                        $complaint_array['transfered_markaz']=$this->input->post('transfered_markaz');
                        $complaint_array['transfered_uc']=$this->input->post('transfered_uc');
                        $complaint_array['transfered_village']=$this->input->post('transfered_village');
                        $resolution_time = "2 Hours";
                    }
                    elseif($complaint=='Dashboard Not Working'){
                        //upload image
                        $description = $this->input->post('dashboard_not_working_comments');
                        $complaint_array['dashboard_not_working_comments']=$this->input->post('dashboard_not_working_comments');
                        $resolution_time = "2 Hours";


                        if ($_FILES['dashboard_not_working_screenshot']['name'] != '') {
                          
                            $config['upload_path'] = $abs_path;
                            $config['overwrite'] = FALSE;
                            $config["allowed_types"] = 'png|jpg';
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('dashboard_not_working_screenshot')) {
                                $this->data['error'] = $this->upload->display_errors();
                                $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                            } else {
                                $imageData = $this->upload->data();
                                $fileName = $imageData['file_name'];
                                $image_array=array('cp_photo'=>$fileName);
                            }
                        }

                    }
                    elseif($complaint=='User Showing Absent on dashboard'){
                        //upload image
                        $description = $this->input->post('showing_absent_dashboard_comments');
                        $complaint_array['showing_absent_dashboard_comments']=$this->input->post('showing_absent_dashboard_comments');
                        $resolution_time = "2 Hours";


                        if ($_FILES['showing_absent_dashboard_screenshot']['name'] != '') {
                            
                            $config['upload_path'] = $abs_path;
                            $config['overwrite'] = FALSE;
                            $config["allowed_types"] = 'png|jpg';
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('showing_absent_dashboard_screenshot')) {
                                $this->data['error'] = $this->upload->display_errors();
                                $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                            } else {
                                $image_data = $this->upload->data();
                                $file_name = $imageData['file_name'];                                
                                $image_array=array('cp_photo'=>$fileName);
                            }
                        }

                    }
                    elseif($complaint=='Activities Missing'){
                        //upload image
                        $description = $this->input->post('activities_missing_comments');
                        $complaint_array['activities_missing_comments']=$this->input->post('activities_missing_comments');
                        $resolution_time = "2 Hours";


                        if ($_FILES['activities_missing_screenshot']['name'] != '') {
                          
                            $config['upload_path'] = $abs_path;
                            $config['overwrite'] = FALSE;
                            $config["allowed_types"] = 'png|jpg';
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('activities_missing_screenshot')) {
                                $this->data['error'] = $this->upload->display_errors();
                                $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                            } else {
                                $imageData = $this->upload->data();
                                $fileName = $imageData['file_name'];                                
                                $image_array=array('cp_photo'=>$fileName);
                            }
                        }



                    }
                    elseif($complaint=='Data Not Showing'){
                        $description = "Data not showing from ".$this->input->post('data_missing_from_date');
                        $description .= " to ".$this->input->post('data_missing_to_date');
                        $description .= "<br>Comments : ".$this->input->post('data_missing_comments');
                        //upload image
                        $complaint_array['data_missing_from_date']=$this->input->post('data_missing_from_date');
                        $complaint_array['data_missing_to_date']=$this->input->post('data_missing_to_date');
                        $complaint_array['data_missing_comments']=$this->input->post('data_missing_comments');
                        $resolution_time = "2 Hours";


                        if ($_FILES['data_not_showing_screenshot']['name'] != '') {
                          
                            $config['upload_path'] = $abs_path;
                            $config['overwrite'] = FALSE;
                            $config["allowed_types"] = 'png|jpg';
                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload('data_not_showing_screenshot')) {
                                $this->data['error'] = $this->upload->display_errors();
                                $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                            } else {
                                $imageData = $this->upload->data();
                                $fileName = $imageData['file_name'];                                
                                $image_array=array('cp_photo'=>$fileName);
                            }
                        }


                    }
                    elseif($complaint=='App not Working'){
                        $description = $this->input->post('app_not_working_error');
                        $complaint_array['app_not_working_error']=$this->input->post('app_not_working_error');
                        $resolution_time = "1 Working Day";
                    }
                    elseif($complaint=='App Crash/Foreced Stopped'){
                        //$description = $this->input->post('name_update_desc');
                        $description = "Application has been crash or stopped on my device";
                        $resolution_time = "1 Working Day";
                    }
                    elseif($complaint=='APK Required'){
                        $description = $this->input->post('apk_required_email');
                        $complaint_array['apk_required_email']=$this->input->post('apk_required_email');
                        $resolution_time = "2 Hours";
                    }
                    elseif($complaint=='Unautherized User'){
                        $description = "Add IMEI : ".$this->input->post('unautherized_user_imei');
                        $complaint_array['unautherized_user_imei']=$this->input->post('unautherized_user_imei');
                        $resolution_time = "2 Hours";
                    }
                    elseif($complaint=='Other'){
                        $description = $this->input->post('other_comments');
                        $resolution_time = "24 Hours";
                    }

                    // if($send_email == true){
                    //     //$this->send_telco_email($complaint,$description.$app_user_info,$app_user_detail['mobile_network']);
                    //     $send_email = false;
                    // }

                    $complaint_array['c_resolution_time']=$resolution_time;
                    $complaint_array['c_title']=$complaint;
                    $complaint_array['c_description']=$description.$app_user_info;
                    $complaint_id = $this->complaint_model->add_complaint($complaint_array);

                    if(!empty($image_array)){
                        $image_array['cp_complaint_id_Fk'] = $complaint_id;
                        $this->db->insert('complaint_photo', $image_array);
                        unset($image_array);
                    }

                    $add_history_array = array(
                        'ch_complaint_id_Fk' => $complaint_id,
                        'ch_description' => "complaint added",
                        'ch_changed_by_id' => $session_data['login_user_id'],
                     );
                    $this->db->insert('complaint_history', $add_history_array);
                }
                redirect(base_url() . 'complaintSystem');
            }

            $data['active_tab'] = 'complaint_add';
            $data['pageTitle'] = "Add Application-".PLATFORM_NAME;
            $data['app_name'] = "";
            
            $this->load->view('templates/header', $data);
            $this->load->view('complaint/add', $data);
            $this->load->view('templates/footer', $data);
            
        } else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }
    public function edit($complaint_id)
    {
        if ($this->session->userdata('logged_in')) {
            $batch = array();
            $session_data = $this->session->userdata('logged_in');

            session_to_page($session_data, $data);

            $complaint_info = $this->complaint_model->get_complaint_by_id($complaint_id);

            $complaint_history = $this->complaint_model->get_complaint_history($complaint_id);

            $data['complaint_info']=$complaint_info[0];
            $data['complaint_history']=$complaint_history;
            
            $department_id = $session_data['login_department_id'];

            $data['active_tab'] = 'complaint_edit';
            $data['pageTitle'] = "Manage Complaint-".PLATFORM_NAME;
            $data['app_name'] = "";
            
            $this->load->view('templates/header', $data);
            $this->load->view('complaint/edit', $data);
            $this->load->view('templates/footer', $data);
        }else {
            //If no session, redirect to login page
            redirect(base_url());
        }
    }
    

    public function change_Status(){
        $complaint_id = $_REQUEST['complaint_id'];
        $status = "Change status as ".$_REQUEST['c_status'];
        $description = $_REQUEST['c_description'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $changed_by_user = $session_data['login_user_id'];

        //Change status
        $change_status_array = array('c_status'=>$_REQUEST['c_status']);
        $this->db->where('c_id', $complaint_id);
        $this->db->update('complaint', $change_status_array);

        //Add history of change status
        $add_history_array = array(
            'ch_complaint_id_Fk' => $complaint_id,
            'ch_status' => $status,
            'ch_description' => $description,
            'ch_changed_by_id' => $changed_by_user,
         );
        $this->db->insert('complaint_history', $add_history_array);

        redirect(base_url() . 'complaintSystem');
    }

    public function create_Action_Buttons($app)
    {
        $complaint_id=$app['c_id'];
        $result='';
        //if ($this->acl->hasPermission('form', 'edit')) {
            $result .= "<a style='padding:2px;' href='".base_url()."view-complaint/".$complaint_id." '><img src='".base_url()."assets/images/tableLink1.png' alt='Edit' title=''Edit'/></a>";
        //}

//        if ($this->acl->hasPermission('app', 'delete')) {
//            $result .= "<a style='padding:2px;' href='javascript:void(0)'><img src='".base_url()."assets/images/tableLink3.png' alt='delete' id ='delete_app' title='Delete' app_id ='".$appId."' /></a>";
//        }

        return $result;
    }

    public function send_Email_Telco()
    {

        $complaint_id = $_REQUEST['complaint_id'];
        $subject = $_REQUEST['subject'];
        $custom_message = $_REQUEST['message'];
        
        $complaint_info = $this->complaint_model->get_complaint_by_id($complaint_id);
        $app_user_id = $complaint_info[0]['c_app_user_id'];
        $app_user_detail = $this->app_users_model->get_app_user_by_id($app_user_id);
        $network = $app_user_detail['mobile_network'];

        $this->load->library('email');
        $this->db->select('*');
        $this->db->from('network_support ns');
        $this->db->where('ns.network_name', $network);
        $query = $this->db->get();
        $telco_list = $query->result_array();

        //Add history of change status
        $session_data = $this->session->userdata('logged_in');
        $changed_by_user = $session_data['login_user_id'];

        foreach($telco_list as $key_t => $telco_value) {
            $this->email->from(SUPPORT_EMAIL, SUPPORT_NAME);
            $this->email->to($telco_value['support_email']);

            $this->email->subject($subject);
            $message = "<b>Dear ".$telco_value['support_user']."</b>,<br />";
            $message .= "Please find below the complaint & related details. Please look into the issue & resolve.";
            $message .= "<br />$custom_message";
            $message .= "<br /><b>Regards,<br />Help Desk Team </b>";
            $message .= "<br /><br />DO NOT REPLY TO THIS EMAIL”. For further information, please contact aun.muhammad@pitb.gov.pk & rahat.ali@pitb.gov.pk";

            $this->email->message($message);
            $this->email->set_mailtype('html');
            $this->email->send();
            $add_history_array = array(
                'ch_complaint_id_Fk' => $complaint_id,
                'ch_status' => "E-mail sent with subject ".$subject,
                'ch_description' => $message,
                'ch_changed_by_id' => $changed_by_user,
            );
            $this->db->insert('complaint_history', $add_history_array);
        }

        redirect(base_url()."view-complaint/".$complaint_id);
    }
    public function get_App_User_Ajax()
    {

        $app_id = $_REQUEST['app_id'];
        $cnic = $_REQUEST['cnic'];
        $user_data = $this->app_users_model->get_app_user_by_cnic($app_id,$cnic);
        //print_r($user_data);
        if($user_data){
            $data2 = array('status'=>true,'user_rec'=>$user_data);
        }
        else{
            $data2 = array('status'=>false);
        }
        echo json_encode($data2);
        exit;
    }

    public function get_Complaints_By(){

        $complaint_by_data = $this->complaint_model->get_complaint_by_all();

        $string = array();
        foreach($complaint_by_data as $key=>$val) {
            $string[] = array('value'=>$val['id'],'label'=>$val['user_name']);
        }
        echo json_encode($string);
        exit;
    }    

    public function get_Application_All(){

        $complaint_by_data = $this->complaint_model->get_applications_all();
        $string = array();
        foreach($complaint_by_data as $key=>$val) {
            $string[] = array('value'=>$val['id'],'label'=>$val['app_name']);
        }
        echo json_encode($string);
        exit;
    }

    
}
