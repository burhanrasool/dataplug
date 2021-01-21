<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map extends CI_Controller {
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
    public function get_heading_data_by_category($forms_list, $to_date,
	$from_date, $category_name, $filter_attribute_search,
	$town_filter, $posted_filters, $search_text = null, $export = null) {
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
        $total_record_return = $this->form_results_model->
		TotalRecMultipleForm($forms_list);
        $pdata['app_id'] = $slug;
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'get_data_ajax';
        $pdata['slug'] = $slug;
        $data['paging'] = $this->parser->parse('map/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('map/form_results_data', $data);
    }

    //new instance
    public function pagination_ajax_data($slug = Null) {
        $forms_list = array();
        $form_single_to_query = array();
        $form_single_to_query[] = array('form_id' => $slug,
		'table_name' => 'zform_' . $slug);
        $page_variable = isset($_POST['page']) ? $_POST['page'] : 0;
        $array_final = array();
        $array_final = $this->get_heading_n_data($form_single_to_query, 0);
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $total_record_return = $this->form_results_model->
		return_total_record($form_single_to_query);
        $pdata['app_id'] = $slug;
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'pagination_ajax_data';
        $pdata['form_id'] = $slug;
        $pdata['slug'] = $slug;
        $data['paging'] = $this->parser->parse('map/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('map/form_results_data', $data);
    }

    /**
     * method  used to render the form pagination data by 
	 ajax call called from paging.php based on category
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
        $cat_filter_value = isset($_GET['cat_filter_value']) 
		? $_GET['cat_filter_value'] : " ";
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
        $array_final = $this->get_heading_data_by_category($view_list, $to_date,
		$from_date, $cat_filter_value, $filter_attribute_search,
		$town_filter, $posted_filters, $search_text, $export = '');
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $selected_form = $this->form_model->get_form($slug);
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($selected_form['app_id']);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'],
			'form_name' => $forms['form_name']);
        }
        $total_record_return = $this->form_results_model->TotalRecByCategoryFilter
		($forms_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search,
		$town_filter, $posted_filters, $search_text);
        $pdata['app_id'] = $selected_form['app_id'];
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'get_data_ajax_category_filter';
        $pdata['slug'] = $slug;
        $pdata['form_list_filter'] = $form_list_filter;
        $data['form_id'] = $slug;
        $pdata['search_text'] = $search_text;
        $data['paging_category_filter'] = $this->parser->parse
		('map/paging_category_filter', $pdata, TRUE);
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
        $this->load->view('map/form_results_data', $data);
    }

    //new instance
    public function paginated_ajax_data_posted() {
        $slug = $_GET['form_id'];
        $to_date = $_GET['to_date'];
        $from_date = $_GET['from_date'];
        $district = $_GET['district'];
        $selected_dc = $_GET['selected_dc'];
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
        $cat_filter_value = isset($_GET['cat_filter_value']) 
		? $_GET['cat_filter_value'] : array();
        $filter_attribute_search = $_GET['filter_attribute_search'];
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
        $array_final = $this->get_heading_n_data_posted($view_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $export = '', $selected_dc);
        $data['headings'] = $array_final['headings'];
        $data['form'] = $array_final['form'];
        $selected_form = $this->form_model->get_form($slug);
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($selected_form['app_id']);
        $forms_list[] = array('form_id' => $slug, 'table_name' => 'zform_' . $slug);
        $total_record_return = $this->form_results_model->return_total_record_posted($forms_list, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $selected_dc);
        $pdata['app_id'] = $selected_form['app_id'];
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'paginated_ajax_data_posted';
        $pdata['slug'] = $slug;
        $pdata['form_list_filter'] = $form_list_filter;
        $data['form_id'] = $slug;
        $pdata['search_text'] = $search_text;
        $data['paging_category_filter'] = $this->parser->parse('map/paging_category_filter', $pdata, TRUE);
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
        $this->load->view('map/form_results_data', $data);
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

    
    //new instance
    public function get_heading_n_data_posted($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text = null, $district, $export = null, $selected_dc) {
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
        $table_headers_array = array();
        $record_array_final = array();
        $heading_query = array();
        $category_name = $value = str_replace('_', '/', $category_name);
        $exclude_array = array('id', 'remote_id', 'imei_no', 'district_name', 'uc_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
        foreach ($forms_list as $form_entity) {
            $table_name = $form_entity['table_name'];
            $results = $this->form_results_model->get_result_paginated_posted($table_name, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $this->perPage, $selected_dc);
//            $results = $this->form_results_model->get_form_results_category($forms_list, $to_date, $from_date, $category_name, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $login_district, $this->perPage);
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
        $exclude_array = array('id', 'remote_id', 'imei_no', 'district_name', 'uc_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
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
                    if (!in_array('image', $table_headers_array)) {
                        $table_headers_array = array_merge($table_headers_array, array('image'));
                    }
                    $record_array = array_merge($record_array, array('image' => $imagess));
                }
                $record_array = array_merge($record_array, array('form_id' => $v['form_id'], 'actions' => $v['id']));
                $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
                $record_array_final[] = $record_array;
            }
            $schema_list = $this->form_results_model->getTableHeadingsFromSchema($table_name);
            $heading_query = array_merge($heading_query, $schema_list);
        }
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
                $form_id_posted = $this->input->post('form_id');
                $changed_category = $this->input->post('changed_category');
                $selected_form = $this->form_model->get_form($form_id_posted);
                $app_id = $selected_form['app_id'];
                $posted_array_filter = array();
                $data_per_filter = array();
                $posted_filters = array();
                $app_settings = $this->app_model->get_app_settings($app_id);
                $app_filter_list = explode(',', $app_settings['map_view_filters']);
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
                    $from_date = "2016-06-03";
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
                                if (!in_array($valueforarray, $filter_exist_array)) {
                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val;
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1';
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
                    $results = $this->form_results_model->get_map_data_paginated_posted($table_name, $to_date, $from_date, $town_filter = null, $posted_filters, $search_text, $login_district);
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
                            if (!in_array($valueforarray, $filter_exist_array)) {
                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val;
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1';
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
                $data['district_filter'] = $app_settings['district_filter'];
                $data['uc_filter'] = $app_settings['uc_filter'];
                $data['map_type_filter'] = !empty($app_settings['map_type_filter']) ? $app_settings['map_type_filter'] : '';
                $data['zoom_level'] = !empty($app_settings['zoom_level']) ? $app_settings['zoom_level'] : '7';
                $data['latitude'] = !empty($app_settings['latitude']) ? $app_settings['latitude'] : '31.58219141239757';
                $data['longitude'] = !empty($app_settings['longitude']) ? $app_settings['longitude'] : '73.7677001953125';
                $data['app_name'] = $selected_app['name'];
                $data['form_for_filter'] = $record_array_final_filter;
                $data['active_tab'] = 'app';
                $data['pageTitle'] = $selected_app['name'] . ' - Map View-DataPlug';
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('map/map_view_1293');
                } else {
                    $this->load->view('map/map_view', $data);
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
                                if (!in_array($valueforarray, $filter_exist_array)) {
                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val;
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1';
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
                            if (!in_array($valueforarray, $filter_exist_array)) {
                                $filter_exist_array[] = $valueforarray;
                                $first_char = substr($valueforarray, 0, 1);
                                $first_char = strtoupper($first_char);
                                if (array_key_exists($first_char, $exist_alpha)) {
                                    $old_val = $exist_alpha[$first_char];
                                    $new_val = (int) $old_val + 1;
                                    $exist_alpha[$first_char] = $new_val;
                                    $pin_name = $first_char . $new_val;
                                } else {
                                    $exist_alpha[$first_char] = '1';
                                    $pin_name = $first_char . '1';
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
                $data['district_filter'] = !empty($app_settings['district_filter']) ? $app_settings['district_filter'] : '';
                $data['uc_filter'] = !empty($app_settings['uc_filter']) ? $app_settings['uc_filter'] : '';
                $data['map_type_filter'] = !empty($app_settings['map_type_filter']) ? $app_settings['map_type_filter'] : '';
                $data['view_type'] = !empty($app_settings['map_type']) ? $app_settings['map_type'] : '';
                $data['zoom_level'] = !empty($app_settings['zoom_level']) ? $app_settings['zoom_level'] : '7';
                $data['latitude'] = !empty($app_settings['latitude']) ? $app_settings['latitude'] : '31.58219141239757';
                $data['longitude'] = !empty($app_settings['longitude']) ? $app_settings['longitude'] : '73.7677001953125';
                $data['app_name'] = $selected_app['name'];
                $data['town_filter'] = $town_list_array;
                $data['headings'] = $heading_array;
                $data['form'] = $record_array_final;
                $data['form_for_filter'] = $record_array_final_filter;
                $data['active_tab'] = 'app';
                $data['pageTitle'] = $selected_app['name'] . ' - Map View-DataPlug';
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('map/map_view_1293');
                } else {
                    $this->load->view('map/map_view', $data);
                }
            }
        } else {
            redirect(base_url() . 'guest');
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
                                if (!in_array($valueforarray, $filter_exist_array)) {
                                    $filter_exist_array[] = $valueforarray;
                                    $first_char = substr($valueforarray, 0, 1);
                                    $first_char = strtoupper($first_char);
                                    if (array_key_exists($first_char, $exist_alpha)) {
                                        $old_val = $exist_alpha[$first_char];
                                        $new_val = (int) $old_val + 1;
                                        $exist_alpha[$first_char] = $new_val;
                                        $pin_name = $first_char . $new_val;
                                    } else {
                                        $exist_alpha[$first_char] = '1';
                                        $pin_name = $first_char . '1';
                                    }
                                    $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                                } else {

                                    if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                        $pin_name = $pin_exist_for_cat[$valueforarray];
                                    }
                                }
                                if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name . ".png")) {
                                    $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                                } else {
                                    $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name . ".png";
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
                                if (!empty($form_item[$headings[$i]])) {
                                    if ($headings[$i] == 'is_take_picture') {
                                    } else if ($headings[$i] == 'image') {
                                        $path = $form_item[$headings[$i]][0]['image'];
                                        $image_row = "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-".date('Y')." - DataPlug <br>By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
                                    } else if ($headings[$i] == 'created_datetime') {
                                        $datetime_row .='<tr><td><b>DATE : </b></td><td>' . date('Y-m-d', strtotime($form_item[$headings[$i]])) . '</td></tr><tr><td><b>TIME : </b></td><td>' . date('H:i:s', strtotime($form_item[$headings[$i]])) . '</td></tr>';
                                    } else {
                                        $map_data .= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $headings[$i]) . ' : ' . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$headings[$i]]) . '<br>\n';
                                        $data_row .= "<tr><td><b>" . preg_replace("/[^A-Za-z0-9\-]/", " ", strtoupper(urldecode($headings[$i]))) . " : </b></td><td>" . preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', strtoupper($form_item[$headings[$i]])) . "</td></tr>";
                                        $id = $form_item['id'];
                                    }
                                }
                            }
                            $final .='["' . $location[0] . '","' . $location[1] . '","' . $form_id . '","' . $icon_filename . '","' . $id . '","' . $category_name . '"] ,';
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
                    if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name . ".png")) {
                        $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                    } else {
                        $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name . ".png";
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
                                $image_row = "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-".date('Y')." - DataPlug <br>By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
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
                redirect(base_url() . 'map/mapview/' . $form_id);
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
                redirect(base_url() . 'map/mapview/' . $form_id);
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
            $this->load->view('map/map_partial', $data);
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
        $page = $this->input->get('page');
        $to_date = $this->input->get('filter_date_to');
        $from_date = $this->input->get('filter_date_from');
        $town_filter = $this->input->get('town_filter');
        if (empty($to_date)) {
            $to_date = "2013-06-03";
            $data['selected_date_to'] = "";
        }
        if (empty($from_date)) {
            $from_date = "2016-06-03";
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
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'] . ".png")) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'] . ".png";
                        }
                        $icon_pair_array = array_merge($icon_pair_array, array($category => $icon_filename_cat));
                        $icon_pair_array_final[] = $icon_pair_array;
                    }
                }
            }
        }
        $results = $this->form_results_model->get_map_data_load_more($form_list_filter, $to_date, $from_date, $page);
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
//        $results_filer = $this->form_results_model->get_form_results($form_id);
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
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'] . ".png")) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'] . ".png";
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'] . ".png")) {

                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'] . ".png";
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'] . ".png")) {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'] . ".png";
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
            $results = $this->form_results_model->get_form_results_by_uc($table_name, $uc);
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
                                        $image_row = "<tr align='center'><td colspan='2'><a href=" . $path . " class='image_colorbox' title='All Rights Reserved © 2013-".date('Y')." - DataPlug Developed By ITU Government of Punjab - Pakistan'><img src=" . $path . " alt='' width='200' height='200'/></a></td></tr>";
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
                            }
                            $pin_exist_for_cat = array_merge($pin_exist_for_cat, array($valueforarray => $pin_name));
                        } else {
                            if (array_key_exists($valueforarray, $pin_exist_for_cat)) {
                                $pin_name = $pin_exist_for_cat[$valueforarray];
                            }
                        }
                        if (!file_exists(FCPATH . "assets/images/map_pins/" . $pin_name . ".png")) {
                            $icon_filename = base_url() . "assets/images/map_pins/default_pin.png";
                        } else {
                            $icon_filename = base_url() . "assets/images/map_pins/" . $pin_name . ".png";
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
        $this->load->view('map/map_activity_popup', $data);
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
        $this->load->view('map/edit_form_partial_map', $data);
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
        $data['paging_date_filter'] = $this->parser->parse('map/paging_date_filter', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['view_page'] = 'paging_date_filter';
        $this->load->view('map/form_results_data', $data);
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
     * Method exposed for iframe of mapview 
     * @param $slug application id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function mapviewframe($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;

        /**
         * multiple form handling system statrs
         */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'form_name' => $forms['form_name']);
        }

        /**
         * multi form ends herer.....
         */
        $heading_array = array();
        /*
         * for purpose of multiple form post back 
         * form id
         */
        if ($this->input->post('form_id')) {
            $form_list_posted = $this->input->post('form_list');
            if (!$form_list_posted) {
                redirect(base_url() . 'map/mapviewframe/' . $slug);
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
            $form_id_posted = $this->input->post('form_id');
            $data['form_id'] = $form_id_posted;
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
                $from_date = "2016-06-03";
                $data['selected_date_from'] = "";
            }
            if (strtotime($to_date) > strtotime($from_date)) {
                $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                redirect(base_url() . 'map/mapview/' . $form_id);
            }
            $total_result = $this->form_results_model->get_form_results_count_for_map_ajax($forms_list, $to_date, $from_date, $town_filter = null);
            $totalPages = ceil($total_result / $this->perMap);
            $data['totalPages'] = $totalPages;
            $filter_date_map = $this->input->post('filter_date_map');
            $selected_form = $this->form_model->get_form($form_id_posted);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            /**
             * displaying dynamic filters
             */
            //Get Multiple form filtesr
            $multiple_filters = $this->form_model->get_form_filters($forms_list);
            $filter_attribute = array();
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
            }
            $data['filter_attribute'] = $filter_attribute;
            $record_array_final = array();

            /**
             * for categry listing
             */
            $record_array_final_filter = array();
            $results_filer = $this->form_results_model->get_form_results_filters($forms_list);
            $town_array = array();
            $uc_array = array();
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_filer as $k => $v) {
                $town_name = $v['town_name'];
                if ($town_name) {
                    if (!in_array($town_name, $town_array)) {
                        array_push($town_array, $town_name);
                    }
                }
                $uc_name = $v['uc_name'];
                if ($uc_name) {
                    if (!in_array($uc_name, $uc_array)) {
                        array_push($uc_array, $uc_name);
                    }
                }
                $record_array = array();
                $result_json = $v['record'];
                $date = $v['created_datetime'];
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }

                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (!in_array($valueforarray, $filter_exist_array)) {

                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                if (!in_array('created_datetime', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('created_datetime'));
                }
                $imagess = $this->form_results_model->getResultsImages($v['id']);
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
                    $record_array_final_filter[] = $record_array;
                }
            }
            /**
             * filter end hrer
             */
            $town_array[] = asort($town_array);
            array_pop($town_array);
            $data['town'] = $town_array;
            $uc_array[] = asort($uc_array);
            array_pop($uc_array);
            $data['uc'] = $uc_array;
            $form_list_filter = array();
            foreach ($form_list_posted as $list) {
                $form_list_filter[] = array('form_id' => $list);
            }
            $results = $this->form_results_model->get_form_results_for_map($form_list_filter, $to_date, $from_date, $town_filter = null);
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                $date = $v['created_datetime'];
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                if (!in_array('created_datetime', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('created_datetime'));
                }
                $imagess = $this->form_results_model->getResultsImages($v['id']);
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
            $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
            /**
             * sorting based on filter attribute of the array data
             * will called a helper class SortAssociativeArray WITH 
             * its call back function call
             */
            $data['headings'] = $heading_array;
            $data['form'] = $record_array_final;

            foreach ($filter_attribute as $filter_attribute_value) {
                uasort($record_array_final, array(new SortAssociativeArray($filter_attribute_value), "call"));
            }
            $data['form_html'] = $selected_form['description'];
            $data['filter'] = $selected_form['filter'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($selected_form['app_id']);
            $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
            $data['district_filter'] = $app_settings['district_filter'];
            $data['uc_filter'] = $app_settings['uc_filter'];
            $data['zoom_level'] = !empty($app_settings['zoom_level']) ? $app_settings['zoom_level'] : '7';
            $data['latitude'] = !empty($app_settings['latitude']) ? $app_settings['latitude'] : '31.58219141239757';
            $data['longitude'] = !empty($app_settings['longitude']) ? $app_settings['longitude'] : '73.7677001953125';
            $data['app_name'] = $selected_app['name'];
            $data['form_for_filter'] = $record_array_final;
            $data['active_tab'] = 'app';
            $data['pageTitle'] = $selected_app['name'] . ' - Map View-DataPlug';
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('map/map_view_frame', $data);
        } else {
            $view_list = array();
            foreach ($forms_list as $final_view) {
                $view_list = array_merge($view_list, array($final_view['form_name'] => $final_view['form_id']));
            }
            $view_list = array_flip($view_list);
            $data['form_lists'] = $view_list;
            $data['form_list_selected'] = array_flip($view_list);
            $first_form_id = $forms_list[0]['form_id'];
            $data['form_id'] = $first_form_id;
            $total_result = $this->form_results_model->get_form_results_count_for_map_ajax($forms_list, $to_date = '', $from_date = '', $town_filter = '');
            $totalPages = ceil($total_result / $this->perMap);
            $data['totalPages'] = $totalPages;
            $selected_form = $this->form_model->get_form($first_form_id);
            $data['form_name'] = $selected_form['name'];
            $data['app_id'] = $selected_form['app_id'];
            if ($first_form_id == 9 || $first_form_id == 40)
                $data['district_selected'] = "31.451920768643237,74.2976188659668";
            else
                $data['district_selected'] = "";

            //Get Multiple form filtesr
            $multiple_filters = $this->form_model->get_form_filters($forms_list);
            $filter_attribute = array();
            foreach ($multiple_filters as $key => $value) {
                array_push($filter_attribute, $value['filter']);
            }
            $data['filter_attribute'] = $filter_attribute;
            $data['boundaries'] = '';

            /**
             * for categry listing
             */
            $record_array_final_filter = array();
            $results_filer = $this->form_results_model->get_form_results_filters($forms_list);
            $town_array = array();
            $uc_array = array();
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results_filer as $k => $v) {
                $town_name = $v['town_name'];
                if ($town_name) {
                    if (!in_array($town_name, $town_array)) {
                        array_push($town_array, $town_name);
                    }
                }
                $uc_name = $v['uc_name'];
                if ($uc_name) {
                    if (!in_array($uc_name, $uc_array)) {
                        array_push($uc_array, $uc_name);
                    }
                }
                $record_array = array();
                $result_json = $v['record'];
                $date = $v['created_datetime'];
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                if (!in_array('created_datetime', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('created_datetime'));
                }
                $imagess = $this->form_results_model->getResultsImages($v['id']);
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
                    $record_array_final_filter[] = $record_array;
                }
            }
            $town_array[] = asort($town_array);
            array_pop($town_array);
            $data['town'] = $town_array;

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
            $record_array_final = array();
            $results = $this->form_results_model->get_form_results_ajax($forms_list);
            $filter_exist_array = array();
            $pin_exist_for_cat = array();
            $col_pin = 0;
            $exist_alpha = array();
            foreach ($results as $k => $v) {
                $record_array = array();
                $result_json = $v['record'];
                $date = $v['created_datetime'];
                $result_array = json_decode($result_json);
                foreach ($result_array as $key => $value) {
                    if (!in_array($key, $heading_array)) {
                        $heading_array = array_merge($heading_array, array($key));
                    }
                    if (in_array($key, $filter_attribute)) {
                        $value = trim($value);
                        $valueforarray = str_replace(' ', '_', $value);
                        if (!in_array($valueforarray, $filter_exist_array)) {
                            $filter_exist_array[] = $valueforarray;
                            $first_char = substr($valueforarray, 0, 1);
                            $first_char = strtoupper($first_char);
                            if (array_key_exists($first_char, $exist_alpha)) {
                                $old_val = $exist_alpha[$first_char];
                                $new_val = (int) $old_val + 1;
                                $exist_alpha[$first_char] = $new_val;
                                $pin_name = $first_char . $new_val;
                            } else {
                                $exist_alpha[$first_char] = '1';
                                $pin_name = $first_char . '1';
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
                if (!in_array('created_datetime', $heading_array)) {
                    $heading_array = array_merge($heading_array, array('created_datetime'));
                }

                $imagess = $this->form_results_model->getResultsImages($v['id']);
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
            $data['selected_form'] = $first_form_id;
            $data['locations'] = $this->getMapHtmlInfo($record_array_final, $heading_array, $filter_attribute);
            $town_lists = $this->app_users_model->get_towns($selected_form['app_id']);
            $town_list_array = array();
            foreach ($town_lists as $towns) {
                if (!in_array($towns['town'], $town_list_array)) {
                    $town_list_array = array_merge($town_list_array, array($towns['town'] => $towns['town']));
                }
            }
            $data['form_html'] = $selected_form['description'];
            $data['filter'] = $selected_form['filter'];
            $data['app_id'] = $selected_form['app_id'];
            $selected_app = $this->app_model->get_app($selected_form['app_id']);
            $app_settings = $this->app_model->get_app_settings($selected_form['app_id']);
            $data['district_filter'] = !empty($app_settings['district_filter']) ? $app_settings['district_filter'] : '';
            $data['uc_filter'] = !empty($app_settings['uc_filter']) ? $app_settings['uc_filter'] : '';
            $data['view_type'] = !empty($app_settings['map_type']) ? $app_settings['map_type'] : '';
            $data['zoom_level'] = !empty($app_settings['zoom_level']) ? $app_settings['zoom_level'] : '';
            $data['latitude'] = !empty($app_settings['latitude']) ? $app_settings['latitude'] : '31.58219141239757';
            $data['longitude'] = !empty($app_settings['longitude']) ? $app_settings['longitude'] : '73.7677001953125';
            $data['app_name'] = $selected_app['name'];
            $data['town_filter'] = $town_list_array;
            $data['headings'] = $heading_array;
            $data['form'] = $record_array_final;
            $data['form_for_filter'] = $record_array_final_filter;
            $data['active_tab'] = 'app';
            $data['pageTitle'] = $selected_app['name'] . ' - Map View-DataPlug';
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('map/map_view_frame', $data);
        }
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
                    if (!in_array($valueforarray, $filter_exist_array)) {
                        $filter_exist_array[] = $valueforarray;
                        $first_char = substr($valueforarray, 0, 1);
                        $first_char = strtoupper($first_char);
                        if (array_key_exists($first_char, $exist_alpha)) {
                            $old_val = $exist_alpha[$first_char];
                            $new_val = (int) $old_val + 1;
                            $exist_alpha[$first_char] = $new_val;
                            $pin_name = $first_char . $new_val;
                        } else {
                            $exist_alpha[$first_char] = '1';
                            $pin_name = $first_char . '1';
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
        $data['pageTitle'] = $data['form_name'] . ' Records - Map View-DataPlug';
        $this->load->view('templates/header_iframe', $data);
        $this->load->view('map/map_view_single', $data);
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
        $category_final = array();
        $sub_final = array();
        $final_json = array();
        $possible_filters = explode(',', $filter_attribute['possible_filters']);
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