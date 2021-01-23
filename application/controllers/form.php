<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Form extends CI_Controller {

    private $perPage = 25;
    private $perMap = 5000;

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('department_model');
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
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
//            $app_id = $slug;
//            $app = $this->app_model->get_app($app_id);
//            $data['app_name'] = $app['name'];
//            $data['app_id'] = $app_id;
//            if (!$this->acl->hasPermission('form', 'view')) {
//                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
//                redirect(base_url() . 'apps');
//            }
//            $session_data = $this->session->userdata('logged_in');
//            session_to_page($session_data, $data);
//            $data['form'] = $this->form_model->get_form_by_app($app_id);
//            $data['active_tab'] = 'app';
//            $data['pageTitle'] = $data['app_name'] . "-Forms";
//            $this->load->view('templates/header', $data);
//            $this->load->view('form/index', $data);
//            $this->load->view('templates/footer');
        } else {
            redirect(base_url() . 'guest');
        }
    }

    /**
     * method to edit form results data in color box by ajax
     * @return void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function gallery_partial() {
        $form_id = $this->input->get('form_id');
        $slug_array = explode('-', $form_id);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        $form_id = $slug;
        $form_result_id = $this->input->get('form_result_id');
        $selected_form_result = $this->form_results_model->get_results($form_result_id);
        $form_id = $selected_form_result['form_id'];
        $imei_no = $selected_form_result['imei_no'];
        $record = $selected_form_result['record'];
        $imagess = $this->form_results_model->getResultsImages($form_result_id);
        $data['form_result_id'] = $form_result_id;
        $data['form_id'] = $form_id;
        $data['images'] = $imagess;
        $selected_form = $this->form_model->get_form($form_id);
        $this->load->view('form/gallery_partial', $data);
    }

    /**
     * method to get heading and form data of  form results table 
     * @param  $slug form id 
     * @param $all_data bit for all data of a form or paginated data
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data($slug, $all_data) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        $form_id = $slug;
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {

                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $heading_array = array();
        $record_array_final = array();
        if ($all_data == 0) {
            $results = $this->form_results_model->get_form_results_pagination($form_id, $this->perPage);
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                $imagess = $this->form_results_model->getResultsImages($v['id']);
                if ($imagess) {
                    if (!in_array('image', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    $record_array = array_merge($record_array, array($key => $value));
                }
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
        } else {
            $results = $this->form_results_model->get_form_results($form_id);
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                if ($v['image'] != '') {
                    if (!in_array('image', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $v['image']));
                }
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    $record_array = array_merge($record_array, array($key => $value));
                }
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
        }

        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * method to get heading and form data  by category based of multiple form
     * @param $forms_list list of form in a single application
     * @param $to_date calender to date
     * @param $from_date  calender from date
     * @param $category_name name of category in filter
     * @param $filter_attribute_search attribute on which search is based
     * @param $search_text serach text
     * @param $town_filter town filter if exist
     * @param $posted_filters Filter list posted
     * @param $export export bit if set
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data_by_category($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text = null, $export = null) {
        $form_id = $forms_list[0]['form_id'];
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $app_id = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $login_district = $session_data['login_district'];
        $heading_array = array();
        $record_array_final = array();
        $category_name = $value = str_replace('_', '/', $category_name);
        if ($export == 1) {
            $results = $this->form_results_model->get_form_results_category_export($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $this->perPage);
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                $imagess = $this->form_results_model->getResultsImages($v['id'], $v['form_id']);
                if ($imagess) {
                    if (!in_array('image', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }

                if ($v['location'] != '') {
                    if (!in_array('location', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('location'));
                    }
                    $record_array = array_merge($record_array, array('location' => $v['location']));
                }
                if ($v['imei_no'] != '') {
                    if (!in_array('imei_no', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('imei_no'));
                    }
                    $record_array = array_merge($record_array, array('imei_no' => $v['imei_no']));
                }
                if ($v['town'] != '') {
                    if (!in_array('town', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('town'));
                    }
                    $record_array = array_merge($record_array, array('town' => $v['town']));
                }

                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    $value = html_entity_decode($value);
                    $value = str_replace('_', '/', $value);
                    $record_array = array_merge($record_array, array($key => $value));
                }
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
        } else {
            $results = $this->form_results_model->get_form_results_category($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $login_district, $this->perPage);
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                $imagess = $this->form_results_model->getResultsImages($v['id']);
                if ($imagess) {
                    if (!in_array('image', $heading_array)) {
                        $heading_array = array_merge($heading_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    $value = html_entity_decode($value);
                    $value = str_replace('_', '/', $value);

                    $record_array = array_merge($record_array, array($key => $value));
                }
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
        }
        $send = array();
        foreach ($record_array_final as $final) {
            if (!empty($category_name)) {
                if (in_array($category_name, $final, FALSE)) {
                    $send[] = $final;
                }
            } else {
                $send[] = $final;
            }
        }
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * method  used to render the form pagination data by ajax call called from paging.php
     * @param $slug application id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_data_ajax($slug = "") {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        $page_variable = isset($_POST['page']) ? $_POST['page'] : $this->perPage;
        $array_final = array();
        $array_final = $this->get_heading_data_multiple_form($forms_list);
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $data['form_id'] = $forms_list[0]['form_id'];
        $total_record_return = $this->form_results_model->TotalRecMultipleForm($forms_list);
        $pdata['app_id'] = $slug;
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'get_data_ajax';
        $pdata['slug'] = $slug;
        $data['paging'] = $this->parser->parse('form/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('form/form_results_data', $data);
    }

    //new instance
    public function pagination_ajax_data($slug = Null) {
        $forms_list = array();
        $form_single_to_query = array();
        $form_single_to_query[] = array('form_id' => $slug, 'table_name' => 'zform_' . $slug);
        $page_variable = isset($_POST['page']) ? $_POST['page'] : 0;
        $array_final = array();
        $array_final = $this->get_heading_n_data($form_single_to_query, 0);
        $data['headings'] = $array_final['headings'];
        $data['saved_columns'] = $array_final['saved_columns'];
        $data['form'] = $array_final['form'];
        $total_record_return = $this->form_results_model->return_total_record($form_single_to_query);
        $pdata['app_id'] = $slug;
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'pagination_ajax_data';
        $pdata['form_id'] = $slug;
        $pdata['slug'] = $slug;
        $data['paging'] = $this->parser->parse('form/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('form/form_results_data', $data);
    }

    /**
     * method  used to render the form pagination data by ajax call called from paging.php based on category
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_data_ajax_category_filter() {
        $slug = $_GET['form_id'];
        $to_date = $_GET['to_date'];
        $from_date = $_GET['from_date'];

        $selected_form = $this->form_model->get_form($slug);
        $app_id = $selected_form['app_id'];
        $data_per_filter = array();

        $posted_filters = array();
        $app_settings = $this->app_model->get_app_settings($app_id);
        $app_filter_list = explode(',', $app_settings['map_view_filters']);
        if (!empty($app_settings['map_view_filters'])) {
            foreach ($app_filter_list as $filters) {

                $data_per_filter[] = $this->input->get($filters);
                foreach ($data_per_filter as $datum) {
                    $final = array();
                    if (!empty($datum)) {
                        foreach ($datum as $inside) {
                            $final = array_merge($final, array($inside => $inside));
                        }
                    }
                }
                $posted_filters[$filters] = $final;
            }
            $data['selected_filters'] = $posted_filters;
        } else {
            $data['selected_filters'] = '';
        }
        $cat_filter_value = isset($_GET['cat_filter_value']) ? $_GET['cat_filter_value'] : " ";
        $filter_attribute_search = $_GET['filter_attribute_search'];
        $search_text = $_GET['search_text'];
        $form_list_filter = $_GET['form_list_filter'];
        $view_list = array();
        foreach ($form_list_filter as $final_view) {
            $view_list[] = array('form_id' => $final_view);
        }
        if (isset($_GET['town_filter'])) {
            $town_filter = $_GET['town_filter'];
        }

        $page_variable = isset($_POST['page']) ? $_POST['page'] : $this->perPage;
        $form_id = $slug;
        $array_final = array();
        $cat_filter_value = $value = str_replace('_', '/', $cat_filter_value);
        $array_final = $this->get_heading_data_by_category($view_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $export = '');
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $selected_form = $this->form_model->get_form($slug);
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($selected_form['app_id']);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        $total_record_return = $this->form_results_model->TotalRecByCategoryFilter($forms_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text);
        $pdata['app_id'] = $selected_form['app_id'];
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'get_data_ajax_category_filter';
        $pdata['slug'] = $slug;
        $pdata['form_list_filter'] = $form_list_filter;
        $data['form_id'] = $slug;
        $pdata['search_text'] = $search_text;
        $data['paging_category_filter'] = $this->parser->parse('form/paging_category_filter', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['posted_filters'] = $posted_filters;
        $data['cat_filter_value'] = $cat_filter_value;
        $data['filter_attribute_search'] = $filter_attribute_search;
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['town_filter'] = $town_filter;
        $data['view_page'] = 'paging_category_filter';
        $this->load->view('form/form_results_data', $data);
    }

    //new instance
    public function paginated_ajax_data_posted() {
        $slug = $_GET['form_id'];
        $to_date = $_GET['to_date'];
        $from_date = $_GET['from_date'];
        $district = $_GET['district'];
        $sent_by = (isset($_GET['sent_by'])) ? $_GET['sent_by'] : '';
        $selected_dc = (isset($_GET['selected_dc'])) ? $_GET['selected_dc'] : '';
        $dynamic_filters = (isset($_GET['dynamic_filters'])) ? $_GET['dynamic_filters'] : '';
        $dynamic_filters = json_decode($dynamic_filters, true);
        $selected_form = $this->form_model->get_form($slug);
        $app_id = $selected_form['app_id'];
        $data_per_filter = array();
        $posted_filters = array();
        $app_settings = $this->app_model->get_app_settings($app_id);
        if(isset($app_settings['map_view_filters'])) {
            $app_filter_list = explode(',', $app_settings['map_view_filters']);
        }else{
            $app_filter_list=array();
        }
        if (!empty($app_settings['map_view_filters'])) {
            foreach ($app_filter_list as $filters) {
                $data_per_filter[] = $this->input->get($filters);
                foreach ($data_per_filter as $datum) {
                    $final = array();
                    if (!empty($datum)) {
                        foreach ($datum as $inside) {
                            $final = array_merge($final, array($inside => $inside));
                        }
                    }
                }
                $posted_filters[$filters] = $final;
            }
            $data['selected_filters'] = $posted_filters;
        } else {
            $data['selected_filters'] = '';
        }
        $cat_filter_value = isset($_GET['cat_filter_value']) ? $_GET['cat_filter_value'] : array();
        $filter_attribute_search = isset($_GET['filter_attribute_search']) ? $_GET['filter_attribute_search'] : '';
        $search_text = $_GET['search_text'];
        $form_list_filter = $_GET['form_list_filter'];
        $view_list = array();
        foreach ($form_list_filter as $final_view) {
            $view_list[] = array('form_id' => $final_view, 'table_name' => 'zform_' . $final_view,);
        }
        if (isset($_GET['town_filter'])) {
            $town_filter = $_GET['town_filter'];
        }

        $page_variable = isset($_POST['page']) ? $_POST['page'] : 0;
        $form_id = $slug;
        $array_final = array();
        $cat_filter_value = $value = str_replace('_', '/', $cat_filter_value);
//        $dynamic_filters = $_GET['dynamic_filters'];
//        echo "<pre>";
//        print_r($dynamic_filters);die;

        $array_final = $this->get_heading_n_data_posted($view_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $sent_by, $export = '', $selected_dc, '', '', '', $dynamic_filters);

        $data['headings'] = $array_final['headings'];
        $data['saved_columns'] = $array_final['saved_columns'];
        $data['form'] = $array_final['form'];

        $selected_form = $this->form_model->get_form($slug);
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($selected_form['app_id']);
        $forms_list[] = array('form_id' => $slug, 'table_name' => 'zform_' . $slug);

        $total_record_return = $this->form_results_model->return_total_record_posted($forms_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $sent_by, $selected_dc, $dynamic_filters);
        $pdata['app_id'] = $selected_form['app_id'];
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'paginated_ajax_data_posted';
        $pdata['slug'] = $slug;
        $pdata['form_list_filter'] = $form_list_filter;
        $pdata['dynamic_filters'] = $dynamic_filters;
        $data['form_id'] = $slug;
        $pdata['search_text'] = $search_text;
        $data['paging_category_filter'] = $this->parser->parse('form/paging_category_filter', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['posted_filters'] = $posted_filters;
        $data['cat_filter_value'] = $cat_filter_value;
        $data['filter_attribute_search'] = $filter_attribute_search;
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['district'] = $district;
        $data['selected_dc'] = $selected_dc;
        $data['town_filter'] = $town_filter;
        $data['view_page'] = 'paging_category_filter';
        $this->load->view('form/form_results_data', $data);
    }

    /**
     * Return form list based on app id and 
     * filter attribute
     *  @return  form-ids array
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    private function get_form_filter_based($app_id, $filter_name) {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_list($app_id);
        foreach ($all_forms as $forms) {
            $form_id = $forms['id'];
            $table_name = 'zform_' . $form_id;
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            foreach ($schema_list as $key => $value) {
                $header_value = $value['COLUMN_NAME'];
                if ($header_value === $filter_name) {
                    $forms_list = array_merge($forms_list, array($form_id));
                    break;
                }
            }
        }
        return $forms_list;
    }

    /**
     * main method to render form results in listview based on specific application Id
     * @param  $slug application id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function results($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            /** multiple form handling system statrs here * */
            $forms_list = array();
            $subdata['app_general_setting'] = (array)get_app_general_settings($slug);
            //$subdata['app_general_setting'] = $app_general_setting;
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            if (empty($forms_list)) {
                $this->session->set_flashdata('validate', array('message' => 'Invalid App Request', 'type' => 'warning'));
                redirect(base_url() . 'app');
            }

//            $app_settings = $this->app_model->get_app_settings($slug);
//            $list_view_settings = get_result_view_settings($slug);
//            if (isset($list_view_settings->district_filter) && $list_view_settings->district_filter == 1) {
//
//                $district_list = $this->form_results_model->get_distinct_district($slug);
//                $data['district_list'] = $district_list;
//            }
//
//            if (isset($list_view_settings->sent_by_filter) && $list_view_settings->sent_by_filter == 1) {
//                $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
//                $data['sent_by_list'] = $sent_by_list;
//            }

            /** multiple form handling system ends here * */
            /** in case of post of form filters * */
            if ($this->input->post('form_list')) {
                $changed_category = '';
                $district = '';
                $selected_dc = '';
                $cat_filter_value = "";
                $sent_by = "";
                $filter_attribute_search = "";
                $form_li = $this->input->post('form_list');
                $dynamic_filters = $this->input->post('dynamic_filters');
//                echo "<pre>";
//                print_r($dynamic_filters);die;
                $selected_form = $this->form_model->get_form($form_li[0]);
                $filter_result = get_result_view_settings($selected_form['app_id']);
                if (!empty($dynamic_filters)) {
                    if (array_key_exists("sent_by", $dynamic_filters)) {
                        $imei_no_string = implode(",", $dynamic_filters['sent_by']);
                        //get users name form imei_nos...
                        $imei_no_list = $this->form_results_model->get_users_name_from_imei_no($imei_no_string);
                        $name_arr = array();
                        foreach ($imei_no_list as $key => $val) {
                            $name_arr[$val['imei_no']] = $val['name'];
                        }
                        $dynamic_filters['sent_by'] = $name_arr;
                    }
                }

                //print_r($selected_form);die;
                if (isset($filter_result->filters)) {
                    $app_filter_list = isset($filter_result->filters->$form_li[0]) ? (array) $filter_result->filters->$form_li[0] : array();
                } else {
                    $app_filter_list = array();
                }
//                if (!empty($selected_form)) {
//                    $app_filter_list = explode(',', $selected_form['possible_filters']);
//                } else {
//                    $app_filter_list = array('id');
//                }
//                $possible_filters_from_settings = $this->form_model->get_form_column_values($app_filter_list, $form_li[0]);
                $possible_filters_from_settings = $app_filter_list;
                $selected_uc = '';
                $selected_pp = '';
                $selected_na = '';


                $form_list_posted = $this->input->post('form_list');
                $form_id = $form_list_posted[0];
//                $district = $this->input->post('district_list');
//                $data['selected_district'] = $district;
//                $changed_category = $this->input->post('changed_category');
//                $selected_dc = $this->input->post('d_center');
//                $selected_form = $this->form_model->get_form($form_id);
//
//                $app_id = $selected_form['app_id'];
//                $changed_filter_form_id = $this->get_form_filter_based($app_id, $changed_category);

                $posted_array_filter = array();
                $data_per_filter = array();

                $posted_filters = array();
                $filter_options = '';
//                $filter_result = get_result_view_settings($selected_form['app_id']);
//                if (isset($filter_result->filters->$form_id)) {
//                    $app_filter_list = $filter_result->filters->$form_id;
//
//                    $filter_options .= "<option value=''>Select One</option>";
//                    if (!empty($app_filter_list)) {
//
//                        foreach ($app_filter_list as $key => $val) {
//                            $print_val = str_replace("_", " ", $val);
//                            if ($changed_category == $val) {
//                                $selected = 'selected';
//                            } else {
//                                $selected = '';
//                            }
//                            $filter_options .= "<option $selected value='$val'>$print_val</option>";
//                        }
//                    }
//                }
//                if (!empty($app_settings['map_view_filters'])) {
//                    foreach ($app_filter_list as $filters) {
//
//                        $data_per_filter[] = $this->input->post($filters);
//                        foreach ($data_per_filter as $datum) {
//                            $final = array();
//                            if (!empty($datum)) {
//                                foreach ($datum as $inside) {
//                                    $final = array_merge($final, array($inside => $inside));
//                                }
//                            }
//                        }
//                        $posted_filters[$filters] = $final;
//                    }
//                    $data['selected_filters'] = $posted_filters;
//                } else {
//                    $data['selected_filters'] = '';
//                }
                $to_date = $this->input->post('filter_date_to');
                $from_date = $this->input->post('filter_date_from');
                $town_filter = $this->input->post('town_filter');
                $search_text = $this->input->post('search_text');
                $data['search_text'] = $search_text;
                if ($search_text) {
                    $search_text = mysql_real_escape_string($search_text);
                    $search_text = str_replace(array('~', '<', '>', '$', '%', '|', '^', '*'), array(' '), $search_text);
                    $search_text = str_replace('/', '\\\\/', $search_text);
                    $search_text = trim($search_text);
                }
                $data['selected_date_to'] = $to_date;
                $data['selected_date_from'] = $from_date;
                $data['town_filter_selected'] = $town_filter;
                $view_list = array();
                $final_send = array();
                foreach ($forms_list as $final_view) {
                    if (in_array($final_view['form_id'], $form_list_posted)) {
                        $final_send = array_merge($final_send, array($final_view['form_name'] => $final_view['form_id']));
                    }
                    $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
                }
                $view_list = array_flip($view_list);
                $data['form_lists'] = $view_list;
                $data['form_list_selected'] = $final_send;
                $form_list_filter = array();
                foreach ($form_list_posted as $list) {
                    $form_list_filter[] = array('form_id' => $list, 'table_name' => 'zform_' . $list);
                }
                if (empty($to_date)) {
                    $to_date = "2013-06-03";
                    $data['selected_date_to'] = "";
                }
                if (empty($from_date)) {
                    $from_date = "2099-06-03";

                    $data['selected_date_from'] = "";
                }
//                $cat_filter = $this->input->post('cat_filter');
//                $cat_filter_value = $cat_filter;
//                $data['selected_cat_filter'] = $cat_filter;
//                $sent_by = $this->input->post('sent_by_list');
//                $data['selected_sent_by'] = $sent_by;
                if (strtotime($to_date) > strtotime($from_date)) {
                    $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                    redirect(base_url() . 'form/results/' . $form_id);
                }
                $data['form_id'] = $form_id;
                if (!$this->acl->hasPermission('form', 'view')) {
                    $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                    redirect(base_url() . 'apps');
                }
                $data['form_name'] = $selected_form['name'];
                $data['app_id'] = $selected_form['app_id'];
                $selected_app = $this->app_model->get_app($data['app_id']);
                $data['app_name'] = $selected_app['name'];
//                $data['filter'] = $changed_category;
                /** Get Town based on app id for displaying town filter on list view* */
//                $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
//                $town_list_array = array();
//                foreach ($town_lists as $towns) {
//                    if (!in_array($towns['town'], $town_list_array)) {
//                        $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
//                    }
//                }
//                $data['town_filter'] = $town_list_array;
                /** Get filters from  multiple forms * */
//                $multiple_filters = $this->form_model->get_form_filters($form_list_filter);
//                $filter_attribute = array();
//                $form_html_multiple = array();
//                foreach ($multiple_filters as $key => $value) {
//                    array_push($filter_attribute, $value['filter']);
//                    array_push($form_html_multiple, $value['description']);
//                }
//                $filter_attribute_search = (!empty($filter_attribute)) ? $filter_attribute : "";
//                $data['filter_attribute'] = $filter_attribute;
//                $data['form_html'] = $form_html_multiple;
                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
                /* $changed_category = filter attrubte search */
                $array_final = array();
                $array_final = $this->get_heading_n_data_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $changed_category, $town_filter, $posted_filters, $search_text, $district, $sent_by, $export = '', $selected_dc, $selected_uc, $selected_pp, $selected_na, $dynamic_filters);
                $data['headings'] = $array_final['headings'];
                $data['form'] = $array_final['form'];

                $result_sess_array = array(
                    'heading' => $array_final['headings'],
                    'form' => $array_final['form']
                );
                $this->db->delete('form_result_temp', array('user_id' => $session_data['login_user_id']));
                $this->db->insert('form_result_temp', array('user_id' => $session_data['login_user_id'], 'query_user' => json_encode($result_sess_array)));

                $total_record_return = $this->form_results_model->return_total_record_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $sent_by, $selected_dc, $dynamic_filters);

                $pdata['TotalRec'] = $total_record_return;
                $pdata['perPage'] = $this->perPage;
                $pdata['cat_filter_value'] = $cat_filter_value;
                $pdata['filter_attribute_search'] = $filter_attribute_search;
                $pdata['dynamic_filters'] = $dynamic_filters;
                $pdata['selected_dc'] = $selected_dc;
                $pdata['to_date'] = $to_date;
                $pdata['form_list_filter'] = $form_list_posted;
                $pdata['posted_filters'] = $posted_filters;
                $pdata['from_date'] = $from_date;
                $pdata['town_filter'] = $town_filter;
                $pdata['search_text'] = $search_text;
                $pdata['district'] = $district;
                $pdata['ajax_function'] = 'paginated_ajax_data_posted';
                $pdata['slug'] = $form_id;
                $subdata['paging_category_filter'] = $this->parser->parse('form/paging_category_filter', $pdata, TRUE);
                $subdata['all_form_results'] = $data['form'];
                $subdata['headings'] = $data['headings'];
                $subdata['saved_columns'] = $array_final['saved_columns'];
                $subdata['form'] = $data['form'];
                $subdata['total_record_return'] = $total_record_return;
                $subdata['page_variable'] = '0';
                $subdata['view_page'] = 'paging_category_filter';
                $subdata['form_id'] = $form_id;
                $subdata['app_id'] = $selected_form['app_id'];
//                print_r($array_final['saved_columns']);die;
                $subdata['saved_columns'] = $array_final['saved_columns'];
                $goto_filter_based = array();
                $data['app_filters_array'] = array();
                $category_values = array();
                (isset($changed_filter_form_id[0])) ? $changed_filter_form_id[0] : '';
                if ($changed_category) {
                    $category_list = $this->form_results_model->get_category_values('zform_' . $changed_filter_form_id[0], $changed_category);
                    foreach ($category_list as $key => $val) {
                        $category_values[$val[$changed_category]] = $val[$changed_category];
                    }
                }

                $data['body_content'] = $this->parser->parse('form/form_results_data', $subdata, TRUE);
                $data['selected_form'] = $form_id;
                $data['category_values'] = $category_values;
                $data['app_id'] = $selected_form['app_id'];
                $data['app_comments'] = $this->form_model->get_comments($selected_form['app_id']);
                $data['pageTitle'] = $selected_app['name'] . ' Records - List View-' . PLATFORM_NAME;
                $selected_app = $this->app_model->get_app($data['app_id']);
                $data['app_name'] = $selected_app['name'];
                $data['filter_options'] = $filter_options;
                $data['possible_filters_from_settings'] = $possible_filters_from_settings;
                $data['dynamic_filters'] = $dynamic_filters;
                $all_visits_hidden = $this->input->post('all_visits_hidden');
                $data['all_visits_hidden'] = $all_visits_hidden;
                $data['active_tab'] = 'form_results';
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('form/results_1293');
                } else if ($slug == 1567) {
                    $final_dc = array('' => 'Select All');
                    $disbursement_center_lists = $this->form_results_model->get_distinct_d_center(1567);
                    foreach ($disbursement_center_lists as $dc) {
                        $final_dc = array_merge($final_dc, array($dc['Disbursement_Center'] => $dc['Disbursement_Center']));
                    }
                    $data['selected_dc'] = $selected_dc;

                    $data['d_center'] = $final_dc;
                    $this->load->view('form/results_1567', $data);
                } else {

                    $this->load->view('form/results');
                }
                $this->load->view('templates/footer', $data);
            } else {
                $form_single_to_query = array();
                $form_single_to_query[] = array('form_id' => $all_forms[0]['form_id'], 'table_name' => 'zform_' . $all_forms[0]['form_id'], 'form_name' => $all_forms[0]['form_name']);
                $data['selected_district'] = '';
                $data['selected_sent_by'] = '';
                $data['all_visits_hidden'] = 0;
                $view_list = array();
                foreach ($forms_list as $final_view) {
                    $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
                }
                $view_list = array_flip($view_list);
                $data['form_lists'] = $view_list;
                $data['form_list_selected'] = $form_single_to_query;
                $first_form_id = $forms_list[0]['form_id'];
                $selected_form = $this->form_model->get_form($first_form_id);
                $data['form_name'] = $selected_form['name'];
                $data['app_id'] = $selected_form['app_id'];
                $selected_app = $this->app_model->get_app($slug);
                $data['app_name'] = $selected_app['name'];
                $data['selected_form'] = $first_form_id;
                $data['filter'] = $selected_form['filter'];
                /** Get filters from  multiple forms * */
//                $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
//                $filter_attribute = array();
//                $form_html_multiple = array();
//                foreach ($multiple_filters as $key => $value) {
//                    array_push($filter_attribute, $value['filter']);
//                    array_push($form_html_multiple, $value['description']);
//                }
//                $data['filter_attribute'] = array($filter_attribute[0]);
//                $data['form_html'] = $form_html_multiple;
                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
                $array_final = array();
                $array_final = $this->get_heading_n_data($form_single_to_query, 0);

//                echo "<pre>";
//                print_r($array_final);die;
                $data['headings'] = $array_final['headings'];
                $data['form'] = $array_final['form'];

                $result_sess_array = array(
                    'heading' => $array_final['headings'],
                    'form' => $array_final['form']
                );
                $this->db->delete('form_result_temp', array('user_id' => $session_data['login_user_id']));
                $this->db->insert('form_result_temp', array('user_id' => $session_data['login_user_id'], 'query_user' => json_encode($result_sess_array)));



                $data['active_tab'] = 'app';
                $total_record_return = $this->form_results_model->return_total_record($form_single_to_query, $slug);
                $pdata['TotalRec'] = $total_record_return;
                $pdata['perPage'] = $this->perPage;
                $pdata['form_id'] = $first_form_id;
                $pdata['ajax_function'] = 'pagination_ajax_data';
                $pdata['slug'] = $first_form_id;
                $subdata['paging'] = $this->parser->parse('form/paging', $pdata, TRUE);
                $subdata['all_form_results'] = $data['form'];
                $subdata['headings'] = $data['headings'];
                $subdata['saved_columns'] = $array_final['saved_columns'];
                $subdata['form'] = $data['form'];
                $subdata['total_record_return'] = $total_record_return;
                $subdata['page_variable'] = '0';
                $subdata['view_page'] = 'paging';
                $subdata['app_id'] = $selected_form['app_id'];

                $filter_result = get_result_view_settings($selected_form['app_id']);

                if (isset($filter_result->filters)) {
                    $app_filter_list = (isset($filter_result->filters->$first_form_id)) ? (array) $filter_result->filters->$first_form_id : array();
                } else {
                    $app_filter_list = array();
                }

//                echo "<pre>";
//                print_r($app_filter_list);die;
//                if (!empty($selected_form)) {
//                    $app_filter_list = explode(',', $selected_form['possible_filters']);
//                } else {
//                    $app_filter_list = array('id');
//                }
//                echo "<pre>";print_r($app_filter_list);die;
                //get column with values...
//                $possible_filters_from_settings = $this->form_model->get_form_column_values($app_filter_list, $first_form_id);
                $possible_filters_from_settings = $app_filter_list;

//                $filter_result = get_result_view_settings($selected_form['app_id']);
//                if (isset($filter_result->filters)) {
//                    $app_filter_list = $filter_result->filters;
//                    $filter_options = '';
//                    $filter_options.="<option value=''>Select One</option>";
//                    if (isset($app_filter_list->$selected_form['id'])) {
//                        if (!empty($app_filter_list->$selected_form['id'])) {
//                            foreach ($app_filter_list->$selected_form['id'] as $key => $val) {
//                                $print_val = str_replace("_", " ", $val);
//                                $filter_options .= "<option  value='$val'>$print_val</option>";
//                            }
//                        }
//                    }
//                }
//            else {
//                    $filter_options = '';
//                }



                $category_values = array();
//                if ($filter_attribute[0]) {
//                    $category_list = $this->form_results_model->get_category_values($form_single_to_query[0]['table_name'], $filter_attribute[0]);
//                    $category_values = array();
//                }
//                $data['app_filters_array'] = array();
//                $data['selected_filters'] = array();
                $data['possible_filters_from_settings'] = $possible_filters_from_settings;
                $data['category_values'] = $category_values;
                $data['body_content'] = $this->parser->parse('form/form_results_data', $subdata, TRUE);
                $data['pageTitle'] = $selected_app['name'] . ' Records - List View-' . PLATFORM_NAME;
                $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
                $town_list_array = array();
//                foreach ($town_lists as $towns) {
//                    if (!in_array($towns['town'], $town_list_array)) {
//                        $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
//                    }
//                }
                $data['app_id'] = $selected_form['app_id'];
                $data['app_comments'] = $this->form_model->get_comments($selected_form['app_id']);
                $data['town_filter'] = $town_list_array;
                $data['active_tab'] = 'form_results';
//                $data['filter_options'] = $filter_options;
                $data['possible_filters_from_settings'] = $possible_filters_from_settings;

                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('form/results_1293');
                } else if ($slug == 1567) {
                    $final_dc = array('' => 'Select All');
                    $disbursement_center_lists = $this->form_results_model->get_distinct_d_center(1567);
                    foreach ($disbursement_center_lists as $dc) {
                        $final_dc = array_merge($final_dc, array($dc['Disbursement_Center'] => $dc['Disbursement_Center']));
                    }
                    $data['selected_dc'] = array();
                    $data['d_center'] = $final_dc;
                    $this->load->view('form/results_1567', $data);
                } else {
                    $this->load->view('form/results');
                }

                $this->load->view('templates/footer', $data);
            }
        } else {
            redirect(base_url() . 'guest');
        }
    }
    
    
    /**
     * Action for Unsaved Activities
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function unsaved_activities() {

        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);

        $us_activities = $this->form_results_model->get_unsaved_activities();


        $data['app_user_list'] = $us_activities;
        $data['active_tab'] = 'app-users';
        $data['pageTitle'] = "Add User-".PLATFORM_NAME;

        $this->load->view('templates/header', $data);
        $this->load->view('form/unsaved_activities', $data);
        $this->load->view('templates/footer', $data);
    }
    /**
     * Action for Unsaved Activities
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function unsaved_activities_ajax() {



        
        $us_activities = $this->form_results_model->get_unsaved_activities($_GET['iDisplayStart'],$_GET['iDisplayLength'],$_GET['sSearch'],$_GET['iSortCol_0'],$_GET['sSortDir_0']);
        $total_act_un = $this->form_results_model->get_unsaved_activities_total($_GET['sSearch']);
//        making array for ajax datatable...
       $data2= array("sEcho" => intval($_GET['sEcho']),
           "iTotalRecords" => $total_act_un,
           "iTotalDisplayRecords" => $total_act_un,);

        foreach ($us_activities as $key => $val) {
            $data2['aaData'][] = array(
                'activity_id' => $val['activity_id'],
                'app_id' => $val['app_id'],
                'app_name' => $val['app_name'],
                'form_id' => $val['form_id'],
                'form_name' => $val['form_name'],
                'imei_no' => $val['imei_no'],
                'dateTime' => $val['dateTime'],
                'error' => $val['error'],
                'form_data' => $val['form_data'],

            );

        }

        if($total_act_un==0){
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
    
    

    public function exportcurrentresults() {

        $session_data = $this->session->userdata('logged_in');
        $activity_aready_exist = $this->db->order_by('id', 'desc')->get_where('form_result_temp', array('user_id' => $session_data['login_user_id']), 1)->row_array();
        $activity_aready_exist = (array) json_decode($activity_aready_exist['query_user']);
        $headings = $activity_aready_exist['heading'];
        $forms = $activity_aready_exist['form'];


        $header = '';
        foreach ($headings as $heading) {
            if ($heading == 'is_take_picture' || $heading == 'actions' || $heading == 'image') {
                continue;
            }
            $header .= $heading . ",";
        }

        $data_form = $header . "\n";
        $total_headings = count($headings);
        foreach ($forms as $form_item) {
            $form_item = (array) $form_item;
            $line = '';
            for ($i = 0; $i < $total_headings; $i++) {
                if ($headings[$i] == 'is_take_picture' || $headings[$i] == 'actions' || $headings[$i] == 'image') {
                    continue;
                } else {
                    $inside = ucwords($form_item[$headings[$i]]);
                    $value = str_replace('"', '""', $inside);
                    $value = '"' . $value . '"' . ",";
                    $line .= $value;
                }
            }
            $data_form .= trim($line) . "\n";
        }
        $data_form = str_replace("\r", "", $data_form);
        //exit;
        $filename = $session_data['login_user_id']."_result_report.csv";

        $filename = str_replace(" ", "-", $filename);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo chr(239) . chr(187) . chr(191) . $data_form;
        exit;
    }

    /**
     * main method to render form results in listview based on specific application Id
     * @param  $slug application id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function damage($slug) {
        $slug_array = array();

        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . '/apps');
            }
            /** multiple form handling system statrs here * */
            $forms_list = array();

            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }


            /** multiple form handling system ends here * */
            /** in case of post of form filters * */
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $all_forms[0]['form_id'], 'table_name' => 'zform_' . $all_forms[0]['form_id'], 'form_name' => $all_forms[0]['form_name']);
            $data['selected_district'] = '';
            $data['selected_sent_by'] = '';
            $data['all_visits_hidden'] = 0;
            $view_list = array();
            foreach ($forms_list as $final_view) {
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $first_form_id = $forms_list[0]['form_id'];
            $data['form_list_selected'] = $form_single_to_query;
            $selected_form = $this->form_model->get_form($first_form_id);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($slug);
            $data['app_name'] = $selected_app['name'];
            $data['selected_form'] = $first_form_id;
            $data['pageTitle'] = $selected_app['name'] . ' Report ' . PLATFORM_NAME;

            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $login_district = $session_data['login_district'];

            $data['active_tab'] = 'app_custom';


            $data['app_id'] = $selected_form['app_id'];
            $data['active_tab'] = 'form_results_damage';

            $this->load->view('templates/header', $data);
            if ($slug == 3883 || $slug == 3882) {
                $this->load->view('form/damage_survay');
            }

            $this->load->view('templates/footer', $data);
        } else {
            redirect(base_url() . 'guest');
        }
    }

    public function resultsframe($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        $app_settings = $this->app_model->get_app_settings($slug);
        if (isset($app_settings['district_filter']) && $app_settings['district_filter'] == 'On') {

            $district_list = $this->form_results_model->get_distinct_district($slug);
            $data['district_list'] = $district_list;
        }

        if (isset($app_settings['sent_by_filter']) && $app_settings['sent_by_filter'] == 'On') {
            $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
            $data['sent_by_list'] = $sent_by_list;
        }
        /** multiple form handling system ends here * */
        /** in case of post of form filters * */
        if ($this->input->post('form_list')) {
            $form_list_posted = $this->input->post('form_list');
            $form_id = $form_list_posted[0];
            $district = $this->input->post('district_list');
            $data['selected_district'] = $district;
            $changed_category = $this->input->post('changed_category');
            $selected_dc = $this->input->post('d_center');
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            $changed_filter_form_id = $this->get_form_filter_based($app_id, $changed_category);
            $posted_array_filter = array();
            $data_per_filter = array();
            $posted_filters = array();
            if (!empty($app_settings)) {
                $app_filter_list = explode(',', $app_settings['list_view_filters']);
            } else {
                $app_filter_list = array('id');
            }
            if (!empty($app_settings['map_view_filters'])) {
                foreach ($app_filter_list as $filters) {

                    $data_per_filter[] = $this->input->post($filters);
                    foreach ($data_per_filter as $datum) {
                        $final = array();
                        if (!empty($datum)) {
                            foreach ($datum as $inside) {
                                $final = array_merge($final, array($inside => $inside));
                            }
                        }
                    }
                    $posted_filters[$filters] = $final;
                }
                $data['selected_filters'] = $posted_filters;
            } else {
                $data['selected_filters'] = '';
            }
            $to_date = $this->input->post('filter_date_to');
            $from_date = $this->input->post('filter_date_from');
            $town_filter = $this->input->post('town_filter');
            $search_text = $this->input->post('search_text');
            $data['search_text'] = $search_text;
            if ($search_text) {
                $search_text = mysql_real_escape_string($search_text);
                $search_text = str_replace(array('~', '<', '>', '$', '%', '|', '^', '*'), array(' '), $search_text);
                $search_text = str_replace('/', '\\\\/', $search_text);
                $search_text = trim($search_text);
            }
            $data['selected_date_to'] = $to_date;
            $data['selected_date_from'] = $from_date;
            $data['town_filter_selected'] = $town_filter;
            $view_list = array();
            $final_send = array();
            foreach ($forms_list as $final_view) {
                if (in_array($final_view['form_id'], $form_list_posted)) {
                    $final_send = array_merge($final_send, array($final_view['form_name'] => $final_view['form_id']));
                }
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $data['form_list_selected'] = $final_send;
            $form_list_filter = array();
            foreach ($form_list_posted as $list) {
                $form_list_filter[] = array('form_id' => $list, 'table_name' => 'zform_' . $list);
            }
            if (empty($to_date)) {
                $to_date = "2013-06-03";
                $data['selected_date_to'] = "";
            }
            if (empty($from_date)) {
                $from_date = "2099-06-03";
                $data['selected_date_from'] = "";
            }
            $cat_filter = $this->input->post('cat_filter');
            $cat_filter_value = $cat_filter;
            $data['selected_cat_filter'] = $cat_filter;
            $sent_by = $this->input->post('sent_by_list');
            $data['selected_sent_by'] = $sent_by;
            if (strtotime($to_date) > strtotime($from_date)) {
                $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                redirect(base_url() . 'form/resultsframe/' . $slug_id);
            }
            $data['form_id'] = $form_id;
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($data['app_id']);
            $data['app_name'] = $selected_app['name'];
            $data['filter'] = $changed_category;
            /** Get Town based on app id for displaying town filter on list view* */
            $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
            $town_list_array = array();
            foreach ($town_lists as $towns) {
                if (!in_array($towns['town'], $town_list_array)) {
                    $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                }
            }
            $data['town_filter'] = $town_list_array;

            /** Get filters from  multiple forms * */
            $multiple_filters = $this->form_model->get_form_filters($form_list_filter);
            $filter_attribute = array();
            $form_html_multiple = array();
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
                array_push($form_html_multiple, $value['description']);
            }

            $filter_attribute_search = (!empty($filter_attribute)) ? $filter_attribute : "";
            $data['filter_attribute'] = $filter_attribute;
            $data['form_html'] = $form_html_multiple;
            $array_final = array();
            $array_final = $this->get_heading_n_data_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $changed_category, $town_filter, $posted_filters, $search_text, $district, $sent_by, $export = '', $selected_dc);
            $data['headings'] = $array_final['headings'];
            $data['form'] = $array_final['form'];
            $total_record_return = $this->form_results_model->return_total_record_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $sent_by, $selected_dc);
            $pdata['TotalRec'] = $total_record_return;
            $pdata['perPage'] = $this->perPage;
            $pdata['cat_filter_value'] = $cat_filter_value;
            $pdata['filter_attribute_search'] = $filter_attribute_search;
            $pdata['selected_dc'] = $selected_dc;
            $pdata['to_date'] = $to_date;
            $pdata['form_list_filter'] = $form_list_posted;
            $pdata['posted_filters'] = $posted_filters;
            $pdata['from_date'] = $from_date;
            $pdata['town_filter'] = $town_filter;
            $pdata['search_text'] = $search_text;
            $pdata['district'] = $district;
            $pdata['ajax_function'] = 'paginated_ajax_data_posted';
            $pdata['slug'] = $form_id;
            $subdata['paging_category_filter'] = $this->parser->parse('form/paging_category_filter', $pdata, TRUE);
            $subdata['all_form_results'] = $data['form'];
            $subdata['headings'] = $data['headings'];
            $subdata['form'] = $data['form'];
            $subdata['total_record_return'] = $total_record_return;
            $subdata['page_variable'] = '0';
            $subdata['view_page'] = 'paging_category_filter';
            $subdata['form_id'] = $form_id;
            $subdata['app_id'] = $selected_form['app_id'];
            $goto_filter_based = array();
            $data['app_filters_array'] = array();
            $category_values = array();
            if ($changed_category) {
                $category_list = $this->form_results_model->get_category_values('zform_' . $changed_filter_form_id[0], $changed_category);
                foreach ($category_list as $category) {
                    if ($slug == '1293') {
                        if ($category[$changed_category])
                            if (!in_array($category, $category_values)) {
                                $category = trim($category[$changed_category]);
                                $category = explode(',', $category);
                                $category_values = array_merge($category_values, array($category[0] => $category[0]));
                            }
                    } else {
                        if ($category[$changed_category])
                            $category_values = array_merge($category_values, array($category[$changed_category] => $category[$changed_category]));
                    }
                }
            }
            $data['body_content'] = $this->parser->parse('form/form_results_data_frame', $subdata, TRUE);
            $data['selected_form'] = $form_id;
            $data['category_values'] = $category_values;
            $data['app_id'] = $selected_form['app_id'];
            $data['app_comments'] = $this->form_model->get_comments($selected_form['app_id']);
            $data['pageTitle'] = $selected_app['name'] . ' Records - List View-' . PLATFORM_NAME;
            $selected_app = $this->app_model->get_app($data['app_id']);
            $data['app_name'] = $selected_app['name'];
            $all_visits_hidden = $this->input->post('all_visits_hidden');
            $data['all_visits_hidden'] = $all_visits_hidden;
            $data['active_tab'] = 'form_results_frame';
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('form/results_frame');
            $this->load->view('templates/footer_iframe', $data);
        } else {
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $all_forms[0]['form_id'], 'table_name' => 'zform_' . $all_forms[0]['form_id'], 'form_name' => $all_forms[0]['form_name']);
            $data['selected_district'] = '';
            $data['selected_sent_by'] = '';
            $data['all_visits_hidden'] = 0;
            $view_list = array();
            foreach ($forms_list as $final_view) {
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $data['form_list_selected'] = $form_single_to_query;
            $first_form_id = $forms_list[0]['form_id'];
            $selected_form = $this->form_model->get_form($first_form_id);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($slug);
            $data['app_name'] = $selected_app['name'];
            $data['selected_form'] = $first_form_id;
            $data['filter'] = $selected_form['filter'];
            /** Get filters from  multiple forms * */
            $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
            $filter_attribute = array();
            $form_html_multiple = array();
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
                array_push($form_html_multiple, $value['description']);
            }
            $data['filter_attribute'] = array($filter_attribute[0]);
            $data['form_html'] = $form_html_multiple;
            $array_final = array();
            $array_final = $this->get_heading_n_data($form_single_to_query, 0);
            $data['headings'] = $array_final['headings'];
            $data['form'] = $array_final['form'];
            $data['active_tab'] = 'app';
            $total_record_return = $this->form_results_model->return_total_record($form_single_to_query);
            $pdata['TotalRec'] = $total_record_return;
            $pdata['perPage'] = $this->perPage;
            $pdata['form_id'] = $first_form_id;
            $pdata['ajax_function'] = 'pagination_ajax_data';
            $pdata['slug'] = $first_form_id;
            $subdata['paging'] = $this->parser->parse('form/paging', $pdata, TRUE);
            $subdata['all_form_results'] = $data['form'];
            $subdata['headings'] = $data['headings'];
            $subdata['form'] = $data['form'];
            $subdata['total_record_return'] = $total_record_return;
            $subdata['page_variable'] = '0';
            $subdata['view_page'] = 'paging';
            $subdata['app_id'] = $selected_form['app_id'];
            if (!empty($app_settings)) {
                $app_filter_list = explode(',', $app_settings['list_view_filters']);
            } else {
                $app_filter_list = array('id');
            }
            $category_values = array();
            if ($filter_attribute[0]) {
                $category_list = $this->form_results_model->get_category_values($form_single_to_query[0]['table_name'], $filter_attribute[0]);
                foreach ($category_list as $category) {
                    if ($category[$filter_attribute[0]]) {
                        if ($slug == '1293') {
                            if (!in_array($category, $category_values)) {
                                $category = trim($category[$filter_attribute[0]]);
                                $category = explode(',', $category);
                                $category_values = array_merge($category_values, array($category[0] => $category[0]));
                            }
                        } else {
                            $category_values = array_merge($category_values, array($category[$filter_attribute[0]] => $category[$filter_attribute[0]]));
                        }
                    }
                }
            }
            $data['app_filters_array'] = array();
            $data['selected_filters'] = array();
            $data['category_values'] = $category_values;
            $data['body_content'] = $this->parser->parse('form/form_results_data_frame', $subdata, TRUE);
            $data['pageTitle'] = $selected_app['name'] . ' Records - List View-' . PLATFORM_NAME;
            $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
            $town_list_array = array();
            foreach ($town_lists as $towns) {
                if (!in_array($towns['town'], $town_list_array)) {
                    $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                }
            }
            $data['app_id'] = $selected_form['app_id'];
            $data['app_comments'] = $this->form_model->get_comments($selected_form['app_id']);
            $data['town_filter'] = $town_list_array;
            $data['active_tab'] = 'form_results_frame';
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('form/results_frame');
            $this->load->view('templates/footer_iframe', $data);
        }
    }

    //new instance
    public function get_heading_n_data_posted($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text = null, $district, $sent_by, $export = null, $selected_dc, $selected_uc, $selected_pp, $selected_na, $dynamic_filters) {
        $form_id = $forms_list[0]['form_id'];
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $app_id = $selected_form['app_id'];
        $table_headers_array = array();
        $record_array_final = array();
        $heading_query = array();
        $category_name = $value = str_replace('_', '/', $category_name);
//        $exclude_array = array('id', 'remote_id', 'district_name', 'uc_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted', 'version_name', 'location_source', 'time_source', 'post_status');
        $exclude_array = array('id', 'remote_id', 'form_id', 'is_deleted');
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
            $results = $this->form_results_model->get_result_paginated_posted($table_name, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $sent_by, $this->perPage, $selected_dc, $selected_uc, $selected_pp, $selected_na, $dynamic_filters);
//            $results = $this->form_results_model->get_form_results_category($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $login_district, $this->perPage);
//           dump($results);
            $imagess = array();
            foreach ($results as $k => $v) {
                $record_array = array();
                $imagess = $this->form_results_model->getResultsImages($v['id'], $v['form_id']);
                if ($imagess) {
                    if (!in_array('image', $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }
                foreach ($v as $key => $value) {
                    $value = html_entity_decode($value);
                    $value = str_replace('_', '/', $value);
                    if (!in_array($key, $exclude_array)) {
                        $record_array = array_merge($record_array, array($key => $value));
                    }
                }
                $record_array = array_merge($record_array, array('form_id' => $v['form_id']));
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
            //below parameter 1 for telling function its for reporting...
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name, 1);
            $heading_query = array_merge($heading_query, $schema_list);
        }
        $send = array();
        foreach ($record_array_final as $final) {
            if (!empty($category_name)) {
                if (in_array($category_name, $final, FALSE)) {
                    $send[] = $final;
                }
            } else {
                $send[] = $final;
            }
        }
//        $table_headers_array = array_merge($table_headers_array, array('sent_by'));
        foreach ($heading_query as $key => $value) {
            $header_value = $value['COLUMN_NAME'];
            if ($header_value != 'created_datetime') {
                if (!in_array($header_value, $exclude_array)) {
                    if (!in_array($header_value, $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array($header_value));
                    }
                }
            }
        }

        $table_headers_array = array_merge($table_headers_array, array('created_datetime'));
        $table_headers_array = array_merge($table_headers_array, array('actions'));
        //get heading lables...
        $table_arr = explode("_", $table_name);
        $form_id = $table_arr[1];
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];
        $column_settings = $this->form_model->get_column_settings($app_id);
        $final_columns = array();
        if (!empty($column_settings)) {
            $column_settings_arr = json_decode($column_settings['columns'], true);
            if (isset($column_settings_arr[$form_id])) {
                $saved_columns = $column_settings_arr[$form_id]['columns'];
                $saved_order = $column_settings_arr[$form_id]['order'];
                asort($saved_order);
                $saved_order = array_filter($saved_order);
                $saved_order_new = array();
                foreach ($saved_order as $key => $val) {
                    $saved_order_new[$key] = $saved_columns[$key];
                    unset($saved_columns[$key]);
                }
                $final_columns = array_merge($saved_order_new, $saved_columns);
                if (isset($imagess)) {
                    $final_columns = array_merge(array('image' => 'image'), $final_columns);
                }
            }
        }

        $data['saved_columns'] = $final_columns;

        $data['headings'] = $table_headers_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * method to get heading and form data of  form results table of multiple form
     * @param  $form_list list of form in a single applicatoin
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    //NEW INSTANCE
    public function get_heading_n_data($forms_list = Null, $all_data) {
        $record_array_final = array();
        $table_headers_array = array();
        $heading_query = array();
        $image_ex = false;
//        $exclude_array = array('id', 'remote_id', 'district_name', 'uc_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted', 'version_name', 'location_source', 'time_source', 'post_status');
        $exclude_array = array('id', 'remote_id', 'form_id', 'is_deleted');
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
            //$table_exist_bit = $this->form_results_model->check_table_exits($table_name);
            if (!is_table_exist($table_name)) {
                $this->session->set_flashdata('validate', array('message' => 'No table schema has been built againts this application', 'type' => 'warning'));
                redirect(base_url() . 'app');
            }
            $results = $this->form_results_model->get_results_paginated($table_name, $this->perPage, $all_data);
            foreach ($results as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if (!in_array($key, $exclude_array)) {
                        $record_array = array_merge($record_array, array($key => $value));
                    }
                }

                $imagess = $this->form_results_model->getResultsImages($v['id'], $form_entity['form_id']);
                if ($imagess) {
                    $image_ex = true;
                    if (!in_array('image', $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }

                $record_array = array_merge($record_array, array('form_id' => $v['form_id'], 'actions' => $v['id']));
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
            // 1 in below function for reporting purpose...
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name, 1);
            $heading_query = array_merge($heading_query, $schema_list);
        }

//        $table_headers_array = array_merge($table_headers_array, array('sent_by'));

        foreach ($heading_query as $key => $value) {
            $header_value = $value['COLUMN_NAME'];
//            if ($header_value != 'created_datetime') {
            if (!in_array($header_value, $table_headers_array)) {
                if (!in_array($header_value, $exclude_array)) {
                    $table_headers_array = array_merge($table_headers_array, array($header_value));
                }
            }
//            }
        }
//        echo "<pre>";
//        print_r($table_headers_array);die;
//        $table_headers_array = array_merge($table_headers_array, array('created_datetime'));

        $table_headers_array = array_merge($table_headers_array, array('actions'));
        //get heading lables...
        $table_arr = explode("_", $table_name);
        $form_id = $table_arr[1];
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];
        $column_settings = $this->form_model->get_column_settings($app_id);
        $saved_order_new = array();
        $final_columns = array();
        if (!empty($column_settings)) {
            $column_settings_arr = json_decode($column_settings['columns'], true);
            if (isset($column_settings_arr[$form_id])) {
                $saved_columns = $column_settings_arr[$form_id]['columns'];
                $saved_order = $column_settings_arr[$form_id]['order'];
                asort($saved_order);
                $saved_order = array_filter($saved_order);
                foreach ($saved_order as $key => $val) {
                    $saved_order_new[$key] = $saved_columns[$key];
                    unset($saved_columns[$key]);
                }
                $final_columns = array_merge($saved_order_new, $saved_columns);
                if ($image_ex) {
                    $final_columns = array_merge(array('image' => 'image'), $final_columns);
                }
            }
        }

        $data['saved_columns'] = $final_columns;
        $data['headings'] = $table_headers_array;
        $data['form'] = $record_array_final;
        return $data[] = $data;
    }

    /**
     * method to get heading and form data of  form results table of multiple form
     * @param  $form_list list of form in a single applicatoin
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data_multiple_form($form_list) {
        $slug = $form_list[0]['form_id'];
        $form_id = $slug;
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {

                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $login_district = $session_data['login_district'];
        $heading_array = array();
        $record_array_final = array();
        $results = $this->form_results_model->get_multiple_form_results_pagination($form_list, $login_district, $this->perPage);
        foreach ($results as $k => $v) {
            $record_array = array();
            $result_json = $v['record'];
            $imagess = $this->form_results_model->getResultsImages($v['id']);
            if ($imagess) {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $imagess));
            }
            $result_array = json_decode($result_json);
            foreach ($result_array as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                $record_array = array_merge($record_array, array($key => $value));
            }

            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
            $record_array_final[] = $record_array;
        }
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * method to get heading data of multiple form for all data based on filter array
     * @param  $form_list list of form in a single applicatoin
     * @param  $app_filter_list array of all filter set on which search is based
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data_multiple_all($form_lists, $app_filter_list) {
        $form_id = $form_lists[0]['form_id'];
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $login_district = $session_data['login_district'];
        $app_filters_array = array();
        $heading_array = array();
        $record_array_final = array();
        $results = $this->form_results_model->get_form_results_multiple_all_data($form_lists, $login_district);
        foreach ($results as $k => $v) {
            $record_array = array();
            $result_json = $v['record'];
            if ($v['image'] != '') {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $v['image']));
            }
            $result_array = json_decode($result_json);
            foreach ($result_array as $key => $value) {
                /* <-- Filter based on App Setting start here --> */
                foreach ($app_filter_list as $filters) {
                    if (isset($result_array->$filters)) {
                        $filter_entity = $result_array->$filters;
                        if (!key_exists($filters, $app_filters_array)) {
                            $app_filters_array[$filters][$filter_entity] = $filter_entity;
                        }
                        if (!in_array($filter_entity, $app_filters_array[$filters])) {
                            $app_filters_array[$filters][$filter_entity] = $filter_entity;
                        }
                    }
                }
                /* <-- Filter based on App Setting ends  here --> */
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                $record_array = array_merge($record_array, array($key => $value));
            }

            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));

            $record_array_final[] = $record_array;
        }

        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        $data['app_filters_array'] = $app_filters_array;
        return $data[] = $data;
    }

    /**
     * method to get heading data of multiple form for all data based on filter array when posted
     * @param  $form_list list of form in a single applicatoin
     * @param  $app_filter_list array of all filter set on which search is based
     * @return  array An array of form heading and its data
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data_multiple_all_for_posted($form_lists, $app_filter_list) {
        $form_id = $form_lists[0]['form_id'];
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $login_district = $session_data['login_district'];
        $heading_array = array();
        $record_array_final = array();
        $app_filters_array = array();
        $results = $this->form_results_model->get_form_results_multiple_all_data($form_lists, $login_district);
        foreach ($results as $k => $v) {
            $record_array = array();
            $result_json = $v['record'];
            if ($v['image'] != '') {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $v['image']));
            }
            $result_array = json_decode($result_json);
            foreach ($result_array as $key => $value) {
                /** Making Filter system herer* */
                $inside_counter = 0;
                foreach ($app_filter_list as $keys => $f_values) {
                    if ($inside_counter != 0) {
                        if (!empty($f_values)) {
                            foreach ($f_values as $inside) {
                                if (isset($result_array->$keys)) {
                                    if ($result_array->$keys == $inside) {
                                        $filter_entity = $result_array->$keys;
                                        if (!key_exists($key, $app_filters_array)) {
                                            $app_filters_array[$keys][$filter_entity] = $filter_entity;
                                        }
                                        if (isset($app_filters_array[$keys])) {
                                            if (!in_array($filter_entity, $app_filters_array[$keys])) {
                                                $app_filters_array[$keys][$filter_entity] = $filter_entity;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($keys == $key) {
                                if (!key_exists($key, $app_filters_array)) {
                                    $app_filters_array[$keys] = array();
                                }
                            }
                        }
                    } else {
                        $filter_entity = $result_array->$keys;
                        if (!key_exists($keys, $app_filters_array)) {
                            $app_filters_array[$keys][$filter_entity] = $filter_entity;
                        }
                        if (!in_array($filter_entity, $app_filters_array[$keys])) {
                            $app_filters_array[$keys][$filter_entity] = $filter_entity;
                        }
                        $inside_counter++;
                    }
                }
                /** Filter system end here ub* */
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                $record_array = array_merge($record_array, array($key => $value));
            }
            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
            $record_array_final[] = $record_array;
        }

        $final_filter_sorted = array();
        /** sorting array based on key* */
        foreach ($app_filter_list as $key_sorter => $posts) {
            $final_filter_sorted[$key_sorter] = $app_filters_array[$key_sorter];
        }
        $data['app_filters_array'] = $final_filter_sorted;
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * main method rendering map of an application based on Application Id
     * @param  $slug application id
     * @return  null
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function mapview($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'application-map/' . $slug);
            }
            /** multiple form handling system statrs * */
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            /** multi form ends herer.....* */
            $heading_array = array();
            /** In case of post filter form map view* */
            if ($this->input->post('form_id')) {
                $form_id_posted = $form_id = $this->input->post('form_id');
                $changed_category = $this->input->post('changed_category');

                $dynamic_filters = $this->input->post('dynamic_filters');
                $selected_form = $this->form_model->get_form($form_id_posted);
                $app_id = $selected_form['app_id'];
                $map_saved_pins = get_map_pin_settings($form_id_posted);
                $posted_array_filter = array();
                $data_per_filter = array();
                $posted_filters = array();
                $app_settings = $this->app_model->get_app_settings($app_id);
                $app_filter_list = explode(',', (isset($app_settings['map_view_filters'])) ? $app_settings['map_view_filters'] : '');
                if (!empty($app_settings['map_view_filters'])) {
                    foreach ($app_filter_list as $filters) {
                        $data_per_filter[] = $this->input->post($filters);
                        foreach ($data_per_filter as $datum) {
                            $final = array();
                            if (!empty($datum)) {
                                foreach ($datum as $inside) {
                                    $final = array_merge($final, array($inside => $inside));
                                }
                            }
                        }
                        $posted_filters[$filters] = $final;
                    }
                    $data['selected_filters'] = $posted_filters;
                } else {
                    $data['selected_filters'] = '';
                }
                $form_list_posted = $this->input->post('form_list');
                $search_text = $this->input->post('search_text');
                $data['search_text'] = $search_text;
                if ($search_text) {
                    $search_text = mysql_real_escape_string($search_text);
                    $search_text = str_replace(array('~', '<', '>', '$', '%', '|', '^', '*'), array(' '), $search_text);
                    $search_text = str_replace('/', '\\\\/', $search_text);
                    $search_text = trim($search_text);
                }
                if (!$form_list_posted) {
                    redirect(base_url() . 'application-map/' . $slug);
                }
                $view_list = array();
                $final_send = array();
                foreach ($forms_list as $final_view) {
                    if (in_array($final_view['form_id'], $form_list_posted)) {
                        $final_send = array_merge($final_send, array($final_view['form_name'] => $final_view['form_id']));
                    }
                    $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
                }
                $view_list = array_flip($view_list);
                $data['form_lists'] = $view_list;
                $data['form_list_selected'] = $final_send;
                $data['form_id'] = $form_list_posted[0];
                $data['selected_form'] = $form_id_posted;
                $to_date = $this->input->post('filter_date_to');
                $from_date = $this->input->post('filter_date_from');
                $view_type = $this->input->post('view_type');
                $boundaries = $this->input->post('boundaries');
                $district_select = $this->input->post('district_select');
                $data['selected_date_to'] = $to_date;
                $data['selected_date_from'] = $from_date;
                $data['view_type'] = $view_type;
                $data['boundaries'] = $boundaries;
                $data['district_selected'] = $district_select;
                if (empty($to_date)) {
                    $to_date = "2013-06-03";
                    $data['selected_date_to'] = "";
                }
                if (empty($from_date)) {
                    $from_date = "2099-06-03";
                    $data['selected_date_from'] = "";
                }
                if (strtotime($to_date) > strtotime($from_date)) {
                    $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                    redirect(base_url() . 'application-map/' . $slug);
                }

                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
                $form_list_filter = array();
                foreach ($form_list_posted as $form_entity) {
                    $form_list_filter[] = array('form_id' => $form_entity, 'table_name' => 'zform_' . $form_entity);
                }

                $total_result = $this->form_results_model->return_total_record_map_posted($form_list_filter, $to_date, $from_date);
                $totalPages = ceil($total_result / $this->perMap);
                $data['totalPages'] = $totalPages;
                $filter_date_map = $this->input->post('filter_date_map');
                $selected_form = $this->form_model->get_form($form_id_posted);
                $data['form_name'] = $selected_form['name'];
                $data['app_id'] = $selected_form['app_id'];
                /*                 * Get Multiple form filters * */
                $filter_attribute = array();
                $form_html_multiple = array();
                $multiple_filters = $this->form_model->get_form_filters($form_list_filter);
                foreach ($multiple_filters as $key => $value) {
                    array_push($filter_attribute, $value['filter']);
                    array_push($form_html_multiple, $value['description']);
                }

                $data['filter_attribute'] = $filter_attribute;
                $data['form_html'] = $form_html_multiple;
                $record_array_final = array();
                /** for categry listing * */
                $record_array_final_filter = array();
                $results_filer_main = $this->form_results_model->get_form_results_filters($form_list_filter, $login_district);
                $app_filters_array = array();
                $town_array = array();
                $uc_array = array();
                $filter_exist_array = array();
                $pin_exist_for_cat = array();
                $col_pin = 0;
                $exist_alpha = array();
                foreach ($results_filer_main as $results_filer) {
                    foreach ($results_filer as $k => $v) {
                        $record_array = array();
                        foreach ($v as $key => $value) {
                            if ($key == 'created_datetime') {
                                $date = $value;
                            } else if ($key == 'form_id') {
                                $form_id = $value;
                            } else if ($key == 'location') {
                                $location = $value;
                            } else if ($key == 'id') {
                                $result_id = $value;
                            }
                            if ($key == 'town_name') {
                                $town_name = $value;
                                if ($town_name) {
                                    if (!in_array($town_name, $town_array)) {
                                        array_push($town_array, $town_name);
                                    }
                                }
                            }
                            if ($key == 'uc_name') {
                                $uc_name = $value;
                                if ($uc_name) {
                                    if (!in_array($uc_name, $uc_array)) {
                                        array_push($uc_array, $uc_name);
                                    }
                                }
                            }

                            if (!in_array($key, $heading_array)) {
                                $heading_array = array_merge($heading_array, array($key));
                            }
                            /* <-- Filter based on App Setting start here --> */
                            foreach ($app_filter_list as $filters) {
                                if (isset($v->$filters)) {
                                    $filter_entity = $v->$filters;
                                    if (!key_exists($filters, $app_filters_array)) {
                                        $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                    }
                                    if (!in_array($filter_entity, $app_filters_array[$filters])) {
                                        $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                    }
                                }
                            }
                            /* <-- Filter based on App Setting ends  here --> */
                            if (in_array($key, $filter_attribute)) {
                                $value = trim($value);
                                $valueforarray = str_replace(' ', '_', $value);
                                if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                    $pin_name = $map_saved_pins[$key][$valueforarray];
                                } else
                                if (!in_array($valueforarray, $filter_exist_array)) {
                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val . '.png';
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1.png';
                                    }
                                    $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                                } else {
                                    if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                        $pin_name = $pin_exist_for_cat[$valueforarray];
                                    }
                                }
                                $record_array = array_merge($record_array, array('pin' => $pin_name));
                            }
                            $value_attribute = html_entity_decode($value);
                            $value_attribute = str_replace('&', 'and', $value_attribute);
                            $key = trim($key);
                            $value_attribute = trim($value_attribute);
                            $record_array = array_merge($record_array, array($key => $value_attribute));
                        }
                        $record_array = array_merge($record_array, array('location' => $location));
                        $record_array = array_merge($record_array, array('id' => $result_id));
                        $record_array = array_merge($record_array, array('created_datetime' => $date));
                        if (!empty($location)) {
                            $record_array_final_filter[] = $record_array;
                        }
                    }
                }
                $data['app_filters_array'] = $app_filters_array;
                /** filter end hrer */
                $final_filter_sorted = array();
                /** sorting array based on key* */
                foreach ($posted_filters as $key_sorter => $posts) {
                    $final_filter_sorted[$key_sorter] = $app_filters_array[$key_sorter];
                }
                $data['app_filters_array'] = $final_filter_sorted;
                $town_array[] = asort($town_array);
                array_pop($town_array);
                $data['town'] = $town_array;
                $district_list = $this->form_results_model->get_distinct_district($slug);
                $data['district_list'] = $district_list;
                $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
                $data['sent_by_list'] = $sent_by_list;
                $uc_array[] = asort($uc_array);
                array_pop($uc_array);
                $data['uc'] = $uc_array;
                $form_list_filter = array();
                foreach ($form_list_posted as $form_entity) {
                    $form_list_filter[] = array('form_id' => $form_entity, 'table_name' => 'zform_' . $form_entity);
                }
                $results_comined_posted = array();
                $record_array_final = array();
                foreach ($form_list_filter as $form_entity) {
                    $table_name = $form_entity['table_name'];
                    $results = $this->form_results_model->get_map_data_paginated_posted($table_name, $to_date, $from_date, $town_filter = null, $posted_filters, $search_text, $login_district, $dynamic_filters);
                    //$results = $this->form_results_model->get_form_results_for_map($forms_list, $to_date, $from_date, $town_filter = null, $posted_filters, $search_text, $login_district);
                    $results_comined_posted = array_merge($results_comined_posted, $results);
                }
                $filter_exist_array = array();
                $pin_exist_for_cat = array();
                $col_pin = 0;
                $exist_alpha = array();
                foreach ($results_comined_posted as $k => $v) {
                    $record_array = array();
                    foreach ($v as $key => $value) {
                        if (!in_array($key, $heading_array)) {
                            $heading_array = array_merge($heading_array, array($key));
                        }

                        if ($key == 'created_datetime') {
                            $date = $value;
                        } else if ($key == 'form_id') {
                            $form_id = $value;
                        } else if ($key == 'location') {
                            $location = $value;
                        } else if ($key == 'id') {
                            $result_id = $value;
                        }

                        if (in_array($key, $filter_attribute)) {
                            $value = trim($value);
                            $valueforarray = str_replace(' ', '_', $value);
                            if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                $pin_name = $map_saved_pins[$key][$valueforarray];
                            } else
                            if (!in_array($valueforarray, $filter_exist_array)) {

                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val . '.png';
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1.png';
                                }
                                $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                            } else {
                                if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                    $pin_name = $pin_exist_for_cat[$valueforarray];
                                }
                            }
                            $record_array = array_merge($record_array, array('pin' => $pin_name));
                        }
                        $value_attribute = html_entity_decode($value);
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        if (strpos($value_attribute, "\r\n") !== false) {
                            $value_attribute = str_replace("\r\n", '-', $value_attribute);
                        }
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        $key = trim($key);
                        $value_attribute = trim($value_attribute);
                        $record_array = array_merge($record_array, array($key => $value_attribute));
                    }

                    $record_array = array_merge($record_array, array('location' => $location));
                    $record_array = array_merge($record_array, array('id' => $result_id));
                    $record_array = array_merge($record_array, array('created_datetime' => $date));
                    if (!empty($location)) {
                        $record_array_final[] = $record_array;
                    }
                }
                $filter_options = '';
                $filter_result = get_map_view_settings($selected_form['app_id']);
                if (isset($filter_result->filters->$form_id)) {
                    $app_filter_list = $filter_result->filters->$form_id;
//                    $filter_options .= "<option value=''>Select One</option>";
                    if (!empty($app_filter_list)) {
                        foreach ($app_filter_list as $key => $val) {
                            $print_val = str_replace("_", " ", $val);
                            if ($changed_category == $val) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                            $filter_options .= "<option $selected value='$val'>$print_val</option>";
                        }
                    }
                }

                $data['filter_options'] = $filter_options;
                $all_visits_hidden = $this->input->post('all_visits_hidden');
                $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
                $data['all_visits_hidden'] = $all_visits_hidden;
                $data['headings'] = $heading_array;
                $data['form'] = $record_array_final;
                /**
                 * sorting based on filter attribute of the array data
                 * will called a helper class SortAssociativeArray WITH 
                 * its call back function call
                 */
                foreach ($filter_attribute as $filter_attribute_value) {
                    uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
                }

                $possible_filters_from_settings = $this->form_model->get_form_column_values($app_filter_list, $selected_form['id']);

                $data['possible_filters_from_settings'] = $possible_filters_from_settings;
                $data['dynamic_filters'] = $dynamic_filters;


                $map_view_settings = get_map_view_settings($slug);
                $data['filter'] = $changed_category;
                $data['app_id'] = $selected_form['app_id'];
                $selected_app = $this->app_model->get_app($selected_form['app_id']);
                $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
                $data['district_filter'] = (isset($map_view_settings->district_filter)) ? $map_view_settings->district_filter : '';
                $data['sent_by_filter'] = !empty($map_view_settings->sent_by_filter) ? $map_view_settings->sent_by_filter : '';
                $data['uc_filter'] = (isset($map_view_settings->uc_filter)) ? $map_view_settings->uc_filter : '';
                $data['map_type_filter'] = (isset($map_view_settings->map_type_filter)) ? $map_view_settings->map_type_filter : '';
                $data['zoom_level'] = (isset($map_view_settings->default_zoom_level)) ? $map_view_settings->default_zoom_level : '';
                $data['latitude'] = (isset($map_view_settings->default_latitude)) ? $map_view_settings->default_latitude : '';
                $data['longitude'] = (isset($map_view_settings->default_longitude)) ? $map_view_settings->default_longitude : '';
                $data['app_name'] = $selected_app['name'];
                $data['form_for_filter'] = $record_array_final_filter;
                $data['active_tab'] = 'app';
                $data['pageTitle'] = $selected_app['name'] . ' - Map View-' . PLATFORM_NAME;
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('form/map_view_1293');
                } else {
                    $this->load->view('form/map_view', $data);
                }
            } else {
                $view_list = array();
                foreach ($forms_list as $final_view) {
                    $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
                }
                $form_single_to_query = array();
                $form_single_to_query[] = array('form_id' => $forms_list[0]['form_id'], 'table_name' => 'zform_' . $forms_list[0]['form_id'], 'form_name' => $forms_list[0]['form_name']);
                $view_list = array_flip($view_list);
                $data['form_lists'] = $view_list;
                $data['form_list_selected'] = $form_single_to_query;
                $first_form_id = $forms_list[0]['form_id'];
                $data['form_id'] = $first_form_id;
                $map_saved_pins = get_map_pin_settings($first_form_id);
                $selected_form = $this->form_model->get_form($first_form_id);
                $data['form_name'] = $selected_form['name'];
                $data['app_id'] = $selected_form['app_id'];
                $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
                $app_filter_list = explode(',', (isset($app_settings['map_view_filters'])?$app_settings['map_view_filters']:""));
                $data['district_selected'] = "";
                /** Get Multiple form filtesr* */
                $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
                $filter_attribute = array();
                $form_html_multiple = array();
                foreach ($multiple_filters as $key => $value) {
                    array_push($filter_attribute, $value['filter']);
                    array_push($form_html_multiple, $value['description']);
                }
                if (!empty($multiple_filters)) {
                    $default_selected_category = $multiple_filters[0]['filter'];
                } else {
                    $default_selected_category = '';
                }
//                echo "<pre>";
//                print_r($multiple_filters);die;
                $data['default_selected_category'] = $default_selected_category;
                $data['form_html'] = $form_html_multiple;
                $data['filter_attribute'] = $filter_attribute;
                $data['boundaries'] = '';
                $data['all_visits_hidden'] = 0;
                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
                /** for categry listing* */
                $record_array_final_filter = array();
                $results_filer_main = $this->form_results_model->get_form_results_filters($form_single_to_query, $login_district);
                $app_filters_array = array();
                $town_array = array();
                $uc_array = array();
                $filter_exist_array = array();
                $pin_exist_for_cat = array();
                $col_pin = 0;
                $exist_alpha = array();
                foreach ($results_filer_main as $results_filer) {
                    foreach ($results_filer as $k => $v) {
                        $record_array = array();
                        foreach ($v as $key => $value) {

                            if ($key == 'created_datetime') {
                                $date = $value;
                            } else if ($key == 'form_id') {
                                $form_id = $value;
                            } else if ($key == 'location') {
                                $location = $value;
                            } else if ($key == 'id') {
                                $result_id = $value;
                            }
                            if ($key == 'town_name') {
                                $town_name = $value;
                                if ($town_name) {
                                    if (!in_array($town_name, $town_array)) {
                                        array_push($town_array, $town_name);
                                    }
                                }
                            }
                            if ($key == 'uc_name') {
                                $uc_name = $value;
                                if ($uc_name) {
                                    if (!in_array($uc_name, $uc_array)) {
                                        array_push($uc_array, $uc_name);
                                    }
                                }
                            }

                            if (!in_array($key, $heading_array)) {
                                $heading_array = array_merge($heading_array, array($key));
                            }
                            /* <-- Filter based on App Setting start here --> */
                            foreach ($app_filter_list as $filters) {
                                if (isset($v->$filters)) {
                                    $filter_entity = $v->$filters;
                                    if (!key_exists($filters, $app_filters_array)) {
                                        $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                    }
                                    if (!in_array($filter_entity, $app_filters_array[$filters])) {
                                        $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                    }
                                }
                            }
                            /* <-- Filter based on App Setting ends  here --> */
                            if (in_array($key, $filter_attribute)) {
                                $value = trim($value);
                                $valueforarray = str_replace(' ', '_', $value);

                                if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                    $pin_name = $map_saved_pins[$key][$valueforarray];
                                } else
                                if (!in_array($valueforarray, $filter_exist_array)) {

                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val . '.png';
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1.png';
                                    }
                                    $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                                } else {
                                    if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                        $pin_name = $pin_exist_for_cat[$valueforarray];
                                    }
                                }
                                $record_array = array_merge($record_array, array('pin' => $pin_name));
                            }
                            $value_attribute = html_entity_decode($value);
                            $value_attribute = str_replace('&', 'and', $value_attribute);
                            $key = trim($key);
                            $value_attribute = trim($value_attribute);
                            $record_array = array_merge($record_array, array($key => $value_attribute));
                        }
                        $record_array = array_merge($record_array, array('location' => $location));
                        $record_array = array_merge($record_array, array('id' => $result_id));
                        $record_array = array_merge($record_array, array('created_datetime' => $date));
                        if (!empty($location)) {
                            $record_array_final_filter[] = $record_array;
                        }
                    }
                }
                $data['app_filters_array'] = $app_filters_array;
                $data['selected_filters'] = array();
                $total_result = $this->form_results_model->return_total_record($form_single_to_query);
                $totalPages = ceil($total_result / $this->perMap);
                $data['totalPages'] = $totalPages;
                $town_array[] = asort($town_array);
                array_pop($town_array);
                $data['town'] = $town_array;
                $district_list = $this->form_results_model->get_distinct_district($slug);
                $data['district_list'] = $district_list;
                $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
                $data['sent_by_list'] = $sent_by_list;
                $uc_array[] = asort($uc_array);
                array_pop($uc_array);
                $data['uc'] = $uc_array;
                /**
                 * sorting based on filter attribute of the array data
                 * will called a helper class SortAssociativeArray WITH 
                 * its call back function call
                 */
                foreach ($filter_attribute as $filter_attribute_value) {
                    uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
                }
                $results_comined = array();
                $record_array_final = array();
                foreach ($form_single_to_query as $form_entity) {
                    $table_name = $form_entity['table_name'];
                    $results = $this->form_results_model->get_map_data_paginated($table_name);
                    $results_comined = array_merge($results_comined, $results);
                }
                $filter_exist_array = array();
                $pin_exist_for_cat = array();
                $col_pin = 0;
                $exist_alpha = array();
                foreach ($results_comined as $k => $v) {

                    $record_array = array();
                    foreach ($v as $key => $value) {

                        if (!in_array($key, $heading_array)) {
                            $heading_array = array_merge($heading_array, array($key));
                        }

                        if ($key == 'created_datetime') {
                            $date = $value;
                        } else if ($key == 'form_id') {
                            $form_id = $value;
                        } else if ($key == 'location') {
                            $location = $value;
                        } else if ($key == 'id') {
                            $result_id = $value;
                        }
                        if (in_array($key, $filter_attribute)) {
                            $value = trim($value);
                            $valueforarray = str_replace(' ', '_', $value);
                            if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                $pin_name = $map_saved_pins[$key][$valueforarray];
                            } else
                            if (!in_array($valueforarray, $filter_exist_array)) {

                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val . '.png';
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1.png';
                                }
                                $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                            } else {
                                if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                    $pin_name = $pin_exist_for_cat[$valueforarray];
                                }
                            }
                            $record_array = array_merge($record_array, array('pin' => $pin_name));
                        }
                        $value_attribute = html_entity_decode($value);
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        if (strpos($value_attribute, "\r\n") !== false) {
                            $value_attribute = str_replace("\r\n", '-', $value_attribute);
                        }
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        $key = trim($key);
                        $value_attribute = trim($value_attribute);
                        $record_array = array_merge($record_array, array($key => $value_attribute));
                    }

                    $record_array = array_merge($record_array, array('location' => $location));
                    $record_array = array_merge($record_array, array('id' => $result_id));
                    $record_array = array_merge($record_array, array('created_datetime' => $date));
                    if (!empty($location)) {
                        $record_array_final[] = $record_array;
                    }
                }
                $data['selected_form'] = $first_form_id;
                $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
                $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
                $town_list_array = array();
                foreach ($town_lists as $towns) {
                    if (!in_array($towns['town'], $town_list_array)) {
                        $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                    }
                }
                $filter_options = '';
                $filter_result = get_map_view_settings($selected_form['app_id']);
                if (isset($filter_result->filters->$first_form_id)) {
                    $app_filter_list = $filter_result->filters->$first_form_id;
//                    $filter_options .= "<option value=''>Select One</option>";
                    if (!empty($app_filter_list)) {
                        foreach ($app_filter_list as $key => $val) {
                            if ($default_selected_category == $val) {
                                $default_selected = 'selected';
                            } else {
                                $default_selected = '';
                            }
                            $print_val = str_replace("_", " ", $val);
                            $filter_options .= "<option value='$val' $default_selected>$print_val</option>";
                        }
                    }
                }


                $possible_filters_from_settings = $this->form_model->get_form_column_values($app_filter_list, $first_form_id);

                $data['possible_filters_from_settings'] = $possible_filters_from_settings;


                $data['filter_options'] = $filter_options;
                $data['filter'] = $selected_form['filter'];
                $data['app_id'] = $selected_form['app_id'];
                $selected_app = $this->app_model->get_app($selected_form['app_id']);
                $map_view_settings = get_map_view_settings($slug);
                $data['district_filter'] = !empty($map_view_settings->district_filter) ? $map_view_settings->district_filter : '';
                $data['sent_by_filter'] = !empty($map_view_settings->sent_by_filter) ? $map_view_settings->sent_by_filter : '';
                $data['uc_filter'] = !empty($map_view_settings->uc_filter) ? $map_view_settings->uc_filter : '';
                $data['map_type_filter'] = (isset($map_view_settings->map_type_filter)) ? $map_view_settings->map_type_filter : '';
                $data['view_type'] = !empty($app_settings['map_type']) ? $app_settings['map_type'] : '';
                $data['zoom_level'] = (isset($map_view_settings->default_zoom_level)) ? $map_view_settings->default_zoom_level : '';
                $data['latitude'] = (isset($map_view_settings->default_latitude)) ? $map_view_settings->default_latitude : '';
                $data['longitude'] = (isset($map_view_settings->default_longitude)) ? $map_view_settings->default_longitude : '';
                $data['app_name'] = $selected_app['name'];
                $data['town_filter'] = $town_list_array;
                $data['headings'] = $heading_array;
                $data['form'] = $record_array_final;
                $data['form_for_filter'] = $record_array_final_filter;
                $data['active_tab'] = 'app';
                $data['pageTitle'] = $selected_app['name'] . ' - Map View-' . PLATFORM_NAME;
                $this->load->view('templates/header', $data);
                if ($slug == 323) {
                    $this->load->view('form/map_building_tag');
                }
                else if ($slug == 1293) {
                    $this->load->view('form/map_view_1293');
                } else {
                    $this->load->view('form/map_view', $data);
                }
            }
        } else {
            redirect(base_url() . 'guest');
        }
    }

    //new 
    public function mapviewframe($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        /** multiple form handling system statrs * */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer.....* */
        $heading_array = array();
        /** In case of post filter form map view* */
        if ($this->input->post('form_id')) {
            $form_id_posted = $this->input->post('form_id');
            $changed_category = $this->input->post('changed_category');
            $selected_form = $this->form_model->get_form($form_id_posted);
            $app_id = $selected_form['app_id'];
            $map_saved_pins = get_map_pin_settings($form_id_posted);
            $posted_array_filter = array();
            $data_per_filter = array();
            $posted_filters = array();
            $app_settings = $this->app_model->get_app_settings($app_id);
            $app_filter_list = explode(',', (isset($app_settings['map_view_filters'])) ? $app_settings['map_view_filters'] : '');
            if (!empty($app_settings['map_view_filters'])) {
                foreach ($app_filter_list as $filters) {
                    $data_per_filter[] = $this->input->post($filters);
                    foreach ($data_per_filter as $datum) {
                        $final = array();
                        if (!empty($datum)) {
                            foreach ($datum as $inside) {
                                $final = array_merge($final, array($inside => $inside));
                            }
                        }
                    }
                    $posted_filters[$filters] = $final;
                }
                $data['selected_filters'] = $posted_filters;
            } else {
                $data['selected_filters'] = '';
            }
            $form_list_posted = $this->input->post('form_list');
            $search_text = $this->input->post('search_text');
            $data['search_text'] = $search_text;
            if ($search_text) {
                $search_text = mysql_real_escape_string($search_text);
                $search_text = str_replace(array('~', '<', '>', '$', '%', '|', '^', '*'), array(' '), $search_text);
                $search_text = str_replace('/', '\\\\/', $search_text);
                $search_text = trim($search_text);
            }
            if (!$form_list_posted) {
                redirect(base_url() . 'form/mapviewframe/' . $slug);
            }
            $view_list = array();
            $final_send = array();
            foreach ($forms_list as $final_view) {
                if (in_array($final_view['form_id'], $form_list_posted)) {
                    $final_send = array_merge($final_send, array($final_view['form_name'] => $final_view['form_id']));
                }
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $data['form_list_selected'] = $final_send;
            $data['form_id'] = $form_list_posted[0];
            $data['selected_form'] = $form_id_posted;
            $to_date = $this->input->post('filter_date_to');
            $from_date = $this->input->post('filter_date_from');
            $view_type = $this->input->post('view_type');
            $boundaries = $this->input->post('boundaries');
            $district_select = $this->input->post('district_select');
            $data['selected_date_to'] = $to_date;
            $data['selected_date_from'] = $from_date;
            $data['view_type'] = $view_type;
            $data['boundaries'] = $boundaries;
            $data['district_selected'] = $district_select;
            if (empty($to_date)) {
                $to_date = "2013-06-03";
                $data['selected_date_to'] = "";
            }
            if (empty($from_date)) {
                $from_date = "2099-06-03";
                $data['selected_date_from'] = "";
            }
            if (strtotime($to_date) > strtotime($from_date)) {
                $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                redirect(base_url() . 'form/mapviewframe/' . $slug);
            }
            $form_list_filter = array();
            foreach ($form_list_posted as $form_entity) {
                $form_list_filter[] = array('form_id' => $form_entity, 'table_name' => 'zform_' . $form_entity);
            }
            $total_result = $this->form_results_model->return_total_record_map_posted($form_list_filter, $to_date, $from_date);
            $totalPages = ceil($total_result / $this->perMap);
            $data['totalPages'] = $totalPages;
            $filter_date_map = $this->input->post('filter_date_map');
            $selected_form = $this->form_model->get_form($form_id_posted);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            /*             * Get Multiple form filters * */
            $filter_attribute = array();
            $form_html_multiple = array();
            $multiple_filters = $this->form_model->get_form_filters($form_list_filter);
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
                array_push($form_html_multiple, $value['description']);
            }
            $data['filter_attribute'] = $filter_attribute;
            $data['form_html'] = $form_html_multiple;
            $record_array_final = array();

            /** for categry listing * */
            $record_array_final_filter = array();
            $results_filer_main = $this->form_results_model->get_form_results_filters($form_list_filter);
            $app_filters_array = array();
            $town_array = array();
            $uc_array = array();
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_filer_main as $results_filer) {
                foreach ($results_filer as $k => $v) {
                    $record_array = array();
                    foreach ($v as $key => $value) {

                        if ($key == 'created_datetime') {
                            $date = $value;
                        } else if ($key == 'form_id') {
                            $form_id = $value;
                        } else if ($key == 'location') {
                            $location = $value;
                        } else if ($key == 'id') {
                            $result_id = $value;
                        }
                        if ($key == 'town_name') {
                            $town_name = $value;
                            if ($town_name) {
                                if (!in_array($town_name, $town_array)) {
                                    array_push($town_array, $town_name);
                                }
                            }
                        }
                        if ($key == 'uc_name') {
                            $uc_name = $value;
                            if ($uc_name) {
                                if (!in_array($uc_name, $uc_array)) {
                                    array_push($uc_array, $uc_name);
                                }
                            }
                        }

                        if (!in_array($key, $heading_array)) {
                            $heading_array = array_merge($heading_array, array($key));
                        }
                        /* <-- Filter based on App Setting start here --> */
                        foreach ($app_filter_list as $filters) {
                            if (isset($v->$filters)) {
                                $filter_entity = $v->$filters;
                                if (!key_exists($filters, $app_filters_array)) {
                                    $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                }
                                if (!in_array($filter_entity, $app_filters_array[$filters])) {
                                    $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                }
                            }
                        }
                        /* <-- Filter based on App Setting ends  here --> */
                        if (in_array($key, $filter_attribute)) {
                            $value = trim($value);
                            $valueforarray = str_replace(' ', '_', $value);
                            if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                $pin_name = $map_saved_pins[$key][$valueforarray];
                            } else
                            if (!in_array($valueforarray, $filter_exist_array)) {

                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val . '.png';
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1.png';
                                }
                                $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                            } else {
                                if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                    $pin_name = $pin_exist_for_cat[$valueforarray];
                                }
                            }
                            $record_array = array_merge($record_array, array('pin' => $pin_name));
                        }
                        $value_attribute = html_entity_decode($value);
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        $key = trim($key);
                        $value_attribute = trim($value_attribute);
                        $record_array = array_merge($record_array, array($key => $value_attribute));
                    }

                    $record_array = array_merge($record_array, array('location' => $location));
                    $record_array = array_merge($record_array, array('id' => $result_id));
                    $record_array = array_merge($record_array, array('created_datetime' => $date));
                    if (!empty($location)) {
                        $record_array_final_filter[] = $record_array;
                    }
                }
            }
            $data['app_filters_array'] = $app_filters_array;

            /** filter end hrer */
            $final_filter_sorted = array();
            /** sorting array based on key* */
            foreach ($posted_filters as $key_sorter => $posts) {
                $final_filter_sorted[$key_sorter] = $app_filters_array[$key_sorter];
            }
            $data['app_filters_array'] = $final_filter_sorted;
            $town_array[] = asort($town_array);
            array_pop($town_array);
            $data['town'] = $town_array;
            $district_list = $this->form_results_model->get_distinct_district($slug);
            $data['district_list'] = $district_list;
            $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
            $data['sent_by_list'] = $sent_by_list;
            $uc_array[] = asort($uc_array);
            array_pop($uc_array);
            $data['uc'] = $uc_array;
            $form_list_filter = array();
            foreach ($form_list_posted as $form_entity) {
                $form_list_filter[] = array('form_id' => $form_entity, 'table_name' => 'zform_' . $form_entity);
            }
            $results_comined_posted = array();
            $record_array_final = array();
            foreach ($form_list_filter as $form_entity) {
                $table_name = $form_entity['table_name'];
                $results = $this->form_results_model->get_map_data_paginated_posted($table_name, $to_date, $from_date, $town_filter = null, $posted_filters, $search_text);
                //$results = $this->form_results_model->get_form_results_for_map($forms_list, $to_date, $from_date, $town_filter = null, $posted_filters, $search_text, $login_district);
                $results_comined_posted = array_merge($results_comined_posted, $results);
            }
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_comined_posted as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {

                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    if (strpos($value_attribute, "\r\n") !== false) {
                        $value_attribute = str_replace("\r\n", '-', $value_attribute);
                    }
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final[] = $record_array;
                }
            }
            $all_visits_hidden = $this->input->post('all_visits_hidden');
            $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
            $data['all_visits_hidden'] = $all_visits_hidden;
            $data['headings'] = $heading_array;
            $data['form'] = $record_array_final;
            /**
             * sorting based on filter attribute of the array data
             * will called a helper class SortAssociativeArray WITH 
             * its call back function call
             */
            foreach ($filter_attribute as $filter_attribute_value) {
                uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
            }

            $data['filter'] = $changed_category;
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($selected_form['app_id']);
            $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
            $map_view_settings = get_map_view_settings($selected_form['app_id']);
            $data['district_filter'] = !empty($map_view_settings->district_filter) ? $map_view_settings->district_filter : '';
            $data['sent_by_filter'] = !empty($map_view_settings->sent_by_filter) ? $map_view_settings->sent_by_filter : '';
            $data['uc_filter'] = !empty($map_view_settings->uc_filter) ? $map_view_settings->uc_filter : '';
            $data['map_type_filter'] = (isset($map_view_settings->map_type_filter)) ? $map_view_settings->map_type_filter : '';
            $data['view_type'] = !empty($app_settings['map_type']) ? $app_settings['map_type'] : '';
            $data['zoom_level'] = (isset($map_view_settings->default_zoom_level)) ? $map_view_settings->default_zoom_level : '';
            $data['latitude'] = (isset($map_view_settings->default_latitude)) ? $map_view_settings->default_latitude : '';
            $data['longitude'] = (isset($map_view_settings->default_longitude)) ? $map_view_settings->default_longitude : '';
            $data['app_name'] = $selected_app['name'];
            $data['form_for_filter'] = $record_array_final_filter;
            $data['active_tab'] = 'app';
            $data['pageTitle'] = $selected_app['name'] . ' - Map View-' . PLATFORM_NAME;
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('form/map_view_frame', $data);
        } else {
            $view_list = array();
            foreach ($forms_list as $final_view) {
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $forms_list[0]['form_id'], 'table_name' => 'zform_' . $forms_list[0]['form_id'], 'form_name' => $forms_list[0]['form_name']);
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $data['form_list_selected'] = $form_single_to_query;
            $first_form_id = $forms_list[0]['form_id'];
            $data['form_id'] = $first_form_id;
            $map_saved_pins = get_map_pin_settings($first_form_id);
            $selected_form = $this->form_model->get_form($first_form_id);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
            $app_filter_list = explode(',', $app_settings['map_view_filters']);
            $data['district_selected'] = "";
            /** Get Multiple form filtesr* */
            $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
            $filter_attribute = array();
            $form_html_multiple = array();
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
                array_push($form_html_multiple, $value['description']);
            }
            $data['form_html'] = $form_html_multiple;
            $data['filter_attribute'] = $filter_attribute;
            $data['boundaries'] = '';
            $data['all_visits_hidden'] = 0;
            $login_district = '';
            /** for categry listing* */
            $record_array_final_filter = array();
            $results_filer_main = $this->form_results_model->get_form_results_filters($form_single_to_query, $login_district);
            $app_filters_array = array();
            $town_array = array();
            $uc_array = array();
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_filer_main as $results_filer) {
                foreach ($results_filer as $k => $v) {
                    $record_array = array();
                    foreach ($v as $key => $value) {
                        if ($key == 'created_datetime') {
                            $date = $value;
                        } else if ($key == 'form_id') {
                            $form_id = $value;
                        } else if ($key == 'location') {
                            $location = $value;
                        } else if ($key == 'id') {
                            $result_id = $value;
                        }
                        if ($key == 'town_name') {
                            $town_name = $value;
                            if ($town_name) {
                                if (!in_array($town_name, $town_array)) {
                                    array_push($town_array, $town_name);
                                }
                            }
                        }
                        if ($key == 'uc_name') {
                            $uc_name = $value;
                            if ($uc_name) {
                                if (!in_array($uc_name, $uc_array)) {
                                    array_push($uc_array, $uc_name);
                                }
                            }
                        }

                        if (!in_array($key, $heading_array)) {
                            $heading_array = array_merge($heading_array, array($key));
                        }
                        /* <-- Filter based on App Setting start here --> */
                        foreach ($app_filter_list as $filters) {
                            if (isset($v->$filters)) {
                                $filter_entity = $v->$filters;
                                if (!key_exists($filters, $app_filters_array)) {
                                    $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                }
                                if (!in_array($filter_entity, $app_filters_array[$filters])) {
                                    $app_filters_array[$filters][$filter_entity] = $filter_entity;
                                }
                            }
                        }
                        /* <-- Filter based on App Setting ends  here --> */
                        if (in_array($key, $filter_attribute)) {
                            $value = trim($value);
                            $valueforarray = str_replace(' ', '_', $value);
                            if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                                $pin_name = $map_saved_pins[$key][$valueforarray];
                            } else
                            if (!in_array($valueforarray, $filter_exist_array)) {

                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val . '.png';
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1.png';
                                }
                                $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                            } else {
                                if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                    $pin_name = $pin_exist_for_cat[$valueforarray];
                                }
                            }
                            $record_array = array_merge($record_array, array('pin' => $pin_name));
                        }
                        $value_attribute = html_entity_decode($value);
                        $value_attribute = str_replace('&', 'and', $value_attribute);
                        $key = trim($key);
                        $value_attribute = trim($value_attribute);
                        $record_array = array_merge($record_array, array($key => $value_attribute));
                    }
                    $record_array = array_merge($record_array, array('location' => $location));
                    $record_array = array_merge($record_array, array('id' => $result_id));
                    $record_array = array_merge($record_array, array('created_datetime' => $date));
                    if (!empty($location)) {
                        $record_array_final_filter[] = $record_array;
                    }
                }
            }
            $data['app_filters_array'] = $app_filters_array;
            $data['selected_filters'] = array();
            $total_result = $this->form_results_model->return_total_record($form_single_to_query);
            $totalPages = ceil($total_result / $this->perMap);
            $data['totalPages'] = $totalPages;
            $town_array[] = asort($town_array);
            array_pop($town_array);
            $data['town'] = $town_array;
            $district_list = $this->form_results_model->get_distinct_district($slug);
            $data['district_list'] = $district_list;
            $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
            $data['sent_by_list'] = $sent_by_list;
            $uc_array[] = asort($uc_array);
            array_pop($uc_array);
            $data['uc'] = $uc_array;
            /**
             * sorting based on filter attribute of the array data
             * will called a helper class SortAssociativeArray WITH 
             * its call back function call
             */
            foreach ($filter_attribute as $filter_attribute_value) {
                uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
            }
            $results_comined = array();
            $record_array_final = array();
            foreach ($form_single_to_query as $form_entity) {
                $table_name = $form_entity['table_name'];
                $results = $this->form_results_model->get_map_data_paginated($table_name);
                $results_comined = array_merge($results_comined, $results);
            }
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_comined as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    if (strpos($value_attribute, "\r\n") !== false) {
                        $value_attribute = str_replace("\r\n", '-', $value_attribute);
                    }
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final[] = $record_array;
                }
            }
            $data['selected_form'] = $first_form_id;
            $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
            $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
            $town_list_array = array();
            foreach ($town_lists as $towns) {
                if (!in_array($towns['town'], $town_list_array)) {
                    $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                }
            }
            $data['filter'] = $selected_form['filter'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($selected_form['app_id']);
            $map_view_settings = get_map_view_settings($selected_form['app_id']);
            $data['district_filter'] = !empty($map_view_settings->district_filter) ? $map_view_settings->district_filter : '';
            $data['sent_by_filter'] = !empty($map_view_settings->sent_by_filter) ? $map_view_settings->sent_by_filter : '';
            $data['uc_filter'] = !empty($map_view_settings->uc_filter) ? $map_view_settings->uc_filter : '';
            $data['map_type_filter'] = (isset($map_view_settings->map_type_filter)) ? $map_view_settings->map_type_filter : '';
            $data['view_type'] = !empty($app_settings['map_type']) ? $app_settings['map_type'] : '';
            $data['zoom_level'] = (isset($map_view_settings->default_zoom_level)) ? $map_view_settings->default_zoom_level : '';
            $data['latitude'] = (isset($map_view_settings->default_latitude)) ? $map_view_settings->default_latitude : '';
            $data['longitude'] = (isset($map_view_settings->default_longitude)) ? $map_view_settings->default_longitude : '';
            $data['app_name'] = $selected_app['name'];
            $data['town_filter'] = $town_list_array;
            $data['headings'] = $heading_array;
            $data['form'] = $record_array_final;
            $data['form_for_filter'] = $record_array_final_filter;
            $data['active_tab'] = 'app';
            $data['pageTitle'] = $selected_app['name'] . ' - Map View-' . PLATFORM_NAME;
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('form/map_view_frame', $data);
        }
    }

    /**
     * Inline method giving string of records
     * @param  $locations array of  all data having info about each single record
     * @param  $filter_attribute list of attributed based on data is parsed icons assigned
     * @param  $headings Heading list fetched from all results
     * @return  string A string concatinated with commas
     * @access Inline
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    private function getMapHtmlInfo($locations = array(), $headings = array(), $filter_attribute) {
        if (isset($locations[0]['form_id'])) {
            $form_id = $locations[0]['form_id'];
        } else {
            return;
        }
        $map_saved_pins = get_map_pin_settings($form_id);

        $final = '';
        $filter_exist_array = array();
        $exist_alpha = array();
        if (count($locations)) {
            $column_number = 0;
            $only_once_category = array();
            $pin_exist_for_cat = array();
            $searched_filter_attribute = array();

            foreach ($filter_attribute as $filter_attribute_value) {
                if (!in_array($filter_attribute_value, $searched_filter_attribute)) {

                    foreach ($locations as $form_item) {
                        if (isset($form_item[$filter_attribute_value])) {
                            $category_name = (!empty($form_item[$filter_attribute_value])) ? $form_item[$filter_attribute_value] : "No " . ucfirst($filter_attribute_value);
                            $category_name = preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $category_name);
                            if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                                if (!in_array($form_item[$filter_attribute_value], $only_once_category)) {
                                    $column_number++;
                                    $only_once_category[] = $form_item[$filter_attribute_value];
                                }
                                $valueforarray = $form_item[$filter_attribute_value];
                                $valueforarray1 = str_replace(' ', '_', $valueforarray);
                                if (isset($map_saved_pins[$filter_attribute_value][$valueforarray1]) && $map_saved_pins[$filter_attribute_value][$valueforarray1] != '') {
                                    $pin_name = $map_saved_pins[$filter_attribute_value][$valueforarray1];
                                } else
                                if (!in_array($valueforarray, $filter_exist_array)) {
                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val . '.png';
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1.png';
                                    }
                                    $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                                } else {

                                    if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                        $pin_name = $pin_exist_for_cat[$valueforarray];
                                    }
                                }
                                if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name)) {
                                    $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                                } else {
                                    $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name;
                                }
                            } else {
                                $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                            }
                            $location = explode(',', $form_item['location']);
                            $form_id = $form_item['form_id'];
                            $map_data = '';
                            $total_headings = count($headings);
                            $image_row = '';
                            $data_row = '';
                            $datetime_row = '';
                            $date = '';
                            $form_result_id = '';
                            $info_window = '';
                            for ($i = 0; $i < $total_headings; $i++) {
                                if (!empty($form_item[$headings[$i]])) {
                                    //$info_window .= $headings[$i]."=>";
                                    if ($headings[$i] == 'is_take_picture') {}
                                    else if ($headings[$i] == 'id') {
                                        $query_img = "select image from zform_images where form_id='".$form_id."' AND zform_result_id ='".$form_item[$headings[$i]]."'";
                                        $image_info = $this->db->query($query_img)->result_array();
                                        if(!empty($image_info)){
                                            $path = get_image_path($image_info[0]['image']);
                                            $image_row .= "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-" . date('Y') . " - " . PLATFORM_NAME . " <br>By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
                                        }
                                    }
                                    else if ($headings[$i] == 'image') {
                                        $path = get_image_path($form_item[$headings[$i]][0]['image']);
                                        $image_row .= "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-" . date('Y') . " - " . PLATFORM_NAME . " <br>By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
                                    } else if ($headings[$i] == 'created_datetime') {
                                        $datetime_row .='<tr><td><b>DATE : </b></td><td>' . date('Y-m-d', strtotime($form_item[$headings[$i]])) . '</td></tr><tr><td><b>TIME : </b></td><td>' . date('H:i:s', strtotime($form_item[$headings[$i]])) . '</td></tr>';
                                    } else {
                                        $map_data .= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $headings[$i]) . ' : ' . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$headings[$i]]) . '<br>\n';
                                        if($form_id == 551){
                                            $array_col = array('BUILDING NAME','NAME OF DIVISION','NAME OF MCS','BUILDING TYPE','ACTIVITY DATETIME');
                                            if(in_array(preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($headings[$i]))),$array_col))
                                                $data_row .= "<tr><td><b>" . preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($headings[$i]))) . " : </b></td><td>" . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', strtoupper($form_item[$headings[$i]])) . "</td></tr>";
                                        }
                                        $id = $form_item['id'];
                                    }
                                }
                            }
                            $info_window = "<table>".$image_row.$data_row."</table>";
                            $marker_date = $form_item['created_datetime'];
                            $final .='["' . $location[0] . '","' . $location[1] . '","' . $form_id . '","' . $icon_filename . '","' . $id . '","' . $category_name . '","' . $marker_date . '","'.$info_window.'"] ,';
                        }
                    }

                    $searched_filter_attribute[] = $filter_attribute_value;
                }
            }
            $final = substr($final, 0, -1);
        } else {
            $final .= '[]';
        }
        return $final;
    }

    /**
     *  Get html information for single marker reocrd on mapview
     * @param  $locations array of  all data having info about each single record
     * @param  $filter_attribute list of attributed based on data is parsed icons assigned
     * @param  $headings Heading list fetched from all results
     * @return  string A string concatinated with commas
     * @access Inline
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    private function getMapHtmlInfoSingle($locations = array(), $headings = array(), $filter_attribute) {
        $final = '';
        if (count($locations)) {
            foreach ($filter_attribute as $filter_attribute_value) {
                foreach ($locations as $form_item) {
                    $category_name = (!empty($form_item[$filter_attribute_value])) ? $form_item[$filter_attribute_value] : str_replace('_', " ", ucfirst($filter_attribute_value));
                    $pin_name = (!empty($form_item[$filter_attribute_value])) ? substr($form_item[$filter_attribute_value], 0, 1) . '1' : "all_visit";
                    if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name)) {
                        $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                    } else {
                        $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name;
                    }
                    $location = explode(',', $form_item['location']);
                    $map_data = '';
                    $total_headings = count($headings);
                    $html_layout = "<table>";
                    $image_row = '';
                    $data_row = '';
                    $datetime_row = '';
                    $date = '';
                    $form_result_id = '';
                    for ($i = 0; $i < $total_headings; $i++) {
                        if (!empty($form_item[$headings[$i]])) {
                            if ($headings[$i] == 'is_take_picture') {
                                
                            } else if ($headings[$i] == 'image') {
                                $path = $form_item[$headings[$i]][0]['image'];
                                $image_row = "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-" . date('Y') . " - " . PLATFORM_NAME . " <br>By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
                            } else if ($headings[$i] == 'created_datetime') {
                                $datetime_row .='<tr><td><b>DATE : </b></td><td>' . date('Y-m-d', strtotime($form_item[$headings[$i]])) . '</td></tr><tr><td><b>TIME : </b></td><td>' . date('H:i:s', strtotime($form_item[$headings[$i]])) . '</td></tr>';
                            } else {
                                $map_data .= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $headings[$i]) . ' : ' . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$headings[$i]]) . '<br>\n';
                                $data_row .= "<tr><td><b>" . preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($headings[$i]))) . " : </b></td><td>" . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', strtoupper($form_item[$headings[$i]])) . "</td></tr>";
                                $id = $form_item['id'];
                            }
                        }
                    }
                    $html_layout = $html_layout . $image_row . $data_row . $datetime_row;
                    $html_layout .= "</table>";
                    $final .='["' . $location[0] . '","' . $location[1] . '","' . $html_layout . '","' . $icon_filename . '","' . $id . '","' . $category_name . '"] ,';
                }
            }
            $final = substr($final, 0, -1);
        } else {
            $final .= '[]';
        }
        return $final;
    }

    /**
     *  Update form on form builder 
     * @return void
     * @param  $slug form id
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function update($slug) {
        if ($this->session->userdata('logged_in')) {
            $history_id = 0;
            if (strpos($slug, 'history')) {
                $slug_str = explode('_', $slug);
                $form_id = $slug_str[0];
                $history_id = $slug_str[2];
            } else {
                $form_id = $slug;
            }
            $data['history_id'] = $history_id;
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            $data['security_key'] = $selected_form['security_key'];
            $request_app = $this->app_model->get_app($app_id);
            $app_general_setting = get_app_general_settings($app_id);
            $upgrade_from_google_play = 0;
            if (isset($app_general_setting->upgrade_from_google_play) && $app_general_setting->upgrade_from_google_play == 1) {
                $upgrade_from_google_play = 1;
            }
            $data['upgrade_from_google_play'] = $upgrade_from_google_play;
            
            $location_required = 1;
            if (isset($app_general_setting->location_required) && $app_general_setting->location_required == 0) {
                $location_required = 0;
            }
            $data['location_required'] = $location_required;
            
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
            $settings_exist = get_app_general_settings($app_id); //$this->app_model->get_app_settings($app_id);
            //print_r($app_general_setting);
            $data['app_settings'] = $settings_exist;
            $data['filter'] = $selected_form['filter'];
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
            //History listing
            $history_list = $this->form_model->get_form_history($form_id, $view_id);
            $data['history_list'] = $history_list;
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
            $selected_form = $this->form_model->get_form($form_id, $view_id);
            if ($view_id) {
                if (isset($selected_form['fvid']) && !empty($selected_form['fvid'])) {
                    $data['description'] = $selected_form['fv_description'];
                } else {
                    redirect(base_url() ."app-landing-page/".$app_id);
                }
                $data['post_url'] = $selected_form['fv_post_url'];
            } else {
                $data['description'] = $selected_form['description'];
                $data['post_url'] = $selected_form['post_url'];
            }
            if ($history_id) {
                $history_rec = $this->form_model->get_form_history($form_id, $view_id, $history_id);
                $data['description'] = $history_rec['description'];
            }
            $app = $this->app_model->get_app($app_id);
            $data['app_id'] = $app_id;
            $data['form_id'] = $form_id;
            $data['row_key'] = $selected_form['row_key'];
            $data['app_name'] = $app['name'];
            $data['form_name'] = $selected_form['name'];
            $data['form_icon'] = $selected_form['icon'];
            $data['view_id'] = $view_id;
            $app_id = $selected_form['app_id'];
            if (!$this->acl->hasPermission('form', 'edit')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $data['active_tab'] = 'form_update';
            $data['pageTitle'] = $app['name'] . '-' . $selected_form['name'] . ' Edit-' . PLATFORM_NAME;
            $this->load->view('templates/form_builder_header', $data);
            $this->load->view('form/update', $data);
            $this->load->view('templates/footer', $data);
        } else {
            redirect(base_url() . 'guest');
        }
    }

    /**
     * app builder for public or non-login users
     * @param type $slug
     * @author zahid nadeem <zahidiubb@yahoo.com>
     */
    public function appbuilder($slug) {
        if ($this->session->userdata('logged_in')) {
            redirect(base_url() . 'apps');
        }
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $this->form_validation->set_rules('app_name', 'App Name', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('status' => '2'));
                exit;
            } else {
                $session_id = $this->session->userdata['session_id'];
                //remove all record against this session id
                $this->db->delete('app_temp', array('session_id' => $session_id));
                $app_name = $this->input->post('app_name');
                $form_name = $app_name;
                $description = $this->input->post('htmldesc');

                $imgContent = '';
                $imgContent = $this->input->post('file_app');
                if ($imgContent != 'undefined') {
                    $imgContent = str_replace('data:image/png;base64,', '', $imgContent);
                    $imgContent = str_replace(' ', '+', $imgContent);
                    $imgContent = base64_decode($imgContent);
                } else {
                    $imgContent = '';
                }
                $full_description = $this->get_full_description('', '');
                $app_data = array(
                    'session_id' => $session_id,
                    'name' => $app_name,
                    'description' => '',
                    'full_description' => $full_description,
                    'icon' => $imgContent,
                );
                $this->db->insert('app_temp', $app_data);
                $app_temp_id = $this->db->insert_id();
                $imgContentform = '';
                $imgContentform = $this->input->post('file_form');
                if ($imgContentform != 'undefined') {
                    $imgContentform = str_replace('data:image/png;base64,', '', $imgContentform);
                    $imgContentform = str_replace(' ', '+', $imgContentform);
                    $imgContentform = base64_decode($imgContentform);
                } else {
                    $imgContentform = '';
                }
                $full_description = $this->get_full_description($description, $app_temp_id);
                $form_data = array(
                    'app_temp_id' => $app_temp_id,
                    'name' => $form_name,
                    'description' => $description,
                    'full_description' => $full_description,
                    'icon' => $imgContentform,
                );
                $this->db->insert('form_temp', $form_data);
                //if user not login
                if ($this->session->userdata('logged_in')) {
                    if (!$this->acl->hasSuperAdmin()) {
                        $data = array();
                        $session_data = $this->session->userdata('logged_in');
                        session_to_page($session_data, $data);
                        addAppFromSession($data['login_department_id'],$data['login_user_id']);
                    }
                    // form saved and user is logged in then rediirect to home index
                    echo json_encode(array('status' => '1'));
                    exit;
                } else {
                    //case when user is not logged in open color box
                    echo json_encode(array('status' => '3'));
                    exit;
                }
            }
        } else {
            $data['active_tab'] = 'app_form_build_public';
            $this->load->view('templates/form_builder_header_public');
            $this->load->view('form/app_builder');
            $this->load->view('templates/footer', $data);
        }
    }

    /**
     * add forms to form builder
     * @param type $slug application id
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function add($slug) {
        if ($this->session->userdata('logged_in')) {
            $app_id = $slug;
            $app = $this->app_model->get_app($app_id);
            $data['app_id'] = $app_id;
            $data['app_name'] = $app['name'];
            if (!$this->acl->hasPermission('form', 'add')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($this->input->post('form_name')) {
                $formName = trim($this->input->post('form_name'));
                $appId = $this->input->post('app_id');
                $description = 'Form is empty...';
                $full_description = $this->get_full_description($description, $app_id);
                $rand_key = random_string('alnum', 10);
                $data = array(
                    'app_id' => $app_id,
                    'name' => $formName,
                    'description' => '',
                    'next' => '',
                    'full_description' => $full_description,
                    'security_key' => $rand_key,
                    'filter' => 'version_name',
                    'possible_filters' => 'version_name'
                );
                $this->db->insert('form', $data);
                $form_id = $this->db->insert_id();
                updateDataBase($form_id, "");
                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'insert', 'description' => 'add new form', 'after' => json_encode($data));
                addlog($logary);
                //Get last inserted form id
                $forms_by_app = $this->form_model->get_form_by_app($app_id);
                $total_forms = count($forms_by_app);
                $iconName = 'formicon_' . $form_id . '.png';
                $abs_path = './assets/images/data/form_icons/' . $app_id . '/';
                $old = umask(0);
                @mkdir($abs_path, 0777);
                umask($old);
                if ($_FILES['userfile_addform']['name'] != '') {
                    //upload form icon
                   
                    $config['upload_path'] = $abs_path;
                    $config['file_name'] = $iconName;
                    $config['overwrite'] = TRUE;
                    $config["allowed_types"] = 'png';
                    $config["max_size"] = 1024;
//                    $config["max_width"] = 400;
//                    $config["max_height"] = 400;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('userfile_addform')) {

                        $this->data['error'] = $this->upload->display_errors();
                        $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your application.', 'type' => 'warning'));
                    } else {
                        //success
                        $imageData = $this->upload->data();
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
                    $from_path = FORM_IMG_DISPLAY_PATH . '../form_icons/default_' . $total_forms . '.png';
                    
                    @copy($from_path,$abs_path.$iconName);
                   // file_put_contents($abs_path . $iconName, file_get_contents($from_path));
                }
                //create file name which transfer with android application
                $file_name_html = "form_$form_id.html";
                $change_next = array(
                    'next' => $file_name_html,
                    'icon' => $iconName
                );
                $this->db->where('id', $form_id);
                $this->db->update('form', $change_next);
                $this->session->set_flashdata('validate', array('message' => 'New form added successfully.', 'type' => 'success'));
                redirect(base_url() . 'app-form/' . $form_id);
            }

            $data['active_tab'] = 'app';
            $data['pageTitle'] = 'Add Form -' . PLATFORM_NAME;
            $this->load->view('form/add', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    

    function check_form_name_availability() {
        $form_name = $this->input->post('form_name');
        $app_id = $this->input->post('app_id');
        if ($this->form_model->form_already_exist($form_name, $app_id)) {
            $jsone_array = array(
                'status' => '', //No availability
            );
        } else {
            $jsone_array = array(
                'status' => 'true', //Name availabile
            );
        }
        echo json_encode($jsone_array['status']);
    }

    /**
     * Move a form on form view
     * @param type $slug form id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function movetoview($slug) {
        if ($this->session->userdata('logged_in')) {
            if($this->input->post()){
                $form_id = $slug;//form_id which want to move to view
                
                $view_id = $this->input->post('view_id');//view_id where want to move the selected form
                $selected_form = $this->form_model->get_form($form_id,$view_id);
                $app_id = $selected_form['app_id'];
                $view_id_session = 0;
                if ($this->session->userdata('view_session')) {
                    $session_view_data = $this->session->userdata('view_session');
                    if ($session_view_data['app_id'] != $app_id) {
                        $view_id_session = 0;
                    } else {
                        $view_id_session = $session_view_data['view_id'];
                    }
                }
                $file_name_html = 'form_'.$form_id.'.html';
                $abs_path = './assets/images/data/form_icons/' . $app_id;
                if($view_id_session == 0)
                {
                    //move form to view
                    $form_move_data = array(
                        'form_id' => $form_id,
                        'view_id' => $view_id,
                        'description' => $selected_form['description'],
                        'full_description' => $selected_form['full_description'],
                        'post_url' => $selected_form['post_url']
                    );
                    //file_write()
                    file_write($abs_path.'/'.$view_id.'_'.$file_name_html,$selected_form['full_description']);
                    
                }
                else{
                    //move view to view
                    $selected_view_form = $this->form_model->get_form($form_id,$view_id_session);
                    $form_move_data = array(
                        'form_id' => $form_id,
                        'view_id' => $view_id,
                        'description' => $selected_view_form['fv_description'],
                        'full_description' => $selected_view_form['fv_full_description'],
                        'post_url' => $selected_view_form['fv_post_url']
                    );
                    file_write($abs_path.'/'.$view_id.'_'.$file_name_html,$selected_view_form['fv_full_description']);
                }
                
                
                if(isset($selected_form['fvid']) && !empty($selected_form['fvid'])) {
                    $this->db->where('id', $selected_form['fvid']);
                    $this->db->update('form_views', $form_move_data);
                }
                else{
                    $this->db->insert('form_views', $form_move_data);
                }
                
                $this->session->set_flashdata('validate', array('message' => 'Active form copied to your required view successfully', 'type' => 'success'));
                redirect(base_url() . 'app-form/' . $form_id);
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    /**
     * Copy a form on form builder
     * @param type $slug form id
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function copy($slug) {
        if ($this->session->userdata('logged_in')) {
            if($this->input->post()){
                $form_id = $slug;//form_id which want to copy
                $app_id = $this->input->post('app_id_popup');//app_id where want to copy the selected form
                
                
                
                $selected_form = $this->form_model->get_form($form_id);
                $allforms_patern = $this->form_model->get_form_by_app($app_id);
                $i = 1;
                $copied_form_name = $selected_form['name'] . '(' . $i . ')';
                foreach ($allforms_patern as $fom) {
                    if ($this->form_model->form_already_exist($copied_form_name, $app_id)) {
                        $i++;
                        $copied_form_name = $selected_form['name'] . '(' . $i . ')';
                    }
                }
                $rand_key = random_string('alnum', 10);
                $data = array(
                    'app_id' => $app_id,
                    'name' => $selected_form['name'],
                    'icon' => $selected_form['icon'],
                    'description' => $selected_form['description'],
                    'full_description' => $selected_form['full_description'],
                    'filter' => $selected_form['filter'],
                    'possible_filters' => $selected_form['possible_filters'],
                    'next' => $selected_form['next'],
                    'is_deleted' => $selected_form['is_deleted'],
                    'security_key' => $rand_key,
                );
                $this->db->insert('form', $data);
                $form_id_new = $this->db->insert_id();
                $old_file = './assets/images/data/form_icons/' . $selected_form['app_id'] . '/formicon_' . $slug . '.png';
                $new_file = './assets/images/data/form_icons/' . $app_id . '/formicon_' . $form_id_new . '.png';
                $old = umask(0);
                @mkdir('./assets/images/data/form_icons/' . $app_id, 0777);
                umask($old);
                copy($old_file, $new_file);
                //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                $logary = array('action' => 'insert', 'description' => 'copy and add new form', 'after' => json_encode($data));
                addlog($logary);
                $file_name_html = "form_$form_id_new.html";
                $change_next = array(
                    'next' => $file_name_html,
                    'name' => $copied_form_name,
                    'icon' => 'formicon_' . $form_id_new . '.png'
                );
                $this->db->where('id', $form_id_new);
                $this->db->update('form', $change_next);
                updateDataBase($form_id_new, $selected_form['description']);
                $abs_path = './assets/images/data/form_icons/' . $app_id;
                file_write($abs_path.'/'.$file_name_html, $selected_form['full_description']);
                $this->session->set_flashdata('validate', array('message' => 'Form Copied successfully. Please click on save button for saving configuration', 'type' => 'success'));
                redirect(base_url() . 'app-form/' . $form_id_new);
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }
    
    /**
     * 
     * @param type $slug
     * @author Zahid Nadeem <zahidiubb@yahoo.com>
     */
    public function copypopup($slug) {
        if ($this->session->userdata('logged_in')) {
            $this->load->library('form_validation');
            $batch = array();
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $department_id = $session_data['login_department_id'];
            
            
            $parameter = explode('_', $slug);
            $app_id = $parameter[0];
            $form_id = $parameter[1];
                        
            $data['app_id'] = $app_id;
            $data['form_id'] = $form_id;
            if (!$this->acl->hasPermission('form', 'add')) {
                echo '<label style="font-size: 20px; color: red; position: absolute; padding: 53px;">You have no permission to copy this form</label>';
                exit;
            }
            $departments = $this->department_model->get_department();
            if ($this->acl->hasSuperAdmin()) {
                $dep[''] = 'Select';
                foreach ($departments as $row) {
                    $dep[$row['id']] = $row['name'];
                }
                $data['departments'] = $dep;
            }
            
            if ($this->acl->hasSuperAdmin()) {
                $data['app_list'] = array();
            } else {
                $data['app_list'] = $this->app_model->get_app_by_user($data['login_user_id']);
            }
            
            $app = $this->app_model->get_app($app_id);

            $data['batch'] = $batch;
            $data['active_tab'] = 'copy_form';
            $data['pageTitle'] = 'Copy Form -' . PLATFORM_NAME;
            $this->load->view('form/copy_popup', $data);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function edit($slug=null) {
        if ($this->session->userdata('logged_in')) {
            $form_id = $slug;
            if ($this->input->post() && $slug !== '') {
                $selected_form = $this->form_model->get_form($form_id);
                $app_id = $selected_form['app_id'];
                $formName = trim($this->input->post('form_name'));
                if ($formName == '') {
                    $this->session->set_flashdata('validate', array('message' => 'Form name should not be empty', 'type' => 'error'));
                    redirect(base_url() . 'app-form/' . $form_id);
                }
                if ($this->form_model->form_already_exist($formName, $app_id, $form_id)) {
                    $this->session->set_flashdata('validate', array('message' => 'Form already exist on this application', 'type' => 'error'));
                    redirect(base_url() . 'app-form/' . $form_id);
                }
                //upload form icon
                $abs_path = './assets/images/data/form_icons/' . $app_id;
                $old = umask(0);
                @mkdir($abs_path, 0777);
                umask($old);
                $iconName = 'formicon_' . $form_id . '.png';
                if ($_FILES['userfile']['name'] != '') {
                
//                $config['upload_path'] = $abs_path;
//                $config['file_name'] = $iconName;
//                $config['overwrite'] = TRUE;
//                $config["allowed_types"] = 'png';
//                $config["max_size"] = 1024;
//                $config["max_width"] = 400;
//                $config["max_height"] = 400;
//                $this->load->library('upload', $config);
//
//                if (!$this->upload->do_upload()) {
//                    $iconName = $selected_form['icon'];
//                    $this->data['error'] = $this->upload->display_errors();
//                    $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your application.', 'type' => 'warning'));
//                } else {
//                    //success
//                }

                $config['upload_path'] = $abs_path;
                $config['file_name'] = $iconName;
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'png';
                $config["max_size"] = 1024;
//                    $config["max_width"] = 400;
//                    $config["max_height"] = 400;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('userfile')) {
//                    echo "<pre>";
//                    print_r($this->upload->display_errors());die;
                    $this->data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors() . ', Default icon has been embeded with your application.', 'type' => 'warning'));
                } else {
                    //success
                    $imageData = $this->upload->data();
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



                $appId = $this->input->post('app_id');
                $file_name_html = "form_$form_id.html";
                if ($this->input->post('possible_filters') == 'version_name') {
                    $dataform = array(
                        'next' => $file_name_html,
                        'name' => $formName,
                        'icon' => $iconName,
                        'filter' => $this->input->post('possible_filters'),
                        'possible_filters' => $this->input->post('possible_filters')
                    );
                } else {
                    $dataform = array(
                        'next' => $file_name_html,
                        'name' => $formName,
                        'icon' => $iconName,
                        'possible_filters' => $this->input->post('possible_filters')
                    );
                }

                $this->db->where('id', $form_id);
                $this->db->update('form', $dataform);
                $view_id = 0;
                if ($this->session->userdata('view_session')) {
                    $session_view_data = $this->session->userdata('view_session');
                    $view_id = $session_view_data['view_id'];
                }
                $description = $this->input->post('htmldesc');
                $post_url = $this->input->post('post_url');
                $full_description = $this->get_full_description($description, $appId);
                if ($view_id) {
                    if ($this->app_users_model->get_form_views_existance($form_id, $view_id)) {
                        $dataview = array(
                            'description' => $description,
                            'full_description' => $full_description,
                            'post_url' => $post_url
                        );
                        $this->db->where('form_id', $form_id);
                        $this->db->where('view_id', $view_id);
                        $this->db->update('form_views', $dataview);

                        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                        $logary = array('action' => 'update', 'description' => 'edit form views', 'form_id' => $form_id, 'form_name' => $formName, 'app_id' => $appId);
                        addlog($logary);
                    } else {
                        $dataview = array(
                            'form_id' => $form_id,
                            'view_id' => $view_id,
                            'description' => $description,
                            'full_description' => $full_description,
                            'post_url' => $post_url
                        );
                        $this->db->insert('form_views', $dataview);
                        //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                        $logary = array('action' => 'insert', 'description' => 'add form views', 'form_id' => $form_id, 'form_name' => $formName, 'app_id' => $appId);
                        addlog($logary);
                    }
                    file_write($abs_path.'/'.$view_id.'_'.$file_name_html, $full_description);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                } else {
                    $filter = $this->input->post('filter');
                    if ($this->input->post('possible_filters') == 'version_name') {
                        $dataform = array(
                            'description' => $description,
                            'full_description' => $full_description,
                            'filter' => $this->input->post('possible_filters'),
                            'post_url' => $post_url,
                            'possible_filters' => $this->input->post('possible_filters')
                        );
                    } else {
                        $dataform = array(
                            'description' => $description,
                            'full_description' => $full_description,
                            'filter' => $filter,
                            'post_url' => $post_url,
                            'possible_filters' => $this->input->post('possible_filters')
                        );
                    }
                    $this->db->where('id', $form_id);
                    $this->db->update('form', $dataform);




                    $replace = array(
                            //remove tabs before and after HTML tags
                            '/\>[^\S ]+/s'   => '>',
                            '/[^\S ]+\</s'   => '<',
                            //shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
                            '/([\t ])+/s'  => ' ',
                            //remove leading and trailing spaces
                            '/^([\t ])+/m' => '',
                            '/([\t ])+$/m' => '',
                            // remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
                            '~//[a-zA-Z0-9 ]+$~m' => '',
                            //remove empty lines (sequence of line-end and white-space characters)
                            '/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
                            //remove empty lines (between HTML tags); cannot remove just any line-end characters because in inline JS they can matter!
                            '/\>[\r\n\t ]+\</s'    => '><',
                            //remove "empty" lines containing only JS's block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
                            '/}[\r\n\t ]+/s'  => '}',
                            '/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
                            //remove new-line after JS's function or condition start; join with next line
                            '/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
                            '/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
                            //remove new-line after JS's line end (only most obvious and safe cases)
                            '/\),[\r\n\t ]+/s'  => '),',
                            //remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs!
                            '~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s' => '$1$2=$3$4', //$1 and $4 insert first white-space character found before/after attribute
                        );
                        $full_description = preg_replace(array_keys($replace), array_values($replace), $full_description);



                    //$full_description=str_replace(array("\n","\r","\t"), "",$full_description);
                    file_write($abs_path.'/'.$file_name_html, $full_description);
                    //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
                    $logary = array('action' => 'update', 'description' => 'edit form', 'form_id' => $form_id, 'form_name' => $formName, 'app_id' => $appId);
                    addlog($logary);
                }
                $data_form_history = array(
                    'form_id' => $form_id,
                    'view_id' => $view_id,
                    'description' => $description,
                );
                $this->db->insert('form_history', $data_form_history);
                //Add form fields
                updateDataBase($form_id, $description);
                $this->change_installed_app_status($app_id);
                if ($this->input->post('is_edit') == '1') {
                    $this->session->set_flashdata('validate', array('message' => 'Android application created successfully.', 'type' => 'success'));
                    redirect(base_url() . 'app/createapk/' . $app_id);
                } else {
                    $this->session->set_flashdata('validate', array('message' => 'Form updated successfully.', 'type' => 'success'));
                    redirect(base_url() . 'app-form/' . $form_id);
                }
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function delete($slug) {
        if ($this->session->userdata('logged_in')) {
            $form_id = $slug;
            if (!$this->acl->hasPermission('form', 'delete')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $view_id = 0;
            if ($this->session->userdata('view_session')) {
                $session_view_data = $this->session->userdata('view_session');
                $view_id = $session_view_data['view_id'];
            }
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            if ($view_id) {
                $this->db->delete('form_views', array('form_id' => $form_id, 'view_id' => $view_id));
                //delete from views only
            } else {
                //delete orignal form and its views also
                $dataform = array(
                    'is_deleted' => '1'
                );
                $this->db->where('id', $form_id);
                $this->db->update('form', $dataform);
                $this->db->delete('form_views', array('form_id' => $form_id));
            }
            //remove landing page of app and app view
            //$this->remove_landing_page($app_id, $view_id, $form_id);
            $this->form_change($app_id);
            $this->change_installed_app_status($app_id);
            $this->session->set_flashdata('validate', array('message' => 'Form deleted successfully.', 'type' => 'success'));
            redirect(base_url() . 'app-landing-page/' . $app_id);
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    function remove_landing_page($app_id, $view_id, $form_id) {
        if ($view_id) {
            $dataapp = array(
                'description' => ''
            );
            $this->db->where(array('app_id' => $app_id, 'view_id' => $view_id));
            $this->db->update('app_views', $dataapp);
        } else {
            $dataapp = array(
                'description' => ''
            );
            $this->db->where('id', $app_id);
            $this->db->update('app', $dataapp);
            $this->db->where(array('app_id' => $app_id));
            $this->db->update('app_views', $dataapp);
        }
    }

    function form_change($app_id) {
        $allform = $this->form_model->get_form_by_app($app_id);
        $i = 0;
        foreach ($allform as $aform) {
            $i++;
            $string = "index$i.html";
            $change_next = array(
                'next' => $string
            );
            $this->db->where('id', $aform['form_id']);
            $this->db->update('form', $change_next);
        }
        $allform = $this->form_model->get_form_by_app($app_id);
        foreach ($allform as $aform) {

            $full_description = $this->get_full_description($aform['description'], $app_id);
            $data = array(
                'description' => $aform['description'],
                'full_description' => $full_description
            );
            $this->db->where('id', $aform['form_id']);
            $this->db->update('form', $data);
        }
    }

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

    function get_full_description($description, $app_id) {
        $css_embed = '';
        if ($app_id) {
            $app_general_setting = get_app_general_settings($app_id);
            if (isset($app_general_setting->app_language)) {
                $language = $app_general_setting->app_language;
                if ($language == 'urdu') {
                    $css_embed = '#form-preview{direction:rtl;}
                        input.css-checkbox[type="checkbox"] + label.css-label, input.css-checkbox[type="radio"] + label.css-label{background-position:right top;padding-left:0;padding-right:23px;}
                        .checkbox input.css-checkbox[type="checkbox"]:checked + label.css-label{background-position:right -18px;}.tbl_widget td {border: 1px solid gainsboro;}.field label{font-size:17px;}';
                }
            }
        }
        $full_description = '<!DOCTYPE html>
                            <html lang="en">
                            <head>
                            <meta charset="utf-8">
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <title>:: ' . PLATFORM_NAME . '</title>

                            <link href="bootstrap.min.css" rel="stylesheet">
                            <link href="common.css" rel="stylesheet">
                            <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                            <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
                            <!--[if lt IE 9]>
                                  <script src="html5shiv.min.js"></script>
                                  <script src="respond.min.js"></script>
                                <![endif]-->';

                                //if($app_id=="8" || $app_id==8)
                                //{
                                    $full_description=$full_description.'<link href="bootstrap.min.css" rel=stylesheet>
                                        <link href="common.css" rel=stylesheet> 

                                          <script type="text/javascript" src="jquerylatest.js"></script> 
                                         <script src="jquery-ui-autocomplete.js"></script>
                                         <script src="jquery.select-to-autocomplete.min.js"></script>
                                        
                                        <script type="text/javascript" src="jqui.js"></script>
                                        <script type="text/javascript" src="datebox.js"></script>
                                        <script type="text/javascript" src="dateformatting.js"></script>
                                          <script type="text/javascript" src="bs.js"></script>
                                        <script src="common.js"></script>
                                        ';
                                //}
                                //else{
                            //         $full_description=$full_description.'
                            // <script src="jquery.min.js"></script> 
                            // <script src="bootstrap.min.js"></script> 
                            // <script src="bootstrap.js"></script>
                            // <script src="jquery-2.0.2.min.js"></script> 
                            // <script src="jquery.js"></script>
                            // <script src="jquery-ui-autocomplete.js"></script>
                            // <script src="jquery.select-to-autocomplete.min.js"></script>
                            // <script src="jquery-ui.min.js"></script>        
                            // <script src="common.js"></script> ';
                                //}

 
                            $full_description=$full_description.'<style type="text/css" media="screen">
                                body {
                                  font-family: Arial, Verdana, sans-serif;
                                  font-size: 13px;
                                }
                                 .ui-autocomplete {
                                    padding: 0;
                                    list-style: none;
                                    background-color: #fff;
                                    width: 218px;
                                    border: 1px solid #B0BECA;
                                    max-height: 350px;
                                    overflow-y: scroll;
                                  }
                                  .ui-autocomplete li{
                                    border: 1px solid #B0BECA;
                                  }
                                  .ui-autocomplete .ui-menu-item a {
                                    border-top: 1px solid #B0BECA;
                                    display: block;
                                    padding: 4px 6px;
                                    color: #353D44;
                                    cursor: pointer;
                                  }
                                  .ui-autocomplete .ui-menu-item:first-child a {
                                    border-top: none;
                                  }
                                  .ui-autocomplete .ui-menu-item a.ui-state-hover {
                                    background-color: #D5E5F4;
                                    color: #161A1C;
                                  }
                                  .ui-autocomplete-input{
                                      opacity: 1 !important;
                                  }
                                  ' . $css_embed . '
                                      </style>
                            </head>
                            <body>
                                <div id="form-builder" class="container">
                                    ' . $description . '
                                </div>
                            </body>
                                    </html>';
        return $full_description;
    }

    /**
     *
     * Delete  a single form results data, soft delete
     * @param $form_result_id form_results id
     * @return void
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function delete_result() {
        $form_id = $this->input->post('form_id');
        $form_result_id = $this->input->post('result_id');
        $table_name = 'zform_' . $form_id;
        //$table_exist_bit = $this->form_results_model->check_table_exits($table_name);
        if (!is_table_exist($table_name)) {
            $this->session->set_flashdata('validate', array('message' => 'No table schema has been built againts this application', 'type' => 'warning'));
            redirect(base_url() . 'app');
        } else {
            $data = array(
                'is_deleted' => '1'
            );
            $this->db->where('id', $form_result_id);
            $this->db->update($table_name, $data);
        }
    }

    /*
     * Get Map partials based on category Name
     * @return void
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */

    public function getMapPartial() {
        $data = array();
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $form_id = $this->input->post('form_id');
            $heading_array = $this->get_heading_data($form_id, 1);
            $filter_attributes = $heading_array['filter_attribute'];
            $record = array();
            foreach ($filter_attributes as $filters) {
                echo $filters;
                $this->form_validation->set_rules($filters, $filters, 'trim|required|min_length[1]|xss_clean');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('validate', array('message' => 'Filter attributes cannot be empty', 'type' => 'warning'));
                redirect(base_url() . 'form/mapview/' . $form_id);
            } else {
                $lat = $this->input->post('Lat');
                $long = $this->input->post('Long');
                $location = $lat . "," . $long;
                $post = $this->input->post();
                if ($this->input->post('form_id')) {
                    unset($post['form_id']);
                }
                if ($this->input->post('Lat')) {
                    unset($post['Lat']);
                }
                if ($this->input->post('imei_no')) {
                    unset($post['imei_no']);
                    $imei_number = $this->input->post('imei_no');
                }
                if ($this->input->post('town_filter')) {
                    unset($post['town_filter']);
                }
                if ($this->input->post('Long')) {
                    unset($post['Long']);
                }
                $record = json_encode($post);
                $image_name = 'users_added' . time();

                //upload images
                $abs_path = './assets/images/data/form-data/';
                $old = umask(0);
                @mkdir($abs_path, 0777);
                umask($old);
                $iconName = $image_name . '.jpg';
                $config['upload_path'] = $abs_path;
                $config['file_name'] = $image_name;
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'jpg|jpeg';
                $config["max_size"] = 2048;
                $config["max_width"] = 1600;
                $config["max_height"] = 1600;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $this->data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('validate', array('message' => $this->upload->display_errors(), 'type' => 'warning'));
                } else {
//success   
                    $imei_number = (!empty($imei_number)) ? $imei_number : "";
                    $data = array(
                        'form_id' => $form_id,
                        'record' => $record,
                        'imei_no' => $imei_number,
                        'image' => $image_name . '.jpg',
                        'location' => $location
                    );
                    $this->db->insert('form_results', $data);
                    $this->session->set_flashdata('validate', array('message' => 'You have added a marker on map successfully', 'type' => 'success'));
                }
                redirect(base_url() . 'form/mapview/' . $form_id);
            }
        } else {
            $slug = $this->input->get('form_id');
            $slug_array = array();
            $slug_array = explode('-', $slug);
            $slug_id = $slug_array[count($slug_array) - 1];
            $slug = $slug_id;
            $form_id = $slug;
            $data['form_id'] = $form_id;
            $heading_array = $this->get_heading_data($data['form_id'], 1);
            $data['filter_attribute'] = $heading_array['filter_attribute'];
            $data['headings'] = $heading_array['headings'];
            $data['form_for_filter'] = $heading_array['form'];
            $data['lat'] = $this->input->get('lat');
            $data['long'] = $this->input->get('long');
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            $town_lists = $this->app_users_model->get_towns($app_id);
            $town_list_array = array();
            foreach ($town_lists as $towns) {
                if (!in_array($towns['town'], $town_list_array)) {
                    $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                }
            }
            $data['town_list_array'] = $town_list_array;
            $this->load->view('form/map_partial', $data);
        }
    }

    /**
     * Get IMEI Number based on form id and town_name
     * @param $form_id form id
     * @param $town_name town Name
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function getImeiNo($form_id, $town_name) {
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];
        $this->load->model('app_users_model');
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($this->app_users_model->get_imei($app_id, $town_name)));
    }

    /**
     * Form ajax based editing 
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function edit_form_result_ajax() {
        $post = $this->input->post();
        $form_result_id = $this->input->post("form_result_id");

        unset($post['form_result_id']);
        unset($post['image']);
        if ($this->input->post('image')) {
            unset($post['image']);
        }
        if ($this->input->post('form_id_hidden')) {
            unset($post['form_id_hidden']);
        }
        $json_result = json_encode($post);
        if ($this->input->post("image") != "") {
            $image = $this->input->post("image");
            $data = array(
                'image' => $image,
                'record' => $json_result,
            );
        } else {
            print_r($json_result);
            $data = array(
                'record' => $json_result,
            );
        }
        $this->db->where('id', $form_result_id);
        $this->db->update('form_results', $data);
    }

    /**
     * Load More Markers concpet on mapview page
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function moreMarker() {
        $selected_post_form_id = explode(',', $this->input->get('selected_form_id'));
        $form_list_filter = array();
        foreach ($selected_post_form_id as $list) {
            $form_list_filter[] = array('form_id' => $list, 'table_name' => 'zform_' . $list);
        }
        $map_saved_pins = get_map_pin_settings($selected_post_form_id);
        $page = $this->input->get('page');
        $to_date = $this->input->get('filter_date_to');
        $from_date = $this->input->get('filter_date_from');
        $town_filter = $this->input->get('town_filter');
        if (empty($to_date)) {
            $to_date = "2013-06-03";
            $data['selected_date_to'] = "";
        }
        if (empty($from_date)) {
            $from_date = "2099-06-03";
            $data['selected_date_from'] = "";
        }
        $heading_array = array();
        $filter_date_map = $this->input->post('filter_date_map');
        //Get Multiple form filtesr
        $multiple_filters = $this->form_model->get_form_filters($form_list_filter);
        $filter_attribute = array();
        foreach ($multiple_filters as $key => $value) {
            array_push($filter_attribute, $value['filter']);
        }
        $data['filter_attribute'] = $filter_attribute;
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $record_array_final = array();
        if (empty($town_filter)) {
            $town_filter = null;
        }
        /** for categry listing * */
        $record_array_final_filter = array();
        $results_filer_main = $this->form_results_model->get_form_results_filters($form_list_filter);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results_filer_main as $results_filer) {
            foreach ($results_filer as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final_filter[] = $record_array;
                }
            }
        }

        /**
         * sorting based on filter attribute of the array data
         * will called a helper class SortAssociativeArray WITH 
         * its call back function call
         */
        foreach ($filter_attribute as $filter_attribute_value) {
            uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
        }

        $only_once_category_icon = array();
        $column_number = 0;
        $icon_pair_array_final = array();
        foreach ($filter_attribute as $filter_attribute_value) {
            foreach ($record_array_final_filter as $form_item) {
                $icon_pair_array = array();
                if (isset($form_item[$filter_attribute_value]) 
                    && !empty($form_item[$filter_attribute_value])) {
                    $category = strtolower($form_item[$filter_attribute_value]);

                    if (!in_array($form_item[$filter_attribute_value], $only_once_category_icon)) {
                        $column_number++;
                        $only_once_category_icon[] = $form_item[$filter_attribute_value];

                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {

                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                        }
                        $icon_pair_array = array_merge($icon_pair_array, 
                                                       array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }
        $results = $this->form_results_model->
            get_map_data_load_more($form_list_filter, $to_date, $from_date, $page);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results as $k => $v) {
            $record_array = array();
            foreach ($v as $key => $value) {

                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (in_array($key, $filter_attribute)) {
                    $value = trim($value);
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) &&
                        $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat,
                                                         array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $value_attribute = html_entity_decode($value);
                $value_attribute = str_replace('&', 'and', $value_attribute);
                if (strpos($value_attribute, "\r\n") !== false) {
                    $value_attribute = str_replace("\r\n", '-', $value_attribute);
                }
                $value_attribute = str_replace('&', 'and', $value_attribute);
                $key = trim($key);
                $value_attribute = trim($value_attribute);
                $record_array = array_merge($record_array, array($key => $value_attribute));
            }
            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoAjax($record_array_final, $heading_array, $filter_attribute, $icon_pair_array_final);
    }

    /**
     * Get district wised marker records on map view when district fitler changes
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function district_wise_record() {
        $app_id = $this->input->get('app_id');
        $district = $this->input->get('district');
        /** multiple form handling system statrs * */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer.....* */
        $heading_array = array();
        $form_id = $forms_list[0]['form_id'];
        $selected_form = $this->form_model->get_form($form_id);
        $map_saved_pins = get_map_pin_settings($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $record_array_final = array();
        /**
         * for categry listing icon mangement
         */
        $record_array_final_filter = array();
        $login_district = '';
        $results_filer_main = $this->form_results_model->get_form_results_filters($forms_list, $login_district);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $town_array = array();
        $uc_array = array();
        $exist_alpha = array();
        foreach ($results_filer_main as $results_filer) {
            foreach ($results_filer as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if ($key == 'town_name') {
                        $town_name = $value;
                        if ($town_name) {
                            if (!in_array($town_name, $town_array)) {
                                array_push($town_array, $town_name);
                            }
                        }
                    }
                    if ($key == 'uc_name') {
                        $uc_name = $value;
                        if ($uc_name) {
                            if (!in_array($uc_name, $uc_array)) {
                                array_push($uc_array, $uc_name);
                            }
                        }
                    }

                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {

                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final_filter[] = $record_array;
                }
            }
        }
        /**
         * sorting based on filter attribute of the array data
         * will called a helper class SortAssociativeArray WITH 
         * its call back function call
         */
        foreach ($filter_attribute as $filter_attribute_value) {
            uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
        }
        $only_once_category_icon = array();
        $column_number = 0;
        $icon_pair_array_final = array();
        foreach ($filter_attribute as $filter_attribute_value) {
            foreach ($record_array_final_filter as $form_item) {
                $icon_pair_array = array();
                if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
//                    $category = explode(" ", $form_item[$filter_attribute_value]);
                    $category = strtolower($form_item[$filter_attribute_value]);
                    if (!in_array($form_item[$filter_attribute_value], $only_once_category_icon)) {
                        $column_number++;
                        $only_once_category_icon[] = $form_item[$filter_attribute_value];
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                        }
                        $icon_pair_array = array_merge($icon_pair_array, array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }
        $results = $this->form_results_model->get_form_results_by_district($forms_list, $district);
        $results_comined = array();
        $record_array_final = array();
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results as $k => $v) {
            $record_array = array();
            foreach ($v as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (in_array($key, $filter_attribute)) {
                    $value = trim($value);
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {

                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $value_attribute = html_entity_decode($value);
                $value_attribute = str_replace('&', 'and', $value_attribute);
                if (strpos($value_attribute, "\r\n") !== false) {
                    $value_attribute = str_replace("\r\n", '-', $value_attribute);
                }
                $value_attribute = str_replace('&', 'and', $value_attribute);
                $key = trim($key);
                $value_attribute = trim($value_attribute);
                $record_array = array_merge($record_array, array($key => $value_attribute));
            }
            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoAjax($record_array_final, $heading_array, $filter_attribute, $icon_pair_array_final);
    }

    /**
     * Get towns wised Union Counsils
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function town_wise_uc() {
        $app_id = $this->input->get('app_id');
        $town = $this->input->get('town');
        $uc_list = array();
        $results = $this->form_results_model->get_uc_by_town($app_id, $town);
        foreach ($results as $data) {
            if ($data['uc_name'] && !in_array($data['uc_name'], $uc_list)) {
                $uc_list = array_merge($uc_list, array($data['uc_name'] => $data['uc_name']));
            }
        }
        echo json_encode($uc_list);
    }

    /**
     * Get towns wised marker records on map view when town fitler changes
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function town_wise_record() {
        $app_id = $this->input->get('app_id');
        $town = $this->input->get('town');
        /** multiple form handling system statrs * */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer.....* */
        $heading_array = array();
        $form_id = $forms_list[0]['form_id'];
        $selected_form = $this->form_model->get_form($form_id);
        $map_saved_pins = get_map_pin_settings($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;

        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $record_array_final = array();
        /**
         * for categry listing icon mangement
         */
        $record_array_final_filter = array();
        $login_district = '';
        $results_filer_main = $this->form_results_model->get_form_results_filters($forms_list, $login_district);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $town_array = array();
        $uc_array = array();
        $exist_alpha = array();
        foreach ($results_filer_main as $results_filer) {
            foreach ($results_filer as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if ($key == 'town_name') {
                        $town_name = $value;
                        if ($town_name) {
                            if (!in_array($town_name, $town_array)) {
                                array_push($town_array, $town_name);
                            }
                        }
                    }
                    if ($key == 'uc_name') {
                        $uc_name = $value;
                        if ($uc_name) {
                            if (!in_array($uc_name, $uc_array)) {
                                array_push($uc_array, $uc_name);
                            }
                        }
                    }
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final_filter[] = $record_array;
                }
            }
        }
        /**
         * sorting based on filter attribute of the array data
         * will called a helper class SortAssociativeArray WITH 
         * its call back function call
         */
        foreach ($filter_attribute as $filter_attribute_value) {
            uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
        }
        $only_once_category_icon = array();
        $column_number = 0;
        $icon_pair_array_final = array();
        foreach ($filter_attribute as $filter_attribute_value) {
            foreach ($record_array_final_filter as $form_item) {
                $icon_pair_array = array();
                if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                    $category = strtolower($form_item[$filter_attribute_value]);
                    if (!in_array($form_item[$filter_attribute_value], $only_once_category_icon)) {
                        $column_number++;
                        $only_once_category_icon[] = $form_item[$filter_attribute_value];
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {

                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                        }
                        $icon_pair_array = array_merge($icon_pair_array, array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }
        $results_comined = array();
        $record_array_final = array();
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
            $results = $this->form_results_model->get_form_results_by_town($table_name, $town);
            $results_comined = array_merge($results_comined, $results);
        }
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results_comined as $k => $v) {
            $record_array = array();
            foreach ($v as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (in_array($key, $filter_attribute)) {
                    $value = trim($value);
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $value_attribute = html_entity_decode($value);
                $value_attribute = str_replace('&', 'and', $value_attribute);
                if (strpos($value_attribute, "\r\n") !== false) {
                    $value_attribute = str_replace("\r\n", '-', $value_attribute);
                }
                $value_attribute = str_replace('&', 'and', $value_attribute);
                $key = trim($key);
                $value_attribute = trim($value_attribute);
                $record_array = array_merge($record_array, array($key => $value_attribute));
            }
            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoAjax($record_array_final, $heading_array, $filter_attribute, $icon_pair_array_final);
    }

    /**
     * Get UNION COUNCILS wised marker records on map view when UC fitler changes
     * @return json
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function uc_wise_record() {
        $app_id = $this->input->get('app_id');
        $uc = $this->input->get('uc');
        $sent_by_filter = $this->input->get('sent_by_filter');
        /** multiple form handling system statrs * */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer.....* */
        $heading_array = array();
        $form_id = $forms_list[0]['form_id'];
        $selected_form = $this->form_model->get_form($form_id);
        $map_saved_pins = get_map_pin_settings($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $record_array_final = array();
        /**
         * for categry listing icon mangement
         */
        $record_array_final_filter = array();
        $login_district = '';
        $results_filer_main = $this->form_results_model->get_form_results_filters($forms_list, $login_district);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $town_array = array();
        $uc_array = array();
        $exist_alpha = array();
        foreach ($results_filer_main as $results_filer) {
            foreach ($results_filer as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if ($key == 'town_name') {
                        $town_name = $value;
                        if ($town_name) {
                            if (!in_array($town_name, $town_array)) {
                                array_push($town_array, $town_name);
                            }
                        }
                    }
                    if ($key == 'uc_name') {
                        $uc_name = $value;
                        if ($uc_name) {
                            if (!in_array($uc_name, $uc_array)) {
                                array_push($uc_array, $uc_name);
                            }
                        }
                    }

                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }

                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final_filter[] = $record_array;
                }
            }
        }
        /**
         * sorting based on filter attribute of the array data
         * will called a helper class SortAssociativeArray WITH 
         * its call back function call
         */
        foreach ($filter_attribute as $filter_attribute_value) {
            uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
        }
        $only_once_category_icon = array();
        $column_number = 0;
        $icon_pair_array_final = array();
        foreach ($filter_attribute as $filter_attribute_value) {
            foreach ($record_array_final_filter as $form_item) {
                $icon_pair_array = array();
                if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                    $category = strtolower($form_item[$filter_attribute_value]);
                    if (!in_array($form_item[$filter_attribute_value], $only_once_category_icon)) {
                        $column_number++;
                        $only_once_category_icon[] = $form_item[$filter_attribute_value];
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                        }
                        $icon_pair_array = array_merge($icon_pair_array, array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }
        $results_comined = array();
        $record_array_final = array();
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
//            $results = $this->form_results_model->get_form_results_by_town($table_name, $town);
            $results = $this->form_results_model->get_form_results_by_uc($table_name, $uc, $sent_by_filter);
            $results_comined = array_merge($results_comined, $results);
        }
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results_comined as $k => $v) {
            $record_array = array();
            foreach ($v as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (in_array($key, $filter_attribute)) {
                    $value = trim($value);
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $value_attribute = html_entity_decode($value);
                $value_attribute = str_replace('&', 'and', $value_attribute);
                if (strpos($value_attribute, "\r\n") !== false) {
                    $value_attribute = str_replace("\r\n", '-', $value_attribute);
                }
                $value_attribute = str_replace('&', 'and', $value_attribute);
                $key = trim($key);
                $value_attribute = trim($value_attribute);
                $record_array = array_merge($record_array, array($key => $value_attribute));
            }
            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoAjax($record_array_final, $heading_array, $filter_attribute, $icon_pair_array_final);
    }

    public function sent_by_wise_record() {
        $app_id = $this->input->get('app_id');
        $sent_by_filter = $this->input->get('sent_by_filter');
        $uc_filter = $this->input->get('uc_filter');
        /** multiple form handling system statrs * */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer.....* */
        $heading_array = array();
        $form_id = $forms_list[0]['form_id'];
        $selected_form = $this->form_model->get_form($form_id);
        $map_saved_pins = get_map_pin_settings($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $record_array_final = array();
        /**
         * for categry listing icon mangement
         */
        $record_array_final_filter = array();
        $login_district = '';
        $results_filer_main = $this->form_results_model->get_form_results_filters($forms_list, $login_district);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $town_array = array();
        $uc_array = array();
        $exist_alpha = array();
        foreach ($results_filer_main as $results_filer) {
            foreach ($results_filer as $k => $v) {
                $record_array = array();
                foreach ($v as $key => $value) {
                    if ($key == 'created_datetime') {
                        $date = $value;
                    } else if ($key == 'form_id') {
                        $form_id = $value;
                    } else if ($key == 'location') {
                        $location = $value;
                    } else if ($key == 'id') {
                        $result_id = $value;
                    }
                    if ($key == 'town_name') {
                        $town_name = $value;
                        if ($town_name) {
                            if (!in_array($town_name, $town_array)) {
                                array_push($town_array, $town_name);
                            }
                        }
                    }
                    if ($key == 'uc_name') {
                        $uc_name = $value;
                        if ($uc_name) {
                            if (!in_array($uc_name, $uc_array)) {
                                array_push($uc_array, $uc_name);
                            }
                        }
                    }

                    if ($key == 'sent_by_list') {
                        $uc_name = $value;
                        if ($uc_name) {
                            if (!in_array($uc_name, $uc_array)) {
                                array_push($uc_array, $uc_name);
                            }
                        }
                    }

                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }

                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                            $pin_name = $map_saved_pins[$key][$valueforarray];
                        } else
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        $record_array = array_merge($record_array, array('pin' => $pin_name));
                    }
                    $value_attribute = html_entity_decode($value);
                    $value_attribute = str_replace('&', 'and', $value_attribute);
                    $key = trim($key);
                    $value_attribute = trim($value_attribute);
                    $record_array = array_merge($record_array, array($key => $value_attribute));
                }
                $record_array = array_merge($record_array, array('location' => $location));
                $record_array = array_merge($record_array, array('id' => $result_id));
                $record_array = array_merge($record_array, array('created_datetime' => $date));
                if (!empty($location)) {
                    $record_array_final_filter[] = $record_array;
                }
            }
        }
        /**
         * sorting based on filter attribute of the array data
         * will called a helper class SortAssociativeArray WITH
         * its call back function call
         */
        foreach ($filter_attribute as $filter_attribute_value) {
            uasort($record_array_final_filter, array(new SortAssociativeArray($filter_attribute_value), "call"));
        }
        $only_once_category_icon = array();
        $column_number = 0;
        $icon_pair_array_final = array();
        foreach ($filter_attribute as $filter_attribute_value) {
            foreach ($record_array_final_filter as $form_item) {
                $icon_pair_array = array();
                if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                    $category = strtolower($form_item[$filter_attribute_value]);
                    if (!in_array($form_item[$filter_attribute_value], $only_once_category_icon)) {
                        $column_number++;
                        $only_once_category_icon[] = $form_item[$filter_attribute_value];
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                        }
                        $icon_pair_array = array_merge($icon_pair_array, array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }

        $results_comined = array();
        $record_array_final = array();
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
//            $results = $this->form_results_model->get_form_results_by_town($table_name, $town);
            $results = $this->form_results_model->get_form_results_by_sent_by($table_name, $sent_by_filter, $uc_filter);
            $results_comined = array_merge($results_comined, $results);
        }
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results_comined as $k => $v) {
            $record_array = array();
            foreach ($v as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (in_array($key, $filter_attribute)) {
                    $value = trim($value);
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $value_attribute = html_entity_decode($value);
                $value_attribute = str_replace('&', 'and', $value_attribute);
                if (strpos($value_attribute, "\r\n") !== false) {
                    $value_attribute = str_replace("\r\n", '-', $value_attribute);
                }
                $value_attribute = str_replace('&', 'and', $value_attribute);
                $key = trim($key);
                $value_attribute = trim($value_attribute);
                $record_array = array_merge($record_array, array($key => $value_attribute));
            }

            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoAjax($record_array_final, $heading_array, $filter_attribute, $icon_pair_array_final);
    }

    /**
     * Inline method giving string of records ajax based
     * @param  $locations array of  all data having info about each single record
     * @param  $filter_attribute list of attributed based on data is parsed icons assigned
     * @param  $headings Heading list fetched from all results
     * @param  $icon_pair_array_final Pair of icon for map
     * @return  string A string concatinated with commas
     * @access Inline
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    private function getMapHtmlInfoAjax($locations = array(), $headings = array(), $filter_attribute, $icon_pair_array_final) {
        $final1 = array();
        $filter_exist_array = array();
        $exist_alpha = array();
        if (count($locations)) {
            $column_number = 0;
            $only_once_category = array();
            $pin_exist_for_cat = array();
            $searched_filter_attribute = array();
            foreach ($filter_attribute as $filter_attribute_value) {
                if (!in_array($filter_attribute_value, $searched_filter_attribute)) {
                    foreach ($locations as $form_item) {
                        if (isset($form_item[$filter_attribute_value])) {
                            $category_name = (!empty($form_item[$filter_attribute_value])) ? $form_item[$filter_attribute_value] : "No " . ucfirst($filter_attribute_value);
                            if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                                if (!in_array($form_item[$filter_attribute_value], $only_once_category)) {
                                    $column_number++;
                                    $only_once_category[] = $form_item[$filter_attribute_value];
                                }
                                $valueforarray = $form_item[$filter_attribute_value];
                                $valueforarray = trim(strtolower($valueforarray));
                                foreach ($icon_pair_array_final as $icon) {

                                    if (array_key_exists($valueforarray, $icon)) {

                                        $icon_filename = $icon[$valueforarray];
                                    } else {
                                        
                                    }
                                }
                            } else {

                                $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                            }
                            $location = explode(',', $form_item['location']);
                            $form_id = $form_item['form_id'];
                            $map_data = '';
                            $total_headings = count($headings);
                            $image_row = '';
                            $data_row = '';
                            $datetime_row = '';
                            $date = '';
                            $form_result_id = '';
                            for ($i = 0; $i < $total_headings; $i++) {
                                $final2 = array();
                                if (!empty($form_item[$headings[$i]])) {
                                    if ($headings[$i] == 'is_take_picture') {
                                        
                                    } else if ($headings[$i] == 'image') {
                                        $path = $form_item[$headings[$i]];
                                        $image_row = "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-" . date('Y') . " - " . PLATFORM_NAME . " Developed By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
                                    } else if ($headings[$i] == 'created_datetime') {
                                        $datetime_row .='<tr><td><b>DATE : </b></td><td>' . date('Y-m-d', strtotime($form_item[$headings[$i]])) . '</td></tr><tr><td><b>TIME : </b></td><td>' . date('H:i:s', strtotime($form_item[$headings[$i]])) . '</td></tr>';
                                    } else {
                                        $map_data .= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $headings[$i]) . ' : ' . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$headings[$i]]) . '<br>\n';
                                        $data_row .= "<tr><td><b>" . preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($headings[$i]))) . " : </b></td><td>" . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', strtoupper($form_item[$headings[$i]])) . "</td></tr>";
                                        $id = $form_item['id'];
                                    }
                                }
                            }
                            $final2 = array_merge($final2, array('long' => $location[0]));
                            $final2 = array_merge($final2, array('lat' => $location[1]));
                            $final2 = array_merge($final2, array('icon_filename' => $icon_filename));
                            $final2 = array_merge($final2, array('id' => $id));
                            $final2 = array_merge($final2, array('category_name' => $category_name));
                            $final2 = array_merge($final2, array('form_result_id' => $id));
                            $final2 = array_merge($final2, array('form_id' => $form_id));
                            $final1[] = $final2;
                        }
                    }
                    $searched_filter_attribute[] = $filter_attribute_value;
                }
            }
        } else {
            $final1[] = "";
        }
        echo json_encode($final1);
    }

    /**
     * map activity detail when on click on map marker  auto clicked for singel marker
     * @return  string information about a single string
     * @access Inline
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function map_activity_popup() {
        $slug = $_GET['form_result_id'];
        $form_id = $_GET['form_id'];
        $table_name = 'zform_' . $form_id;
        $map_saved_pins = get_map_pin_settings($form_id);
        $data['form_id'] = $form_id;
        $heading_array = array();
        $selected_form = $this->form_model->get_form($form_id);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $record_array_final = array();
        $results = $this->form_results_model->get_results_single($slug, $table_name);
        $exclude_array = array('id', 'remote_id', 'imei_no', 'uc_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results as $k => $v) {
            $record_array = array();
            $date = $v['created_datetime'];
            foreach ($v as $key => $value) {
                if ($key == 'created_datetime') {
                    $date = $value;
                } else if ($key == 'form_id') {
                    $form_id = $value;
                } else if ($key == 'location') {
                    $location = $value;
                } else if ($key == 'id') {
                    $result_id = $value;
                }
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if (in_array($key, $filter_attribute)) {
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                if (!in_array($key, $exclude_array)) {
                    $record_array = array_merge($record_array, array($key => $value));
                }
            }
            $imagess = $this->form_results_model->getResultsImages($result_id, $form_id);
            if ($imagess) {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $imagess));
            }
            $record_array = array_merge($record_array, array('location' => $location));
            $record_array = array_merge($record_array, array('id' => $result_id));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($location)) {
                $record_array_final[] = $record_array;
            }
        }
        $final1 = array();
        $filter_exist_array = array();
        $exist_alpha = array();
        $data_array = array();
        if (count($record_array_final)) {
            $column_number = 0;
            $only_once_category = array();
            $pin_exist_for_cat = array();
            foreach ($filter_attribute as $filter_attribute_value) {
                foreach ($record_array_final as $form_item) {
                    $category_name = (!empty($form_item[$filter_attribute_value])) ? $form_item[$filter_attribute_value] : "No " . ucfirst($filter_attribute_value);
                    if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                        if (!in_array($form_item[$filter_attribute_value], $only_once_category)) {
                            $column_number++;
                            $only_once_category[] = $form_item[$filter_attribute_value];
                        }
                        $valueforarray = $form_item[$filter_attribute_value];
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            $valueforarray1 = str_replace(' ', '_', $valueforarray);
                            if (isset($map_saved_pins[$filter_attribute_value][$valueforarray1]) && $map_saved_pins[$filter_attribute_value][$valueforarray1] != '') {
                                $pin_name = $map_saved_pins[$filter_attribute_value][$valueforarray1];
                            } else
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val . '.png';
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1.png';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name)) {
                            $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name;
                        }
                    } else {
                        $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                    }
                    if (empty($form_item['location'])) {
                        $location = explode(',', '31.454969126242176,74.33821678161621');
                    } else {
                        $location = explode(',', $form_item['location']);
                    }
                    $map_data = '';
                    $total_headings = count($heading_array);
                    $html_layout = array();
                    $image_row = '';
                    $data_row = '';
                    $datetime_row = '';
                    $date = '';
                    $form_result_id = '';
                    for ($i = 0; $i < $total_headings; $i++) {
                        $final2 = array();
                        if (!empty($form_item[$heading_array[$i]])) {
                            if ($heading_array[$i] == 'is_take_picture') {
                                
                            } else if ($heading_array[$i] == 'image') {
                                $path = $form_item[$heading_array[$i]][0]['image'];
                                $html_layout = array_merge($html_layout, array('image' => $imagess));
                            } else if ($heading_array[$i] == 'created_datetime') {
                                $date_time = $form_item[$heading_array[$i]];
                                $html_layout = array_merge($html_layout, array('date_time' => $date_time));
                            } else {
                                $field_name = preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($heading_array[$i])));
                                $field_value = strtoupper($form_item[$heading_array[$i]]);
                                $html_layout = array_merge($html_layout, array($field_name => $field_value));
                                $id = $form_item['id'];
                            }
                        }
                    }
                    $data_array = $html_layout;
                    $final2 = array_merge($final2, array('long' => $location[0]));
                    $final2 = array_merge($final2, array('lat' => $location[1]));
                    $final2 = array_merge($final2, array('icon_filename' => $icon_filename));
                    $final2 = array_merge($final2, array('id' => $id));
                    $final2 = array_merge($final2, array('category_name' => $category_name));
                    $final2 = array_merge($final2, array('form_result_id' => $id));
                    $final1[] = $final2;
                }
            }
        } else {
            $final1 = '';
        }
        $data['locations'] = $final1;
        $data['data_array'] = $data_array;
        $this->load->view('form/map_activity_popup', $data);
    }

    /**
     * 
     * function to edit in color box with ajax call
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function edit_form_partial() {
        $form_id = $this->input->get('form_id');
        $form_result_id = $this->input->get('form_result_id');
        $selected_form_result = $this->form_results_model->get_results($form_id, $form_result_id);
        $selected_form = $this->form_model->get_form($form_id, 0);
        $description = $selected_form['description'];
        $data['description'] = $description;
        $data['form_result_id'] = $form_result_id;
        $data['form_id'] = $form_id;
        $data['attribute_values'] = json_encode($selected_form_result);
        $this->load->view('form/edit_form_partial', $data);
    }

    /**
     * ajax call for editing data on listview based on edit_form_partial()
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function edit_listview_partial() {
        if ($this->input->post()) {
            $post = $this->input->post();
            foreach ($post as $key => $v) {
                $cap_first = explode('-', $key);
                if ($cap_first[0] == 'caption') {
                    unset($post[$key]);
                }
            }
            $form_result_id = $this->input->post('form_result_id');
            $form_id = $this->input->post('form_id');
            $table_name = 'zform_' . $form_id;
            unset($post['form_result_id']);
            unset($post['form_id']);
            unset($post['takepic']);
            unset($post['security_key']);
            if ($this->input->post('takepic')) {
                unset($post['takepic']);
            }
            $exclude_array = array('security_key');
            $table_headers_array = array();
            $heading_query = array();
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            $heading_query = array_merge($heading_query, $schema_list);
            foreach ($heading_query as $key => $value) {
                $header_value = $value['COLUMN_NAME'];
                if ($header_value != 'created_datetime') {
                    if (!in_array($header_value, $table_headers_array)) {
                        if (!in_array($header_value, $exclude_array)) {
                            $table_headers_array = array_merge($table_headers_array, array($header_value));
                        }
                    }
                }
            }
            $table_headers_array = array_merge($table_headers_array, array('created_datetime'));
            $table_headers_array = array_merge($table_headers_array, array('actions'));
            $formdata = array();
            foreach ($post as $key => $rec) {
                foreach ($table_headers_array as $column) {
                    if ($column == $key) {
                        $formdata = array_merge($formdata, array($key => $rec));
                    }
                }
            }
            $this->db->where('id', $form_result_id);
            $this->db->update($table_name, $formdata);
        }
    }

    /**
     * 
     * ajax call for editing data from mapview based on edit_form_partial_map()
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function edit_map_partial() {
        if ($this->input->post()) {
            $data = $this->input->post('image');
            if ($data != 'undefined') {
                $img = str_replace('data:image/jpeg;base64,', '', $data);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $abs_path = './assets/images/data/form-data/';
                $image_name = 'users_added' . time();
                $file = $abs_path . $image_name . '.jpg';
                $success = file_put_contents($file, $data);
            } else {
                $image_name = "";
            }
            $post = $this->input->post();
            $form_result_id = $this->input->post('form_result_id');
            $form_id = $this->input->post('form_id');
            unset($post['form_result_id']);
            unset($post['form_id']);
            if ($this->input->post('image')) {
                unset($post['image']);
            }
            $json_result = json_encode($post);
            if (!file_exists(FCPATH . 'assets/images/data/form-data/' . $image_name . '.jpg')) {
                $data = array(
                    'record' => $json_result,
                );
            } else {
                $data = array(
                    'record' => $json_result,
                    'image' => $image_name . '.jpg',
                );
            }
            $this->db->where('id', $form_result_id);
            $this->db->update('form_results', $data);
        }
    }

    /**
     * 
     * method for edit map paritial for  in mapview
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function edit_form_partial_map() {
        $form_id = $this->input->get('form_id');
        $form_result_id = $this->input->get('form_result_id');
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
        $selected_form_result = $this->form_results_model->get_results($form_result_id);
        $form_id = $selected_form_result['form_id'];
        $imei_no = $selected_form_result['imei_no'];
        $image = $selected_form_result['image'];
        $record = $selected_form_result['record'];
        $data ['form_result_id'] = $form_result_id;
        $data ['long'] = $long;
        $data ['lat'] = $lat;
        $data['form_id'] = $form_id;
        $data['image'] = $image;
        $data['imei'] = $imei_no;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $heading_array = array();
        $record_array = array();
        $result_array = json_decode($record);
        $record_array_final = array();
        foreach ($result_array as $key => $value) {
            if (!in_array($key, $heading_array)) {
                $heading_array = array_merge($heading_array, array($key));
            }
            $record_array = array_merge($record_array, array($key => $value));
        }
        $record_array_final[] = $record_array;
        $data['locations'] = $record_array_final;
        $data['headings'] = $heading_array;
        $heading_array = $this->get_heading_data($form_id, 1);
        $data['form_for_filter'] = $heading_array['form'];
        $this->load->view('form/edit_form_partial_map', $data);
    }

    /**
     * Ajax pagination for filter date based with its own appropriate fetch record
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_data_ajax_date_filter($slug = "", $to_date = "", $from_date = "") {
        $page_variable = isset($_POST['page']) ? $_POST['page'] : $this->perPage;
        $form_id = $slug;
        $array_final = array();
        $array_final = $this->get_heading_data_by_date($form_id, $to_date, $from_date);
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $total_record_return = $this->form_results_model->TotalRecByDateFilter($form_id, $to_date, $from_date);
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'get_data_ajax_date_filter';
        $pdata['slug'] = $slug;
        $data['paging_date_filter'] = $this->parser->parse('form/paging_date_filter', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['view_page'] = 'paging_date_filter';
        $this->load->view('form/form_results_data', $data);
    }

    /**
     * 
     * Get Heading and form data based on filter date
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_heading_data_by_date($slug, $to_date, $from_date) {
        $form_id = $slug;
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $heading_array = array();
        $record_array_final = array();
        $results = $this->form_results_model->get_form_results_by_date($form_id, $to_date, $from_date, $this->perPage);
        foreach ($results as $k => $v) {
            $record_array = array();
            $result_json = $v['record'];
            if ($v['image'] != '') {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $v['image']));
            }
            $result_array = json_decode($result_json);
            foreach ($result_array as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                $record_array = array_merge($record_array, array($key => $value));
            }

            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
            $record_array_final[] = $record_array;
        }
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * function to display map of singel record
     * @param type $slug
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function mapview_single($slug = '') {
        $mixture_slug = explode('-', $slug);
        $result_id = $mixture_slug[count($mixture_slug) - 1];
        $form_id = $mixture_slug[0];
        $table_name = 'zform_' . $form_id;
        $map_saved_pins = get_map_pin_settings($form_id);
        $data['form_id'] = $form_id;
        $heading_array = array();
        $selected_form = $this->form_model->get_form($form_id);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $filter_attribute = array();
        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }
        $data['filter_attribute'] = $filter_attribute;
        $record_array_final = array();
        $results = $this->form_results_model->get_results_single($result_id, $table_name);
        $filter_exist_array = array();
        $pin_exist_for_cat = array();
        $col_pin = 0;
        $exist_alpha = array();
        foreach ($results as $k => $v) {
            $record_array = array();
            $date = $v['created_datetime'];
            foreach ($v as $key => $value) {
                if (!in_array($key, $heading_array)) {
                    $heading_array = array_merge($heading_array, array($key));
                }
                if (in_array($key, $filter_attribute)) {
                    $valueforarray = str_replace(' ', '_', $value);
                    if (isset($map_saved_pins[$key][$valueforarray]) && $map_saved_pins[$key][$valueforarray] != '') {
                        $pin_name = $map_saved_pins[$key][$valueforarray];
                    } else
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val . '.png';
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1.png';
                        }
                        $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                    } else {
                        if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                            $pin_name = $pin_exist_for_cat[$valueforarray];
                        }
                    }
                    $record_array = array_merge($record_array, array('pin' => $pin_name));
                }
                $record_array = array_merge($record_array, array($key => $value));
            }
            if (!in_array('created_datetime', $heading_array)) {
                $heading_array = array_merge($heading_array, array('created_datetime'));
            }
            $imagess = $this->form_results_model->getResultsImages($v['id'], $v['form_id']);
            if ($imagess) {
                if (!in_array('image', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('image'));
                }
                $record_array = array_merge($record_array, array('image' => $imagess));
            }
            $record_array = array_merge($record_array, array('location' => $v['location']));
            $record_array = array_merge($record_array, array('id' => $v['id']));
            $record_array = array_merge($record_array, array('created_datetime' => $date));
            if (!empty($v['location'])) {
                $record_array_final[] = $record_array;
            }
        }
        $data['locations'] = $this->getMapHtmlInfoSingle($record_array_final, $heading_array, $filter_attribute);
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;
        $data['form_for_filter'] = $record_array_final;
        $data['active_tab'] = 'app';
        $data['pageTitle'] = $data['form_name'] . ' Records - Map View-' . PLATFORM_NAME;
        $this->load->view('templates/header_iframe', $data);
        $this->load->view('form/map_view_single', $data);
    }

    /**
     * Method to export data in excell sheet formate
     * @param type $slug application id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function exportresults($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        if ($this->session->userdata('logged_in')) {
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            $data['form_lists'] = $forms_list;
            $data['active_tab'] = 'export_results';
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            if ($this->input->post('form_lists')) {
                $form_id = $this->input->post('form_lists');
                $data['selected_form'] = $form_id;
                $selected_form = $this->form_model->get_form($form_id);
                $data['form_id'] = $form_id;
                $data['app_id'] = $selected_form['app_id'];
                $app = $this->app_model->get_app($selected_form['app_id']);
                $data['form_name'] = $selected_form['name'];
                $data['app_name'] = $app['name'];
                $data['pageTitle'] = 'Export Results -' . PLATFORM_NAME;
                $this->load->view('templates/header', $data);
                $this->load->view('form/export_results', $data);
                $this->load->view('templates/footer', $data);
            } else if ($this->input->post('form_id')) {
                ini_set('memory_limit', '-1');
                ini_set('max_execution_time', 1200);
                $form_id_stutus = $this->input->post('form_id');
                $selected_form = $this->form_model->get_form($forms_list[0]['form_id']);
                $app_id = $selected_form['app_id'];
                if ($form_id_stutus != 'all_forms_result_export') {
                    $forms_list = array();
                    foreach ($forms_list as $key => $forms_single) {
                        unset($forms_list[$key]);
                    }
                    $forms_list[] = array('form_id' => $form_id_stutus, 'table_name' => 'zform_' . $form_id_stutus);
                }
                $to_date = $this->input->post('filter_date_to');
                $from_date = $this->input->post('filter_date_from');
                $data['selected_date_to'] = $to_date;
                $data['selected_date_from'] = $from_date;
                $cat_filter_value = '';
                $filter_attribute_search = '';
                $town_filter = '';
                if (empty($to_date)) {
                    $to_date = "2013-01-01";
                    $data['selected_date_to'] = "";
                }
                if (empty($from_date)) {
                    $from_date = "2023-01-01";
                    $data['selected_date_from'] = "";
                }
                $array_final = array();
                $array_final = $this->get_heading_n_data_export($forms_list, $to_date, $from_date);
                $data['headings'] = $array_final['headings'];
                $data['form'] = $array_final['form'];
                $headings = $data['headings'];
                $forms = $data['form'];
                $header = '';
                foreach ($headings as $values) {
                    if ($values == 'is_take_picture' || $values == 'actions')
                        continue;
                    $header .= $values . ",";
                }
                if ($forms) {
                    $data_form = '';
                    $data_form = $header . "\n";
                    $total_headings = count($data['headings']);
                    foreach ($forms as $form) {
                        $line = '';
                        for ($i = 0; $i < $total_headings; $i++) {

                            if ($headings[$i] == 'is_take_picture' || $headings[$i] == 'actions')
                                continue;
                            //continue
                            if (!isset($form[$headings[$i]]) || $form[$headings[$i]] == "") {
                                $value = ","; //in this case, ";" separates columns
                            } else if ($headings[$i] == 'image') {
                                $value = str_replace('"', '""', $form[$headings[$i]]);
                                $multi_image_str = '';
                                foreach ($form[$headings[$i]] as $multi_image) {
                                    $multi_image_str .= $multi_image['image'] . ' ,';
                                }
                                $multi_image_str = substr($multi_image_str, 0, -1);
                                $value = '"' . $multi_image_str . '"' . ",";
                            } else {
                                $value = str_replace('"', '""', $form[$headings[$i]]);
                                $value = '"' . $value . '"' . ","; //if you change the separator before, change this ";" too
                            }
                            $line .= $value;
                        } //end foreach
                        $data_form .= trim($line) . "\n";
                    } //end while
                    //avoiding problems with data that includes "\r"
                    $data_form = str_replace("\r", "", $data_form);
                } else {
                    $data_form = 'There is no Record in this form';
                }
                //Working but not column
                $app = $this->app_model->get_app($app_id);
                if ($form_id_stutus == 'all_forms_result_export') {
                    $filename = $app['name'] . '( All-Forms-Data )' . ".csv";
                } else {
                    $filename = $app['name'] . '(' . $array_final['form_name'] . ')' . ".csv";
                }

                $filename = str_replace(" ", "-", $filename);
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                echo chr(239) . chr(187) . chr(191) . $data_form;
                exit;
            } else {
                $form_id = $first_form_id = $forms_list[0]['form_id'];
                $data['selected_form'] = '';
                $selected_form = $this->form_model->get_form($form_id);
                $data['form_id'] = 'all_forms_result_export';
                $data['app_id'] = $selected_form['app_id'];
                $app = $this->app_model->get_app($selected_form['app_id']);
                $data['form_name'] = $selected_form['name'];
                $data['app_name'] = $app['name'];
                $data['pageTitle'] = 'Export Results -' . PLATFORM_NAME;
                $this->load->view('templates/header', $data);
                $this->load->view('form/export_results', $data);
                $this->load->view('templates/footer', $data);
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
    }

    public function exportiframe($slug) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 1200);
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $app_id = $slug_id;
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        $to_date = '';
        $from_date = '';
        $cat_filter_value = '';
        $filter_attribute_search = '';
        $town_filter = '';
        if (empty($to_date)) {
            $to_date = "2013-01-01";
        }
        if (empty($from_date)) {
            $from_date = "2023-01-01";
        }

        $array_final = array();
        $array_final = $this->get_heading_n_data_export($forms_list, $to_date, $from_date);
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $headings = $data['headings'];
        $forms = $data['form'];
        $header = '';
        foreach ($headings as $values) {
            if ($values == 'is_take_picture' || $values == 'actions')
                continue;
            $header .= $values . ",";
        }
        if ($forms) {
            $data_form = '';
            $data_form = $header . "\n";
            $total_headings = count($data['headings']);
            foreach ($forms as $form) {
                $line = '';
                for ($i = 0; $i < $total_headings; $i++) {
                    if ($headings[$i] == 'is_take_picture' || $headings[$i] == 'actions')
                        continue;
                    //continue

                    if (!isset($form[$headings[$i]]) || $form[$headings[$i]] == "") {
                        $value = ","; //in this case, ";" separates columns
                    } else if ($headings[$i] == 'image') {
                        $value = str_replace('"', '""', $form[$headings[$i]]);
                        $multi_image_str = '';
                        foreach ($form[$headings[$i]] as $multi_image) {
                            $multi_image_str .= $multi_image['image'] . ' ,';
                        }
                        $multi_image_str = substr($multi_image_str, 0, -1);
                        $value = '"' . $multi_image_str . '"' . ",";
                    } else {
                        $value = str_replace('"', '""', $form[$headings[$i]]);
                        $value = '"' . $value . '"' . ","; //if you change the separator before, change this ";" too
                    }
                    $line .= $value;
                } //end foreach
                $data_form .= trim($line) . "\n";
            } //end while
            //avoiding problems with data that includes "\r"
            $data_form = str_replace("\r", "", $data_form);
        } else {
            $data_form = 'There is no Record in this form';
        }
        //Working but not column
        $app = $this->app_model->get_app($app_id);
        $filename = $app['name'] . '-export' . ".csv";
        $filename = str_replace(" ", "-", $filename);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo chr(239) . chr(187) . chr(191) . $data_form;
        exit;
    }

    //new instance for export
    public function get_heading_n_data_export($forms_list, $to_date, $from_date) {
        $form_id = $forms_list[0]['form_id'];
        $data['form_id'] = $form_id;
        $selected_form = $this->form_model->get_form($form_id);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $app_id = $selected_form['app_id'];
        $table_headers_array = array();
        $record_array_final = array();
        $heading_query = array();
        $exclude_array = array('id', 'remote_id', 'uc_name', 'town_name', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
            $results = $this->form_results_model->get_results_for_export($table_name, $to_date, $from_date, $app_id);
            foreach ($results as $k => $v) {
                $record_array = array();
                $imagess = $this->form_results_model->getResultsImages($v['id'], $v['form_id']);
                if ($imagess) {
                    if (!in_array('image', $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }
                foreach ($v as $key => $value) {
                    $value = html_entity_decode($value);
                    $value = str_replace('_', '/', $value);
                    if (!in_array($key, $exclude_array)) {
                        $record_array = array_merge($record_array, array($key => $value));
                    }
                }
                $record_array = array_merge($record_array, array('form_id' => $v['form_id']));
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            $heading_query = array_merge($heading_query, $schema_list);
        }
        $send = array();
        foreach ($record_array_final as $final) {
            if (!empty($category_name)) {
                if (in_array($category_name, $final, FALSE)) {
                    $send[] = $final;
                }
            } else {
                $send[] = $final;
            }
        }
        $table_headers_array = array_merge($table_headers_array, array('sent_by'));
        foreach ($heading_query as $key => $value) {
            $header_value = $value['COLUMN_NAME'];
            if ($header_value != 'created_datetime') {
                if (!in_array($header_value, $exclude_array)) {
                    if (!in_array($header_value, $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array($header_value));
                    }
                }
            }
        }
        $table_headers_array = array_merge($table_headers_array, array('created_datetime'));

        $table_headers_array = array_merge($table_headers_array, array('town'));
        $table_headers_array = array_merge($table_headers_array, array('actions'));
        $data['headings'] = $table_headers_array;
        $data['form'] = $record_array_final;
        $data['active_tab'] = 'app';
        return $data[] = $data;
    }

    /**
     * Change filter 
     * @param type $slug form_id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function changefilter($slug) {
        $form_id = $slug;
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];
        $filter = trim($this->input->post('filter'));
        $dataform = array(
            'filter' => $filter
        );
        $this->db->where('id', $form_id);
        $this->db->update('form', $dataform);
        $this->session->set_flashdata('validate', array('message' => 'Form filter updated successfully.', 'type' => 'success'));
        if ($this->input->post('redirect_to') == 'form_result') {
            redirect(base_url() . 'application-results/' . $app_id);
        } else if ($this->input->post('redirect_to') == 'mapview') {
            redirect(base_url() . 'application-map/' . $app_id);
        }
    }

    /**
     * change filter ajax based for list view with populating the filter dropdown
     * @param type $slug form_id
     * @return  json
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function changeFilterList() {
        $app_id = $this->input->post('app_id');
        $filter = trim($this->input->post('filter_selected'));
        $forms_list = array();
        $table_headers_array = array();
        $all_forms = $this->form_model->get_form_list($app_id);
        foreach ($all_forms as $forms) {
            $form_id = $forms['id'];
            $table_name = 'zform_' . $form_id;
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            foreach ($schema_list as $key => $value) {
                $header_value = $value['COLUMN_NAME'];
                if ($header_value == $filter) {
                    $dataform = array(
                        'filter' => $filter
                    );
                    $this->db->where('id', $form_id);
                    $this->db->update('form', $dataform);
                }
            }
        }
        exit();
    }

    /**
     * change filter ajax based for map view with populating the filter dropdown
     * @return  json
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function changeFilterMap() {
        $app_id = $this->input->post('app_id');
        $filter = trim($this->input->post('filter_selected'));
        $forms_list = array();
        $table_headers_array = array();
        $all_forms = $this->form_model->get_form_list($app_id);
        foreach ($all_forms as $forms) {
            $form_id = $forms['id'];
            $table_name = 'zform_' . $form_id;
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            foreach ($schema_list as $key => $value) {
                $header_value = $value['COLUMN_NAME'];
                if ($header_value == $filter) {
                    $dataform = array(
                        'filter' => $filter
                    );
                    $this->db->where('id', $form_id);
                    $this->db->update('form', $dataform);
                }
            }
        }
        exit();
    }

    /**
     * Rotate image
     * @return  json
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function rotateImage() {
        $img_url = FCPATH . 'assets/images/data/form-data/' . $_POST['image'];
        $degrees = $_POST['degree'];
        $source = imagecreatefromjpeg($img_url);
        $rotate = imagerotate($source, $degrees, 0);
        if (!imagejpeg($rotate, $img_url, 90)) {
            echo 'Rotation failed';
        }
    }

    /**
     * setting filters based on other on mapview  based on first filter selected
     * @param $app_id applicatoin id
     * @return  json
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function map_filters_settings($app_id) {
        $filter_values = $this->input->post('filter_values');
        $changed_filter = $this->input->post('changed_filter');
        $filter_to_update = $this->input->post('filter_to_update');
        /**
         * multiple form handling system statrs
         */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        /** multi form ends herer..... */
        $record_array_final_filter = array();
        $results_filer = $this->form_results_model->get_form_results_filters($forms_list);
        $app_filters_array = array();
        foreach ($results_filer as $k => $v) {
            $result_json = $v['record'];
            $result_array = json_decode($result_json);
            foreach ($result_array as $key => $value) {
                if (isset($result_array->$filter_to_update)) {
                    foreach ($filter_values as $f_values) {
                        if ($result_array->$changed_filter == $f_values) {
                            $filter_entity = $result_array->$filter_to_update;
                            if (!key_exists($filter_to_update, $app_filters_array)) {
                                $app_filters_array[$filter_to_update][$filter_entity] = $filter_entity;
                            }
                            if (!in_array($filter_entity, $app_filters_array[$filter_to_update])) {
                                $app_filters_array[$filter_to_update][$filter_entity] = $filter_entity;
                            }
                        }
                    }
                }
            }
        }
        header('Content-Type: application/x-json; charset=utf-8');
        echo (json_encode($app_filters_array[$filter_to_update]));
    }

    /**
     * Adding comments to applicatoin
     * @param $app_id applicatoin id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    function comments_adding() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $comment_text = $this->input->post('comment_text');
            $app_id = $this->input->post('app_id');
            $comment_array = array(
                'app_id' => $app_id,
                'user_id' => $session_data['login_user_id'],
                'comments' => $comment_text
            );
            $this->db->insert('app_comments', $comment_array);
            $this->db->insert_id();
            $data['app_comments'] = $this->form_model->get_comments($app_id);
            $this->load->view('form/comments_adding', $data);
        }
    }

    /**
     * Maintain state of the application view in app builder
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function stateviewbuilder() {
        $screen_size = $this->input->post('screen_size');
        $app_id = $this->input->post('app_id');
        $app_settings = array(
            'default_view_builder' => $screen_size,
        );
        $this->db->where('app_id', $app_id);
        $settings = get_app_general_settings($app_id);
        $settings->screen_view = $screen_size;
        $setting_json = json_encode($settings);
        $this->db->query("update app_settings set filters='$setting_json' where setting_type='GENERAL_SETTINGS' AND app_id='$app_id'");
//        $this->db->update('app_settings', $app_settings);
    }

    public function addTableColumn() {
        $form_id = $this->input->post('form_id');
        $field_name = $this->input->post('field_name');
        $field = array($field_name => array('type' => 'VARCHAR', 'constraint' => 200));
        $this->load->dbforge();
        $this->dbforge->add_column('zform_' . $form_id, $field);
    }

    public function deleteTableColumn() {
        $form_id = $this->input->post('form_id');
        $field_name = $this->input->post('field_name');
        $this->load->dbforge();
        $this->dbforge->drop_column('zform_' . $form_id, $field_name);
    }

    public function editTableColumn() {
        $form_id = $this->input->post('form_id');
        $sub_table_name = $this->input->post('sub_table_name');
        if ($form_id) {
            $old_field_name = str_replace('[]', '', $this->input->post('old_field_name'));
            $new_field_name = str_replace('[]', '', $this->input->post('new_field_name'));
            $description = $this->input->post('form_str');
            if ($old_field_name != $new_field_name) {
                if ($sub_table_name != '') {
                    if ($this->form_results_model->check_column_exits('zform_' . $form_id . '_' . $sub_table_name, $new_field_name)) {
                        $jsone_array = array('status' => false, 'field_name' => $old_field_name,'message' => 'Column exist in sub table already');
                        echo json_encode($jsone_array);
                        exit();
                    }
                } else {

                    if(is_default_column($new_field_name)){

                        $jsone_array = array('status' => 'default', 'field_name' => $old_field_name);
                        echo json_encode($jsone_array);
                        exit();

                    }
                    else if ($this->form_results_model->check_column_exits('zform_' . $form_id, $new_field_name)) {
                        $jsone_array = array('status' => false, 'field_name' => $old_field_name,'message' => 'Column exist in table already');
                        echo json_encode($jsone_array);
                        exit();
                    }
                }
            }
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            $full_description = $this->get_full_description($description, $app_id);
            $dataform = array(
                'description' => $description,
                'full_description' => $full_description,
            );
            $this->db->where('id', $form_id);
            $this->db->update('form', $dataform);
            $this->load->dbforge();
            if ($sub_table_name != '') {
                $fields = array(
                    $old_field_name => array(
                        'name' => $new_field_name,
                        'type' => 'text',
                        'NULL' => TRUE
                    ),
                );
                $this->dbforge->modify_column('zform_' . $form_id . '_' . $sub_table_name, $fields);
            } else {
                $column_info = $this->form_results_model->check_column_exits('zform_' . $form_id, $old_field_name);
                if ($column_info['DATA_TYPE'] == 'varchar') {
                    $fields = array(
                        $old_field_name => array(
                            'name' => $new_field_name,
                            'type' => 'VARCHAR',
                            'constraint' => $column_info['CHARACTER_MAXIMUM_LENGTH'],
                            'NULL' => TRUE
                        ),
                    );
                } else {
                    $fields = array(
                        $old_field_name => array(
                            'name' => $new_field_name,
                            'type' => 'text',
                            'NULL' => TRUE
                        ),
                    );
                }
                $this->dbforge->modify_column('zform_' . $form_id, $fields);
            }

            //edit subtable name which relate with main table field
            $is_subtable = $this->input->post('is_subtable');
            if ($is_subtable) {
                if ($old_field_name != $new_field_name) {
                    $this->load->model('form_results_model');
                    $table_name = 'zform_' . $form_id . '_' . $old_field_name;
                    //$table_exist = $this->form_results_model->check_table_exits($table_name);
                    if (is_table_exist($table_name)) {
                        $this->dbforge->rename_table('zform_' . $form_id . '_' . $old_field_name, 'zform_' . $form_id . '_' . $new_field_name);
                    }
                }
            }
            //array parameters : action, description, before, after, app_id, app_name, form_id, form_name
            $logary = array('action' => 'update', 'description' => 'Edit table field name', 'form_id' => $form_id, 'before' => $old_field_name, 'after' => $new_field_name);
            addlog($logary);
        }
        $jsone_array = array('status' => true);
        echo json_encode($jsone_array);
        exit();
    }

    /**
     * Custome function  for Disbursment Center app
     * to get dc list on based of district via ajax
     */
    public function get_district_wise_d_center() {
        $app_id = $this->input->post('app_id');
        $district = $this->input->post('district');
        $dc_list = $this->form_results_model->get_d_center_district_wise($app_id, $district);
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($dc_list);
        exit();
    }

    /**
     * get_form_based_category_values via ajax
     */
    public function get_form_based_category_values() {
        $form_id = $this->input->post('form_id');
        $filter_attribute = $this->form_model->get_form_filters_only($form_id);
        $app_id = $this->db->query("select app_id from form where id=$form_id")->result_array();
        $app_id = $app_id[0]['app_id'];
        $settings = get_result_view_settings($app_id);
        $category_final = array('' => 'Select One');
        $sub_final = array();
        $final_json = array();
        $possible_filters = $settings->filters->$form_id;
        if ($possible_filters) {
            foreach ($possible_filters as $category) {
                $category_final = array_merge($category_final, array($category => str_replace('_', ' ', $category)));
            }
        }
        $filter = $filter_attribute['filter'];
        $sub_cat_list = $this->form_results_model->get_category_values('zform_' . $form_id, $filter);
        if ($sub_cat_list) {
            foreach ($sub_cat_list as $cat) {
                $sub_final = array_merge($sub_final, array($cat[$filter] => $cat[$filter]));
            }
        }
        if ($category_final || $sub_final) {
            $final_json = array('category' => $category_final, 'sub_category' => $sub_final, 'selected_cat' => $filter);
            header('Content-Type: application/x-json; charset=utf-8');
            echo json_encode($final_json);
        } else {
            return FALSE;
        }
        exit();
    }

    function load_form_settings() {
        echo "<pre>";
        print_r($_POST['form_settings']);
    }

    public function show_subtable($table) {
        $table_arr = explode("_", $table);
        $row_id = end($table_arr);
        array_pop($table_arr);
        $heading = end($table_arr);
        $table = implode("_", $table_arr);
        //get subtable data...
        $result = $this->form_results_model->get_subtable_data($table, $row_id);
        $data['subtable'] = $result;
        $data['heading'] = $heading;
        $this->load->view('form/form_subtable', $data);
    }
    
    public function changestatusrecord() {
        //$this->load->model('form_result_model');
        $rec_id = $this->input->post('id');
        $form_id = $this->input->post('form_id');
        $activity_status= $this->input->post('activity_status');
        $data = array(
            'activity_status' => $activity_status
        );
        $this->db->where('id', $rec_id);
        $this->db->update('zform_'.$form_id, $data);

        exit;
    }

}

/**
 * class for sorting array absolutely differert  moduole
 * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
 */
class SortAssociativeArray {

    private $attr;

    function __construct($attr) {
        $this->attr = $attr;
    }

    function call($a, $b) {
        return $this->term_meta_cmp($a, $b, $this->attr);
    }

    function term_meta_cmp($b, $a, $attr) {
        if (isset($a[$attr]) && isset($b[$attr])) {
            if ($a[$attr] == $b[$attr]) {
                return 0;
            }
            return ($a[$attr] > $b[$attr]) ? -1 : 1;
        } else {
            return TRUE;
        }
    }

}
