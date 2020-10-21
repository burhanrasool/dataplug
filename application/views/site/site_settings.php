<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Site Default Setting</h2>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>site/sitesettings/1" method="POST" class="full validate add_task_form"  enctype="multipart/form-data" />
                            <fieldset>
                                <legend>Site Settings</legend>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>URL</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="url" id="url" value="<?php echo $url; ?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Directory Path</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="directory_path" id="directory_path" value="<?php echo $directory_path; ?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Android Target</strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="android_target" id="android_target" value="<?php echo $android_target; ?>" />
                                    </div>
                                </div>
                                <div class="row" id="map_type_filter_div">
                                    <label for="d1_textfield">
                                        <strong>Amazon Access Key </strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="s3_access_key" id="s3_access_key" value="<?php echo $s3_access_key; ?>" />
                                    </div>
                                </div>
                                <div class="row" id="map_type_filter_div">
                                    <label for="d1_textfield">
                                        <strong>Amazon Secret Key </strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="s3_secret_key" id="s3_secret_key" value="<?php echo $s3_secret_key; ?>" />
                                    </div>
                                </div>
                                <div class="row" id="map_type_filter_div">
                                    <label for="d1_textfield">
                                        <strong>Amazon Bucket </strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="s3_bucket" id="s3_bucket" value="<?php echo $s3_bucket; ?>" />
                                    </div>
                                </div>
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
        width: 100%;
    }
    .row label{
        width: 145px;
        margin: 3px 3px 0 0;
        float: left;
    }
    #map_type_div{
        display: none;
    }
</style>
<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0px;
    }
    #district_filter,#map_type_filter,#uc_filter,#map_type,#app_language {
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
        width: 100%;
    }
    .row label{
        display: block;
        float: left;
        width: 148px;
    }
    .row div input{
        float: none;
        width: 100%;

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

     h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>