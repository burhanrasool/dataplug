        $(function() {
            $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
        });
        function clear_field(obj) {
            $(obj).val("");
        }

        function check_date_validity() {
            var date_from = $('#datepicker2').val();
            var date_to = $('#datepicker').val();
            if (new Date(date_from).getTime() < new Date(date_to).getTime()) {
                $('#datepicker2').val('');
                $('#datepicker').val('');
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
                    url: Settings.base_url+"form/changeFilterList",
                    data: {app_id: app_id, filter_selected: filter_selected},
                    type: 'POST',
                    success: function(resp) {
                        $("#cat_filter").empty();
                        $.each(resp, function(option, value) {
                            if (value.length > 23) {
                                value = value.substring(0, 23) + ' ...';
                            }
                            var opt = $('<option />');
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
        $('#form_descrip').find('input, textarea, select').each(function() {

            if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
            {
                if ($(this).attr('name') != undefined) {
                    var field_name = $(this).attr('name');
                    field_name = field_name.replace('[]', '');
                    var skip = $(this).attr('rel');
                    var type = $(this).attr('type');
                    var selected = Settings.filter;
                    //if (type != 'text' && type != 'hidden') {

                    if ($.inArray(field_name, templist) == '-1')
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
                                        $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
                                    }
                                    else {
                                        field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                        $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
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
            $.each(words, function() {
                var first_letter = this.substring(0, 1);
                html += first_letter.toUpperCase() + this.substring(1) + ' ';
            });
            return html;
        }
        
        
        $('#graph_view').css('background-color', '#EDB234');
        
        
        $(function() {
            $('#container').highcharts({
                chart: {
                    type: 'bar',
                    style: {
                        fontFamily: 'serif',
                    },
                    height: Settings.calculated_height,
                    width: 804
                },
                title: {
                    text: Settings.graph_text
                },
                subtitle: {
                    text: 'Copy Rights dataplug.itu.edu.pk'
                },
                xAxis: {
                    categories: [Settings.list_x_axix],
                    title: {
                        //                    text: 'Towns List',
                        //                    align: 'high'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Tagging (Total : '+ Settings.total_records+')',
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
                        data: [Settings.list_y_axix]
                    }]
            });
        });
        
        
        $(function() {
            $('#container_pie').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: Settings.graph_text + ' (Pie Chart)'
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
                                name: Settings.highest_name,
                                y: Settings.highest_count,
                                sliced: true,
                                selected: true
                            },Settings.pie_chart_data]
                    }]
            });
        });
        
        
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
                    $("body").append('<div  id="overlay_loading" title="Please Wait while the Graph loads"><img  alt=""  src="'+Settings.base_url +'assets/images/loading_map.gif">< /div>');
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
                        url: Settings.base_url + 'graph/single_category_graph/' +Settings.form_id,
                        type: 'POST', data: {category_name: category_name, filter_attribute: filter_attribute},
                        success: function(response) {
                            $('.applicationText').html(response);
                            $('#overlay_loading').hide();
                        }
                    });
                }
            });
        });
        $('#form_lists').change(function() {
            $('#graph_hidden_form_id').val($(this).val());
            $('#filter_submit').trigger('click');
        });
        
        if(Settings.disburs_chart){
        	
        	 $(function() {
                 $('#container_stack').highcharts({
                     chart: {
                         type: 'column'
                     },
                     title: {
                         text: 'Incident Type Graph'
                     },
                     xAxis: {
                         categories: [Settings.categories_type]
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
                     series: [Settings.final_series]
                 });
             });
        	
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        