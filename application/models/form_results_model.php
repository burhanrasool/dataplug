<?php

class Form_Results_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public $per_map = 5000;

    /**
     * Return form resutls only for filters with limit 
     * @param type $form_list form list
     * @return array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function get_form_results_filters($form_list = array(), $login_district = NULL) {

        $lists = array();
        foreach ($form_list as $form_entity) {

            $this->db->where('form_id', $form_entity['form_id']);
            $this->db->where('is_deleted', '0');
            $this->db->where('location <>', '');
//            $this->db->order_by("created_datetime", "desc");
            $this->db->limit(4000);
            $query = $this->db->get('zform_' . $form_entity['form_id']);
            $lists[] = $query->result_array();
        }

        return $lists;
    }

    /**
     * Just Check app has data or not...
     * @param type $form_list form list
     * @return array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function get_result_is_empty($form_list = FALSE) {

        $lists = array();
        $total_rec = 0;
        foreach ($form_list as $form_entity) {
            $form_id = $form_entity['form_id'];
            $this->db->where('is_deleted', '0');
            $this->db->limit(2);

            $query = $this->db->get('zform_' . $form_id);
            $total_rec += $query->num_rows();
        }

        return $total_rec;
    }

    /**
     * GET form result based on pk
     * @param type $slug form result id
     * @return row
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function get_results($form_id = NULL, $form_result_id = NULL) {

        $table_name = 'zform_' . $form_id;
        $this->db->where('id', $form_result_id);
        $this->db->where('is_deleted', '0');
        $this->db->where('location <>', '');
        $query = $this->db->get($table_name);

        return $query->row_array();
    }

    /**
     * GET all results based on primary key
     * @param type $slug form result id
     * @return array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function get_results_single($result_id = FALSE, $table_name) {
        if ($result_id === FALSE) {

            return FALSE;
        }

        $query = "SELECT $table_name.*, app_users.name as sent_by
                  FROM  $table_name
                  LEFT JOIN app_users ON ('app_user.imei_no= $table_name.imei_no')
                  Where $table_name.id='$result_id'

                  ";
        $query = $this->db->query($query);

//        $query = $this->db->get_where($table_name, array('id' => $result_id));
        return $query->result_array();
    }

    /**
     * Set offset for pagination
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    function getOffSet()
    {
        $page = $this->input->post('page');
        if (!$page):
            $offset = 0;
        else:
            $offset = $page;
        endif;
        return $offset;
    }

    /*
     * get uc based on form id and towns name
     * @param  $slug
     * @param  $town_name
     * @return  array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */

    public function get_uc_by_town($app_id, $town_name) {

        $query = '';
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $table_name = 'zform_' . $forms['form_id'];
            $query .= 'SELECT id,form_id,district_name,town_name,uc_name,is_deleted,location FROM ' . $table_name . ' WHERE is_deleted = 0 AND location <> "" AND town_name = "' . $town_name . '" GROUP BY uc_name UNION ALL ';
        }
        $lasRemoval = strrpos($query, 'UNION ALL');
        $query = substr($query, 0, $lasRemoval);

        $query = $this->db->query($query);
        return $query->result_array();
    }

    /*
     * get result based on district
     * @param  $form_list
     * @param  $district
     * @return  array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */

    public function get_form_results_by_district($form_list = array(), $district) {
        $this->db->select('*');
        $this->db->from($form_list[0]['table_name']);
        $this->db->where('district_name =', $district);
        $this->db->where('is_deleted', 0);
        $this->db->where('location <>', '');
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
     * get result based on uc
     * @param  $slug
     * @param  $town_name
     * @return  array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */

    public function get_form_results_by_town($table_name, $town_name) {

        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where('town_name =', $town_name);
        $this->db->where('is_deleted', 0);
        $this->db->where('location <>', '');
        $query = $this->db->get();
        return $query->result_array();
//        $this->db->select('*');
//        $this->db->from('form_results');
//        $this->db->where('form_results.form_id =', $slug);
//        $this->db->where('form_results.is_deleted', 0);
//        $this->db->where('form_results.town_name', $town_name);
//        $query = $this->db->get();
//        return $query->result_array();
    }

    /*
     * get result based on uc
     * @param  $slug
     * @param  $town_name
     * @return  array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */

    public function get_form_results_by_uc($table_name, $uc_name, $sent_by_imei) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where('uc_name =', $uc_name);
        if ($sent_by_imei != '') {
            $this->db->where('imei_no =', $sent_by_imei);
        }
        $this->db->where('is_deleted', 0);
        $this->db->where('location <>', '');
        $query = $this->db->get();
        return $query->result_array();
    }

    /*
     * get result based on senty by
     * @param  $senty by
     * @return  array
     * @author irfan javed <irfanjvd@gmail.com >
     */

    public function get_form_results_by_sent_by($table_name, $sent_by_imei, $uc_name) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where('imei_no =', $sent_by_imei);
        if ($uc_name != '') {
            $this->db->where('uc_name =', $uc_name);
        }
        $this->db->where('is_deleted', 0);
        $this->db->where('location <>', '');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get Images of sinle results
     * @param  $result_id
     * @return  array
     * @author Ubaidullah Balti <ubaidcskiu@gmail.com>
     */
    public function getResultsImages($result_id, $form_id, $per_page = null) {
        $this->db->select('*');
        $this->db->from('zform_images');
        $this->db->where('zform_result_id', $result_id);
        $this->db->where('form_id', $form_id);
        $offset = $this->getOffset();
        if ($per_page != null) {
            $this->db->limit($per_page, $offset);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    //new isntance of categorbased
    public function getCountCatgoryBase($slug = FALSE, $category_name, $filter_attribute_search = array(), $to_date, $from_date, $selected_district, $selected_sent_by = '', $selected_uc = '') {

        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $category_name = str_replace("'", "\'", $category_name);
        if ($selected_district) {
            $district_query = "AND district_name = '" . $selected_district . "'";
        } else {
            $district_query = "AND 1=1";
        }
        if ($selected_sent_by) {
            $sent_by_query = "AND imei_no = '" . $selected_sent_by . "'";
        } else {
            $sent_by_query = "AND 1=1";
        }

        $filter_string = '(';
        if (is_array($filter_attribute_search)) {
            foreach ($filter_attribute_search as $filter_name) {
                $category = str_replace("/", "\\\\\\\\/", $category_name);
                $filter_string .= $filter_name . " LIKE '$category' OR ";
            }
            $lastORPosition = strrpos($filter_string, 'OR');
            $filter_string = substr($filter_string, 0, $lastORPosition);
            $filter_string .= ') AND (';
        } else {
            $filter_string .= "1=1";
        }
        $lasRemoval = strrpos($filter_string, 'AND');
        $filter_string = substr($filter_string, 0, $lasRemoval);

        if (is_array($filter_attribute_search)) {
            $filter_attribute_search = $filter_attribute_search[0];
        }



        $table_name = 'zform_' . $slug;

        $selected_form = $this->form_model->get_form($slug);
        $app_id = $selected_form['app_id'];
        if ($app_id == 1293) {
            $query = "SELECT *
                  FROM " . $table_name . "
                  WHERE " . $filter_attribute_search . " LIKE '" . $category_name . "%' 
                  AND is_deleted =0
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> '' 
                 $district_query
                 $sent_by_query";
        } else {
            $query = "SELECT *
                  FROM " . $table_name . "
                  WHERE " . $filter_string . "
                  AND is_deleted =0
                   AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> ''   
                  $district_query
                  $sent_by_query";
        }

//        echo $query;
//        die;
//        $query = $this->db->query($query);
//        $query = "SELECT fr.id as 'id',fr.form_id,fr.location,fr.record,fr.is_deleted
//                        FROM `form_results` fr
//                        WHERE " . $filter_string . "
//                        AND fr.is_deleted =0
//                        AND fr.form_id = '" . $slug . "'
//                        AND fr.location <> ''";

        $query = $this->db->query($query);

        return $query->num_rows();
    }

    //Check table exist in the database Schema ubaidcskiU@gmail.com
    public function check_table_exits($table_name = Null) {
        $query = "SELECT count(*)
                   FROM information_schema.tables
                    WHERE table_name = '$table_name'";

        $query = $this->db->query($query);

        return $query->row_array();
    }

    public function check_column_exits($table_name, $column_name) {
        $query = "SELECT * FROM  INFORMATION_SCHEMA.COLUMNS
               WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'";
        $query = $this->db->query($query);
        return $query->row_array();
    }

    //New instance
    public function get_results_paginated($table_name = FALSE, $per_page, $all_data = 0) {
        $table_array = explode('_', $table_name);
        $form_id = $table_array[count($table_array) - 1];
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];

        $offset = $this->getOffset();
        $this->db->select("$table_name.*,au.name as sent_by");
        $this->db->from($table_name);
//        $this->db->where_in('form_results.form_id', $lists);
        $this->db->where("$table_name.is_deleted", 0);
        if ($all_data == 0) {
            $this->db->limit($per_page, $offset);
        }

        $this->db->join('app_users au', "$table_name.imei_no = au.imei_no AND au.app_id=$app_id", 'left');
        $this->db->order_by("$table_name.id", 'Desc');

        $query = $this->db->get();
//        echo $this->db->last_query();
        return $query->result_array();
    }

    /** For Heading get Purpose */
    public function getTableHeadingsFromSchema($table_name = NULL, $for_reporting = NULL) {
        if ($for_reporting == 1) {
            $table_arr = explode("_", $table_name);
            $form_id = $table_arr[1];
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];
            $column_settings = $this->form_model->get_column_settings($app_id);
            $final_columns = array();
            if (!empty($column_settings)) {

                $all_columns = json_decode($column_settings['columns'], true);
                if (isset($all_columns[$form_id])) {
                    $selected_form_columns = $all_columns[$form_id];
                    //dump($selected_form_columns, 0);
                    if (!empty($selected_form_columns['visible'])) {
                        foreach ($selected_form_columns['visible'] as $key => $val) {
                            $final_columns[] = array(
                                'COLUMN_NAME' => $key,
                                'order' => (isset($selected_form_columns['order'])) ? $selected_form_columns['order'][$key] : array(),
                                'new_name' => (isset($selected_form_columns['columns'])) ? $selected_form_columns['columns'][$key] : array()
                            );
                        }
                    }
                }else{
                    $database = $this->db->database;
                    $query = "SHOW COLUMNS from $table_name from $database";
//        $query = "SELECT COLUMN_NAME FROM  INFORMATION_SCHEMA.COLUMNS
//               WHERE TABLE_NAME = '$table_name'";
                    $query = $this->db->query($query);
                    $result = $query->result_array();
                    foreach ($result as $key => $val) {
                        $result[$key]['COLUMN_NAME'] = $val['Field'];
                    }
//        return $query->result_array();
                    return $result;
                }
            } else {

                $database = $this->db->database;
                $query = "SHOW COLUMNS from $table_name from $database";
//        $query = "SELECT COLUMN_NAME FROM  INFORMATION_SCHEMA.COLUMNS
//               WHERE TABLE_NAME = '$table_name'";
                $query = $this->db->query($query);
                $result = $query->result_array();
                foreach ($result as $key => $val) {
                    $result[$key]['COLUMN_NAME'] = $val['Field'];
                }
//        return $query->result_array();
                return $result;
            }
            return $final_columns;
        } else {
            $database = $this->db->database;
            $query = "SHOW COLUMNS from $table_name from $database";
//        $query = "SELECT COLUMN_NAME FROM  INFORMATION_SCHEMA.COLUMNS
//               WHERE TABLE_NAME = '$table_name'";
            $query = $this->db->query($query);
            $result = $query->result_array();
            foreach ($result as $key => $val) {
                $result[$key]['COLUMN_NAME'] = $val['Field'];
            }
//        return $query->result_array();
            return $result;
        }
    }

    public function getTableData($table_name = 'NULL') {
        $this->db->select('*');
        $this->db->from($table_name);

        $query = $this->db->get();
        return $query->result_array();
    }

    //New instance alos for map 
    public function return_total_record($form_list = Null, $app_id = '') {
        $total_count = 0;
//        echo "<pre            >";
//        print_r($form_list);
        foreach ($form_list as $form_name) {

            $table_array = explode('_', $form_name['table_name']);
//            $form_id = $table_array[count($table_array) - 1];
//            $selected_form = $this->form_model->get_form($form_id);
//            $app_id = $selected_form['app_id'];
            $this->db->select("COUNT(*)as total");
            $this->db->from($form_name['table_name']);
            $this->db->where("$form_name[table_name].is_deleted", '0');
//            $this->db->join("app_users au","$form_name[table_name].imei_no=au.imei_no AND au.app_id=$app_id","left");
            $this->db->where('location <>', '');
            $query = $this->db->get();
            $result = $query->row_array();
//            echo "<pre>";
//            print_r($result);die;
//            $total_count += $query->num_rows();
            $total_count += $result['total'];
        }

        return $total_count;
    }

    //New instance for map posted
    public function return_total_record_map_posted($form_list = Null, $to_date, $from_date) {


        $query = "SELECT *
                        FROM " . $form_list[0]['table_name'] . "
                        WHERE  location <> ''  
                        AND is_deleted = 0
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'";

        $query = $this->db->query($query);
        return $query->num_rows();
//        $this->db->from($form_list[0]['table_name']);
//        $this->db->where('is_deleted', '0');
//        $this->db->where('location <>', '');
//        $query = $this->db->get();
//
//        return $query->num_rows();
    }

    //New instance for graph
    public function return_total_record_for_graph($form_id = Null,$select_value='') {
        if($select_value!=''){
            $this->db->select("$select_value");
            $this->db->distinct();
        }

        $this->db->from('zform_' . $form_id);
        $this->db->where('is_deleted', '0');
        $this->db->where('location <>', '');
        $query = $this->db->get();
        return $query->result_array();
    }

    //new isntanct
    public function get_result_paginated_posted($table_name = FALSE, $to_date, $from_date, $category_name = array(), $filter_attribute_search = Null, $town_filter = '', $posted_filters = array(), $search_text, $district = '', $sent_by = '', $per_page, $selected_dc, $selected_uc, $selected_pp, $selected_na, $dynamic_filters) {

        $table_array = explode('_', $table_name);
        $form_id = $table_array[count($table_array) - 1];
        $selected_form = $this->form_model->get_form($form_id);
        $app_id = $selected_form['app_id'];

        $dynamic_cond = '';
        $join_cond = "LEFT JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
        if (!is_array($dynamic_filters)) {
            $dynamic_filters = json_decode($dynamic_filters, true);
        }
//                echo "<pre>";
//        print_r($dynamic_filters);die;
        if (!empty($dynamic_filters)) {
            foreach ($dynamic_filters as $key => $val) {
                if($key=="sent_by") {
                    $val = implode("_", array_keys($val));
                    $val = str_replace("_", ",", $val);
                }else{
                    $val = implode("_", $val);
                    $val = str_replace("_", ",", $val);
                }
//                echo $val;die;

                if($key=="sent_by"){
                    $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id  AND fr.imei_no IN ($val)";
                }else {
                    $val_arr=explode(",",$val);

                    $dynamic_cond.= "AND(";
                    foreach($val_arr as $key1=>$val){
                        $dynamic_cond .= "FIND_IN_SET('$val',fr.$key) OR ";
                    }
                    $dynamic_cond=$str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $dynamic_cond);
                    $dynamic_cond.=" ) ";
//                    echo $dynamic_cond;die;
//                    $dynamic_cond .= "AND fr.$key IN ('$val')";
//                    $dynamic_cond .= "AND FIND_IN_SET('$val',frs.$key)";
                }

            }
        }


//        if (empty($district)) {
//            $district = " AND 1=1";
//        } else {
//            $district = "AND fr.district_name ='$district'";
//        }
//
//        if (empty($sent_by)) {
//            $sent_by_cond = " AND 1=1";
//        } else {
//            $sent_by_cond = 'AND (';
//            foreach ($sent_by as $key => $val) {
//                $sent_by_cond .= " fr.imei_no = '$val' OR";
//            }
//            $lastSpacePosition = strrpos($sent_by_cond, " OR");
//            $sent_by_cond = substr($sent_by_cond, 0, $lastSpacePosition);
//            $sent_by_cond.=") ";
//        }
//
//
//        if (empty($selected_dc)) {
//            $selected_dc = " AND 1=1";
//        } else {
//            $selected_dc = "AND fr.Disbursement_Center ='$selected_dc'";
//        }
//
        $lists = '';
//        $list_for_reg_exp = '';
//
//        if (!empty($category_name) && isset($category_name)) {
//            foreach ($category_name as $cat_entity) {
//                $lists .= "'" . $cat_entity . "',";
//                $list_for_reg_exp .= $cat_entity . "|";
//            }
//            $lastORPosition = strrpos($lists, ',');
//            $lists = substr($lists, 0, $lastORPosition);
//
//            $lastORPositionRegExp = strrpos($list_for_reg_exp, '|');
//            $list_for_reg_exp = substr($list_for_reg_exp, 0, $lastORPositionRegExp);
//        }
//
//
//        //Search string operaiont starts here
        $heading_query = array();
        $table_headers_array = array();
        $table_header_text = '';
        $exclude_array = array('id', 'uc_name', 'created_datetime', 'district_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
        $heading_query = $this->getTableHeadingsFromSchema($table_name);

        $heading_counter_array = array();
        foreach ($heading_query as $key => $value) {
            $header_value = $value['COLUMN_NAME'];
            if (!in_array($header_value, $exclude_array)) {
                $table_header_text .= $header_value . ',';
                $heading_counter_array = array_merge($heading_counter_array, array($header_value));
            }
        }

        if (count($heading_counter_array) <= 1) {
            $table_header_text .= '1,1,';
        }
        $lastComPosition = strrpos($table_header_text, ',');
        $table_header_text = substr($table_header_text, 0, $lastComPosition);
        //search code ends here
//        echo $table_name;
//
//
//
//        if (is_array($filter_attribute_search)) {
//            $filter_attribute_search = $filter_attribute_search[0];
//        }
//
////        if (in_array($filter_attribute_search, $heading_counter_array)) {
        $offset = $this->getOffset();
        if ($lists == '') {
//
//
            $table_header_text = str_replace(",", ",fr.", $table_header_text);
            $table_header_text = "fr." . $table_header_text;
////                echo $table_header_text;die;
//            if ($sent_by != '') {
//                $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
//            } else {
//                $join_cond = "LEFT JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
//            }
//
            if ($search_text != '') {
                $search_where = "CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'";
            } else {
                $search_where = '1=1';
            }

            $query = "SELECT fr.*,au.name as sent_by
                        FROM " . $table_name . " fr
                        $join_cond
                        WHERE
                        $search_where
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                        AND
                        fr.is_deleted = 0

                        $dynamic_cond

                        Order By fr.id Desc LIMIT $offset,$per_page ";

        } else {

            $table_array = explode('_', $table_name);
            $form_id = $table_array[count($table_array) - 1];
            $selected_form = $this->form_model->get_form($form_id);
            $app_id = $selected_form['app_id'];

            $table_header_text = str_replace(",", ",fr.", $table_header_text);
            $table_header_text = "fr." . $table_header_text;
            if ($app_id == 1293) {

                $query = "SELECT *
                        FROM " . $table_name . " fr
                        WHERE  $filter_attribute_search REGEXP '" . $list_for_reg_exp . "'
                        AND CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                        AND fr.is_deleted = 0
                        Order By fr.id Desc LIMIT $offset,$per_page ";
            } else {
                if ($sent_by != '') {
                    $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
                } else {
                    $join_cond = "LEFT JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
                }
                if ($filter_attribute_search != '') {
                    $newWhere = 'fr.' . $filter_attribute_search;
                    $cat_search = 'WHERE ' . $newWhere . ' IN (' . "$lists" . ')';
                } else {
                    $cat_search = 'WHERE ';
                }
                if ($search_text != '') {
                    $search_where = "AND CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'";
                } else {
                    $search_where = 'AND 1=1';
                }

                $query = "SELECT fr.*,au.name as sent_by
                        FROM " . $table_name . " fr
                        $join_cond
                        $cat_search
                        $search_where
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                        AND fr.is_deleted = 0

                        $sent_by_cond
                        $district
                        $selected_dc
                        Order By fr.id Desc LIMIT $offset,$per_page ";
            }
        }

        $query = $this->db->query($query);
        return $query->result_array();
//        } else {
//
//            return array();
//        }
    }

    //new instance

    public function return_total_record_posted($form_list, $to_date, $from_date, $category_name = array(), $filter_attribute_search, $town_filter = '', $posted_filters = array(), $search_text = '', $district = '', $sent_by = '', $selected_dc, $dynamic_filters) {
        $dynamic_cond = '';
        $lists = '';
        $join_cond = "";

        if (is_array($filter_attribute_search)) {
            $filter_attribute_search = $filter_attribute_search[0];
        }

        $total_record = 0;
        $offset = $this->getOffset();
        foreach ($form_list as $form_name) {
            $table_name = $form_name['table_name'];

            //Search string operaiont starts here
            $heading_query = array();
            $table_headers_array = array();
            $heading_counter_array = array();
            $table_header_text = '';
            $exclude_array = array('id', 'uc_name', 'created_datetime', 'district_name', 'town_name', 'location', 'form_id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img1_title', 'img2_title', 'img3_title', 'img4_title', 'img5_title', 'is_deleted');
            $heading_query = $this->getTableHeadingsFromSchema($table_name);
            foreach ($heading_query as $key => $value) {
                $header_value = $value['COLUMN_NAME'];
                if (!in_array($header_value, $exclude_array)) {
                    $table_header_text .= $header_value . ',';
                    $heading_counter_array = array_merge($heading_counter_array, array($header_value));
                }
            }

            if (count($heading_counter_array) <= 1) {
                $table_header_text .= '1,1,';
            }
            $last_com_position = strrpos($table_header_text, ',');
            $table_header_text = substr($table_header_text, 0, $last_com_position);
            //search code ends here

//            if (in_array($filter_attribute_search, $heading_counter_array)) {
                if ($lists == '') {
                    $table_array = explode('_', $table_name);
                    $form_id = $table_array[count($table_array) - 1];
                    $selected_form = $this->form_model->get_form($form_id);
                    $app_id = $selected_form['app_id'];

                    $table_header_text = str_replace(",", ",fr.", $table_header_text);
                    $table_header_text = "fr." . $table_header_text;


                    if ($search_text != '') {
                        $search_where = "CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'";
                    } else {
                        $search_where = '1=1';
                    }

                    if (!is_array($dynamic_filters)) {
                        $dynamic_filters = json_decode($dynamic_filters, true);
                    }
                    if (!empty($dynamic_filters)) {
                        foreach ($dynamic_filters as $key => $val) {
                            if($key=="sent_by") {
                                $val = implode("_", array_keys($val));
                                $val = str_replace("_", ",", $val);
                            }else{
                                $val = implode("_", $val);
                                $val = str_replace("_", ",", $val);
                            }

                            if($key=="sent_by"){

                                $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id  AND fr.imei_no IN ($val)";
                            }else {

                                $val_arr=explode(",",$val);
                                $dynamic_cond.= "AND(";
                                foreach($val_arr as $key1=>$val){
                                    $dynamic_cond .= "FIND_IN_SET('$val',fr.$key) OR ";
                                }
                                $dynamic_cond=$str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $dynamic_cond);
                                $dynamic_cond.=" ) ";
                            }

                        }
                    }

                    $query = "SELECT fr.imei_no
                        FROM " . $table_name . " fr
                        $join_cond
                        WHERE
                          $search_where
                        AND fr.is_deleted = 0
                            $dynamic_cond
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'";
                } else {
                    $table_array = explode('_', $table_name);
                    $form_id = $table_array[count($table_array) - 1];
                    $selected_form = $this->form_model->get_form($form_id);
                    $app_id = $selected_form['app_id'];
                    $table_header_text = str_replace(",", ",fr.", $table_header_text);
                    $table_header_text = "fr." . $table_header_text;
                    if ($app_id == 1293) {

                        $query = "SELECT fr.imei_no
                        FROM " . $table_name . " fr

                        WHERE  'fr.'.$filter_attribute_search REGEXP '" . $list_for_reg_exp . "'
                        AND CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                        AND fr.is_deleted = 0";
                    } else {
                        if ($sent_by != '') {
                            $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
                        } else {
                            $join_cond = "LEFT JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id";
                        }
                        $new_where = 'fr.' . $filter_attribute_search;
                        $query = "SELECT fr.imei_no
                        FROM " . $table_name . " fr
                        $join_cond
                        WHERE  $newWhere IN (" . $lists . ")
                        AND CONCAT_WS($table_header_text) LIKE '%" . $search_text . "%'
                        AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                        $sent_by_cond
                        $district
                            $selected_dc
                            $dynamic_cond
                        AND fr.is_deleted = 0";
                    }
                }
                $query = $this->db->query($query);
                $total_record += $query->num_rows();
//            }
        }
        return $total_record;
    }

    //New Instance
    public function get_map_data_paginated($table_name = FALSE) {
        $query = "SELECT *
                  FROM " . $table_name . "
                  WHERE  location <> ''
                  AND is_deleted = 0
                  limit $this->perMap";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    //New Instance for export results
    public function get_results_for_export($table_name = FALSE, $to_date, $from_date, $app_id = null) {
        $query = "SELECT $table_name.*,app_users.name as sent_by,app_users.town as town
                  FROM " . $table_name . "
                  LEFT JOIN app_users ON ($table_name.imei_no=app_users.imei_no AND app_users.app_id=$app_id)
                  WHERE  location <> ''
                  AND $table_name.is_deleted = 0
                  AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    //New Instance
    public function get_map_data_paginated_posted($table_name = FALSE, $to_date, $from_date, $town_filter = null, $posted_filters = array(), $search_text = null, $login_district = NULL, $dynamic_filters) {
        $dynamic_cond='';
        if (!is_array($dynamic_filters)) {
            $dynamic_filters = json_decode($dynamic_filters, true);
        }
        if (!empty($dynamic_filters)) {
            foreach ($dynamic_filters as $key => $val) {
                $val = implode("_", $val);
                $val = str_replace("_", "','", $val);
//                echo $val;die;

                if($key=="sent_by"){
                    $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no AND au.app_id=$app_id  AND fr.imei_no IN ('$val')";
                }else {

                    $dynamic_cond .= "AND fr.$key IN ('$val')";
                }

            }
        }

        $query = "SELECT *
                  FROM " . $table_name . " fr
                   WHERE is_deleted = 0
                   $dynamic_cond
                   AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                   limit $this->perMap";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    //New Instance for load more
    public function get_map_data_load_more($form_list = FALSE, $to_date, $from_date, $page) {
        $per_page = $this->perMap;
        $totalRecords = $this->return_total_record($form_list);
//        $totalRecords = $this->get_form_results_count_for_map_ajax($form_list, $to_date, $from_date, $town_filter);
        $totalPages = ceil($totalRecords / $per_page);

        if ($totalRecords != $this->perMap) {
            $offset = ($totalPages - $page) * $per_page;
        }

        $table_name = 'zform_' . $form_list[0]['form_id'];

        $query = "SELECT *
                  FROM " . $table_name . " fr
                   WHERE is_deleted = 0
                   AND CAST(created_datetime AS DATE) BETWEEN '$to_date' AND '$from_date'
                   Order By id ASC LIMIT $offset,$per_page";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function syncDataFromRemoteServer($form_id, $from_date_stamp = null, $to_date_stamp = null,$imei_no = null) {

        $imei_query = '';
        if($imei_no)
          $imei_query = " AND fr.imei_no='".$imei_no."'";

        if (!empty($from_date_stamp) && !empty($to_date_stamp)) {
            // $from_date_stamp = date('Y-m-d H:i:s', $from_date_stamp);
            // $to_date_stamp = date('Y-m-d H:i:s', $to_date_stamp);
            $query = "SELECT fr.*,GROUP_CONCAT( CONCAT_WS('|',i.image,i.title)  SEPARATOR '#') AS zimages
                    FROM zform_" . $form_id . " fr
                    LEFT JOIN zform_images AS i ON i.zform_result_id=fr.id AND i.form_id=" . $form_id . "
                    WHERE  fr.form_id = " . $form_id . "
                    AND fr.is_deleted = 0
                    AND fr.location <> ''
                    AND fr.created_datetime >= '" . $from_date_stamp . "' AND fr.created_datetime <= '" . $to_date_stamp . "'
                    $imei_query
                    GROUP BY fr.id Order By fr.created_datetime Desc";
        } else if (!empty($from_date_stamp)) {
            //$from_date_stamp = date('Y-m-d H:i:s', $from_date_stamp);
            $query = "SELECT fr.*,GROUP_CONCAT( CONCAT_WS('|',i.image,i.title)  SEPARATOR '#') AS zimages
                    FROM zform_" . $form_id . " fr
                    LEFT JOIN zform_images AS i ON i.zform_result_id=fr.id AND i.form_id=" . $form_id . "
                    WHERE  fr.form_id = " . $form_id . "
                    AND fr.is_deleted = 0
                    AND fr.location <> ''
                    AND fr.created_datetime >= '" . $from_date_stamp . "'
                    $imei_query
                    GROUP BY fr.id Order By fr.created_datetime Desc";
        } else if (!empty($to_date_stamp)) {
            //$to_date_stamp = date('Y-m-d H:i:s', $to_date_stamp);
            $query = "SELECT fr.*,GROUP_CONCAT( CONCAT_WS('|',i.image,i.title)  SEPARATOR '#') AS zimages
                    FROM zform_" . $form_id . " fr
                    LEFT JOIN zform_images AS i ON i.zform_result_id=fr.id AND i.form_id=" . $form_id . "
                    WHERE  fr.form_id = " . $form_id . "
                    AND fr.is_deleted = 0
                    AND fr.location <> ''
                    AND fr.created_datetime <= '" . $to_date_stamp . "'
                    $imei_query
                    GROUP BY fr.id Order By fr.created_datetime Desc";
        } else {
            $query = "SELECT fr.*,GROUP_CONCAT( CONCAT_WS('|',i.image,i.title)  SEPARATOR '#') AS zimages
                    FROM zform_" . $form_id . " fr
                    LEFT JOIN zform_images AS i ON i.zform_result_id=fr.id AND i.form_id=" . $form_id . "
                    WHERE  fr.form_id = " . $form_id . "
                    AND fr.is_deleted = 0
                    AND fr.location <> ''
                    $imei_query
                    GROUP BY fr.id Order By fr.created_datetime Desc";
        }
        //echo $query;

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function syncDataFromRemoteServerCustomData($form_id, $from_date_stamp = null, $to_date_stamp = null,$imei_no = null) {

        $imei_query = '';
        if($imei_no)
          $imei_query = " AND zf.imei_no='".$imei_no."'";

        if (!empty($from_date_stamp) && !empty($to_date_stamp)) {
      
            // $from_date_stamp = date('Y-m-d H:i:s', $from_date_stamp);
            // $to_date_stamp = date('Y-m-d H:i:s', $to_date_stamp);
      
            $query = "SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_In_location as check_in_check_out_location , zf.location ,
            'check_in_type' as ci_type  ,

            'bcg_count' as bcg_count,
            'bcg_viles' as bcg_viles,

            'opv_count' as opv_count,
            'opv_viles' as opv_viles,

            'penta_count' as penta_count,
            'penta_viles' as penta_viles,

            'meales_count' as measles_count,
            'meales_viles' as measles_viles,

            'pneumo_count' as pneumo_count,
            'pneumo_viles' as pneumo_viles,

            'zf.Number_of_Children_Vaccinated_IPV' as ipv_count,
            'zf.Number_of_Vials_Consumed_IPV' as ipv_viles,

            'zf.Number_of_Children_Vaccinated_TT' as tt_count,
            'zf.Number_of_Vials_Consumed_TT' as tt_viles,
            
            'zf.Number_of_Children_Vaccinated_pentavalent' as penta_count,
            'zf.Number_of_Vials_Consumed_pentavalent' as penta_viles

            FROM zform_1 zf
            WHERE 
            zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_In_location <> '' AND zf.Check_In_location IS NOT NULL
            AND zf.location <> '0.0,0.0' AND zf.Check_In_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND  DATE(zf.created_datetime) BETWEEN '" . $from_date_stamp . "' AND '" . $to_date_stamp . "'
            GROUP BY zf.imei_no , zf.activity_datetime

            UNION

            SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_Out , zf.Check_Out_location as check_in_check_out_location , zf.location ,
            if(zf.Select_Activity_Performed='Routine Immunization',1,2) as ci_type ,

            zf.Number_of_Children_Vaccinated_bcg as bcg_count,
            zf.Number_of_Vials_Consumed_bcg as bcg_viles,

            zf.Number_of_Children_Vaccinated_opv as opv_count,
            zf.Number_of_Vials_Consumed_opv as opv_viles,

            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles,

            zf.Number_of_Children_Vaccinated_Measles as measles_count,
            zf.Number_of_Vials_Consumed_Measles as measles_viles,

            zf.Number_of_Children_Vaccinated_Pneumococcal as pneumo_count,
            zf.Number_of_Vials_Consumed_Pneumococcal as pneumo_viles,

            zf.Number_of_Children_Vaccinated_IPV as ipv_count,
            zf.Number_of_Vials_Consumed_IPV as ipv_viles,

            zf.Number_of_Children_Vaccinated_TT as tt_count,
            zf.Number_of_Vials_Consumed_TT as tt_viles,
            
            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles
             
            FROM zform_2 zf
            WHERE 
            zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_Out_location <> '' AND zf.Check_Out_location IS NOT NULL 
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND zf.location <> '0.0,0.0' AND zf.Check_Out_location <> '0.0,0.0'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND  DATE(zf.created_datetime) BETWEEN '" . $from_date_stamp . "' AND '" . $to_date_stamp . "'
            GROUP BY zf.imei_no , zf.activity_datetime";
      
        } else if (!empty($from_date_stamp)) {
            $from_date_stamp = date('Y-m-d H:i:s', $from_date_stamp);
            $query = "SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_In_location as check_in_check_out_location , zf.location ,
            'check_in_type' as ci_type  ,

            'bcg_count' as bcg_count,
            'bcg_viles' as bcg_viles,

            'opv_count' as opv_count,
            'opv_viles' as opv_viles,

            'penta_count' as penta_count,
            'penta_viles' as penta_viles,

            'meales_count' as meales_count,
            'meales_viles' as meales_viles,

            'pneumo_count' as pneumo_count,
            'pneumo_viles' as pneumo_viles,

            'zf.Number_of_Children_Vaccinated_IPV' as ipv_count,
            'zf.Number_of_Vials_Consumed_IPV' as ipv_viles,

            'zf.Number_of_Children_Vaccinated_TT' as tt_count,
            'zf.Number_of_Vials_Consumed_TT' as tt_viles,
            
            'zf.Number_of_Children_Vaccinated_pentavalent' as penta_count,
            'zf.Number_of_Vials_Consumed_pentavalent' as penta_viles

            FROM zform_1 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_In_location <> '' AND zf.Check_In_location IS NOT NULL 
            AND zf.activity_datetime <> '0000-00-00 00:00:00'
            AND zf.location <> '0.0,0.0' AND zf.Check_In_location <> '0.0,0.0'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND zf.created_datetime >= '" . $from_date_stamp . "'
            GROUP BY zf.imei_no

            UNION

            SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_Out_location as check_in_check_out_location , zf.location ,
            if(zf.Select_Activity_Performed='Routine Immunization',1,2) as ci_type ,

            zf.Number_of_Children_Vaccinated_bcg as bcg_count,
            zf.Number_of_Vials_Consumed_bcg as bcg_viles,

            zf.Number_of_Children_Vaccinated_opv as opv_count,
            zf.Number_of_Vials_Consumed_opv as opv_viles,

            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles,

            zf.Number_of_Children_Vaccinated_Measles as meales_count,
            zf.Number_of_Vials_Consumed_Measles as meales_viles,

            zf.Number_of_Children_Vaccinated_Pneumococcal as pneumo_count,
            zf.Number_of_Vials_Consumed_Pneumococcal as pneumo_viles,

            zf.Number_of_Children_Vaccinated_IPV as ipv_count,
            zf.Number_of_Vials_Consumed_IPV as ipv_viles,

            zf.Number_of_Children_Vaccinated_TT as tt_count,
            zf.Number_of_Vials_Consumed_TT as tt_viles,
            
            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles
             
            FROM zform_2 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_Out_location <> '' AND zf.Check_Out_location IS NOT NULL 
            AND zf.location <> '0.0,0.0' AND zf.Check_Out_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND zf.created_datetime >= '" . $from_date_stamp . "'
            GROUP BY zf.imei_no";
        } else if (!empty($to_date_stamp)) {
            $to_date_stamp = date('Y-m-d H:i:s', $to_date_stamp);
            $query = "SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_In_location as check_in_check_out_location , zf.location ,
            'check_in_type' as ci_type  ,

            'bcg_count' as bcg_count,
            'bcg_viles' as bcg_viles,

            'opv_count' as opv_count,
            'opv_viles' as opv_viles,

            'penta_count' as penta_count,
            'penta_viles' as penta_viles,

            'meales_count' as meales_count,
            'meales_viles' as meales_viles,

            'pneumo_count' as pneumo_count,
            'pneumo_viles' as pneumo_viles,

            'zf.Number_of_Children_Vaccinated_IPV' as ipv_count,
            'zf.Number_of_Vials_Consumed_IPV' as ipv_viles,

            'zf.Number_of_Children_Vaccinated_TT' as tt_count,
            'zf.Number_of_Vials_Consumed_TT' as tt_viles,
            
            'zf.Number_of_Children_Vaccinated_pentavalent' as penta_count,
            'zf.Number_of_Vials_Consumed_pentavalent' as penta_viles

            FROM zform_1 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_In_location <> '' AND zf.Check_In_location IS NOT NULL 
            AND zf.location <> '0.0,0.0' AND zf.Check_In_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND zf.created_datetime <= '" . $to_date_stamp . "'
            GROUP BY zf.imei_no

            UNION

            SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_Out_location as check_in_check_out_location , zf.location ,
            if(zf.Select_Activity_Performed='Routine Immunization',1,2) as ci_type ,

            zf.Number_of_Children_Vaccinated_bcg as bcg_count,
            zf.Number_of_Vials_Consumed_bcg as bcg_viles,

            zf.Number_of_Children_Vaccinated_opv as opv_count,
            zf.Number_of_Vials_Consumed_opv as opv_viles,

            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles,

            zf.Number_of_Children_Vaccinated_Measles as meales_count,
            zf.Number_of_Vials_Consumed_Measles as meales_viles,

            zf.Number_of_Children_Vaccinated_Pneumococcal as pneumo_count,
            zf.Number_of_Vials_Consumed_Pneumococcal as pneumo_viles,

            zf.Number_of_Children_Vaccinated_IPV as ipv_count,
            zf.Number_of_Vials_Consumed_IPV as ipv_viles,

            zf.Number_of_Children_Vaccinated_TT as tt_count,
            zf.Number_of_Vials_Consumed_TT as tt_viles,
            
            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles
             
            FROM zform_2 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_Out_location <> '' AND zf.Check_Out_location IS NOT NULL 
            AND zf.location <> '0.0,0.0' AND zf.Check_Out_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            AND zf.created_datetime <= '" . $to_date_stamp . "'
            GROUP BY zf.imei_no";
        } else {
            $query = "SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_In_location as check_in_check_out_location , zf.location ,
            'check_in_type' as ci_type  ,

            'bcg_count' as bcg_count,
            'bcg_viles' as bcg_viles,

            'opv_count' as opv_count,
            'opv_viles' as opv_viles,

            'penta_count' as penta_count,
            'penta_viles' as penta_viles,

            'meales_count' as meales_count,
            'meales_viles' as meales_viles,

            'pneumo_count' as pneumo_count,
            'pneumo_viles' as pneumo_viles,

            'zf.Number_of_Children_Vaccinated_IPV' as ipv_count,
            'zf.Number_of_Vials_Consumed_IPV' as ipv_viles,

            'zf.Number_of_Children_Vaccinated_TT' as tt_count,
            'zf.Number_of_Vials_Consumed_TT' as tt_viles,
            
            'zf.Number_of_Children_Vaccinated_pentavalent' as penta_count,
            'zf.Number_of_Vials_Consumed_pentavalent' as penta_viles

            FROM zform_1 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_In_location <> '' AND zf.Check_In_location IS NOT NULL 
            AND zf.location <> '0.0,0.0' AND zf.Check_In_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            GROUP BY zf.imei_no

            UNION

            SELECT 
            zf.form_id , zf.imei_no , zf.activity_datetime , zf.Check_In , zf.Check_Out_location as check_in_check_out_location , zf.location ,
            if(zf.Select_Activity_Performed='Routine Immunization',1,2) as ci_type ,

            zf.Number_of_Children_Vaccinated_bcg as bcg_count,
            zf.Number_of_Vials_Consumed_bcg as bcg_viles,

            zf.Number_of_Children_Vaccinated_opv as opv_count,
            zf.Number_of_Vials_Consumed_opv as opv_viles,

            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles,

            zf.Number_of_Children_Vaccinated_Measles as meales_count,
            zf.Number_of_Vials_Consumed_Measles as meales_viles,

            zf.Number_of_Children_Vaccinated_Pneumococcal as pneumo_count,
            zf.Number_of_Vials_Consumed_Pneumococcal as pneumo_viles,

            zf.Number_of_Children_Vaccinated_IPV as ipv_count,
            zf.Number_of_Vials_Consumed_IPV as ipv_viles,

            zf.Number_of_Children_Vaccinated_TT as tt_count,
            zf.Number_of_Vials_Consumed_TT as tt_viles,
            
            zf.Number_of_Children_Vaccinated_pentavalent as penta_count,
            zf.Number_of_Vials_Consumed_pentavalent as penta_viles
             
            FROM zform_2 zf
            WHERE 
            AND zf.is_deleted = 0
            AND zf.location <> '' AND zf.location IS NOT NULL 
            AND zf.Check_Out_location <> '' AND zf.Check_Out_location IS NOT NULL 
            AND zf.location <> '0.0,0.0' AND zf.Check_Out_location <> '0.0,0.0'
            AND  zf.activity_datetime <> '0000-00-00 00:00:00'
            AND  date(zf.activity_datetime) != '1970-01-01'
            AND  zf.activity_datetime <> ''
            AND  zf.activity_datetime IS NOT NULL
            GROUP BY zf.imei_no";
        }
    
    // echo $query; die;
    
        $query = $this->db->query($query);
        return $query->result_array();
    }

    /*
     * Get Unique district based on app id
     * ubaidullah.balti@itu.edu.pk
     */

    public function get_distinct_district($app_id) {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }

        $this->db->distinct();
        $this->db->where('is_deleted', '0');
        $this->db->where('location <>', '');
        $this->db->select('district_name');
        $this->db->from($forms_list[0]['table_name']);
        $query = $this->db->get();
//         $this->db->where('is_deleted', '0');
//        $this->db->where('location <>', '');
        return $query->result_array();
    }

    public function get_distinct_sent_by($app_id) {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }

        $this->db->distinct();
//        $this->db->where('is_deleted', '0');
//        $this->db->where('location <>', '');
        $this->db->select('name,imei_no');
        $this->db->from('app_users');
        $this->db->where('app_id', $app_id);
        $this->db->order_by("name ASC");
        $query = $this->db->get();
//         $this->db->where('is_deleted', '0');
//        $this->db->where('location <>', '');
        return $result = $query->result_array();
//        echo "<pre>";
//        print_r($result);die;
    }

    public function get_district_categorized_count($form_id, $district_name, $filter_attribute, $category_name, $to_date, $from_date) {
        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $table_name = 'zform_' . $form_id;
        $category_name = str_replace("'", "\'", $category_name);

        $query = "SELECT *
                  FROM $table_name
                  WHERE " . $filter_attribute . " LIKE '%" . $category_name . "%' 
                  AND is_deleted =0
                  AND district_name = '" . $district_name . "'
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> ''";
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    /**
     * Get category list from each form
     */
    public function get_category_values($table_name, $filter_attribute) {
        $query = "SELECT " . $filter_attribute . "
                  FROM " . $table_name . "
                  WHERE  is_deleted =0
                  AND location <> ''
                  GROUP BY " . $filter_attribute . "";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function get_disbursment_record($form_id, $dates) {
        $this->db->select();
        $this->db->from('zform_' . $form_id);
        $this->db->where('is_deleted', '0');

        if ($dates) {
            $this->db->like('created_datetime', $dates);
        }
        $this->db->order_by("created_datetime", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_disbursment_type_record() {

        $this->db->select();
        $this->db->from('zform_1768');
        $this->db->where('is_deleted', '0');
        $this->db->order_by("Type", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get Distinct Distbursment Center 
     * for DC application
     * $slug
     */
    public function get_distinct_d_center($app_id) {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }

        $this->db->distinct();
        $this->db->select('Disbursement_Center');
        $this->db->from($forms_list[0]['table_name']);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get Distinct Distbursment Center 
     * for DC application on based of district wise
     * $slug
     */
    public function get_d_center_district_wise($app_id, $district) {
        $forms_list = array();
        $all_forms = $this->form_model->get_form_by_app($app_id);
        foreach ($all_forms as $forms) {
            $forms_list[] = array('form_id' => $forms['form_id'], 'table_name' => 'zform_' . $forms['form_id'], 'form_name' => $forms['form_name']);
        }

        $this->db->distinct();
        $this->db->select('Disbursement_Center');
        $this->db->from($forms_list[0]['table_name']);
        $this->db->where('district_name', $district);
        $query = $this->db->get();
//        return $query->result_array();

        $Disbursement_Centers = array('' => 'Select DC');

        if ($query->result()) {
            foreach ($query->result() as $group) {
                $Disbursement_Centers[$group->Disbursement_Center] = $group->Disbursement_Center;
            }
            return $Disbursement_Centers;
        } else {
            return FALSE;
        }
    }

    /**
     * @param type $table_name
     * @return type
     * Method for returning last max remote id only for importiing data from 
     * other server purpose
     * @author ubaidcskiu@gmail.com
     */
    public function return_max_remote_id($table_name) {

        $query = "SELECT MAX(remote_id) as max_remote_id
                  FROM $table_name";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getCountCatgoryBaseNew($slug = FALSE, $category_name, $filter_attribute_search = array(), $from_date, $to_date, $selected_district, $selected_sent_by = '', $selected_uc = '') {

        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $category_name = str_replace("'", "\'", $category_name);
        if ($selected_district) {
            $district_query = "AND district_name = '" . $selected_district . "'";
        } else {
            $district_query = "AND 1=1";
        }
        if ($selected_sent_by) {
            $sent_by_query = "AND imei_no = '" . $selected_sent_by . "'";
        } else {
            $sent_by_query = "AND 1=1";
        }

        $filter_string = '';
        if (is_array($filter_attribute_search) AND $category_name!='') {
            $filter_string = '(';
            foreach ($filter_attribute_search as $filter_name) {
                $category = str_replace("/", "\\\\\\\\/", $category_name);
                $filter_string .= $filter_name . " LIKE '$category' OR ";
            }
            $lastORPosition = strrpos($filter_string, 'OR');
            $filter_string = substr($filter_string, 0, $lastORPosition);
            $filter_string .= ') AND (';
        } else {
            $filter_string .= "1=1";
        }
//        $lasRemoval = strrpos($filter_string, 'AND');
//        $filter_string = substr($filter_string, 0, $lasRemoval);

        if (is_array($filter_attribute_search)) {
            $filter_attribute_search = $filter_attribute_search[0];
        }



        $table_name = 'zform_' . $slug;

        $selected_form = $this->form_model->get_form($slug);
        $app_id = $selected_form['app_id'];
        if ($app_id == 1293) {
            $query = "SELECT *
                  FROM " . $table_name . "
                  WHERE " . $filter_attribute_search . " LIKE '" . $category_name . "%'
                  AND is_deleted =0
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> ''
                 $district_query
                 $sent_by_query";
        } else {
            $query = "SELECT COUNT(*) as total,district_name,$filter_attribute_search
                  FROM " . $table_name . "
                  WHERE " . $filter_string . "
                  AND is_deleted =0
                   AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  $district_query
                  $sent_by_query
                  GROUP BY district_name, $filter_attribute_search order by $filter_attribute_search
                  ";
        }

//        echo $query;
//        die;
//        $query = $this->db->query($query);
//        $query = "SELECT fr.id as 'id',fr.form_id,fr.location,fr.record,fr.is_deleted
//                        FROM `form_results` fr
//                        WHERE " . $filter_string . "
//                        AND fr.is_deleted =0
//                        AND fr.form_id = '" . $slug . "'
//                        AND fr.location <> ''";

        $query = $this->db->query($query);

        return $query->result_array();
    }

    public function get_district_categorized_count_new($form_id, $district_name, $filter_attribute, $category_name, $from_date, $to_date) {
        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $table_name = 'zform_' . $form_id;
        $category_name = str_replace("'", "\'", $category_name);

        $query = "SELECT COUNT(*) as total,district_name,$filter_attribute
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> ''
                  AND district_name!=''
                  AND $filter_attribute LIKE '%$category_name%'
                  GROUP BY district_name order by $filter_attribute
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }


    public function get_custom_reports_new($form_id, $district_name, $filter_attribute, $category_name, $from_date, $to_date, $new_category) {
        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $table_name = 'zform_' . $form_id;
        $category_name = str_replace("'", "\'", $category_name);

        $query = "SELECT COUNT(*) as total,$new_category,$filter_attribute
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'

                  AND $new_category!=''
                  AND $filter_attribute LIKE '%$category_name%'
                  GROUP BY $new_category order by $filter_attribute
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function get_monthwise_categorized_count_new($form_id, $district_name, $filter_attribute, $category_name, $from_date, $to_date) {
        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $table_name = 'zform_' . $form_id;
        $category_name = str_replace("'", "\'", $category_name);

        $query = "SELECT COUNT(*) as total,EXTRACT(MONTH FROM `activity_datetime`) AS MONTH,EXTRACT(YEAR FROM `activity_datetime`) AS YEAR,CONCAT(EXTRACT(MONTH FROM `activity_datetime`),'/','01/',EXTRACT(YEAR FROM `activity_datetime`)) AS MONYER,$filter_attribute
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  AND location <> ''
                  AND district_name!=''
                  AND $filter_attribute LIKE '%$category_name%'
                  GROUP BY MONTH,YEAR order by activity_datetime DESC
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    
    public function get_school_categorized_count_new($form_id, $district_name, $filter_attribute, $category_name, $from_date, $to_date) {
        $from_date = empty($from_date) ? '2000-12-19' : $from_date;
        $to_date = empty($to_date) ? '2050-12-19' : $to_date;
        $table_name = 'zform_' . $form_id;
        $category_name = str_replace("'", "\'", $category_name);

        $query = "SELECT COUNT(*) as total,$filter_attribute
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  AND CAST(created_datetime AS DATE) BETWEEN '$from_date' AND '$to_date'
                  AND location <> ''
                  AND district_name='$district_name'
                  AND FIND_IN_SET('$category_name',$filter_attribute)
                  GROUP BY created_datetime order by $filter_attribute
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function get_distinct_values_of_column($form_id,$filter_column){
        $table_name="zform_$form_id";
        $query = "SELECT DISTINCT $filter_column
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function find_record_count($table_name){
        $query = "SELECT COUNT(*) as total
                  FROM $table_name
                  WHERE
                  is_deleted =0
                  ";
        $query = $this->db->query($query);
        $result=$query->row();
        $query->free_result();
        return $result->total;
    }

    function get_dynamic_results($column,$table_name,$search_word){
        if($search_word==""){
            $search_cond="1=1 AND ";
        }else{
            $search_cond="fr.$column LIKE '$search_word%' AND ";
        }

        if($column=="sent_by"){
            if($search_word==" "){
                $search_cond_sent_by="1=1  ";
            }else {
                $search_cond_sent_by="au.name LIKE '$search_word%'";
            }
            $join_cond = "JOIN app_users au ON fr.imei_no=au.imei_no  AND $search_cond_sent_by";
            $search_cond="1=1 AND ";
            $column="au.name as sent_by, au.imei_no as imei_no";
        }else{
            $join_cond="";
        }

        $query = "SELECT DISTINCT $column
                  FROM $table_name fr
                  $join_cond
                  WHERE
                  $search_cond
                  fr.is_deleted =0 LIMIT 0 , 200
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function get_users_name_from_imei_no($imei_string){
        $query = "SELECT name,imei_no
                  FROM app_users
                  WHERE
                  imei_no IN  ($imei_string)
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function get_subtable_data($table,$row_id){
        $query = "SELECT *
                  FROM $table
                  WHERE zform_result_id=$row_id
                  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    
    function save_mobile_activity($record_array){
        $this->db->insert('mobile_activity_log', $record_array);
        return $this->db->insert_id();
    }
    function update_mobile_activity($id,$record_array){
        $this->db->where('id', $id);
        $this->db->update('mobile_activity_log', $record_array);
    }
    function remove_mobile_activity($id){
        $this->db->delete('mobile_activity_log', array('id' => $id));
    }

    function get_unsaved_activities($limit=null,$length=null,$search=null,$sort_column=null,$sort_order=null){
            $allColumns=array(
              "ma.id",
              "ma.app_id",
              "a.name",
              "ma.form_id",
              "f.name",
              "ma.imei_no",
              "ma.dateTime",
              "ma.form_data",
              "ma.error",
          );

          $this->db->select("ma.id as activity_id,ma.app_id,a.name as app_name,ma.form_id,f.name as form_name,ma.form_data,ma.dateTime,ma.error,ma.imei_no",FALSE);
          $this->db->from('mobile_activity_log ma');
          $this->db->join('app a', 'ma.app_id = a.id','left');
          $this->db->join('form f', 'ma.form_id = f.id','left');

          if($search!=null){
              $like="(a.name LIKE '%$search%' OR
                      f.name LIKE '%$search%' OR
                      a.id LIKE '%$search%' OR
                      f.id LIKE '%$search%' OR
                      ma.form_data LIKE '%$search%')";
              $this->db->where($like);
          }

          if($sort_column!=null && $sort_order!=null){
              $this->db->order_by($allColumns[$sort_column], ucwords($sort_order));
          }

          if($length!=null && $limit!=null) {
              $this->db->limit($length, $limit);
          }
          $query = $this->db->get();
          return $query->result_array();

    }

    function get_unsaved_activities_total($search=null){
            $allColumns=array(
              "ma.id",
              "ma.app_id",
              "a.name",
              "ma.form_id",
              "f.name",
              "ma.imei_no",
              "ma.dateTime",
              "ma.form_data",
              "ma.error",
          );


          $this->db->select("ma.id as activity_id,ma.app_id,a.name as app_name,ma.form_id,f.name as form_name,ma.form_data,ma.dateTime,ma.error,ma.imei_no",FALSE);
          $this->db->from('mobile_activity_log ma');
          $this->db->join('app a', 'ma.app_id = a.id','left');
          $this->db->join('form f', 'ma.form_id = f.id','left');

          if($search!=null){
              $like="(a.name LIKE '%$search%' OR
                      f.name LIKE '%$search%' OR
                      a.id LIKE '%$search%' OR
                      f.id LIKE '%$search%' OR
                      ma.form_data LIKE '%$search%')";
              $this->db->where($like);
          }

         return $query = $this->db->count_all_results();

    }
    
    
    function save_mobile_tracking($record_array){
        $this->db->insert('mobile_tracking_log', $record_array);
        return $this->db->insert_id();
    }
    function update_mobile_tracking($id,$record_array){
        $this->db->where('id', $id);
        $this->db->update('mobile_tracking_log', $record_array);
    }
    function remove_mobile_tracking($id){
        $this->db->delete('mobile_tracking_log', array('id' => $id));
    }

    function get_unsaved_tracking(){
        $this->db->select("ma.id as activity_id,f.name as form_name,a.name as app_name,ma.*",FALSE);
        $this->db->from('mobile_tracking_log ma');
        $this->db->join('app a', 'ma.app_id = a.id','left');
        $this->db->join('form f', 'ma.form_id = f.id','left');
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>
