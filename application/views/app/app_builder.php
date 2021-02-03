
<?php
if ($view_list) {
    ?>
    <form style='margin: auto;width: 980px;' action="<?= base_url() ?>app-landing-page/<?php echo $app_id; ?>" method="POST" class="full validate add_task_form"  id='app_view'/>
    <select name='view_id' id='view_id' />
    <option value="default">Default View</option>
    <?php
    foreach ($view_list as $key => $value) {
        ?>
        <option value="<?php echo $key ?>" <?php
        if ($view_id == $key) {
            echo "selected";
        }
        ?>><?php echo $value; ?></option>
            <?php }
            ?>
    </select>
    </form>

    <?php
}
?>
<form style='margin: auto;width: 980px; position: relative;' action="<?= base_url() ?>app/edit/<?php echo $app_id; ?>" method="POST" class="full validate add_task_form"  enctype="multipart/form-data" id='app_edit'/>
<input type="hidden" id="is_edit" name="is_edit" value="0">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style='width: 969px;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
    <tbody>
        <tr>
            <td style='width: 123px;'><strong>Application Name</strong></td>
            <td  style='width: 150px;'><input class="required textBoxLogin" type="text" name="app_name" id="d1_textfield" value="<?php echo $name; ?>" /></td>
            <td  style='width: 235px;'>
                <strong style="width: 110px; line-height: 20px; margin-top: 8px;">Application Icon (.png only)</strong>

                <img id='img_app_icon' width="50" height="50" src="<?php
//                $file_headers = @get_headers(FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $icon);
                //$file_headers = @get_headers(base_url() . 'assets/images/data/form_icons/' . $app_id . '/' . $icon);
//                echo "<pre>";
//                print_r($file_headers);die;
//                if ($file_headers[0] == 'HTTP/1.1 200 OK') {
                if(file_exists(FCPATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'form_icons'.DIRECTORY_SEPARATOR.$app_id.DIRECTORY_SEPARATOR.$icon)){
//                    echo $image_path_icon = FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $icon;
                    echo $image_path_app_icon = base_url() . 'assets/images/data/form_icons/' . $app_id . '/' . $icon;
                } else {
                    echo $image_path_app_icon = FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png';
                    $icon='default_app.png';
                }
                ?>" alt="" onClick="$('#userfile').click();"/>
                <input type="file" name="userfile" id="userfile" accept="*.png" onchange="check_file()" style='display:none;'/>

            </td>
            <td  style='width: 235px;'>
                <strong style="width: 110px; line-height: 20px; margin-top: 8px;">Splash Screen (.png only)</strong>
                <img id='img_splash_screen' width="50" height="50" src="<?php
                if(file_exists(FCPATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'form_icons'.DIRECTORY_SEPARATOR.$app_id.DIRECTORY_SEPARATOR.$splash_icon)){
                    echo $image_path_icon = base_url() . 'assets/images/data/form_icons/' . $app_id . '/' .$splash_icon;
                } else {
                    echo $image_path_icon = FORM_IMG_DISPLAY_PATH . '../form_icons/splash.png';
                }
                ?>" alt="" onClick="$('#splashfile').click();" style="margin-top: 7px;"/>
                <input type="file" name="splashfile" id="splashfile" accept="*.png" onchange="check_file_splash(this)" style='display:none;'/>

            </td>
            <td></td>
            <td  style='width: 135px;'></td>
        </tr>
        <tr>
            <td colspan='5'>
                </td>
            <td>
                <a href="#" class="saveForm" id="saveForm" style="top:-10px;">Save</a>
                <?php if($this->acl->hasPermission('app', 'build')) {?>
                <a id="create_application" onclick="$('#is_edit').val('1');" style="top:-10px;" >Build New Version</a>
                <?php }?>
            </td>
        </tr>

        <?php if($super_app_user=='yes'){?>
        <tr>
            <td colspan="2" style="padding: 5px 0 5px 35px">
                <a href="<?= base_url() ?>assign-applicatioin-users/<?php echo $app_id?>" style="background-color: green; padding: 5px; color: white; border-radius: 5px;text-decoration: none;"  class="cbp-tm-icon-archive">
                    Assign application to other users
                </a>
                <a target="new" href="<?= base_url() ?>web/test/<?php echo $app_id?>" style="background-color: green; padding: 5px; color: white; border-radius: 5px; margin-left: 6px;text-decoration: none;" class="cbp-tm-icon-archive">
                    Test in Web Mode
                </a>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>
</form>

<div id="content" class="wrapper">
    <div id="controls-left">
        <ul id="add-form-title" style="display:none;">
            <li id="title-field" rel="<?php echo $name; ?>" icon_web="<?php echo $image_path_app_icon; ?>" icon_device="<?php echo $icon; ?>">Form Title</li>
        </ul>
        <ul id="form-fields">

            <li style='background: none;font-size: 16px;font-weight: bold;cursor: default;'>Control Menu</li>
            <?php
            if(count($forms)==0)
            {
               if($this->acl->hasPermission('form', 'add')) {?>
        
                <a style="text-decoration:none;"><li id="add_more_form_left" style="height: 30px;"><span></span>Add Form</li></a>
        
            <?php }
        
            }
            foreach ($forms as $form) {
                ?>
            <li id="form-icon-field" icon="<?php echo $form['icon']; ?>" icon-url="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form['icon']; ?>" form-url="<?php echo $form['linkfile']; ?>" form-title="<?php echo $form['title']; ?>" style="height:36px;">


                    <?php
                    if (!empty($form['icon'])) {
                        ?>
                        <img alt="" title="<?php echo $form['title']; ?>" class="formIconsUpload" src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form['icon'] ?>">
                        <?php
                    }?>
                        <span title="<?php echo $form['title'];?>">
                            <?php
                            if(strlen($form['title'])>15)
                                {
                                echo htmlspecialchars(substr($form['title'],0,15)."..");

                                }
                                else{
                                    echo htmlspecialchars($form['title']);
                            }
                            ?>
                        </span>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <div id="work-area-main">
        <div style="margin: 10px 0;text-align: center;">
            View on Different Screen
            <?php
//            echo "<pre>";
//            print_r($app_settings);die;

            ?>
            <select id="screen_size">
                <option value="1" <?php

                if (isset($app_settings->screen_view) && $app_settings->screen_view == '1') {
                    echo 'selected';
                }
                ?>>Development Mode</option>
                <option value="2" <?php
                if (isset($app_settings->screen_view) && $app_settings->screen_view == '2') {
                    echo 'selected';
                }
                ?>>Sony Xperia Z</option>
                <option value="3" <?php
                if (isset($app_settings->screen_view) && $app_settings->screen_view == '3') {
                    echo 'selected';
                }
                ?>>HTC One</option>
                <option value="4" <?php
                if (isset($app_settings->screen_view) && $app_settings->screen_view == '4') {
                    echo 'selected';
                }
                ?>>Samsung Galaxy Y-2</option>
            </select>
        </div>
        <input type="hidden" name="" id="add_after_widget" value="" />
        <div class="mobile-image">
            <div id="form-builder" class="container">
                <?php if (empty($description)) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-hd">
                            <form id="form-preview" action="" class="TTWForm" method="post" novalidate onSubmit="return submitform(this)">
                                <input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>" />
                            </form>
                        </div>
                    </div>
                </div>
                    <?php
                } else {
                    echo $description;
                }
                ?>
            </div>
        </div>
    </div>
    <div id="controls-right">
        <ul>
            <li><h3>Forms</h3></li>
            <?php
            if ($this->acl->hasPermission('form', 'edit')) {
            if(count($forms)>1){?>
            <li class="li-checked" style="padding: 20px!important;height: 22px;">Control Menu</li>
            <?php }else{?>
            <li id="edit_app_popup" style="cursor: pointer;height: 22px;">Edit Application</li>
            <?php }
            }?>
            <?php
            foreach ($forms as $form) {
                ?>
                <li>
                    <a href="<?= base_url() ?>app-form/<?php echo $form['form_id']; ?>" style="text-decoration:none;color:currentColor;">

                    <?php
                    if (!empty($form['icon'])) {
                        ?>
                        <img  class="formIconsUpload" alt="" title="<?php echo $form['title']; ?>" src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form['icon'] ?>">
                        <?php
                    } ?>
                        <span title="<?php echo $form['title'];?>">
                            <?php
                            if(strlen($form['title'])>10)
                                {
                                echo htmlspecialchars(substr($form['title'],0,10)."..");

                                }
                                else{
                                    echo htmlspecialchars($form['title']);

                            }
                            ?></span>
                   </a>
                    <div style="margin: 15px;position: absolute;right: 0;top: 10px;">
                    <?php
                    if (!$view_id) {
                        ?>
                        <a form_id="<?php echo $form['form_id']; ?>" app_id="<?=$app_id?>"  class="copy_form" title="Copy"><img src="<?= base_url() ?>assets/images/buildform.png" alt="" /></a>
                    <?php } ?>
                        <?php if($this->acl->hasPermission('form', 'delete')) {?>
                        <a href="javascript:void(0)" title="Delete"><img form_id ="<?php echo $form['form_id']; ?>" class="delete_form" src="<?= base_url() ?>assets/images/tableLink3.png" alt="" /></a>
                        <?php }?>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php if($this->acl->hasPermission('form', 'add')) {?>
        <ul id="add-form">
             <a style="text-decoration:none;"><li id="add_more_form" style="height: 30px;"><span></span>Add Form</li></a>
        </ul>
        <?php }?>
    </div>
</div>

<div class="clear"></div>

<!-- Application Dialogs -->
<div id="form-builder-dialog" class="modal-box">

    <div id="confirm-action" title="Confirm">
        <p class="warning message"></p>

        <p>Are you sure?</p>
    </div>

    <div id="set-form-title" title="Form Title">
        <div>Title: <input name="form_width" type="text"></div>
    </div>

    <div id="set-form-submit" title="Form Submit">
        <div>Value: <input name="form_submit" type="text"></div>
    </div>

</div>


<!-- Field settings dialog -->
<div id="field-settings" class="clearfix">

    <div id="settings-pointer">&nbsp;</div>
    <span id="close-field-settings">X</span>
    <ul id="field-settings-tabs">
        <li><a href="#main-settings">Main</a></li>
        <li><a href="#validation-settings">Validation</a></li>
    </ul>

    <div id="main-settings">
        <span id="current-field"></span>

        <div class="setting label clearfix">
            <label>Label</label>
            <input type="text" name="field-label-setting" id="field-label-setting"/>
        </div>

        <div class="setting clearfix">
            <label>Name</label>
            <input type="text" name="field-name-setting" id="field-name-setting"/>
        </div>

        <div id="input-settings-container">
            <span id="settings-loading">loading</span>

            <div id="input-settings">&nbsp;</div>
        </div>

    </div>
    <div id="validation-settings">

        <div class="val-settings-section">
            <div id="val-required"><input type="checkbox" checked="checked"/> Required</div>
        </div>

        <div class="val-settings-section">
            <div id="val-email"><input type="checkbox" 
                                       name="val-type"/><span class="option-title"> Email</span>
            </div>
            <div id="val-number"><input type="checkbox" 
                                        name="val-type"/><span class="option-title"> Number</span>
            </div>
        </div>

        <div class="val-settings-section">
            <div id="val-min" class="clearfix">
                Min: <input class="option setting" type="text"/></div>
            <div id="val-max" class="clearfix">
                Max: <input class="option setting" type="text"/></div>
            <div id="val-pattern" class="clearfix">
                Pattern: <input type="text" class="option setting"/></div>
        </div>
    </div>

</div>

<!-- jQuery Templates -->
<div id="form-elements"></div>

<div id="input-settings-template">
    <script id="input-settings-tmpl" type="text/x-jquery-tmpl">
        < div id = "${id}" class = "option clearfix" >
        < span class = "add-option" > & nbsp; < /span>
        < span class = "remove-option" > & nbsp; < /span>

        < div class = "option setting" >
        < input type = "text" name = "option-title" class = "option-title" / >
        < /div>
        < /div>
    </script>
</div>

<style>
    #overlay_loading img{
        margin: 12% 0 0 47%;
    }
   .field.f_50 {
        float: left;
        margin: 4%;
      }
</style>

<?php
$desc = '';
if (!empty($description)) {
    $desc = 'not empty';
}
?>
<script type="text/javascript">
    var Settings = {
        base_url: '<?php echo base_url(); ?>',
        app_id: '<?php echo $app_id; ?>',
        description: "<?php echo $desc; ?>"
    }
</script>
