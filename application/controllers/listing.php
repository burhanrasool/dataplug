<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listing extends CI_Controller {
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
        $this->load->view('listing/gallery_partial', $data);
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
        $data['paging'] = $this->parser->parse('listing/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('listing/form_results_data', $data);
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
        $data['form'] = $array_final['form'];
        $total_record_return = $this->form_results_model->return_total_record($form_single_to_query);
        $pdata['app_id'] = $slug;
        $pdata['TotalRec'] = $total_record_return;
        $pdata['perPage'] = $this->perPage;
        $pdata['ajax_function'] = 'pagination_ajax_data';
        $pdata['form_id'] = $slug;
        $pdata['slug'] = $slug;
        $data['paging'] = $this->parser->parse('listing/paging', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['view_page'] = 'paging';
        $this->load->view('listing/form_results_data', $data);
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
        $data['paging_category_filter'] = $this->parser->parse('listing/paging_category_filter', $pdata, TRUE);
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
        $this->load->view('listing/form_results_data', $data);
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
        $cat_filter_value = isset($_GET['cat_filter_value']) ? $_GET['cat_filter_value'] : array();
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
        $data['paging_category_filter'] = $this->parser->parse('listing/paging_category_filter', $pdata, TRUE);
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
        $this->load->view('listing/form_results_data', $data);
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
                redirect(base_url() . '/apps');
            }
            /** multiple form handling system statrs here * */
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            if (empty($forms_list)) {
                $this->session->set_flashdata('validate', array('message' => 'Invalid App Request', 'type' => 'warning'));
                redirect(base_url() . 'app');
            }
            $app_settings = $this->app_model->get_app_settings($slug);
            if ($app_settings['district_filter'] == 'On') {
                $district_list = $this->form_results_model->get_distinct_district($slug);
                $data['district_list'] = $district_list;
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
                    $from_date = "2016-06-03";

                    $data['selected_date_from'] = "";
                }
                $cat_filter = $this->input->post('cat_filter');
                $cat_filter_value = $cat_filter;
                $data['selected_cat_filter'] = $cat_filter;
                if (strtotime($to_date) > strtotime($from_date)) {
                    $this->session->set_flashdata('validate', array('message' => 'Invalid Date selection. From Date should be greater than To Date.', 'type' => 'warning'));
                    redirect(base_url() . 'listing/results/' . $form_id);
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
                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
                $array_final = array();
                $array_final = $this->get_heading_n_data_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $changed_category, $town_filter, $posted_filters, $search_text, $district, $export = '', $selected_dc);
                $data['headings'] = $array_final['headings'];
                $data['form'] = $array_final['form'];
                $total_record_return = $this->form_results_model->return_total_record_posted($form_list_filter, $to_date, $from_date, $cat_filter_value, $filter_attribute_search, $town_filter, $posted_filters, $search_text, $district, $selected_dc);
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
                $subdata['paging_category_filter'] = $this->parser->parse('listing/paging_category_filter', $pdata, TRUE);
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
                $data['body_content'] = $this->parser->parse('listing/form_results_data', $subdata, TRUE);
                $data['selected_form'] = $form_id;
                $data['category_values'] = $category_values;
                $data['app_id'] = $selected_form['app_id'];
                $data['app_comments'] = $this->form_model->get_comments($selected_form['app_id']);
                $data['pageTitle'] = $selected_app['name'] . ' Records - List View-DataPlug';
                $selected_app = $this->app_model->get_app($data['app_id']);
                $data['app_name'] = $selected_app['name'];
                $all_visits_hidden = $this->input->post('all_visits_hidden');
                $data['all_visits_hidden'] = $all_visits_hidden;
                $data['active_tab'] = 'form_results';
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('listing/results_1293');
                } else if ($slug == 1567) {
                    $final_dc = array('' => 'Select All');
                    $disbursement_center_lists = $this->form_results_model->get_distinct_d_center(1567);
                    foreach ($disbursement_center_lists as $dc) {
                        $final_dc = array_merge($final_dc, array($dc['Disbursement_Center'] => $dc['Disbursement_Center']));
                    }
                    $data['selected_dc'] = $selected_dc;

                    $data['d_center'] = $final_dc;
                    $this->load->view('listing/results_1567', $data);
                } else {
                    $this->load->view('listing/results');
                }
                $this->load->view('templates/footer', $data);
            } else {
                $form_single_to_query = array();
                $form_single_to_query[] = array('form_id' => $all_forms[0]['form_id'], 'table_name' => 'zform_' . $all_forms[0]['form_id'], 'form_name' => $all_forms[0]['form_name']);
                $data['selected_district'] = '';
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
                $session_data = $this->session->userdata('logged_in');
                session_to_page($session_data, $data);
                $login_district = $session_data['login_district'];
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
                $subdata['paging'] = $this->parser->parse('listing/paging', $pdata, TRUE);
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
                $data['body_content'] = $this->parser->parse('listing/form_results_data', $subdata, TRUE);
                $data['pageTitle'] = $selected_app['name'] . ' Records - List View-DataPlug';
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
                $data['active_tab'] = 'form_results';
                $this->load->view('templates/header', $data);
                if ($slug == 1293) {
                    $this->load->view('listing/results_1293');
                } else if ($slug == 1567) {
                    $final_dc = array('' => 'Select All');
                    $disbursement_center_lists = $this->form_results_model->get_distinct_d_center(1567);
                    foreach ($disbursement_center_lists as $dc) {
                        $final_dc = array_merge($final_dc, array($dc['Disbursement_Center'] => $dc['Disbursement_Center']));
                    }
                    $data['selected_dc'] = array();
                    $data['d_center'] = $final_dc;
                    $this->load->view('listing/results_1567', $data);
                } else {
                    $this->load->view('listing/results');
                }
                $this->load->view('templates/footer', $data);
            }
        } else {
            redirect(base_url() . 'guest');
        }
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
//dump($forms_list);
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
        $this->load->view('listing/edit_form_partial', $data);
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
        $data['paging_date_filter'] = $this->parser->parse('listing/paging_date_filter', $pdata, TRUE);
        $data['all_form_results'] = $data['form'];
        $data['total_record_return'] = $total_record_return;
        $data['page_variable'] = $page_variable;
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['view_page'] = 'paging_date_filter';
        $this->load->view('listing/form_results_data', $data);
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
                $data['pageTitle'] = 'Export Results -DataPlug';
                $this->load->view('templates/header', $data);
                $this->load->view('listing/export_results', $data);
                $this->load->view('templates/footer', $data);
            } else if ($this->input->post('form_id')) {
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
                echo $data_form;
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
                $data['pageTitle'] = 'Export Results -DataPlug';
                $this->load->view('templates/header', $data);
                $this->load->view('listing/export_results', $data);
                $this->load->view('templates/footer', $data);
            }
        } else {
            //If no session, redirect to login page
            redirect(base_url() . 'guest');
        }
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
            $results = $this->form_results_model->get_results_for_export($table_name, $to_date, $from_date);
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
            $this->load->view('listing/comments_adding', $data);
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
        $this->db->update('app_settings', $app_settings);
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