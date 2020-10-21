
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
            <select  name="district_list" id="district_list_1567" style="max-width: 127px" >
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
    <div style="margin:10px 0 0 0px;">
        <span id="filter_span_dc">
            <?php
            echo 'DC : ';
        echo form_dropdown('d_center', $d_center, $selected_dc, 'id="d_center" class="d_center"');
            ?>
            <!--<select  name="d_center[]" id="d_center" style="max-width: 127px" multiple="multiple">-->
                <?php
//                echo $selected_dc; die
//                foreach ($d_center as $dc) {
//                    if (isset($dc['Disbursement_Center']) && $dc['Disbursement_Center'] != '') {
//                        
//                        ?>
                        <!--<option <?php // echo $select; ?> value="<?php // echo strip_tags($dc['Disbursement_Center']); ?>"><?php // echo $dc['Disbursement_Center']; ?></option>-->
                        <?php
//                    }
//                }
                ?>
            <!--</select>-->
        </span>
        <input type="hidden" value="<?php echo $form_id; ?>" name="form_id">
        <input type="hidden" value="" name="changed_category" id="changed_category">
        <input type="hidden" value="0" name="all_visits_hidden">
        <label style="padding-right:0px;">From</label> : 
        <input size ="15" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($selected_date_to) ? $selected_date_to : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
         To :
        <input size="15" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($selected_date_from) ? $selected_date_from : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
        <span id="search_text_span"> Search :
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
        #filter,#town_filter,#district_list_1567,#d_center,#form_list{
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
            padding-left:0px;
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
            /*margin-left: 14px;*/

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


