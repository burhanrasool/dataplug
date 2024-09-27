<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
        <form style='margin: auto;width: 100%;' action="<?= base_url() ?>
			app/import_app_user_csv" method="POST"
              class="full validate add_task_form"
				enctype="multipart/form-data" id='form_edit'/>
            <h2>Import application user - Attached columns with table fields</h2>
            <input type="hidden" name="app_id_import" id="app_id_import"
				value="<?php echo $app_id_import; ?>"/>
            <input type="hidden" name="department_id_import" id="department_id_import"
				value="<?php echo $department_id_import; ?>"/>
            <input type="hidden" name="view_id_import" id="view_id_import"
				value="<?php echo $view_id_import; ?>"/>
            <input type="hidden" name="upload_file_path" 
				id="upload_file_path" value="<?php echo $upload_file_path; ?>"/>
            <table cellpadding="0" cellspacing="0" border="0" class="display"
				id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 230px;">
                <tbody>
                    <tr>
                        <td style="padding-top: 20px;padding-left: 100px">

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>User Name</strong>
                                </label>
                                <div>
                                    <select name="user_name" id="user_name" class="textBoxLogin">
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                               
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>IMEI Number</strong>
                                </label>
                                <div>
                                    <select name="imei_no" id="imei_no" class="textBoxLogin">
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>District</strong>
                                </label>
                                <div>
                                    <select name="district" id="district" class="textBoxLogin">
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Town</strong>
                                </label>
                                <div>
                                    <select name="town" id="town" class="textBoxLogin">
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>CNIC</strong>
                                </label>
                                <div>
                                    <select name="cnic" id="cnic" class="textBoxLogin">
                                        <option value=""></option>
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Mobile Number</strong>
                                </label>
                                <div>
                                    <select name="mobile_number" id="mobile_number" class="textBoxLogin">
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Mobile Network</strong>
                                </label>
                                <div>
                                    <select name="mobile_network" id="mobile_network" class="textBoxLogin">
                                        <option value=""></option>
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Login User</strong>
                                </label>
                                <div>
                                    <select name="login_user" id="login_user" class="textBoxLogin">
                                        <option value=""></option>
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Login Password</strong>
                                </label>
                                <div>
                                    <select name="login_password" id="login_password" class="textBoxLogin">
                                        <option value=""></option>
                                        <?php foreach ($fields_name as $keyf => $fields) {?>
                                            <option value="<?php echo $keyf; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            </td>
                        </tr>
                        <tr>
                        <td style="width: 100%;">
                            <div class="row" style="float: right;" >
                                
                                <label for="d1_textfield">
                                    <strong> </strong>
                                </label>
                                <div>
                                    <a href="<?php echo base_url().'applicatioin-users'?>
										"  class="genericBtn" style="">
                                    Back
                                    </a>
                                    <button class="genericBtn" style="width: 100px; margin: 0px; padding: 7px;">
										Import CSV</button>
                                </div>
                            </div>
                        </td>
                        </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

<style>
    .error p{
        color: red;
        font-weight: bold;
    }
    #availability_status {
        color: green;
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

    .row {
        display: block;
        float: left;
        color:#333333;
        font-size: 100%;
        width: 50%;
    }
    tr:hover{
        background-color: #FFFFFF;
    }


    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>