
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
$list_x_axix = substr($list_x_axix, 0, -1);
$list_y_axix = substr($list_y_axix, 0, -1);

if (count($users_wise_counter) > 14) {
    $calculated_height = count($users_wise_counter) * 30;
} else {
    $calculated_height = 420;
}
?>


<div class="applicationText">

    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">

    </div>
</div>
<div class="tableContainer">
    <div class="success"></div>
    <div>
        <form accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?= base_url() . 'form/edit_form_result_ajax'; ?>" id='form_edit'>
            <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
                <thead>
                    <tr>
                        <?php
                        $total_headings = count($headings);
                        foreach ($headings as $heading):
                            if ($heading != 'is_take_picture'):
                                ?>
                                <th class="Categoryh"><?php echo trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', urldecode($heading))); ?></th>
                                <?php
                            endif;
                        endforeach
                        ?>
                    </tr>
                </thead>

                <tbody>


                    <?php foreach ($form as $form_item): ?>
                        <tr class="trSelect" id="<?php echo $form_item['actions']; ?>">
                            <?php
                            for ($i = 0; $i < $total_headings; $i++) {

                                if ($headings[$i] != 'is_take_picture') {
                                    ?>
                                    <td class="Category" >
                                        <?php
                                        if (isset($form_item[$headings[$i]])) {
                                            if ($headings[$i] == 'image') {

                                                $image_colorbox = $form_item['actions'];

                                                if ($form_id == 9 OR $form_id == 40) {
                                                    ?>
                                                    <!--Light box for other and color box for tow apps 9 and 40-->
                                                    <a href="<?= base_url() . 'form/gallery_partial'; ?>" title="All Rights Reserved  © 2013 - Government Open Data Kit<br> Developed By :ITU Government of Punjab - Pakistan " name = " ITU Government of Punjab - Pakistan" class="image_colorbox">
                                                        <img alt="" width="50" height="50" src="<?php echo $form_item[$headings[$i]][0]['image']; ?>">
                                                    </a>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="<?php echo $form_item[$headings[$i]][0]['image']; ?>" rel="lightbox['<?php echo $image_colorbox; ?>']" title='<?php echo $form_item[$headings[$i]][0]['title']; ?>'>
                                                        <img align="left" src="<?php echo $form_item[$headings[$i]][0]['image']; ?>" width="50" height="50" alt="Record Images" title="Record Images" />
                                                    </a>
                                                    <?php
                                                    array_splice($form_item[$headings[$i]], 0, 1);
//                                                    echo '<pre>'; print_r($form_item[$headings[$i]]);die;
                                                    foreach ($form_item[$headings[$i]] as $multi_image) {
                                                        ?>
                                                        <a rel="lightbox['<?php echo $image_colorbox; ?>']" href="<?php echo $multi_image['image']; ?>" title="<?php echo $multi_image['title']; ?>" name = " ITU Government of Punjab - Pakistan">
                                                        </a>
                                                        <?php
                                                    }
                                                }
                                            } elseif ($headings[$i] == 'actions') {
                                                ?>
                                                
                                                <?php if ($app_id != 266) { ?>
                                                    <img src="<?= base_url() ?>assets/images/tableLink3.png" alt=""  title='Delete' class='delete_icon'/>
                                                    <a class="edit_color_box" href="<?= base_url() . 'form/edit_form_partial'; ?>">
                                                        <img src="<?= base_url() ?>assets/images/tableLink1.png" alt=""  title='Update' class='update_icosn'/>
                                                    </a>
                                                <?php } ?>
                                                <a href="<?= base_url() . 'single-record-map/' . $form_item['actions']; ?>" target="_blank" >
                                                    <img src="<?= base_url() ?>assets/images/viewmap.png" alt=""  title='Map View'  height="30px" width="30px"/>
                                                </a>

                                                <?php
                                            } else {
                                                echo '<span class="row_text">';
//                                                $data = ($headings[$i] != "Description") ? $form_item[$headings[$i]] : strtoupper($form_item[$headings[$i]]);
                                                echo strtoupper($form_item[$headings[$i]]);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <?php
                                }
                            }
                            ?>                            
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </form>
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
                },
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Tagging<?php echo " (Total : " . $total_records . ')'; ?>',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                },
                allowDecimals: false
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
<script type="text/javascript" charset="utf-8">
    jQuery('td input').hide();
    jQuery(document).ready(function() {
//        $('#filter_graph_type option[value="user"]').prop('selected', true);
        // Delete call here
        jQuery('.delete_icon').on('click', function(e) {
            if (confirm('Are you sure? You want to Delelte')) {

                var rowId_for_edit = jQuery(this).parents('td').parents('tr').attr('id');

                jQuery.ajax({
                    url: "<?= base_url() ?>form/delete_result",
                    data: {result_id: rowId_for_edit},
                    type: 'POST',
                    success: function(data) {
                        jQuery('#' + rowId_for_edit).hide();
                        jQuery(".success").text('Record Deleted Successfully ').show().fadeOut(7000); //=== Show Success Message==

                    },
                    error: function(data) {
                        console.log(data);

                    }
                });
            }

            else {
                return false;
            }
            e.preventDefault(); //=== To Avoid Page Refresh and Fire the Event "Click"===
        });


        jQuery(".image_colorbox").live('click', function(e) {
            var image_url = jQuery(this).attr('href');
            jQuery(this).colorbox({
                width: "55%",
                height: "65%",
                open: true,
                title: function() {
                    var link = '<?php echo base_url(); ?>';
                    var url = jQuery(".image_colorbox").attr('name');
                    var title = jQuery(".image_colorbox").attr('title');
                    return  title;
                }});
            e.preventDefault();
            return false;
        });

        jQuery(".edit_color_box").live('click', function(e) {


            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            var rowId = jQuery(this).parents('td').parents('tr').attr('id');
            var datum = 'form_id=' + id + '& form_result_id=' + rowId;
            jQuery(this).colorbox({
                width: "50%",
                height: "70%",
                open: true,
                data: datum,
            });
            e.preventDefault();
            return false;
        });

    });
    oTable = jQuery('#application-listing').dataTable({
        "jQueryUI": true,
        "paginationType": "full_numbers",
        "bServerSide": false,
        'iDisplayLength': 10,
    });


</script>
<style type="text/css">
    .applicationText{
        margin-bottom: 0px !important;
    }
    .applicationText a{
        position: relative !important;
        float: none !important;
    }
    .Category a{
        padding: 0px !important;
        width:0px !important;
    }
    .filter_class{
        color:#777777; display:table; padding-top:10px;
    }
    .Category input{
        display: none;
    }



    body{
        font-family:Arial, Helvetica, sans-serif; 
        font-size:13px;
    }
    .info, .success, .warning, .error, .validation {
        border: 1px solid;
        margin: 10px 0px;
        padding:15px 70px 15px 96px;
        background-repeat: no-repeat;
        background-position: 10px center;
    }

    .success {
        color: #4F8A10;
        background-color: #E5BF00;
        position: absolute;
        z-index: 11;
        margin: 0px 0px 11px 227px;
        display: none;
    }
    .tableContainer{
        overflow: auto;
    }


</style>
