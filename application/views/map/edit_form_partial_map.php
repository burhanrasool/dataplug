<?php
$this->load->helper('form');
echo form_open_multipart('form/edit_map_partial', array('method' => 'post', 'id' => 'add_data_form'));
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <tbody>
    <strong><span style="font-size: 18px; text-align: center;color: #007600">Results Update</span></strong><hr>
    <?php
    $total_headings = count($headings);
    $exclude_array = array('image', 'created_datetime', 'actions');
    $arraylist = array();
    if (!empty($filter_attribute)) {
        $column_number = 0;
        foreach ($filter_attribute as $filter_attribute_value) {

            $filter_data = array();
            $column_number++;
            if (!in_array($filter_attribute_value, $exclude_array)) {

                foreach ($form_for_filter as $key => $form_item) {

                    if (!empty($form_item[$filter_attribute_value])) {
                        if (!in_array($form_item[$filter_attribute_value], $filter_data)) {
                            $filter_data = array_merge($filter_data, array($form_item[$filter_attribute_value] => $form_item[$filter_attribute_value]));
                        }
                    }
                }
                $arraylist = $filter_data;
            }
        }
    }
    ?>
    <?php foreach ($locations as $form_item): ?>
        <?php
        for ($i = 0; $i < $total_headings; $i++) {

            if ($headings[$i] != 'is_take_picture') {
                if (!empty($filter_attribute)) {
                    $column_number = 0;
                    foreach ($filter_attribute as $filter_attribute_value) {
                        $column_number++;
                        if (!in_array($filter_attribute_value, $exclude_array)) {
                            if (in_array($filter_attribute_value, $headings)) {
//                                        
                                $selected = !empty($selected_filter) ? $selected_filter : '';
                                if (!in_array($headings[$i], $exclude_array)) {
                                    echo '<tr><td> <strong>';
                                    echo ucwords(str_replace("_", " ", $filter_attribute_value));
                                    echo ' </strong></td><td>';
                                    echo form_dropdown($filter_attribute_value, $arraylist, $form_item[$headings[$i]], 'id="cat_filter" ') . '</br>';
                                    echo '</td></tr>';
                                }
                                $exclude_array[] = array_push($exclude_array, $filter_attribute_value);
                            } else {
                                $selected = !empty($selected_filter) ? $selected_filter : '';
                                echo '<tr><td> <strong>';
                                echo ucwords(str_replace("_", " ", $filter_attribute_value));
                                echo ' </strong></td><td>';
                                echo form_dropdown($filter_attribute_value, $arraylist, '', 'id="cat_filter" ') . '</br>';
                                echo '</td></tr>';
                                $exclude_array[] = array_push($exclude_array, $filter_attribute_value);
                            }
                        }
                    }
                }
                ?>
                <div class="row">
                    <?php if (!in_array($headings[$i], $exclude_array)) { ?>
                        <tr><td>
                                <label for="d1_textfield">
                                    <strong>
                                        <?php
                                        echo ucwords(str_replace("_", " ", $headings[$i])) . ' : ';
                                        ?>
                                    </strong>
                                </label>
                            </td> <td>
                                <input type="text" size="50"  name="<?php echo $headings[$i]; ?>" value='<?php echo $form_item[$headings[$i]]; ?>'>
                            </td> </tr>
                    <?php }
                    ?>
                </div>
                <?php
            }
        }
    endforeach;
    ?>
    <tr><td></td><td>
            <?php
            if (!empty($image)) {
                ?>
                <div>
                    <!--                    <div style="margin:0 0 35px 135px">
                    <?php // if (file_exists(FCPATH . "assets/images/data/form-data/" . $image)): ?>
                                                <a href='javascript:void(0)' >
                                                    <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>" value='360' title='Rotate 360 Degree' id='degree_image' onclick="rotate_image(this)" >
                                                </a>
                                                <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>"  title='Already Rotated 360 Degree' id='degree_image_notclickable' style="display: none" >
                    <?php
//                        endif;
                    ?>
                                        </div>-->
                    <div style="margin: 0 0 0 77px;">
                        <img src="<?php echo $image; ?>" alt="" height="150px" width="150px" id ='colorbox_small_image' />
                    </div>
                    <div style="margin:-85px 0 -31px 26px;">
                        <?php if (file_exists(FCPATH . "assets/images/data/form-data/" . $image)): ?>
                            <a href='javascript:void(0)' >
                                <img src="<?= base_url() . 'assets/images/degree_270.png'; ?>" value='90' title='Rotate 270 Degree' id='degree_image' onclick="rotate_image(this)" >
                            </a>
                            <img src="<?= base_url() . 'assets/images/degree_270.png'; ?>"  title='Already Rotated 270 Degree' id='degree_image_notclickable' style="display: none" >
                            <?php
                        endif;
                        ?>
                    </div>
                    <div style="margin:0 0 0 250px">
                        <?php if (file_exists(FCPATH . "assets/images/data/form-data/" . $image)): ?>
                            <a href='javascript:void(0)' >
                                <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>" value='270' title='Rotate 90 Degree' id='degree_image' onclick="rotate_image(this)" >
                            </a>
                            <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>"  title='Already Rotated 90 Degree' id='degree_image_notclickable' style="display: none" >
                            <?php
                        endif;
                        ?>
                    </div>

                    <div style="margin:56px 0 -9px 139px">
                        <?php if (file_exists(FCPATH . "assets/images/data/form-data/" . $image)): ?>
                            <a href='javascript:void(0)'  >
                                <img src="<?= base_url() . 'assets/images/degree_180.png'; ?>" value='180' title='Rotate 180 Degree' id='degree_image' onclick="rotate_image(this)" >
                            </a>
                            <img src="<?= base_url() . 'assets/images/degree_180.png'; ?>"  title='Already Rotated 180 Degree' id='degree_image_notclickable' style="display: none" >
                            <?php
                        endif;
                        ?>
                    </div>
                </div>

                <?php
            }
            ?>
        </td></tr>
    <tr>
    <div class="row">
        <input type="hidden" name="form_result_id" value="<?php echo $form_result_id; ?>">
        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
        <?php
        if ($this->acl->hasSuperAdmin()) {
            ?>
            <label for="d1_textfield">
                <strong><td><strong>Image : </strong></td></strong>
            </label>
            <div>
                <td><div id="base"></div>

                    <input type="file" name="locality_image" id="userfile" accept="*.jpg" onchange="check_file()"/>


                </td>
            </div>
            <?php
        }
        ?>
    </div>
</tr><tr>
    <td><td>
        <input type='button' value='Update' id="submit_edit">

    </td></tr>

</tbody>
</table>
<?php echo form_close(); ?>
<script type="text/javascript">
                            jQuery(document).ready(function() {
                                var file
                                function readImage(input) {
                                    if (input.files && input.files[0]) {
                                        var FR = new FileReader();
                                        FR.onload = function(e) {

                                            file = e.target.result;
                                        };
                                        FR.readAsDataURL(jQuery('input#userfile')[0].files[0]);
                                    }

                                }
                                jQuery("#userfile").change(function() {
                                    readImage(this);
                                });
                                jQuery('#submit_edit').on('click', function(e) {

                                    var rowId_for_edit = '<?= $form_result_id; ?>';
                                    var data = jQuery("#add_data_form").serialize() + '&image=' + file;
                                    jQuery.ajax({
                                        url: "<?= base_url() ?>form/edit_map_partial",
                                        data: data,
                                        type: 'POST',
                                        success: function(data) {

                                            for (i = 0; i < locations.length; i++) {
                                                if (locations[i][0] == '<?= $lat ?>' && locations[i][1] == '<?= $long ?>') {
                                                    markers[i].setMap(null);
                                                }
                                            }
                                            jQuery('.gm-style-iw').next().click()
                                            jQuery(".success").text('Record updated Successfully ').show().fadeOut(7000); //=== Show Success Message==
                                            jQuery.fn.colorbox.close();
                                        },
                                        error: function(data) {
                                            console.log(data);

                                        }
                                    });
                                });

                            });

                            function rotate_image(obj) {
                                jQuery(obj).hide();
                                jQuery(obj).parent().next().show();
//                                console.log(jQuery(obj).parent().attr('click'));
                                var image = '<?= $image; ?>';
//                            alert(jQuery(obj).parent().disabled = true);
                                var degree = jQuery(obj).attr('value');
                                if (degree == 270) {
                                    jQuery('#colorbox_small_image').css({
                                        "-webkit-transform": "rotate(90deg)",
                                        "-moz-transform": "rotate(90deg)",
                                        "transform": "rotate(90deg)" /* For modern browsers(CSS3)  */
                                    })
                                }
                                if (degree == 180) {
                                    jQuery('#colorbox_small_image').css({
                                        "-webkit-transform": "rotate(180deg)",
                                        "-moz-transform": "rotate(180deg)",
                                        "transform": "rotate(180deg)" /* For modern browsers(CSS3)  */
                                    })
                                }
                                if (degree == 90) {
                                    jQuery('#colorbox_small_image').css({
                                        "-webkit-transform": "rotate(270deg)",
                                        "-moz-transform": "rotate(270deg)",
                                        "transform": "rotate(270deg)" /* For modern browsers(CSS3)  */
                                    })
                                }
                                if (degree == 360) {
                                    jQuery('#colorbox_small_image').css({
                                        "-webkit-transform": "rotate(360deg)",
                                        "-moz-transform": "rotate(360deg)",
                                        "transform": "rotate(360deg)" /* For modern browsers(CSS3)  */
                                    })
                                }
                                if (degree != "") {
                                    jQuery.ajax({
                                        url: "<?= base_url() ?>form/rotateImage",
                                        data: {image: image, degree: degree},
                                        type: 'POST',
                                        success: function(data) {
                                        },
                                        error: function(data) {
                                            console.log(data);

                                        }
                                    });
                                }
                            }
                            function check_file() {
                                str = document.getElementById('userfile').value.toUpperCase();
                                suffix = ".jpg";
                                suffix2 = ".JPG";
                                if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                                        str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                                    alert('File type not supported, only (.png) files allowed.');
                                    document.getElementById('userfile').value = '';
                                }
                            }
</script>
