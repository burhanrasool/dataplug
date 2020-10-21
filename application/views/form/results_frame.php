
                <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/component.css'>
                    <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/cmxform.css'>


                        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css" />
                        <style type="text/css" title="currentStyle">@import "<?= base_url() ?>assets/css/demo_table_jui.css";@import "<?= base_url() ?>assets/css/jquery.dataTables.css";@import "<?= base_url() ?>assets/themes/flick/jquery.ui.theme.css";@import "<?= base_url() ?>assets/themes/flick/jquery-ui.min.css";</style>
                        <link rel="stylesheet" href="<?= base_url() ?>assets/themes/smoothness/jquery-ui-1.8.4.custom.css">
<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    $margin = '';
    if (count($form_lists) > 1) {
        $margin = 'margin:11px 0 -40px 184px';
    } else {
        $margin = 'margin:11px 0 -38px';
    }
    ?>
</div>
<div class="applicationText" style="width: 100%">
    <div class="crimeListTexta" style="<?php echo $margin; ?>">

        <form id='setfilter' method='POST' action='<?= base_url() ?>form/changefilter/<?php echo $form_id ?>'>
            <input name="redirect_to" value="mapview" type="hidden" />
            Category :
            <select class="required" name="filter" id="filter" style="width:188px;max-width: 127px;" onChange="jQuery('#overlay_loading').show();
                    filter_update('<?php echo $app_id ?>', jQuery(this).val())"/>
            </select>
        </form>
    </div>
    <?php
    echo '<div class="filter_class" style="width: 100%">';
    $app_name = preg_replace('/[^A-Za-z0-9]/', '-', $app_name);
    $slug = $app_name . '-' . $app_id;
    echo form_open(base_url() . 'form/resultsframe/' . $slug, 'id=date_filter_form name=date_filter_form');

    //fOR multiple filters handling
    $selected_final = "";
    foreach ($app_filters_array as $key => $filters) {
        foreach ($selected_filters as $selected_key => $selected) {
            if ($key == $selected_key) {
                $selected_final = $selected;
            }
        }


        echo '<div class="form_class" style="float: left">';

        echo '&nbsp;' . str_replace('_', ' ', $key) . ' : ';
        echo '<select id=' . $key . ' name =' . $key . '[] class="filter_list_listview" multiple="multiple" rel = ' . $key . '>';
        foreach ($filters as $category => $value) {
            $category = (strlen($value) > 23) ? substr($value, 0, 23) . ' ...' : $value;
            if ($selected_final) {
                if (in_array($value, $selected_final)) {
                    echo '<option value="' . $value . '" selected="selected" >' . $category . '</option>';
                } else {
                    echo '<option value="' . $value . '" >' . $category . '</option>';
                }
            } else {
                echo '<option value="' . $value . '" >' . $category . '</option>';
            }
        }
        echo '<select>';

        echo '</div>';
    }
    //multiple filters ends hree
    echo '<div class="form_class" style="float: left">';
    if (count($form_lists) > 1) {
        echo 'Forms : ';
        echo form_dropdown('form_list[]', $form_lists, $form_list_selected, 'id="form_list" class="form_list"');
        echo '';
    } else {
        echo '<input type="hidden" value="' . $form_id . '" name="form_list[]">';
    }
    echo '</div>';
    ?>

    <span id="filter_span">
        <?php
        if (!empty($filter_attribute)) {
            $this->load->helper(array('form'));

            $selected_cat_filter = !empty($selected_cat_filter) ? $selected_cat_filter : "";
            echo '<label style="padding:0px;">Sub Category</label>';
            echo ': ' . form_dropdown('cat_filter[]', $category_values, $selected_cat_filter, 'id="cat_filter" class="multiselect" multiple="multiple" style="height:26px;border:1px solid #0e76bd;" ');
            echo '';
        }
        ?>
    </span>
    <?php if (isset($district_list)) { ?>
        <span id="filter_span_district">
            Districts :  
            <select  name="district_list" id="district_list" style="max-width: 127px" >
                <option selected="selected" value="">All District</option>
                <?php
                foreach ($district_list as $district) {
                    if (isset($district['district_name']) && $district['district_name'] != '') {
                        $select = '';
                        if (strip_tags($district['district_name']) == $selected_district) {
                            $select = 'selected';
                        }
                        ?>
                        <option <?php echo $select; ?> value="<?php echo strip_tags($district['district_name']); ?>"><?php echo $district['district_name']; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </span>
    <?php } ?>
    <br>
    <br>


    <?php if (isset($sent_by_list)) { ?>
        <span id="filter_span_sent_by">
            Sent By :
            <select  name="sent_by_list[]" style="max-width: 127px" id="sent_by_filter" class="multiselect" multiple="multiple" >
<!--                <option selected="selected" value="">Sent By</option>-->
                <?php
//                print_r($sent_by_list);die;
                foreach ($sent_by_list as $sent_by) {
                    if (isset($sent_by['imei_no']) && $sent_by['imei_no'] != '') {
                        $select = '';
//                        if (strip_tags($sent_by['imei_no']) == $selected_sent_by) {
                        if(in_array($sent_by['imei_no'],$selected_sent_by)){
                            $select = 'selected';
                        }
                        ?>
                        <option <?php echo $select; ?> value="<?php echo strip_tags($sent_by['imei_no']); ?>"><?php echo $sent_by['name']; ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        </span>
    <?php } ?>
<br>

    <div style="margin:10px 0 0 0px;">

        <input type="hidden" value="<?php echo $form_id; ?>" name="form_id">
        <input type="hidden" value="" name="changed_category" id="changed_category">
        <input type="hidden" value="0" name="all_visits_hidden">
        <label style="padding-right:19px;">From</label> : 
        <input size ="15" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($selected_date_to) ? $selected_date_to : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
        &nbsp To :&nbsp
        <input size="15" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($selected_date_from) ? $selected_date_from : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
        <span id="search_text_span"> Search :&nbsp
            <input size="27" type="text" id="search_text" value="<?php echo!empty($search_text) ? $search_text : ""; ?>" name="search_text">
        </span>
        <input type="submit" value="Filter" id="filter_submit" class="genericBtn">
        <input type="button" value="Reset" id="filter_reset" class="genericBtnreset">

    </div>
    <?php echo form_close(); ?>
</div>

 <div class='export_div'>

        <a href="<?= base_url() ?>form/exportiframe/<?php echo $app_id; ?>" title='Export Results in XLs Formate'>
            <img src="<?= base_url() . 'assets/images/export_data.png'; ?>" alt ="">
        </a>
    </div>
</div>

<div>
    <div id="container">
        <?php echo @$body_content; ?>
        
    </div>



    <style>
        #filter_span {
            margin: 0px 0px 0 204px;
        }
        #filter_span_district {
            margin: 0px 0 0 0px;
        }
        #overlay_loading img{
            margin: 20% 0 0 47%;
        }
        #cat_filter{
            width:130px;
            max-width: 130px;
        }
        #voilation{
            width:188px;
            max-width: 105px;
        }
        .Category a {
            padding: 0;
        }

        .ui-state-default{
            background: none;
        }
        .ui-dropdownchecklist{
            width: 200px;
        }
        .ui-dropdownchecklist-text {
            color: #000000;
            font-size: 14px !important;
        }
        .ui-dropdownchecklist-selector{
            padding:1px !important;
        }
        .element.style {
            cursor: default;
            display: inline-block;
            overflow: hidden;
        }
        #ddcl-cat_filter {
            width: 110px;
        }
        #form_lists {
            width: 95px;
            margin-top: 2px;
        }
        #ddcl-voilation {
            width: 110px;
        }
        .ui-dropdownchecklist {
            width: 30%;
        }
        .ui-dropdownchecklist-selector-wrapper, .ui-widget .ui-dropdownchecklist-selector-wrapper {
            margin-bottom: 2px;
        }
        .ms-parent li{
            color: #7C7B7B;
            font-size: 11px;
        }

        .ms-choice{
            width: 130px !important;
        }
        .crimeListTexta {
            color: #777777;
            display: inline;
            float: left;
            margin: 7px 2px -33px 0;
            position: relative;
        }
        #filter,#town_filter,#district_list,#sent_by_list,#form_list{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;

            color: #444444;
            cursor: pointer;
            height: 26px;
            line-height: 26px;
            overflow: hidden;
            padding: 0;
            text-align: left;
            margin-right: 0;
            text-decoration: none;
            white-space: nowrap;
            max-width: 141px;
            width: 135px;
        }
        .form_class div{
            position: relative;
            display: inline;
        }
        #datepicker,#datepicker2{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;
            color: #444444;
            cursor: pointer;
            height: 22px;
            line-height: 26px;
            overflow: hidden;
            padding-left:20px;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
        }
        #search_text{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;
            color: #444444;
            height: 22px;
            line-height: 26px;
            overflow: hidden;
            padding-left:10px;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
        }
        #search_text_span{
            margin-left: 38px;

        }
        .ui-widget-content {
            background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x scroll 50% 50% #ffffff !important;
            border: medium none !important;
            color: #222222 !important;
        }

        input.genericBtnreset {
            background: none repeat scroll 0 0 #2da5da;
            border: medium none;
            color: #fff;
            cursor: pointer;
            float: right;
            margin-left: 10px;
            margin-right: 3px;
            outline: medium none;
            padding: 5px 20px;
        }
        .export_div {
            right: 170px;
            position:static !important;
        }

        #filter_customized{
            background-color: #ffffff;
            border: 1px solid #0e76bd;
            color: #444444;
            cursor: pointer;
            height: 26px;
            line-height: 26px;
            margin-right: 0;
            max-width: 141px;
            overflow: hidden;
            padding: 0;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
            width: 135px;
        }
        table.display thead th div.DataTables_sort_wrapper span{
            display: none;
        }
    </style>


