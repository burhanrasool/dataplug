<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    ?>
</div>
<form id='setfilter' method='POST' action='<?= base_url() ?>customreports/dashboard/<?php echo $app_id ?>'>
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
    <select class="required" name="filter1" id="filter1" style="width:188px" onChange="jQuery('#overlay_loading').show();"/>
    <?php echo $filter_options1; ?>
    </select>
    Sub Category :
    <select class="required" name="filter" id="filter" style="width:188px" onChange="jQuery('#overlay_loading').show();
            filter_update('<?php echo $app_id ?>', jQuery(this).val())"/>
    <?php echo $filter_options; ?>
    </select>
<label style="padding-right:5px;">From</label> :
<input size="12" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($from_date) ? $from_date : ""; ?>" name="filter_date_from"  onchange="check_date_validity()" ondblclick="clear_field(this)">
&nbsp To :&nbsp
<input size ="12" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($to_date) ? $to_date : ""; ?>" name="filter_date_to"  onchange="check_date_validity()" ondblclick="clear_field(this)">
<input type="submit" value="Submit" id="filter_submit" class="genericBtn">
</form>
<div style="">

    <div style="float: left; margin-right: 9px;">
        <a style="position:relative;" href="<?php echo base_url()?>customreports/exportdistrictreport/<?php echo $form_id;?>/?from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : "" ; ?>&filter1=<?php echo $new_category; ?>"><button>Export</button></a>

    </div>
    <div style="float: left;margin-right: 9px;">
        <a style="position:relative;"  href="<?php echo base_url()?>graph/monthwisereport/<?php echo $app_id;?>/?from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : ""; ?>"><button>Month Wise Report</button></a>
    </div>
    <div style="float: left;margin-right: 9px;">
        <a style="position:relative;"  href="javascript:void(0)" onclick="printDiv('applicationText')"><button>Print</button></a>
    </div>

</div>
<div class="applicationText">

            <div style="">
                <div style="float: left;width: 50%">
                    <h2>
                        <?php echo ucwords(str_replace("_"," ",$new_category)); ?> Wise Report for <?php echo ucwords(str_replace("_"," ",$default_selected_category));?>
                    </h2>
                </div>


            </div>
            
                
                <div class="CSSTableGenerator">
                    <table border="1" style="clear:both" cellspacing="0" cellpadding="0" id="data_cat" class="display">
                        <tr>
                            <td><?php echo ucwords(str_replace("_"," ",$new_category)); ?></td>
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
                            <?php if($app_id==2663){ ?>
                            <td>Activity Total</td>
                            <?php  } ?>
<!--                            <td>Action</td>-->
                        </tr>
                        <?php
//                        echo "<pre>";
//                        print_r($district_categorized);die;
//                        foreach($district_categorized as $key=>$val){
//                            foreach($val as $key1=>$val1){
//                                if($key1!="district" && $key1!="total"){
//
//                                }                            }
//                        }
                        $last_array=array();
                        foreach ($district_categorized as $data) {
                            $counter = 0;
                            if(!empty($last_array)) {
                                    foreach ($data as $id=>$value) {
                                        $last_array[$id]+=$value;
                                    }
//                                echo "<pre>";
//                                print_r($last_array);die;
                            }
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
                                        echo $inside;
                                        $districts = $inside;
                                        
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
                            if(empty($last_array)) {
                                $last_array = $data;
                            }

                            echo $total_string;

                            ?> 
<!--                                <td><a href="--><?php //echo base_url()?><!--graph/subreport/--><?php //echo $app_id;?><!--/?district=--><?php //echo $districts;?><!--&from_date=--><?php //echo!empty($from_date) ? $from_date : ""; ?><!--&to_date=--><?php //echo!empty($to_date) ? $to_date : ""; ?><!--" style="position: relative">Detail</a></td>-->
                            </tr><?php
                        }
                        $last_array['district']="TOTAL";
//                        echo "<pre>";
//                        print_r($last_array);die;
                        echo "<tr>";
                        foreach($last_array as $key=>$val){
                            if($key!='total') {
                                echo "<td>$val</td>";
                            }
                        }
                        $total=(isset($last_array['total']))?$last_array['total']:"";
                        echo "<td>$total</td>";
                        echo "</tr>";
                        ?>

                    </table>
                </div>
                </h5>
                <?php


            ?>



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
                    float: left;
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
</div>
<script>
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = $('.'+divID).html();
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        //Print Page
        window.print();
        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }

</script>

