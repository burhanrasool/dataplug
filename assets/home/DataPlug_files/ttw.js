/**
 * Created by 23rd and Walnut
 * www.23andwalnut.com
 * User: Saleem El-Amin
 * Date: Jan 13, 2010
 * Time: 12:42:27 PM
 */

$(function()
{
    //get rid of the javascript warning
    var workspace = document.getElementById('form-preview');
    //jsWarning = document.getElementById('javascript-warning');
    //workspace.removeChild(jsWarning);


    var TTWFormBuilder = function()
    {
        //Pseudo stop if this is ie. 
        if ($.support.leadingWhitespace == false)
            ieNotice('Awww Shucks! This app only works in modern browsers.');

        var loading_indicator = '<div class="loading-indicator">' +
                '<div class="loading-overlay">&nbsp;</div>' +
                '<div class="loading-content">' +
                'Loading...' +
                '</div>' +
                '</div>',
                content = $('#content'),
                formElements = [],
                formPreview = $('#form-preview'),
                addedFieldCount = 0,
                fieldSettings = $('#field-settings'),
                fieldSettingsInterval = 0,
                validationSettings = fieldSettings.find('#validation-settings'),
                inputSettingsTmpl = $('#input-settings-tmpl'),
                inputSettings = fieldSettings.find('#input-settings'),
                hoverField,
                columnWidth,
                settings = {},
                notification = $('#notification'),
                notificationTimeout,
                sizeClasses = 'f_25 f_50 f_75 f_100',
                sizeClassBase = 25,
                controls = $('#controls'),
                loader = {},
                useUniform = true,
                cachedOption = ['', ''],
                settingsLoadingInd = $('#settings-loading'),
                formBuilder = $('#form-builder'),
                pv,
                theme = 'elegant',
                base_url = $('#base_url').val();





        function init()
        {

            loadingIndicator();

            content.bind('initFormBuilderComplete', function()
            {
                loadingIndicator('remove');
                checkDependencies();
            });

            formPreview.sortable({
                start: function()
                {
                    hideFieldSettings();
                }
            });
            
            $(".tabdiv").sortable({
                start: function()
                {
                    //hideFieldSettings();
                }
            });
            $(".tabs").sortable({
                start: function()
                {
                    //hideFieldSettings();
                }
            });
            

            fieldSettings.tabs();

            var loadApp = new loader();
            loadApp.start();

        }




        //Loads all the resources for the application
        loader = function()
        {






            function start()
            {
                step_1();
            }





            function step_1()
            {
                loadFormElements(step_2);
            }





            function step_2()
            {
                bindEvents(step_3);
            }





            function step_3()
            {
                var preload = [
                    base_url + 'assets/form_builder/application/images/modal_header.png',
                    base_url + 'assets/form_builder/application/images/modal_footer.png',
                    base_url + 'assets/form_builder/application/images/pencil.png',
                    base_url + 'assets/form_builder/application/images/remove.png',
                    base_url + 'assets/form_builder/application/images/settings-pointer.png',
                    base_url + 'assets/form_builder/application/images/add_16.png',
                    base_url + 'assets/form_builder/application/images/close_16.png',
                    base_url + 'assets/form_builder/application/images/exclamation.png',
                    base_url + 'assets/form_builder/application/images/plus_16.png',
                    base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/images/sprite.png',
                    base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/images/next.png',
                    base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/images/prev.png'
                ];
                preloadImages(preload, step_4)
            }





            function step_4()
            {
                content.trigger('initFormBuilderComplete');
            }





            return{
                start: start
            }

        };

        function countField()
        {
            var i = 1;
            $(".field").each(function() {
                if ($('#field' + i + '-container').length == 0)
                {
                    return i - 1;
                }
                i++;
            });
            return i - 1;
        }
        function countTab()
        {
            var i = 1;
            $(".tabli").each(function() {
                
//                if ($('#field' + i + '-tab').length == 0)
//                {
//                    return i;
//                }
                i++;
            });
            return i;
        }

        function nextTabId()
        {
            var i = 1;
            $(".tabli").each(function() {
                
                if ($('#field' + i + '-tab').length == 0)
                {
                    return i;
                }
                i++;
            });
            return i;
        }
        function checkDependencies()
        {
            $.post(base_url + 'assets/form_builder/application/dependency_check.php', function(data) {
                if (data != 'SUCCESS')
                    showAlert(data);
            });
        }


        /* Bind all event handlers */
        function bindEvents(callback)
        {

            $('#form-fields li, #html5-fields li').click(function()
            {
                addField($(this));

            });
            
            $('#add-cnic-scanner li').click(function()
            {
//                alert($('#cnic-name').attr('name'));
//                if($('#cnic-name').val() !== undefined)
//                {
                    var addedFieldCount = countField();
                    var total_tabs = countTab();
                    if(total_tabs == 1){
                        if(addedFieldCount>0){
                            addFormTabElement($('#tab-field'),'');
                        }
                    }
                    addFormTabElement($('#tab-field'),'CNIC Scanner');
                    addField($('#scan-cnic'));
                    addField($('#cnic-IDno'));
                    addField($('#cnic-name'));
                    addField($('#cnic-father-name'));
                    addField($('#cnic-family-no'));
                    addField($('#cnic-date-of-birth'));
                    addField($('#cnic-address'));
                    addField($('#cnic-district'));
                    addField($('#cnic-city'));
                    
                    
//                }
//                else
//                {
//                    alert('You already added this widget');
//                }

            });

            $('#add-form-tab li').click(function()
            {
                addFormTabElement($(this),'');

            });
            
            $('#add-form-title li').click(function()
            {
                $form_name = $(this).attr('rel');
                addFormElement('title',$form_name);

            });

            $('#add-form-submit li').click(function()
            {
                addFormElement('submit','');

            });

            $('#form-width').click(function()
            {
                promptForInput($('#set-form-width'), setFormWidth);
            });

            $('#form-theme').click(function() {
                promptForInput($('#set-form-theme'), setTheme);
            });

            $('#form-preview').submit(function()
            {
                previewNotification();
                return false;
            });

            $('#clear').click(function()
            {
                confirmAction(resetForm, 'Reset Form');
                return false;
            });

            $('#save').click(function()
            {
                saveForm();
                return false;
            });


            /** Edit Field Attributes **/

            /** Field Settings Box **/
            $('.edit-field').live('click', function()
            {
                hoverField = $(this).parents('.field');
                clearInterval(fieldSettingsInterval);
                positionFieldSettings();
                resetFieldSettingsDialog(hoverField);

                fieldSettings.animate({opacity: 1}, 'fast').find('#current-field').html(hoverField.attr('id'));
            });

            $('.delete-field, .delete-title, .delete-submit').live('click', function()
            {
                var field = $(this).parents('.field');

                confirmAction(function() {
                    removeField(field);
                    linkCaptionField();
                    if ($('.cameraclass').length < 1)
                    {
                        $('#takepic').remove();
                    }
                }, 'Delete Field');
                
            });
            $('.delete-tab').live('click', function()
            {
                var parent_li_id = $(this).parent().parent().attr('id');
                var child_anchor_id = $('#'+parent_li_id+' a').attr('rel');
                
                confirmAction(function() {
                    $('#'+child_anchor_id).remove();
                    $('#'+parent_li_id).remove();
                    var total_tabs = countTab();
                    if(total_tabs == 1)
                        {
                            $('#tabs-header').remove();
                        }
                }, 'Delete Tab');
            });

            $('.edit-title').live('click', function()
            {
                var formtitle = $(this).parent().parent().find('h2').html();
                $('#set-form-title').find('input').val(formtitle);
                editFormTitle();
            });
            $('.edit-tab').live('click', function()
            {
                var tabtitle = $(this).parent().parent().find('a').html();
                $('#set-tab-title').find('input').val(tabtitle);
                var parent_id = $(this).parent().parent().attr('id');
                editTabTitle(parent_id);
            });

            $('.edit-submit').live('click', function() {
                var submittitle = $(this).parent().parent().find('input').val();
                $('#set-form-submit').find('input').val(submittitle);
                promptForInput($('#set-form-submit'), setSubmitValue);
            });

            $('#field-settings, #field-settings input').live('focus', function(event)
            {
                clearInterval(fieldSettingsInterval);
            });

//            fieldSettings.live('focusout', function()
//            {
//                fieldSettingsInterval = setTimeout(function()
//                {
//                    hideFieldSettings();
//                }, 2000);
//            });

            /** Field Settings Inputs **/
            $('#field-label-setting').bind('textchange, keyup', function(event, previousText)
            {
                var currentField = fieldSettings.find('#current-field').text();
                $('#' + currentField).find('label').html($(this).val());
            });

            //bind event for input name
            $('#field-name-setting').bind('textchange, keyup', function(e)
            {
                $(this).val($(this).val().replace(/[^a-zA-Z 0-9_-]+/g, ""));
                $(this).val($(this).val().replace(' ', "_"));
                var currentField = fieldSettings.find('#current-field').text(),
                        $currentField = $('#' + currentField),
                        val = $(this).val();

                if ($currentField.hasClass('checkbox-group') && (val.substr(val.length - 2, val.length - 1) != '[]'))
                {
                    var last = val[val.length - 1];
                    val = (last == '[') ? val + ']' : val + '[]';
                }

                $currentField.find('input, textarea, select').each(function(){
                    
                    if(!($(this).attr('rel')!='undefined' && $(this).attr('rel')=='skip')){
                        $(this).attr('name', val);
                        linkCaptionField();
                    }
                    
                });
                
                
            });
            
            //bind event for input Value
            $('#field-value-setting').bind('textchange', function()
            {
                var currentField = fieldSettings.find('#current-field').text(),
                        $currentField = $('#' + currentField),
                        val = $(this).val();
                        $currentField.find('input[type="text"]').each(function(){
                            $(this).attr('Value',val);
                        });
                
            });

            //Field Settings add and remove buttons
            $('.add-option').live('click', function()
            {
                addFieldOption();
            });

            $('.remove-option').live('click', function()
            {
                removeFieldOption($(this));
            });

            $('#close-field-settings').click(function()
            {
                hideFieldSettings();
            });

            $('#download').click(function()
            {
                verifyForm();
                return false;
            });

            $('#preview').click(function()
            {
                previewMode();
                return false;
            });

            $('.form-format').click(function()
            {
                gatherFormDetails($(this).attr('id').slice(4));
                return false;
            });

            $('.slider .handle, .slider .progress').live('click', function()
            {
                if (!$(this).parents('form').hasClass('preview-mode'))
                    showNotification('Use preview mode to test range input');
            });

            $('#help').click(function()
            {
                displayInstructions();
                return false;
            });

            execute_callback(callback);
        }




        /** Add or remove the loading indicator **/
        function loadingIndicator(remove)
        {

            if (remove == 'undefined' || !remove)
            {
                if (!$('.loading-indicator').length)
                    $('body').append(loading_indicator);
            }
            else
            {
                $('.loading-indicator').remove();
            }

        }




        /** Load the form elements template **/
        function loadFormElements(callback)
        {
            $.get(base_url + 'assets/form_builder/application/form_elements.html', function(data)
            {
                formElements = $('#form-elements').html(data);

                execute_callback(callback);
            });


        }


        function getActiveTab()
        {
            if($("#tabs-header").length > 0)
            {
                var active_tab=0;
                $('ul.tabs a').each(function(){
                     if($(this).hasClass('active'))
                         {
                             active_tab = $(this).attr('rel');
                             return active_tab;
                         }
                });
            }
            return active_tab;
        }


        /** Add a field to the form **/
        function addField(fieldobj)
        {

            
            
            var fieldType = fieldobj.attr('id');
            
            var iconurl ='';
            if (fieldType == 'camera-field')
            {
                if($('#takepic').val() != 'takepicture')
                {
                    $('#form-preview').append('<input type="hidden" name="takepic" id="takepic" value="takepicture" />');
                }
                iconurl = fieldobj.attr('icon-url');
                if ($('.cameraclass').length > 4)
                {
                    alert('Object added only five time in a form');
                    return;
                }
            }
            if (fieldType == 'lat-lang-field')
            {
                if ($('.latlangclass').length > 0)
                {
                    alert('LatLong Button will added only once in a form');
                    return;
                }
            }

            var addedFieldCount = countField();
            var thisNewField,
                    introRemoved,
                    fieldId = 'field' + (++addedFieldCount),
                    field = {
                fieldType: fieldType,
                name: fieldId,
                id: fieldId,
                fieldWidth: 'f_100',
                actionsType: 'field',
                iconurl: iconurl,
            };
            

            hideFieldSettings();

            columnWidth = formBuilder.width() / 4;

            var active_tab = getActiveTab();
            //Make sure the field is added before the submit button
            if ($('.submit').length > 0)
            {
                 if(active_tab)
                {
                    $('#form-elements-tmpl').tmpl(field).appendTo("#"+active_tab);
                    
                }
                else
                    {
                        $('.submit').before($('#form-elements-tmpl').tmpl(field));
                    }
            }
            else{
                if(active_tab)
                {
                    $('#form-elements-tmpl').tmpl(field).appendTo("#"+active_tab);
                }else{
                    $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);
                }
            }


            linkCaptionField();
            thisNewField = $('#' + fieldId);

//            if (fieldType == 'date-field')
//                thisNewField.dateinput();

            if (fieldType == 'range-field')
                thisNewField.rangeinput();

            if (fieldType == 'file-field')
                $('form').attr('enctype', "multipart/form-data");


            if (useUniform && ($.inArray(fieldType, ['select-field','select-api', 'checkbox-field', 'radio-field', 'file-field']) != -1))
            {

                if (fieldType == 'select-api'){
                 // no uniform required when user select options   
                }
                else if (fieldType == 'checkbox-field' || fieldType == 'radio-field')
                {
                    $('#' + fieldId + '-container').find('input').uniform();
                }
                else
                {
                    thisNewField.uniform();
                }

            }

            bindResizableToFields($('#' + fieldId + '-container').animate({opacity: 1}, 'fast'));
        }

    //Adding caption with take image controll by Zahid Nadeem
    function linkCaptionField()
    {
        $('#form-preview').find('.link-field').each(function(){
            var fieldId = $(this).attr('id');
            $('#'+fieldId).empty();
            var templist = [];
            $('#'+fieldId).append('<option value="custom" display_value="Custom" >Custom</option>');
             $('#form-preview').find('input, textarea, select').each(function(){
                    var field_name = $(this).attr('name');
                    var field_id = $(this).attr('id');
                    //field_name = field_name.replace('[]', '');
                    var skip = $(this).attr('rel');
                    var type = $(this).attr('type');
                    if($.inArray(field_name,templist)=='-1')
                    {
                        
                        templist.push(field_name);
                        if(field_name != 'form_id' && field_name != 'takepic' && skip != 'skip' && type != 'submit' && skip !='norepeat')
                            {
                                var label_value = $('#'+field_id+'-container').find('label').text();
                                if(label_value=='')
                                    {
                                        label_value = field_name;
                                    }
                                    //field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g,"");
                                    $('#'+fieldId).append('<option value="'+label_value+'" display_value="'+label_value+'" >'+label_value+'</option>');
                            }
                      }
                });
                
        });
    }

        /** Remove a field from the form **/
        function removeField(field)
        {
            field.fadeOut();
            field.remove();
            hideFieldSettings();

        }




        /** Add a title or submit to the form **/
        function addFormElement(type,form_name)
        {
            if(form_name=='')
                {
                    form_name='Form title';
                }
            if ($('#form-' + type).length <= 0)
            {
                var field = {
                    fieldType: type + '-field',
                    id: 'form-' + type,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    title:form_name
                };

                if (type == 'title')
                    $('#form-elements-tmpl').tmpl(field).prependTo(formBuilder);
                else
                    $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);

                $('#form-' + type).animate({opacity: 1}, 'fast');
            }

        }
        
        /** Add a title or submit to the form **/
        function addFormTabElement(fieldobj,title)
        {
            
            
            
            var fieldType = fieldobj.attr('id');
            
            var addedFieldCountTab = nextTabId();
            fieldId = 'field' + (addedFieldCountTab) +'-tab';
            //addedFieldCount++;
            if(title == '')
            {
                title = 'Tab '+addedFieldCountTab+' Title'
            }
            
            var type='tab';
            
                var field = {
                    fieldType: type + '-field',
                    id: fieldId,
                    title: title,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    tabid: 'tab'+addedFieldCountTab
                };
                
            if($("#tabs-header").length > 0)
            {
                 var fieldtab = {
                    fieldType: type + '-field',
                    id: fieldId,
                    title: title,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    tabid: 'tab'+addedFieldCountTab
                };
                
                   $('#field-tab-tmpl').tmpl(fieldtab).appendTo($("#tabs-header .tabs"));
                   $('#field-tab-area-tmpl').tmpl(fieldtab).insertAfter($("#tabs-header"));
                   $("#title-"+fieldId).trigger('click');
                
            }
            else
                {
                    
                    $('#form-elements-tmpl').tmpl(field).prependTo($("#form-preview"));
                    bindResizableToFields($('#tabs-header').animate({opacity: 1}, 'fast'));
                    
                    if(addedFieldCountTab==1){
                        $('#form-preview').find(".field").each(function()
                        {
                            if($(this).attr('id')=='tabs-header' || $(this).attr('id')=='form-submit'){
                                
                            }
                            else{
                                //alert($(this).attr('id'));
                                $('#'+$(this).attr('id')).prependTo($("#tab"+addedFieldCountTab));
                                
                            }
                        });
                    }
                }
                   

                
                //if (type == 'title')
                    //$('#field-tab-tmpl').tmpl(fieldtab).prependTo($("#form-preview"));
                    
                   
                    
                //else
                    //$('#form-elements-tmpl').tmpl(field).appendTo(formPreview);

                //$('#form-' + type).animate({opacity: 1}, 'fast');

        }




        /** Edit the form title **/
        function editFormTitle()
        {
            $('#set-form-title').dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Apply": function()
                    {
                        setFormTitle($(this).find('input').val());
                        $(this).dialog("close");
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }
        
        /** Edit the form title **/
        function editTabTitle(parent_id)
        {
            $('#set-tab-title').dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Apply": function()
                    {
                        setTabTitle($(this).find('input').val(),parent_id);
                        $(this).dialog("close");
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }




        /** Set the form title **/
        function setFormTitle(title)
        {
            $('#form-title h2').html(title);
        }
        /** Set the form title **/
        function setTabTitle(title,parent_id)
        {
            $('#title-'+parent_id).html(title);
        }


        function setSubmitValue(value)
        {
            $('#form-submit input').val(value);
        }


        /** Clear all elements from teh form **/
        function resetForm()
        {
            hideFieldSettings();
            formPreview.html('').removeAttr('enctype');
            formBuilder.find('#form-title').remove();
            showNotification('Form Reset');
        }




        /** Each field size is associated with a class  (25% = f_25). Set correct class on the field **/
        function setFieldSizeClass(field, ui, columnWidth)
        {
            var margin, multiplier, oldMultiplier, sizeClass;

            margin = getMargin();
            multiplier = Math.floor((field.width() + margin) / columnWidth);
            oldMultiplier = Math.floor((ui.size.width + margin) / columnWidth);

            sizeClass = multiplier * sizeClassBase;

            field.removeClass(sizeClasses);
            field.addClass('f_' + sizeClass);

            return sizeClass;
        }




        /** Reset the field settings dialog for the current field **/
        function resetFieldSettingsDialog(currentField)
        {

            var fieldType;
            inputSettings.html('');

            //make sure the first tab is selected
            fieldSettings.tabs('select', 0);

            //pre-populate label and field name with current value
            fieldSettings.find('#field-label-setting').val(currentField.find('label').html());
            fieldSettings.find('#field-name-setting').val(currentField.find('input:first, select, textarea').attr('name'));
            fieldSettings.find('#field-value-setting').val(currentField.find('input[type="text"]').attr('value'));

            inputSettings.css('display', 'none');
            settingsLoadingInd.css('display', 'block');
            //loop through each of the input or option fields for the current element
            //append the form to edit that element, bind events to inputs
            currentField.find("input, option, textarea").each(function()
            {
                
                var thisInputField = $(this),
                        thisInputFieldId = thisInputField.attr('id'),
                        thisInputFieldSettings,
                        typedValue;
                        
                if(thisInputField.attr('rel')!='undefined' && thisInputField.attr('rel')=='skip')
                return true;

                fieldType = getInputOptionParentType(thisInputField);
                //alert(thisInputField.val());

                if (fieldType == 'checkbox' || fieldType == 'radio' || fieldType == 'select')
                {
                    
                    if(fieldType == 'select'){
                        //add an "option name" field for each option/choice
                        inputSettings.append($('#select-settings-tmpl').tmpl({id: thisInputFieldId + '-setting'}));
                    }else{
                        //add an "option name" field for each option/choice
                        inputSettings.append($('#input-settings-tmpl').tmpl({id: thisInputFieldId + '-setting'}));
                    }

                    //the handle to the "option name" field we just created
                    thisInputFieldSettings = $('#' + thisInputFieldId + '-setting');

                    //populate existing values
                     var settingsInput = thisInputFieldSettings.find('input');
                    
                    if(fieldType == 'select'){
                        thisInputFieldSettings.find("input").each(function(){
                        
                        if($(this).attr('name')=='option-title')
                        {
                            $(this).val(thisInputField.val());
                        }
                        if($(this).attr('name')=='option-display-value')
                        {
                            $(this).val(thisInputField.attr('display_value'));
                        }
                        
                    });
                    }else{
                        settingsInput.val(getInputOptionValue(thisInputField, fieldType));
                    }
                    
                   
                    
                    

                    //bind text change event for option
                    //changed from thisInputFieldSettings.find('input[name=option-title]');
                    bindInputOptionToFieldSettings(settingsInput, thisInputField);

                }
            });

            settingsLoadingInd.css('display', 'none');
            inputSettings.css('display', 'block');

            resetValidationSettings(currentField, fieldType);
        }




        /** Hide the field settings dialog **/
        function hideFieldSettings()
        {
            fieldSettings.animate({opacity: 0}, 'fast', function()
            {
                $(this).css({display: 'none'});
            });
        }




        /** Position the field settings dialog next to the current field **/
        function positionFieldSettings(less12)
        {
            //Workaround b/c jQuery UI position not functioning as expected in webkit browsers
            fieldSettings.css('display', 'block').offset({left: hoverField.offset().left + hoverField.width() - 300, top: hoverField.offset().top + 6});
        }




        /** This adds an element to a select field, radio group, or checkbox group **/
        function addFieldOption()
        {
            if(hoverField.find('select').is('select')){
                
                var currentFieldId = hoverField.attr('id'),
                    currentNumOptions = hoverField.find('option:last').attr('id'),
                    tmpInputObj = hoverField.find('option:first'),
                    fieldName = tmpInputObj.is('input') ? tmpInputObj.attr('name') : tmpInputObj.parent().attr('name'),
                    fieldType = getInputOptionParentType(tmpInputObj),
                    appendToParent = (fieldType != 'select') ? tmpInputObj.parents('div.field:first') : tmpInputObj.parent('select'),
                    option = {},
                    newOptionNum,
                    id,
                    settingsInput,
                    thisInputField,
                    thisInputFieldCurrentValue;
                
            }else
                {
                    var currentFieldId = hoverField.attr('id'),
                    currentNumOptions = hoverField.find('input:last, option:last').attr('id'),
                    tmpInputObj = hoverField.find('input:first, option:first'),
                    fieldName = tmpInputObj.is('input') ? tmpInputObj.attr('name') : tmpInputObj.parent().attr('name'),
                    fieldType = getInputOptionParentType(tmpInputObj),
                    appendToParent = (fieldType != 'select') ? tmpInputObj.parents('div.field:first') : tmpInputObj.parent('select'),
                    option = {},
                    newOptionNum,
                    id,
                    settingsInput,
                    thisInputField,
                    thisInputFieldCurrentValue;
                }

            currentNumOptions = parseInt(currentNumOptions.slice(currentNumOptions.indexOf('-') + 1));
            newOptionNum = currentNumOptions + 1;

            id = fieldName.replace('[]', '') + '-' + newOptionNum;

            option = {
                fieldType: fieldType + '-option',
                type: fieldType,
                name: fieldName,
                id: id,
                option: 'Option ' + newOptionNum
            };

            //append to tmpInputObj rather than hover field to handle select field case
            $('#form-elements-tmpl').tmpl(option).appendTo(appendToParent);
            if(fieldType =='select')
                {
                    $('#select-settings-tmpl').tmpl({id: id + '-setting'}).appendTo(inputSettings);
                }
                else{
                    $('#input-settings-tmpl').tmpl({id: id + '-setting'}).appendTo(inputSettings);
                }

            //get handles to the field settings input and input/option that we just created
            settingsInput = $('#' + id + '-setting').find('input');
            thisInputField = $('#' + id);

            if (useUniform)
                thisInputField.uniform();

            //set the newly added field settings input to the value of the new input/option
            thisInputFieldCurrentValue = getInputOptionValue(thisInputField, fieldType);
            settingsInput.val(thisInputFieldCurrentValue);

            //bind text change event
            bindInputOptionToFieldSettings(settingsInput, thisInputField);


            //Adding option causes settings to move down, re-position them
            positionFieldSettings(true);

        }




        /** Removes an option from a select, radio, or checkbox group **/
        function removeFieldOption(optionRemoveButton)
        {
            var optionSetting = optionRemoveButton.parent(),
                    optionSettingId = optionSetting.attr('id'),
                    inputOptionId = optionSettingId.slice(0, optionSettingId.lastIndexOf('-')),
                    inputOption = content.find('#' + inputOptionId);


            if (inputOption.is('option'))
                updateUniform = true;

            inputOption.parents('div.option:first').stop().fadeOut(function()
            {
                $(this).remove();
            });

            inputOption.fadeOut(function()
            {
                $(this).remove();
            });
            optionSetting.fadeOut(function()
            {
                $(this).remove();
            });

            if (updateUniform)
            {
                $.uniform.update(inputOption.parent());
            }
        }




        /** Binds the new option to the field settings dialog**/
        function bindInputOptionToFieldSettings(settingsInput, thisInputField)
        {
            var typedValue;

            settingsInput.bind('textchange', function()
            {
                //This is a checkbox or radio
                if (!thisInputField.is('option'))
                {
                    typedValue = $(this).val();

                    //cache the textchange field so we don't have to traverse the dom on each key change
                    if (cachedOption[0] == thisInputField.attr('id'))
                    {
                        thisInputField.attr('value', typedValue);
                        cachedOption[1].html(typedValue);
                    }
                    else
                    {
                        cachedOption[0] = thisInputField.attr('id');
                        if (useUniform)
                            cachedOption[1] = thisInputField.attr('value', typedValue).closest('div').next('span.option-title').html(typedValue);
                        else
                            cachedOption[1] = thisInputField.attr('value', typedValue).next('span.option-title').html(typedValue);
                    }
                }
                else
                {
                    typedValue = $(this).val();
                    if($(this).attr('name')=='option-title')
                    {
                        thisInputField.html(typedValue).attr('value', typedValue);
                        //thisInputField.html(typedValue).attr('display_value', typedValue);
                    }
                    if($(this).attr('name')=='option-display-value')
                    {
                        thisInputField.html(typedValue).attr('display_value', typedValue);
                    }
                    //This is a select
                    
                    //thisInputField.html(typedValue).attr('value', typedValue);
                    
                    //update uniform since this is a select, otherwise wrong value will display in span
                    if (useUniform && thisInputField.is(':first-child'))
                        $.uniform.update(thisInputField.parent());
                }
            });
        }




        /** Get the value of a specific option in a select, radio, checkbox group**/
        function getInputOptionValue(thisInputField, fieldType)
        {
            if (useUniform)
                return (fieldType != 'select') ? thisInputField.closest('div').next('span.option-title').html() : thisInputField.html();
            else
                return (fieldType != 'select') ? thisInputField.next('span.option-title').html() : thisInputField.html();
        }




        /** Determine if the current option belongs to a radio, select, or checkbox**/
        function getInputOptionParentType(inputOption)
        {
            //var fieldType = inputOption.html5type();
            var fieldType = getType(inputOption);

            //since html5type does not return textarea type
            if (inputOption.is('textarea'))
                fieldType = 'textarea';

            if (fieldType === undefined && inputOption.parent().is('select'))
                fieldType = 'select';

            return fieldType;
        }




        /** Get a field's type**/
        function getType(field)
        {
            var input;


            if (!field.is('input, select, option, textarea'))
                input = field.find('input:first, select, textarea');
            else
                input = field;

            //.ttw-range and .ttw-date are hacks b/c chrome is currently stripping the type attribute from these fields!
            if (input.is('.ttw-range'))
                return 'range';
            else if (input.is('.ttw-date'))
                return 'date';
            else
                return (input.is('input')) ? input.html5type() : (input.is('select, option')) ? 'select' : (input.is('textarea')) ? 'textarea' : '';
        }




        /** Reset the validation settings section of the field settings dialog **/
        function resetValidationSettings(currentField, fieldType)
        {
            var fieldRules,
                    rules = {
                text: ['required','filter', 'pattern'],
                number: ['required','filter', 'min', 'max'],
                email: ['required','filter'],
                url: ['required'],
                select: ['required','filter'],
                textarea: ['required'],
                radio: ['required'],
                checkbox: ['required'],
                password: ['required'],
                date: ['required','filter', 'min', 'max'],
                range: ['required', 'min', 'max'],
                file: ['required']
            };

            fieldRules = rules[fieldType];

            //reset all the values to blank or attribute value on currentField, unbind previous field, bind event handler for current field
            validationSettings.find('input').each(function()
            {
                var id = $(this).parent().attr('id'),
                        rule = id.slice(4),
                        value, attributes;

                //is there a value for this validation rule on currentField, if so set it in the validations settings box
                value = (currentField.find('input').attr(rule) !== undefined) ? currentField.attr(rule) : '';

                if ($(this).attr('type') == 'checkbox')
                    attributes = {'checked': value};
                else
                    attributes = {'value': value};

                //apply attributes variable, unbind old events, bind current field, then hide fields. They will be re-enabled based on rules and current inputs later
                $(this).attr(attributes)
                        .unbind('change textchange')
                        .bind('change textchange', function()
                {
                    updateValidationRules(currentField, $(this), id);
                })
                        .parent().css('display', 'none');
            });

            //set required as true
            validationSettings.find('#val-required input').attr({'checked': 'checked'});

            //loop through the rules for the field type and re-enable them (set back to default layout)
            for (var i = 0; i < fieldRules.length; i++)
            {
                var thisValField = validationSettings.find('#val-' + fieldRules[i]).css('display', 'block').find('input');
                thisValField.val(currentField.find('input:first').attr(fieldRules[i]));

                if (fieldRules[i] == 'required')
                {
                    //if the required attribute is missing, the user has made this field optional, remove default required state
                    if (!isRequired(currentField))
                        thisValField.attr('checked', false);
                }
                else if (fieldRules[i] == 'filter')
                {
                    //if the required attribute is missing, the user has made this field optional, remove default required state
                    if (!isFilter(currentField))
                        thisValField.attr('checked', false);
                }

            }

        }




        /** Update the validation rules on a specific field once changes are made in the field settings dialog**/
        function updateValidationRules(currentField, validationField, validationRule)
        {
            var currentInput = currentField.find('input, select, textarea');

            validationRule = validationRule.slice(4);

            if (validationRule == 'pattern' || validationRule == 'min' || validationRule == 'max')
            {
                currentInput.attr(validationRule, validationField.val());
            }
            else if (validationRule == 'required')
            {
                var type = currentInput.attr('type');

                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').addClass('required');
                    else
                        currentInput.attr('required', validationRule);
                }
                else
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').removeClass('required');
                    else
                        currentInput.removeAttr(validationRule)
                }
            }
            else if (validationRule == 'filter')
            {
                var type = currentInput.attr('type');

                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').addClass('filter');
                    else
                        currentInput.attr('filter', validationRule);
                }
                else
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').removeClass('filter');
                    else
                        currentInput.removeAttr(validationRule)
                }
            }
        }




        /** Determine if a particular field has the required attribute set**/
        function isRequired(field)
        {
            var attr = field.find('input:first, textarea, select').attr('required');
            return field.hasClass('required') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }
        /** Determine if a particular field has the required attribute set**/
        function isFilter(field)
        {
            var attr = field.find('input:first, textarea, select').attr('filter');
            return field.hasClass('filter') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }




        /** Change the form width**/
        function setFormWidth(size)
        {
            var s = size;
            if (size.substr(-2) == 'px')
                size = size.substr(0, size.length - 2);

            if (!isNaN(size) && parseInt(size) == size)
            {
                hideFieldSettings();
                size = parseInt(size);
                formBuilder.width(size + 'px').find('#form-preview').width(size + 'px');
                $('#form-preview').html('');
                addedFieldCount = 0;
            }
            else
                showNotification('Invalid value');
        }



        /** Change the form theme **/
        function setTheme(newTheme)
        {
            theme = newTheme;
            $('#style').attr('href', base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/css/style.css');
            $('#style-uniform').attr('href', base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/css/uniform.' + theme + '.css');
        }



        /** Make the fields resizeable **/
        function bindResizableToFields(fields)
        {

            var margin = getMargin(),
                    max = (columnWidth * 4) - margin,
                    min = columnWidth - margin;

            fields.resizable({
                handles: ' e',
                grid: [columnWidth, 0],
                maxWidth: max,
                minWidth: min,
                start: function(event, ui)
                {
                    hideFieldSettings();
                },
                resize: function(event, ui)
                {
                    var sizeClass = setFieldSizeClass($(this), ui, columnWidth);
                    showNotification('Width: ' + sizeClass + "%");
                },
                stop: function(event, ui)
                {
                    var $this = $(this), sizeClass = setFieldSizeClass($this, ui, columnWidth);
                    showNotification('Width: ' + sizeClass + "%");

                    //because jquery explictly sets height on resize which breaks the border around the field container
                    $this.css('height', '');
                }
            });
        }





        function getMargin()
        {
            return  .04 * (columnWidth * 4);
        }




        /** Check the form for errors before downloading **/
        function verifyForm()
        {
            var error = false,
                    errorMsg = '';


            if (!formPreview.find('.submit').length)
            {
                error = true;
                errorMsg += 'This form does not have a submit button';
            }

            if (!formPreview.find('.field').not('.submit').length)
            {
                error = true;
                errorMsg += (errorMsg != '') ? '<br/>This form is empty' : 'This form is empty';
            }

            if (!error)
            {
                downloadType();
            }
            else
            {
                confirmAction(downloadType, 'Alert', errorMsg);
            }
        }




        /**Prompt the user to select which type of download they want**/
        function downloadType()
        {
            $('#get-form').dialog({
                resizable: false,
                title: 'Download Form',
                modal: true,
                width: 'auto',
                buttons: {
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }




        /** Download the form**/
        function gatherFormDetails(output_type)
        {

            var fields,
                    formHtml,
                    formOutput = $('#form-output'),
                    validationRules = {},
                    datesAndRanges = {date: '', range: ''},
            postData = {};

            $('#get-form').dialog('close');

            loadingIndicator();

            //exit preview mode if it is enabled
            if (formPreview.hasClass('preview-mode'))
                previewMode();


            //copy entire form to hidden output area for post-processing
            formOutput.html(formBuilder.html());

            formOutput.find('.TTWForm').removeAttr('id').removeClass('ui-sortable');

            formOutput.find('.field').each(function()
            {
                var $this = $(this), type, input;

                if (!$this.is('.submit, .form-title'))
                {
                    //get rid of the brackets[] on checkbox name attribute otherwise it messes up post value for some reason
                    validationRules[$this.find('input, select, textarea').attr('name').replace('[]', '')] = getValidationRules($this);
                }

                //remove misc jquery ui classes, inline styles, etc
                $this.find('.field-actions, .ui-resizable-handle').remove();

                $this.removeClass('ui-resizable').removeAttr('style').removeAttr('aria-disabled');

                input = $this.find('input, select, textarea');

                input.removeAttr('disabled').removeAttr('style');

                //input.removeAttr('size'); //size is being added to file inputs? Commented out, bc ie9 and ff4 choke on this line

                type = getType($this);

                if (type == 'range' || type == 'date')
                    datesAndRanges[type] += input.attr('id') + ' ';

                //remove extra markup created by uniform plugin
                if ($.inArray(type, ['checkbox', 'radio', 'select', 'file']) != -1)
                    removeUniformMarkup($this, type);

                //remove extra markup for range created by tools.rangeinput()
                if (type == 'range')
                    $this.find('.slider').remove();
            });

            formHtml = '<div class="TTWForm-container">' + formOutput.html() + '</div>';

            //beautify the html
            formHtml = style_html(formHtml, 5, ' ', 150);

            postData = {
                'dates_ranges': datesAndRanges,
                'action': 'build',
                'output_type': output_type,
                'form_html': formHtml,
                'width': formBuilder.width(),
                'rules': validationRules,
                'theme': theme
            };

            $.post(base_url + 'assets/form_builder/application/build_form.php', postData, function(data)
            {

                if (output_type == 'code')
                    displayFormCode(data);
                else
                    downloadZip(data);

                //Clean up
                formOutput.html('');
            });
        }





        //remove the extra markup added by the uniform() plugin
        function removeUniformMarkup(field, type)
        {

            if (type == 'select')
            {
                field.find('span:first-child').remove();
                field.find('select').unwrap();
            }
            else if (type == 'radio' || type == 'checkbox')
            {
                field.find(' input').unwrap().unwrap();
            }
            else
            {
                field.find('span.filename, span.action').remove();
                field.find('input').unwrap();
            }
        }




        /** Download the zip **/
        function downloadZip(data)
        {
            var data = $.parseJSON(data);
            if (data.error == undefined)
            {
                var file = data.zip;
                window.open('application/build_form.php?action=download&form=' + file);
            }
            else if (data.error == 'NO-ZIP')
            {
                showAlert('<p class="warning">Your server does not have the zip extension installed. </p>' +
                        'Please install the zip extension or download your form manually:<br/><br/>' +
                        '<a class="open-directory" href="' + data.form_path + '" target="_blank">Go to form directory</a>' +
                        '<br/><br/>' + data.manual_download, '500px');
            }


            loadingIndicator('remove');
        }




        /** Show the form code **/
        function displayFormCode(data)
        {

            var code = $.parseJSON(data),
                    formCode = $('#form-code');

            if (code == null || code == undefined)
            {
                showAlert('No data to display');
                return;
            }

            formCode.html($('#get-code-tmpl').tmpl({html: code.html, css: code.css, js: code.js, php: code.php}));

            formCode.tabs();

            loadingIndicator('remove');

            formCode.dialog({
                buttons: {"Ok": function()
                    {
                        $(this).dialog("close");
                    }},
                width: 800,
                height: 400,
                close: function()
                {
                    formCode.html('');
                    formCode.tabs('destroy');
                }
            });

            SyntaxHighlighter.highlight();

        }




        /** Format the validation rules for a particular field**/
        function getValidationRules(field)
        {
            var input = field.find('input:first, select, textarea'),
                    type = getType(field),
                    rules = {
                'type': type,
                'pattern': input.attr('pattern'),
                'min': input.attr('min'),
                'max': input.attr('max'),
                'required': (type == 'checkbox' || type == 'radio') ? field.hasClass('required') : input.attr('required')
            },
            thisFieldRules = {};

            $.each(rules, function(name, value)
            {
                if (rules[name] != undefined && rules[name] != '')
                    thisFieldRules[name] = rules[name];

            });


            return thisFieldRules;

        }




        /** Show an application notification**/
        function showNotification(message, timeout)
        {
            //don't want a previously set timeout to prematurely clear this message if notification called in rapid succession
            clearTimeout(notificationTimeout);
            notification.html(message).css('display', 'block').animate({opacity: 1}, 'fast');

            if (timeout == undefined)
                timeout = 2000;

            notificationTimeout = setTimeout(function()
            {
                hideNotification();
            }, timeout);
        }




        /** Hide notification **/
        function hideNotification()
        {
            notification.animate({opacity: 0}, 'fast', function()
            {
                $(this).css('display', 'none')
            });
        }





        /** Enter or exit preview mode **/
        function previewMode()
        {
            var previewButton = $('#preview'), pvResult;
            if (previewButton.hasClass('no-preview'))
            {
                //hide the controls to preven new fields from being added
                controls.animate({'opacity': 0}, 800, function()
                {
                    $(this).css('height', 1);
                });

                //switch preview button class/text
                previewButton.addClass('in-preview').removeClass('no-preview').find('span').html('Exit Preview');

                formBuilder.addClass('preview-mode');

                hideFieldSettings();
                //make sure the form has a submit button so user can test validation
                if (!formPreview.find('.submit').length)
                {
                    addFormElement('submit');
                }

                //disable sorting
                formPreview.sortable("disable");

                //disable resizing and enable any range inputs
                formPreview.find('.field').resizable('disable').each(function()
                {
                    var f = $(this).find('input');

                    if (f.html5type() == 'range' || f.hasClass('range'))
                    {
                        f.removeAttr('disabled');
                        //f.rangeinput();
                    }

                });


                //unbind any previous submit (return false) events and enable jQuery Tools validation
                formPreview.unbind('submit').validator({effect: 'labelMate'});

                //Enable the extra validation logic for checkbox and radio groups
                pv = new previewValidation();
                pv.start();

                formPreview.submit(function(e)
                {
                    //run check/radio group validation
                    pvResult = pv.validate();

                    //client side validation passed. Let user know
                    if (!e.isDefaultPrevented() && pvResult)
                    {
                        showNotification('Validation passed');
                    }

                    return false;
                });

                showNotification('Preview Mode', 4000);
            }
            else
            {
                controls.css('height', 'auto').animate({'opacity': 1}, 800);

                previewButton.addClass('no-preview').removeClass('in-preview').find('span').html('Preview');

                formBuilder.removeClass('preview-mode');

                formPreview.bind('submit', function()
                {
                    return false;
                }).data("validator").destroy();

                formPreview.sortable("enable");

                //rebind resizable and disable range inputs
                formPreview.find('.field').resizable('enable').each(function()
                {
                    var f = $(this).find('input');
                    if (f.html5type() == 'range')
                    {
                        f.attr('disabled', 'disabled');
                    }

                });

                pv.stop();
                //disable previously set submit callback function
                formPreview.unbind('submit').submit(function()
                {
                    previewNotification();
                    return false;
                });


            }
        }





        /**
         * This simulates the client side validation performed in
         * a downloaded form. If that logic changes, it must change
         * here as well.
         */
        var previewValidation = function()
        {

            var validator = formPreview.data("validator");

            //validate checkbox and radio groups
            function validateCheckRadio()
            {
                var err = {};

                $('.radio-group, .checkbox-group').each(function() {
                    if ($(this).hasClass('required'))
                        if (!$(this).find('input:checked').length)
                            err[$(this).find('input:first').attr('name')] = 'Please complete this mandatory field.';
                });

                if (!$.isEmptyObject(err))
                {
                    validator.invalidate(err);
                    return false
                }
                else
                    return true;

            }





            //clear any checkbox errors
            function clearCheckboxError(input) {
                var parentDiv = input.parents('.field');

                if (parentDiv.hasClass('required'))
                    if (parentDiv.find('input:checked').length > 0) {
                        validator.reset(parentDiv.find('input:first'));
                        parentDiv.find('.error').remove();
                    }
            }





            function validate()
            {
                return validateCheckRadio();
            }





            function start()
            {
                $('[type=checkbox]').bind('change', function()
                {
                    clearCheckboxError($(this));
                });

                //Position the error messages next to input labels
                $.tools.validator.addEffect("labelMate", function(errors, event) {
                    $.each(errors, function(index, error) {
                        error.input.first().parents('.field').find('.error').remove().end().find('label').after('<span class="error">' + error.messages[0] + '</span>');
                    });

                }, function(inputs) {
                    inputs.each(function() {
                        $(this).parents('.field').find('.error').remove();
                    });

                });
            }





            function stop()
            {
                $('.error').remove();
                $('[type=checkbox]').unbind('change');
            }





            return{
                start: start,
                validate: validate,
                stop: stop
            }
        };





        function previewNotification()
        {
            showNotification('Use preview mode to test form');
        }





        function confirmAction(action, title, message)
        {
            var confirmDialog = $('#confirm-action');

            if (title == undefined)
                title = 'Confirm';

            if (message != undefined)
            {
                confirmDialog.find('.message').html(message).css('display', 'block');
            }
            else
                confirmDialog.find('.message').html('').css('display', 'none');


            confirmDialog.dialog({
                resizable: false,
                title: title,
                modal: true,
                buttons: {
                    "Yes": function()
                    {
                        $(this).dialog("close");
                        action();
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }



        function promptForInput(form, action)
        {
            form.dialog({
                resizable: false,
                modal: true,
                open: function() {
                    //$(this).find('select').uniform();  //lets use the styled select to get rid of annoying moz outline
                },
                buttons: {
                    "Apply": function()
                    {
                        var input, val;

                        input = $(this).find('input, select');
                        val = input.is('input') ? input.val() : input.find(':selected').val();

                        action(val);

                        $(this).dialog("close");
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }





        function showAlert(alert, width)
        {
            if (width == undefined)
                width = 'auto';

            $('<div>' + alert + '</div>').dialog({
                resizable: false,
                title: 'Alert',
                modal: true,
                width: width,
                buttons: {
                    Ok: function()
                    {

                        $(this).dialog("close");
                    }
                }
            });
        }





        function ieNotice(alert)
        {
            $('<div>' + alert + '</div>').dialog({
                resizable: false,
                title: 'Alert',
                modal: true,
                buttons: {}
            });
        }





        function displayInstructions()
        {
            $('#instructions').dialog({
                resizable: false,
                title: 'Instructions',
                modal: true,
                width: 600,
                buttons: {
                    Ok: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }





        function preloadImages(imageList, callback)
        {
            var i, total, loaded = 0, images = [];
            if (typeof imageList != 'undefined')
            {
                if ($.isArray(imageList))
                {
                    total = imageList.length; // used later
                    for (var i = 0; i < total; i++)
                    {
                        images[imageList[i]] = new Image();
                        images[imageList[i]].onload = function()
                        {
                            loaded++;
                            if (loaded == total)
                            {
                                if ($.isFunction(callback))
                                {
                                    callback();
                                }
                            }
                        };
                        images[imageList[i]].src = imageList[i];
                    }
                }
            }
        }










        function execute_callback(callback)
        {
            if ($.isFunction(callback))
            {
                callback();
            }
        }








        return{
            init: init
        }
    }(); // End of the form builder object



    //Start the form builder **/
    TTWFormBuilder.init();




});
