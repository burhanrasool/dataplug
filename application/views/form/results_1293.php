
<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    ?>
</div>
<div class="applicationText" style="width: 100%">
    <div class="crimeListTexta">

        <form id='setfilter' method='POST' action='<?= base_url() ?>form/changefilter/<?php echo $form_id ?>'>
            <input name="redirect_to" value="mapview" type="hidden" />
            Change Category :
            <?php
            $flood_relief_options = array(
//                'Name' => 'Name',
//                'Father_Name' => 'Father Name',
                'Gender' => 'Gender',
//                'Phone_Number' => 'Phone Number',
                'Interview_Point' => 'Interview Point',
//                'district_name' => 'District Name',
                'Do_you_or_your_family_affected_by_flood' => 'Were you or your family affected by Flood ?',
                'Nature_of_financial_damage' => 'Nature of Financial Damage',
                'Do_you_get_any_aid' => 'Did you get any aid ?',
                'From_which_institution_you_ged_aid' => 'From which institution you got aid ?',
                'Facilities_Available' => 'Facilities Available',
                'What_You_Get_in_Cooked_Food' => 'What do you get in cooked food ?',
                'Government_performed_well_compared_to_past' => 'Did Government performed well ?',
            );

            if ($app_id == 1293) {
                ?>
                <select class="required"  name="filter_customized" id="filter_customized" style="width:188px" onChange="jQuery('#overlay_loading').show();
                        filter_update('<?php echo $app_id ?>', jQuery(this).val())"/>
                        <?php
                        if (isset($flood_relief_options)) {
                            foreach ($flood_relief_options as $key_param => $value) {
                                $selected = '';
                                if ($filter == $key_param) {
                                    $selected = "selected";
                                }
                                ?>
                        <option display_value="<?php echo $key_param; ?>" value="<?php echo $key_param; ?>" <?php echo $selected; ?> ><?php echo $value; ?></option>
                        <?php
                    }
                }
                ?>
                </select>
            <?php } else { ?>
                <select class="required" name="filter" id="filter" style="width:188px" onChange="jQuery('#overlay_loading').show();
                        filter_update('<?php echo $app_id ?>', jQuery(this).val())"/>
                </select>
            <?php } ?>
        </form>
    </div>
    <?php
    echo '<div class="filter_class" style="width: 100%">';
    $app_name = str_replace(' ', '-', $app_name);
    $slug = $app_name . '-' . $app_id;
    echo form_open(base_url() . 'application-results/' . $slug, 'id=date_filter_form name=date_filter_form');

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
        echo form_dropdown('form_list[]', $form_lists, $form_list_selected, 'id="form_list" class="form_list" multiple="multiple" ');
        echo '';
    } else {
        echo '<input type="hidden" value="' . $form_id . '" name="form_list[]">';
    }
    echo '</div>';
    if (!empty($town_filter)) {
        echo '&nbsp;&nbsp;Town : ';
        $town_filter_list = array();
        $town_filter_list = array_merge(array('' => 'Select All'), $town_filter);
        $town_filter_selected = (!empty($town_filter_selected)) ? $town_filter_selected : "";
        echo form_dropdown('town_filter', $town_filter_list, $town_filter_selected, 'id="town_filter"');
        echo ' ';
    }
    ?>
    <span id="filter_span">
        <?php
        if (!empty($filter_attribute)) {
            $this->load->helper(array('form'));
//            $filter_data = array();
//            foreach ($filter_attribute as $filter_attribute_value) {
//                if (in_array($filter_attribute_value, $headings_filter)) {
//                    foreach ($form_for_filter as $key => $form_item) {
//                        if (!empty($form_item[$filter_attribute_value])) {
//                            if (!in_array($form_item[$filter_attribute_value], $filter_data)) {
//                                $key = trim($form_item[$filter_attribute_value]);
//                                $key = explode(',', $key);
//                                $value = ($form_item[$filter_attribute_value]);
//                                $value = explode(',', $value);
//                                $filter_data = array_merge($filter_data, array($key[0] => $value[0]));
//                            }
//                        }
//                    }
//                }
//            }
             $selected_cat_filter = !empty($selected_cat_filter) ? $selected_cat_filter : "";
            echo '<label style="padding:0px;">Category</label>';
            echo ': ' . form_dropdown('cat_filter[]', $category_values, $selected_cat_filter, 'id="cat_filter" class="multiselect" multiple="multiple" style="height:26px;border:1px solid #0e76bd;" ');
            echo '';
        }
        ?>
    </span><br>
    <div style="margin:10px 0 0 0px;">

        <input type="hidden" value="<?php echo $form_id; ?>" name="form_id">
        <input type="hidden" value="" name="changed_category" id="changed_category">
        <input type="hidden" value="0" name="all_visits_hidden">
        <label style="padding-right:19px;">From</label> : 
        <input size ="15" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($selected_date_to) ? $selected_date_to : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
        &nbsp To :&nbsp
        <input size="15" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($selected_date_from) ? $selected_date_from : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
        <span id="search_text_span"> Search :&nbsp
            <input size="27" type="text" id="search_text" value="<?php echo!empty($search_text) ? $search_text : ""; ?>" name="search_text" placeholder='type your search here....'>
        </span>
        <input type="submit" value="Filter" id="filter_submit" class="genericBtn">
        <input type="button" value="Reset" id="filter_reset" class="genericBtnreset">

    </div>
    <?php echo form_close(); ?>
</div>
<?php
if ($this->acl->hasPermission('app', 'build')) {
    ?>
    <div class='export_div'>

        <a href="<?= base_url() ?>export-result/<?php echo $app_id ?>" title='Export Results in XLs Formate'>
            <img src="<?= base_url() . 'assets/images/export_data.png'; ?>" alt ="">
        </a>
    </div>
<?php } ?>
</div>


<div>
    <div id="container">
        <?php echo @$body_content; ?>
        <!--    Add comment block start -->
        <div style="width: 100%" class="applicationText">
            <div style="width: 100%" class="filter_class">
                <div style="margin:10px 0 0 0;">

                    &nbsp; Post Comment :&nbsp; &nbsp;
                    <textarea type="text"id="comment_text" value="" style='width: 99.4%'></textarea>
                    <div id='comment_saved' style='display: none;color: green;'>Your comment has been posted successfully...</div>
                    <div id='comment_error' style='display: none;color: red;'>Please enter text to post</div>
                    <input type="button" id="comments_adding" value="Add Comment">
                </div>
            </div>
        </div>
        <!--    Add comment block end -->
    </div>



    <style>
    
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
        .crimeListTexta{
            float: right;
            color: #777777;
        }
        #filter,#town_filter{
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
    </style>


