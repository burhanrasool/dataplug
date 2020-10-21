
<?php
if ($view_list) {
    ?>
<div style='margin: auto;width: 980px;'>
    <form  style="float: left;" action="<?= base_url() ?>app-form/<?php echo $form_id; ?>" method="POST" class="full validate add_task_form"  id='app_view'/>
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


    <form style="margin-left: 150px;" action="<?= base_url() ?>form-move-view/<?php echo $form_id; ?>" method="POST" class="full validate"  id='form_move_to_view'/>
    
    <label>Move Selected Form to view</label><select name='view_id' id='view_id' />
    <option value="default">Select View</option>
    <?php
    foreach ($view_list as $key => $value) {
        ?>
        <option value="<?php echo $key ?>" ><?php echo $value; ?></option>
            <?php }
            ?>
    </select>
    <input type="submit" value="Start Moving">
    </form>
    </div>
    <?php
}
?>
<style>
#icon_notify {
    height: 40px;
    position: absolute;
    width: 30px;
    display:none;
}

#icon_notify_counter {
    background: green none repeat scroll 0 0;
    border-radius: 10px;
    font-size: xx-small;
    left: -15px;
    padding: 4px;
    position: absolute;
    top: 17px;
}
    #overlay_loading img{
        margin: 12% 0 0 47%;
    }
    .tabs li {
        display: inline;
        list-style: none outside none;
        position: relative;
    }
    .tabli a {
        padding:5px !important;
    }
    .tabli{
        min-width:99px;

    }
    .tabli .field-actions{
        top:3px;
        width:37px;


    }
    .pageli{
        min-width:99px;

    }
    .pageli .field-actions{
        top:3px;
        width:37px;


    }
    .tabs a {
        background: none repeat scroll 0 0 #ffffff;
        color: #000000;
        display: inline-block;
        padding: 5px;
        text-decoration: none;
        margin-right: 2px;
        border: 1px solid;
        min-width: 40px;
        text-align: center;
    }
    .tabs a.active {
        background: none repeat scroll 0 0 #5F5FD1;
        color: #ffffff;
    }

    .tabdiv {
        padding: 10px;

    }


    .tbl_widget td
    {
        border:1px solid;
    }
    .active_td{
        background-color:#d3caca;
    }
    .field:hover{
        border:1px solid #f3f3f3;
        padding:10px 0;
    }
    .field{
        border:1px solid #f3f3ff;
        padding:10px 0;
    }
    <?php
    if ($app_settings->app_language == 'urdu') {
        ?>
        #form-preview{direction:rtl;}
        input.css-checkbox[type="checkbox"] + label.css-label, input.css-checkbox[type="radio"] + label.css-label{background-position:right top;padding-left:0;padding-right:23px;}
        .checkbox input.css-checkbox[type="checkbox"]:checked + label.css-label{background-position:right -18px;}
        <?php
    }
    ?>

</style>
<form style='margin: auto;width: 980px; position: relative;' action="<?= base_url() ?>form/edit/<?php echo $form_id ?>" method="POST" class="full validate add_task_form"  enctype="multipart/form-data"  id='form_edit'/>
<input type="hidden" name="htmldesc" id="htmldesc" />
<input type="hidden" id="is_edit" name="is_edit" value="0">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style='width: 969px;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
    <tbody>
        <tr>

            <td style='width: 90px;'>
                <strong>Form Name</strong></td>
            <td  style='width: 185px;'>
                <input class="required textBoxLogin" type="text" name="form_name" id="d1_textfield" value="<?php echo $form_name; ?>"/>
                <input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>" />
            </td>

            <td  style='width: 135px;height: 30px;'><strong>Form Icon (.png only)</strong>

            </td>
            <td>
                <img id='img_form_icon' style="width:10%;" src="<?php
                if(file_exists(FCPATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'form_icons'.DIRECTORY_SEPARATOR.$app_id.DIRECTORY_SEPARATOR.$form_icon)){
                    echo $imagepathicon = FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form_icon;
                } else {
                    echo $imagepathicon = FORM_IMG_DISPLAY_PATH . '../form_icons/default_1.png';
                    $form_icon='default_1.png';
                }
                ?>" alt="" onClick="$('#userfile').click();"/>
                <input style='display:none;' type="file" name="userfile" id="userfile" accept="*.png" onchange="check_file()"/>
            </td>

            <td  style='width: 110px;'>


            </td>
        </tr>
        <tr>

            <td style='width: 90px;'>
                <strong>Post URL</strong>
            </td>
            <td  style='width: 185px;'>
                <input class="required textBoxLogin" type="text" name="post_url" id="post_url" value="<?php echo $post_url; ?>"/>
            </td>

            <td  style='width: 140px;height: 30px;'>
                <strong>Filter</strong>
            </td>
            <td>
<!--                            <input class="required" type="text" name="filter" id="filter" value="<?php echo $filter; ?>"/>-->
                <select class="required customSelect" name="filter" id="filter" style="width: 150px;"/>
                </select>
            </td>
    <input type="hidden" name="possible_filters" value="" id="possible_filters">

    <td  style='width: 110px;'>
        <a class="saveForm" id="saveForm">Save</a>
        <?php if ($this->acl->hasPermission('app', 'build')) { ?>
            <a id="create_application" onclick="$('#is_edit').val('1');">Build New Version</a>
        <?php } ?>
    </td>
</tr>
 <?php if($super_app_user=='yes'){?>
<tr>
    <td colspan="2" style="padding: 5px 0 5px 10px">
        <a href="<?= base_url() ?>assign-applicatioin-users/<?php echo $app_id?>" style="background-color: green; padding: 5px; color: white; border-radius: 5px;text-decoration: none;" class="cbp-tm-icon-archive">
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
        <ul id="add-form-title" style='display:none;'>
            <li id="title-field" rel="<?php echo $form_name; ?>" icon_web="<?php echo $imagepathicon; ?>" icon_device="<?php echo $form_icon; ?>"><span></span>Form Title</li>
        </ul>
        <ul>
            <li style='background: none;font-size: 16px;font-weight: bold;cursor: default;text-align: center'>Controls</li>
        </ul>
        <ul id="add-form-tab">
            <li id="tab-field"><span></span>Add Tab</li>
        </ul>
        <ul id="add-form-page">
            <li id="page-field"><span></span>Add Page</li>
        </ul>
        <ul id="form-fields">

            <li id="text-field"><span></span>Text Field</li>
            <li id="number-field"><span></span>Number Field</li>
            <li id="hidden-field"><span></span>Hidden Field</li>
            <li id="textarea-field"><span></span>Text Area</li>
            <li id="select-field"><span></span>Dropdown</li>
            <li id="multiselect-field"><span></span>Dropdown Multi Select</li>
            <li id="select-api"><span></span>Dropdown Auto-Fill</li>
            <li id="radio-field"><span></span>Radio Button</li>
            <li id="checkbox-field"><span></span>Checkboxes</li>
            <li id="password-field" style="display:none;"><span></span>Password</li>
            <li id="date-field"><span></span>Date Field</li>
            <li id="time-field"><span></span>Time Field</li>
            <li id="camera-field"  style="" icon-url="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/takepicture.png' ?>"><span></span>Take Picture</li>
            <li id="lat-lang-field"  style=""><span></span>Take Location</li>
            <li id="take-time"  style=""><span></span>Take Time</li>
            <li id="random-field"  style=""><span></span>Random key generator</li>
            <li id="file-field"  style="display:none;"><span></span>File Upload</li>
            <li id="table-widget-2"><span></span>Table Widget(2 col)</li>
            <li id="table-widget-3"><span></span>Table Widget(3 col)</li>
            <li id="table-widget-6"><span></span>Table Widget(6 col)</li>

        </ul>
        <ul id="add-cnic-scanner">
            <li id="cnic-scanner"  style=""><span></span>CNIC Scanner</li>
            <li id="scan-cnic"  style="display:none;"><span></span>CNIC scan button</li>
            <li id="cnic-IDno"  style="display:none;"><span></span>CNIC IDno</li>
            <li id="cnic-name"  style="display:none;"><span></span>CNIC name</li>
            <li id="cnic-father-name"   style="display:none;"><span></span>CNIC father name</li>
            <li id="cnic-family-no"   style="display:none;"><span></span>CNIC family no</li>
            <li id="cnic-date-of-birth"   style="display:none;"><span></span>Date of birth</li>
            <li id="cnic-address"  style="display:none;"><span></span>Address</li>
            <li id="cnic-district"  style="display:none;"><span></span>District</li>
            <li id="cnic-city"  style="display:none;"><span></span>City</li>
        </ul>

        <ul id="add-loop-widget" style=''>
            <li id="loop-widget"><span></span>Loop Widget</li>
        </ul>
        <ul id="add-form-submit" style=''>
            <li id="submit-field"><span></span>Submit Button</li>
        </ul>

        <ul id="add-form-save">
            <li id="save-field"><span></span>Save Form Widget</li>
        </ul>
        <div class="title"  style="display:none;">HTML5 Fields</div>
        <ul id="html5-fields"  style="display:none;">
            <li id="email-field"><span></span>Email Field</li>
            <li id="url-field"><span></span>URL Field</li>
            <li id="range-field"><span></span>Range Field</li>
        </ul>

        <div class="title"  style="display:none;">Form Settings</div>
        <ul class="form-settings"  style="display:none;">
            <li id="form-theme"> Theme</li>
            <li id="form-width"> Width</li>
        </ul>
    </div>

    <div id="work-area-main">
        <div style="text-align:center; margin: 10px 0;">
            View on Different Screen<select id="screen_size">
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
                                <input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>" />
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
        <ul id="form-fields">
            <li><h3>Forms</h3></li>
            <?php
            if ($this->acl->hasPermission('form', 'edit')) {
                if (count($forms) > 1) {
                    ?>
                    <li style="height:22px;"><a href="<?= base_url() ?>app-landing-page/<?php echo $app_id ?>" style="text-decoration: none;color:currentColor;">Control Menu</a></span>
                    <?php } else { ?>
                    <li id="edit_app_popup" style="cursor: pointer;height: 22px;">Edit Application</li>
                    <?php
                }
            }
            ?>
            <?php
            foreach ($forms as $form) {
                $imagepathicon=base_url().'assets/images/data/form_icons/'.$app_id.'/'.$form['icon'];
                ?>
                <li class="<?php
                if ($form['form_id'] == $form_id) {
                    echo 'li-checked';
                } else {
                    echo '';
                }
                ?>" icon="<?php echo $form['icon']; ?>" icon-url="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form['icon']; ?>" form-url="<?php echo $form['linkfile']; ?>" form-title="<?php echo $form['title']; ?>"><span></span>
                        <?php if ($form['form_id'] != $form_id) { ?>
                        <a href="<?= base_url() ?>app-form/<?php echo $form['form_id']; ?>" style="text-decoration:none;color:currentColor;">
                        <?php } ?>
                        <?php
                        if (!empty($form['icon'])) {
                            ?>
                            <img class="formIconsUpload"alt="" title="<?php echo $form['title']; ?>" src="<?php echo $imagepathicon; ?>">
                        <?php }
                        ?>
                        <span title="<?php echo htmlspecialchars($form['title']); ?>">
                            <?php
                            if (strlen($form['title']) > 10) {
                                echo htmlspecialchars(substr($form['title'], 0, 10) . "..");
                            } else {
                                echo htmlspecialchars($form['title']);
                            }
                            ?></span>
                        <?php if ($form['form_id'] != $form_id) { ?>
                        </a>
                    <?php } ?>
                    <div style="margin: 15px;position: absolute;right: 0;top: 10px;">
                        <?php
                        if (!$view_id) {
                            ?>
                            <a form_id="<?php echo $form['form_id']; ?>" app_id="<?=$app_id?>"  class="copy_form" title="Copy"><img src="<?= base_url() ?>assets/images/buildform.png" alt="" /></a>
                        <?php } ?>
                        <?php if ($this->acl->hasPermission('form', 'delete')) { ?>
                            <a href="javascript:void(0)" title="Delete"><img form_id="<?php echo $form['form_id']; ?>" class="delete_form" src="<?= base_url() ?>assets/images/tableLink3.png" alt="" /></a>
                        <?php } ?>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php if ($this->acl->hasPermission('form', 'add')) { ?>
            <ul id="add-form">
                <a style="text-decoration:none;"><li id="add_more_form" style="height:30px;"><span></span>Add Form</li></a>
            </ul>
        <?php } ?>
    </div>


    <div id="controls-right" style="margin-top: 30px;">
        <ul style="height: 310px;">
	        <li style="padding: 0px; border-bottom: 0px none; margin-bottom: -4px;">
		        <ul id="form-fields">
		            <li style="background-color: rgb(14, 118, 188); height: 30px; padding: 5px;"><h3 style="color: white;">History</h3></li>
				</ul>

			</li>
	        <li style="height: 266px; padding: 0px;border-bottom: 0px none;">
		        <ul id="form-fields" style="max-height: 266px; min-height: 266px; overflow: auto;">
		            <?php
		            $i=0;
		            foreach ($history_list as $history) {
		            $i++;
		            	?>
		            <li class="<?php if($history_id==$history['id']){echo 'li-checked';}elseif($history_id=='' && $i==1){echo 'li-checked';}?>"  form-title="sadfsf" style="height: 17px;padding:6px">
		               <a href="<?= base_url() ?>app-form/<?php echo $form_id; ?>_history_<?php echo $history['id'];?>" style="text-decoration:none;color:currentColor;">
		               	<span title="Last updated datetime" style="<?php if($i==1){echo "left: 7px;width: 94%;";}else{?>left: 20px; width: 90%;<?php }?>"><?php if($i==1){echo "Current - ".$history['created_datetime'];}else{echo $history['created_datetime'];}?></span>

		               </a>
		            </li>
		            <?php }?>
				</ul>
			</li>
        </ul>
    </div>


</div>

<div class="clear"></div>

<!-- Field settings dialog -->
<div style="width:980px;margin: 0 auto;position: relative">
    <div id="field-settings" class="clearfix">

        <div id="settings-pointer">&nbsp;</div>

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

            <div class="setting name clearfix">
                <label>Name (Maximum length : 64)</label>
                <input type="text" name="field-name-setting" id="field-name-setting" maxlength="64"/>
            </div>

            <div class="setting defaultvalue clearfix">
                <label>Default Value</label>
                <input type="text" name="field-value-setting" id="field-value-setting"/>
            </div>

            <div class="setting actionbefore clearfix">
                <label>Action Required Before</label>
                <select type="text" name="field-action-required-setting" id="field-action-required-setting"></select>
            </div>
            <div class="setting trackingwidget clearfix">
                <label>Tracking Start / Stop</label>
                <select type="text" name="field-tracking-setting" id="field-tracking-setting">
                    <option value="" >Off</option>
                    <option value="start" >Start Tracking</option>
                    <option value="stop" >Stop Tracking</option>
                </select>
            </div>
            <div class="setting timetaken clearfix">
                <label>Time Taken</label>
                <select type="text" name="field-time-taken-setting" id="field-time-taken-setting">
                    
                    <option value="multiple" >Multiple time in a day</option>
                    <option value="once" >Once in a day</option>
                    
                </select>
            </div>
            <div class="setting timevalidation clearfix">
                <label>Time should be greater then below field</label>
                <select type="text" name="field-time-validation-setting" id="field-time-validation-setting"></select>
            </div>
            <div class="setting parentwidget clearfix">
                <label>Parent Widget</label>
                <select type="text" name="field-parent-setting" id="field-parent-setting"></select>
            </div>
            <div class="setting dependonwidget clearfix">
                <label>Depend On Widget</label>
                <select type="text" name="field-dependon-setting" id="field-dependon-setting"></select>
            </div>

            <div class="setting dependonvalue clearfix">
                <label>Depend On Widget Value</label>
                <input type="text" name="field-dependon-value-setting" id="field-dependon-value-setting"/>
            </div>
            <div class="setting apiurl clearfix">
                <label>API Url</label>
                <input type="text" name="field-api-setting" id="field-api-setting"/>
                <input type="button" onclick="loadselect();" value="Get Options from API">
            </div>
            <div id="input-settings-container">
                <span class = "add-option add-option-main" > Add Option </span>
                <span id="settings-loading">loading</span>
                <span id="remove-option-all" style="" >Delete All Options</span>

                <div id="input-settings" style="clear: both;">&nbsp;</div>

            </div>
        </div>
        <div id="validation-settings">

            <div class="val-settings-section">
                <div id="val-required" style="width: 100%"><input type="checkbox" checked="checked"/> Required</div>
                <div id="val-filter" style="width: 100%"><input type="checkbox" checked="checked"/> Filter</div>
                <div id="val-caption" style="width: 100%"><input type="checkbox"/> Show Caption</div>
                <div id="val-editable" style="width: 100%"><input type="checkbox"/> Show on edit form only</div>
                <div id="val-save_last_activity" style="width: 100%"><input type="checkbox"/> Save last activity value</div>
                <div id="val-multiple" style="width: 100%"><input type="checkbox"/> Multiple Select</div>
                <div id="val-text_validation" style="width: 100%; margin-top: 14px;">Text Validation: <br /><select style="width: 80%; margin: 2px 36px; border: 1px solid rgb(179, 179, 179);"><option value="">All Text </option><option value="alphabet_only">Alphabet [a-zA-Z ] Only</option><option value="numeric_only">Numeric [0-9] Only</option><option value="alphanumeric">Alphanumeric [a-zA-Z0-9. ] Only</option></select> </div>
            </div>

            <div class="val-settings-section">
                <div id="val-email"><input type="checkbox" name="val-type"/><span class="option-title"> Email</span>
                </div>
                <div id="val-number"><input type="checkbox" name="val-type"/><span class="option-title"> Number</span>
                </div>
            </div>

            <div class="val-settings-section">
                
                <div id="val-min" class="clearfix">Min: <input class="option setting" type="text"/></div>
                <div id="val-max" class="clearfix">Max: <input class="option setting" type="text"/></div>
                <div id="val-pattern" class="clearfix">Pattern: <input type="text" class="option setting"/></div>
                <div id="val-minlength" class="clearfix">Min Length: <input type="number" class="option setting"/></div>
                <div id="val-exactlength" class="clearfix">Max Length: <input type="number" class="option setting"/></div>
                <div id="val-maxlength" class="clearfix">Exact Length: <input type="number" class="option setting"/></div>

            </div>
        </div>
        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
            <div class="ui-dialog-buttonset">
                <button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" id="delete-element" element_id="" style="float: left; padding: 5px;"><span class="ui-button-text">Remove field</span></button>
                <button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" id="close-apply"><span class="ui-button-text">Apply</span></button>
            </div>
        </div>
    </div>
    <div id="page-settings" class="clearfix">

        <div id="settings-pointer">&nbsp;</div>

        <div id="main-settings">
            <span id="current-page" style="font-size: 18px; text-align: center; float: right; text-transform: uppercase; border: 1px solid rgb(255, 255, 255); width: 100%;display:none;"></span>
            <span style="font-size: 18px; text-align: center; float: right; text-transform: uppercase; border: 1px solid rgb(255, 255, 255); width: 100%;">Edit Page Setting</span>


            <div class="setting pagetitle clearfix">
                <label>Page Title</label>
                <input type="text" name="title_page" id="title_page">
            </div>
            <div class="setting next clearfix">
                <label>Next</label>
                <select type="text" name="next_page" id="next_page"></select>
            </div>
            <div class="setting previous clearfix">
                <label>Previous</label>
                <select type="text" name="previous_page" id="previous_page"></select>
            </div>
            <div style="width: 100%"><input type="checkbox" id="skip_page"/> Skip Allow</div>
            <div style="font-size: 18px; text-align: center; float: right; text-transform: uppercase; border: 1px solid rgb(255, 255, 255); width: 100%;">Logical Rules</div>
            <div class="setting pageelement clearfix">
            <select type="text" name="page_elements" id="page_elements"></select>
            <div id="element_value" style="margin-top: 20px; width: 100%;">
                <div style="float: left; width: 20%;">Value</div>
                <div style="float: right; width: 61%;"> Rule</div>
                <div style="float: left; width: 20%;">Value</div>
                <div style="float: right; width: 61%;"> Rule</div>

            </div>
            </div>

        </div>

        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
            <div class="ui-dialog-buttonset">

                <button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" id="close-apply-page"><span class="ui-button-text">Apply</span></button>
            </div>
        </div>

    </div>
</div>

<!-- Application Dialogs -->
<div id="form-builder-dialog" class="modal-box">

    <div id="confirm-action" title="Confirm">
        <p class="warning message"></p>

        <p>Are you sure?</p>
    </div>
    <div id="set-form-title" title="Form Title">
        <div>Title: <input name="form_width" type="text"></div>
    </div>
    <div id="set-col-title" title="Column Title">
        <div>Title: <input name="form_width" type="text"></div>
    </div>
    <div id="set-row-title" title="Row Title">
        <div>Title: <input name="form_width" type="text"></div>
    </div>
    <div id="set-tab-title" title="Tab Title">
        <div>Title: <input name="tab_width" type="text"></div>
    </div>

    <div id="set-form-submit" title="Form Submit">
        <div>Value: <input name="form_submit" type="text"></div>
        <label>Tracking Start / Stop</label>
        <select type="text" name="form_submit_tracking" id="form_submit_tracking">
            <option value="" >Off</option>
            <option value="start" >Start Tracking</option>
            <option value="stop" >Stop Tracking</option>
        </select>
    </div>
    <div id="set-save-only" title="Form Save">
        <div>Value: <input name="form_save_only" type="text"></div>
    </div>
    <div id="set-save-submit" title="Form Submit After Save">
        <div>Value: <input name="form_save_submit" type="text"></div>
    </div>

</div>



<!-- jQuery Templates -->
<div id="form-elements"></div>

<div id="input-settings-template">
    <script id="input-settings-tmpl" type="text/x-jquery-tmpl">
        <div id = "${id}" class = "option clearfix" >

        <span class = "remove-option" > &nbsp; </span>

        <div class = "option setting" >
        <input type = "text" name = "option-title" class = "option-title" />
        </div>
        </div>
    </script>
    <script id="select-settings-tmpl" type="text/x-jquery-tmpl">

        <div id = "${id}" class = "option clearfix" >

        <span class = "remove-option" > &nbsp; </span>
        <span style="margin-left: 25px;" class = "up-first-option" title="Move to First"> <i class="fa fa-angle-double-up fa-2x"></i>   </span>
        <span style="margin-left: 25px;" class = "up-option" title="Move Up" > <i class="fa fa-angle-up fa-2x"></i>   </span>
        <span style="margin-left: 25px;" class = "down-option" title="Move Down"> <i class="fa fa-angle-down fa-2x"></i> </span>
        <span style="margin-left: 25px;" class = "down-last-option" title="Move to Last" > <i class="fa fa-angle-double-down fa-2x"></i> </span>

        <div class = "option setting" >
        Value<input type = "text" name = "option-title" class = "option-title" />
        Display Value<input type = "text" name = "option-display-value" class = "option-display-value" />
        Parent Value<input type = "text" name = "option-parent-value" class = "option-parent-value" />
        </div>
        </div>
    </script>
</div>

<?php
$desc = '';
if (!empty($description)) {
    $desc = 'not empty';
}
?>

<script type="text/javascript">
    var Settings = {
        base_url: '<?php echo base_url(); ?>',
        form_id: '<?php echo $form_id; ?>',
        security_key: '<?php echo $security_key; ?>',
        filter: '<?php echo $filter; ?>',
        app_id: '<?php echo $app_id; ?>',
        description: "<?php echo $desc; ?>",
        upgrade_from_google_play: "<?php echo $upgrade_from_google_play; ?>",
        row_key: "<?php echo $row_key; ?>",
        location_required: "<?php echo $location_required; ?>"
    }
</script>


