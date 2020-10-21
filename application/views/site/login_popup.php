<div class="login_NEW_border">
</div>
<div class="login_NEW">

    <div class="loginContent">
        <span id="status_span">
        </span>
        <?php echo form_open('users/login_confirm', array('id' => 'login_form')); ?>
        <input type="text" size="20" class="textBoxLogin" id="username" name="username" placeholder="Email" value="<?php
        if (isset($_COOKIE['remember_me_username']))
            echo $_COOKIE['remember_me_username'];
        else
            echo "";
        ?>"/>
        <br/>
        <input type="password" size="20" class="textBoxLogin" id="passowrd" name="password" placeholder="Password" value="<?php
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
            <a style="text-decoration:none;" class="genericBtn"  id="loginlink">Login</a>
            <a style="text-decoration:none;" class="genericBtn" href="<?= base_url() ?>users/signup/">Sign Up</a>
            <?php echo form_close(); ?>
            <br clear="all" />
        </p>
        <p>Forgot your Password ? <a href="<?= base_url() ?>forgotpassword" style="color:#0E76BD;text-decoration: none;">Click Here.</a></p>
    </div>
</div>
<script>

	$('#login_form').each(function() {
	    $(this).find('input').keypress(function(e) {
	        // Enter pressed?
	        if(e.which == 10 || e.which == 13) {
	        	$('#loginlink').trigger('click');
	        }
	    });
	});
    $('#loginlink').click(function() {
        var email = $('#username').val();
        var passowrd = $('#passowrd').val();
        $.ajax({
            url: "<?= base_url() ?>users/validate_users_login",
            data: {email: email, password: passowrd},
            type: 'POST',
            success: function(response) {
                response = $.parseJSON(response);
                if (response.status == 'success') {
                    $('#login_form').submit();
                } else {
                    $('#status_span').text(response.status);
                }

            },
            error: function(data) {
                console.log(response);

            }
        });
    })</script>

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
    .loginContent .genericBtn2:hover {
        color:#d3caca;
    }
    .loginContent .genericBtn {
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

    .loginContent .genericBtn:hover {
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
        margin: 0 auto 0 137px;
        padding-top: 90px;
        width: 210px;
    }
</style>


