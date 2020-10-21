<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Edit App User</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 270px;">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open("app/editAppUser/" . $user_id); ?>

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>*USER NAME </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="user_name" id="first_name" value="<?php echo $user_name; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('user_name'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>*Application Views </strong>
                                </label>
                                <div>
                                    <select id="view_id" name="view_id" style="">
                                        <option value='0'>Default View</option>
                                        <?php
                                        if (isset($view_list)) {
                                            foreach ($view_list as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($view_id == $key) ? 'selected="selected"' : ''; ?> ><?php echo $value; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php echo $this->form_validation->error('view_id'); ?>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>*IMEI NO </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="imei_no" id="imei_no" value="<?php echo $imei_no; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('imei_no'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>*CNIC </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="cnic" id="cnic" value="<?php echo $cnic; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('cnic'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>TOWN </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="town" name="town" id="town" value="<?php echo $town; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('town'); ?>
                            </div>
                            <br />

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>District </strong>
                                </label>
                                <div>
                                    <?php
                                    $list_data = array(
                                        'Lahore' => 'Lahore',
                                        'ATTOCK' => 'ATTOCK',
                                        'Bhakkar' => 'Bhakkar',
                                        'Khushab' => 'Khushab',
                                        'Mianwali' => 'Mianwali',
                                        'Okara' => 'Okara',
                                        'BAHAWALNAGAR' => 'BAHAWALNAGAR',
                                        'BAHAWALPUR' => 'BAHAWALPUR',
                                        'RAHIM YAR KHAN' => 'RAHIM YAR KHAN',
                                        'DG Khan' => 'DG Khan',
                                        'Layyah' => 'Layyah',
                                        'Muzaffar Garh' => 'Muzaffar Garh',
                                        'Rajanpur' => 'Rajanpur',
                                        'FAISALABAD' => 'FAISALABAD',
                                        'Jhang' => 'Jhang',
                                        'TOBA TEK SINGH' => 'TOBA TEK SINGH',
                                        'Gujranwala' => 'Gujranwala',
                                        'Gujrat' => 'Gujrat',
                                        'Hafizabad' => 'Hafizabad',
                                        'MANDI BAHAUDDIN' => 'MANDI BAHAUDDIN',
                                        'NAROWAL' => 'NAROWAL',
                                        'SIALKOT' => 'SIALKOT',
                                        'Kasur' => 'Kasur',
                                        'Sheikhupura' => 'Sheikhupura',
                                        'Nankana Sahib' => 'Nankana Sahib',
                                        'Khanewal' => 'Khanewal',
                                        'Lodhran' => 'Lodhran',
                                        'Multan' => 'Multan',
                                        'PAKPATTAN' => 'PAKPATTAN',
                                        'SAHIWAL' => 'SAHIWAL',
                                        'Vehari' => 'Vehari',
                                        'Chakwal' => 'Chakwal',
                                        'JHELUM' => 'JHELUM',
                                        'RAWALPINDI' => 'RAWALPINDI',
                                        'Sargodha' => 'Sargodha',
                                        'Chiniot' => 'Chiniots',
                                    );

                                    $list_data[] = asort($list_data);
                                    array_pop($list_data);
                                    $list_data = array_merge(array('' => 'None'), $list_data);
//                                    echo '<pre>';
//                                    print_r($list_data); die;
                                    echo form_dropdown('user_district', $list_data, $user_district, 'id="user_district"')
                                    ?>
            <!--<input class="required" type="text" name="user_district" id="user_district" value="<?php echo $user_district; ?>" />-->
                                </div>
                                <?php echo $this->form_validation->error('user_district'); ?>
                            </div>
                            <br />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Mobile Number </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="tel" name="mobile_number" id="mobile_number" value="<?php echo $mobile_number; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('mobile_number'); ?>
                            </div>
                            <br />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Login User Name</strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="tel" name="login_user" id="login_user" value="<?php echo $login_user; ?>" />
                                </div>
                                <?php echo $this->form_validation->error('login_user'); ?>
                            </div>
                            <br />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Login Password </strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="tel" name="login_password" id="login_password" value="<?php echo $login_password; ?>" />
                                </div>
                            </div>
                            <br />
                            
                            <div class="row">
                                <div class="right">
                                    <button class="genericBtn">Submit</button>
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
    #user_district,#view_id {
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
        width: 700px;
        color:#333333;
        font-size: 100%;
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

