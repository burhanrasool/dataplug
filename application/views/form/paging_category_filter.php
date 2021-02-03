<?php

$config['first_link'] = '<input type="submit" value="First">';

$config['div'] = 'container'; //Div tag id
$url = base_url() . 'form/' . $ajax_function . '?form_id=' . $slug . '&to_date=' . $to_date;
$url .= '&from_date=' . $from_date;
if (is_array($filter_attribute_search)) {
    foreach ($filter_attribute_search as $category) {
        $category = str_replace("/", '_', $category);
        if (strpos($category, '&') !== false) {
            $category = rawurlencode($category);
        }
        $url .= '&filter_attribute_search[]=' . $category;
    }
}

//echo '<pre>';
//print_r($posted_filters);die;
if (is_array($form_list_filter)) {
    foreach ($form_list_filter as $category) {
        $category = str_replace("/", '_', $category);
        if (strpos($category, '&') !== false) {
            $category = rawurlencode($category);
        }
        $url .= '&form_list_filter[]=' . $category;
    }
}

if(is_array($dynamic_filters)){
    $url.="&dynamic_filters=".rawurlencode(json_encode($dynamic_filters));
}
//for dynamic filters
if (!empty($posted_filters)) {
    foreach ($posted_filters as $key => $filters) {
        $key = str_replace("/", '_', $key);
        if (strpos($key, '&') !== false) {
            $key = rawurlencode($key);
        }
        foreach ($filters as $inside) {
            $inside = str_replace("/", '_', $inside);
            if (strpos($inside, '&') !== false) {
                $inside = rawurlencode($inside);
            }
            $url .= '&' . $key . '[]=' . $inside;
        }
    }
}
//dynamic filters ends here
if (is_array($cat_filter_value)) {
    foreach ($cat_filter_value as $category) {
        $category = str_replace("/", '_', $category);
        if (strpos($category, '&') !== false) {
            $category = rawurlencode($category);
        }
        $url .= '&cat_filter_value[]=' . $category;
    }
}
$url .= '&town_filter=' . $town_filter;
$url .= '&district=' . $district;
$url .= '&selected_dc=' . $selected_dc;
$url .= '&search_text=' . $search_text . '&';

$config['base_url'] = $url;
$config['total_rows'] = $total_rows;
$config['per_page'] = $per_page;
$config['postVar'] = 'page';

$this->ajax_pagination->initialize($config);
echo $this->ajax_pagination->create_links();
