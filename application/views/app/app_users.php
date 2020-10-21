<div class="applicationText">
    <h2>Application Users   </h2>
    <br clear="all" />
</div>

<style>
    .addappusertable tr:hover{
        background: none !important;

    }
    .addappusertable tr td{
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .row p{
        color: red;
    }
    
</style>
<?php //echo form_open(base_url()."applicatioin-users");
if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') != 'xmlhttprequest') {
    ?>
    <?php if ($this->acl->hasPermission('app_users', 'add')) { ?>
        <form style='margin: auto;width: 100%;' action="<?= base_url() ?>applicatioin-users" method="POST"
              class="full validate add_task_form" enctype="multipart/form-data" id='form_edit'/>
        <input type="hidden" name="htmldesc" id="htmldesc"/>
        <table cellpadding="0" cellspacing="0" border="0" class="display addappusertable" id="example"
               style='width: 100%;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
            <tbody>
            <?php if ($this->acl->hasSuperAdmin()) { ?>
                <tr>
                    <td style='width: 50px;'>
                        <label for="d1_textfield">
                            <strong>*Department: </strong>
                        </label>
                    </td>
                    <td colspan="3">

                        <div class="row">

                            <div>

                                <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id" style="width: 135px;"'); ?>
                            </div>
                            <?php echo $this->form_validation->error('department_id'); ?>
                        </div>
                        <br/>

                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>*Application </strong>
                    </label>
                </td>
                <td style='width: 50px;'>
                    <div class="row" id="dep_name">

                        <div>

                            <select id="app_id" name="app_id" style="width: 135px;">
                                <option value="">Select App</option>
                                <?php
                                if (isset($app_list)) {
                                    foreach ($app_list as $value) {
                                        ?>
                                        <option
                                            value="<?php echo $value['id'] ?>"><?php echo $value['name']; ?></option>
                                    <?php
                                    }
                                }
                                ?>

                            </select>
                        </div>
                        <?php echo $this->form_validation->error('app_id'); ?>
                    </div>
                </td>

                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>Application Views </strong>
                    </label>
                </td>

                <td style='width: 50px;'>
                    <div class="row" id="dep_name">
                        <div>

                            <select id="view_id" name="view_id" style="width:135px;">
                            </select>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>*Name</strong>
                    </label>
                </td>
                <td style='width: 50px;'>
                    <div class="row">
                        <div>
                            <input class="textBoxLogin" type="text" name="name" id="name"
                                   value="<?php echo set_value('name'); ?>"/>
                        </div>
                        <?php echo $this->form_validation->error('name'); ?>
                    </div>
                </td>

                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>Town </strong>
                    </label>

                </td>
                <td style='width: 50px;'>

                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="text" name="town" id="town"
                                   value="<?php echo set_value('town'); ?>"/>
                        </div>
                        <?php echo $this->form_validation->error('town'); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>*IMEI No. </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="text" name="imei_no" maxlength="15" id="imei_no"
                                   value="<?php echo set_value('imei_no'); ?>"/>
                        </div>
                        <?php echo $this->form_validation->error('imei_no'); ?>
                    </div>

                </td>

                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>*Mobile Number. </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="tel" name="mobile_number" maxlength="15" id="mobile_number"
                                   value="<?php echo set_value('mobile_number'); ?>"/>
                        </div>
                        <?php echo $this->form_validation->error('mobile_number'); ?>
                    </div>

                </td>
            </tr>
            <tr>

                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>District </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>

                            <select id="district" name="district" style="width: 135px;">
                                <option value="">None</option>
                                <option value='Lahore'>Lahore</option>
                                <option value='ATTOCK'>Attock</option>
                                <option value='Bhakkar'>Bhakkar</option>
                                <option value='Khushab'>Khushab</option>
                                <option value='Mianwali'>Mianwali</option>
                                <option value='OKARA'>Okara</option>
                                <option value='BAHAWALNAGAR'>Bahawalnagar</option>
                                <option value='BAHAWALPUR'>Bahawalpur</option>
                                <option value='RAHIM YAR KHAN'>Rahim Yar Khan</option>
                                <option value='DG Khan'>D G Khan</option>
                                <option value='Layyah'>Layyah</option>
                                <option value='Muzaffar Garh'>Muzaffargarh</option>
                                <option value='Rajanpur'>Rajanpur</option>
                                <option value='FAISALABAD'>Faisalabad</option>
                                <option value='Jhang'>Jhang</option>
                                <option value='TOBA TEK SINGH'>Toba Tek Singh</option>
                                <option value='Gujranwala'>Gujranwala</option>
                                <option value='Gujrat'>Gujrat</option>
                                <option value='Hafizabad'>Hafizabad</option>
                                <option value='MANDI BAHAUDDIN'>Mandi Baha ud din</option>
                                <option value='NAROWAL'>Narowal</option>
                                <option value='SIALKOT'>Sialkot</option>
                                <option value='Kasur'>Kasur</option>
                                <option value='Sheikhupura'>Sheikhupura</option>
                                <option value='Nankana Sahib'>Nankana Sahib</option>
                                <option value='Khanewal'>Khanewal</option>
                                <option value='Lodhran'>Lodhran</option>
                                <option value='Multan'>Multan</option>
                                <option value='PAKPATTAN'>Pakpattan</option>
                                <option value='SAHIWAL'>Sahiwal</option>
                                <option value='Vehari'>Vehari</option>
                                <option value='Chakwal'>Chakwal</option>
                                <option value='JHELUM'>Jhelum</option>
                                <option value='RAWALPINDI'>Rawalpindi</option>
                                <option value='Sargodha'>Sargodha</option>
                                <option value='Chiniot'>Chiniot</option>
                            </select>

                            <!--<input class="required" type="text" name="district" id="imei_no" value="<?php // echo set_value('district');               ?>" />-->
                        </div>
                        <?php echo $this->form_validation->error('district'); ?>
                    </div>

                </td>
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>*CNIC </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="text" name="cnic" maxlength="15" id="cnic"
                                   value="<?php echo set_value('cnic'); ?>"/>
                        </div>
                        <?php echo $this->form_validation->error('cnic'); ?>
                    </div>

                </td>


            </tr>
            <tr>

                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>Login User Name </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="text" name="login_user" id="login_user" value="<?php echo set_value('login_user');?>" />
                        </div>
                    </div>
                    <?php echo $this->form_validation->error('login_user'); ?>
                </td>
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>Login Password </strong>
                    </label>

                </td>
                <td style='width: 50px;'>
                    <div class="row">

                        <div>
                            <input class="textBoxLogin" type="text" name="login_password" id="login_password" value="<?php echo set_value('login_password');               ?>" />
                        </div>
                    </div>

                </td>

            </tr>
            <tr style="text-align: center;">
                <td style='width: 110px;' colspan="6">

                    <button class="genericBtn" style="">Add</button>

                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </form>

        <form style='margin: auto;width: 100%;' action="<?= base_url() ?>applicatioin-users-import" method="POST"
              class="full validate add_task_form" enctype="multipart/form-data" id='form_edit'/>

        <table cellpadding="0" cellspacing="0" border="0" class="display addappusertable" id="example"
               style='width: 100%;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
            <tbody>

                <tr>
        <?php if ($this->acl->hasSuperAdmin()) { ?>
            <td style='width: 50px;'>
                <label for="d1_textfield">
                    <strong>*Department: </strong>
                </label>
            </td>
            <td style="width:50px;">

                <div class="row">

                    <div>

                        <?php echo form_dropdown('department_id_import', $departments, $batch, 'id="department_id_import" style="width: 135px;"'); ?>
                    </div>
                    <?php echo $this->form_validation->error('department_id_import'); ?>
                </div>


            </td>
        <?php
        }
            ?>
                    <td style='width: 50px;'>
                        <label for="d1_textfield">
                            <strong>*Application </strong>
                        </label>
                    </td>
                    <td style='width: 50px;'>
                        <div class="row">

                            <div>

                                <select id="app_id_import" name="app_id_import" style="width: 135px;">
                                    <option value="">Select App</option>
                                    <?php
                                    if (isset($app_list)) {
                                        foreach ($app_list as $value) {
                                            ?>
                                            <option
                                                value="<?php echo $value['id'] ?>"><?php echo $value['name']; ?></option>
                                        <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                            <?php echo $this->form_validation->error('app_id_import'); ?>
                        </div>
                    </td>
                    <td style='width: 120px;'>
                        <label for="d1_textfield">
                            <strong>Application Views </strong>
                        </label>
                    </td>

                    <td style='width: 50px;'>
                        <div class="row">
                            <div>

                                <select id="view_id_import" name="view_id_import" style="width:135px;">
                                </select>
                            </div>

                        </div>
                    </td>
                </tr>




            <tr title="Add column in CSV &#13;imei&#13;mobile_number&#13;user_name&#13;district&#13;town&#13;login_user&#13;login_password&#13;cnic&#13;mobile netword">
                <td style='width: 50px;'>
                    <label for="d1_textfield">
                        <strong>Select File </strong>
                    </label>
                </td>

                <td style='width: 100px;' colspan="2">
                    <div class="row" id="dep_name">
                        <div>
                            <label style="width:26px;font-size: 22px;color:blue;" title="Add column in CSV &#13;imei&#13;mobile_number&#13;user_name&#13;district&#13;town&#13;login_user&#13;login_password&#13;cnic&#13;mobile netword">?</label>
                            <input type="file" name="user_import" style="width: auto;border:0px" >

                        </div>

                    </div>
                </td>
                <td style="float: left">
                    <div class="row">
                        <button class="genericBtn" style="width:100px;margin: 0px">Import CSV</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </form>
    <?php
    }

}?>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing34543" class="display appusersajaxtable">
            <thead>
                <tr>
                    <th class="Categoryh">Application Name</th>
                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                        <th class="Categoryh">Department Name</th>
                    <?php } ?>
                    <th class="Categoryh">View Name</th>
                    <th class="Categoryh">User Name</th>
                    <th class="Categoryh">District</th>
                    <th class="Categoryh">Town Name</th>
                    <th class="Categoryh">IMEI #</th>
                    <th class="Categoryh">CNIC #</th>
                    <th class="Categoryh">Mobile #</th>
                    <th class="Categoryh">Login User</th>
                    <th class="Categoryh">Login Password</th>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>


<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0;
    }
    #department_id,#app_id,#district,#view_id {
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
        width: 139px;
    }

    #example strong {
        float: none;
        font-weight: none;
        height: 0;
        line-height: 33px;
        margin: none;
    }
    .row div{
        float: left;
    }
    .row label{
        display: block;
        float: left;
        width: 127px;
    }
    .row div input{
        float: none;
        width: 123px;

    }
   .genericBtn {
  float: right;
  margin: -35px 15px 0;
}

    tr:hover{
        background-color: #FFFFFF;
    }
    table.dataTable thead th{
        padding: 3px 0 3px 10px;
        text-align: left;
    }
</style>