<link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/app_settings.css'>
<div id="container">
<div class="inner-wrap">
<div class="table-sec">

<h2><?php //echo $department_name;            ?><?php echo $app_name; ?> Settings</h2>
<!--            <a href="<?= base_url() ?>users/userlisting" class="backBtn">Back</a>-->
<section id="tabbed">
    <!-- First tab input and label -->
    <?php
    if(isset($selected_tab)){
        $style_settings="style='display:none';";
    }else{
        $style_settings="";
    }
    ?>
    <input id="t-1" name="tabbed-tabs" type="radio" checked="checked">
    <label for="t-1" class="tabs shadow entypo-pencil" <?php echo $style_settings; ?>>General Settings</label>
    <!-- Second tab input and label -->
    <input id="t-2" name="tabbed-tabs" type="radio">
    <label for="t-2" class="tabs shadow entypo-paper-plane" <?php echo $style_settings; ?>>Form Settings</label>
    <!-- Third tab input and label -->
    <input id="t-3" name="tabbed-tabs" type="radio">
    <label for="t-3" class="tabs tabs3 shadow entypo-menu" <?php echo $style_settings; ?>>List View Settings </label>
    <!-- Fourth tab input and label -->
    <input id="t-4" name="tabbed-tabs" type="radio">
    <label for="t-4" class="tabs tabs4 shadow entypo-menu" <?php echo $style_settings; ?>>Map View Settings </label>
    <!-- Fifth tab input and label -->
    <input id="t-5" name="tabbed-tabs" type="radio">
    <label for="t-5" class="tabs tabs5 shadow entypo-menu" <?php echo $style_settings; ?>>Graph View Settings </label>
    <!-- Six tab input and label -->
    <input id="t-6" name="tabbed-tabs" type="radio">
    <label for="t-6" class="tabs tabs6 shadow entypo-menu" <?php echo $style_settings; ?>>Send SMS </label>


    <!-- Tabs wrapper -->
    <div class="wrapper ">


        <!-- Tab 1 content -->
        <div class="tab-1 mytab" <?php echo $style_settings; ?>>
            <?php
            $attributes = array('id' => 'general_settings');
            if(isset($general_settings_filter)){
                $filter_arr=json_decode($general_settings_filter);
                $secured_apk='';
                $only_authorized='';
                $app_language='';
                $record_stop_sending='';
                $screen_view='';
                $upgrade_from_google_play='';
                $message_stop_sending_record='';
                $form_submission_web_link=0;
                $activity_status_change=0;
                $high_resolution_image=0;
                $persist_images_on_device=0;
                $background_update=1;
                $force_update=1;
                $enable_auto_time=1;
                $debug_geo_fencing=0;
                $location_required = 1;
                $has_geo_fencing=0;
                $debug_tracking=0;
                $tracking_status=0;
                $direct_save=0;
                $tracking_interval=5;
                $tracking_distance=100;
                $minimum_version_to_accept='';
                if(isset($filter_arr->setting_type) && $filter_arr->setting_type=='general_settings') {
                    $direct_save = isset($filter_arr->direct_save)?$filter_arr->direct_save:'0';
                    $location_required = isset($filter_arr->location_required)?$filter_arr->location_required:'1';
                    $debug_geo_fencing = isset($filter_arr->debug_geo_fencing)?$filter_arr->debug_geo_fencing:'0';
                    $has_geo_fencing = isset($filter_arr->has_geo_fencing)?$filter_arr->has_geo_fencing:'0';
                    $debug_tracking = isset($filter_arr->debug_tracking)?$filter_arr->debug_tracking:'0';
                    $tracking_status = isset($filter_arr->tracking_status)?$filter_arr->tracking_status:'0';
                    $tracking_interval = isset($filter_arr->tracking_interval)?$filter_arr->tracking_interval:'5';
                    $tracking_distance = isset($filter_arr->tracking_distance)?$filter_arr->tracking_distance:'100';
                    $background_update = isset($filter_arr->background_update)?$filter_arr->background_update:'1';
                    $force_update = isset($filter_arr->force_update)?$filter_arr->force_update:'1';
                    $enable_auto_time = isset($filter_arr->enable_auto_time)?$filter_arr->enable_auto_time:'1';
                    $high_resolution_image = isset($filter_arr->high_resolution_image)?$filter_arr->high_resolution_image:'0';
                    $persist_images_on_device = isset($filter_arr->persist_images_on_device)?$filter_arr->persist_images_on_device:'0';
                    $form_submission_web_link = isset($filter_arr->form_submission_web_link)?$filter_arr->form_submission_web_link:'0';
                    $activity_status_change = isset($filter_arr->activity_status_change)?$filter_arr->activity_status_change:'0';
                    $secured_apk = $filter_arr->secured_apk;
                    $only_authorized = $filter_arr->only_authorized;
                    $app_language = $filter_arr->app_language;
                    $record_stop_sending = $filter_arr->record_stop_sending;
                    $minimum_version_to_accept = isset($filter_arr->minimum_version_to_accept)?$filter_arr->minimum_version_to_accept:'';
                    $screen_view = $filter_arr->screen_view;
                    $upgrade_from_google_play = $filter_arr->upgrade_from_google_play;
                    $message_stop_sending_record = $filter_arr->message_stop_sending_record;
                }
            }
            ?>
            <?php echo form_open("",$attributes); ?>
            <?php echo form_hidden('setting_type', 'general_settings'); ?>

            <div class="row">
                <label for="d1_textfield">
                    Location Required when submit record
                </label>
                <div>
                    <?php echo form_dropdown('location_required',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$location_required", 'id="location_required"'); ?>
                </div>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    High Resolution Image
                </label>
                <div>
                    <?php echo form_dropdown('high_resolution_image',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$high_resolution_image", 'id="high_resolution_image"'); ?>
                </div>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Deleting image after submit activity
                </label>
                <div>
                    <?php echo form_dropdown('persist_images_on_device',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$persist_images_on_device", 'id="persist_images_on_device"'); ?>
                </div>
            </div>
            <br />
            <br />
            
            <div class="row">
                <label for="d1_textfield">
                    Secured APK Build
                </label>
                <div>
                    <?php echo form_dropdown('secured_apk',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$secured_apk", 'id="secured_apk"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Only authorized user can send record using this application
                </label>
                <div>
                    <?php echo form_dropdown('only_authorized',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$only_authorized", 'id="only_authorized"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />


            <div class="row">
                <label for="d1_textfield">
                    Direct save to production.
                </label>
                <div>
                    <?php echo form_dropdown('direct_save',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$direct_save", 'id="direct_save"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    App Language
                </label>
                <div>
                    <?php echo form_dropdown('app_language', array('english' => 'English', 'urdu' => 'Urdu'), "$app_language", 'id="app_language"'); ?>
<!--                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="" />-->
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Records sending stops temporary
                </label>
                <div>
                    <?php echo form_dropdown('record_stop_sending',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$record_stop_sending", 'id="record_stop_sending"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Minimum version to accept activities
                </label>
                <div>
                    <?php echo form_dropdown('minimum_version_to_accept',$app_r,
                        "$minimum_version_to_accept", 'id="minimum_version_to_accept"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Message in case of stop sending record
                </label>
                <div>
                    <input class="textBoxLogin" type="text" name="message_stop_sending_record" id="last_name" value="<?php echo $message_stop_sending_record; ?>" />

                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Default screen view
                </label>
                <div>
                    <?php echo form_dropdown('screen_view',
                        array('1' => 'Development Mode', '2' => 'Sony Experia','3'=>'HTC One','4'=>'Samsung'),
                        "$screen_view", 'id="upgrade_from_google_play"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Background Update On save server
                </label>
                <div>
                    <div>
                        <?php echo form_dropdown('background_update',
                            array('1' => 'ON', '0' => 'OFF'),
                            "$background_update", 'id="background_update"'); ?>
                    </div>
<!--                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="" />-->
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />
            
            <div class="row">
                <label for="d1_textfield">
                    Force Update
                </label>
                <div>
                    <div>
                        <?php echo form_dropdown('force_update',
                            array('1' => 'ON', '0' => 'OFF'),
                            "$force_update", 'id="force_update"'); ?>
                    </div>
<!--                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="" />-->
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Enable Auto Time
                </label>
                <div>
                    <div>
                        <?php echo form_dropdown('enable_auto_time',
                            array('1' => 'ON', '0' => 'OFF'),
                            "$enable_auto_time", 'id="enable_auto_time"'); ?>
                    </div>
<!--                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="" />-->
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Upgrade from google play store
                </label>
                <div>
                    <div>
                        <?php echo form_dropdown('upgrade_from_google_play',
                            array('0' => 'OFF', '1' => 'ON'),
                            "$upgrade_from_google_play", 'id="upgrade_from_google_play"'); ?>
                    </div>
<!--                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="" />-->
                </div>
<!--                --><?php //echo $this->form_validation->error('last_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Activity Status change (Pending/Approved/Rejected)
                </label>
                <div>
                    <?php echo form_dropdown('activity_status_change',
                        array('0' => 'OFF', '1' => 'ON'),
                        "activity_status_change", 'id="activity_status_change"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Web Link for submission Activities
                </label>
                <div>
                    <?php echo form_dropdown('form_submission_web_link',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$form_submission_web_link", 'id="form_submission_web_link"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            <div id="urldiv" class="row" style="margin-top: 15px; background-color: yellow;<?php if($form_submission_web_link=='0'){echo 'display:none;';}?>" onclick="select_all(this)"><?php echo base_url().'web/index/'.$app_id;?></div>
            <fieldset>
            <legend>Tracking</legend>
            <div class="row">
                <label for="d1_textfield">
                    Tracking Status
                </label>
                <div>
                    <?php echo form_dropdown('tracking_status',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$tracking_status", 'id="tracking_status"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
                
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Tracking Interval (Seconds)
                </label>
                <div>
                    <input class="textBoxLogin" type="text" name="tracking_interval" id="tracking_interval" value="<?php echo $tracking_interval; ?>" />
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Tracking Distance (Meters)
                </label>
                <div>
                    <input class="textBoxLogin" type="text" name="tracking_distance" id="tracking_distance" value="<?php echo $tracking_distance; ?>" />
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Debug Mode of Tracking
                </label>
                <div>
                    <?php echo form_dropdown('debug_tracking',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$debug_tracking", 'id="debug_tracking"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            
            <div class="row" style="display: none;">
                <label for="d1_textfield">
                    GEO Fencing Status
                </label>
                <div>
                    <?php echo form_dropdown('has_geo_fencing',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$has_geo_fencing", 'id="has_geo_fencing"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            <div class="row" style="display: none;">
                <label for="d1_textfield">
                    GEO Fencing Debug Mode
                </label>
                <div>
                    <?php echo form_dropdown('debug_geo_fencing',
                        array('0' => 'OFF', '1' => 'ON'),
                        "$debug_geo_fencing", 'id="debug_geo_fencing"'); ?>
                </div>
<!--                --><?php //echo $this->form_validation->error('first_name'); ?>
            </div>
            <br />
            <br />
            </fieldset>
            


            <div class="actions">
                <div class="right">
                    <input type="button" class="genericBtn general_settings filter_button" id="general_settings" value="Update"><br><br>
                    <span class="general_settings_msg" style="width:530px; color: green; text-align: right;float: right;">

                    </span>
<!--                    <button class="genericBtn general_settings" >Update</button>-->
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>
        <!--  General settings tab ends here  -->


        <div class="tab-2 mytab" id="tab2_form_setting">
        
        </div>
        <!--   Form settings tab ends here     -->

        <div class="tab-3 mytab">
            <?php
            $attributes = array('id' => 'result_view_settings');
            if(isset($result_view_settings_filter)){
                $filter_arr=json_decode($result_view_settings_filter);
                $district_filter='';
                $uc_filter='';
                $sent_by_filter='';
                $result_view_form='';
                $result_view_filters=array();
                $result_view_form_id='';

                if((isset($filter_arr->setting_type)) && $filter_arr->setting_type=='result_view_settings') {
                    $district_filter = (isset($filter_arr->district_filter))?$filter_arr->district_filter:"";
                    $uc_filter = (isset($filter_arr->uc_filter))?$filter_arr->uc_filter:"";
                    $sent_by_filter = (isset($filter_arr->sent_by_filter))?$filter_arr->sent_by_filter:"";
                    $result_view_form = (isset($filter_arr->form))?$filter_arr->form:"";
                    $result_view_filters = (array)$filter_arr->filters;

                }
            }
//            echo "<pre>";
//            print_r($result_view_filters);die;
            ?>
            <?php echo form_open("", $attributes); ?>
            <?php echo form_hidden('setting_type', 'result_view_settings'); ?>

<!--            <div class="row">-->
<!--                <label for="d1_textfield">-->
<!--                    District Filter-->
<!--                </label>-->
<!--                <div>-->
<!--                    --><?php //echo form_dropdown('district_filter',
//                        array('0' => 'No', '1' => 'Yes'),
//                        "$district_filter", 'id="district_filter"'); ?>
<!--                </div>-->
<!--<!--                --><?php ////echo $this->form_validation->error('new_password'); ?>
<!--            </div>-->
<!--            <br />-->
<!--            <br />-->
<!---->
<!--            <div class="row">-->
<!--                <label for="d1_textfield">-->
<!--                    Uc Filter-->
<!--                </label>-->
<!--                <div>-->
<!--                    --><?php //echo form_dropdown('uc_filter',
//                        array('0' => 'No', '1' => 'Yes'),
//                        "$uc_filter", 'id="uc_filter"'); ?>
<!--                </div>-->
<!--            </div>-->
<!--            <br />-->
<!--            <br />-->
<!---->
<!--            <div class="row">-->
<!--                <label for="d1_textfield">-->
<!--                    Sent By Filter-->
<!--                </label>-->
<!--                <div>-->
<!--                    --><?php //echo form_dropdown('sent_by_filter',
//                        array('0' => 'No', '1' => 'Yes'),
//                        "$sent_by_filter", 'id="sent_by_filter"'); ?>
<!--                </div>-->
<!--                <!--                --><?php ////echo $this->form_validation->error('conf_new_password'); ?>
<!--            </div>-->
<!--            <br />-->
<!--            <br />-->

            <div class="row">
                <label for="d1_textfield">
                    Form


                </label>
                <div>
                    <select name="form" onchange="get_form_columns(value,$('#result_view_filter_value').val(),$('#result_view_form_value').val(),'result_view_settings')">
                        <option value="">Select One</option>
                        <?php
                        $all_form_arr=array();
                        $i=1;
                        foreach($final_forms as $key=>$forms){
                        if($i==1){

                            if($result_view_form==''){
                                $result_view_form_id=$forms['id'];
                            }
                        }else {
                                $result_view_form_id = $result_view_form;
                        }
                        $all_form_arr[$key]=$key;
                            ?>
                            <option <?php if($forms['id']==$result_view_form){ echo "selected"; } ?> value="<?php echo $forms['id']; ?>"  "><?php echo $key; ?></option>
                        <?php
                            $i++;
                        }
                        ?>

                    </select>
                </div>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Filters
                </label>
                <div>
                    <select name="filters[]" id="result_view_form" multiple="multiple" class="multiselect"  style="width: 150px;">
                        <?php
//                        $form_filter=$filters_array[$form_id];
//                        foreach($first_form_columns as $key=>$filter){
//                        echo $result_view_form_id;die;
//                        echo "<pre>";
//                        print_r($possible_and_defaults);
                        if($result_view_form_id!=''){
                        $possible_and_defaults_filters=explode(",",$possible_and_defaults[$result_view_form_id]['possible_filter_selected']);
////                        echo "<pre>";
//                        print_r($result_view_filters);die;
                        foreach($possible_and_defaults_filters as $key=>$filter){

                            ?>
                            <option <?php if(in_array($filter,$result_view_filters)){ echo "selected"; } ?> value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br />
            <br />


            <div class="actions">
                <div class="right">
                    <input type="button" class="genericBtn general_settings filter_button" id="result_view_settings" value="Update"><br><br>
                    <span class="result_view_settings_msg" style="width:530px; color: green; text-align: right;float: right;">
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>


        <div class="tab-4 mytab">
            <?php
            $attributes = array('id' => 'map_view_settings');
            if(isset($map_view_settings_filter)){
                $filter_arr=json_decode($map_view_settings_filter);
                $default_latitude='';
                $default_longitude='';
                $default_zoom_level='';
                $map_type_filter='';
                $district_filter='';
                $district_wise_filter='';
                $uc_filter='';
                $sent_by_filter='';
                $map_view_form='';
                $map_view_filters=array();
                $map_view_form_id='';
                $map_distance_mapping='';
                $distance='';
                $matching_field='';
//                echo "<pre>";
//                print_r($filter_arr);die;
                if((isset($filter_arr->setting_type)) && $filter_arr->setting_type=='map_view_settings') {
                    $default_latitude = (isset($filter_arr->default_latitude))?$filter_arr->default_latitude:"";
                    $default_longitude = (isset($filter_arr->default_longitude))?$filter_arr->default_longitude:"";
                    $default_zoom_level = (isset($filter_arr->default_zoom_level))?$filter_arr->default_zoom_level:"";
                    $map_type_filter = (isset($filter_arr->map_type_filter))?$filter_arr->map_type_filter:"";
                    $district_filter = (isset($filter_arr->district_filter))?$filter_arr->district_filter:'';
                    $district_wise_filter = (isset($filter_arr->district_wise_filter))?$filter_arr->district_wise_filter:"";
                    $uc_filter = (isset($filter_arr->uc_filter))?$filter_arr->uc_filter:"";
                    $sent_by_filter = (isset($filter_arr->sent_by_filter))?$filter_arr->uc_filter:"";
                    $map_view_form = (isset($filter_arr->form))?$filter_arr->form:"";
                    $map_view_filters = $filter_arr->filters;
                    $map_distance_mapping = isset($filter_arr->map_distance_mapping)?$filter_arr->map_distance_mapping:'';
                    $distance = isset($filter_arr->distance)?$filter_arr->distance:'';
                    $matching_field = isset($filter_arr->matching_field)?$filter_arr->matching_field:'';
                }
            }

            $j=1;
            ?>
            <?php echo form_open("", $attributes); ?>
            <?php echo form_hidden('setting_type', 'map_view_settings'); ?>
            <section id="tabbed-inner-map">

            <input id="map-inner-t-1" name="tabbed-tabs" type="radio"
                   <?php if ($j == 1){ ?>checked="checked" <?php } ?> >
            <label for="map-inner-t-1"
                   class="tabs shadow entypo-pencil">Common Settings</label>

            <input id="map-inner-t-2" name="tabbed-tabs" type="radio">
            <label for="map-inner-t-2"
                   class="tabs shadow entypo-pencil">Pin Settings
            </label>

            <!-- Tabs wrapper -->
            <div class="map-wrapper-inner">
                <!-- Tab 1 content -->

                <div class="map-inner-tab-1 mytab">
                    <div class="row">
                        <label for="d1_textfield">
                            Default Latitude
                        </label>
                        <div>
                            <input class="textBoxLogin" type="text" name="default_latitude" id="d1_textfield" value="<?php echo $default_latitude; ?>" />
                        </div>
                        <!--                --><?php //echo $this->form_validation->error('new_password'); ?>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Default Longtitude
                        </label>
                        <div>
                            <input class="textBoxLogin" type="text" name="default_longitude" id="d1_textfield" value="<?php echo $default_longitude; ?>" />
                        </div>
                        <!--                --><?php //echo $this->form_validation->error('new_password'); ?>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Default Zoom Level
                        </label>
                        <div>
                            <input class="textBoxLogin" type="text" name="default_zoom_level" id="d1_textfield" value="<?php echo $default_zoom_level; ?>" />
                        </div>
                        <!--                --><?php //echo $this->form_validation->error('new_password'); ?>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Map Type Filter
                        </label>
                        <div>
                            <?php echo form_dropdown('map_type_filter',
                                array('0' => 'No', '1' => 'Yes'),
                                "$map_type_filter", 'id="map_type_filter"'); ?>
                        </div>
                        <!--                --><?php //echo $this->form_validation->error('new_password'); ?>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            District Filter
                        </label>
                        <div>
                            <?php echo form_dropdown('district_filter',
                                array('0' => 'No', '1' => 'Yes'),
                                "$district_filter", 'id="district_filter"'); ?>
                        </div>
                        <!--                <?php //echo $this->form_validation->error('new_password'); ?>-->
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Uc Filter
                        </label>
                        <div>
                            <?php echo form_dropdown('uc_filter',
                                array('0' => 'No', '1' => 'Yes'),
                                "$uc_filter", 'id="uc_filter"'); ?>
                        </div>
                        <!--                <?php //echo $this->form_validation->error('conf_new_password'); ?>-->
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Sent By Filter
                        </label>
                        <div>
                            <?php echo form_dropdown('sent_by_filter',
                                array('0' => 'No', '1' => 'Yes'),
                                "$sent_by_filter", 'id="sent_by_filter"'); ?>
                        </div>
                        <!--                <?php //echo $this->form_validation->error('conf_new_password'); ?>-->
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Form
                        </label>
                        <div>
                            <select name="form" onchange="get_form_columns(value,$('#map_view_filter_value').val(),$('#map_view_form_value').val(),'map_view_settings')">
                                <option value="">Select One</option>
                                <?php

                                $all_form_arr=array();
                                $j=1;
                                foreach($final_forms as $key=>$forms){
                                    if($j==1){
                                        $first_form_id=$forms['id'];
                                        if($map_view_form==''){
//                                    $map_view_form_id=$first_form_id;
                                            $map_view_form_id=$forms['id'];

                                        }
                                    }
                                    else {
                                            $map_view_form_id = $map_view_form;
                                    }


                                    $all_form_arr[$key]=$key;
                                    ?>
                                    <option <?php if(isset($map_view_form) && $forms['id']==$map_view_form){ echo "selected"; } ?> value="<?php echo $forms['id']; ?>"  "><?php echo $key; ?></option>
                        <?php
                            $j++;
                        }
                                ?>

                            </select>
                        </div>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            Filters
                        </label>
                        <div>
                            <select name="filters[]" id="map_view_form" multiple="multiple" class="multiselect" style="width: 150px;">
                                <?php
                                //                        $form_filter=$filters_array[$form_id];

                                if($map_view_form_id!='') {
                                    $possible_and_defaults_filters = explode(",", $possible_and_defaults[$map_view_form_id]['possible_filter_selected']);
//                        foreach($first_form_columns as $key=>$filter){
                                    foreach ($possible_and_defaults_filters as $key => $filter) {

                                        ?>
                                        <option <?php if (in_array($filter, (array)$map_view_filters)) {
                                            echo "selected";
                                        } ?> value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                    <?php
                                    }
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                            District Wise Report
                        </label>
                        <div>
                            <?php echo form_dropdown('district_wise_filter',
                                array('0' => 'No', '1' => 'Yes'),
                                "$district_filter", 'id="district_wise_filter"'); ?>
                        </div>
                        <!--                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
                    </div>
                    <br />
                    <br />

                    <div class="row">
                        <label for="d1_textfield">
                        Map Distance? :
                        </label>
                        <div>
                            <input type="checkbox" name="map_distance_mapping" value="1" <?php if($map_distance_mapping==1){ echo "checked"; }  ?> >
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <label for="d1_textfield">
                        Distance :
                        </label>
                        <div>
                            <input class="textBoxLogin" type="text" name="distance" value="<?php echo $distance; ?>" placeholder=" Enter distance in meters...">
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <label for="d1_textfield">
                        Matching Field:
                        </label>
                        <div>

                            <input class="textBoxLogin" type="text" name="matching_field" value="<?php echo $matching_field; ?>" placeholder=" Enter field names with comma separate">

                        </div>
                    </div>



                    <div class="actions">
                        <div class="right">
                            <input type="button" class="genericBtn general_settings filter_button" id="map_view_settings" value="Update"><br><br>
                    <span class="map_view_settings_msg" style="width:530px; color: green; text-align: right;float: right;">
                        </div>

                    </div>


                </div>




            </div>
            <?php echo form_close(); ?>
            <div class="map-wrapper-inner">
                <!-- Tab 1 content -->

                <div class="map-inner-tab-2 mytab">
                    <?php
                    $attributes = array('id' => 'map_pin_view_settings');
                    ?>
                    <?php echo form_open("", $attributes); ?>
                    <div class="row">
                        <label for="d1_textfield">
                            Form
                        </label>
                        <div>
                            <select name="form" id="map_pin_form_id" onchange="get_form_columns(value,$('#map_view_filter_value').val(),$('#map_view_form_value').val(),'map_pin_settings')">
                                <option value="">Select One</option>
                                <?php

                                $all_form_arr=array();
                                $j=1;
                                foreach($final_forms as $key=>$forms){
                                    if($j==1){
                                        $first_form_id=$forms['id'];
                                        if($map_view_form==''){
//                                    $map_view_form_id=$first_form_id;
                                            $map_view_form_id=$forms['id'];

                                        }
                                    }
                                    else {
                                        $map_view_form_id = $map_view_form;
                                    }


                                    $all_form_arr[$key]=$key;
                                    ?>
                                    <option <?php /*if(isset($map_view_form) && $forms['id']==$map_view_form){ echo "selected"; }*/ ?> value="<?php echo $forms['id']; ?>"  "><?php echo $key; ?></option>
                        <?php
                            $j++;
                        }
                                ?>

                            </select>
                        </div>
                    </div>



                    <div class="row">
                        <label for="d1_textfield">
                            Filters
                        </label>
                        <div>
                            <select name="filters" id="map_pin_form"  style="width: 150px;" onchange="get_pins_n_table(value)">

                            </select>
                        </div>
                    </div>



<!--                    Fields:-->
<!--                    <select onchange="get_pins_n_table(value)">-->
<!--                        --><?php
////                        echo "<pre>";
////                        print_r($required_fields);die;
//                        foreach($required_fields as $key=>$val) {
//                            $keys = array_keys($val);
//                            ?>
<!--                                <option value="">Select One</option>-->
<!--                            --><?php
//                            foreach ($keys as $key1=>$val1) {
//                                ?>
<!--<!--                                <option value="--><?php ////echo $key.'-'.$val1; ?><!--<!--">--><?php ////echo $val1." ($key)"; ?><!--<!--</option>-->
<!--                                <option value="--><?php //echo $key.'-'.$val1; ?><!--">--><?php //echo $val1; ?><!--</option>-->
<!--                            --><?php
//                            }
//                        }
//                        ?>
<!--                    </select>-->
<!--                    <br><br>-->
<!--                    <div class="pins_result_msg" style="display: none;"><h2>Choose pins</h2></div>-->

                        <div class="pins_result">

                        </div>



                        <div class="pins_result_new">

                        </div>

                        <div class="actions" style="display: none;">
                            <div class="right">
                                <input type="button" class="genericBtn general_settings map_pin_button" id="map_pin_view_settings" value="Update"><br><br>
                                    <span class="map_pin_msg" style="width:530px; color: green; text-align: right;float: right;">
                            </div>

                        </div>

                        <div id="pins_table">

                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            </section>



        </div>


        <div class="tab-5 mytab">
            <?php
            $attributes = array('id' => 'graph_view_settings');
            if(isset($graph_view_settings_filter)){
                $filter_arr=json_decode($graph_view_settings_filter);
                $district_filter='';
                $uc_filter='';
                $sent_by_filter='';
                $form='';
                $district_wise_report='';
                $pie_chart_of_selected_filters='';
                $bar_graph_filters='';
                $graph_view_form='';
                $graph_view_filters=array();
                $graph_view_form_id='';

                if((isset($filter_arr->setting_type)) && $filter_arr->setting_type=='graph_view_settings') {
                    $district_filter = (isset($filter_arr->district_filter))?$filter_arr->district_filter:"";
                    $uc_filter = (isset($filter_arr->uc_filter))?$filter_arr->uc_filter:"";
                    $sent_by_filter = (isset($filter_arr->sent_by_filter))?$filter_arr->sent_by_filter:"";
                    $form = (isset($filter_arr->form))?$filter_arr->form:"";
                    $district_wise_report = (isset($filter_arr->district_wise_report))?$filter_arr->district_wise_report:"";
                    $pie_chart_of_selected_filters = (isset($filter_arr->pie_chart_of_selected_filters))?$filter_arr->pie_chart_of_selected_filters:"";
                    $bar_graph_filters = (isset($filter_arr->bar_graph_filters))?$filter_arr->bar_graph_filters:"";
                    $graph_view_form = (isset($filter_arr->form))?$filter_arr->form:"";
                    $graph_view_filters = (isset($filter_arr->filters))?$filter_arr->filters:"";
                }
            }
            ?>
            <?php echo form_open("", $attributes); ?>
            <?php echo form_hidden('setting_type', 'graph_view_settings'); ?>

            <div class="row">
                <label for="d1_textfield">
                    District Filter
                </label>
                <div>
                    <?php echo form_dropdown('district_filter',
                        array('0' => 'No', '1' => 'Yes'),
                        "$district_filter", 'id="district_filter"'); ?>
                </div>
                             <?php //echo $this->form_validation->error('new_password'); ?>
            </div>
            <br />
            <br />
<!---->
            <div class="row">
                <label for="d1_textfield">
                    Uc Filter
                </label>
                <div>
                    <?php echo form_dropdown('uc_filter',
                        array('0' => 'No', '1' => 'Yes'),
                        "$uc_filter", 'id="uc_filter"'); ?>
                </div>
                                <?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />
<!---->
            <div class="row">
                <label for="d1_textfield">
                    Sent By Filter
                </label>
                <div>
                    <?php echo form_dropdown('sent_by_filter',
                        array('0' => 'No', '1' => 'Yes'),
                        "$sent_by_filter", 'id="sent_by_filter"'); ?>
                </div>
                                <?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Form
                </label>
                <div>
                    <select name="form" onchange="get_form_columns(value,$('#graph_view_filter_value').val(),$('#graph_view_form_value').val(),'graph_view_settings')">
                        <option value="">Select One</option>
                        <?php
                        $all_form_arr=array();
                        $j=1;
                        foreach($final_forms as $key=>$forms){
                            if($j==1){
                                $first_form_id=$forms['id'];
                                if($graph_view_form_id==''){
//                                    $graph_view_form_id=$first_form_id;
                                    $graph_view_form_id=$forms['id'];
                                }
                            }else {
                                $graph_view_form_id = $graph_view_form;
                            }

                            $all_form_arr[$key]=$key;
                            ?>
                            <option <?php if(isset($graph_view_form_id) && $forms['id']==$graph_view_form_id){ echo "selected"; } ?> value="<?php echo $forms['id']; ?>"  "><?php echo $key; ?></option>
                        <?php
                            $j++;
                        }
                        ?>

                    </select>
                </div>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Filters
                </label>
                <div>
                    <select name="filters[]" id="graph_view_form" multiple="multiple" class="multiselect"  style="width: 150px;">
                        <?php
                        //                        $form_filter=$filters_array[$form_id];

                        if($graph_view_form_id!='') {
                            $possible_and_defaults_filters = explode(",", $possible_and_defaults[$graph_view_form_id]['possible_filter_selected']);
//                        foreach($first_form_columns as $key=>$filter){
                            foreach ($possible_and_defaults_filters as $key => $filter) {
                                if ($filter != 'sent_by') {
                                    ?>
                                    <option <?php if (in_array($filter, (array)$graph_view_filters)) {
                                        echo "selected";
                                    } ?> value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                <?php
                                }
                            }

                        }

                        ?>
                    </select>
                </div>
            </div>
            <br />
            <br />
            <div class="row">
                <label for="d1_textfield">
                    Bar Graph
                </label>
                <div>
                    <?php echo form_dropdown('bar_graph_filters',
                        array('0' => 'No', '1' => 'Yes'),
                        "$bar_graph_filters", 'id="bar_graph_filters"'); ?>
                </div>
                <!--                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    District Wise Report
                </label>
                <div>
                    <?php echo form_dropdown('district_wise_report',
                        array('0' => 'No', '1' => 'Yes'),
                        "$district_wise_report", 'id="district_wise_report"'); ?>
                </div>
                <!--                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Pie Chart Of Selected Filters
                </label>
                <div>
                    <?php echo form_dropdown('pie_chart_of_selected_filters',
                        array('0' => 'No', '1' => 'Yes'),
                        "$pie_chart_of_selected_filters", 'id="pie_chart_of_selected_filters"'); ?>
                </div>
                <!--                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />


            <div class="actions">
                <div class="right">
                    <input type="button" class="genericBtn general_settings filter_button" id="graph_view_settings" value="Update"><br><br>
                    <span class="graph_view_settings_msg" style="width:530px; color: green; text-align: right;float: right;">
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>


        <div class="tab-6 mytab">
            <?php
            $selected_users=array();
            $attributes = array('id' => 'sms_settings');
            if(isset($sms_settings_filter)){
                $filter_arr=json_decode($sms_settings_filter);
                
                $message='';
                if((isset($filter_arr->setting_type)) && $filter_arr->setting_type=='sms_settings') {
                    if((isset($filter_arr->users) && $filter_arr->users!='')){
                        $selected_users = $filter_arr->users;
                    }
                    
                    $message = $filter_arr->message;


                }
            }
            ?>
            <?php echo form_open("", $attributes); ?>
            <?php echo form_hidden('setting_type', 'sms_settings'); ?>

            <div class="row">
                <label for="d1_textfield">
                   Send To
                </label>
                <div>
                    <select name="users[]" id="app_settings_users" multiple="multiple" class="multiselect"   style="width: 150px;">
                        <?php
                        foreach($all_users as $key=>$users){
//                            echo "<pre>";
//                            print_r($selected_users);die;
                            ?>
                            <option <?php if(in_array($users['mobile_number']."_".$users['user_name'],$selected_users)){ echo "selected"; } ?> value="<?php echo $users['mobile_number']."_".$users['user_name']; ?>"><?php echo $users['user_name']; ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </div>
            </div>
            <br />
            <br />

            <div class="row">
                <label for="d1_textfield">
                    Text to send
                </label>
                <div>
                    <?php echo form_textarea('message', $message, 'class="general"'); ?>
                </div>
                <!--                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
            </div>
            <br />
            <br />

            <div class="actions">
                <div class="right">
                    <input type="button" class="genericBtn sms_settings filter_button" id="sms_settings" value="Send SMS"><br><br>
                    <span class="sms_settings_msg" style="width:530px; color: green; text-align: right;float: right;">
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>





    </div>

</section>

</div>
</div>
</div>
<?php
$u_agent = $_SERVER['HTTP_USER_AGENT'];

// Next get the name of the useragent yes seperately and for good reason
if (preg_match('/Firefox/i', $u_agent)) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
} elseif (preg_match('/Chrome/i', $u_agent)) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
}
if ($ub == 'Chrome') {
    ?>
    <style>
        .genericBtn {
            margin-left: 244px !important;
        }
        .row label{
            width: 149px !important;
        }
    </style>
<?php
}

//echo"<pre>";
//print_r(json_encode($result_view_filters));die;


?>
<input type="hidden" value='<?php echo json_encode($result_view_filters); ?>' id="result_view_filter_value">
<input type="hidden" value='<?php echo $result_view_form; ?>' id="result_view_form_value">

<input type="hidden" value='<?php echo json_encode($map_view_filters); ?>' id="map_view_filter_value">
<input type="hidden" value='<?php echo $map_view_form; ?>' id="map_view_form_value">

<input type="hidden" value='<?php echo json_encode($graph_view_filters); ?>' id="graph_view_filter_value">
<input type="hidden" value='<?php echo $graph_view_form; ?>' id="graph_view_form_value">
<script type="text/javascript">
    function select_all(el) {
        if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.selection != "undefined" && typeof document.body.createTextRange != "undefined") {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.select();
        }
    }
</script>
<script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.multiple.select.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/multiple-select.css"/>
<script>

$('#t-2').live('click',function(){
//$('#overlay_loading').show();
    var app_id = "<?php echo $app_id;?>";
    console.log('call ajax start');


$.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>app/newformsettings/"+app_id,
            data: "app_id=" + app_id,
            success: function (page_load) {
                //$('#tab2_form_setting').html('zahid');
                $('#tab2_form_setting').html(page_load);
                    console.log('call ajax success');
            },
            error: function (result)
            {
                $("#loaded_samples_ajax").html("Error");
            },
            fail: (function (status) {
                $("#loaded_samples_ajax").html("Fail");
            }),
            beforeSend: function (d) {
                $('#loaded_samples_ajax').html("<center><strong style='color:red'>Please Wait...<br><img height='25' width='120' src='<?php echo base_url(); ?>assets/images/loading.gif' /></strong></center>");
            }

        });

    console.log('call ajax end');
    
});


    function get_form_columns(id,filters,form_view_saved_form_id,form_type){

        $.ajax( {
            type: "POST",
            url: "<?php echo base_url();?>app/get_form_columns",
            data: "form_id="+id+"&filters="+filters+"&form_view_saved_form_id="+form_view_saved_form_id,
            success: function( response ) {
                if(form_type=="result_view_settings") {
                    jQuery("#result_view_form").empty();
                    jQuery.each(response.category, function (option, value) {
                        var opt = jQuery('<option />');
                        opt.val(option);
                        opt.text(value);
                        jQuery('#result_view_form option[value="' + response.selected_options + '"]').prop('selected', true);
                        jQuery("#result_view_form").append(opt).multipleSelect("refresh");
                    });

                    jQuery.each(response.category, function (option, value) {
                        jQuery.each(response.selected_options, function (option, value1) {
                            jQuery('#result_view_form option[value="' + value1 + '"]').prop('selected', true);
                            jQuery("#result_view_form").multipleSelect("refresh");
                        });
                    });


                }

                if(form_type=="map_view_settings"){
                    jQuery("#map_view_form").empty();
                    jQuery.each(response.category, function (option, value) {

                        var opt = jQuery('<option />');
                        opt.val(option);
                        opt.text(value);
                        jQuery('#map_view_form option[value="' + response.selected_options + '"]').prop('selected', true);
                        jQuery("#map_view_form").append(opt).multipleSelect("refresh");
                    });

                    jQuery.each(response.category, function (option, value) {
                        jQuery.each(response.selected_options, function (option, value1) {
                            jQuery('#map_view_form option[value="' + value1 + '"]').prop('selected', true);
                            jQuery("#map_view_form").multipleSelect("refresh");
                        });
                    });
                }

                if(form_type=="map_pin_settings"){
                    jQuery("#map_pin_form").empty();
                    jQuery.each(response.category, function (option, value) {

                        var opt = jQuery('<option />');
                        opt.val(option);
                        opt.text(value);
                        jQuery('#map_pin_form option[value="' + response.selected_options + '"]').prop('selected', true);
                        jQuery("#map_pin_form").append(opt);
                        $('.pins_result').html("");
                        $('.pins_result_new').html("");
                    });

                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>app/get_saved_pins_html",
                        data: "form_id=" + id ,
                        success: function (response) {
                            $('#pins_table').html(response);
                        }
                    })

//                    jQuery.each(response.category, function (option, value) {
//                        jQuery.each(response.selected_options, function (option, value1) {
//                            jQuery('#map_view_form option[value="' + value1 + '"]').prop('selected', true);
//                            jQuery("#map_view_form").multipleSelect("refresh");
//                        });
//                    });
                }

                if(form_type=="graph_view_settings"){
                    jQuery("#graph_view_form").empty();
                    jQuery.each(response.category, function (option, value) {
                        if(value!='sent_by') {
                            var opt = jQuery('<option />');
                            opt.val(option);
                            opt.text(value);
                            jQuery('#graph_view_form option[value="' + response.selected_options + '"]').prop('selected', true);
                            jQuery("#graph_view_form").append(opt).multipleSelect("refresh");
                        }
                    });

                    jQuery.each(response.category, function (option, value) {
                        jQuery.each(response.selected_options, function (option, value1) {
                            jQuery('#graph_view_form option[value="' + value1 + '"]').prop('selected', true);
                            jQuery("#graph_view_form").multipleSelect("refresh");
                        });
                    });
                }

            }
        } );
    }
    document.addEventListener('DOMContentLoaded', function() {

        get_form_columns('<?php echo ($result_view_form)?$result_view_form:$result_view_form_id; ?>',$("#result_view_filter_value").val(),$("#result_view_form_value").val(),"result_view_settings");
        get_form_columns('<?php echo ($map_view_form)?$map_view_form:$map_view_form_id; ?>' , $("#map_view_filter_value").val() , $("#map_view_form_value").val(),"map_view_settings");
        get_form_columns('<?php echo ($graph_view_form)?$graph_view_form:$graph_view_form_id; ?>' , $("#graph_view_filter_value").val() , $("#map_view_form_value").val(),"graph_view_settings");
//           alert("ddd");
        <?php
        if(isset($selected_tab)){
        $tab_id_arr=explode("-",$selected_tab);
        $tab_id=$tab_id_arr[1];
        ?>
            $(".tabs<?php echo $tab_id; ?>").show();
            $("#<?php echo $selected_tab; ?>").click();

        <?php
        }
        ?>

    }, false);



function get_pins_n_table(value){

    $('#overlay_loading').show();
    jQuery(".pins_result_new").empty();
    value_arr=value.split("-");
//    var form_id=value_arr[0];
    var form_id=$("#map_pin_form_id").val();
//    var column=value_arr[1];
    var column=value;
    var total;

    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>app/get_map_pins",
        data: "form_id=" + form_id + "&column=" + column ,
        success: function (response) {
            $('.pins_result_msg').show();
            $('.actions').show();
            $('.pins_result').html(response);
            jQuery(".map_pin_value").autocomplete({

                filter: true,
                width: 200,
                placeholder: "Please select"
            });

            $( "input.myfield" ).autocomplete({

                source: function( request, response ) {

                    $.ajax({
                        dataType: "json",
                        type : 'POST',
                        url: "<?php echo base_url();?>app/get_field_values",
                        data: "form_id=" + form_id + "&column=" + column+"&value="+$('.myfield').val() ,
                        success: function(data) {
                            $('input.myfield').removeClass('ui-autocomplete-loading');  // hide loading image

                            response(
                                $.map(data, function(item) {
                                    return {
                                        label: item.label,
                                        value: item.value
                                    }
                                })
                            );
                        },
                        error: function(data) {
                            $('input.myfield').removeClass('ui-autocomplete-loading');
                        }
                    });
                },
                minLength: 2,
                open: function() {

                },
                close: function() {

                },
                focus:function(event,ui) {

                },
                select: function( event, ui ) {
                    var origEvent = event;
                    while (origEvent.originalEvent !== undefined){
                        origEvent = origEvent.originalEvent;
                    }
                    if (origEvent.type == 'click'){
                        document.getElementById('myfield').value = ui.item.value;
                        value=$('.myfield').val();
                    } else {
                        value=$('.myfield').val();
                    }


                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>app/get_name_pin",
                        data: "value=" + value + "&column=" + column+"&form_id="+form_id,
                        success: function (response) {
                            $('.pins_result_new').html(response);
                            $('#pins').ddslick({
                //            data: ddData,
                                    width: 150,
                                    height: 200,
                //            imagePosition: "left",
                //            selectText: "Select your favorite social network",
                                    onSelected: function (data) {
                                        console.log(data);
                                        if (data.selectedIndex > 0) {
                                            data.selectedData.value;
                                        }
                                    }

                                });

                        }
                    });
                }
            });


            $('#overlay_loading').hide();

            $(".myfield").keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                        return false;
                }
            });
        }
    });
}


//    function load_form_data(final_forms){
//        $("#form_data").load("../app/load_form_settings",{'form':final_forms});
//    }
</script>
<style>
    #overlay_loading img{
        margin: 20% 0 0 47%;
    }
</style>
