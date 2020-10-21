
<div class="applicationText">

    <?php 
        if(isset($filter_result->district_wise_report) && $filter_result->district_wise_report==1){
        ?>
    <div style="clear: both;"><div style="float: left; width: 38%;"><a style="position: relative;float: left" href="<?php base_url()?>/graph/dashboard/<?php echo $app_id;?>/?from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : ""; ?>"><button>Back</button></a></div>
    <div style="text-align: center; width: 33%; float: left;">
        <h2 style="float:left;color: black;">
                Report of District <?php echo $district;?> 
        </h2>
    </div>
        <div><a href="<?php base_url()?>/graph/exportschoolreport/<?php echo $form_id;?>/?district=<?php echo $district;?>&from_date=<?php echo!empty($from_date) ? $from_date : ""; ?>&to_date=<?php echo!empty($to_date) ? $to_date : ""; ?>"><button>Export</button></a></div></div>
    
 
                
                
                
                
                <div class="CSSTableGenerator">
                    <table border="1" style="clear:both" cellspacing="0" cellpadding="0" id="data_cat" class="display">
                        <tr>
                            <?php
                            foreach ($category_list as $category) {
//                            foreach ($district_categorized as $key=>$category) {
                            ?>


<!--                                <td>--><?php //echo $key; ?><!--</td>-->
                                <td><?php echo $category; ?></td>

                            <?php } ?>
                            
                        </tr>
                        <?php
                        foreach ($district_categorized as $data) {
                            $counter = 0;
                            
                            ?>
                            <tr><?php
                            foreach ($data as $insid_key =>$inside) {
                                
                                //$total_string="<td>0</td>";;
                                if($insid_key=='total'){
                                    //$total_string = "<td>".$data['total']."</td>";
                                }else{
                                ?>

                                <td><?php 
                                    if ($counter == 0) {
                                        echo $inside;
                                    }  else {
                                        if($insid_key=='Category'){
                                            echo $inside;
                                        }
                                        else if ($inside == 0) {
                                            echo 'No';
                                        } else {
                                            echo 'Yes';
                                        }
                                    }
                                    ?></td>

                                <?php }
                                $counter++;
                            }
                            //echo $total_string;
                            ?> 
                                
                            </tr><?php
                        }
                        ?>

                    </table>
                </div>
                </h5>
                <?php
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
