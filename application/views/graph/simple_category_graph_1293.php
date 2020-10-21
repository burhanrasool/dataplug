<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    ?>
</div>

<?php
echo '<div class="form_class" style="">';
if (count($form_lists) > 1) {
    echo form_open(base_url() . 'graph/dashboard/' . $app_id, 'id=date_filter_form name=date_filter_form');
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
    echo form_close();
}
echo '</div>';
?>
<form id='setfilter' method='POST' action='<?= base_url() ?>graph/dashboard/<?php echo $app_id ?>'>
    <input name="form_id" value="<?php echo $form_id; ?>" type="hidden" id="graph_hidden_form_id" />
    Change Category :
    <?php
    $flood_relief_options = array(
//        'Name' => 'Name',
//        'Father_Name' => 'Father Name',
        'Gender' => 'Gender',
        'district_name' => 'District Name',
//        'Phone_Number' => 'Phone Number',
        'Interview_Point' => 'Interview Point',
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
                    $count = 0;
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
<label style="padding-right:19px;">From</label> : 
<input size ="15" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($from_date) ? $from_date : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
&nbsp To :&nbsp
<input size="15" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($to_date) ? $to_date : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
<input type="submit" value="Submit" id="filter_submit" class="genericBtn">
</form>


<div class="applicationText">

    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto; float: right;">

    </div>
    <hr>
    <div id="container_pie" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">
    </div>
    <hr>
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

        <?php } else { ?>
            <h5>
                District wise Report
                </h1>
                <div class="CSSTableGenerator" >
                    <table border="1" style="clear:both" cellspacing="0" cellpadding="0" id="data_cat" class="display">
                        <tr>
                            <td>District</td>
                            <?php foreach ($category_list as $category) { ?>


                                <td><?php echo $category ?></td>

                            <?php } ?>
                            <td>Total</td>
                        </tr>
                        <?php
                        foreach ($district_categorized as $data) {
                            $counter = 0;
                            ?> <tr><?php
                                foreach ($data as $inside) {
                                    ?>

                                    <td><?php
                                        if ($counter == 0) {
                                            echo $inside;
                                        } else if ($counter == count($data) - 1) {

                                            echo $inside;
                                        } else {
                                            if ($inside == 0) {
                                                echo '0  ( 0 % )';
                                            } else {
                                                echo $inside . ' (' . round($inside / $data['total'] * 100, 2) . ' %) ';
                                            }
                                        }
                                        ?></td>

                                    <?php
                                    $counter ++;
                                }
                                ?> </tr><?php
                        }
                        ?>

                    </table>
                </div>

            <?php } ?>
            </div>


            <style>
                input.genericBtn {
                    background: none repeat scroll 0 0 #2da5da;
                    border: medium none;
                    color: #fff;
                    cursor: pointer;
                    display: block;
                    float: right;
                    margin: 0px 195px 0px 0px;
                    outline: medium none;
                    padding: 3px 20px;
                    position: relative;
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
                    highest_count: "<?php echo $highest_count; ?>",
                    pie_chart_data: "<?php echo $pie_chart_data; ?>",
                    calculated_height: "<?php echo $calculated_height; ?>",
                    highest_name: "<?php echo $highest_name; ?>",
                }
            </script>
