<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo PLATFORM_NAME;?></title>
        <link rel="icon"  type="image/png" href="<?= base_url() ?>assets/images/logo-favicon.png" />
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
            <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/component.css'>
                <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

                    <!-- Style sheets -->
                    <link href="<?= base_url() ?>assets/form_builder/application/css/basic.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link href="<?= base_url() ?>assets/form_builder/application/css/jquery-ui.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link href="<?= base_url() ?>assets/form_builder/application/css/style.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link id="style-uniform" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/elegant/css/uniform.elegant.css" media="screen" rel="stylesheet"
                          type="text/css"/>
                    <link id="style" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/elegant/css/style.css" media="screen" rel="stylesheet"
                          type="text/css"/>
                    <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/cmxform.css'>

                    <!-- Javascript Files -->
                    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
                    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
                    <!--                    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.js"></script>-->
                    <!--                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery-ui-1.8.9.custom.min.js"></script>-->
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.tmpl.min.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.textchange.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/jquery.html5type.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/form_resources/js/jquery.tools.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/form_resources/js/jquery.uniform.min.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/einars-js-beautify/beautify-html.js"></script>
                    <script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/modernizr.custom.js"></script>
                    <!-- Syntax Highligher Resources -->
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shCore.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushXml.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushCss.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushJScript.js"></script>
                    <script type="text/javascript" src="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/scripts/shBrushPhp.js"></script>
                    <link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/styles/shCoreDefault.css">
<!--                          <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
                         <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>-->

                        <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/colorbox.css'>
                            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
                            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.js" ></script>
                            <!--<script type="text/javascript" src="<?= base_url() ?>assets/form_builder/application/js/ttw.formbuilder.js"></script>-->
                            <style type="text/css">
                                body {
                                    background: none;
                                }
                                a.logo {
                                    text-decoration: none;
                                    width:307px;
                                }
                                a.logo:hover{
                                    color: #ffffff;
                                }
                                .HeaderWraper h1{
                                    margin-bottom: 0;
                                }
                                .dropdown li a{
                                    text-decoration: none;
                                }
                                .cbp-tm-icon-archive{
                                    text-decoration: none;
                                }
                                .cbp-tm-icon-mobile{
                                    text-decoration: none;
                                }
                                ul.dropdown{
                                    margin:10px 0 0 88px;
                                }

                            </style>
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

                                });
                            </script>

                            </head>

                            <body class="bgColor">
                                <input type="hidden" name="base_url" id="base_url" value='<?php echo base_url(); ?>'/>
                                <div class="headerContainer">


                                    <div class="HeaderWraper">
                                        <h1>
                                            <?php
                                            $app_name = '';
                                            $app_name_first_word = explode(' ', trim($app_name));
                                            ?>
                                            <a  class="logo" href="<?= base_url() ?>">
                                                <?php echo $app_name_first_word[0]; ?>
                                                <span><?php echo substr(strstr("$app_name", " "), 1); ?></span>
                                            </a>

                                        </h1>
                                    </div>

                                </div>
<?php
                                $messages = $this->session->flashdata('validate');

                                if ($messages) {
                                    if ($messages['type'] == 'success') {
                                        $type = 'flashdiv-success';
                                    } else if ($messages['type'] == 'error') {
                                        $type = 'flashdiv-error';
                                    } else if ($messages['type'] == 'warning') {
                                        $type = 'flashdiv-warning';
                                    }
                                    ?>
                                    <div class="<?php echo $type; ?>">
                                        <div class="flash-nav-container">
                                            <div>
                                                <?php echo $messages['message']; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                                <div class="Wraper">

