<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class=" js no-touch" style="" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title><?php echo $pageTitle; ?></title>
            <meta name="viewport" content="width=device-width,initial-scale=1">
                <link rel="icon"  type="image/png" href="<?= base_url() ?>assets/home/images/logo-favicon.png" />
                <link rel="stylesheet" href="<?= base_url() ?>assets/home/DataPlug_files/style_002.css">
                    <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/colorbox.css'>
                        <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/cmxform.css'>

                            <!-- Style sheets -->
                            <link href="<?= base_url() ?>assets/home/DataPlug_files/basic.css" media="screen" rel="stylesheet" type="text/css">
                                <!--[if gt IE 7]>
                                <style>
                                  .loginContent input.genericBtn{
                                                                        margin:25px 0 0 10px;
                                float:left;
                                                                    }
                                
                                </style>
                                
                                <![endif]-->
                                <script src="<?= base_url() ?>assets/web/jquery.min.js"></script>
                                <link rel="stylesheet" href="<?= base_url() ?>assets/home/css/responsiveslides.css" type="text/css" />
                                <style type="text/css">body{background:none;background:url(<?= base_url() ?>assets/home/images/cream_pixels.png);}a.logo{text-decoration:none;width:307px;}a.logo:hover{color:#fff;}.HeaderWraper h1{margin-bottom:0;}.dropdown li a{text-decoration:none;}.cbp-tm-icon-archive{text-decoration:none;}.cbp-tm-icon-mobile{text-decoration:none;}ul.dropdown{margin:10px 0 0 88px;}.headerContainer{background:none repeat scroll 0 0 #FFF;border-bottom:medium none;border-top:20px solid #0A6AAA;height:155px;padding:10px 0;}.HeaderWraper{margin:0 auto;padding-top:5px;position:relative;text-align:center;width:auto;max-width:1000px;}.userActions{position:absolute;right:0;top:10px;}.HeaderWraper .genericBtn{background:none repeat scroll 0 0 #2DA5DA;border:medium none;color:#FFF;cursor:pointer;outline:medium none;padding:5px 10px;position:relative;right:0;top:0;text-decoration:none;}.HeaderWraper .genericBtn:hover{color:#d3caca;}.HeaderWraper .genericBtn.aboutTeamBtn{background:none repeat scroll 0 0 #2DA5DA;border:medium none;color:#FFF;cursor:pointer;outline:medium none;padding:5px 10px;text-decoration:none;}.HeaderWraper .genericBtn.aboutTeamBtn:hover{color:#d3caca;}a.logo{background:url("<?= base_url() ?>assets/home/images/logo-home.png") no-repeat scroll left 0 rgba(0,0,0,0);color:#0E76BC;display:block;float:none;font-family:arial;font-size:22px;font-weight:normal;height:140px;line-height:140px;margin:5px auto;padding:0;position:relative;text-align:center;z-index:1000;width:300px;}.homeFeatured{background-color:#0A6AAA;margin:0 auto;max-width:1000px;overflow:hidden;padding:20px 0 5px;}.affiliationFeatured{background-color:#DEF4FF;margin:0 auto;max-width:1000px;overflow:hidden;padding:10px 0 5px;}.affiliationFeatured ul{float:left;}.affiliationFeatured ul li{float:left;list-style:none outside none;text-align:center;width:380px;margin:0 10px;}.affiliationFeatured ul img{width:100%;}.featuredWrap ul{float:left;margin:0;padding:0;width:100%;}.featuredWrap ul li{float:left;list-style:none outside none;margin:0 16px 20px;text-align:center;width:290px;}.featuredWrap ul h2{color:#FFF;font-family:Arial,Helvetica,sans-serif;font-size:25px;margin:0 0 5px 0;}.featuredWrap ul p{color:#FFF;font-size:15px;height:60px;line-height:20px;margin:0 auto 30px;text-align:center;width:215px;}.featuredActions{background:none repeat scroll 0 0 #0A6AAA;border-radius:0 0 20px 20px;clear:both;display:block;height:67px;margin:0 auto;width:95%;z-index:1000;position:relative;}.featuredWrap a{float:left;height:69px;margin:0;text-indent:-9999em;width:301px;}.featuredWrap a.tryBtn{background:url(<?= base_url() ?>assets/home/images/tryBtn.png) no-repeat;}.featuredWrap a.downloadBtn{background:url(<?= base_url() ?>assets/home/images/downloadBtn.png) no-repeat;}.featuredWrap a.forumBtn{background:url(<?= base_url() ?>assets/home/images/forumBtn.png) no-repeat;}.homeFooter{margin:0 auto;max-width:1000px;padding:10px 0;text-align:center;width:auto;}.homeFooter p{color:#111;}ul.affulRt li{width:200px;margin:0;}ul.affulRt{margin:45px 0;}ul.affulRt h3{margin:0 0 0 8px;font-size:21px;color:#777;}ul.affulLft li{text-align:left;width:480px;}ul.affulRt img{width:100%;}.callbacks_container{float:none;margin:-45px auto 0;max-width:1000px;position:relative;}.callbacks{position:relative;list-style:none;overflow:hidden;width:100%;padding:0;margin:0;}.callbacks li{position:absolute;width:100%;left:0;top:0;}.callbacks img{display:block;position:relative;z-index:1;height:auto;width:100%;border:0;}.callbacks .caption{display:block;position:absolute;z-index:2;font-size:20px;text-shadow:none;color:#fff;background:#000;background:rgba(0,0,0,.8);left:0;right:0;bottom:0;padding:10px 20px;margin:0;max-width:none;display:none;}.callbacks_nav{position:absolute;-webkit-tap-highlight-color:rgba(0,0,0,0);top:52%;left:0;opacity:.7;z-index:3;text-indent:-9999px;overflow:hidden;text-decoration:none;height:61px;width:38px;background:transparent url(<?= base_url() ?>assets/home/images/themes.gif) no-repeat left top;margin-top:-25px;}.callbacks_nav:active{opacity:1.0;}.callbacks_nav.next{left:auto;background-position:right top;right:0;}.team{background:none repeat scroll 0 0 #DEF4FF;margin:0 auto;max-width:1000px;}.teamWrap{overflow:hidden;padding:20px;}.teamWrap ul{display:block;margin:0;padding:0;width:100%;}.teamWrap ul li{float:left;list-style:none outside none;margin:0 50px 0 0;padding:0;width:430px;}.teamWrap img{border:1px solid #FFF;float:left;margin:0 20px 0 0;padding:2px;width:38%;}.teamWrap h1{border-bottom:3px solid;}.teamMember{border-bottom:1px solid #111;display:block;float:left;margin:0;padding:20px 0;width:100%;}.loginContent input.genericBtn{margin-top:0!important;}@media only screen and(max-width:959px){a.logo{background:url("<?= base_url() ?>assets/home/images/logo-home-mobile.png") no-repeat scroll left 0 rgba(0,0,0,0);color:#0E76BC;display:block;float:none;font-family:arial;font-size:22px;font-weight:normal;height:170px;line-height:170px;margin:35px auto 0 auto;padding:0;position:relative;text-align:center;width:200px;z-index:1000;}ul.affulLft li{text-align:left;width:245px;}ul.affulRt{margin:5px 0;}ul.affulRt li{margin:0;text-align:left!important;width:200px;}.featuredWrap a.tryBtn{background:url(<?= base_url() ?>assets/home/images/tryBtnMob.png) no-repeat;}.featuredWrap a.downloadBtn{background:url(<?= base_url() ?>assets/home/images/downloadBtnMob.png) no-repeat;}.featuredWrap a.forumBtn{background:url(<?= base_url() ?>assets/home/images/forumBtnMob.png) no-repeat;}.featuredWrap a{float:none;height:69px;margin:0 auto;text-indent:-9999em;width:201px;display:block;}.teamWrap img{border:1px solid #FFF;float:left;margin:0 20px 0 0;padding:2px;width:22%;}.teamWrap ul li{float:left;list-style:none outside none;margin:0 10px 10px 0;padding:0;width:430px;}.teamWrap h2,.teamWrap h3{margin:5px 0;font-size:18px;}.teamMember{border-bottom:0 none;display:block;float:left;margin:0;padding:0;width:100%;}.teamWrap h2,.teamWrap h3{display:inline-block;margin:35px 0!important;}.teamWrap h1{border-bottom:3px solid;font-size:25px;}}</style>
                                <style>
                                    #status_span {
                                        float: left;
                                        font-size: 12px;
                                        font-weight: normal;
                                        margin-bottom: -34px;
                                        margin-left: 12px;
                                    }
                                    .genericBtn2{
                                        background: none repeat scroll 0 0 #2DA5DA;
                                        border: medium none;
                                        color: #FFFFFF;
                                        cursor: pointer;
                                        float: left;
                                        margin-left: 1px;
                                        outline: medium none;
                                        padding: 5px 0;
                                        min-width: 70px;
                                    }
                                    .genericBtn2:hover {
                                        color:#d3caca;
                                    }
                                    .genericBtn {
                                        background: none repeat scroll 0 0 #2DA5DA;
                                        border: medium none;
                                        color: #FFFFFF;
                                        cursor: pointer;
                                        outline: medium none;
                                        padding: 5px 0;
                                        text-decoration: none;
                                        margin-left: 4px;
                                        min-width: 70px;
                                        text-align: center;
                                    }

                                    .genericBtn:hover {
                                        color:#d3caca;
                                    }


                                    .login_NEW {
                                        background: url("../../assets/images/dataplug-logo.png") no-repeat scroll left 0 rgba(0, 0, 0, 0);
                                        color: #0E76BC;
                                        font-size: 22px;
                                        font-weight: normal;
                                        height: 65px;
                                        margin: 0 35px;
                                        float: left;
                                        display: block;
                                    }
                                    .login_NEW_border{
                                        border-top: 7px solid #0E76BD;
                                        position: relative;
                                        top: 72px;
                                        width: 100%;
                                    }
                                    .loginContent p{
                                        color: #333333;
                                        margin-bottom: 0px !important;

                                        display: block;
                                        font-size: 12px;
                                        padding-bottom: 0;
                                        padding-top: 8px;
                                    }
                                    input.genericBtn {
                                        background: none repeat scroll 0 0 #2DA5DA;
                                        border: medium none;
                                        color: #FFFFFF;
                                        cursor: pointer;
                                        display: inline;
                                        margin-right: 66px;
                                        margin-top: -26px;
                                        outline: medium none;
                                        padding: 5px 10px;

                                    }

                                    .loginContent {
                                        color: #FF0000;
                                        margin: 0 auto 0 90px;
                                        padding-top: 90px;
                                        width: 210px;
                                    }
                                    .outer_div{
                                        width: 386px; 
                                        position: absolute; 
                                        left: 36%; 
                                        top: 10%; 
                                        border: 1px solid blue; 
                                        border-radius: 1pc; 
                                        height: 283px;
                                    }
                                </style>
                                </head>

                                <body>
                                    <input name="base_url" id="base_url" value="<?= base_url() ?>" type="hidden">
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

                                        <?php } ?>

                                        <div class="outer_div">
                                            <div class="login_NEW_border" id="login-home">
                                            </div>
                                            <div class="login_NEW">

                                                <div class="loginContent">
                                                    <span id="status_span">
                                                    </span>
                                                    <?php echo form_open('web/login/' . $id, array('id' => 'login_web_form')); ?>
                                                    <input type="hidden" value="<?php echo $id; ?>" id="app_id" name="app_id"/>
                                                    <input type="text" size="20" class="textBoxLogin" id="login_user" name="login_user" placeholder="Login User Name" value="<?php
                                                    if (isset($_COOKIE['remember_me_username']))
                                                        echo $_COOKIE['remember_me_username'];
                                                    else
                                                        echo "";
                                                    ?>"/>
                                                    <br/>
                                                    <input type="password" size="20" class="textBoxLogin" id="login_password" name="login_password" placeholder="Password" value="<?php
                                                    if (isset($_COOKIE['remember_me_password']))
                                                        echo $_COOKIE['remember_me_password'];
                                                    else
                                                        echo "";
                                                    ?>"/>
                                                    <br clear="all" />
                                                    <p>
                                                        <input type="checkbox" class="textBoxLogin" name="remember_me" id="remember_me" <?php if (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me'] == 'on') echo "checked"; ?> style="margin:2px 4px 0 0;" />
                                                        Remember me on this computer
                                                    </p>
                                                    <p>
                                                        <a style="text-decoration:none;" class="genericBtn"  id="loginlink" onclick="$('#login_web_form').submit();">Login</a>
                                                        <a style="text-decoration:none;" class="genericBtn" href="<?= base_url() ?>users/signup/">Sign Up</a>
                                                        <?php echo form_close(); ?>
                                                        <br clear="all" />
                                                    </p>
                                                    <p>Forgot your Password ? <a href="<?= base_url() ?>forgotpassword" style="color:#0E76BD;text-decoration: none;">Click Here.</a></p>
                                                </div>
                                            </div>
                                        </div>

                                </body>
                                </html>