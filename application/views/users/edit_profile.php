<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2><?php //echo $department_name;            ?> Edit Profile</h2>
<!--            <a href="<?= base_url() ?>users/userlisting" class="backBtn">Back</a>-->
<style>
    #tabbed label.tabs {
        background: #6e98e3;
        color: #fff;
    }
    #tabbed label.tabs:hover,
    #tabbed label.tabs:focus {
        background: #4877cb;
    }
    #tabbed .cell.table.first.blue {
        background: #6E98E3;
    }
    #tabbed p.framed-quote {
        background: #6e98e3;
    }
    #tabbed p.framed-quote.arrow-left:before {
        border-color: transparent #6e99e3 transparent transparent;
    }
    #tabbed .button.blue {
        background: #6e98e3;
    }
    #tabbed .button.blue:hover {
        background: #333;
    }
    #tabbed .title.large {
        color: #4877cb;
    }
    #tabbed p.quote {
        color: #6E99E3;
    }
    #tabbed a {
        color: #6e98e3;
    }
    #tabbed input.reset:hover {
        color: #6E98E3;
    }
    .mytab{
        box-shadow:0 0 6px rgba(0, 0, 0, 0.25);
        padding:10px;
    }
    [class*="entypo-"]:before {
        font-family: 'entypo', sans-serif;
    }
    #tabbed label {
        cursor: pointer
    }
    #tabbed input[type=radio] {
        display: none;
        visibility: hidden;
        opacity: 0
    }
    #tabbed a {
        text-decoration: none
    }
    #tabbed {
        width: 1000px;
        position: relative;
        float: left;
        text-align: left;
        -webkit-backface-visibility: hidden
    }
    #tabbed .wrapper {
        background: #fff;
        position: relative;
        width: 100%;
        height: auto;
        float: left;
        text-align: left;
        margin-bottom: 20px;
    }
    #tabbed .wrapper>div {
        position: absolute;
        top: 0;
        left: 0;
        height: auto;
        width: 100%;
        background: #fff;
        z-index: -1;
        opacity: 0;
        visibility: hidden;
        padding: 40px;
        float: left
    }
    #tabbed input#t-1:checked~.wrapper .tab-1,
    #tabbed input#t-2:checked~.wrapper .tab-2,
    #tabbed input#t-3:checked~.wrapper .tab-3,
    #tabbed input#t-4:checked~.wrapper .tab-4,
    #tabbed input#t-5:checked~.wrapper .tab-5,
    #tabbed input#t-6:checked~.wrapper .tab-6 {
        position: relative;
        float: left;
        z-index: 10;
        opacity: 1;
        visibility: visible
    }
    #tabbed label.tabs {
        display: inline-block;
        color: #fff;
        font-size: 14px;
        text-align: center;
        text-transform: uppercase;
        padding: 0 40px;
        width: auto;
        height: 44px;
        line-height: 44px;
        -webkit-transform: translateY(6px);
        -moz-transform: translateY(6px);
        -ms-transform: translateY(6px);
        -o-transform: translateY(6px);
        transform: translateY(6px)
    }
    #tabbed input:checked+label.tabs,
    #tabbed label.tabs:focus,
    #tabbed label.tabs:hover {
        -webkit-transform: translateY(0);
        -moz-transform: translateY(0);
        -ms-transform: translateY(0);
        -o-transform: translateY(0);
        transform: translateY(0)
    }
    #tabbed label.tabs:before {
        padding-right: 10px
    }
    #tabbed a,
    #tabbed input,
    #tabbed input:checked~.wrapper>div,
    #tabbed label.tabs,
    #tabbed select,
    #tabbed textarea {
        -webkit-transition: all ease .2s;
        -moz-transition: all ease .3s;
        -ms-transition: all ease .3s;
        -o-transition: all ease .3s;
        transition: all ease .2s
    }

</style>
            <section id="tabbed">
                <!-- First tab input and label -->
                <input id="t-1" name="tabbed-tabs" type="radio" checked="checked">
                <label for="t-1" class="tabs shadow entypo-pencil">Change Personal Information</label>
                <!-- Second tab input and label -->
                <input id="t-2" name="tabbed-tabs" type="radio">
                <label for="t-2" class="tabs shadow entypo-paper-plane">Change Email</label>
                <!-- Third tab input and label -->
                <input id="t-3" name="tabbed-tabs" type="radio">
                <label for="t-3" class="tabs tabs3 shadow entypo-menu">Change Password </label>
                <!-- Fourth tab input and label -->

                <!-- Tabs wrapper -->
                <div class="wrapper ">


                    <!-- Tab 1 content -->
                    <div class="tab-1 mytab">
                        <?php echo form_open(base_url() . "user-profile/" . $user_id); ?>
                        <?php echo form_hidden('form_type', 'form_info'); ?>

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*First Name </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('first_name'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*Last Name </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('last_name'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="actions" style="clear:both">
                            <div class="right">
                                <button class="genericBtn">Submit</button>
                            </div>

                        </div>

                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-2 mytab">
                        <?php echo form_open(base_url() . "user-profile/" . $user_id); ?>
                        <?php echo form_hidden('form_type', 'form_email'); ?>



                        <!--                            <div class="row">
                                                            <label for="d1_textfield">
                                                                <strong>User Name :  </strong>
                                                            </label>
                                                            <div style="margin-top:9px;">
                            <?php //echo $user_name; ?>
                                                            </div>
                                                        </div>
                                                        <br />-->

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*Your Email </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="text" name="email" id="email" value="<?php echo $email; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('email'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="row">
                            <label for="d1_textfield" title="The URL where you want to move after login.">
                                <strong>Redirection After Login</strong>
                            </label>
                            <div>
                                <input title="Enter here the URL where you want to move after login." class="textBoxLogin" type="text" name="default_url" id="default_url" value="<?php echo $default_url; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('default_url'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="actions" style="clear:both">
                            <div class="right">
                                <button class="genericBtn">Submit</button>
                            </div>

                        </div>

                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-3 mytab">
                        <?php echo form_open(base_url() . "user-profile/" . $user_id); ?>
                        <?php echo form_hidden('form_type', 'form_password'); ?>

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*Current Password </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="password" name="current_password" id="current_password" value="<?php echo $current_password; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('current_password'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*New password </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="password" name="new_password" id="new_password" value="<?php echo $new_password; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('new_password'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*Confirm new password </strong>
                            </label>
                            <div>
                                <input class="textBoxLogin" type="password" name="conf_new_password" id="conf_new_password" value="<?php echo $conf_new_password; ?>" />
                            </div>
                            <?php echo $this->form_validation->error('conf_new_password'); ?>
                        </div>
                        <br />
                        <br />

                        <div class="actions" style="clear:both">
                            <div class="right">
                                <button class="genericBtn">Submit</button>
                            </div>

                        </div>

                        <?php echo form_close(); ?>
                    </div>

                </div>

            </section>








<!--            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">-->
<!--                <tbody>-->
<!--                    <tr>-->
<!--                        <td> -->
<!--                            <label style="color:#2DA5DA;font-size: 16px">Change Personal Information</label>-->
<!--                            <br />-->
<!--                            <br />-->
<!--                            --><?php //echo form_open(base_url() . "user-profile/" . $user_id); ?>
<!--                            --><?php //echo form_hidden('form_type', 'form_info'); ?>
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*First Name </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="text" name="first_name" id="first_name" value="--><?php //echo $first_name; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('first_name'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*Last Name </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="--><?php //echo $last_name; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('last_name'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="actions">-->
<!--                                <div class="right">-->
<!--                                    <button class="genericBtn">Submit</button>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!---->
<!--                            --><?php //echo form_close(); ?>
<!--                        </td>-->
<!--                        <td>-->
<!--                            --><?php //echo form_open(base_url() . "user-profile/" . $user_id); ?>
<!--                            --><?php //echo form_hidden('form_type', 'form_email'); ?>
<!---->
<!--                            <label style="color:#2DA5DA;font-size: 16px">Change Email</label>-->
<!--                            <br />-->
<!--                            <br />-->
<!---->
<!--                            <!--                            <div class="row">-->
<!--                                                            <label for="d1_textfield">-->
<!--                                                                <strong>User Name :  </strong>-->
<!--                                                            </label>-->
<!--                                                            <div style="margin-top:9px;">-->
<!--                            --><?php ////echo $user_name; ?>
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <br />-->
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*Your Email </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="text" name="email" id="email" value="--><?php //echo $email; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('email'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield" title="The URL where you want to move after login.">-->
<!--                                    <strong>Redirection After Login</strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input title="Enter here the URL where you want to move after login." class="textBoxLogin" type="text" name="default_url" id="default_url" value="--><?php //echo $default_url; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('default_url'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="actions">-->
<!--                                <div class="right">-->
<!--                                    <button class="genericBtn">Submit</button>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!---->
<!--                            --><?php //echo form_close(); ?>
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>-->
<!--                            --><?php //echo form_open(base_url() . "user-profile/" . $user_id); ?>
<!--                            --><?php //echo form_hidden('form_type', 'form_password'); ?>
<!---->
<!--                            <label style="color:#2DA5DA;font-size: 16px">Change Password</label>-->
<!--                            <br />-->
<!--                            <br />-->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*Current Password </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="password" name="current_password" id="current_password" value="--><?php //echo $current_password; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('current_password'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*New password </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="password" name="new_password" id="new_password" value="--><?php //echo $new_password; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('new_password'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    <strong>*Confirm new password </strong>-->
<!--                                </label>-->
<!--                                <div>-->
<!--                                    <input class="textBoxLogin" type="password" name="conf_new_password" id="conf_new_password" value="--><?php //echo $conf_new_password; ?><!--" />-->
<!--                                </div>-->
<!--                                --><?php //echo $this->form_validation->error('conf_new_password'); ?>
<!--                            </div>-->
<!--                            <br />-->
<!---->
<!--                            <div class="actions">-->
<!--                                <div class="right">-->
<!--                                    <button class="genericBtn">Submit</button>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!---->
<!--                            --><?php //echo form_close(); ?>
<!--                        </td>-->
<!--                        <td>-->
<!--                            --><?php //echo form_open(base_url() . "user-profile/" . $user_id); ?>
<!--                            --><?php //echo form_hidden('form_type', 'form_cancel'); ?>
<!--                            <!--                            <label style="color:blue;font-size: 16px">Cancel your account</label>-->
<!--                                                        <br />-->
<!--                                                        <br />-->
<!--                            -->
<!--                            -->
<!--                                                        <div class="actions">-->
<!--                                                            <div class="right">-->
<!--                                                                <button class="submit">Click here to cancel request</button>-->
<!--                                                            </div>-->
<!--                            -->
<!--                                                        </div>-->
<!---->
<!--                            --><?php //echo form_close(); ?>
<!--                        </td>-->
<!--                    </tr>-->
<!--                </tbody>-->
<!--            </table>-->
        </div>
    </div>
</div>
<?php
$u_agent = $_SERVER['HTTP_USER_AGENT'];

// Next get the name of the useragent yes seperately and for good reason
if (preg_match('/Firefox/i', $u_agent)) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
} elseif (preg_match('/Chrome/i', $u_agent)) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
}
if ($ub == 'Chrome') {
    ?>
    <style>
        .genericBtn {
            margin-left: 244px !important;
        }
        .row label{
            width: 149px !important;
        }
    </style>
    <?php
}
?>
<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0px;
    }
    #user_district {
        background-color: #FFFFFF;
        border: 1px solid #0E76BD;
        color: #444444;
        cursor: pointer;
        height: 26px;
        line-height: 26px;
        margin-right: 8px;
        max-width: 151px;
        overflow: hidden;
        padding: 0;
        text-align: left;
        text-decoration: none;
        white-space: nowrap;
        width: 140px;
    }

    #example strong {
        float: none;
        height: 0;
        line-height: 33px;
        margin: none;
        font-size: 12px;
    }
    .row div{
        float: left;
    }
    .row label{
        display: block;
        float: left;
        width: 148px;
    }
    .row div input{
        float: none;

    }
    .genericBtn{
        margin-left: 208px;
    }
    .row {
        display: block;
        float: left;

        color:#333333;
        font-size: 100%;
        clear: both;
    }
    tr:hover{
        background-color: #FFFFFF;
    }

    .genericBtn {
        margin-left: 225px;
    }
    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>

<script>


    <?php
    if($formtype=='form_email'){
    ?>
    l = document.getElementById('t-2');
    l.click();
    <?php
    }

    if($formtype=='form_password'){
    ?>
    l = document.getElementById('t-3');
    l.click();
    <?php
    }
     ?>
</script>