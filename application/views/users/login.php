<div class="login">
    <div class="loginContent">
        <?php echo form_open('users/login_confirm'); ?>
        <input type="text" size="20" class="textBox" id="username" name="username" placeholder="Email"/>
        <input type="password" size="20" class="textBox" id="passowrd" name="password" placeholder="Password"/>
        <p><input type="checkbox"  /> Remember me on this computer</p>
        <p>
            <a style="text-decoration:none;" href="<?= base_url() ?>users/signup/"><input type="button"  value="SignUp" class="submit float_right" /></a>
            <input type="submit"  value="Login" class="submit" />
            <?php echo form_close(); ?>
            <br clear="all" />
        </p>
        <p>Forgot your Password ? <a href="<?= base_url() ?>forgotpassword">Click Here.</a></p>
    </div>
</div>


