<div style="width: 473px; margin: 0 0 0 67px">
    <?php
    $this->load->helper('form');
    echo form_open_multipart('form/edit_listview_partial', array('method' => 'post', 'id' => 'add_data_form'));
    ?>
    <?php if (empty($description)) { ?>
        <input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>" />
        <?php
    } else {
        ?>
        <input type="hidden" name="form_result_id" id="form_result_id" value="<?php echo $form_result_id; ?>" />
        <?php
        echo $description;
    }
    
    ?>
    <br><input type="submit" value="Update" id="update_data" />

</div>
<script type="text/javascript">
    var attribute_values = <?= $attribute_values ?>;
    jQuery(document).ready(function() {
        jQuery('#add_data_form').find('input,select').each(function() {
            var name = jQuery(this).attr('name');
            var type = jQuery(this).attr('type');
            var id = jQuery(this).attr('id');

            var skip = jQuery(this).attr('rel');
            if (skip == 'skip') {
                var html_id = jQuery(this).attr('class');
                jQuery('.' + html_id).remove();
            }
            jQuery.each(attribute_values, function(i, j) {
                if (type == 'checkbox') {

                    textName = name.replace(/[\][]/g, '');

                    if (textName == i) {
                        var temp = new Array();
                        var temp = j.split(",");
                        jQuery.each(temp, function(i) {
                            var splited_value = temp[i];
                            jQuery('input:checkbox[value="' + splited_value + '"]').attr('checked', true);
                        })
                    }
                }
                else if (type == 'radio') {
                    if (name == i) {
                        if (jQuery('#' + id).attr('value') == j)
                        {

                            jQuery('input:radio[value="' + j + '"]').attr('checked', true);
                        }
                    }
                } else if (type == 'text' || type == 'number') {
                    if (name == i) {
                        jQuery('#' + id + '-container input').val(j);
                    }
                } else {
                    if (name == i) {
                        jQuery('#' + id + ' option[value="' + j + '"]').prop('selected', true);
                    }
                }

            });
        });

        jQuery('#update_data').on('click', function(e) {
            var rowId_for_edit = '<?= $form_result_id; ?>';
            var data = jQuery("#add_data_form").serialize();
            jQuery.ajax({
                url: "<?= base_url() ?>form/edit_listview_partial",
                data: data,
                type: 'POST',
                success: function(data) {
                    jQuery('#' + rowId_for_edit).hide();
                    jQuery(".success").text('Record updated Successfully ').show().fadeOut(7000); //=== Show Success Message==
                    jQuery.fn.colorbox.close();
                },
                error: function(data) {
                    console.log(data);

                }
            });
        });

    });
    jQuery('#form-submit input').hide();
    jQuery('.ui-resizable').find('select').css('opacity', '1');
    jQuery('.selector span').css('display', 'none');
    jQuery('.radio span input').css('opacity', '1');
    jQuery('.checker span input').css('opacity', '1');
    jQuery('.latlangclass').css('display', 'none');
    jQuery('.cameraclass ').css('display', 'none');
    jQuery('.forweb input').attr('readonly', true);
    jQuery('.forweb').find('img').css('display', 'none');

    jQuery('.ui-resizable input[type=text]').attr('size', '39')
</script>
<style>
    #form-title {
        background-color: #999999;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        height: auto;
        margin: 0;
        text-align: center;
        width: 529px;
    }
    .checker span input{
        float: left;
        margin: 0px 0 0 -21px;
        /*display:inline;*/
    }
    .forweb input{
        margin-left: 52px;
    }
    .selector{
        margin: -20px 0px 0px 110px;
    }
    h2{
        color: #000000 !important;
        margin-top: -7px !important;
    }
    .radio span input{
        float: left;
        display: inline;
    }
    #add_data_form {
        border-color: #FFFFFF;
        margin-left: -20px;
    }
    #tabs-header{
        height: 34px;
    }
    #form-submit {
        display: none;
        padding-bottom: 0;
    }
    #form-title .container-fluid{
    	display:none;
    }
    .field select{
    width:100%;
    }
    .field{
    margin:20px;
    }
    
    #update_data{
    background: #2da5da none repeat scroll 0 0;
    border: medium none;
    color: #fff;
    cursor: pointer;
    float: right;
    margin-left: 10px;
    margin-right: 3px;
    outline: medium none;
    padding: 5px 20px;}
    label{
        width:270px;
        display: block;
    }

    .selector {
        margin: -20px 0 0 218px;
        width: 220px;
    }
</style>
<script>
// Words tabs
    var $tabs = jQuery("#add_data_form").tabs();
// Make tab names dropable
    var $tab_items = jQuery(".tabs li", $tabs).droppable({
        accept: ".field",
        //hoverClass: "ui-state-hover",
        tolerance: 'pointer',
        drop: function(event, ui) {
            var item = jQuery(ui.draggable);
            var drop_to_item_id = 'title-' + $(this).attr('id');
            var tab_id = jQuery('#' + drop_to_item_id).attr('rel');
            ui.draggable.remove();
            jQuery('#' + tab_id).append(item.context.outerHTML);
            jQuery('#' + ui.draggable.attr('id')).attr('style', 'left:auto;right:auto;position:relative;opacity:1')
        }
    });


    jQuery('ul.tabs a').each(function() {
        active_tab = jQuery(this).attr('rel');
        if (jQuery(this).hasClass('active'))
        {
            jQuery("#" + active_tab).show();
        }
        else
        {
            jQuery("#" + active_tab).hide();
        }
    });


    jQuery('ul.tabs a').live('click', function(e) {

        jQuery('a').removeClass('active');
        // Make the old tab inactive.
        jQuery('ul.tabs a').each(function() {
            active_tab = jQuery(this).attr('rel');
            jQuery("#" + active_tab).hide();
        });

        // Make the tab active.
        jQuery("#" + jQuery(this).attr('id')).addClass('active');
        jQuery("#" + jQuery(this).attr('rel')).show();

        // Prevent the anchor's default click action
        e.preventDefault();
    });
</script>