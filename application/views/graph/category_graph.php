
<?php
/**
 * for x and y - axix data
 */
$list_x_axix = '';
$list_y_axix = '';
foreach ($category_list_count as $key => $counts) {
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
?>


<div class="applicationText">

    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">

    </div>
</div>

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
</script>
