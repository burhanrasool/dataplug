<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    ?>
</div>
<form id='setfilter' method='POST' action='<?= base_url() ?>graph/monthwisereport/<?php echo $app_id ?>' style="display: none;">
    <input name="form_id" value="<?php echo $form_id; ?>" type="hidden" id="graph_hidden_form_id" />
    <?php
    if (count($form_lists) > 1) {

        echo 'Forms : ';
        ?>
        <select id="form_lists" name ='form_lists' >
            <?php foreach ($form_lists as $values) { ?>
                <option value = "<?php echo $values['form_id'] ?>" <?php if ($values['form_id'] == $selected_form) echo 'selected'; ?> />
                <?php echo $values['form_name']; ?>
                </option>
            <?php } ?>
        </select>
        <?php
    }
    ?>
    Category :
    <select class="required" name="filter" id="filter" style="width:188px" onChange="jQuery('#overlay_loading').show();
            filter_update('<?php echo $app_id ?>', jQuery(this).val())"/>
    <?php echo $filter_options; ?>
    </select>
    <?php if ($app_id == '4631') { ?>

    UC :
    <select name="uc[]" class="multiselect uc" multiple="multiple">
            <!--        --><?php echo $uc_options; ?>

        </select>
    <?php } ?>
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

    <?php if (isset($sent_by_list)) { ?>
        <span id="filter_span_district">
        Sent By :
        <select  name="sent_by_list" id="sent_by_graph_list" style="max-width: 127px" >
            <option selected="selected" value="">All Sent By</option>
            <?php
            foreach ($sent_by_list as $sent_by) {
                if (isset($sent_by['name']) && $sent_by['name'] != '') {
                    $select = '';
                    if (strip_tags($sent_by['imei_no']) == $selected_sent_by) {
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

<!--<label style="padding-right:5px;">From</label> : -->
<input style="display: none;" size="12" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($from_date) ? $from_date : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
<!--&nbsp To :&nbsp-->
<input style="display:none;" size ="12" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($to_date) ? $to_date : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
<input type="submit" value="Submit" id="filter_submit" class="genericBtn">
</form>
<div class="applicationText">
<?php
    if(isset($filter_result->bar_graph_filters) && $filter_result->bar_graph_filters==1) {
        ?>
    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto; float: right;">

    </div>
    <?php }?>
    <hr>
    <?php
    if(isset($filter_result->pie_chart_of_selected_filters) && $filter_result->pie_chart_of_selected_filters==1) {
        ?>

        <div id="container_pie" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">
        </div>
    <hr>
    <?php
    }
    ?>
    <?php if ($app_id == '1567') { ?>

        <h5>
            Facilities Availability Report
            </h1>
            <div class="CSSTableGenerator" >
                <table border="1" style="clear:both" cellspacing="0" cellpadding="0" id="data_cat" class="display">
                    <tr>
                        <td>District</td>
                        <td>Tehsil</td>
                        <td>Disbursement Center</td>
                        <td>Facilities N/A Total</td>
                        <td>Facilities N/A</td>
                    </tr>

                    <?php
                    $available_array = array(
                        "Police security at site"
                        , "Electrity supply"
                        , "Generator"
                        , "Fuel for generator"
                        , "Outside tentage"
                        , "Medical facility counter"
                        , "Cooked food"
                        , "Water"
                        , "Tea"
                        , "NADRA’s MRV"
                        , "Presence of NADRA’s staff"
                        , "Awareness banners"
                        , "DRC counter"
                        , "Volunteer teachers"
                        , "Volunteers wearing an identity tag"
                        , "Transport arrangement for affectees"
                        , "Presence of Bank of Punjab staff"
                        , "Availability of cash"
                        , "Cash counters fully functional"
                        , "Functional Biometric devices"
                        , "Functional CCTV cameras"
                        , "Functional toliets"
                        , "Internet connectivity"
                    );
                    foreach ($disbursement_rec as $disb_district_key => $disb_district) {
                        foreach ($disb_district as $disb_tehsil_key => $disb_tehsil) {
                            foreach ($disb_tehsil as $disb_dc_key => $disb_dc) {
                                ?>
                                <tr>

                                    <td><?php echo $disb_district_key; ?></td>
                                    <td><?php echo $disb_tehsil_key; ?></td>
                                    <td><?php echo $disb_dc_key; ?></td>

                                    <?php
                                    $total_rows = count($disb_dc['facilities']);
                                    ?>
                                    <td><?php echo 23 - $total_rows; ?></td><td>
                                        <?php
                                        foreach ($available_array as $disb_na) {
                                            if (!in_array($disb_na, $disb_dc['facilities'])) {
                                                echo $disb_na . '<br />';
                                            }
                                        }
                                        ?>
                                    </td></tr>
                                <?php
                            }
                        }
                        ?> <?php
                    }
                    ?>

                </table>
            </div>

        <?php } else {
        if(isset($filter_result->district_wise_report) && $filter_result->district_wise_report==1){
        ?>
            <div style="">
                <div style="float: left;width: 50%"><h2>
                Month wise Report
                </h2></div>
                <div style="float: left;display:none">
                    <a style="position:relative;" href="<?php base_url()?>/graph/exportdistrictreport/<?php echo $form_id;?>/?from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : ""; ?>"><button>Export</button></a>
                    
                </div>
                <div style="float: left;margin-right: 50px;">
                    <a style="position:relative;"  href="<?php base_url()?>/graph/dashboard/<?php echo $app_id;?>/?from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : ""; ?>"><button>Back</button></a>
                </div>
                
            </div>
            
                
                <div class="CSSTableGenerator">
                    <table border="1" style="clear:both" cellspacing="0" cellpadding="0" id="data_cat" class="display">
                        <tr>
                            <td>Month</td>
                            <?php
//                            echo "<pre>";
//                            print_r($category_list);
                            foreach ($category_list as $category) {
//                            foreach ($district_categorized as $key=>$category) {
                            ?>


<!--                                <td>--><?php //echo $key; ?><!--</td>-->
                                <td><?php echo $category; ?></td>

                            <?php } ?>
                            <td>Total</td>
                        </tr>
                        <?php
                        foreach ($district_categorized as $data) {
                            $counter = 0;
                            
                            ?>
                            <tr><?php
                            foreach ($data as $insid_key =>$inside) {
                                
                                //$total_string="<td>0</td>";;
                                if($insid_key=='total'){
                                    $total_string = "<td>".$data['total']."</td>";
                                }else{
                                ?>

                                <td><?php 
                                    if ($counter == 0) {
                                        if($inside == '0/01/0'){
                                            echo "Not Set";
                                        }else{
                                            echo  date("Y-M", strtotime($inside." 00:00:00"));
                                        }
                                    }  else {
                                        if ($inside == 0) {
//                                            this check added for dataplug for application school inspection
                                            if($app_id==2663) {
                                                echo '0';
                                            }else{
                                                echo '0  ( 0 % )';
                                            }
                                        } else {
//                                            this check added for dataplug for application school inspection
                                            if($app_id==2663) {
                                                echo $inside;
                                            }else{
                                                echo $inside . ' (' . round($inside / $data['total'] * 100, 2) . ' %) ';
                                            }
                                        }
                                    }
                                    ?></td>

                                <?php }
                                $counter++;
                            }

                            echo $total_string;

                            ?> 
                                
                            </tr><?php
                        }
                        ?>

                    </table>
                </div>
                </h5>
                <?php
                }
            }
            ?>
            </div>


            <style>

                .ui-widget-content {
                    background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x scroll 50% 50% #ffffff !important;
                    border: medium none !important;
                    color: #222222 !important;
                }
                #form_lists, #filter,#district_list,#sent_by_graph_list {
                    background-color: #ffffff;
                    border: 1px solid #0e76bd;
                    color: #444444;
                    cursor: pointer;
                    height: 26px;
                    line-height: 23px;
                    margin-right: 0;
                    max-width: 110px;
                    overflow: hidden;
                    padding: 2px ;
                    text-align: left;
                    text-decoration: none;
                    white-space: nowrap;
                    width: 135px;
                }
                #datepicker, #datepicker2 {
                    background-color: #ffffff;
                    border: 1px solid #0e76bd;
                    color: #444444;
                    cursor: pointer;
                    height: 22px;
                    line-height: 26px;
                    overflow: hidden;
                    padding-left: 10px;
                    text-align: left;
                    text-decoration: none;
                    white-space: nowrap;
                }

                input.genericBtn {
                    background: none repeat scroll 0 0 #2da5da;
                    border: medium none;
                    color: #fff;
                    cursor: pointer;
                    float: left;
                    height: 26px;
                    margin: 0px 0px 0px 12px;
                    outline: medium none;
                    padding: 3px 20px;
                    position: absolute;
                }
                #overlay_loading img{
                    margin: 20% 0 0 47%;
                }
                .applicationText {

                    margin-top: 23px;

                }

                .CSSTableGenerator {
                    margin:0px;padding:0px;
                    width:100%;
                    box-shadow: 10px 10px 5px #888888;
                    border:1px solid #000000;

                    -moz-border-radius-bottomleft:0px;
                    -webkit-border-bottom-left-radius:0px;
                    border-bottom-left-radius:0px;

                    -moz-border-radius-bottomright:0px;
                    -webkit-border-bottom-right-radius:0px;
                    border-bottom-right-radius:0px;

                    -moz-border-radius-topright:0px;
                    -webkit-border-top-right-radius:0px;
                    border-top-right-radius:0px;

                    -moz-border-radius-topleft:0px;
                    -webkit-border-top-left-radius:0px;
                    border-top-left-radius:0px;
                    overflow-x: scroll;
                }.CSSTableGenerator table{
                    border-collapse: collapse;
                    border-spacing: 0;
                    width:100%;
                    height:100%;
                    margin:0px;padding:0px;
                }.CSSTableGenerator tr:last-child td:last-child {
                    -moz-border-radius-bottomright:0px;
                    -webkit-border-bottom-right-radius:0px;
                    border-bottom-right-radius:0px;
                }
                .CSSTableGenerator table tr:first-child td:first-child {
                    -moz-border-radius-topleft:0px;
                    -webkit-border-top-left-radius:0px;
                    border-top-left-radius:0px;
                }
                .CSSTableGenerator table tr:first-child td:last-child {
                    -moz-border-radius-topright:0px;
                    -webkit-border-top-right-radius:0px;
                    border-top-right-radius:0px;
                }.CSSTableGenerator tr:last-child td:first-child{
                    -moz-border-radius-bottomleft:0px;
                    -webkit-border-bottom-left-radius:0px;
                    border-bottom-left-radius:0px;
                }.CSSTableGenerator tr:hover td{

                }
                .CSSTableGenerator tr:nth-child(odd){ background-color:#ffffff; }
                .CSSTableGenerator tr:nth-child(even)    { background-color:#cccccc; }.CSSTableGenerator td{
                    vertical-align:middle;


                    border:1px solid #000000;
                    border-width:0px 1px 1px 0px;
                    text-align:left;
                    padding:7px;
                    font-size:12px;
                    font-family:Arial;
                    font-weight:bold;
                    color:#000000;
                }.CSSTableGenerator tr:last-child td{
                    border-width:0px 1px 0px 0px;
                }.CSSTableGenerator tr td:last-child{
                    border-width:0px 0px 1px 0px;
                }.CSSTableGenerator tr:last-child td:last-child{
                    border-width:0px 0px 0px 0px;
                }
                .CSSTableGenerator tr:first-child td{
                    background:-o-linear-gradient(bottom, #4c4c4c 5%, #4c4c4c 100%); background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #4c4c4c), color-stop(1, #4c4c4c) );
                    background:-moz-linear-gradient( center top, #4c4c4c 5%, #4c4c4c 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#4c4c4c", endColorstr="#4c4c4c"); background: -o-linear-gradient(top,#4c4c4c,4c4c4c);

                    background-color:#4c4c4c;
                    border:0px solid #000000;
                    text-align:center;
                    border-width:0px 0px 1px 1px;
                    font-size:14px;
                    font-family:Arial;
                    font-weight:bold;
                    color:#ffffff;
                }
                .CSSTableGenerator tr:first-child:hover td{
                    background:-o-linear-gradient(bottom, #4c4c4c 5%, #4c4c4c 100%); background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #4c4c4c), color-stop(1, #4c4c4c) );
                    background:-moz-linear-gradient( center top, #4c4c4c 5%, #4c4c4c 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#4c4c4c", endColorstr="#4c4c4c"); background: -o-linear-gradient(top,#4c4c4c,4c4c4c);

                    background-color:#4c4c4c;
                }
                .CSSTableGenerator tr:first-child td:first-child{
                    border-width:0px 0px 1px 0px;
                }
                .CSSTableGenerator tr:first-child td:last-child{
                    border-width:0px 0px 1px 1px;
                }
                table.display td {
                    padding: 5px 10px;
                }
            </style>
            <?php
            /**
             * for x and y - axix data
             */
            $list_x_axix = '';
            $list_y_axix = '';
            foreach ($category_list_count as $key => $counts) {
                $key = (strlen($key) > 30) ? substr($key, 0, 30) . ' ...' : $key;
                $list_x_axix .= "'$key'" . ',';
                $list_y_axix .= "$counts" . ',';
            }
            $list_x_axix = substr($list_x_axix, 0, -1);
            $list_y_axix = substr($list_y_axix, 0, -1);
            if (count($category_list_count) > 14) {
                $calculated_height = count($category_list_count) * 30;
            } else {
                $calculated_height = 420;
            }

            /**
             * Pie chart system
             */
            $pie_array = $category_list_count;
            $highest_point = '';
            $pie_chart_data = '';
            array_shift($pie_array);
            foreach ($pie_array as $key => $counts) {
                $pie_chart_data .= '[' . "'$key'" . ',' . $counts . '],';
            }
            /*
             * getting only highest value from data to make
             * it bit poped in graph
             */
            foreach ($category_list_count as $key => $counts) {
                $highest_name = $key;
                $highest_count = $counts;
                break;
            }
            $pie_chart_data = substr($pie_chart_data, 0, -1);
            ?>

            <script type="text/javascript">
                var Settings = {
                    base_url: '<?php echo base_url(); ?>',
                    form_id: '<?php echo $form_id; ?>',
                    list_x_axix: "<?php echo $list_x_axix; ?>",
                    total_records: '<?php echo $total_records; ?>',
                    filter: '<?php echo $filter; ?>',
                    list_y_axix: "<?php echo $list_y_axix; ?>",
                    graph_text: "<?php echo $graph_text; ?>",
                    highest_count: "<?php echo (isset($highest_count))?$highest_count:0; ?>",
                    pie_chart_data: "<?php echo $pie_chart_data; ?>",
                    calculated_height: "<?php echo $calculated_height; ?>",
                    highest_name: "<?php echo (isset($highest_name))?$highest_name:''; ?>",
                }
            </script>
