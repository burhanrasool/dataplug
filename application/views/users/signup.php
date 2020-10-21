<style>
    .teamWrap h2, .teamWrap h3 {
        display: inline-block;
        margin: 60px 0 !important;
    }

</style>
<div class="team">
    <div class="teamWrap">
        <h1>Sign Up</h1>
        <span style="margin-bottom:0.75em; display: block;">
            (<span style="color:red"> * </span> fields are required )
        </span>

        <?php echo form_open("users/signup"); ?>
        <ul>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Departments: </strong>
                    </label>
                    <p>

                        <?php
                        $batch = '';
                        foreach ($departments as $key => $value) {
//                            if ($value == 'Public')
//                                $batch = $key;
                        }
                        echo form_dropdown('department_id', $departments, $batch, 'id="department_id"');
                        ?>
                        <?php echo $this->form_validation->error('department_id'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember" id="dep_name" style="display:none;">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Department Name: </strong>
                    </label>
                    <p>

                        <input class="" type="text" name="department_name" id="department_name" value="<?php echo set_value('department_name'); ?>" />
                        <?php echo $this->form_validation->error('department_name'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>First Name: </strong>
                    </label>
                    <p>

                        <input class="" type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" />
                        <?php echo $this->form_validation->error('first_name'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Last Name: </strong>
                    </label>
                    <p>

                        <input class="" type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" />
                        <?php echo $this->form_validation->error('last_name'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <label for="d1_textfield">
                        <strong><span style="color:red"> * </span>Your Email: </strong>
                    </label>
                    <p>

                        <input class="" type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" />
                        <?php echo $this->form_validation->error('email'); ?>
                    </p>
                </li>
            </div>
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

                        <input class="" type="password" name="conf_password" id="conf_password" value="<?php echo set_value('conf_password'); ?>" />
                        <?php echo $this->form_validation->error('conf_password'); ?>
                    </p>
                </li>
            </div>
            <div class="teamMember">
                <li> 
                    <p>

                        <button class="genericBtn">Submit</button>
                    </p>
                </li>
            </div>

        </ul>
        <?php echo form_close(); ?>
    </div>
</div>

<script>

//    $(document).ready(function() {
//        $('#department_id').trigger('change');
//    });
//
//    $('#department_id').change(function() {
//        var department = $(this).val();
//        if (department == 'new')
//        {
//            $('#dep_name').show();
//        } else
//        {
//            $('#dep_name').hide();
//            $('#department_name').val('');
//        }
//    });

</script>
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