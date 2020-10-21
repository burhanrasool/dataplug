
<div class="login_NEW_border">
</div>
<div class="login_NEW">

    <div class="loginContent">
        <?php echo form_open('users/login_confirm'); ?>
        <input type="text" size="20" class="textBoxLogin" id="username" name="username" placeholder="Email"  value="<?php if(isset($_COOKIE['remember_me_username']))echo $_COOKIE['remember_me_username'];else echo ""; ?>"/>
        <br/>
        <input type="password" size="20" class="textBoxLogin" id="passowrd" name="password" placeholder="Password" value="<?php if(isset($_COOKIE['remember_me_password']))echo $_COOKIE['remember_me_password'];else echo ""; ?>" />
        <br clear="all" />
        <p>
            <input type="checkbox" class="textBoxLogin" name="remember_me" id="remember_me" <?php if(isset($_COOKIE['remember_me']) && $_COOKIE['remember_me']=='on')echo "checked"; ?> style="margin:2px 4px 0 0;" />
            Remember me on this computer
        </p>
        <p>
            <input type="submit"  value="Login" class="genericBtn2" />
            <a style="text-decoration:none;" class="genericBtn" href="<?= base_url() ?>users/signup/">
                Sign Up</a>
            <?php echo form_close(); ?>
            <br clear="all" />
        </p>
        <p>Forgot your Password ? <a href="<?= base_url() ?>forgotpassword" style="color:#0E76BD;text-decoration: none;">Click Here.</a></p>
    </div>
</div>

<style>
    .genericBtn2{
        background: none repeat scroll 0 0 #2DA5DA;
        border: medium none;
        color: #FFFFFF;
        cursor: pointer;
        float: left;
        margin-left: 1px;
        outline: medium none;
        padding: 6px 0;
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

    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
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
        margin: 0 auto 0 137px;
        padding-top: 90px;
        width: 210px;
    }
</style>


