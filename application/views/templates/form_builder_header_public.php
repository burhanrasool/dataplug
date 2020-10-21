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
                    
                    <link id="style" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/custom/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link id="style" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/custom/css/common.css" media="screen" rel="stylesheet" type="text/css"/>
                    
                    <link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/styles/shCoreDefault.css">
                        <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/colorbox.css'>
                            
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
                                        <img src="<?= base_url() ?>assets/home/images/itu.png" alt="Information Technology" class="ItuLogo" style="right:480px;"/>
                                        <div id="login_form">
                                            <?php echo form_open('users/login_confirm'); ?>
                                            <input type="text" size="20" class="textBoxLogin" id="username" name="username" placeholder="Email"/>
                                            <input type="password" size="20" class="textBoxLogin" id="passowrd" name="password" placeholder="Password" style="margin-right:4px"/>

                                            <input type="submit"  value="Login" class="submitLogin" />
                                            <a style="text-decoration:none;" href="<?= base_url() ?>users/signup/">
                                                <input type="button"  value="Sign Up" class="submitLogin" style="margin-right: 0px;"/>
                                            </a>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="flashdiv-error">
                                    <div class="flash-nav-container">
                                        <div id="error">

                                        </div>
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
                                                    <?php if($messages['message'] == 'Verify your e-mail address to login'){
                                                            ?>
                                                        <script>
                                                        
                                                        alert('<?php echo $messages['message'];?>');</script>
                                                                <?php 
                                                        } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                <div class="flashdiv-warning" id="check_browser" style="display: none;">
                                    <div class="flash-nav-container">
                                        <div>
                                            For best results, Use <a href="http://www.mozilla.org/en-US/firefox/new/" target="_new">Mozilla Firefox</a> or <a href="https://www.google.com/intl/en/chrome/browser/" target="_new">Google Chrome</a> browser.
                                        </div>
                                    </div>
                                </div>
                                <script>
                                        function getBrowser() {
                                                if( navigator.userAgent.indexOf("Chrome") != -1 ) {
                                                  return "Chrome";
                                                } else if( navigator.userAgent.indexOf("Opera") != -1 ) {
                                                  return "Opera";
                                                } else if( navigator.userAgent.indexOf("MSIE") != -1 ) {
                                                  return "IE";
                                                } else if( navigator.userAgent.indexOf("Firefox") != -1 ) {
                                                  return "Firefox";
                                                } else {
                                                  return "unknown";
                                                }
                                              }
                                              
                                              var check_browser = getBrowser();
                                              if(check_browser== "IE")
                                              {
                                                  $('#check_browser').show()
                                              }
                                        </script>
                                <div class="Wraper">