<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$exclude_array = array('created_datetime', 'actions', 'id', 'is_take_picture', 'Image', 'takepic');
$heads = "";
$this->load->helper('form');
echo form_open_multipart('form/getMapPartial', 'id=add_data_form');
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <tbody>
    <strong>
        <span style="font-size: 18px; text-align: center;color: #007600">Add New Map Record</span>
    </strong><hr>
    <?php
    if (!empty($filter_attribute)) {
        $column_number = 0;
        foreach ($filter_attribute as $filter_attribute_value) {
            $arraylist = array();
            $filter_data = array();
            $column_number++;
            if (in_array($filter_attribute_value, $headings)) {
                $filter_data = array();
                foreach ($form_for_filter as $key => $form_item) {

                    if (!empty($form_item[$filter_attribute_value])) {
                        if (!in_array($form_item[$filter_attribute_value], $filter_data)) {
                            $filter_data = array_merge($filter_data, array($form_item[$filter_attribute_value] => $form_item[$filter_attribute_value]));
                        }
                    }
                }
                $selected = !empty($selected_filter) ? $selected_filter : '';
                $arraylist = array_merge(array('' => 'Select Defualt'), $filter_data);
                echo form_hidden('form_id', $form_id);
                echo '<tr><td><strong>';
                echo 'Filter ' . ucwords(str_replace("_", " ", $filter_attribute_value));
                echo '</strong></td><td>';
                echo form_dropdown($filter_attribute_value, $arraylist, $selected, 'id="cat_filter" column_number="' . $column_number . '"') . '</br>';
                echo '</td></tr>';
            }
            $exclude_array[] = array_push($exclude_array, $filter_attribute_value);
        }
    }
    foreach ($headings as $heads) {
        if (!in_array($heads, $exclude_array)) {
            if ($heads == 'image') {
                echo '<tr><td><strong>';
                echo $heads .= ':</strong></td><td><input type="file" name="' . $heads . '"></td></tr>';
            } else {
                echo '<tr><td><strong>';
                echo $heads .= ':</strong></td><td><input type="text" name="' . $heads . '"></td></tr>';
            }
        }
    }
    $latlong = '<input type="hidden" name="Lat" value="' . $lat . '">
                <input type="hidden" name="Long" value="' . $long . '"><br>
                <input type="hidden" name="form_id" value="' . $form_id . '"><br>';
    echo $latlong;
    if (!empty($town_list_array)) {
        echo '<tr><td><strong>Town Filter : </strong></td>';
        $town_filter_list = array();
        $town_filter_list = array_merge(array('' => 'Select Town'), $town_list_array);
        $town_filter_selected = (!empty($town_filter_selected)) ? $town_filter_selected : "";
        echo '<td>';
        echo form_dropdown('town_filter', $town_filter_list, $town_filter_selected, 'id="town_filter"');
        echo '</td></tr><tr>';
        echo '<td><strong>Imei: </strong></td>';
        ?>
        <td>
            <select id="imei_no" name="imei_no">
                <option value=""></option>
            </select>
        </td>
        <?php
    }
    ?>
    <tr>

    <tr>
        <td><td>
            <input type='submit' value='Submit'>

        </td></tr>

</tbody>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
    $('#town_filter').live('change', function() {
        $("#imei_no > option").remove();
        var town_name = $(this).val();
        var form_id = "<?= $form_id ?>";
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>form/getImeiNo/" + form_id + "/" + town_name,
            success: function(imei_list)
            {
                $.each(imei_list, function(town, imei)
                {
                    var opt = $('<option />');
                    opt.val(town);
                    opt.text(imei);
                    $('#imei_no').append(opt);
                });
            }

        });

    });

</script>