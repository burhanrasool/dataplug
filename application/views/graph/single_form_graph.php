
<?php
/**
 * for x and y - axix data
 */
$list_x_axix = '';
$list_y_axix = '';
foreach ($users_wise_counter as $key => $counts) {
    $list_x_axix .= "'$key'" . ',';
    $list_y_axix .= "$counts" . ',';
}
//echo '<pre>';
//echo $list_y_axix;
//print_r($users_wise_counter);die;

$list_x_axix = substr($list_x_axix, 0, -1);
$list_y_axix = substr($list_y_axix, 0, -1);
if (count($users_wise_counter) > 14) {
    $calculated_height = count($users_wise_counter) * 30;
} else {
    $calculated_height = 420;
}




/**
 * Pie chart system
 */
$pie_array = $users_wise_counter;
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
foreach ($users_wise_counter as $key => $counts) {
    $highest_name = $key;
    $highest_count = $counts;
    break;
}
$pie_chart_data = substr($pie_chart_data, 0, -1);
?>


<div class="applicationText">

    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">

    </div>
    <button id="button_bar">Export Bar Graph</button>
    <br>
    <br>
    <hr>
    <div id="container_pie" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">
    </div>
    <button id="button_pie">Export Pie Chart</button>
    <br>
    <br>
    <hr>
</div>
<!--    Add comment block start -->
<div style="width: 100%" class="applicationText">
    <div style="width: 100%" class="filter_class">
        <div style="margin:10px 0 0 13px;">

            &nbsp; Post Comment :&nbsp; &nbsp;
            <textarea type="text"id="comment_text" value="" style='width: 100%'></textarea>
            <div id='comment_saved' style='display: none;color: green;'>Your comment has been posted successfully...</div>
            <div id='comment_error' style='display: none;color: red;'>Please enter some text to post.</div>
            <input type="button" id="comments_adding" value="Add Comment">
        </div>
    </div>
</div>
<!--    Add comment block end -->
<script type="text/javascript">


    $(function() {
        $('#container').highcharts({
            chart: {
                type: 'bar',
                style: {
                    fontFamily: 'serif',
                },
                height: '<?php echo $calculated_height; ?>',
                width: 804
            },
            title: {
                text: '<?php echo str_replace('_', ' ', $graph_type); ?> Bar Chart'
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
                    text: '<?php echo 'Out of selected samples of ' . $no_of_visits . ' visits';?>',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' Record'
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
                    name: '<?php echo str_replace('_', ' ', $graph_type); ?>',
                    data: [<?php echo $list_y_axix; ?>]
                }]
        });
        // the button handler
        $('#button_bar').click(function() {
            var chart = $('#container').highcharts();
            chart.exportChart(null, {
                chart: {
                    backgroundColor: '#FFFFFF'
                }
            });
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
                text: '<?php echo str_replace('_', ' ', $graph_type) . ' (Pie Chart)<br> Out of selected samples of ' . $no_of_visits . ' visits'; ?>',
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
                    name: '<?php echo str_replace('_', ' ', $graph_type); ?>',
                    data: [{
                            name: "<?php echo $highest_name ?>",
                            y: <?php echo $highest_count ?>,
                            sliced: true,
                            selected: true
                        },<?php echo $pie_chart_data; ?>]
                }]
        });
        // the button handler
        $('#button_pie').click(function() {
            var chart = $('#container_pie').highcharts();
            chart.exportChart(null, {
                chart: {
                    backgroundColor: '#FFFFFF'
                }
            });
        });
    });

    jQuery("#comments_adding").live('click', function(e) {

        var comment_text = jQuery('#comment_text').val();
        if (comment_text.trim() == '')
        {
            jQuery('#comment_saved').hide();
            jQuery('#comment_error').show();
            return false;
        }
        var app_id = '266';
        jQuery.ajax({
            url: "<?= base_url() ?>form/comments_adding",
            data: {
                'comment_text': comment_text,
                'app_id': app_id
            },
            type: "post",
            success: function(response) {
                jQuery('#comment_text').val('');
                jQuery('#comment_area').html(response);
                jQuery('#comment_error').hide();
                jQuery('#comment_saved').show();
            }

        });
    });

</script>
<style>
    .applicationText button{
        float: right;
    }
</style>
