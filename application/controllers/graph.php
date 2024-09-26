<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Graph extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('app_users_model');
        $this->load->model('app_installed_model');
        $this->load->model('form_model');
        $this->load->model('form_results_model');
        $this->load->model('app_released_model');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('custome_helper');
        // if (!$this->acl->hasSuperAdmin()) {
        //     if($this->acl->hasPermission('complaint','Access only complaint module')){
        //         redirect(base_url() . 'complaintSystem');
        //     }
        // }
    }

    /**
     * initial renderer of graph for each applicaoitn based on Application id
     * @param  $slug Applicatoin Id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function dashboard($slug) {
        ini_set('memory_limit', '-1');
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;

        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $login_district = $session_data['login_district'];

            /**
             * multiple form handling system statrs
             */
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            $data['form_lists'] = $forms_list;

            $selected_form = $this->form_model->get_form($forms_list[0]['form_id']);
            $all_kinds_of_map_app = array(0);

            $app_settings = $this->app_model->get_app_settings($slug);
            $district_list = array();
            $list_view_settings = get_graph_view_settings($slug);
//            if ($app_settings['district_filter'] == 'On') {
//            if (isset($list_view_settings->district_filter) && $list_view_settings->district_filter == 1) {
//
//                $district_list = $this->form_results_model->get_distinct_district($slug);
//                $data['district_list'] = $district_list;
//
//            }
//            if ($app_settings['sent_by_filter'] == 'On') {
            if (isset($list_view_settings->sent_by_filter) && $list_view_settings->sent_by_filter == 1) {
                $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
                $data['sent_by_list'] = $sent_by_list;
            }

            if ($this->input->post('custom1567')) {
                $form_id = $this->input->post('customform');
                /** Get filters from  multiple forms * */
                $multiple_filters = $this->form_model->get_form_filters($forms_list);

                $filter_attribute = array();
                $form_html_multiple = array();

                foreach ($multiple_filters as $key => $value) {
                    array_push($filter_attribute, $value['filter']);
                    array_push($form_html_multiple, $value['description']);
                }
                $data['filter_attribute'] = array($filter_attribute[0]);
                $data['form_html'] = $form_html_multiple;

                $data_array = $this->get_category_list($form_id);
                $data['filter'] = $this->input->post('filter');
                $data['selected_form'] = '';
                $category_list = $data_array['category_list'];
                $filter_attribute = $data_array['filter_attribute'];
                $data['filter_attribute'] = $filter_attribute;

                $category_list[] = asort($category_list);
                array_pop($category_list);
                $data['category_list'] = $category_list;

                $data['disbursement_rec'] = '';
                $date_search = '';
//                if ($form_id == 1654) {
//                    //add part for disbursment app - Start
//                    $formid = $form_id;
//                    $date_search = $this->input->post('customdate');
//                    $disbursment_rec = $this->form_results_model->get_disbursment_record($formid, $date_search);
//                    $final_disb_array = array();
//                    $tehsil_disb_array = array();
//                    foreach ($disbursment_rec as $myrep) {
//                        if (!key_exists($myrep['District'], $final_disb_array)) {
//                            $final_disb_array[$myrep['District']] = array();
//                        }
//
//                        if (!key_exists($my_rep['Tehsil'], $final_disb_array[$myrep['District']])) {
//                            $final_disb_array[$myrep['District']][$myrep['Tehsil']] = array();
//                        }
//
//                        if (!key_exists($myrep['Disbursement_Center'], $final_disb_array[$myrep['District']][$myrep['Tehsil']])) {
//                            $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']] = array();
//                        }
//
//                        $available_fac = explode(',', $myrep['Facilities_Available']);
//                        foreach ($available_fac as $avail_value) {
//                            if (!key_exists('facilities', $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']])) {
//                                $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'] = array();
//                            }
//                            if (!in_array($avail_value, $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'])) {
//                                array_push($final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'], $avail_value);
//                            }
//                        }
//                    }
//
//                    $data['disbursement_rec'] = $final_disb_array;
//                }

                $data['disbursement_type_rec'] = '';
                if ($form_id == 1768) {
                    //add part for incident report form - start
                    $final_disb_type_array = array();
                    $disbursment_type_rec = $this->form_results_model->get_disbursment_type_record();
                    foreach ($disbursment_type_rec as $mytype) {
                        if (!key_exists($mytype['Type'], $final_disb_type_array)) {
                            $final_disb_type_array[$mytype['Type']] = array();
                        }

                        if (!key_exists($mytype['District'], $final_disb_type_array[$mytype['Type']])) {
                            $final_disb_type_array[$mytype['Type']][$mytype['District']] = array('count' => 1);
                        } else {
                            $final_disb_type_array[$mytype['Type']][$mytype['District']]['count'] = (int) $final_disb_type_array[$mytype['Type']][$mytype['District']]['count'] + 1;
                        }
                    }
                    $data['disbursement_type_rec'] = $final_disb_type_array;
                }
                //add part for incident report form - End
                $data['district_categorized'] = array();
                $totalRecords = 0;
                $category_list_count = array();
                $data['from_date'] = '';
                $data['to_date'] = '';
                $from_date = '2000-9-19';
                $to_date = '2050-9-19';
                foreach ($category_list as $category) {
                    $categorieslist = explode(',', $category);
                    foreach ($categorieslist as $catu) {
                        $category_count = $this->form_results_model->getCountCatgoryBase($form_id, $catu, $filter_attribute, $to_date, $from_date, $selected_district = '');
                        $category_list_count = array_merge($category_list_count, array(str_replace("`", "", $catu) => $category_count));
                        $totalRecords += $category_count;
                    }
                }

                $category_list_count[] = arsort($category_list_count);
                array_pop($category_list_count);

                if ($slug == 1293) {
                    $total_result = $this->form_results_model->return_total_record($forms_list);
                    $total_record = $total_result;
                } else {
                    $total_record = $totalRecords;
                }
                $selected_app = $this->app_model->get_app($slug);
                $data['app_name'] = $selected_app['name'];
                $data['form_id'] = $form_id;
                $data['category_list'] = $category_list;
                $data['total_records'] = $total_record;
                $data['category_list_count'] = $category_list_count;
                $data['graph_text'] = 'Data Records By Category';
                $data['pageTitle'] = " Graph-View";
                $data['graph_type'] = 'Category';
                $data['app_id'] = $selected_form['app_id'];
                $data['active_tab'] = 'graph-category';
                $data['date_search'] = $date_search;
                $this->load->view('templates/header', $data);
                if ($slug == '1567') {
                    $this->load->view('graph/simple_category_graph_1567', $data);
                } else if ($slug == '1293') {
                    $this->load->view('graph/simple_category_graph_1293', $data);
                } else {
                    $this->load->view('graph/simple_category_graph', $data);
                }
                $this->load->view('templates/footer', $data);
            } else {

                if ($this->input->post()) {
                    $changed_category = $this->input->post('filter');
                    $form_id = $this->input->post('form_id');

                    $selected_sent_by = $this->input->post('sent_by_list');
                    $data['selected_sent_by'] = $selected_sent_by;

                    $form_single_to_query = array();
                    $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);

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
                    /* Ends filter */

                    $data_array = $this->get_category_list($form_id);
//                    print_r($data_array);die;
                    $data['filter'] = $selected_form['filter'];
                    $data['selected_form'] = $form_id;
                    $category_list = $data_array['category_list'];

                    $category_list[] = asort($category_list);
                    array_pop($category_list);
                    //$data['category_list'] = $category_list;
                    //add part for disbursment app - Start
//                    $formid = '1654';
                    $date_search = '';
                    $final_disb_array = array();
                    $tehsil_disb_array = array();


                    $data['disbursement_rec'] = $final_disb_array;
                  
                    $from_date = $this->input->post('filter_date_from');
                    $to_date = $this->input->post('filter_date_to');
//                  $district_list = $this->form_results_model->get_distinct_district($slug);


                    $final_district_wise_array = array();
                    $final_district_wise_array2 = array();
                    $final = array();


                    $filter_result = get_graph_view_settings($selected_form['app_id']);
                    if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {

                        $new_category_list = array();
                        foreach ($category_list as $cl_key => $cl_value) {
                            $cl_expl = explode(',', $cl_value);
                            $cl_expl = array_filter($cl_expl);
                            foreach ($cl_expl as $clx_key => $clx_value) {

                                if (!array_key_exists($clx_value, $new_category_list)) {
                                    $new_category_list[$clx_value] = $clx_value;
                                }
                            }
                        }
                        $data['category_list'] = $new_category_list;

                        foreach ($new_category_list as $cat_listv) {
                            $district_wise_catorized = $this->form_results_model->get_district_categorized_count_new($form_id, "", $filter_attribute[0], $cat_listv, $from_date, $to_date);

                            foreach ($district_wise_catorized as $key => $val) {
                                if (!array_key_exists($val['district_name'], $final_district_wise_array2)) {
                                    $final_district_wise_array2[$val['district_name']] = array();
                                    foreach ($new_category_list as $cat_listvv) {
                                        $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array('district' => $val['district_name'], $cat_listvv => '0', 'total' => '0'));
                                    }
                                }
                                if (array_key_exists($val['district_name'], $final_district_wise_array2)) {
                                    $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array($cat_listv => $val['total'], 'total' => $final_district_wise_array2[$val['district_name']]['total'] + $val['total']));
                                } else {
                                    $final_district_wise_array2[$val['district_name']] = array('district' => $val['district_name'], $cat_listv => $val['total'], 'total' => $val['total']);
                                }
                            }
                        }
                    }


                    $data['district_categorized'] = $final_district_wise_array2;


                    //$data['district_categorized'] = $final_district_wise_array2;

                    $totalRecords = 0;
                    $category_list_count = array();

                    $category_count = $this->form_results_model->getCountCatgoryBaseNew($form_id, "", $filter_attribute, $from_date, $to_date, $selected_district = '');
                    $category_list_count = array();
                    foreach ($category_count as $key => $val) {
                        $totalRecords+=$val['total'];
                        if (array_key_exists($val[$filter_attribute[0]], $category_list_count)) {
                            $category_list_count[$val[$filter_attribute[0]]] += $val['total'];
                        } else {
                            $category_list_count[$val[$filter_attribute[0]]] = $val['total'];
                        }
                    }

                    $category_list_count[] = arsort($category_list_count);
                    array_pop($category_list_count);

                    $total_record = $totalRecords;
                    $selected_app = $this->app_model->get_app($slug);

                    $filter_options = '';

                    if (isset($filter_result->filters->$selected_form['id'])) {
                        $app_filter_list = $filter_result->filters->$selected_form['id'];
                        $filter_options .= "<option value=''>Select One</option>";
                        if (!empty($app_filter_list)) {
                            foreach ($app_filter_list as $key => $val) {
                                $print_val = str_replace("_", " ", $val);
                                if ($changed_category == $val) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                $filter_options .= "<option echo $selected value='$val'>$print_val</option>";
                            }
                        }
                    }


                    $data['filter_result'] = $filter_result;
                    $data['filter_options'] = $filter_options;
                    $data['from_date'] = $from_date;
                    $data['to_date'] = $to_date;
                    $data['app_name'] = $selected_app['name'];
                    $data['form_id'] = $form_id;
                    //$data['category_list'] = $category_list;
                    $data['total_records'] = $total_record;
                    $data['category_list_count'] = $category_list_count;
                    $data['graph_text'] = 'Graph By <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
                    $data['pageTitle'] = " Graph-View";
                    $data['graph_type'] = 'Category';
                    $data['app_id'] = $selected_form['app_id'];
                    $data['active_tab'] = 'graph-category';

                    $data['date_search'] = $date_search;
                    $this->load->view('templates/header', $data);
                    if ($slug == '1567') {
                        $this->load->view('graph/simple_category_graph_1567', $data);
                    } else if ($slug == '1293') {
                        $this->load->view('graph/simple_category_graph_1293', $data);
                    } else {
                        $this->load->view('graph/simple_category_graph', $data);
                    }
                    $this->load->view('templates/footer', $data);
                } else {

                    $form_id = $forms_list[0]['form_id'];
                    $form_single_to_query = array();
                    $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);
                    /** Get filters from  multiple forms * */
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
                    //echo "<pre>";
//                print_r($multiple_filters);die;
                    $data['default_selected_category'] = $default_selected_category;


                    $data['filter_attribute'] = array($filter_attribute[0]);
                    $data['form_html'] = $form_html_multiple;
                    /* Ends filter */
                    $data_array = $this->get_category_list($form_id);
                    $data['filter'] = $selected_form['filter'];
                    $data['selected_form'] = '';
                    $category_list = $data_array['category_list'];
                    $category_list[] = asort($category_list);
                    array_pop($category_list);
                    //$data['category_list'] = $category_list;
                    $new_category_list = array();
                    foreach ($category_list as $cl_key => $cl_value) {
                        $cl_expl = explode(',', $cl_value);
                        $cl_expl=array_filter($cl_expl);
                        foreach ($cl_expl as $clx_key => $clx_value) {

                            if (!array_key_exists($clx_value, $new_category_list)) {
                                $new_category_list[$clx_value] = $clx_value;
                            }
                        }
                    }

                    $data['category_list'] = $new_category_list;
                    $date_search = '';

                    $final_disb_array = array();
                    $tehsil_disb_array = array();

                    $data['disbursement_rec'] = $final_disb_array;

                    //add part for incident report form - End

                    $to_date = '';
                    $from_date = '';
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    if(isset($_REQUEST['from_date'])){
                        $to_date = $_REQUEST['to_date'];
                        $from_date = $_REQUEST['from_date'];
                        $data['from_date'] = $from_date;
                        $data['to_date'] = $to_date;
                    }
                    
                    $final_district_wise_array = array();
                    $final_district_wise_array2 = array();
                    $final = array();

                    $filter_result = get_graph_view_settings($selected_form['app_id']);
                    if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {
                        foreach ($new_category_list as $cat_listv) {
                            $district_wise_catorized = $this->form_results_model->get_district_categorized_count_new($form_id, "", $filter_attribute[0], $cat_listv, $from_date, $to_date);

                            foreach ($district_wise_catorized as $key => $val) {
                                if (!array_key_exists($val['district_name'], $final_district_wise_array2)) {
                                    $final_district_wise_array2[$val['district_name']] = array();
                                    foreach ($new_category_list as $cat_listvv) {
                                        $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array('district' => $val['district_name'], $cat_listvv => '0', 'total' => '0'));
                                    }
                                }
                                if (array_key_exists($val['district_name'], $final_district_wise_array2)) {
                                    $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array($cat_listv => $val['total'], 'total' => $final_district_wise_array2[$val['district_name']]['total'] + $val['total']));
                                } else {
                                    $final_district_wise_array2[$val['district_name']] = array('district' => $val['district_name'], $cat_listv => $val['total'], 'total' => $val['total']);
                                }
                            }
                        }
                    }

                    $data['district_categorized'] = $final_district_wise_array2;

                    $totalRecords = 0;
                    $category_list_count = array();

                    $category_count = $this->form_results_model->getCountCatgoryBaseNew($form_id, "", $filter_attribute, $from_date, $to_date, $selected_district = '');
                    $category_list_count = array();

                    foreach ($category_count as $key => $val) {
                        $totalRecords+=$val['total'];
                        if (array_key_exists($val[$filter_attribute[0]], $category_list_count)) {
                            $category_list_count[$val[$filter_attribute[0]]] += $val['total'];
                        } else {
                            $category_list_count[$val[$filter_attribute[0]]] = $val['total'];
                        }
                    }

                    $category_list_count[] = arsort($category_list_count);
                    array_pop($category_list_count);
                    if ($slug == 1293) {
                        $total_result = $this->form_results_model->return_total_record($forms_list);
                        $total_record = $total_result;
                    } else {
                        $total_record = $totalRecords;
                    }
                    $selected_app = $this->app_model->get_app($slug);

                    $filter_options = '';

                    $app_filter_list = array();
                    if (isset($filter_result->filters->$selected_form['id'])) {
                        $app_filter_list = $filter_result->filters->$selected_form['id'];
                        $filter_options.="<option value=''>Select One</option>";
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


                    $data['filter_result'] = $filter_result;
                    $data['filter_options'] = $filter_options;
                    $data['app_name'] = $selected_app['name'];
                    $data['form_id'] = $form_id;
                    $data['total_records'] = $total_record;
                    $data['category_list_count'] = $category_list_count;
                    $data['graph_text'] = 'Graph By <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
                    $data['pageTitle'] = " Graph-View";
                    $data['graph_type'] = 'Category';
                    $data['app_id'] = $selected_form['app_id'];
                    $data['active_tab'] = 'graph-category';

                    $data['date_search'] = $date_search;
                    $this->load->view('templates/header', $data);
                    if ($slug == '1567') {
                        $this->load->view('graph/simple_category_graph_1567', $data);
                    } else if ($slug == '1293') {
                        $this->load->view('graph/simple_category_graph_1293', $data);
                    } else {

                        $this->load->view('graph/simple_category_graph', $data);
                    }
                    $this->load->view('templates/footer', $data);
                }
            }
        } else {
            redirect(base_url());
        }
    }

    /**
     * initial renderer of graph for each applicaoitn based on Application id
     * @param  $slug Applicatoin Id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function subreport($slug) {
        ini_set('memory_limit', '-1');
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;

        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $login_district = $session_data['login_district'];

            /**
             * multiple form handling system statrs
             */
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            $data['form_lists'] = $forms_list;

            $selected_form = $this->form_model->get_form($forms_list[0]['form_id']);
            $all_kinds_of_map_app = array(0);

            $app_settings = $this->app_model->get_app_settings($slug);
            $district_list = array();
            $list_view_settings = get_graph_view_settings($slug);






            $form_id = $forms_list[0]['form_id'];
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);
            /** Get filters from  multiple forms * */
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
            //echo "<pre>";
//                print_r($multiple_filters);die;
            $data['default_selected_category'] = $default_selected_category;


            $data['filter_attribute'] = array($filter_attribute[0]);
            $data['form_html'] = $form_html_multiple;
            /* Ends filter */
            $data_array = $this->get_category_list($form_id);
            $data['filter'] = $selected_form['filter'];
            $data['selected_form'] = '';
            $category_list = $data_array['category_list'];
            $category_list[] = asort($category_list);
            array_pop($category_list);
            //$data['category_list'] = $category_list;
            $new_category_list = array();
            foreach ($category_list as $cl_key => $cl_value) {
                $cl_expl = explode(',', $cl_value);
                $cl_expl = array_filter($cl_expl);
                foreach ($cl_expl as $clx_key => $clx_value) {

                    if (!array_key_exists($clx_value, $new_category_list)) {
                        $new_category_list[$clx_value] = $clx_value;
                    }
                }
            }
            $data['category_list'] = $new_category_list;
            $date_search = '';

            $final_disb_array = array();
            $tehsil_disb_array = array();

            $data['disbursement_rec'] = $final_disb_array;

            //add part for incident report form - End

            $data['from_date'] = '';
            $data['to_date'] = '';
            $from_date = $_REQUEST['from_date'];
            $to_date = $_REQUEST['to_date'];
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $final_district_wise_array = array();
            $final_district_wise_array2 = array();
            $final = array();

            $filter_result = get_graph_view_settings($selected_form['app_id']);
            if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {
                foreach ($new_category_list as $cat_listv) {
                    $district_wise_catorized = $this->form_results_model->get_school_categorized_count_new($form_id, $_REQUEST['district'], $filter_attribute[0], $cat_listv, $from_date, $to_date);

                    foreach ($district_wise_catorized as $key => $val) {
                        if (!array_key_exists($_REQUEST['district'], $final_district_wise_array2)) {
                            $final_district_wise_array2[$_REQUEST['district']] = array();
                            foreach ($new_category_list as $cat_listvv) {
                                $final_district_wise_array2[$_REQUEST['district']] = array_merge($final_district_wise_array2[$_REQUEST['district']], array($cat_listvv => $cat_listvv, 'total' => $val['total']));
                            }
                        }
                        if (array_key_exists($_REQUEST['district'], $final_district_wise_array2)) {
                            $final_district_wise_array2[$_REQUEST['district']] = array_merge($final_district_wise_array2[$_REQUEST['district']], array($cat_listv => $cat_listv, 'total' => $final_district_wise_array2[$_REQUEST['district']]['total'] + $val['total']));
                        } else {
                            $final_district_wise_array2[$_REQUEST['district']] = array($cat_listv => $cat_listv, 'total' => $val['total']);
                        }
                    }
                }
            }

            $data['district_categorized'] = $final_district_wise_array2;

            $totalRecords = 0;
            $category_list_count = array();

            $category_count = $this->form_results_model->getCountCatgoryBaseNew($form_id, "", $filter_attribute, $from_date, $to_date, $selected_district = '');
            $category_list_count = array();

            foreach ($category_count as $key => $val) {
                $totalRecords+=$val['total'];
                if (array_key_exists($val[$filter_attribute[0]], $category_list_count)) {
                    $category_list_count[$val[$filter_attribute[0]]] += $val['total'];
                } else {
                    $category_list_count[$val[$filter_attribute[0]]] = $val['total'];
                }
            }

            $category_list_count[] = arsort($category_list_count);
            array_pop($category_list_count);
            
            $total_record = $totalRecords;
            
            $selected_app = $this->app_model->get_app($slug);

            $filter_options = '';

            $app_filter_list = array();
            if (isset($filter_result->filters->$selected_form['id'])) {
                $app_filter_list = $filter_result->filters->$selected_form['id'];
                $filter_options.="<option value=''>Select One</option>";
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


            $data['district'] = $_REQUEST['district'];
            $data['filter_result'] = $filter_result;
            $data['filter_options'] = $filter_options;
            $data['app_name'] = $selected_app['name'];
            $data['form_id'] = $form_id;
            $data['total_records'] = $total_record;
            $data['category_list_count'] = $category_list_count;
            $data['graph_text'] = 'Graph By <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
            $data['pageTitle'] = " Graph-View";
            $data['graph_type'] = 'Category_sub';
            $data['app_id'] = $selected_form['app_id'];
            $data['active_tab'] = 'graph-category_subrep';

            $this->load->view('templates/header', $data);
            $this->load->view('graph/simple_category_graph_subrep', $data);
            
            $this->load->view('templates/footer', $data);
        } else {
            redirect(base_url());
        }
    }

    
    /**
     * initial renderer of graph for each applicaoitn based on Application id
     * @param  $slug Applicatoin Id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function monthwisereport($slug) {
        ini_set('memory_limit', '-1');
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;

        if ($this->session->userdata('logged_in')) {
            if (!$this->acl->hasPermission('form', 'view')) {
                $this->session->set_flashdata('validate', array('message' => "You don't have enough permissions to do this task.", 'type' => 'warning'));
                redirect(base_url() . 'apps');
            }
            $session_data = $this->session->userdata('logged_in');
            session_to_page($session_data, $data);
            $login_district = $session_data['login_district'];

            /**
             * multiple form handling system statrs
             */
            $forms_list = array();
            $all_forms = $this->form_model->get_form_by_app($slug);
            foreach ($all_forms as $forms) {
                $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
            }
            $data['form_lists'] = $forms_list;

            $selected_form = $this->form_model->get_form($forms_list[0]['form_id']);
            $all_kinds_of_map_app = array(0);

            $app_settings = $this->app_model->get_app_settings($slug);
            $district_list = array();
            $list_view_settings = get_graph_view_settings($slug);






            $form_id = $forms_list[0]['form_id'];
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);
            /** Get filters from  multiple forms * */
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
            //echo "<pre>";
//                print_r($multiple_filters);die;
            $data['default_selected_category'] = $default_selected_category;


            $data['filter_attribute'] = array($filter_attribute[0]);
            $data['form_html'] = $form_html_multiple;
            /* Ends filter */
            $data_array = $this->get_category_list($form_id);
            $data['filter'] = $selected_form['filter'];
            $data['selected_form'] = '';
            $category_list = $data_array['category_list'];
            $category_list[] = asort($category_list);
            array_pop($category_list);
            //$data['category_list'] = $category_list;
            $new_category_list = array();
            foreach ($category_list as $cl_key => $cl_value) {
                $cl_expl = explode(',', $cl_value);
                $cl_expl = array_filter($cl_expl);
                foreach ($cl_expl as $clx_key => $clx_value) {

                    if (!array_key_exists($clx_value, $new_category_list)) {
                        $new_category_list[$clx_value] = $clx_value;
                    }
                }
            }
            $data['category_list'] = $new_category_list;
            $date_search = '';

            $final_disb_array = array();
            $tehsil_disb_array = array();

            $data['disbursement_rec'] = $final_disb_array;

            //add part for incident report form - End

            $data['from_date'] = '';
            $data['to_date'] = '';
            
            $from_date = '';
            $to_date = '';
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $final_district_wise_array = array();
            $final_district_wise_array2 = array();
            $final = array();

            $filter_result = get_graph_view_settings($selected_form['app_id']);
            if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {
                foreach ($new_category_list as $cat_listv) {
                    $district_wise_catorized = $this->form_results_model->get_monthwise_categorized_count_new($form_id, '', $filter_attribute[0], $cat_listv, $from_date, $to_date);

                    foreach ($district_wise_catorized as $key => $val) {
                        if (!array_key_exists($val['MONYER'], $final_district_wise_array2)) {
                            $final_district_wise_array2[$val['MONYER']] = array();
                            foreach ($new_category_list as $cat_listvv) {
                                $final_district_wise_array2[$val['MONYER']] = array_merge($final_district_wise_array2[$val['MONYER']], array('MONYER' => $val['MONYER'], $cat_listvv => '0', 'total' => '0'));
                            }
                        }
                        if (array_key_exists($val['MONYER'], $final_district_wise_array2)) {
                            $final_district_wise_array2[$val['MONYER']] = array_merge($final_district_wise_array2[$val['MONYER']], array($cat_listv => $val['total'], 'total' => $final_district_wise_array2[$val['MONYER']]['total'] + $val['total']));
                        } else {
                            $final_district_wise_array2[$val['MONYER']] = array('MONYER' => $val['MONYER'], $cat_listv => $val['total'], 'total' => $val['total']);
                        }
                    }
                }
            }

            $data['district_categorized'] = $final_district_wise_array2;

            $totalRecords = 0;
            $category_list_count = array();

            $category_count = $this->form_results_model->getCountCatgoryBaseNew($form_id, "", $filter_attribute, $from_date, $to_date, $selected_district = '');
            $category_list_count = array();

            foreach ($category_count as $key => $val) {
                $totalRecords+=$val['total'];
                if (array_key_exists($val[$filter_attribute[0]], $category_list_count)) {
                    $category_list_count[$val[$filter_attribute[0]]] += $val['total'];
                } else {
                    $category_list_count[$val[$filter_attribute[0]]] = $val['total'];
                }
            }

            $category_list_count[] = arsort($category_list_count);
            array_pop($category_list_count);
            
            $total_record = $totalRecords;
            
            $selected_app = $this->app_model->get_app($slug);

            $filter_options = '';

            $app_filter_list = array();
            if (isset($filter_result->filters->$selected_form['id'])) {
                $app_filter_list = $filter_result->filters->$selected_form['id'];
                $filter_options.="<option value=''>Select One</option>";
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


            //$data['district'] = $_REQUEST['district'];
            $data['filter_result'] = $filter_result;
            $data['filter_options'] = $filter_options;
            $data['app_name'] = $selected_app['name'];
            $data['form_id'] = $form_id;
            $data['total_records'] = $total_record;
            $data['category_list_count'] = $category_list_count;
            $data['graph_text'] = 'Graph By <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
            $data['pageTitle'] = " Graph-View";
            $data['graph_type'] = 'Category_sub';
            $data['app_id'] = $selected_form['app_id'];
            $data['active_tab'] = 'graph-category_subrep';

            $this->load->view('templates/header', $data);
            $this->load->view('graph/simple_category_graph_monthwise', $data);
            
            $this->load->view('templates/footer', $data);
        } else {
            redirect(base_url());
        }
    }
    
    
    
    public function graphframe($slug) {

        //$slug = $slug_id;
        //exit;



        /**
         * multiple form handling system statrs
         */
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($slug);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }
        $data['form_lists'] = $forms_list;

        $selected_form = $this->form_model->get_form($forms_list[0]['form_id']);
        $all_kinds_of_map_app = array(0);

        $app_settings = $this->app_model->get_app_settings($slug);
        $district_list = array();
        if ($app_settings['district_filter'] == 'On') {

            $district_list = $this->form_results_model->get_distinct_district($slug);
            $data['district_list'] = $district_list;
        }
        if ($app_settings['sent_by_filter'] == 'On') {
            $sent_by_list = $this->form_results_model->get_distinct_sent_by($slug);
            $data['sent_by_list'] = $sent_by_list;
        }



        if ($this->input->post()) {

            $form_id = $this->input->post('form_id');
            $selected_district = $this->input->post('district_list');
            $data['selected_district'] = $selected_district;

            $selected_sent_by = $this->input->post('sent_by_list');
            $data['selected_sent_by'] = $selected_sent_by;

            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);

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
            /* Ends filter */

            $data_array = $this->get_category_list($form_id);
            //                    print_r($data_array);die;
            $data['filter'] = $selected_form['filter'];
            $data['selected_form'] = $form_id;
            $category_list = $data_array['category_list'];

            $category_list[] = asort($category_list);
            array_pop($category_list);
            $data['category_list'] = $category_list;

            //add part for disbursment app - Start
            $formid = '1654';
            $date_search = '';
            $disbursment_rec = $this->form_results_model->get_disbursment_record($formid, $date_search);
            //                    echo "<pre>";
            //                    print_r($disbursment_rec);
            //                    echo "<pre>";die;
            $final_disb_array = array();
            $tehsil_disb_array = array();
            foreach ($disbursment_rec as $myrep) {
                if (!key_exists($myrep['District'], $final_disb_array)) {
                    $final_disb_array[$myrep['District']] = array();
                }

                if (!key_exists($myrep['Tehsil'], $final_disb_array[$myrep['District']])) {
                    $final_disb_array[$myrep['District']][$myrep['Tehsil']] = array();
                }

                if (!key_exists($myrep['Disbursement_Center'], $final_disb_array[$myrep['District']][$myrep['Tehsil']])) {
                    $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']] = array();
                }

                $available_fac = explode(',', $myrep['Facilities_Available']);
                foreach ($available_fac as $avail_value) {
                    if (!key_exists('facilities', $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']])) {
                        $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'] = array();
                    }
                    if (!in_array($avail_value, $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'])) {
                        array_push($final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'], $avail_value);
                    }
                }
            }

            $data['disbursement_rec'] = $final_disb_array;
            $to_date = $this->input->post('filter_date_from');
            $from_date = $this->input->post('filter_date_to');
            $district_list = $this->form_results_model->get_distinct_district($slug);


            $final_district_wise_array = array();
            $final_district_wise_array2 = array();
            $final = array();

            foreach ($district_list as $district) {
                $total = 0;
                $counter = 0;
                foreach ($category_list as $category) {
                    $counter ++;
                    $district_wise_catorized = $this->form_results_model->get_district_categorized_count($form_id, $district['district_name'], $filter_attribute[0], $category, $to_date, $from_date);
                    $total += $district_wise_catorized;
                    $final_district_wise_array = array_merge($final_district_wise_array, array('district' => $district['district_name'], $category => $district_wise_catorized));
                    if ($counter == count($category_list)) {
                        $final_district_wise_array = array_merge($final_district_wise_array, array('total' => $total));
                    }
                }
                $final_district_wise_array2[$district['district_name']] = $final_district_wise_array;
            }

            $data['district_categorized'] = $final_district_wise_array2;

            $totalRecords = 0;
            $category_list_count = array();
            foreach ($category_list as $category) {
                $categorieslist = explode(',', $category);
                foreach ($categorieslist as $catu) {
                    $category_count = $this->form_results_model->getCountCatgoryBase($form_id, $catu, $filter_attribute, $to_date, $from_date, $selected_district, $selected_sent_by);
                    $category_list_count = array_merge($category_list_count, array($catu => $category_count));
                    $totalRecords += $category_count;
                }
            }

            $category_list_count[] = arsort($category_list_count);
            array_pop($category_list_count);

            $total_record = $totalRecords;
            $selected_app = $this->app_model->get_app($slug);

            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['app_name'] = $selected_app['name'];
            $data['form_id'] = $form_id;
            $data['category_list'] = $category_list;
            $data['total_records'] = $total_record;
            $data['category_list_count'] = $category_list_count;
            $data['graph_text'] = 'Graph By Category <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
            $data['pageTitle'] = " Graph-View";
            $data['graph_type'] = 'Category';
            $data['app_id'] = $selected_form['app_id'];
            $data['active_tab'] = 'graph-category';

            $data['date_search'] = $date_search;
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('graph/simple_category_graph_frame', $data);
            $this->load->view('templates/footer_iframe', $data);
        } else {

            $form_id = $forms_list[0]['form_id'];
            $form_single_to_query = array();
            $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $forms_list[0]['form_name']);
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
            /* Ends filter */
            $data_array = $this->get_category_list($form_id);

            $data['filter'] = $selected_form['filter'];
            $data['selected_form'] = '';
            $category_list = $data_array['category_list'];

            $category_list[] = asort($category_list);
            array_pop($category_list);
            $data['category_list'] = $category_list;

            //add part for disbursment app - Start
            $formid = '1654';
            $date_search = '';
            $disbursment_rec = $this->form_results_model->get_disbursment_record($formid, $date_search);
            $final_disb_array = array();
            $tehsil_disb_array = array();
            foreach ($disbursment_rec as $myrep) {
                if (!key_exists($myrep['District'], $final_disb_array)) {
                    $final_disb_array[$myrep['District']] = array();
                }

                if (!key_exists($myrep['Tehsil'], $final_disb_array[$myrep['District']])) {
                    $final_disb_array[$myrep['District']][$myrep['Tehsil']] = array();
                }

                if (!key_exists($myrep['Disbursement_Center'], $final_disb_array[$myrep['District']][$myrep['Tehsil']])) {
                    $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']] = array();
                }

                $available_fac = explode(',', $myrep['Facilities_Available']);
                foreach ($available_fac as $avail_value) {
                    if (!key_exists('facilities', $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']])) {
                        $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'] = array();
                    }
                    if (!in_array($avail_value, $final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'])) {
                        array_push($final_disb_array[$myrep['District']][$myrep['Tehsil']][$myrep['Disbursement_Center']]['facilities'], $avail_value);
                    }
                }
            }

            $data['disbursement_rec'] = $final_disb_array;
            //add part for incident report form - End
            $district_list = $this->form_results_model->get_distinct_district($slug);
            $data['from_date'] = '';
            $data['to_date'] = '';
            $from_date = '2050-9-19';
            $to_date = '2000-9-19';
            $final_district_wise_array = array();
            $final_district_wise_array2 = array();
            $final = array();

            foreach ($district_list as $district) {
                $total = 0;
                $counter = 0;
                foreach ($category_list as $category) {
                    $counter ++;
                    $district_wise_catorized = $this->form_results_model->get_district_categorized_count($form_id, $district['district_name'], $filter_attribute[0], $category, $from_date, $to_date);
                    $total += $district_wise_catorized;
                    $final_district_wise_array = array_merge($final_district_wise_array, array('district' => $district['district_name'], $category => $district_wise_catorized));
                    if ($counter == count($category_list)) {
                        $final_district_wise_array = array_merge($final_district_wise_array, array('total' => $total));
                    }
                }
                $final_district_wise_array2[$district['district_name']] = $final_district_wise_array;
            }
            $data['district_categorized'] = $final_district_wise_array2;

            $totalRecords = 0;
            $category_list_count = array();
            foreach ($category_list as $category) {
                $categorieslist = explode(',', $category);
                foreach ($categorieslist as $catu) {
                    $category_count = $this->form_results_model->getCountCatgoryBase($form_id, $catu, $filter_attribute, $from_date, $to_date, $selected_district = '');
                    $category_list_count = array_merge($category_list_count, array($catu => $category_count));
                    $totalRecords += $category_count;
                }
            }
            $category_list_count[] = arsort($category_list_count);
            array_pop($category_list_count);

            if ($slug == 1293) {
                $total_result = $this->form_results_model->return_total_record($forms_list);
                $total_record = $total_result;
            } else {
                $total_record = $totalRecords;
            }
            $selected_app = $this->app_model->get_app($slug);
            $data['app_name'] = $selected_app['name'];
            $data['form_id'] = $form_id;
            $data['category_list'] = $category_list;
            $data['total_records'] = $total_record;
            $data['category_list_count'] = $category_list_count;
            $data['graph_text'] = 'Graph By Category <b> ' . str_replace('_', ' ', $filter_attribute[0]) . '</b>';
            $data['pageTitle'] = " Graph-View";
            $data['graph_type'] = 'Category';
            $data['app_id'] = $selected_form['app_id'];
            $data['active_tab'] = 'graph-category';

            $data['date_search'] = $date_search;
            $this->load->view('templates/header_iframe', $data);
            $this->load->view('graph/simple_category_graph_frame', $data);
            $this->load->view('templates/footer_iframe', $data);
        }
    }

    /**
     * graph view chang for ajax based  based on graph type
     * @param  $slug Form Id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function graph_type($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        $graph_type = $this->input->post('graph_type');

        $session_data = $this->session->userdata('logged_in');
        session_to_page($session_data, $data);
        $login_district = $session_data['login_district'];

        $selected_form = $this->form_model->get_form($slug);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $selected_app = $this->app_model->get_app($data['app_id']);
        $data['app_name'] = $selected_app['name'];

        if ($graph_type == 'user') {
            $users_lists = $this->app_users_model->get_app_users_app_based($selected_form['app_id'], $login_district);
            $users_lists_array = array();
            foreach ($users_lists as $users_name) {

                $users_lists_array[] = array('imei_no' => $users_name['imei_no'], 'user_name' => $users_name['user_name']);
            }
            $users_wise_counter = array();
            $totalRecords = 0;
            foreach ($users_lists_array as $users) {
                $users_count = $this->form_results_model->getCountUserBased($slug, $users['imei_no']);
                $users_wise_counter = array_merge($users_wise_counter, array($users['user_name'] => $users_count));
                $totalRecords += $users_count;
            }
            $users_wise_counter[] = arsort($users_wise_counter);
            array_pop($users_wise_counter);

            $data['form_id'] = $slug;
            $data['total_records'] = $totalRecords;
            $data['users_wise_counter'] = $users_wise_counter;
            $data['graph_text'] = 'Data Records By Users';
            $data['graph_type'] = $graph_type;
            $this->load->view('graph/dashboard_partial', $data);
        } else if ($graph_type == 'category') {
            $data_array = $this->get_category_list($slug);
            $category_list = $data_array['category_list'];
            $filter_attribute = $data_array['filter_attribute'];

            $totalRecords = 0;
            $category_list_count = array();
            foreach ($category_list as $category) {
                $category_count = $this->form_results_model->getCountCatgoryBased($slug, $category, $filter_attribute);
                $category_list_count = array_merge($category_list_count, array($category => $category_count));
                $totalRecords += $category_count;
            }
            $category_list_count[] = arsort($category_list_count);
            array_pop($category_list_count);

            $data['form_id'] = $slug;
            $data['category_list'] = $category_list;
            $data['total_records'] = $totalRecords;
            $data['category_list_count'] = $category_list_count;
            $data['graph_text'] = 'Data Records By Category';
            $data['graph_type'] = $graph_type;
            $this->load->view('graph/category_graph', $data);
        }
    }

    /**
     * graph view for singel user based on imei number  of specific application
     * @param  $slug form id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function single_user_graph($slug) {
        $slug_array = array();
        $slug_array = explode('-', $slug);
        $slug_id = $slug_array[count($slug_array) - 1];
        $slug = $slug_id;
        $imei_number = $this->input->post('imei_number');
        $user_name = $this->input->post('user_name');

        $selected_form = $this->form_model->get_form($slug);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $selected_app = $this->app_model->get_app($data['app_id']);
        $data['app_name'] = $selected_app['name'];

        /**
         * for data table module of user based data
         */
        $heading_array = array();
        $record_array_final = array();
        $user_wise_result = $this->form_results_model->getResultUserImeiBased($slug, $imei_number);
        foreach ($user_wise_result as $k => $v) {
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
            $record_array = array_merge($record_array, array('uc_name' => $v['uc_name']));
            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));

            $record_array_final[] = $record_array;
        }
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;

        /*
         * datatable process ends herer-------
         */
        $totalRecords = 0;
        $users_uc_set = array();
        $uc_counted = array();
        foreach ($record_array_final as $record) {
            $uc_name = $record['uc_name'];
            if (!empty($uc_name) & !in_array($uc_name, $uc_counted)) {
                $users_count = $this->form_results_model->getCountUserUcBased($slug, $imei_number, $uc_name);
                if ($users_count > 0) {
                    $users_uc_set = array_merge($users_uc_set, array($uc_name => $users_count));
                }
                $totalRecords += $users_count;
                $uc_counted [] = $uc_name;
            }
        }
        $users_uc_set[] = arsort($users_uc_set);
        array_pop($users_uc_set);
        $data['users_wise_counter'] = $users_uc_set;
        $data['form_id'] = $slug;
        $data['total_records'] = $totalRecords;
        $data['graph_text'] = 'Tagging Records By ' . $user_name;
        $this->load->view('graph/single_user_graph', $data);
    }

    /**
     * graph view for singel category based on imei number  of specific application
     * @param  $slug form id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function single_category_graph($slug) {

        $category_name = $this->input->post('category_name');
        $filter_attribute = $this->input->post('filter_attribute');

        $selected_form = $this->form_model->get_form($slug);
        $data['form_name'] = $selected_form['name'];
        $data['app_id'] = $selected_form['app_id'];
        $selected_app = $this->app_model->get_app($data['app_id']);
        $data['app_name'] = $selected_app['name'];

        /**
         * for data table module of user based data
         */
        $heading_array = array();
        $record_array_final = array();
        $user_wise_result = $this->form_results_model->getResultSingleCatgory($slug, $category_name, $filter_attribute);
        foreach ($user_wise_result as $k => $v) {
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
            $record_array = array_merge($record_array, array('uc_name' => $v['uc_name']));
            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));

            $record_array_final[] = $record_array;
        }
        $heading_array = array_merge($heading_array, array('created_datetime', 'actions'));
        $data['headings'] = $heading_array;
        $data['form'] = $record_array_final;

        /*
         * datatable process ends herer-------
         */
        $totalRecords = 0;
        $category_uc_set = array();
        $uc_counted = array();

        foreach ($record_array_final as $record) {
            $uc_name = $record['uc_name'];
            if (!empty($uc_name) & !in_array($uc_name, $uc_counted)) {
                $category_count = $this->form_results_model->getCountCategoryUcBased($slug, $category_name, $filter_attribute, $uc_name);
                if ($category_count > 0) {
                    $category_uc_set = array_merge($category_uc_set, array($uc_name => $category_count));
                }
                $totalRecords += $category_count;
                $uc_counted [] = $uc_name;
            }
        }
        $category_uc_set[] = arsort($category_uc_set);
        array_pop($category_uc_set);

        $data['form_id'] = $slug;
        $data['total_records'] = $totalRecords;
        $data['category_wise_counter'] = $category_uc_set;
        $data['graph_text'] = 'Tagging Records Of ' . $category_name;
        $this->load->view('graph/single_category_graph', $data);
    }

    /**
     * function to  get heading and form data of  form results table return data array having two values  forms and headings
     * @param  $slug form id
     * @return  void
     * @author UbaidUllah Balti <ubaidcskiu@gmail.com>
     */
    public function get_category_list($slug) {
        $form_id = $slug;
        $selected_form = $this->form_model->get_form($form_id);
//        echo "<pre>";
//        print_r($selected_form);die;
        $filter_attribute = array();

        if ($selected_form['filter'] != '') {
            $filter_rec = array_filter(array_map('trim', explode(',', $selected_form['filter'])));
            foreach ($filter_rec as $key => $value) {
                array_push($filter_attribute, $value);
            }
        }else{
            $filter_attribute[0] = '';
        }

//        if ($form_id == 1654) {
//            $filter_attribute = array('Facilities_Available');
//        } else if ($form_id == 1768) {
//            $filter_attribute = array('Type');
//        } else if ($form_id == 1769) {
//            $filter_attribute = array('Designation');
//        } else if ($form_id == 1770) {
//            $filter_attribute = array('Satisfied_from_the_service');
//        }

        $table_headers_array = array();
        $record_array_final = array();

        $results = $this->form_results_model->return_total_record_for_graph($form_id, $filter_attribute[0]);
//        echo "<pre>";
//        print_r($results);die;
//        foreach ($results as $k => $v) {
//            $record_array = array();
//            foreach ($v as $key => $value) {
////                    if (!in_array($key, $exclude_array)) {
//                $record_array = array_merge($record_array, array($key => $value));
////                    }
//            }
//
//
////                $imagess = $this->form_results_model->getResultsImages($v['id'],$form_id);
////                if ($imagess) {
////                    if (!in_array('image', $table_headers_array)) {
////                        $table_headers_array = array_merge($table_headers_array, array('image'));
////                    }
////                    $record_array = array_merge($record_array, array('image' => $imagess));
////                }
//
////            $record_array = array_merge($record_array, array('created_datetime' => $v['created_datetime'], 'actions' => $v['id']));
//            $record_array_final[] = $record_array;
//        }
        $record_array_final = $results;


        $heading_query = $this->form_results_model->getTableHeadingsFromSchema('zform_' . $form_id);
        foreach ($heading_query as $key => $value) {
            $header_value = $value['COLUMN_NAME'];
            if (!in_array($header_value, $table_headers_array)) {
//                if (!in_array($header_value, $exclude_array)) {
                $table_headers_array = array_merge($table_headers_array, array($header_value));
//                }
            }
        }
        $category_list = array();
        if (!empty($filter_attribute)) {

            foreach ($filter_attribute as $filter_attribute_value) {

                $filter_data = array();

                if (in_array($filter_attribute_value, $table_headers_array)) {
//                    if ($selected_form['app_id'] == 1293 || $selected_form['app_id'] == 1567) {
//                        foreach ($record_array_final as $key => $form_item) {
//
//                            if (!empty($form_item[$filter_attribute_value])) {
//                                if (!in_array($form_item[$filter_attribute_value], $filter_data)) {
//                                    $key = trim($form_item[$filter_attribute_value]);
//                                    $key = explode(',', $key);
//                                    $value = ($form_item[$filter_attribute_value]);
//                                    $value = explode(',', $value);
//                                    $filter_data = array_merge($filter_data, array($key[0] => $value[0]));
//                                }
//                            }
//                        }
//                    } else {
                    foreach ($record_array_final as $key => $form_item) {

                        if (!empty($form_item[$filter_attribute_value])) {
                            if (!in_array($form_item[$filter_attribute_value], $filter_data)) {
                                $key = trim($form_item[$filter_attribute_value]);
                                $value = ($form_item[$filter_attribute_value]);
                                $filter_data = array_merge($filter_data, array($key => $value));
                            }
                        }
                    }
//                    }
                    $category_list = $filter_data;
                }
            }
        }
//                echo '<pre>'; print_r($category_list); die;
        $data['category_list'] = $category_list;
        $data['filter_attribute'] = $filter_attribute;
        $data['form_data'] = $record_array_final;
        $data['heading'] = $table_headers_array;
        return $data[] = $data;
    }

    public function exportdistrictreport($slug) {

        $form_id = $slug;
        $selected_form = $this->form_model->get_form($slug);
        //print_r($selected_form);
        $form_single_to_query = array();
        $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $selected_form['name']);

        /** Get filters from  multiple forms * */
        $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
        $filter_attribute = array();
        $form_html_multiple = array();
        foreach ($multiple_filters as $key => $value) {
            array_push($filter_attribute, $value['filter']);
            array_push($form_html_multiple, $value['description']);
        }
        $data['filter_attribute'] = array($filter_attribute[0]);


        $data_array = $this->get_category_list($slug);
        $data['filter'] = $selected_form['filter'];
        $data['selected_form'] = $slug;
        $category_list = $data_array['category_list'];

        $category_list[] = asort($category_list);
        array_pop($category_list);

        $to_date = $_REQUEST['to_date'];
        $from_date = $_REQUEST['from_date'];

        $filter_result = get_graph_view_settings($selected_form['app_id']);

        if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {

            $new_category_list = array();
            foreach ($category_list as $cl_key => $cl_value) {
                $cl_expl = explode(',', $cl_value);
                $cl_expl = array_filter($cl_expl);
                foreach ($cl_expl as $clx_key => $clx_value) {

                    if (!array_key_exists($clx_value, $new_category_list)) {
                        $new_category_list[$clx_value] = $clx_value;
                    }
                }
            }
//            print "<pre>";
//            print_r($new_category_list);
//            exit;
            $data['category_list'] = $new_category_list;
            $final_district_wise_array2 = array();
            foreach ($new_category_list as $cat_listv) {
                $district_wise_catorized = $this->form_results_model->get_district_categorized_count_new($form_id, "", $filter_attribute[0], $cat_listv, $from_date, $to_date);

                foreach ($district_wise_catorized as $key => $val) {
                    if (!array_key_exists($val['district_name'], $final_district_wise_array2)) {
                        $final_district_wise_array2[$val['district_name']] = array();
                        foreach ($new_category_list as $cat_listvv) {
                            $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array('district' => $val['district_name'], $cat_listvv => '0', 'total' => '0'));
                        }
                    }
                    if (array_key_exists($val['district_name'], $final_district_wise_array2)) {
                        $final_district_wise_array2[$val['district_name']] = array_merge($final_district_wise_array2[$val['district_name']], array($cat_listv => $val['total'], 'total' => $final_district_wise_array2[$val['district_name']]['total'] + $val['total']));
                    } else {
                        $final_district_wise_array2[$val['district_name']] = array('district' => $val['district_name'], $cat_listv => $val['total'], 'total' => $val['total']);
                    }
                }
            }
        }
//print "<pre>";
        $header = 'District,';
        foreach ($new_category_list as $category) {
            $header .= $category . ",";
        }

        $data_form = $header . 'Total,' . "\n";
        foreach ($final_district_wise_array2 as $data) {
            $line = '';
            $counter = 0;
            $total_string = 0;
            foreach ($data as $insid_key => $inside) {
                if ($insid_key == 'total') {
                    $total_string = $data['total'];
                } else {

                    if ($counter == 0) {
                        $value = str_replace('"', '""', $inside);
                        $value = '"' . $value . '"' . ",";
                    } else {
                        if ($inside == 0) {
                            $v1 = '0';
                        } else {
                            $v1 = $inside;
                        }
                        $value = str_replace('"', '""', $v1);
                        $value = '"' . $value . '"' . ",";
                    }
                    $line .= $value;
                }

                $counter++;
            }
            $value = str_replace('"', '""', $total_string);
            $value = '"' . $value . '"' . ",";
            $line .= $value;


            $data_form .= trim($line) . "\n";
        }
        //avoiding problems with data that includes "\r"
        $data_form = str_replace("\r", "", $data_form);

        $filename = $form_id . "_report.csv";

        $filename = str_replace(" ", "-", $filename);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo chr(239) . chr(187) . chr(191) . $data_form;
        exit;
    }
    public function exportschoolreport($slug) {

        $form_id = $slug;
        $selected_form = $this->form_model->get_form($slug);
        //print_r($selected_form);
        $form_single_to_query = array();
        $form_single_to_query[] = array('form_id' => $form_id, 'table_name' => 'zform_' . $form_id, 'form_name' => $selected_form['name']);

        /** Get filters from  multiple forms * */
        $multiple_filters = $this->form_model->get_form_filters($form_single_to_query);
        $filter_attribute = array();
        $form_html_multiple = array();
        foreach ($multiple_filters as $key => $value) {
            array_push($filter_attribute, $value['filter']);
            array_push($form_html_multiple, $value['description']);
        }
        $data['filter_attribute'] = array($filter_attribute[0]);


        $data_array = $this->get_category_list($slug);
        $data['filter'] = $selected_form['filter'];
        $data['selected_form'] = $slug;
        $category_list = $data_array['category_list'];

        $category_list[] = asort($category_list);
        array_pop($category_list);

        $to_date = $_REQUEST['to_date'];
        $from_date = $_REQUEST['from_date'];

        $filter_result = get_graph_view_settings($selected_form['app_id']);

        if (isset($filter_result->district_wise_report) && $filter_result->district_wise_report == 1) {

            $new_category_list = array();
            foreach ($category_list as $cl_key => $cl_value) {
                $cl_expl = explode(',', $cl_value);
                foreach ($cl_expl as $clx_key => $clx_value) {

                    if (!array_key_exists($clx_value, $new_category_list)) {
                        $new_category_list[$clx_value] = $clx_value;
                    }
                }
            }
            $data['category_list'] = $new_category_list;
            $final_district_wise_array2 = array();
            foreach ($new_category_list as $cat_listv) {
                $district_wise_catorized = $this->form_results_model->get_school_categorized_count_new($form_id, $_REQUEST['district'], $filter_attribute[0], $cat_listv, $from_date, $to_date);

                foreach ($district_wise_catorized as $key => $val) {
                    if (!array_key_exists($val['EMIS_Code'], $final_district_wise_array2)) {
                        $final_district_wise_array2[$val['EMIS_Code']] = array();
                        foreach ($new_category_list as $cat_listvv) {
                            $final_district_wise_array2[$val['EMIS_Code']] = array_merge($final_district_wise_array2[$val['EMIS_Code']], array('school' => $val['school_name'], 'Category' => $val['Category'], $cat_listvv => '0','total' => '0'));
                        }
                    }
                    if (array_key_exists($val['EMIS_Code'], $final_district_wise_array2)) {
                        $final_district_wise_array2[$val['EMIS_Code']] = array_merge($final_district_wise_array2[$val['EMIS_Code']], array($cat_listv => $val['total'], 'Category' => $val['Category'], 'total' => $final_district_wise_array2[$val['EMIS_Code']]['total'] + $val['total']));
                    } else {
                        $final_district_wise_array2[$val['EMIS_Code']] = array('school' => $val['school_name'], $cat_listv => $val['total'], 'Category' => $val['Category'], 'total' => $val['total']);
                    }
                }
            }
        }
//print "<pre>";
        $header = 'School Name,Category,';
        foreach ($new_category_list as $category) {
            $header .= $category . ",";
        }
$data_form = $header . "\n";
        
        foreach ($final_district_wise_array2 as $data) {
            $line = '';
            $counter = 0;
            $total_string = 0;
            foreach ($data as $insid_key => $inside) {
                if ($insid_key == 'total') {
                    //$total_string = $data['total'];
                } else {

                    if ($counter == 0) {
                        $value = str_replace('"', '""', $inside);
                        $value = '"' . $value . '"' . ",";
                    } else {
                        if($insid_key == 'Category'){
                            $v1 = $inside;
                        }
                        else if ($inside == 0) {
                            $v1 = 'No';
                        } else {
                            $v1 = 'Yes';
                        }
                        $value = str_replace('"', '""', $v1);
                        $value = '"' . $value . '"' . ",";
                    }
                    $line .= $value;
                }

                $counter++;
            }
//            $value = str_replace('"', '""', $total_string);
//            $value = '"' . $value . '"' . ",";
            //$line .= $value;


            $data_form .= trim($line) . "\n";
        }
        //avoiding problems with data that includes "\r"
        $data_form = str_replace("\r", "", $data_form);

        $filename = $_REQUEST['district'] . "_district_school_report.csv";

        $filename = str_replace(" ", "-", $filename);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo chr(239) . chr(187) . chr(191) . $data_form;
        exit;
    }

}

?>
