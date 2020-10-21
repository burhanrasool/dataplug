
<style>
    
    #work-area{
        min-height: 539px !important;
    }
    .login{
        width: 485px !important;
    }
    #cboxContent{
        height: 307px !important;
    }
    #cboxClose{
        background: none !important;
    }
    
    .tbl_widget td
    {
        border:1px solid;
    }
    .active_td{
        background-color:#d3caca;
    }

</style>

<form style='margin: auto;width: 980px; position:  relative;' action="<?= base_url() ?>form/appbuilder/1" method="POST" class="full validate add_task_form"  enctype="multipart/form-data"  id='form_edit'/>
<input type="hidden" name="htmldesc" id="htmldesc" />
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style='width: 100%;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
    <tbody>
        <tr>
            <td style='width: 25px;'>
<!--                <a href="<?= base_url() ?>app-landing-page/<?php //echo $app_id                  ?>" style=""><img style="width: 60px;height: 38px;" src="<?= base_url() ?>assets/images/goback.png" class="float_left" alt="" /></a>-->
            </td>
            <td style='width: 115px;'>
                <strong>Application Name</strong></td>
            <td  style='width: 140px;'>
                <input class="required textBoxLogin" type="text" name="app_name" id="d1_textfield" value="<?php //echo //$form_name;                  ?>"/>
                <?php echo $this->form_validation->error('app_name'); ?>
            </td>

            <td  style='width: 200px;height: 30px;'><strong>Application Icon (.png only)</strong>
                <img  id='img_app_icon' src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png'; ?>" alt="" onClick="$('#appfile').click();" />
                <input type="file" name="appfile" id="appfile" accept="*.png" onchange="check_appfile()" style='display:none;'/>
            </td>
            <td style='width: 200px;height: 30px;'><strong>Form Icon (.png only)</strong>
                <img id='img_form_icon' src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/default_1.png'; ?>" alt="" onClick="$('#formfile').click();"/>
                <input style='display:none;' type="file" name="formfile" id="formfile" accept="*.png" onchange="check_formfile()"/>
            </td>
            <td  style='width: 110px;'>
                <a class="saveForm" id="saveFormAlone">Save</a>
            </td>
        </tr>
    </tbody>
</table>
</form>
<div id="content" class="wrapper">
    <div id="controls">
        <ul id="add-form-title" style='display:none;'>
            <li id="title-field" rel="Form Title<?php //echo $form_name;                  ?>"><span></span>Form Title</li>
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
            <li id="file-field"  style="display:none;"><span></span>File Upload</li>
            <li id="table-widget-2"><span></span>Table Widget(2x2)</li>
            <li id="table-widget-3"><span></span>Table Widget(3x2)</li>
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
            <li id="date-field"><span></span>Date Field</li>
            <li id="range-field"><span></span>Range Field</li>
        </ul>

        <div class="title"  style="display:none;">Form Settings</div>
        <ul class="form-settings"  style="display:none;">
            <li id="form-theme"> Theme</li>
            <li id="form-width"> Width</li>
        </ul>
    </div>

    <div id="work-area">
        <input type="hidden" id="is_edit" value="0">
        <div style="text-align:center;margin: 10px 0;">
            View on Different Screen<select id="screen_size">
                <option value="1">Development Mode</option>
                <option value="2">Sony Xperia Z</option>
                <option value="3">HTC One</option>
                <option value="4" selected>Samsung Galaxy Y-2</option>

            </select>
        </div>
        <input type="hidden" name="" id="add_after_widget" value="" />
        <div class="mobile-image">
            
            <div id="form-builder" class="container">
                <?php if (empty($description)) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-hd">
                            <form id="form-preview" action=""  method="post" novalidate onSubmit="return submitform(this)" role="form">
                                <input type="hidden" name="form_id" id="form_id" value="<?php //echo $form_id;                  ?>" />
                                <input type="hidden" id="form_operation" value="" />
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
                <label>Name</label>
                <input type="text" name="field-name-setting" id="field-name-setting"/>
            </div>

            <div class="setting defaultvalue clearfix">
                <label>Default Value</label>
                <input type="text" name="field-value-setting" id="field-value-setting"/>
            </div>

            <div class="setting actionbefore clearfix">
                <label>Action Required Before</label>
                <select type="text" name="field-action-required-setting" id="field-action-required-setting"></select>
            </div>
            <div class="setting parentwidget clearfix">
                <label>Parent Widget</label>
                <select type="text" name="field-parent-setting" id="field-parent-setting"></select>
            </div>
            <div class="setting parentwidget clearfix">
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
                <div id="val-caption" style="width: 100%"><input type="checkbox" checked="checked"/> Show Caption</div>
                <div id="val-editable" style="width: 100%"><input type="checkbox"/> Show on edit form only</div>
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
    <div id="set-page-title" title="Page Title">
        <div>Title: <input name="page_width" type="text"></div>
    </div>

    <div id="set-form-submit" title="Form Submit">
        <div>Value: <input name="form_submit" type="text"></div>
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

        <div class = "option setting" >
        Value<input type = "text" name = "option-title" class = "option-title" />
        Display Value<input type = "text" name = "option-display-value" class = "option-display-value" />
        Parent Value<input type = "text" name = "option-parent-value" class = "option-parent-value" />
        </div>
        </div>
    </script>
</div>

