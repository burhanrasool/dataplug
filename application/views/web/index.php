<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo PLATFORM_NAME; ?></title>

        <link href="<?= base_url() ?>assets/web/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/web/common.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
                                          <script src="html5shiv.min.js"></script>
                                          <script src="respond.min.js"></script>
                                        <![endif]-->
        <script src="<?= base_url() ?>assets/web/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/web/bootstrap.min.js"></script>
        <script src="<?= base_url() ?>assets/web/bootstrap.js"></script>
        <script src="<?= base_url() ?>assets/web/jquery-2.0.2.min.js"></script>
        <script src="<?= base_url() ?>assets/web/jquery.js"></script>
        <script src="<?= base_url() ?>assets/web/jquery-ui-autocomplete.js"></script>
        <script src="<?= base_url() ?>assets/web/jquery.select-to-autocomplete.min.js"></script>
        <script src="<?= base_url() ?>assets/web/jquery-ui.min.js"></script>
        
        
        <script>
        function AndroidFunctionClass() {
    
            this.showAlertDialog = function(message) {
                alert(message);
            };
            this.removeTimeData = function() {
                return true;
            };
            this.isLocationAvailable = function() {
                return true;
            };
            this.checkTimeStatus = function() {
                return false;
            };
            this.GetLastActivity = function() {
                return false;
            };
            this.onBackPressShowAlert = function() {
                return false;
            };
            this.GetCountOfUnSentActivities = function() {
                return '0';
            };
            this.takeTime = function() {
                var current_date = new Date(Date.now());

                $('#' + time_id).val(current_date);//datetime string
                $('#' + time_id + '_location').val('0.0,0.0');//location string
                try {
                    $('#' + time_id + '_source').val('web');//location string
                } catch (err) {

                }
                return true;
            };
        }
        var AndroidFunction = new AndroidFunctionClass();
        
        var base_url = $('#base_url').val();
        var app_id = $('#app_id').val();
        if ($('#form_id').val() == undefined)
        {

            $('.field').find('[class="formobile"]').each(function () {
                $('.field').css('float', 'left');
                var image_name = $(this).find('img').attr('src');

                //Get form_id
                var skip_first_array = image_name.split('_');
                var skip_second_array = skip_first_array[1].split('.');
                var form_id = skip_second_array[0];

                //Creat form url
                var form_url = base_url + 'web/index/' + app_id + '_' + form_id
                $(this).attr('href', form_url);

                //Create image url
                var image_url = base_url + '/assets/images/data/form-data/../form_icons/' + app_id + '/' + image_name
                $(this).find('img').attr('src', image_url);

            });
            //create image url for title
            var app_icon_name = $('#form-title').find('[class="img-icon formobile"]').attr('src');
            var app_image_url = base_url + '/assets/images/data/form-data/../form_icons/' + app_id + '/' + app_icon_name;
            $('#form-title').find('[class="img-icon formobile"]').attr('src', app_image_url);
        } else {
            //create image url for title
            var form_icon_name = $('#form-title').find('[class="img-icon formobile"]').attr('src');
            var form_image_url = base_url + '/assets/images/data/form-data/../form_icons/' + app_id + '/' + form_icon_name;
            $('#form-title').find('[class="img-icon formobile"]').attr('src', form_image_url);
            $('#form-title').find('[class="navbar-brand"]').attr('href', base_url + 'web/index/' + app_id)

        }
        $('#form-title').find('[class="nav navbar-nav navbar-right"]').hide()

        //Form onload area

        if ($('#form-save').val() != undefined)
        {
            $('#form-submitedit').show();
            $('#form-save').hide();
        }
        
        function submitform(f) {

            //Validation in case of pages
            var form_id_main = $('#form_id').val();
            var page_validation = false;
            if ($('#pages-header') != undefined) {
                var page_id = $('#form-submit').parent().attr('id');
                if ($('#form-submit').parent().hasClass('pagediv'))
                {
                    if (isrequiredednext(page_id))
                    {
                        page_validation = true;//its true because validation perform on specific page, so no need to validate other pages
                    } else {
                        return false;
                    }
                }
            }



            var form_operation = $('#form_operation').val();
            $('#form-preview').find('[field_setting="main"]').each(function () {
                if ($(this).attr('disabled') == 'disabled')
                {
                    $(this).attr('disabled', false);
                }
                if ($(this).attr('readonly') == 'readonly')
                {
                    $(this).attr('readonly', false);
                }
            });

            if (isrequiredsubmit(f, page_validation))
            {
                var form_ser = $(f).serialize();
                var form_values = parse_query(form_ser);
                var myJsonString = JSON.stringify(form_values);//This function will return json {"field1":"zad","field2":"Option+1"}

                var base_url = $('#base_url').val();

                $.ajax({
                    url: base_url + "api/saverecordsweb",
                    data: {
                        'form_data': myJsonString,
                        'imei_no': '1234567890123'
                    },
                    type: "post",
                    success: function (response) {

                        alert(response);
                    }

                });




                reset_images();
                $('.loop-list-main').html('');
                if ($('#pages-header') != undefined) {
                    $('ul.pages li:first a').trigger('click');
                    ;
                }
                if ($('#tabs-header') != undefined) {
                    $('ul.tabs li:first a').trigger('click');

                }

            }
            //}
            return false;
        }
        </script>
        <script src="<?= base_url() ?>assets/web/common_index.js"></script>
        <style type="text/css" media="screen">

            <?php
            $css_embed = '';
            if (isset($app_general_setting->app_language)) {
                $language = $app_general_setting->app_language;
                if ($language == 'urdu') {
                    $css_embed = '#form-preview{direction:rtl;}
                input.css-checkbox[type="checkbox"] + label.css-label, input.css-checkbox[type="radio"] + label.css-label{background-position:right top;padding-left:0;padding-right:23px;}
                .checkbox input.css-checkbox[type="checkbox"]:checked + label.css-label{background-position:right -18px;}.tbl_widget td {border: 1px solid gainsboro;}.field label{font-size:17px;}';
                }
            }
            ?>
            body {
                font-family: Arial, Verdana, sans-serif;
                font-size: 13px;
            }

            .ui-autocomplete {
                padding: 0;
                list-style: none;
                background-color: #fff;
                width: 218px;
                border: 1px solid #B0BECA;
                max-height: 350px;
                overflow-y: scroll;
            }

            .ui-autocomplete .ui-menu-item a {
                border-top: 1px solid #B0BECA;
                display: block;
                padding: 4px 6px;
                color: #353D44;
                cursor: pointer;
            }

            .ui-autocomplete .ui-menu-item:first-child a {
                border-top: none;
            }

            .ui-autocomplete .ui-menu-item a.ui-state-hover {
                background-color: #D5E5F4;
                color: #161A1C;
            }

            .ui-autocomplete-input {
                opacity: 1 !important;
            }
<?php echo $css_embed; ?>
        </style>
    </head>
    <body>
        <div><a href="<?php echo base_url() . 'web/logout/' . $slug; ?>">Logout</a></div>
        <input type="hidden" name="base_url" id="base_url" value="<?= base_url() ?>"/>
        <input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>"/>
        <div class="container" id="form-builder" style="">
        <?php echo $full_description; ?>
        </div>
    </body>
</html>