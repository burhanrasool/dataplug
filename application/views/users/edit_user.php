<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Edit User</h2>
            <span style="margin-bottom:0.75em; display: block;">
                (<span style="color:red"> * </span> fields are required )
            </span>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open("users/edituser/" . $user_id); ?>

                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong><span style="color:red"> * </span>Departments: </strong>
                                    </label>
                                    <div>

                                        <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('department_id'); ?>
                                </div>

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
                                                $selected = '';
                                                if ($key == $group_id)
                                                    $selected = 'selected';
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                                <?php echo $this->form_validation->error('group_id'); ?>
                            </div>

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>First Name </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('first_name'); ?>
                            </div>


                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Last Name </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('last_name'); ?>
                            </div>


                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>Your Email </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="email" id="email" value="<?php echo $email; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('email'); ?>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Password </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="password" name="password" id="password" value="" placeholder="Type here if change password"/>
                                </div>
                                
                            </div>
                           


<!--                            <div class="row">
                                <label for="d1_textfield">
                                    <strong><span style="color:red"> * </span>User Name </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="user_name" id="user_name" value="<?php //echo $user_name; ?>" />
                                </div>
                                <?php //echo $this->form_validation->error('user_name'); ?>
                            </div>-->


                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Redirection After Login </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="default_url" id="default_url" value="<?php echo $default_url; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('default_url'); ?>
                            </div>



                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>District</strong>
                                </label>
                                <div>
                                    <select id="district" name="district">
                                        <option value='' >Select District</option>
                                        <option value='Lahore' <?php echo ($district == 'Lahore') ? 'selected' : ''; ?>>Lahore</option>
                                        <option value='ATTOCK' <?php echo ($district == 'ATTOCK') ? 'selected' : ''; ?>>Attock</option>
                                        <option value='Bhakkar'<?php echo ($district == 'Bhakkar') ? 'selected' : ''; ?>>Bhakkar</option>
                                        <option value='Khushab' <?php echo ($district == 'Khushab') ? 'selected' : ''; ?>>Khushab</option>
                                        <option value='Mianwali' <?php echo ($district == 'Mianwali') ? 'selected' : ''; ?>>Mianwali</option>
                                        <option value='OKARA' <?php echo ($district == 'OKARA') ? 'selected' : ''; ?>>Okara</option>
                                        <option value='BAHAWALNAGAR' <?php echo ($district == 'BAHAWALNAGAR') ? 'selected' : ''; ?>>Bahawalnagar</option>
                                        <option value='BAHAWALPUR' <?php echo ($district == 'BAHAWALPUR') ? 'selected' : ''; ?>>Bahawalpur</option>
                                        <option value='RAHIM YAR KHAN' <?php echo ($district == 'RAHIM YAR KHAN') ? 'selected' : ''; ?>>Rahim Yar Khan</option>
                                        <option value='DG Khan' <?php echo ($district == 'DG Khan') ? 'selected' : ''; ?>>D G Khan</option>
                                        <option value='Layyah' <?php echo ($district == 'Layyah') ? 'selected' : ''; ?>>Layyah</option>
                                        <option value='Muzaffar Garh' <?php echo ($district == 'Muzaffar Garh') ? 'selected' : ''; ?>>Muzaffargarh</option>
                                        <option value='Rajanpur' <?php echo ($district == 'Rajanpur') ? 'selected' : ''; ?>>Rajanpur</option>
                                        <option value='FAISAL ABAD' <?php echo ($district == 'FAISAL ABAD') ? 'selected' : ''; ?>>Faisalabad</option>
                                        <option value='Jhang' <?php echo ($district == 'Jhang') ? 'selected' : ''; ?>>Jhang</option>
                                        <option value='TOBA TEK SINGH' <?php echo ($district == 'TOBA TEK SINGH') ? 'selected' : ''; ?>>Toba Tek Singh</option>
                                        <option value='Gujranwala' <?php echo ($district == 'Gujranwala') ? 'selected' : ''; ?>>Gujranwala</option>
                                        <option value='Gujrat' <?php echo ($district == 'Gujrat') ? 'selected' : ''; ?>>Gujrat</option>
                                        <option value='Hafizabad' <?php echo ($district == 'Hafizabad') ? 'selected' : ''; ?>>Hafizabad</option>
                                        <option value='MANDI BAHAUDDIN' <?php echo ($district == 'MANDI BAHAUDDIN') ? 'selected' : ''; ?>>Mandi Baha ud din</option>
                                        <option value='NAROWAL' <?php echo ($district == 'NAROWAL') ? 'selected' : ''; ?>>Narowal</option>
                                        <option value='SIALKOT' <?php echo ($district == 'SIALKOT') ? 'selected' : ''; ?>>Sialkot</option>
                                        <option value='Kasur' <?php echo ($district == 'Kasur') ? 'selected' : ''; ?>>Kasur</option>
                                        <option value='Sheikhupura' <?php echo ($district == 'Sheikhupura') ? 'selected' : ''; ?>>Sheikhupura</option>
                                        <option value='Nankana Sahib' <?php echo ($district == 'Nankana Sahib') ? 'selected' : ''; ?>>Nankana Sahib</option>
                                        <option value='Khanewal' <?php echo ($district == 'Khanewal') ? 'selected' : ''; ?>>Khanewal</option>
                                        <option value='Lodhran' <?php echo ($district == 'Lodhran') ? 'selected' : ''; ?>>Lodhran</option>
                                        <option value='Multan' <?php echo ($district == 'Multan') ? 'selected' : ''; ?>>Multan</option>
                                        <option value='PAKPATTAN' <?php echo ($district == 'PAKPATTAN') ? 'selected' : ''; ?>>Pakpattan</option>
                                        <option value='SAHIWAL' <?php echo ($district == 'SAHIWAL') ? 'selected' : ''; ?>>Sahiwal</option>
                                        <option value='Vehari' <?php echo ($district == 'Vehari') ? 'selected' : ''; ?>>Vehari</option>
                                        <option value='Chakwal' <?php echo ($district == 'Chakwal') ? 'selected' : ''; ?>>Chakwal</option>
                                        <option value='JHELUM' <?php echo ($district == 'JHELUM') ? 'selected' : ''; ?>>Jhelum</option>
                                        <option value='RAWALPINDI' <?php echo ($district == 'RAWALPINDI') ? 'selected' : ''; ?>>Rawalpindi</option>
                                        <option value='Sargodha' <?php echo ($district == 'Sargodha') ? 'selected' : ''; ?>>Sargodha</option>
                                        <option value='Chiniot' <?php echo ($district == 'Chiniot') ? 'selected' : ''; ?>>Chiniot</option>
                                    </select>
                                </div>
                                <?php echo $this->form_validation->error('default_url'); ?>
                            </div>


                            <div class="row">
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
    #group_id,#district,#department_id {
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
        margin-left: 208px;
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
        margin-left: 227px;
    }
    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>
