<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2><?php echo $app_name; ?> Setting</h2>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>application-setting/<?= $app_id ?>" method="POST" class="full validate add_task_form"  enctype="multipart/form-data" />
                            <fieldset>
                                <legend>Map Settings</legend>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Default Latitude</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="latitude" id="d1_textfield" value="<?php echo $latitude; ?>" />
                                    </div>
                                    <?php echo $this->form_validation->error('latitude'); ?>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Default Longitude</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="longitude" id="d1_textfield" value="<?php echo $longitude; ?>" />
                                    </div>
                                    <?php echo $this->form_validation->error('longitude'); ?>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Default Zoom Level</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="zoom_level" id="d1_textfield" value="<?php echo $zoom_level; ?>" />
                                    </div>
                                    <?php echo $this->form_validation->error('zoom_level'); ?>
                                </div>
                                <div class="row" id="map_type_filter_div">
                                    <label for="d1_textfield">
                                        <strong>Map Type Filter </strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('map_type_filter', array('On' => 'On', 'Off' => 'Off'), $map_type_filter, 'id="map_type_filter"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('map_type_filter'); ?>
                                </div>
                                <div class="row" id="map_type_div">
                                    <label for="d1_textfield">
                                        <strong>Default Map Type</strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('map_type', array('Heat' => 'Heat Map', 'Pin' => 'Pin Map'), $map_type, 'id="map_type"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('map_type'); ?>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>District Filter </strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('district_filter', array('On' => 'On', 'Off' => 'Off'), $district_filter, 'id="district_filter"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('district_filter'); ?>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Sent By Filter </strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('sent_by_filter', array('On' => 'On', 'Off' => 'Off'), $sent_by_filter, 'id="sent_by_filter"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('sent_by_filter'); ?>
                                </div>

                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>UC Filter </strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('uc_filter', array('On' => 'On', 'Off' => 'Off'), $uc_filter, 'id="uc_filter"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('uc_filter'); ?>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>App Language </strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('app_language', array('english' => 'English', 'urdu' => 'Urdu'), $app_language, 'id="app_language"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('app_language'); ?>
                                </div>

                                <!--                                <fieldset>
                                                                    <legend>Other Settings:</legend>
                                                                    <div class="row">
                                                                        <label for="d1_textfield">
                                                                            <strong>Default Zoom Level</strong>
                                                                        </label>
                                                                        <div>
                                                                            <input class="required" type="text" name="app_name" id="d1_textfield" value="<?php echo set_value('app_name'); ?>" />
                                                                        </div>
                                <?php echo $this->form_validation->error('app_name'); ?>
                                                                    </div>
                                                                </fieldset>-->

                                <div class="row">
                                    <div class="right">
                                        <button class="genericBtn">Save</button>
                                    </div>

                                </div>
                            </fieldset>

                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .row,.actions{
        margin-left: 80px;
    }
    .row div{
        margin: 0 0 9px;
    }
    .row label{
        width: 145px;
        margin: 3px 3px 0 0;
        float: left;
    }
    #map_type_div{
        display: none;
    }

    .row p{
        color:red;
        font-size: 12px;
        margin: 0px;
    }
    #district_filter,#sent_by_filter,#map_type_filter,#uc_filter,#map_type,#app_language {
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
        margin-left: 238px;
    }
    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>