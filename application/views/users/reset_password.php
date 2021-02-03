<style>
    .teamWrap h2, .teamWrap h3 {
        display: inline-block;
        margin: 60px 0 !important;
    }

</style>

<div class="team">
    <div class="teamWrap">
        <h1>Reset Password</h1>

        <?php echo form_open('users/resetpassword'); ?>
        <?php echo form_hidden('email',$email); ?>
        <?php echo form_hidden('user_id',$user_id); ?>
        <ul>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Password: </strong>
                    </label>
                    <p>

                        <input class="" type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" />
                        <?php echo $this->form_validation->error('password'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Confirm Password: </strong>
                    </label>
                    <p>

                        <input class="" type="password" name="conf_password" id="conf_password" value="<?php echo set_value('configure_password'); ?>" />
                        <?php echo $this->form_validation->error('configure_password'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <p>

                        <button class="genericBtn">Reset</button>
                    </p>
                </li>
            </div>

        </ul>
        <?php echo form_close(); ?>
    </div>
</div>


<style>
    .teamMember{
        border-bottom: none;

    }

    .teamMember p select {
        background: none repeat scroll 0 0 padding-box #FFFFFF;
        border: 1px solid #0E76BD;
        color: #000000;
        display: inline;
        float: left;
        font-size: 12px;
        margin: 5px 0 10px;
        padding: 10px 5px;
        width: 100%;
    }
    .teamMember p input {
        background: none repeat scroll 0 0 padding-box #FFFFFF;
        border: 1px solid #0E76BD;
        color: #000000;
        display: inline;
        float: left;
        font-size: 12px;
        margin: 5px 0 10px;
        padding: 10px;
        width: 95%;
    }
    p {
        color: #FF0000;
        font-size: 12px;
        margin: 0;
    }

</style>
