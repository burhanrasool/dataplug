<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('app_users_model');
        $this->load->model('app_model');
        $this->load->model('site_model');
        $this->load->model('form_model');
        $this->load->model('app_released_model');
        $this->load->model('app_installed_model');
        $this->load->model('department_model');
        $this->load->model('form_results_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper("url");
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
            if ($this->acl->hasSuperAdmin()) {
                $result_set_total = $this->site_model->get_all_log_count();
                $config['base_url'] = base_url() . 'log/index/';
                $config['total_rows'] = $result_set_total;
                $config['per_page'] = '20';
                $config['use_page_numbers'] = TRUE;
                $config['full_tag_open'] = '<div id="pagination">';
                $config['full_tag_close'] = '</div>';
                $data['total_rows'] = $config['total_rows'];
                $data['per_page'] = $config['per_page'];
                $this->pagination->initialize($config);
                if ($this->uri->segment(3) != "")
                    $offset = $this->uri->segment(3);
                else
                    $offset = '0';
                if ($offset != '0')
                    $offset = ($offset - 1) * $config['per_page'];
                $data['page_offset'] = $offset;
                $log = $this->site_model->get_all_log($config['per_page'], $offset);
                $data["paging_links"] = $this->pagination->create_links();
                $data['active_tab'] = 'log';
                $data['logs'] = $log;
                $data['total_records'] = $result_set_total;
                $data['per_page'] = $config['per_page'];
                $data['offset'] = $offset;
                $data['pageTitle'] = "Applications-".PLATFORM_NAME;
                $this->load->view('templates/header', $data);
                $this->load->view('log/index', $data);
//                $this->load->view('log/index_test', $data);
                $this->load->view('templates/footer', $data);
            } else {
                redirect(base_url() . 'guest');
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

// Load record via ajax in datatable...
    public function Ajax_Logs() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($this->acl->hasSuperAdmin()) {
                $result_set_total = $this->site_model->get_all_log_count($_GET['sSearch_0'],$_GET['sSearch_1'],$_GET['sSearch_2'],$_GET['sSearch_3'],$_GET['sSearch_6'],$_GET['sSearch_7'],$_GET['sSearch_10']);
                $data= array("sEcho" => intval($_GET['sEcho']),
                    "iTotalRecords" => $result_set_total,
                    "iTotalDisplayRecords" => $result_set_total,);
                $log = $this->site_model->get_all_log_ajax($_GET['iDisplayStart'],$_GET['iDisplayLength'],$_GET['sSearch_0'],$_GET['sSearch_1'],$_GET['sSearch_2'],$_GET['sSearch_3'],$_GET['sSearch_6'],$_GET['sSearch_7'],$_GET['sSearch_10'],$_GET['iSortCol_0'],$_GET['sSortDir_0']);
                foreach($log as $val){
                    if(is_object(json_decode($val['before_record'])) || is_array(json_decode($val['before_record']))) {
                        $before_title = print_r(json_decode($val['before_record']), true);
                    }else{
                        $before_title=$val['before_record'];
                    }
                    if(is_object(json_decode($val['after_record']))) {
                        $after_title = print_r(json_decode($val['after_record']), true);
                    }else{
                        $after_title=$val['after_record'];
                    }
                        $data['aaData'][]=array(
                        'changed_by_name'=> $val['changed_by_name'],
                        'department_name'=> $val['department_name'],
                        'action_type'=> $val['action_type'],
                        'action_description'=> $val['action_description'],
                        'before_record'=> (strlen($val['before_record']) < 20) ? $val['before_record'] : substr($val['before_record'], 0, 20) . '..',
                        'after_record'=> (strlen($val['after_record']) < 20) ? $val['after_record'] : substr($val['after_record'], 0, 20) . '..',
                        'app_name'=> $val['app_name'],
                        'form_name'=> $val['form_name'],w
                        'controller'=> $val['controller'],
                        'method'=> $val['method'],
                        'created_datetime'=> $val['created_datetime'],
                        'before_record_title'=> $before_title,
                        'after_record_title'=> $after_title,
                    );
                }
                if(count($log)==0){
                    echo json_encode(array('aaData'=>''));
                }else {
                    echo json_encode($data);
                }
            } else {
                redirect(base_url() . 'guest');
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    //get actions for log view...
    public function getActions(){
        $query=$this->db->query("select DISTINCT action_type from log");
        $result=$query->result_array();
        $string='';
        foreach($result as $key=>$val) {
            $string.=$val['action_type'].',';
        }
        $final_string=rtrim($string,',');
        echo $final_string;
    }

}
