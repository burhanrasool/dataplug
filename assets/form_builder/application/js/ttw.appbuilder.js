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

        var content = $('#content'),
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
                theme = 'custom',
                base_url = $('#base_url').val();





        function init()
        {

            //loadingIndicator();

            content.bind('initFormBuilderComplete', function()
            {
                //loadingIndicator('remove');
                checkDependencies();
            });

            formPreview.sortable({
                start: function()
                {
                    hideFieldSettings();
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
                bindEvents(step_4);
            }





            function step_3()
            {

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
                //addField($(this).attr('id'));
                addField($(this));

            });

            $('#add-form-title li').click(function()
            {
                $app_name = $(this).attr('rel');
                addFormElement('title',$app_name);

            });

            $('#add-form-submit li').click(function()
            {
                addFormElement('submit','');

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
                }, 'Delete Field');
            });

            $('.edit-title').live('click', function()
            {
                var formtitle = $(this).parent().parent().find('h2').html();
                $('#set-form-title').find('input').val(formtitle);
                editFormTitle();
            });

            $('.edit-submit').live('click', function() {
                promptForInput($('#set-form-submit'), setSubmitValue);
            });

            $('#field-settings, #field-settings input').live('focus', function(event)
            {
                clearInterval(fieldSettingsInterval);
            });

            fieldSettings.live('focusout', function()
            {
                fieldSettingsInterval = setTimeout(function()
                {
                    hideFieldSettings();
                }, 2000);
            });

            /** Field Settings Inputs **/
            $('#field-label-setting').bind('textchange', function(event, previousText)
            {
                var currentField = fieldSettings.find('#current-field').text();
                $('#' + currentField).find('label').html($(this).val());
            });

            //bind event for input name
            $('#field-name-setting').bind('textchange', function()
            {
                var currentField = fieldSettings.find('#current-field').text(),
                        $currentField = $('#' + currentField),
                        val = $(this).val();

                if ($currentField.hasClass('checkbox-group') && (val.substr(val.length - 2, val.length - 1) != '[]'))
                {
                    var last = val[val.length - 1];
                    val = (last == '[') ? val + ']' : val + '[]';
                }

                $currentField.find('input, textarea, select').attr('name', val);
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

            execute_callback(callback);
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




        /** Add a field to the form **/
        function addField(fieldobj)
        {
            var fieldType = fieldobj.attr('id');
            var icon = fieldobj.attr('icon');
            var formurl = fieldobj.attr('form-url');
            var formtitle = fieldobj.attr('form-title');
            var iconurl = fieldobj.attr('icon-url');
            
            
           
            if (fieldType == 'form-field')
            {
                
                if ($('.cameraclass').length > 0)
                {
                    alert('Object added only once in a form');
                    return;
                }
            }
            
            if (fieldType == 'camera-field')
            {
                if ($('.cameraclass').length > 0)
                {
                    alert('Object added only once in a form');
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
                fieldWidth: 'f_50',
                actionsType: 'field',
                icon: icon,
                formurl: formurl,
                formtitle: formtitle,
                iconurl: iconurl,
            };

            hideFieldSettings();

            columnWidth = formBuilder.width() / 4;

            //Make sure the field is added before the submit button
            if ($('.submit').length > 0)
            {
                $('.submit').before($('#form-elements-tmpl').tmpl(field));
            }
            else
                $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);

            thisNewField = $('#' + fieldId);

            if (fieldType == 'date-field')
                thisNewField.dateinput();

            if (fieldType == 'range-field')
                thisNewField.rangeinput();

            if (fieldType == 'file-field')
                $('form').attr('enctype', "multipart/form-data");


            if (useUniform && ($.inArray(fieldType, ['select-field', 'checkbox-field', 'radio-field', 'file-field']) != -1))
            {

                if (fieldType == 'checkbox-field' || fieldType == 'radio-field')
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




        /** Remove a field from the form **/
        function removeField(field)
        {
            field.fadeOut();
            field.remove();
            hideFieldSettings();

        }




        /** Add a title or submit to the form **/
        function addFormElement(type,app_name)
        {
            if ($('#form-' + type).length <= 0)
            {
                var field = {
                    fieldType: type + '-field',
                    id: 'form-' + type,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    title:app_name
                };

                if (type == 'title')
                    $('#form-elements-tmpl').tmpl(field).prependTo(formBuilder);
                else
                    $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);

                $('#form-' + type).animate({opacity: 1}, 'fast');
            }

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




        /** Set the form title **/
        function setFormTitle(title)
        {
            $('#form-title h2').html(title);
        }


        function setSubmitValue(value)
        {
            $('#form-submit input').val(value);
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

                fieldType = getInputOptionParentType(thisInputField);

                if (fieldType == 'checkbox' || fieldType == 'radio' || fieldType == 'select')
                {

                    //add an "option name" field for each option/choice
                    inputSettings.append($('#input-settings-tmpl').tmpl({id: thisInputFieldId + '-setting'}));

                    //the handle to the "option name" field we just created
                    thisInputFieldSettings = $('#' + thisInputFieldId + '-setting');

                    //populate existing values
                    var settingsInput = thisInputFieldSettings.find('input');
                    settingsInput.val(getInputOptionValue(thisInputField, fieldType));

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
            fieldSettings.css('display', 'block').offset({left: hoverField.offset().left + hoverField.width() + 47, top: hoverField.offset().top});
        }




        /** This adds an element to a select field, radio group, or checkbox group **/
        function addFieldOption()
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
            $('#input-settings-tmpl').tmpl({id: id + '-setting'}).appendTo(inputSettings);

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
                    //This is a select
                    typedValue = $(this).val();
                    thisInputField.html(typedValue).attr('value', typedValue);

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
                text: ['required', 'pattern'],
                number: ['required', 'min', 'max'],
                email: ['required'],
                url: ['required'],
                select: ['required'],
                textarea: ['required'],
                radio: ['required'],
                checkbox: ['required'],
                password: ['required'],
                date: ['required', 'min', 'max'],
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
                        thisValField.attr('checked', '');
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
        }




        /** Determine if a particular field has the required attribute set**/
        function isRequired(field)
        {
            var attr = field.find('input:first, textarea, select').attr('required');
            return field.hasClass('required') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
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
