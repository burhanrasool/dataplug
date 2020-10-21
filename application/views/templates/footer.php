</div>
<div style="clear:both;"><div class="footer"><?php echo FOOTER_TEXT;?></div>

<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/modernizr.custom.js"></script>
<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/lightbox-2.6.min.js" ></script>
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.columnFilter.js"></script>


<script type="text/javascript" src="<?= base_url() ?>assets/js/ddslick.js"></script>


<!--Common pages javascript-->
<?php
//if (isset($active_tab) && $active_tab != 'app_form_build_public') {
    ?>
    <script src="<?= base_url() ?>assets/js/cbpTooltipMenu.min.js"></script>         
    <script>var menu = new cbpTooltipMenu(document.getElementById('cbp-tm-menu'));</script>
<?php //} ?>
<!--update page javascript-->
<?php
if (isset($active_tab) && $active_tab == 'form_update') {
    ?>
    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/ttw.formbuilder.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/custom/form_update.js"></script>
    
    <?php
} elseif (isset($active_tab) && $active_tab == 'app_form_build') {//for landing page, Icon of forms places her landing_page.js
    ?>

    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/ttw.formbuilder.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/custom/landing_page.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'app_index') {
    ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom/app_index.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'complaint_edit') { ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom/complaint_edit.js"></script>
<?php
} elseif (isset($active_tab) && $active_tab == 'complaint_add') { ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom/complaint_add.js"></script>
<?php
} elseif (isset($active_tab) && $active_tab == 'complaint_index') {
    ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom/complaint_index.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'api_index') {
    ?>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom/api_index.js"></script>

<?php
} elseif (isset($active_tab) && $active_tab == 'api_create_url') {
    ?>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/custom/api_create_url.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'api_app_url') {
    ?>

    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script>
        jQuery(function() {
            jQuery("#from_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#to_date").datepicker({dateFormat: 'yy-mm-dd'});
        });
        
        function check_date_validity() {
            var date_from = jQuery('#from_date').val();
            var date_to = jQuery('#to_date').val();
            if (new Date(date_from).getTime() > new Date(date_to).getTime()) {
                jQuery('#to_date').val('');
                alert('Invalid Date selection');
                return false;
            }
            return true;
        }
        function clear_field(obj) {
            jQuery(obj).val("");
        }
        
        var app_secret = $('#app_secret').val();
        var app_id = $('#app_id').val();
        var bas_url = '<?= base_url() ?>api/sendToRemoteServer?app_id='+app_id+'&security_token='+app_secret;
        
        var para_ex='&from_date_stamp=&to_date_stamp=';

        $('#urldiv').html(bas_url+para_ex);
        $('#checkurl').attr('href',bas_url+para_ex);
    
        $('#urldiv').click(function () {
            $(this).focus();
        });
        $('#from_date').change(function () {
            check_date_validity();
            var para_from = '';
            if($(this).val()!=''){
                para_ex = '&from_date_stamp='+$(this).val();  
            }
            else{ 
                var para_ex='&from_date_stamp='
                $('#checkurl').attr('href',bas_url+para_ex);
                $('#parentvalue').val('');
                $("#parentvalue option:first").attr('selected','selected');
                return false;
            }
            
            if($('#to_date').val()!='')
            {
                para_ex += '&to_date_stamp='+$('#to_date').val();
            }else{
                para_ex += '&to_date_stamp=';
            }

            $('#checkurl').attr('href',bas_url+para_ex);
            $('#urldiv').html(bas_url+para_ex);
        });
        $('#to_date').change(function () {
            check_date_validity();
            var para_from = '';
            if($(this).val()!=''){
                para_ex = '&to_date_stamp='+$(this).val();  
            }
            else{ 
                var para_ex='&to_date_stamp='
                $('#checkurl').attr('href',bas_url+para_ex);
                $('#parentvalue').val('');
                $("#parentvalue option:first").attr('selected','selected');
                return false;
            }
            
            if($('#from_date').val()!='')
            {
                para_ex = '&from_date_stamp='+$('#from_date').val()+para_ex;
            }
            else{
                para_ex = '&from_date_stamp='+para_ex;
            }

            $('#checkurl').attr('href',bas_url+para_ex);
            $('#urldiv').html(bas_url+para_ex);

        });
    </script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'app_form_build_public') {
    ?>
    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/ttw.formbuilder.js"></script>
    <script>


        $(".field").sortable({
            tolerance: 'pointer',
            cursor: 'move',
            forcePlaceholderSize: true,
            dropOnEmpty: true,
            //connectWith: 'ol.word-list',
            placeholder: "ui-state-highlight"
        }).disableSelection();
        // Words tabs
        var $tabs = $("#form-preview").tabs();
        // Make tab names dropable
        var $tab_items = $(".tabs li", $tabs).droppable({
            accept: ".field",
            //hoverClass: "ui-state-hover",
            tolerance: 'pointer',
            drop: function(event, ui) {
                var item = $(ui.draggable);
                var drop_to_item_id = 'title-' + $(this).attr('id');
                var tab_id = $('#' + drop_to_item_id).attr('rel');
                ui.draggable.remove();
                $('#' + tab_id).append(item.context.outerHTML);
                $('#' + ui.draggable.attr('id')).attr('style', 'left:auto;right:auto;position:relative;opacity:1')
            }
        });
        $('ul.tabs a').each(function() {
            active_tab = $(this).attr('rel');
            if ($(this).hasClass('active'))
            {
                $("#" + active_tab).show();
            }
            else
            {
                $("#" + active_tab).hide();
            }
        });
        $('ul.tabs a').live('click', function(e) {
            $('#add_after_widget').val('');
            $('a').removeClass('active');
            // Make the old tab inactive.
            $('ul.tabs a').each(function() {
                active_tab = $(this).attr('rel');
                $("#" + active_tab).hide();
            });
            // Make the tab active.
            $("#" + $(this).attr('id')).addClass('active');
            $("#" + $(this).attr('rel')).show();
            // Prevent the anchor's default click action
            e.preventDefault();
        });
        
        var $page_items = $(".pages li", $tabs).droppable({
            accept: ".field",
            //hoverClass: "ui-state-hover",
            tolerance: 'pointer',
            drop: function(event, ui) {
                var item = $(ui.draggable);
                var drop_to_item_id = 'title-' + $(this).attr('id');
                var page_id = $('#' + drop_to_item_id).attr('rel');
                ui.draggable.remove();
                $('#' + page_id).append(item.context.outerHTML);
                $('#' + ui.draggable.attr('id')).attr('style', 'left:auto;right:auto;position:relative;opacity:1')
            }
        });
        $('ul.pages a').each(function() {
            active_page = $(this).attr('rel');
            if ($(this).hasClass('active'))
            {
                $("#" + active_page).show();
            }
            else
            {
                $("#" + active_page).hide();
            }
        });
        $('ul.pages a').live('click', function(e) {
            $('#add_after_widget').val('');
            $('a').removeClass('active');
            // Make the old tab inactive.
            $('ul.pages a').each(function() {
                active_page = $(this).attr('rel');
                $("#" + active_page).hide();
            });
            // Make the tab active.
            $("#" + $(this).attr('id')).addClass('active');
            $("#" + $(this).attr('rel')).show();
            // Prevent the anchor's default click action
            e.preventDefault();
        });

        $('#remove-option-all').click(function(e) {
            $('.remove-option').trigger('click');
        });

        var ci_base_url = '<?php echo base_url(); ?>';
        $(document).ready(function() {

            $('#cboxMiddleLeft').css('background', 'none')
            $('#cboxTopCenter').css('background', 'none')
            $('#cboxTopRight').css('background', 'none')
            $('#cboxMiddleRight').css('background', 'none')
            $('#cboxBottomCenter').css('background', 'none')
            $('#cboxTopLeft').css('background', 'none')
            $('#cboxBottomLeft').css('background', 'none')
            $('#cboxBottomRight').css('background', 'none')

            setTimeout(function() {
                $("#add-form-title li").trigger('click');
            }, 500);
//            setTimeout(function() {
//                $('#add-form-submit li').trigger('click');
//            }, 500);
            setTimeout(function() {
                $("#screen_size").trigger('change');
            }, 500);
            $('.selector').change(function() {
                var curdiv = $(this).attr('id');
                var display_value = $('#' + curdiv + ' select option:selected').attr('display_value');
                if (display_value == '')
                {
                    display_value = $('#' + curdiv + ' select option:selected').attr('value');
                }
                $('#' + curdiv + ' span').html(display_value);
            });
            var file_app;
            var file_form;
            function readImageForm(input) {
                if (input.files && input.files[0]) {
                    var FR = new FileReader();
                    FR.onload = function(e) {
                        file_form = e.target.result;
                    };
                    FR.readAsDataURL(jQuery('input#formfile')[0].files[0]);
                }

            }

            function readImageApp(input) {
                if (input.files && input.files[0]) {
                    var FR = new FileReader();
                    FR.onload = function(e) {
                        file_app = e.target.result;
                    };
                    FR.readAsDataURL(jQuery('input#appfile')[0].files[0]);
                }

            }
            jQuery("#formfile").change(function() {
                readImageForm(this);
            });
            jQuery("#appfile").change(function() {
                readImageApp(this);
            });
            jQuery('#saveFormAlone').on('click', function(e) {
                var form_title = $('#form-title').find('h2').html();
                $('#form_name').val(form_title);
                var data = $('#form-builder').html();
                $('#htmldesc').val(data);
                var data = $("#form_edit").serialize() + '&file_form=' + file_form + '&file_app=' + file_app;
                jQuery.ajax({
                    url: "<?= base_url() ?>form/appbuilder/1",
                    data: data,
                    type: 'POST',
                    success: function(response) {
                        response = $.parseJSON(response);
                        if (response.status == 1) {
                            window.location = "<?php echo base_url() . ''; ?>";
                        }
                        else if (response.status == 3) {
                            //jQuery(".flashdiv-success #success").text('Form Saved Succesfully');

                            $.colorbox({
                                innerWidth: 485,
                                innerHeight: 316,
                                href: '<?php echo base_url() . 'users/login_popup' ?>'
                            });
                        } else if (response.status == 2) {
                            jQuery(".flashdiv-warning").hide();
                            jQuery(".flashdiv-success").hide();
                            jQuery(".flashdiv-error #error").text('Application name is missing.');
                        }




                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
        function check_appfile() {
            str = document.getElementById('appfile').value.toUpperCase();
            suffix = ".png";
            suffix2 = ".PNG";
            if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                    str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                alert('File type not supported, only (.png) files allowed.');
                $('#img_app_icon').attr('src', '<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png'; ?>');
                document.getElementById('appfile').value = '';
            }
            else
            {
                readURLapp(document.getElementById('appfile'));
            }
        }

        function readURLapp(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img_app_icon').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function check_formfile() {
            str = document.getElementById('formfile').value.toUpperCase();
            suffix = ".png";
            suffix2 = ".PNG";
            if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                    str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                alert('File type not supported, only (.png) files allowed.');
                $('#img_form_icon').attr('src', '<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/default_1.png'; ?>');
                document.getElementById('formfile').value = '';
            }
            else
            {
                readURLform(document.getElementById('formfile'));
            }
        }
        function readURLform(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img_form_icon').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        function linkfield(element_id)
        {
            var selected_value = $('#linked_field-' + element_id).val();
            if (selected_value == 'custom')
            {
                $('#div_caption-' + element_id).empty();
                $('#div_caption-' + element_id).append('<input type="text" rel="norepeat" name="caption-' + element_id + '" placeholder="Enter image title here"/>');
            }
            else {
                $('#div_caption-' + element_id).empty();
                $('#div_caption-' + element_id).append('<input type="text" readonly rel="norepeat" name="caption-' + element_id + '" value="' + selected_value + '"/>');
            }
        }

        function loadselect() {

            var selectbox = $("#field-api-setting").attr('select_id');
            var selectbox_value = $("#" + selectbox).attr('api_url');
            if (selectbox_value == '')
            {
                alert("Please enter API url for getting options");
                return false;
            }

            var url_cross_domain = encodeURI($("#field-api-setting").val());
            var url_same_domain = ci_base_url + 'api/getOptions';
            $.ajax({
                url: url_same_domain,
                data: {'url_cross_domain': url_cross_domain},
                type: 'POST',
                async: true,
                dataType: 'json',
                success: function(response) {

                    var options = JSON.stringify(response);
                    var options = JSON.parse(options);
                    if (options == null)
                    {
                        alert('Error: URL not compatible.');
                        return false;
                    }

                    if (options.options.length > 0)
                    {
                        $('#' + selectbox).empty();
                    }
                    $('#' + selectbox).prev().html('Please Select');
                    $('#' + selectbox).append('<option id="' + selectbox + "-0" + '" value="" display_value="Please Select" parent_value="" data-alternative-spellings="" field_setting="main">Please Select</option>');
                    for (var i = 0; i < options.options.length; i++) {
                        var strval = options.options[i].value;
                        var parentval = options.options[i].parent_value;
                        var strdispval = options.options[i].display_value;
                        var option_id = selectbox + "-" + (i + 1);
                        strval.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                        strdispval.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                        $('#' + selectbox).append('<option id="' + option_id + '" value="' + strval + '" display_value="' + strdispval + '"  parent_value="' + parentval + '" data-alternative-spellings="' + options.options[i].id + '" field_setting="main">' + strdispval + '</option>');
                    }
                    alert('New options has been added successfully.');
                },
                error: function(e) {
                    alert('Error: URL not compatible.');
                }
            });
        }


    </script>
    <script>
        $(document).ready(function() {
            //initilize the java object for android app
            $('.selector').change(function() {
                var curdiv = $(this).attr('id');
                $('#' + curdiv + ' span').html($('#' + curdiv + ' select').val());
            });
        });
        $(window).load(function() {

            $('.loading-indicator').hide();
        });</script>
    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/common.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'users-listing') {
    ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {

            jQuery('.delete_user').live('click', function() {
                if (confirm('Are you sure you want to delete this User?')) {
                    var user_id = jQuery(this).attr('user_id');
                    jQuery.ajax({
                        url: "<?= base_url() ?>users/deleteuser/" + user_id,
                        type: 'POST',
                        success: function(data) {
                            window.location.reload();
                        },
                    });
                } else {
                    return true;
                }
            });
            oTable = $('#application-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": false,
                        "aTargets": [-1] // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers"
            });
        });
        $(document).mouseup(function()
        {
            $(".edit_status_box").hide();
            $(".text_status").show();
        });
        $(".edit_status_box").mouseup(function()
        {
            return false;
        });
        //set status
        $(".edit_status").click(function() {
            var ID = $(this).attr('id');
            $("#edit_status_" + ID).hide();
            $("#edit_status_input_" + ID).show();
        }).change(function() {
            var ID = $(this).attr('id');
            var status = $("#edit_status_input_" + ID).val();
            var dataString = 'id=' + ID + '&status=' + status;
            if (status.length > 0)
            {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>users/changestatus",
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        if (status == '1')
                            var changestatus = 'Active';
                        else
                            var changestatus = 'Inactive';
                        $("#edit_status_" + ID).html(changestatus);
                    }
                });
            }
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'department-index') {
    ?>
    <script>
        $('#add_dept').click(function() {
            $.colorbox({
                innerWidth: 485,
                innerHeight: 325,
                href: '<?= base_url() ?>new-department'
            });
        });</script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('.delete_department').live('click', function() {
                if (confirm('Are you sure you want to delete this Department?')) {
                    var department_id = jQuery(this).attr('department_id');
                    $.ajax({
                        url: "<?= base_url() ?>department/delete/" + department_id,
                        type: 'POST',
                        success: function(data) {
                            window.location.reload();
                        },
                    });
                } else {
                    return true;
                }
            });
            
            oTable = $('#application-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": false,
                        "aTargets": [-1, 1], // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers"
            });
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'users-add') {
    ?>
    <script>

        $(document).ready(function() {

            $('#department_id').trigger('change');
        });
        $('#department_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#group_id > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>users/getgroups/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, type) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(type);
                        $('#group_id').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'users-edit') {
    ?>
    <script>

        $(document).ready(function() {

            $('#department_id').trigger('change');
        });
        $('#department_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#group_id > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            var group_id = <?php echo $group_id; ?>;
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>users/getgroups/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, type) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(type);
                        $('#group_id').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                    $('#group_id').val(group_id);
                }

            });
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'groups-listing') {
    ?>



    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            oTable = $('#application-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": false,
                        "aTargets": [-1] // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers"
            });
        });</script>

    <?php
} elseif (isset($active_tab) && $active_tab == 'app-users') {
    ?>

    <script>

        $(document).ready(function() {

            $('#imei_no').keydown(function(event) {
                // Allow special chars + arrows 
                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9
                        || event.keyCode == 27 || event.keyCode == 13
                        || (event.keyCode == 65 && event.ctrlKey === true)
                        || (event.keyCode >= 35 && event.keyCode <= 39)) {
                    return;
                } else {
                    // If it's not a number stop the keypress
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                        event.preventDefault();
                    }
                }
            });
            $('#delete_user').live('click', function() {
                if (confirm('Are you sure you want to delete this User?')) {
                    var user_id = $(this).attr('user_id');
                    $.ajax({
                        url: "<?= base_url() ?>users/deleteAppUser/" + user_id,
                        type: 'POST',
                        success: function(data) {
                            window.location.reload();
                        }
                    });
                } else {
                    return true;
                }
            });
        });
        $(document).ready(function() {
            oTable = $('#application-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": false,
                        "aTargets": [-1] // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers",
                'iDisplayLength': 25,
            });
            jQuery('#delete_app').live('click', function() {
                if (confirm('Are you sure you want to delete this App?')) {
                    var app_id = jQuery(this).attr('app_id');
                    jQuery.ajax({
                        url: "<?= base_url() ?>app/delete/" + app_id,
                        type: 'POST',
                        success: function(data) {
                            window.location.reload();
                        },
                    });
                } else {
                    return true;
                }
            });
        });
        
        $(document).ready(function() {

            $('.appusersajaxtable').dataTable( {

                "lengthMenu": [[25, 50, 100], [25, 50, 100]]
                ,"processing": true
                ,"serverSide": true
                ,"sPaginationType": "full_numbers"
                ,"bJQueryUI":true
//                    ,"sAjaxDataProp": "aaData"

                ,"aoColumns":[
                    {"mDataProp":"app_id","bSortable": true}
                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                    ,{"mDataProp":"deoartment_id","bSortable":true}
                    <?php } ?>
                    ,{"mDataProp":"view_id","bSortable": false}
                    ,{"mDataProp":"name","bSortable": true}
                    ,{"mDataProp":"district","bSortable": true}
                    ,{"mDataProp":"town","bSortable": true}
                    ,{"mDataProp":"imei_no","bSortable": true}
                    ,{"mDataProp":"cnic","bSortable": true}
                    ,{"mDataProp":"mobile_number","bSortable": true}
                    ,{"mDataProp":"login_user","bSortable": false}
                    ,{"mDataProp":"login_password","bSortable": false}
                    ,{"mDataProp":"action","bSortable": false}

                ]

                ,"sAjaxSource": "<?php echo base_url(); ?>app/ajaxappusers"
                ,"sAjaxDataProp": "aaData"




            } );
            $('.unsavedactivitiesajaxtable').dataTable( {

                "lengthMenu": [[25, 50, 100], [25, 50, 100]]
                ,"processing": true
                ,"serverSide": true
                ,"sPaginationType": "full_numbers"
                ,"bJQueryUI":true
//                    ,"sAjaxDataProp": "aaData"

                ,"aoColumns":[
                    {"mDataProp":"activity_id","bSortable": true}
                    ,{"mDataProp":"app_id","bSortable": false}
                    ,{"mDataProp":"app_name","bSortable": true}
                    ,{"mDataProp":"form_id","bSortable": true}
                    ,{"mDataProp":"form_name","bSortable": true}
                    ,{"mDataProp":"imei_no","bSortable": true}                   
                    ,{"mDataProp":"dateTime","bSortable": true}
                    ,{"mDataProp":"error","bSortable": false}
                    ,{"mDataProp":"form_data","bSortable": false}

                ]

                ,"sAjaxSource": "<?php echo base_url(); ?>form/unsaved_activities_ajax"
                ,"sAjaxDataProp": "aaData"




            } );



            $('#department_id').trigger('change');
            $('#department_id_import').trigger('change');
        });
        
        $('#department_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#app_id > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getapps/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#app_id').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                    $('#app_id').trigger('change');
                }

            });
        });
        $('#app_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#view_id > option").remove(); //first of all clear select items
            var app_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getappviews/" + app_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    var opt = $('<option />'); // here we're creating a new select option with for each city
                    opt.val('0');
                    opt.text("Default View");
                    $('#view_id').append(opt);
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#view_id').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });
        });
        
        $('#department_id_import').change(function() { //any select change on the dropdown with id country trigger this code
            $("#app_id_import > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getapps/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#app_id_import').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                    $('#app_id_import').trigger('change');
                }

            });
        });
        $('#app_id_import').change(function() { //any select change on the dropdown with id country trigger this code
            $("#view_id_import > option").remove(); //first of all clear select items
            var app_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getappviews/" + app_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    var opt = $('<option />'); // here we're creating a new select option with for each city
                    opt.val('0');
                    opt.text("Default View");
                    $('#view_id_import').append(opt);
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#view_id_import').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });
        });

        </script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'app-users-views') {
    ?>
    <script>

        $(document).ready(function() {
            $('.delete_views').live('click', function() {
                if (confirm('Are you sure you want to delete this View?')) {
                    var view_id = jQuery(this).attr('view_id');
                    $.ajax({
                        url: "<?= base_url() ?>app/appusersviewdelete/" + view_id,
                        type: 'POST',
                        success: function(data) {
                            window.location.reload();
                        },
                    });
                } else {
                    return true;
                }
            });

            $('#department_id').trigger('change');
            oTable = $('#application-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": false,
                        "aTargets": [-1] // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers",
                'iDisplayLength': 25,
            });
        });
        $('#department_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#app_id > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getapps/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#app_id').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'groups-add') {
    ?>
    <script>

        $(document).ready(function() {
            $('#department_id').trigger('change');
        });
        $('#department_id').change(function() {
            var department = $(this).val();
            if (department == 'new')
            {
                $('#dep_name').show();
            } else
            {
                $('#dep_name').hide();
                $('#department_name').val('');
            }
        });</script>

    <?php
} elseif (isset($active_tab) && $active_tab == 'form_results') {
    ?>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

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
            
                        jQuery(".open_settings").click(function(){
                var tab_id=jQuery('#open_settings').val();
            jQuery.colorbox({
                open: true,
                width: '90%',
                height: '100%',

                iframe:true,
                scrolling: false,
                href: '<?php echo base_url() . 'application-setting/'.$app_id; ?>'+'/'+tab_id,
                onClosed:function(){parent.location.reload();}
            });
            });
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
                        jQuery('#overlay_loading').hide();
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
        
        
        
        jQuery(document).mouseup(function()
        {
            jQuery(".edit_activity_status_box").hide();
            jQuery(".text_activity_status").show();
        });
        $(".edit_activity_status_box").mouseup(function()
        {
            return false;
        });
        //set status
        jQuery(".edit_activity_status").click(function() {
            var ID = jQuery(this).attr('id');
            jQuery("#edit_activity_status_" + ID).hide();
            jQuery("#edit_activity_status_input_" + ID).show();
        }).change(function() {
            var ID = jQuery(this).attr('id');
            var form_id = jQuery(this).attr('form_id');
            var activity_status = jQuery("#edit_activity_status_input_" + ID).val();
            var dataString = 'id=' + ID + '&activity_status=' + activity_status + '&form_id=' + form_id;
            //if (activity_status.length > 0)
            //{
                jQuery.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>form/changestatusrecord",
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        if (activity_status == 'rejected')
                            var changestatus = 'Rejected';
                        else if (activity_status == 'approved')
                            var changestatus = 'Approved';
                        else
                            var changestatus = 'Pending';
                        jQuery("#edit_activity_status_" + ID).html(changestatus);
                    }
                });
            //}
        });
        
        

    </script>

        <script>
            jQuery(document).ready(function() {
                jQuery('#changed_category').val(jQuery('#filter').val());
            })
        </script>

    <script type="text/javascript">
//         var templist = [];
//         jQuery('#form_descrip').find('input, textarea, select').each(function() {
//             if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
//             {
//                 if (jQurl: form.attr( 'action' ),uery(this).attr('name') != undefined) {
//                     var field_name = jQuery(this).attr('name');
//                     field_name = field_name.replace('[]', '');
//                     var skip = jQuery(this).attr('rel');
//                     var type = jQuery(this).attr('type');
                    var selected = '<?php //echo $filter; ?>';
//                     //if (type != 'text' && type != 'hidden') {

//                     if (jQuery.inArray(field_name, templist) == '-1')
//                     {
//                         var field_name_display = field_name;
//                         templist.push(field_name);
//                         if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                                 //                    if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                                 {
//                                     field_name_display.replace(/[_\W](^\s+|\s+$|(.*)>\s*)/g, "");
//                                     field_name_display = field_name_display.replace(/_/g, ' ');
//                                     field_name_display = capitalize_first_letter(field_name_display);
//                                     //field_name_display
//                                     if (selected == field_name)
//                                     {
//                                         field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                                         jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
//                                     }
//                                     else {
//                                         field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                                         jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
//                                     }
//                                 }
//                     }
//                     //}
//                 }
//             }
//         });
//         if (jQuery('#filter').val() == null) {
//             jQuery('#filter').append('<option value="version_name" >version name</option>');
//         }
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

        jQuery(document).ready(function() {


            jQuery("#comments_adding").live('click', function(e) {

                var comment_text = jQuery('#comment_text').val();
                if (comment_text.trim() == '')
                {
                    jQuery('#comment_saved').hide();
                    jQuery('#comment_error').show();
                    return false;
                }
                var app_id = '<?php echo $app_id ?>';
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

        });
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




        //        jQuery("#form_list").multipleSelect({
    //            filter: true,
    //            width: 200,
    //            placeholder: "Please select"
    //        });
    //        $("#form_list").searchable({
    //            maxListSize: 100, // if list size are less than maxListSize, show them all
    //            maxMultiMatch: 50, // how many matching entries should be displayed
    //            exactMatch: false, // Exact matching on search
    //            wildcards: true, // Support for wildcard characters (*, ?)
    //            ignoreCase: true, // Ignore case sensitivity
    //            latency: 200, // how many millis to wait until starting search
    //            warnMultiMatch: 'top {0} matches ...', // string to append to a list of entries cut short by maxMultiMatch 
    //            warnNoMatch: 'No matches', // string to show in the list when no entries match
    //            zIndex: 'auto'							// zIndex for elements generated by this plugin
    //        });
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
                    jQuery('#overlay_loading').show();
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

                            jQuery(window).load(function() {

                                jQuery('#overlay_loading').hide();
                            });
                            /*
                             * Loading wait status for map
                             */
                            loading_image();
                            function loading_image() {
                                jQuery(function() {

                                    var docHeight = jQuery(document).height();
                                    jQuery("body").append('<div  id="overlay_loading" title="Please Wait while the page loads">\n\
        <img  alt=""  \n\
        src="<?php echo base_url() . 'assets/images/loading_map.gif'; ?>">\n\
        < /div>');
                                    jQuery("#overlay_loading")
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
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/prototype.js"></script>
    <script type="text/javascript" charset="utf-8">
                            jQuery('td input').hide();
                            jQuery(document).ready(function() {

                                // Delete call here
                                jQuery('.delete_icon').on('click', function(e) {
                                    if (confirm('Are you sure you want to delete?')) {

                                        var rowId_for_edit = jQuery(this).parents('td').parents('tr').attr('id');
                                        var form_id = jQuery(this).attr('form_id');
                                        jQuery.ajax({
                                            url: "<?= base_url() ?>form/delete_result",
                                            data: {form_id: form_id, result_id: rowId_for_edit},
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
                                //        jQuery(".image_colorbox").live('click', function(e) {
                                //            var image_url = jQuery(this).attr('href');
                                //            jQuery(this).colorbox({
                                //                width: "55%",
                                //                height: "65%",
                                //                open: true,
                                //                title: function() {
                                //                    var link = 'http://godk.itu.edu.pk/';
                                //                    var url = jQuery(".image_colorbox").attr('name');
                                //                    var title = jQuery(".image_colorbox").attr('title');
                                //                    return  title;
                                //                }});
                                //            e.preventDefault();
                                //            return false;
                                //        });

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
        jQuery(document).ready(function()
        {
            jQuery(".update_icosn").bind('contextmenu', function(e) {
                return false;
            });
        });

        jQuery('.genericBtnreset').live('click', function() {
//            jQuery("#cat_filter option:selected").removeAttr("selected");
//            jQuery("#cat_filter").multipleSelect("refresh");
//            jQuery("#form_list option:selected").removeAttr("selected");
//            jQuery("#form_list").multipleSelect("refresh");
            jQuery('#datepicker2').val('');
            jQuery('#datepicker').val('');
            jQuery('#search_text').val('');
        });

        </script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'export_results') {
    ?>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script>
        jQuery(function() {
            jQuery("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
        });</script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#form_lists').change(function() {
                if (jQuery(this).val() != 'all_forms_result_export') {
                    this.form.submit();
                } else {
                    jQuery('#form_id_export').val('all_forms_result_export');
                }
            })
        });
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


    </script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/chosen.proto.js"></script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'graph-dashboard') {
    ?>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/highcharts.js"></script>
    <script type="text/javascript">
        jQuery('#user_span').hide();
        jQuery('#filter_span').hide();
        $(window).load(function() {
            $('#overlay_loading').hide();
        });
        /**
         * FOR graph type like users or town wise graph
         */
        $('#filter_graph_type').live('change', function() {


            $('#overlay_loading').show();
            var graph_type = $(this).val();
            if (graph_type == 'town') {
                window.location = window.location;
            } else if (graph_type == 'user') {
                $.ajax({
                    url: "<?= base_url() . 'graph/graph_type/' . $form_id ?>",
                    type: 'POST',
                    data: {graph_type: graph_type},
                    success: function(response) {
                        jQuery('.applicationText').html(response);
                        $('#overlay_loading').hide();
                        jQuery('#filter_span').hide();
                        jQuery('#user_span').show();
                    }
                });
            } else {
                $.ajax({
                    url: "<?= base_url() . 'graph/graph_type/' . $form_id ?>",
                    type: 'POST',
                    data: {graph_type: graph_type},
                    success: function(response) {
                        jQuery('.applicationText').html(response);
                        $('#overlay_loading').hide();
                        jQuery('#user_span').hide();
                        jQuery('#filter_span').show();
                    }
                });
            }
        });
        /**
         * FOR single users graph usess
         */
        $('#users_graph').live('change', function() {

            $('#overlay_loading').show();
            var imei_number = $(this).val();
            var user_name = $('#users_graph :selected').text();
            if (imei_number == '') {
                window.location = window.location;
            } else {
                $.ajax({
                    url: "<?= base_url() . 'graph/single_user_graph/' . $form_id ?>",
                    type: 'POST',
                    data: {imei_number: imei_number, user_name: user_name},
                    success: function(response) {
                        jQuery('.applicationText').html(response);
                        $('#overlay_loading').hide();
                    }
                });
            }
        });
        /**
         * FOR single category graph usess
         */
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
        $(function() {
            $('#container').highcharts({
                chart: {
                    type: 'bar'
                },
                title: {
                    text: '<?php echo $graph_text . ' (Bar Graph)'; ?>'
                },
                subtitle: {
                    text: 'All Rights Reserved © 2013-<?php echo date('Y'); ?> - DataPlug By ITU Government of Punjab - Pakistan.'
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
                    verticalAlign: 'top', x: 0,
                    y: 20,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: '#FFFFFF',
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{name: 'Tagging Records',
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
                                name: "<?php echo $highest_name ?>",
                                y: <?php echo $highest_count ?>,
                                sliced: true,
                                selected: true
                            },<?php echo $pie_chart_data; ?>]
                    }]
            });
        });</script>
    <script type='text/javascript'>

        jQuery('#form_lists').change(function() {
            this.form.submit();
        })

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
    </script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'graph-category') {

    ?>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-3d.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
<!--    <script type="text/javascript" src="--><?//= base_url() ?><!--/assets/js/highcharts.js"></script>-->
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.searchabledropdown-1.0.8.min.js"></script>
<!--    <script src="http://code.highcharts.com/modules/exporting.js"></script>-->


    <script type="text/javascript">
        $(function() {
            $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
        });
        function clear_field(obj) {
            jQuery(obj).val("");
        }

        function check_date_validity() {
            var date_from = jQuery('#datepicker').val();
            var date_to = jQuery('#datepicker2').val();
            if (new Date(date_from).getTime() < new Date(date_to).getTime()) {
                jQuery('#datepicker').val('');
                jQuery('#datepicker2').val('');
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
                        //$("#cat_filter").empty();
                        $.each(resp, function(option, value) {
                            if (value.length > 23) {
                                value = value.substring(0, 23) + ' ...';
                            }
                            var opt = jQuery('<option />');
                            opt.val(option);
                            opt.text(value);
                            //$("#cat_filter").append(opt).multipleSelect("refresh");
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

//         var templist = [];
//         jQuery('#form_descrip').find('input, textarea, select').each(function() {

//             if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
//             {
//                 if (jQuery(this).attr('name') != undefined) {
//                     var field_name = jQuery(this).attr('name');
//                     field_name = field_name.replace('[]', '');
//                     var skip = jQuery(this).attr('rel');
//                     var type = jQuery(this).attr('type');
                    var selected = '<?php //echo $filter; ?>';
//                     //if (type != 'text' && type != 'hidden') {

//                     if (jQuery.inArray(field_name, templist) == '-1')
//                     {
//                         var field_name_display = field_name;
//                         templist.push(field_name);
//                         if (field_name != 'District' && field_name != 'Tehsil' && field_name != 'Hospital_Name' && field_name != 'No_of_Citizen_Visited' && field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                                 //                    if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                                 {
//                                     field_name_display.replace(/[_\W](^\s+|\s+$|(.*)>\s*)/g, "");
//                                     field_name_display = field_name_display.replace(/_/g, ' ');
//                                     field_name_display = capitalize_first_letter(field_name_display);
//                                     //field_name_display
//                                     if (selected == field_name)
//                                     {
//                                         field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                                         jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
//                                     }
//                                     else {
//                                         field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                                         jQuery('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
//                                     }
//                                 }
//                     }
//                     //}
//                 }
//             }
//         });
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
                        fontFamily: 'serif'
                    },
                    height: '<?php echo $calculated_height; ?>',
                    width: 804
//                    options3d: {
//                        enabled: true,
//                        alpha: 6,
//                        beta: 10
//                    }
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
                        depth: 35,
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
                        name: 'Tagging',
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
                    plotShadow: false,
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
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
                        depth: 35,
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
} elseif (isset($active_tab) && $active_tab == 'app-settings') {
    ?>
    <script>


        $(document).ready(function() {
            var map_type_filter = '<?= (isset($map_type_filter))?$map_type_filter:"" ?>';
            if (map_type_filter == 'Off') {
                $('#map_type_div').hide('slow');
            } else {
                $('#map_type_div').show();
            }

            $('#map_type_filter').change(function() {
                if ($(this).val() == 'Off') {
                    $('#map_type_div').hide('slow');
                } else {
                    $('#map_type_div').show('slow');
                }
            });
            $('#check_all_column').live('click',function(e){
                var form_id = $(this).attr('form_id');
                if ($(this).is(':checked')) {
                    $('.chk_column_setting'+form_id).attr('checked', true);
                }
                else{
                    $('.chk_column_setting'+form_id).attr('checked', false);
                }

                });
        });


    </script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'app_released') {
    ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            oTable = $('#version-listing').dataTable({
                "aoColumnDefs": [
                    {
                        "bSortable": true,
                        "aTargets": [2] // <-- gets last column and turns off sorting
                    }
                ],
                "jQueryUI": true,
                "paginationType": "full_numbers"
            });
        });</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'users-add') {
    ?>
    <script type="text/javascript">
        $('#password').val('');
        $('.textBoxLogin').val('');</script>
    <?php
} elseif (isset($active_tab) && $active_tab == 'log') {
    ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('.after,.before').live('click', function() {
                if ($(this).attr('title') != "") {
                    $("#detail_dialogue p").text($(this).attr('title'))
                    $(function() {
                        $("#detail_dialogue").dialog();
                    });
                }
            })

            var isOpen = $("#detail_dialogue").dialog("instance");
            if (isOpen != 'undefined') {
                $('.Category').click(function() {
    //                    $("#detail_dialogue").dialog('close');
                })
            }


        
        /*
        making datatable ajax loading
        for logs view irfan
         */

          

                var myTable=$('.logajaxtable').dataTable( {
                    "lengthMenu": [[25, 50, 100], [25, 50, 100]]
                    ,"processing": true
                    ,"serverSide": true
                    ,"sPaginationType": "full_numbers"
                    ,"bJQueryUI":true
//                    ,"sAjaxDataProp": "aaData"

                    ,"aoColumns":[
                         {"mDataProp":"changed_by_name","bSortable": true}
                        ,{"mDataProp":"department_name","bSortable":true}
                        ,{"mDataProp":"action_type","bSortable": true}
                        ,{"mDataProp":"action_description","bSortable": true}
                        ,{"mDataProp":"before_record","bSortable": false}
                        ,{"mDataProp":"after_record","bSortable": false}
                        ,{"mDataProp":"app_name","bSortable": false}
                        ,{"mDataProp":"form_name","bSortable": false}
                        ,{"mDataProp":"controller","bSortable": false}
                        ,{"mDataProp":"method","bSortable": false}
                        ,{"mDataProp":"created_datetime","bSortable": false}
                    ]

                    ,"sAjaxSource": "<?php echo base_url(); ?>log/ajax_logs"
                    ,"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                        $('td:eq(4)', nRow).addClass('before');
                        $('td:eq(5)', nRow).addClass('after');
                        $('td:eq(4)', nRow).attr('title', aData['before_record_title']);
                        $('td:eq(5)', nRow).attr('title', aData['after_record_title']);
                        return nRow;
                    }



                } ).columnFilter({
                    sPlaceHolder: "head:before",
                    aoColumns: [
                        { type: "text"},
                        { type: "text" },
//                        { type: "select",values:["insert","delete","update","login","logout"]},
                        { type: "select",values:getValues()},
                        { type: "text" },
                        null,
                        null,
                        { type: "text" },
                        { type: "text" },
                        null,
                        null,
                        { type: "date-range", value:"" },

                    ]

                });
                $("#application-listing-app_filter").hide();
                $( ".text_filter" ).each(function() {
                    value=$(this).val();
                    $( this ).attr('title', value);
//                    $( this ).before( "<p>"+value+"</p>" );
                });


            } );

        var options=[];
            function getValues(){
            $.ajax({
                method: "POST",
                url: "<?php echo base_url();?>log/get_actions",
                success: function(data) {
                    arr=data.split(",");
                    for (i = 0; i < arr.length; i++){
                        options[i]=arr[i];
                    }
                },
                async: false

            });
            return options;
        }




    </script>


<?php } elseif (isset($active_tab) && $active_tab == 'groups') { ?>
    <script>
    $('#check_all_column').live('click',function(e){
        if ($(this).is(':checked')) {
            $('.permission_chkbox').attr('checked', true);
        }
        else{
            $('.permission_chkbox').attr('checked', false);
        }

    });
    </script>
<?php }?>
<script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.multiple.select.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/multiple-select.css"/>
<script>
    jQuery(document).ready( function() {
        jQuery('.filter_button').live( "click", function() {
            var form_id=this.id;
            var form = $('#'+form_id);
            jQuery.ajax( {
                type: "POST",
                url: form.attr( 'action' ),
                data: form.serialize(),
                success: function( response ) {
                    console.log( response );
                    if(response=='success'){
                        $('.'+form_id+'_msg').show();
                        $('.'+form_id+'_msg').html("Settings saved successfully!!!");
                        setTimeout(function() {
                            $('.'+form_id+'_msg').hide('blind', {}, 500)
                        }, 5000);


                    }
                }
            } );


        } )

        jQuery('.map_pin_button').live( "click", function() {
            var form_id=this.id;
            var form = $('#'+form_id);
            jQuery.ajax( {
                type: "POST",
                    url: "<?php echo base_url()."app/save_pin_settings"; ?>",
                data: form.serialize(),
                success: function( response ) {
                    console.log( response );
                    if(response=='success'){
                        $('.map_pin_msg').show();
                        $('.map_pin_msg').html("Settings saved successfully!!!");
                        setTimeout(function() {
                            $('.map_pin_msg').hide('blind', {}, 500)
                   F     }, 5000);
                    }else{
                        $('.map_pin_msg').show();
                        $('.map_pin_msg').html("<span style='color:red'>Please try again!!!</span>");
                        setTimeout(function() {
                            $('.map_pin_msg').hide('blind', {}, 500)
                        }, 5000);
                    }
                }
            } )


        } );


        jQuery("#app_settings_users").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });
        jQuery("#graph_view_form").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });
        jQuery("#map_view_form").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });
        jQuery("#result_view_form").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });
        jQuery(".possible_filters").multipleSelect({
            filter: true,
            width: 200,
            placeholder: "Please select"
        });




            jQuery(".edit_color_box").live('click', function(e) {
                var form_id = jQuery(this).attr('form_id');
                var rowId = jQuery(this).parents('td').parents('tr').attr('id');
                var datum = 'form_id=' + form_id + '& form_result_id=' + rowId;
                jQuery(this).colorbox({
                    width: "50%",
                    height: "80%",
                    open: true,
                    data: datum,
                });
                e.preventDefault();
                return false;
            });
            
            $('#form_submission_web_link').change(function(){
                if($(this).val()==0){
                    $('#urldiv').hide();
                   
                }
                else{
                     $('#urldiv').show();
                }
            });

    } );

    function get_pins(value){
        $.ajax( {
            type: "POST",
            url: "<?php echo base_url()."app/get_pins"; ?>",
            data:"field_value="+value,
            success: function( response ) {
                console.log('.pin_result_'+value);
                $('.pin_result_'+value).html(response);
            }
        });
    }

    function change_filter(key,value){
        $(".map_pin_msg").html("<font color='orange'>Loading Values !!!</font>");
        $("#map_pin_form").val(key);
        $("#map_pin_form").triggerHandler("change");
        setTimeout(function(){
            if($("#myfield").val()==""){
                $("#myfield").val(value);
                $("#myfield").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:$("#myfield").val()}});
                $(".map_pin_msg").html("");
//                return false;
            }
        }, 1000);

    }



    jQuery(function() {
            jQuery("#internet_issue_from_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#internet_issue_to_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#balance_received_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#balance_deduction_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#leave_from_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#leave_to_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#data_missing_from_date").datepicker({dateFormat: 'yy-mm-dd'});
            jQuery("#data_missing_to_date").datepicker({dateFormat: 'yy-mm-dd'});
        });

</script>

</body>

</html>
