<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Add New User</h2>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 330px;">

            </span>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span style="margin-bottom:0.75em; display: block;">
                (<span style="color:red"> * </span> fields are required )
                                <br>
                                <?php
                                echo ($this->session->flashdata('message'));
                                ?>
                        </td>
                    </tr>
                    <tr>

                        <td>
                            <?php echo form_open(base_url() . "add-new-user"); ?>

                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong><span style="color:red"> * </span>Department </strong>
                                    </label>
                                    <div>

                                        <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('department_id'); ?>
                                </div>
                                <br />
                            <?php } ?>
                            <div class="row" id="dep_name">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Group </strong>
                                </label>
                                <div>

                                    <select id="group_id" name="group_id">
                                        <?php
                                        if (isset($group_list)) {
                                            foreach ($group_list as $key => $value) {
                                                echo $value;
                                                ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                                <?php echo $this->form_validation->error('group_id'); ?>
                            </div>
                            <br />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>First Name </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('first_name'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Last Name </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('last_name'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Your Email </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('email'); ?>
                            </div>
                            <br />


<!--                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>User Name</strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="username" id="username" value="<?php //echo set_value('username'); ?>" />
                                </div>
                                <?php //echo $this->form_validation->error('username'); ?>
                            </div>
                            <br />-->

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Password </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('password'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Confirm Password </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="password" name="conf_password" id="conf_password" value="<?php echo set_value('conf_password'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('conf_password'); ?>
                            </div>

                            <div class="actions" style="clear: both;">
                                <div class="right">
                                    <button class="genericBtn" style="margin-left: 158px;">Submit</button>
                                    <a  href="<?= base_url() ?>users" class="genericBtn" style="height: 18px;padding: 5px;text-align: center;width: 54px;margin-left: 0px;">Back</a>
                                </div>

                            </div>

                            <?php echo form_close(); ?>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0px;
    }
    #group_id,#department_id {
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
        width: 146px;
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
        margin-left: 226px;
    }
    .row {
        display: block;
        float: left;
        width: 700px;
        color:#333333;
        font-size: 100%;
    }
    tr:hover{
        background-color: #FFFFFF;
    }

    .genericBtn {
        margin-left: 238px;
    }
    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>
