</div>
<div class="footer"><?php echo FOOTER_TEXT;?></div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.8.2.js"></script>
<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/modernizr.custom.js"></script>
<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/common-function.js" ></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/common_main.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.textchange.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.html5type.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/form_resources/js/jquery.tools.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/form_resources/js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/einars-js-beautify/beautify-html.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.countdown.js" ></script>

<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/common_main.js"></script>
<!-- Syntax Highligher Resources -->
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shCore.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushXml.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="http://jquery-datatables-column-filter.googlecode.com/svn/trunk/media/js/jquery.dataTables.columnFilter.js"></script>


    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
    
    
    
    <?php
if (isset($active_tab) && $active_tab == 'graph-category') {
    ?>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/highcharts.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.searchabledropdown-1.0.8.min.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>


    <script type="text/javascript">
        $(function() {
            $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
        });
        function clear_field(obj) {
            jQuery(obj).val("");
        }

        function check_date_validity() {
            var date_from = jQuery('#datepicker2').val();
            var date_to = jQuery('#datepicker').val();
            if (new Date(date_from).getTime() < new Date(date_to).getTime()) {
                jQuery('#datepicker2').val('');
                jQuery('#datepicker').val('');
                alert('Invalid Date selection');
            }
        }
        /**
         * function to update filters 
         * and trigger click event on filter  button  ubaid
         */
        function filter_update(app_id, filter_selected) {
            var filter_selected = filter_selected;
            if (filter_selected != 'all_visits') {
                $("input[name='all_visits_hidden']").val('0');
                $.ajax({
                    url: "<?= base_url() ?>form/changeFilterList",
                    data: {app_id: app_id, filter_selected: filter_selected},
                    type: 'POST',
                    success: function(resp) {
                        $("#cat_filter").empty();
                        $.each(resp, function(option, value) {
                            if (value.length > 23) {
                                value = value.substring(0, 23) + ' ...';
                            }
                            var opt = jQuery('<option />');
                            opt.val(option);
                            opt.text(value);
                            $("#cat_filter").append(opt).multipleSelect("refresh");
                        });
                        $('#overlay_loading').hide();
                        //                        $("#cat_filter").multipleSelect("checkAll");
                        //                        $("#form_list").multipleSelect("checkAll");
    //                        window.location = window.location;
                        $('#setfilter').submit();
                    },
                    error: function(data) {
                    }
                });
            } else {
                $("input[name='all_visits_hidden']").val('1');
                $("#cat_filter").empty();
                window.location = window.location;
            }

            $('#changed_category').val(filter_selected);
        }

        var templist = [];
        jQuery('#form_descrip').find('input, textarea, select').each(function() {

            if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
            {
                if (jQuery(this).attr('name') != undefined) {
                    var field_name = jQuery(this).attr('name');
                    field_name = field_name.replace('[]', '');
                    var skip = jQuery(this).attr('rel');
                    var type = jQuery(this).attr('type');
                    var selected = '<?php echo $filter; ?>';
                    //if (type != 'text' && type != 'hidden') {

                    if (jQuery.inArray(field_name, templist) == '-1')
                    {
                        var field_name_display = field_name;
                        templist.push(field_name);
                        if (field_name != 'District' && field_name != 'Tehsil' && field_name != 'Hospital_Name' && field_name != 'No_of_Citizen_Visited' && field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
                                //                    if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
                                {
                                    field_name_display.replace(/[_\W](^\s+|\s+$|(.*)>\s*)/g, "");
                                    field_name_display = field_name_display.replace(/_/g, ' ');
                                    field_name_display = capitalize_first_letter(field_name_display);
                                    //field_name_display
                                    if (selected == field_name)
                                    {
                                        field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                        jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
                                    }
                                    else {
                                        field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                        jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
                                    }
                                }
                    }
                    //}
                }
            }
        });
        function capitalize_first_letter(str) {
            var words = str.split(' ');
            var html = '';
            jQuery.each(words, function() {
                var first_letter = this.substring(0, 1);
                html += first_letter.toUpperCase() + this.substring(1) + ' ';
            });
            return html;
        }
    </script>
    <script type="text/javascript">
        $('#graph_view').css('background-color', '#EDB234');
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
        $(function() {
            $('#container').highcharts({
                chart: {
                    type: 'bar',
                    style: {
                        fontFamily: 'serif',
                    },
                    height: '<?php echo $calculated_height; ?>',
                    width: 0
                },
                title: {
                    text: '<?php echo $graph_text; ?>'
                },
                subtitle: {
                    text: 'Copy Rights dataplug.itu.edu.pk'
                },
                xAxis: {
                    categories: [<?php echo $list_x_axix; ?>],
                    title: {
                        //                    text: 'Towns List',
                        //                    align: 'high'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Tagging<?php echo " (Total : " . $total_records . ')'; ?>',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' Taggings'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        },
                        colorByPoint: true
                    }
                },
                colors: [
                    '#2f7ed8',
                    '#8bbc21',
                    '#910000',
                    '#492970',
                    '#0d233a',
                    '#1aadce',
                    '#f28f43',
                ],
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: 0,
                    y: 20,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: '#FFFFFF',
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                        name: 'Tagging Records',
                        data: [<?php echo $list_y_axix; ?>]
                    }]
            });
        });
        //    Pie charts start here....
        $(function() {
            $('#container_pie').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: '<?php echo $graph_text . ' (Pie Chart)'; ?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'pie',
                        name: 'Tagging Records',
                        data: [{
                                name: "<?php echo (isset($highest_name))? $highest_name: ''; ?>",
                                y: <?php echo (isset($highest_count))?$highest_count:'0'; ?>,
                                sliced: true,
                                selected: true
                            },<?php echo $pie_chart_data; ?>]
                    }]
            });
        });
    <?php
    if (isset($disbursement_type_rec) && $disbursement_type_rec) {
        //$disbursement_type_rec
        $categories_type = '';
        $datafor = '';
        $final_series = '';
        $categories_district_exist = array();
        foreach ($disbursement_type_rec as $keytype => $valuetype) {

            $datafor = '';
            foreach ($valuetype as $keydist => $valuedist) {
                if (!in_array($keydist, $categories_district_exist)) {
                    array_push($categories_district_exist, $keydist);
                    $categories_type .= "'" . $keydist . "'" . ",";
                    $datafor .= $valuedist['count'] . ',';
                }
            }
            $datafor = substr($datafor, 0, -1);
            $final_series .= "{
name: '$keytype',
data: [$datafor]
},";
        }

        $categories_type = substr($categories_type, 0, -1);
        $final_series = substr($final_series, 0, -1);
        ?>


            $(function() {
                $('#container_stack').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Incident Type Graph'
                    },
                    xAxis: {
                        categories: [<?php echo $categories_type; ?>]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total number of incident'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    legend: {
                        align: 'right',
                        x: -70,
                        verticalAlign: 'top',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>' + this.x + '</b><br/>' +
                                    this.series.name + ': ' + this.y + '<br/>' +
                                    'Total: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    textShadow: '0 0 3px black, 0 0 3px black'
                                }
                            }
                        }
                    },
                    series: [<?php echo $final_series; ?>]
                });
            });
    <?php } ?>
        $(window).load(function() {
            $('#overlay_loading').hide();
        });
        /**
         * FOR single category graph usess
         */
        $(document).ready(function() {

            /*
             * Loading wait status for map
             */
            loading_image();
            function loading_image() {
                $(function() {

                    var docHeight = $(document).height();
                    $("body").append('<div  id="overlay_loading" title="Please Wait while the Graph loads">\n\
        <img  alt=""  \n\
        src="<?php echo base_url() . 'assets/images/loading_map.gif'; ?>">\n\
        < /div>');
                    $("#overlay_loading")
                            .height(docHeight)
                            .css({
                                'opacity': 0.16,
                                'position': 'absolute',
                                'top': 0,
                                'left': 0,
                                'background-color': 'black',
                                'width': '100%',
                                'z-index': 5000
                            });
                });
            }

            $('#cat_filter').live('change', function() {

                $('#overlay_loading').show();
                var category_name = $(this).val();
                var selected = $(this).find('option:selected');
                var filter_attribute = selected.data('filter');
                if (category_name == '') {
                    window.location = window.location;
                } else {
                    $.ajax({
                        url: "<?= base_url() . 'graph/single_category_graph/' . $form_id ?>",
                        type: 'POST', data: {category_name: category_name, filter_attribute: filter_attribute},
                        success: function(response) {
                            jQuery('.applicationText').html(response);
                            $('#overlay_loading').hide();
                        }
                    });
                }
            });
        })
        $('#form_lists').change(function() {
            $('#graph_hidden_form_id').val($(this).val());
            $('#filter_submit').trigger('click');
        })

    </script>



    <?php
} else {
    ?>

    <script>

        jQuery('#list_view').css('background-color', '#EDB234');
        jQuery(function() {
            jQuery("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
        });
        jQuery(document).ready(function() {
            var all_visits_hidden = "<?php echo $all_visits_hidden ?>";
            if (all_visits_hidden == 1) {
                jQuery('#filter option[value="all_visits"]').prop('selected', true);
                jQuery("input[name='all_visits_hidden']").val('1');
            }
        })

        /**
         * function to update filters 
         * and trigger click event on filter  button  ubaid
         */
        function filter_update(app_id, filter_selected) {
            var filter_selected = filter_selected;
            if (filter_selected != 'all_visits') {
                jQuery("input[name='all_visits_hidden']").val('0');
                jQuery.ajax({
                    url: "<?= base_url() ?>form/changeFilterList",
                    data: {app_id: app_id, filter_selected: filter_selected},
                    type: 'POST',
                    success: function(resp) {
                        jQuery("#cat_filter").empty();
                        jQuery.each(resp, function(option, value) {
                            if (value.length > 23) {
                                value = value.substring(0, 23) + ' ...';
                            }
                            var opt = jQuery('<option />');
                            opt.val(option);
                            opt.text(value);
    //                            jQuery("#cat_filter").append(opt).multipleSelect("refresh");
                        });
                        //jQuery('#overlay_loading').hide();
    //                        jQuery("#cat_filter").multipleSelect("checkAll");
    //                        jQuery("#form_list").multipleSelect("checkAll");
                        jQuery('#filter_submit').trigger('click');
                    },
                    error: function(data) {
                    }
                });
            } else {
                jQuery("input[name='all_visits_hidden']").val('1');
                jQuery("#cat_filter").empty();
                jQuery('#filter_submit').trigger('click');
            }

            jQuery('#changed_category').val(filter_selected);
        }

    </script>

        <script>
            jQuery(document).ready(function() {
                jQuery('#changed_category').val(jQuery('#filter').val());
            })
        </script>
    
    <script type="text/javascript">
        var templist = [];
        jQuery('#form_descrip').find('input, textarea, select').each(function() {
            if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
            {
                if (jQuery(this).attr('name') != undefined) {
                    var field_name = jQuery(this).attr('name');
                    field_name = field_name.replace('[]', '');
                    var skip = jQuery(this).attr('rel');
                    var type = jQuery(this).attr('type');
                    var selected = '<?php echo $filter; ?>';
                    //if (type != 'text' && type != 'hidden') {

                    if (jQuery.inArray(field_name, templist) == '-1')
                    {
                        var field_name_display = field_name;
                        templist.push(field_name);
                        if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
                                //                    if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
                                {
                                    field_name_display.replace(/[_\W](^\s+|\s+$|(.*)>\s*)/g, "");
                                    field_name_display = field_name_display.replace(/_/g, ' ');
                                    field_name_display = capitalize_first_letter(field_name_display);
                                    //field_name_display
                                    if (selected == field_name)
                                    {
                                        field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                        jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
                                    }
                                    else {
                                        field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                        jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
                                    }
                                }
                    }
                    //}
                }
            }
        });
        if (jQuery('#filter').val() == null) {
            jQuery('#filter').append('<option value="version_name" >version name</option>');
        }
        //        jQuery('#filter').append('<option value="all_visits" display_value="all_visits" >All Visits</option>');
        function capitalize_first_letter(str) {
            var words = str.split(' ');
            var html = '';
            jQuery.each(words, function() {
                var first_letter = this.substring(0, 1);
                html += first_letter.toUpperCase() + this.substring(1) + ' ';
            });
            return html;
        }

        /**
         * 
         * @returns {undefined}
         * check date from and too compatibility
         * auth:ubd
         */
        function check_date_validity() {
            var date_from = jQuery('#datepicker2').val();
            var date_to = jQuery('#datepicker').val();
            if (new Date(date_from).getTime() < new Date(date_to).getTime()) {
                jQuery('#datepicker2').val('');
                jQuery('#datepicker').val('');
                alert('Invalid Date selection');
            }
        }
        /*
         * Clear date filed on doubl
         * click
         * auth:ubd
         */
        function clear_field(obj) {
            jQuery(obj).val("");
        }

    </script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.multiple.select.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/multiple-select.css"/>
    <!--<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.searchabledropdown-1.0.8.min.js"></script>-->

    <script>

        $('#district_list_1567').change(function() {
            //            $("#view_id > option").remove(); 
            jQuery("#d_center").empty();
            var district = jQuery(this).val();
            var app_id = "<?php echo $app_id; ?>";
            jQuery.ajax({
                type: "POST",
                url: "<?= base_url() ?>form/get_district_wise_d_center",
                data: {app_id: app_id, district: district},
                success: function(groups)
                {
                    jQuery.each(groups, function(id, type)
                    {
                        var opt = jQuery('<option />');
                        opt.val(id);
                        opt.text(type);
                        jQuery('#d_center').append(opt);
                    });
    //                    jQuery("#d_center").multipleSelect("refresh");
                }

            });
        });
        $('#form_list').change(function() {
            //            $("#view_id > option").remove(); 
            jQuery("#filter").empty();
            var form_id = jQuery(this).val();
            jQuery.ajax({
                type: "POST",
                url: "<?= base_url() ?>form/get_form_based_category_values",
                data: {form_id: form_id},
                success: function(resp) {
                    /** 
                     * Updating  category
                     */
                    jQuery("#filter").empty();
                    jQuery.each(resp.category, function(option, value) {
                        var opt = jQuery('<option />');
                        opt.val(option);
                        opt.text(value);
                        jQuery("#filter").append(opt);
                    });
                    if (resp.selected_cat) {
                        jQuery('#filter option[value="' + resp.selected_cat + '"]').prop('selected', true);
                        jQuery('#changed_category').val(resp.selected_cat);
                    }
                    /** 
                     * Updating sub category
                     */
                    jQuery("#cat_filter").empty();
                    jQuery.each(resp.sub_category, function(option, value) {
                        var opt = jQuery('<option />');
                        opt.val(option);
                        opt.text(value);
                        jQuery("#cat_filter").append(opt).multipleSelect("refresh");
                    });
                }

            });
        });
        jQuery("#cat_filter").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });

        jQuery("#sent_by_filter").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });

        jQuery("#sent_by_map_filter").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });

    
        jQuery(".filter_list_listview").multipleSelect({
            width: 210,
            filter: true,
            onClick: function(view) {
                //            $eventResult.text(view.label + '(' + view.value + ') ' +
                //                    (view.checked ? 'checked' : 'unchecked'));
                var changed_value = view.rel;
                var changed_filter = view.rel;
                //            var changed_filter = $('#changed_value:contains(' + changed_value + ')').attr('id');
                var filter_to_update = jQuery('#' + changed_filter).parent().next().children().attr('id');
                var filter_values = jQuery("#" + changed_filter + "").multipleSelect('getSelects');
                if (filter_values != '' && filter_to_update != null) {
                    //jQuery('#overlay_loading').show();
                    jQuery.ajax({
                        url: "<?= base_url() ?>form/map_filters_settings/<?php echo $app_id ?>",
                                            data: {filter_values: filter_values, filter_to_update: filter_to_update, changed_filter: changed_filter},
                                            type: 'POST',
                                            success: function(resp) {
                                                jQuery("#" + filter_to_update + "").empty();
                                                jQuery.each(resp, function(id, type) {
                                                    if (type.length > 23) {
                                                        type = type.substring(0, 23) + ' ...';
                                                    }
                                                    var opt = jQuery('<option />');
                                                    opt.val(id);
                                                    opt.text(type);
                                                    jQuery("#" + filter_to_update + "").append(opt).multipleSelect("refresh");
                                                    //                                               $("#" + filter_to_update + "").multipleSelect('setSelects', ["LAHORE CANTT TEHSIL"]);

                                                    //$("#" + filter_to_update + "").next().children().next().children().next().find('li input').attr('checked',true);
                                                });
                                                jQuery("#" + filter_to_update + "").next().children().next().children().next().find('li input').each(function() {
                                                    //$(this).trigger('click');
                                                })
                                                empty_filter(filter_to_update);
                                                //                                            $("#" + filter_to_update + "").multipleSelect("checkAll");
                                                jQuery("#" + filter_to_update + "").multipleSelect("refresh");
                                                jQuery('#overlay_loading').hide();
                                            },
                                            error: function(resp) {
                                                console.log('Error');
                                                jQuery('#overlay_loading').hide();
                                            }
                                        });
                                    }
                                    else
                                    {
                                        jQuery("#" + filter_to_update + "").empty();
                                        empty_filter(filter_to_update);
                                        //                                            $("#" + filter_to_update + "").multipleSelect("checkAll");
                                        jQuery("#" + filter_to_update + "").multipleSelect("refresh");
                                    }
                                }
                            });
                            function empty_filter(filter_to_update)
                            {
                                var next_id = jQuery("#" + filter_to_update + "").parent().next().children().attr('id');
                                if (next_id != undefined)
                                {
                                    jQuery("#" + next_id + "").empty();
                                    jQuery("#" + next_id + "").multipleSelect("refresh");
                                    empty_filter(next_id);
                                }
                            }
                            jQuery(document).ready(function() {
                                jQuery('.ms-parent li').hover(function() {
                                    var title = jQuery(this).children().children().attr('value');
                                    jQuery(this).attr('title', title);
                                    //                            alert($(this).children().children().attr('value'));
                                });
                            })

                          
                           
                            
                            
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/prototype.js"></script>
    <script type="text/javascript" charset="utf-8">
                            jQuery('td input').hide();
                            jQuery(document).ready(function() {

                                // Delete call here
                                
                                jQuery(".image_colorbox").live('click', function(e) {
                                    var url = window.location.pathname;
                                    var id = url.substring(url.lastIndexOf('/') + 1);
                                    var rowId = jQuery(this).parents('td').parents('tr').attr('id');
                                    var datum = 'form_id=' + id + '& form_result_id=' + rowId;
                                    jQuery(this).colorbox({
                                        width: "50%",
                                        height: "80%",
                                        open: true,
                                        data: datum,
                                    });
                                    e.preventDefault();
                                    return false;
                                });
                                

                            });
                            oTable = jQuery('#application-listing-listview').dataTable({
                                "jQueryUI": true,
                                "paginationType": "full_numbers",
                                "bServerSide": false,
                                'iDisplayLength': 25,
                                "oLanguage": {
                                    "sEmptyTable": "No data available"
                                },
                                "aaSorting": [[<?php echo count($headings) - 1; ?>, "desc"]],
                            });</script>
    <script type="text/javascript" language="javascript">
        //<!--Disabling right click on widget-->
       

        jQuery('.genericBtnreset').live('click', function() {
            jQuery("#cat_filter option:selected").removeAttr("selected");
            jQuery("#cat_filter").multipleSelect("refresh");
            jQuery("#form_list option:selected").removeAttr("selected");
            jQuery("#form_list").multipleSelect("refresh");
            jQuery('#datepicker2').val('');
            jQuery('#datepicker').val('');
            jQuery('#search_text').val('');
        });
        </script>
<?php } ?>


</body>

</html>
