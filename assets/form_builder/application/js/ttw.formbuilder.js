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
    var old_field_value = '';


    var TTWFormBuilder = function()
    {
        //Pseudo stop if this is ie. 
        if ($.support.leadingWhitespace == false)
            ieNotice('Awww Shucks! This app only works in modern browsers.');

        var     content = $('#content'),
                formElements = [],
                formPreview = $('#form-preview'),
                addedFieldCount = 0,
                fieldSettings = $('#field-settings'),
                pageSettings = $('#page-settings'),
                fieldSettingsInterval = 0,
                validationSettings = fieldSettings.find('#validation-settings'),
                inputSettingsTmpl = $('#input-settings-tmpl'),
                inputSettings = fieldSettings.find('#input-settings'),
                hoverField,
                hoverPage,
                columnWidth,
                settings = {},
                sizeClasses = 'f_25 f_50 f_75 f_100',
                sizeClassBase = 25,
                controls = $('#controls'),
                loader = {},
                cachedOption = ['', ''],
                settingsLoadingInd = $('#settings-loading'),
                formBuilder = $('#form-builder'),
                pv,
                theme = 'custom',
                base_url = $('#base_url').val(),
                form_id = $('#form_id').val();





        function init()
        {
            content.bind('initFormBuilderComplete', function()
            {
                checkDependencies();
            });

            formPreview.sortable({
                start: function()
                {
                    hideFieldSettings();
                }
            });

            //Adding Tab functionality
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
            
            //Adding Page functionality
            $(".pagediv").sortable({
                start: function()
                {
                    //hideFieldSettings();
                }
            });
            $(".pages").sortable({
                start: function()
                {
                    //hideFieldSettings();
                }
            });
            $(".popuploop").sortable({
            	start: function()
            	{
            		//hideFieldSettings();
            	}
            });

//Adding Tab functionality
            fieldSettings.tabs();
            pageSettings.tabs();

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
        
        //Adding Tab functionality
        function countTab()
        {
            var i = 1;
            $(".tabli").each(function() {
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
        
        //Add Page functionality
        function countPage()
        {
            var i = 1;
            $(".pageli").each(function() {
                i++;
            });
            return i;
        }
        
        function nextPageId()
        {
            var i = 1;
            $(".pageli").each(function() {

                if ($('#field' + i + '-page').length == 0)
                {
                    return i;
                }
                i++;
            });
            return i;
        }


        //Adding Table functionality
        function countTable()
        {
            var i = 1;
            $(".tbl_widget").each(function() {
                i++;
            });
            return i;
        }
        
        //Adding loop widget
        function nextLoopId()
        {
        	var i = 1;
            $(".loop-widget-main").each(function() {

                if ($('#loop-' + i).length == 0)
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

            $('.add-row2').live('click', function()
            {
                addRow2($(this));
            });
            $('.add-row3').live('click', function()
            {
                addRow3($(this));
            });
            $('#form-fields li, #html5-fields li').click(function()
            {
                addField($(this));
            });

            $('#add-cnic-scanner li').click(function()
            {
                var page_total = countPage();
                if(page_total>1)
                {
                    showAlert('CNIC Scanner is not allowed in page base form. Remove pages for using CNIC Scanner.');
                    return false;
                }
                var addedFieldCount = countField();
                var total_tabs = countTab();
                if (total_tabs == 1) {
                    if (addedFieldCount > 0) {
                        addFormTabElement($('#tab-field'), '');
                    }
                }
                addFormTabElement($('#tab-field'), 'CNIC Scanner');
                addField($('#scan-cnic'));
                addField($('#cnic-IDno'));
                addField($('#cnic-name'));
                addField($('#cnic-father-name'));
                addField($('#cnic-family-no'));
                addField($('#cnic-date-of-birth'));
                addField($('#cnic-address'));
                addField($('#cnic-district'));
                addField($('#cnic-city'));


            });

            $('#add-form-tab li').click(function()
            {
                var page_total = countPage();
                if(page_total>1)
                {
                    showAlert('Tab is not allowed in this form. Remove pages first');
                    return false;
                }
                addFormTabElement($(this), '');
            });
            $('#add-form-page li').click(function()
            {
                var tab_total = countTab();
                if(tab_total>1)
                {
                    showAlert('Page is not allowed in this form. Remove tabs first.');
                    return false;
                }
                addFormPageElement($(this), '');
            });

            $('#add-form-title li').click(function()
            {
                $form_name = $(this).attr('rel');
                addFormElement('title', $form_name);
            });

            $('#add-form-submit li').click(function()
            {
                addFormElement('submit', '');
            });
            
            $('#add-form-save li').click(function()
            {
                addFormElement('save', '');
            });
            
            $('#add-loop-widget li').live('click', function()
            {
                addLoopWidget($(this));
            });

            $('#form-preview').submit(function()
            {
                return false;
            });

            $('#save').click(function()
            {
                saveForm();
                return false;
            });

            $('#form-builder').live('mouseup',function()
            {
                if (!$(this).hasClass('.tbl_widget')){
                    $('.tbl_widget').removeClass('active_table');
                    $('.tbl-td').removeClass('active_td');
                }
            });

            $('.tbl-td').live('click',function()
            {
                var widget_id_finding = $(this).attr('class');
                var disget_id = widget_id_finding.split('-');
                
                $('.tbl_widget').removeClass('active_table');
                $('.tbl-td').removeClass('active_td');
                $('#'+disget_id[0]).addClass('active_table');
                $('.'+disget_id[0]+'-etd').removeClass('active_td');
                $(this).addClass('active_td');
            });
            

            /** Field Settings Box **/
            $('.edit-field-sep').live('click', function()
            {

                hidePageSettings();
                $('#add_after_widget').val($(this).attr('id'));
                hoverField = $(this);
                clearInterval(fieldSettingsInterval);
                positionFieldSettings();
                resetFieldSettingsDialog(hoverField);

                fieldSettings.animate({opacity: 1}, 'fast').find('#current-field').html(hoverField.attr('id'));

                old_field_value = $('#field-name-setting').val();

            });
            /** Loop Settings Box **/
            $('.edit-field-loop').live('click', function()
            {
            	
            	$('#add_after_widget').val('');
            	$('.loop-widget-main').removeClass('active_loop');
            	
            	if($(this).next().css('display') != 'none'){
            		$(this).next().hide();
            	}
            	else{
            		$(this).parent().addClass('active_loop');
            		$(this).next().show();
            		
            	}
            	
            	hidePageSettings();
            	$('#add_after_widget').val('');
            	hoverField = $(this);
            	clearInterval(fieldSettingsInterval);
            	positionFieldSettings();
            	resetFieldSettingsDialog(hoverField);
            	
            	fieldSettings.animate({opacity: 1}, 'fast').find('#current-field').html(hoverField.attr('id'));
            	
            	old_field_value = $('#field-name-setting').val();
            	
            	
            });
            
            /** Field Settings Box **/
            $('.edit-page').live('click', function()
            {
                hideFieldSettings();
                $('#add_after_widget').val('');

                hoverPage = $('#'+$(this).parent().next().attr('rel'));
                //console.log(hoverPage);
                clearInterval(0);
                positionPageSettings();
                resetPageSettingsDialog(hoverPage);

                pageSettings.animate({opacity: 1}, 'fast').find('#current-page').html(hoverPage.attr('id'));

            });
            $('.edit-field').live('click', function()
            {
                hidePageSettings();
                $('#add_after_widget').val($(this).attr('id'));
                hoverField = $(this).parents('.field');
                clearInterval(fieldSettingsInterval);
                positionFieldSettings();
                resetFieldSettingsDialog(hoverField);

                fieldSettings.animate({opacity: 1}, 'fast').find('#current-field').html(hoverField.attr('id'));

                old_field_value = $('#field-name-setting').val();

            });

            $('.delete-field, .delete-title, .delete-submit').live('click', function()
            {
                $('#add_after_widget').val('');
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
            
            $('#delete-element').live('click', function()
            {
                $('#add_after_widget').val('');
                var field = $(this).attr('element_id');

                confirmAction(function() {
                    removeElement(field);
                    linkCaptionField();
                    if ($('.cameraclass').length < 1)
                    {
                        $('#takepic').remove();
                    }
                }, 'Delete Field');

            });
            
            //Adding Tab functionality
            $('.delete-tab').live('click', function()
            {
                $('#add_after_widget').val('');
                var parent_li_id = $(this).parent().parent().attr('id');
                var child_anchor_id = $('#' + parent_li_id + ' a').attr('rel');

                confirmAction(function() {
                    $('#' + child_anchor_id).remove();
                    $('#' + parent_li_id).remove();
                    var total_tabs = countTab();
                    if (total_tabs == 1)
                    {
                        $('#tabs-header').remove();
                    }
                }, 'Delete Tab');
            });
            
            $('.edit-tab').live('click', function()
            {
                $('#add_after_widget').val('');
                var tabtitle = $(this).parent().parent().find('a').html();
                tabtitle = tabtitle.replace(/&lt;/g,'<');
                tabtitle = tabtitle.replace(/&gt;/g,'>');
                $('#set-tab-title').find('input').val(tabtitle);
                var parent_id = $(this).parent().parent().attr('id');
                editTabTitle(parent_id);
            });
            
            $('.edit-tab-sep').live('click', function()
            {
                $('#add_after_widget').val('');
                var tabtitle = $(this).find('a').html();
                tabtitle = tabtitle.replace(/&lt;/g,'<');
                tabtitle = tabtitle.replace(/&gt;/g,'>');
                $('#set-tab-title').find('input').val(tabtitle);
                var parent_id = $(this).attr('id');
                editTabTitle(parent_id);
            });
            
            //Adding Page functionality
            $('.delete-page').live('click', function()
            {
                $('#add_after_widget').val('');
                var parent_li_id = $(this).parent().parent().attr('id');
                var child_anchor_id = $('#' + parent_li_id + ' a').attr('rel');

                confirmAction(function() {
                    $('#' + child_anchor_id).remove();
                    $('#' + parent_li_id).remove();
                    var total_pages = countPage();
                    if (total_pages == 1)
                    {
                        $('#pages-header').remove();
                    }
                }, 'Delete Page');
            });
            
            
            //Adding Table functionality
            $('.delete-table-widget').live('click', function()
            {
                var widget_id = $(this).attr('table_id');
               
                confirmAction(function() {
                    $("#"+widget_id).remove();
                    linkCaptionField();
                    if ($('.cameraclass').length < 1)
                    {
                        $('#takepic').remove();
                    }
                }, 'Delete Field');

            });

            $('.edit-title').live('click', function()
            {
                $('#add_after_widget').val('');
                var formtitle = $(this).parent().parent().find('h2').html();
                formtitle = formtitle.replace(/&lt;/g,'<');
                formtitle = formtitle.replace(/&gt;/g,'>');
                $('#set-form-title').find('input').val(formtitle);
                editFormTitle();
            });
            
            $('.edit-title-sep').live('click', function()
            {
                $('#add_after_widget').val('');
                var formtitle = $(this).find('a').html();
                formtitle = formtitle.replace(/&lt;/g,'<');
                formtitle = formtitle.replace(/&gt;/g,'>');
                $('#set-form-title').find('input').val(formtitle);
                editFormTitle();
            });
            
            

            $('.edit-submit').live('click', function() {
                $('#add_after_widget').val('');
                var submittitle = $(this).parent().parent().find('input').val();
                submittitle = submittitle.replace(/&lt;/g,'<');
                submittitle = submittitle.replace(/&gt;/g,'>');
                $('#set-form-submit').find('input').val(submittitle);
                promptForInput($('#set-form-submit'), setSubmitValue);
            });
            
            $('.edit-submit-sep').live('click', function() {
                $('#add_after_widget').val('');
                var submittitle = $(this).find('span').html();
                //submittitle = submittitle.replace('&lt;','<');
                submittitle = submittitle.replace(/&lt;/g,'<');
                submittitle = submittitle.replace(/&gt;/g,'>');
                //submittitle = submittitle.replace(/>/g,'&gt;');
                $('#set-form-submit').find('input').val(submittitle);
                var tracking_stat = $('.edit-submit-sep').attr('tracking_status');
                $('#set-form-submit').find('#form_submit_tracking').val(tracking_stat);
                
                promptForSubmit($('#set-form-submit'), setSubmitValue);
            });
            $('.edit-save-sep').live('click', function() {
                $('#add_after_widget').val('');
                var submittitle = $(this).find('span').html();
                submittitle = submittitle.replace(/&lt;/g,'<');
                submittitle = submittitle.replace(/&gt;/g,'>');
                $('#set-save-only').find('input').val(submittitle);
                promptForSubmit($('#set-save-only'), setSaveButtonValue);
            });
            $('.edit-editsubmit-sep').live('click', function() {
                $('#add_after_widget').val('');
                var submittitle = $(this).find('span').html();
                submittitle = submittitle.replace(/&lt;/g,'<');
                submittitle = submittitle.replace(/&gt;/g,'>');
                $('#set-save-submit').find('input').val(submittitle);
                promptForSubmit($('#set-save-submit'), setSaveSubmitValue);
            });
            
            $('.edit-col').live('click', function()
            {
                var formtitle = $(this).html();
                formtitle = formtitle.replace(/&lt;/g,'<');
                formtitle = formtitle.replace(/&gt;/g,'>');
                $('#set-col-title').find('input').val(formtitle);
                editColTitle($(this));
            });
            
            $('.edit-row').live('click', function()
            {
                var formtitle = $(this).html();
                formtitle = formtitle.replace(/&lt;/g,'<');
                formtitle = formtitle.replace(/&gt;/g,'>');
                $('#set-row-title').find('input').val(formtitle);
                editRowTitle($(this));
            });
            

            $('#field-settings, #field-settings input').live('focus', function(event)
            {
                clearInterval(fieldSettingsInterval);
            });

            /** Field Settings Inputs **/
            $('#field-api-setting').bind('textchange, keyup', function(event, previousText)
            {
                var currentField = fieldSettings.find('#current-field').text();
                var label_value = $(this).val();
                $('#' + currentField).find('select').attr('api_url',label_value);
            });
            /** Field Settings Inputs **/
            $('#field-label-setting').bind('textchange, keyup', function(event, previousText)
            {
                var currentField = fieldSettings.find('#current-field').text();
                var label_value = $(this).val();
                $('#' + currentField).find('label:first').html(label_value);
            });

            //bind event for input name
            $('#field-name-setting').bind('textchange, keyup', function(e)
            {
                $(this).val($(this).val().replace(/[^a-zA-Z 0-9_]+/g, ""));
                $(this).val($(this).val().replace(/ /g, "_"));
                var currentField = fieldSettings.find('#current-field').text(),
                        $currentField = $('#' + currentField),
                        val = $(this).val();

                if ($currentField.hasClass('checkbox-group') && (val.substr(val.length - 2, val.length - 1) != '[]'))
                {
                    var last = val[val.length - 1];
                    val = (last == '[') ? val + ']' : val + '[]';
                }

                
                $currentField.find('[field_setting="main"]').each(function() {

                    if (!($(this).attr('rel') != 'undefined' && $(this).attr('rel') == 'skip')) {
                        $(this).attr('name', val);
                        linkCaptionField();
                    }
                });
                
                if($('#' + currentField).hasClass('taketimeclass')){
                	$('#'+currentField.split('-')[0]+'_location').attr('name',val+'_location');
                	$('#'+currentField.split('-')[0]+'_source').attr('name',val+'_source');
                }
            });

            $('#field-name-setting').bind('blur', function(e)
            {
            	var fortable = fieldSettings.find('#current-field').text();
            	var is_subtable = $('#'+fortable).find('[field_setting="main"]').attr('subtable');
            	
            	var sub_table_name = '';
            	if($('#'+fortable).find('[field_setting="main"]').attr('subtable_id') != undefined && $('#'+fortable).find('[field_setting="main"]').attr('subtable_id') != ''){
            		var sub_tab_id = $('#'+fortable).find('[field_setting="main"]').attr('subtable_id');
            		sub_table_name = $('#'+sub_tab_id).attr('name');
            	}
            	
            	if(is_subtable != undefined){
            		is_subtable = true;
            	}else{
            		is_subtable = false;
            	}
                var form_str = $('#form-builder').html();
                var form_id = $('#form_id').val();
                var new_field_value = $('#field-name-setting').val();
                var postedFieldData = {
                    'form_id': form_id,
                    'old_field_name': old_field_value,
                    'new_field_name': new_field_value,
                    'form_str': form_str,
                    'is_subtable' : is_subtable,
                    'sub_table_name':sub_table_name
                };
                var confirm_msg = "This field already exist, Do you want to make duplicate field";
                
                if(sub_table_name != '')
                {
	                	var existboth=0;
	                	$('#'+fortable).parent().find('input:first, textarea, select').each(function() {
	
	                       if ($(this).attr('name') == new_field_value) {
	                           existboth++;
	                       }
	                   });
	                   if(existboth == 2)
	                   {
	                	   if(!confirm(confirm_msg,'300'))
	                	   {
		                       $('#field-name-setting').val(old_field_value);
		                       $('#field-name-setting').focus();
		                       $('#field-name-setting').keyup();
		                       return false;
	                       }
	                   }
                	
                	}
                else{
                	
                	var existboth=0;
                    $('#form-builder').find('input:first, textarea, select').each(function() {

                       if ($(this).attr('name') == new_field_value) {
                           existboth++;
                       }
                   });
                   if(existboth == 2)
                   {
                	   
                	   if(!confirm(confirm_msg,'300'))
                	   {
	                       $('#field-name-setting').val(old_field_value);
	                       $('#field-name-setting').focus();
	                       $('#field-name-setting').keyup();
	                       return false;
                       }
                   }
                	
               }
                
                
                
                view_id = 0;
                if($('#view_id').length)
                {
                    var view_id = $('#view_id').val();
                    if(view_id=='default')
                    {
                        view_id = 0;
                    }
                }
                if(view_id==0)
                {

                    $.post(base_url + 'form/editTableColumn', postedFieldData, function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status == 'default'){
                            $('#field-name-setting').val(old_field_value);
                            $('#field-name-setting').focus();
                            $('#field-name-setting').keyup();
                            alert("This is a system column name, Please change");
                        }
                        else if(response.status == false)
                        {
                        	
				if(!confirm(confirm_msg,'300')){
				   $('#field-name-setting').val(old_field_value);
				   $('#field-name-setting').focus();
				   $('#field-name-setting').keyup();
				 }
                        }
                        else
                        {
                            old_field_value = new_field_value;
                        }

                    });
                    
                	
                	if($('#' + fortable).hasClass('taketimeclass')){
                		var postedTimeData = {
                                    'form_id': form_id,
                                    'old_field_name': old_field_value+'_location',
                                    'new_field_name': new_field_value+'_location',
                                    'form_str': form_str,
                                    'is_subtable' : is_subtable,
                                    'sub_table_name':sub_table_name
                                };
                		
	                	$.post(base_url + 'form/editTableColumn', postedTimeData, function(data) {
	                            var response = jQuery.parseJSON(data);
	                            if(response.status != false)
	                            {
	                            	//console.log(fortable.split('-')[0]+'_location');
	                            	//$('#'+fortable.split('-')[0]+'_location').attr('name',new_field_value+'_location');
	                            	
	                            }
	
	                        });
                		
                                var postedTimeData = {
                                    'form_id': form_id,
                                    'old_field_name': old_field_value+'_source',
                                    'new_field_name': new_field_value+'_source',
                                    'form_str': form_str,
                                    'is_subtable' : is_subtable,
                                    'sub_table_name':sub_table_name
                                };
                		
	                	$.post(base_url + 'form/editTableColumn', postedTimeData, function(data) {
	                            var response = jQuery.parseJSON(data);
	                            if(response.status != false)
	                            {
	                            	//console.log(fortable.split('-')[0]+'_location');
	                            	//$('#'+fortable.split('-')[0]+'_location').attr('name',new_field_value+'_location');
	                            	
	                            }
	
	                        });
                		
                	}
                }
            });

            //bind event for input Value
            $('#field-value-setting').bind('textchange', function()
            {
                var currentField = fieldSettings.find('#current-field').text(),
                        $currentField = $('#' + currentField),
                        val = $(this).val();
                        $currentField.find('input[type="text"],input[type="number"],textarea').each(function() {
                            if($(this).is('textarea'))
                            {
                                $(this).html(val);
                            }
                            else
                            {
                                $(this).attr('Value', val);
                            }
                        });

            });

            //bind event for Dependent Value
            $('#field-parent-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('parent_id', val);
                    });

            });
            //bind event for Dependent Value
            $('#field-dependon-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('dependon_id', val);
                    });

            });
            //bind event for Action Before current
            $('#field-action-required-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('action_before', val);
                    });

            });
            
            //bind event for Action Before current
            $('#field-time-taken-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('time_taken', val);
                    });

            });
            //bind event for Action Before current
            $('#field-tracking-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('tracking_status', val);
                    });

            });
            
            //bind event for Time Validation current
            $('#field-time-validation-setting').bind('change', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('time_validation', val);
                    });

            });
            
            $('#field-dependon-value-setting').bind('keyup', function()
            {
                
                var currentField = fieldSettings.find('#current-field').text(),
                    $currentField = $('#' + currentField),
                    val = $(this).val();
                    $currentField.find('[field_setting="main"]').each(function() {
                        $(this).attr('dependon_value', val);
                    });

            });
            $('.select_rules').live('change', function(){
                var ele_id = $(this).find('option:selected').attr('element_id');
                var page_id = $(this).find('option:selected').attr('value');
                $('#'+ele_id).attr('page_id',page_id);
                
            });
            $('.minclass').live('change', function(){
                var ele_id = $(this).attr('element_id');
                var min_value = $(this).attr('value');
                $('#'+ele_id).attr('min_value',min_value);
                
            });
            $('.maxclass').live('change', function(){
                var ele_id = $(this).attr('element_id');
                var max_value = $(this).attr('value');
                $('#'+ele_id).attr('max_value',max_value);
                
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
            $('#close-apply').click(function()
            {
                hideFieldSettings();
            });
            $('#close-apply-page').click(function()
            {
                hidePageSettings();
            });

            //add page functionality
            $('#next_page').bind('change', function()
            {
                var  val_next = $(this).val();
                var currentPage = pageSettings.find('#current-page').text();
                
               $('#'+currentPage).find('.page-next').attr('page_id', val_next);
               if(val_next=='')
                {
                    $('#'+currentPage).find('.page-next').hide();
                }
                else{
                     $('#'+currentPage).find('.page-next').show();
                }
            });
            $('#previous_page').bind('change', function()
            {
                var  val_prev = $(this).val();
                var currentPage = pageSettings.find('#current-page').text();
               $('#'+currentPage).find('.page-previous').attr('page_id', val_prev);
               if(val_prev=='')
                {
                    $('#'+currentPage).find('.page-previous').hide();
                }
                else{
                     $('#'+currentPage).find('.page-previous').show();
                }
            });
            $('#skip_page').bind('click', function()
            {
                var currentPage = pageSettings.find('#current-page').text();
                if($(this).attr('checked')){
                    $('#'+currentPage).find('.page-skip').attr('skip', 'yes');
                    $('#'+currentPage).find('.page-skip').attr('page_id', currentPage);
                    $('#'+currentPage).find('.page-skip').show();
                }
                else{
                    $('#'+currentPage).find('.page-skip').attr('skip', '');
                    $('#'+currentPage).find('.page-skip').attr('page_id', '');
                    $('#'+currentPage).find('.page-skip').hide();
                }
//                var  val_prev = $(this).attr('checked');
//                alert(val_prev);
//                var currentPage = pageSettings.find('#current-page').text();
//               $('#'+currentPage).find('.page-previous').attr('page_id', val_prev);
            });
            
            
            $('.add_new_condition').live('click', function(){
                //condition_id='"+element_form_id+"_condition_"+con_num+"';
                var element_id = $(this).attr('element_id');
                var con_num = 2;
                var selected_page ='';
                var vallsit='';
                var valrule='';
                var selected_page ='';
                var min_value ='';
                var max_value ='';
                  
                var canculate_condition_id = calculate_condition_id(element_id);
                $('#'+element_id+'_condition').append('<option id="'+element_id+'_condition_'+canculate_condition_id+'"></option>');
                $('#element_value').empty();
                vallsit = '<div style="float: left;width:20%;margin-left:5px">From</div><div style="float: left;width:20%;margin-left:5px">To</div><div style="float: right; width: 55%;">Redirect to</div>';
                $('#'+element_id+'_condition').find('option').each(function(){
                            selected_page=0;
                            min_value=0;
                            max_value=0;
                            if($(this).attr('page_id')!=undefined){
                                selected_page = $(this).attr('page_id');
                            }
                            if($(this).attr('min_value')!=undefined){
                                min_value = $(this).attr('min_value');
                            }
                            if($(this).attr('max_value')!=undefined){
                                max_value = $(this).attr('max_value');
                            }
                    
                            con_num++;
                            var element_form_id = $(this).attr('id');
                            vallsit += '<div style="float:left;"><span class="remove-condition" element_id="'+element_form_id+'"> &nbsp; </span><div style="float: left;width:20%"><input type="number" element_id="'+element_form_id+'" class="minclass" value="'+min_value+'" /></div>';
                            vallsit += '<div style="float: left;width:20%;margin-left:10px"><input type="number" element_id="'+element_form_id+'" class="maxclass"  value="'+max_value+'"/></div>';
                            valrule = create_next_rule(element_form_id,'number',selected_page);
                            vallsit += '<div style="float: left; width: 40%;margin-left:10px;"> '+valrule+'</div></div>';
                            
                });
                vallsit += "<input type='button' class='add_new_condition' value='Add Condition' element_id='"+element_id+"'>";
                $('#element_value').append(vallsit);

                

            });
            
            
            
            $('.remove-condition').live('click', function(){
                var element_id = $(this).attr('element_id');
                $(this).parent().remove();
                $('#'+element_id).remove();
            });
            //$('.pageli').find('[rel="'+current_active_page+'"]').text()
            $('#title_page').bind('keyup', function(){
                
                var current_page_title = pageSettings.find('#current-page').text();
                $('.pageli').find('[rel="'+current_page_title+'"]').html($(this).val());
//                var element_id = $(this).attr('element_id');
//                $(this).parent().remove();
//                $('#'+element_id).remove();
            });

            $('.up-first-option').live('click', function(){
                var setting_option_id = $(this).parent().attr('id');
                var field_name = $('#current-field').text();
                setting_option_id = setting_option_id.split('-');
                var option_id = setting_option_id[0]+'-'+setting_option_id[1];

                if($("#"+option_id+"-setting").prev().hasClass('option')){

                    var temp_html_setting = $("#"+option_id+"-setting").prop('outerHTML');
                    //Get here first option
                    // var prev_ele_setting = $("#"+option_id+"-setting").prev().attr('id');
                    $("#"+option_id+"-setting").remove();
                    $("#input-settings div:first-child").before(temp_html_setting);
                    // $('#'+prev_ele_setting).before(temp_html_setting); 


                     var temp_html = $("#"+option_id).prop('outerHTML');
                    // var prev_ele = $("#"+option_id).prev().attr('id');
                     $("#"+option_id).remove();
                     $('#'+setting_option_id[0]+' option:first-child').before(temp_html);
                    $('#'+field_name).trigger('click');
                }
            });
            $('.up-option').live('click', function(){
                var setting_option_id = $(this).parent().attr('id');
                var field_name = $('#current-field').text();
                setting_option_id = setting_option_id.split('-');
                var option_id = setting_option_id[0]+'-'+setting_option_id[1];

                if($("#"+option_id+"-setting").prev().hasClass('option')){

                    var temp_html_setting = $("#"+option_id+"-setting").prop('outerHTML');
                    var prev_ele_setting = $("#"+option_id+"-setting").prev().attr('id');
                    $("#"+option_id+"-setting").remove();
                    $('#'+prev_ele_setting).before(temp_html_setting); 


                    var temp_html = $("#"+option_id).prop('outerHTML');
                    var prev_ele = $("#"+option_id).prev().attr('id');
                    $("#"+option_id).remove();
                    $('#'+prev_ele).before(temp_html);
                    $('#'+field_name).trigger('click');
                }
            });

            $('.down-option').live('click', function(){
                var setting_option_id = $(this).parent().attr('id');
                var field_name = $('#current-field').text();
                setting_option_id = setting_option_id.split('-');
                var option_id = setting_option_id[0]+'-'+setting_option_id[1];

                if($("#"+option_id+"-setting").next().hasClass('option')){

                    var temp_html_setting = $("#"+option_id+"-setting").prop('outerHTML');
                    var prev_ele_setting = $("#"+option_id+"-setting").next().attr('id');

                    $("#"+option_id+"-setting").remove();
                    $('#'+prev_ele_setting).after(temp_html_setting); 


                    var temp_html = $("#"+option_id).prop('outerHTML');
                    var prev_ele = $("#"+option_id).next().attr('id');
                    $("#"+option_id).remove();
                    $('#'+prev_ele).after(temp_html);
                    $('#'+field_name).trigger('click');
                }
            });

            $('.down-last-option').live('click', function(){
                var setting_option_id = $(this).parent().attr('id');
                var field_name = $('#current-field').text();
                setting_option_id = setting_option_id.split('-');
                var option_id = setting_option_id[0]+'-'+setting_option_id[1];

                if($("#"+option_id+"-setting").next().hasClass('option')){

                    var temp_html_setting = $("#"+option_id+"-setting").prop('outerHTML');
                    //Get here first option
                    // var prev_ele_setting = $("#"+option_id+"-setting").prev().attr('id');
                    $("#"+option_id+"-setting").remove();
                    $("#input-settings div:last-child").after(temp_html_setting);
                    // $('#'+prev_ele_setting).before(temp_html_setting); 


                     var temp_html = $("#"+option_id).prop('outerHTML');
                    // var prev_ele = $("#"+option_id).prev().attr('id');
                     $("#"+option_id).remove();
                     $('#'+setting_option_id[0]+' option:last-child').after(temp_html);
                    $('#'+field_name).trigger('click');
                }
            });

            $('#page_elements').bind('change', function()
            {
                
                //logical rules - start
                var element_type = $('#page_elements option:selected').attr('element_type');
                var element_form_id = $(this).attr('value');
                $('#element_value').empty();
                var vallsit ='';
                var valrule = '';
                var selected_page ='';
                var min_value ='';
                var max_value ='';
                if(element_type=='select'){
                    $('#'+element_form_id).find('option').each(function(){
                        if($(this).attr('page_id')!=undefined){
                            selected_page = $(this).attr('page_id');
                        }
                        var ele_form_id = $(this).attr('id');
                        if($(this).attr('value')==''){
                        	vallsit = '<div style="height: 100%; clear: both;"><div style="float: left; padding: 5px; width: 45%; text-align: right;">Empty option</div>';
                        }
                        else{
                        	vallsit = '<div style="height: 100%; clear: both;"><div style="float: left; padding: 5px; width: 45%; text-align: right;">'+$(this).attr('value')+'</div>';
                        }
                        
                        valrule = create_next_rule(ele_form_id,element_type,selected_page);
                        vallsit += '<div style="float: right; width: 50%;"> '+valrule+'</div></div>';
                        $('#element_value').append(vallsit);
                    });
                    
                }
                else if(element_type=='radio'){
                    
                    $('input[name="'+element_form_id+'"]').each(function(){
                        if($(this).attr('page_id')!=undefined){
                            selected_page = $(this).attr('page_id');
                        }
                        element_form_id = $(this).attr('id');
                        vallsit = '<div style="height: 100%; clear: both;"><div style="float: left; padding: 5px; width: 45%; text-align: right;">'+$(this).attr('value')+'</div>';
                        valrule = create_next_rule(element_form_id,element_type,selected_page);
                        vallsit += '<div style="float: right; width: 50%;"> '+valrule+'</div></div>';
                        $('#element_value').append(vallsit);
                    });
                    
                }
                else if(element_type=='number'){
                    if($('#'+element_form_id+'_condition').length>0){
                        
                        vallsit = '<div style="float: left;width:20%;margin-left:5px">From</div><div style="float: left;width:20%;margin-left:5px">To</div><div style="float: right; width: 55%;">Redirect to</div>';
                        $('#'+element_form_id+'_condition').find('option').each(function(){

                            selected_page=0;
                            min_value=0;
                            max_value=0;
                            if($(this).attr('page_id')!=undefined){
                                selected_page = $(this).attr('page_id');
                            }
                            if($(this).attr('min_value')!=undefined){
                                min_value = $(this).attr('min_value');
                            }
                            if($(this).attr('max_value')!=undefined){
                                max_value = $(this).attr('max_value');
                            }
                            var cond_form_id = $(this).attr('id');
                            vallsit += '<div style="float:left;"><span class="remove-condition" element_id="'+cond_form_id+'"> &nbsp; </span><div style="float: left;width:20%"><input type="number" element_id="'+cond_form_id+'" class="minclass" value="'+min_value+'" /></div>';
                            vallsit += '<div style="float: left;width:20%;margin-left:10px"><input type="number" element_id="'+cond_form_id+'" class="maxclass"  value="'+max_value+'"/></div>';
                            valrule = create_next_rule(cond_form_id,element_type,selected_page);
                            vallsit += '<div style="float: left; width: 40%;margin-left:10px;"> '+valrule+'</div></div>';
                            $('#element_value').append(vallsit);
                            vallsit='';
                        });


                        vallsit += "<input type='button' class='add_new_condition' value='Add Condition' element_id='"+element_form_id+"'>";
                        $('#element_value').append(vallsit);


                    }
                    else{
                        var new_condition = "<conditionarea id='"+element_form_id+"_condition' style='display:none;'></conditionarea>"
                        $('#'+element_form_id).parent().append(new_condition);
                        vallsit = "<input type='button' class='add_new_condition' value='Add Condition' element_id='"+element_form_id+"'>";
                        $('#element_value').append(vallsit);
                    }
                
            }
                //logical rules - end
                
                var currentPage = pageSettings.find('#current-page').text();
                if($(this).attr('checked')){
                    $('#'+currentPage).find('.page-skip').attr('skip', 'yes');
                    $('#'+currentPage).find('.page-skip').attr('page_id', currentPage);
                    $('#'+currentPage).find('.page-skip').show();
                }
                else{
                    $('#'+currentPage).find('.page-skip').attr('skip', '');
                    $('#'+currentPage).find('.page-skip').attr('page_id', '');
                    $('#'+currentPage).find('.page-skip').hide();
                }
                var  val_prev = $(this).attr('checked');
                var currentPage = pageSettings.find('#current-page').text();
               $('#'+currentPage).find('.page-previous').attr('page_id', val_prev);
            });
            
            execute_callback(callback);
        }
        
        function calculate_condition_id(element_req){
            var inc=1;
            $('#'+element_req+'_condition').find('option').each(function(){
                if($('#'+element_req+'_condition_'+inc).length > 0){
                    inc++;
                }
            });
            return inc;
            
        }
        function create_next_rule(element_id,element_type,selected_page){
                
                var current_active_page = $('#current-page').text();
                var currentPage = $('#'+current_active_page);
                var next_page='';
                var next_ele='';
                
                next_page += '<option value="" element_id="'+element_id+'" element_type="'+element_type+'">Please Select</option>';
                $('.pages').find('li').each(function(){
                
                    var page_title = $(this).find('a').html();
                    var page_id = $(this).find('a').attr('rel');

                    if(current_active_page!=page_id)
                    {
                        if(selected_page == page_id)
                        {
                            next_page += '<option value="'+page_id+'" element_id="'+element_id+'" element_type="'+element_type+'" selected>'+page_title+'</option>';
                        }
                        else{
                            next_page += '<option value="'+page_id+'" element_id="'+element_id+'" element_type="'+element_type+'">'+page_title+'</option>';
                        }
                        
                    }
                });
               return next_ele = '<select class="select_rules">'+next_page+'</select>';
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
            if ($("#tabs-header").length > 0)
            {
                var active_tab = 0;
                $('ul.tabs a').each(function() {
                    if ($(this).hasClass('active'))
                    {
                        active_tab = $(this).attr('rel');
                        return active_tab;
                    }
                });
            }
            return active_tab;
        }
        function getActiveLoop()
        {
        	var active_loop = 0;
        	if ($(".loop-widget-main").length > 0)
        	{
        		
        		$('.loop-widget-main').each(function() {
        			if ($(this).hasClass('active_loop'))
        			{
        				active_loop = $(this).attr('id');
        				return active_loop;
        			}
        		});
        	}
        	return active_loop;
        }
        function getInputIdForTable()
        {
        	var active_loop_id = getActiveLoop();
        	var active_loop_input = $("#addmore"+active_loop_id).find('input').attr('id');
        	return active_loop_input;
        	
        }
        
        function getActivePage()
        {
            if ($("#pages-header").length > 0)
            {
                var active_page = 0;
                $('ul.pages a').each(function() {
                    if ($(this).hasClass('active'))
                    {
                        active_page = $(this).attr('rel');
                        return active_page;
                    }
                });
            }
            return active_page;
        }


        /** Add a field to the form **/
        function addRow2(fieldobj)
        {
            var table_id = fieldobj.attr('table_id');
            var row_id = fieldobj.attr('last_row_id');
            fieldobj.attr('last_row_id',parseInt(fieldobj.attr('last_row_id'))+1);
            var last_row_id = parseInt(fieldobj.attr('last_row_id'))+1;
            var field = {
                            fieldType: 'table-row2-adding',
                            table_id: table_id,
                            row_id: row_id,
                            last_row_id: last_row_id
                        };
           fieldobj.before($('#form-elements-tmpl').tmpl(field));
        }
        function addRow3(fieldobj)
        {
            var table_id = fieldobj.attr('table_id');
            var row_id = fieldobj.attr('last_row_id');
            fieldobj.attr('last_row_id',parseInt(fieldobj.attr('last_row_id'))+1);
            var last_row_id = parseInt(fieldobj.attr('last_row_id'))+1;
            var field = {
                            fieldType: 'table-row3-adding',
                            table_id: table_id,
                            row_id: row_id,
                            last_row_id: last_row_id
                        };
           fieldobj.before($('#form-elements-tmpl').tmpl(field));
        }
        function addLoopWidget(fieldobj){
        	

            var fieldType = fieldobj.attr('id');
            
          //loop-widget
            
                    var next_loop_id = nextLoopId();
                    var thisNewField,
                            introRemoved,
                            loopId = next_loop_id,
                            field = {
                                fieldType: fieldType,
                                id: loopId                               
                            };

                columnWidth = formBuilder.width() / 4;

                var active_tab = getActiveTab();
                var active_page = getActivePage();
                //alert(active_page);
                //Make sure the field is added before the submit button
                var afterwidget = $('#add_after_widget').val();
                
                    if (active_tab)
                    {
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else{
                            if($("#" + active_tab).find('#form-submit').length>0){
                                $('#form-submit').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                                $('#form-elements-tmpl').tmpl(field).appendTo("#" + active_tab);
                            }
                            
                        }
                    }  
                    else if (active_page)
                    {
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else
                        {
                            if($("#" + active_page).find('#form-submit').length>0){
                                $('#'+active_page).find('#form-submit').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                                $('#'+active_page).find('.page-btn-div-class').before($('#form-elements-tmpl').tmpl(field));
                            }
                        }
                    } 
                    else 
                    {
                       
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else
                        {
                            if ($('.submit').length > 0){
                                 $('#form_id').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                               $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);
                            }
                        }
                    }
 //               }

                //linkCaptionField();
                //thisNewField = $('#' + fieldId);
            
        	
        }
        function addField(fieldobj)
        {
            var fieldType = fieldobj.attr('id');
            
            //This condition for Landing page
            if(fieldType == 'form-icon-field')
            {
                var fieldType = fieldobj.attr('id');
                var icon = fieldobj.attr('icon');
                var formurl = fieldobj.attr('form-url');
                var formtitle = fieldobj.attr('form-title');
                var iconurl = fieldobj.attr('icon-url');
                
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

                               
            }
            else
            {

            	var active_tab = getActiveTab();
                var active_page = getActivePage();
                var active_loop = getActiveLoop();
                
                var iconurl = '';
                if (fieldType == 'camera-field')
                {
                	if(active_loop)
            		{
	            		alert('This widget is not allow in loop widget');
	            		return;
            		}
                    if ($('#takepic').length < 1)
                    {
                        $('#form-preview').append('<input type="hidden" name="takepic" id="takepic" value="" />');
                    }
                    iconurl = fieldobj.attr('icon-url');
                    // if ($('.cameraclass').length > 4)
                    // {
                    //     alert('Object added only five time in a form');
                    //     return;
                    // }
                }
                if (fieldType == 'lat-lang-field')
                {
                	if(active_loop)
            		{
	            		alert('This widget is not allow in loop widget');
	            		return;
            		}
                    if ($('.latlangclass').length > 0)
                    {
                        alert('LatLong Button will added only once in a form');
                        return;
                    }
                }
                if (fieldType == 'table-widget-2' || fieldType == 'table-widget-3')
                {
                    if($('.tbl_widget').hasClass('active_table'))
                    {
                        $('.tbl_widget').removeClass('active_table');
                    }
                    var table = countTable();
                    var thisNewField,
                        introRemoved,
                        fieldId = 'table' + table,
                        field = {
                            fieldType: fieldType,
                            id: fieldId,
                        };
                }
                else
                {
                	var subtable_id = '';
                	if(active_loop)
            		{
                		subtable_id = getInputIdForTable();
                		
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
                                subtable_id :subtable_id,
                            };

                }


                hideFieldSettings();

                columnWidth = formBuilder.width() / 4;

                
               
                //alert(active_page);
                //Make sure the field is added before the submit button
                var afterwidget = $('#add_after_widget').val();
                
                    if($('.tbl_widget').hasClass('active_table'))
                    {
                        $('#form-elements-tmpl').tmpl(field).appendTo($('.active_td'));
                    }
                    else if (active_loop){
                    	if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else{
                            
                            $("#" + active_loop).find('.loop-btn-cls').before($('#form-elements-tmpl').tmpl(field));
                            
                        }
                    }
                    else if (active_tab)
                    {
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else{
                            if($("#" + active_tab).find('#form-submit').length>0){
                                $('#form-submit').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                                $('#form-elements-tmpl').tmpl(field).appendTo("#" + active_tab);
                            }
                            
                        }
                    }  
                    else if (active_page)
                    {
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else
                        {
                            if($("#" + active_page).find('#form-submit').length>0){
                                $('#'+active_page).find('#form-submit').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                                $('#'+active_page).find('.page-btn-div-class').before($('#form-elements-tmpl').tmpl(field));
                            }
                        }
                    } 
                    else 
                    {
                       
                        if(afterwidget!='')
                            $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                        else
                        {
                            if ($('.submit').length > 0){
                                 $('#form-submit').before($('#form-elements-tmpl').tmpl(field));
                            }else{
                               $('#form-elements-tmpl').tmpl(field).appendTo(formPreview);
                            }
                        }
                    }
 //               }

                linkCaptionField();
                thisNewField = $('#' + fieldId);
            }
        }
        //Adding caption with take image controll by Zahid Nadeem
        function linkCaptionField()
        {
            $('#form-preview').find('.link-field').each(function() {
                var fieldId = $(this).attr('id');
                $('#' + fieldId).empty();
                var templist = [];
                $('#' + fieldId).append('<option value="custom" display_value="Custom" >Custom</option>');
                $('#form-preview').find('input, textarea, select').each(function() {
                    var field_name = $(this).attr('name');
                    var field_id = $(this).attr('id');
                    //field_name = field_name.replace('[]', '');
                    var skip = $(this).attr('rel');
                    var type = $(this).attr('type');

                    if ($.inArray(field_name, templist) == '-1')
                    {

                        templist.push(field_name);
                        if (field_name != 'security_key' && field_name != 'form_id' && field_name != 'takepic' && skip != 'skip' && type != 'submit' && skip != 'norepeat')
                        {
                            var label_value = $('#' + field_id + '-container').find('label').text();
                            if (label_value == '')
                            {
                                label_value = field_name;
                            }
                            field_id = field_id.replace('[]', '');
                            var field_id_new = field_id.split('-')[0];
                            //field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g,"");
                            $('#' + fieldId).append('<option value="' + field_id_new + '" display_value="' + label_value + '">' + label_value + '</option>');
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
        /** Remove a field from the form **/
        function removeElement(field)
        {
        	if($('#'+field).attr('loop_id') != undefined){
        		var loop_id = $('#'+field).attr('loop_id');
        		$('#loop-'+loop_id).fadeOut();
        		$('#loop-list-'+loop_id).fadeOut();
        		$('#loop-'+loop_id).remove();
        		$('#loop-list-'+loop_id).remove();
        	}
        	else
    		{
	            $('#'+field).fadeOut();
	            $('#'+field).remove();
    		}
            hideFieldSettings();

        }




        /** Add a title or submit to the form **/
        function addFormElement(type, form_name)
        {
        	var icon_web = '';
        	var icon_device = '';
            if (form_name == '')
            {
                form_name = 'Form title';
                
            }
            else{
                form_name = form_name.replace(/</g,'&lt;');
                form_name = form_name.replace(/>/g,'&gt;');
            }
            if (type == 'title'){
                if (type == 'title'){
                    if ($('#form-title').length <= 0)
                    {
                            icon_web = $('#title-field').attr('icon_web');
                            icon_device = $('#title-field').attr('icon_device');
                            var field = {
                            fieldType: type + '-field',
                            id: 'form-' + type,
                            fieldWidth: 'f_100',
                            actionsType: type,
                            title: form_name,
                            icon_web: icon_web,
                            icon_device: icon_device,
                        };
                        $('#form-elements-tmpl').tmpl(field).prependTo(formBuilder);
                    }
                }
            }else{
                var field = {
                    fieldType: type + '-field',
                    id: 'form-' + type,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    title: form_name,
                    icon_web: icon_web,
                    icon_device: icon_device,
                };

                var active_tab = getActiveTab();
                var active_page = getActivePage();
                //alert(active_page);
                //Make sure the field is added before the submit button
                var afterwidget = $('#add_after_widget').val();

                if (active_tab)
                {
                    if(afterwidget!='')
                        $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                    else
                        $('#form-elements-tmpl').tmpl(field).appendTo("#" + active_tab);
                }
                else if (active_page)
                {
                    if(afterwidget!='')
                        $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                    else
                    {
                        $('#'+active_page).find('.page-btn-div-class').before($('#form-elements-tmpl').tmpl(field));
                    }
                        //$('#form-elements-tmpl').tmpl(field).appendTo("#" + active_page);
                }
                else
                {
                    if(afterwidget!='')
                        $('#'+afterwidget).after($('#form-elements-tmpl').tmpl(field));
                    else
                        $('#form_id').before($('#form-elements-tmpl').tmpl(field));
                }

            }
            

        }

        /** Add a title or submit to the form **/
        function addFormTabElement(fieldobj, title)
        {



            var fieldType = fieldobj.attr('id');

            var addedFieldCountTab = nextTabId();
            fieldId = 'field' + (addedFieldCountTab) + '-tab';
            //addedFieldCount++;
            if (title == '')
            {
                title = 'Tab ' + addedFieldCountTab + ' Title';
            }

            var type = 'tab';

            var field = {
                fieldType: type + '-field',
                id: fieldId,
                title: title,
                fieldWidth: 'f_100',
                actionsType: type,
                tabid: 'tab' + addedFieldCountTab
            };

            if ($("#tabs-header").length > 0)
            {
                var fieldtab = {
                    fieldType: type + '-field',
                    id: fieldId,
                    title: title,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    tabid: 'tab' + addedFieldCountTab
                };

                $('#field-tab-tmpl').tmpl(fieldtab).appendTo($("#tabs-header .tabs"));
                $('#field-tab-area-tmpl').tmpl(fieldtab).insertAfter($("#tabs-header"));
                $("#title-" + fieldId).trigger('click');
                
                if(addedFieldCountTab==2)
                $("#saveForm").trigger('click');

            }
            else
            {

                $('#form-elements-tmpl').tmpl(field).prependTo($("#form-preview"));
                bindResizableToFields($('#tabs-header').animate({opacity: 1}, 'fast'));

                if (addedFieldCountTab == 1) {
                    $('#form-preview').find(".field").each(function()
                    {
                        if ($(this).attr('id') == 'tabs-header' || $(this).attr('id') == 'form-submit') {

                        }
                        else {
                            //alert($(this).attr('id'));
                            $('#' + $(this).attr('id')).prependTo($("#tab" + addedFieldCountTab));

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
        /** Add a title or submit to the form **/
        function addFormPageElement(fieldobj, title)
        {



            var fieldType = fieldobj.attr('id');

            var addedFieldCountPage = nextPageId();
            fieldId = 'field' + (addedFieldCountPage) + '-page';
            //addedFieldCount++;
            if (title == '')
            {
                title = 'Page ' + addedFieldCountPage
            }

            var type = 'page';

            var field = {
                fieldType: type + '-field',
                id: fieldId,
                title: title,
                fieldWidth: 'f_100',
                actionsType: type,
                pageid: 'page' + addedFieldCountPage
            };

            if ($("#pages-header").length > 0)
            {
                var fieldpage = {
                    fieldType: type + '-field',
                    id: fieldId,
                    title: title,
                    fieldWidth: 'f_100',
                    actionsType: type,
                    pageid: 'page' + addedFieldCountPage
                };

                $('#field-page-tmpl').tmpl(fieldpage).appendTo($("#pages-header .pages"));
                $('#field-page-area-tmpl').tmpl(fieldpage).insertAfter($("#pages-header"));
                $("#title-" + fieldId).trigger('click');
                
                //if(addedFieldCountPage==2)
                //$("#saveForm").trigger('click');

            }
            else
            {

                $('#form-elements-tmpl').tmpl(field).prependTo($("#form-preview"));
                bindResizableToFields($('#pages-header').animate({opacity: 1}, 'fast'));

                if (addedFieldCountPage == 1) {
                    $('#form-preview').find(".field").each(function()
                    {
                        if ($(this).attr('id') == 'pages-header' || $(this).attr('id') == 'form-submit') {

                        }
                        else {
                            //alert($(this).attr('id'));
                            $('#' + $(this).attr('id')).prependTo($("#page" + addedFieldCountPage));

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
                        var form_name=$(this).find('input').val();
                        form_name = form_name.replace(/</g,'&lt;');
                        form_name = form_name.replace(/>/g,'&gt;');
                        setFormTitle(form_name);
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
        function editColTitle(ele)
        {
            $('#set-col-title').dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Apply": function()
                    {
                        var col_name=$(this).find('input').val();
                        //col_name = col_name.replace(/</g,'');
                        col_name = col_name.replace(/</g,'&lt;');
                        col_name = col_name.replace(/>/g,'&gt;');
                        
                        setColTitle(ele,col_name);
                        $(this).dialog("close");
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }
        
        function editRowTitle(ele)
        {
            $('#set-row-title').dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Apply": function()
                    {
                        var row_name=$(this).find('input').val();
                        //row_name = row_name.replace(/</g,'');
                        row_name = row_name.replace(/</g,'&lt;');
                        row_name = row_name.replace(/>/g,'&gt;');
                        setRowTitle(ele,row_name);
                        $(this).dialog("close");
                    },
                    Cancel: function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        }

        /** Edit the tab title **/
        function editTabTitle(parent_id)
        {
            $('#set-tab-title').dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Apply": function()
                    {
                        var tab_name=$(this).find('input').val();
                        //tab_name = tab_name.replace(/</g,'');
                        tab_name = tab_name.replace(/</g,'&lt;');
                        tab_name = tab_name.replace(/>/g,'&gt;');
                        setTabTitle(tab_name, parent_id);
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
            $('#form-title a.navbar-brand').html(title);
        }
        /** Set the form title **/
        function setColTitle(ele,title)
        {
            ele.html(title);
        }
        function setRowTitle(ele,title)
        {
            ele.html(title);
        }
        /** Set the tab title **/
        function setTabTitle(title, parent_id)
        {
            $('#title-' + parent_id).html(title);
        }



        function setSubmitValue(value)
        {
            //value = value.replace(/</g,'');
            value = value.replace(/</g,'&lt;');
            value = value.replace(/>/g,'&gt;');
            $('#form-submit span').html(value);
        }
        function setSaveButtonValue(value)
        {
            //value = value.replace(/</g,'');
            value = value.replace(/</g,'&lt;');
            value = value.replace(/>/g,'&gt;');
            $('#form-save span').html(value);
        }
        function setSaveSubmitValue(value)
        {
            //value = value.replace(/</g,'');
            value = value.replace(/</g,'&lt;');
            value = value.replace(/>/g,'&gt;');
            $('#form-submitedit span').html(value);
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
            var element_name = 'Option 1';
            //pre-populate label and field name with current value
            if(currentField.hasClass('checkbox-group')){
                element_name = currentField.find('input:first').attr('name');
                if(element_name!=undefined)
                element_name = element_name.replace('[]', '');
                else{return false;}
            }
            else if(currentField.hasClass('cameraclass'))
            {
                element_name = currentField.find('input[type="hidden"]').attr('name');
               
            }else{
                element_name = currentField.find('input:first, select, textarea').attr('name');
            }
            if(element_name==undefined)
            return false;
            //alert(currentField.attr('id'));
            fieldSettings.find('#delete-element').attr('element_id',currentField.attr('id'));
            fieldSettings.find('#field-label-setting').val(currentField.find('label').html());
            fieldSettings.find('#field-name-setting').val(element_name);
            fieldSettings.find('#field-dependon-value-setting').attr('dependon_value',currentField.attr('dependon_value'));
            fieldSettings.find('#field-tracking-setting').val(currentField.find('input:first').attr('tracking_status')).trigger('change');
            
            $('#field-time-taken-setting').parent().hide();
            if(currentField.hasClass('taketimeclass')){
                $('#field-time-taken-setting').parent().show();
                fieldSettings.find('#field-time-taken-setting').val(currentField.find('input:first').attr('time_taken')).trigger('change');
                
            }
            var current_field_name = element_name;
            var selected_parent='';
            var selected_dependon='';
//            console.log(currentField);
//            if(currentField.attr('parent_id') != undefined && currentField.attr('parent_id')!=''){
//                selected_val = currentField.attr('parent_id');
//                alert(selected_val);
//            }
            
            fieldSettings.find('#field-dependon-value-setting').val('');
            $('.defaultvalue').show();
            fieldSettings.find('#field-value-setting').parent().show();
            currentField.find('[field_setting="main"]').each(function(){
                //if($(this).attr('field_setting')!=undefined && $(this).attr('field_setting')=='main'){
                    if($(this).is('textarea'))
                    {
                        fieldSettings.find('#field-value-setting').val($(this).html());
                        
                    }
                    else if($(this).is(':radio') || $(this).is(':checkbox') || $(this).is('select')){
                        fieldSettings.find('#field-value-setting').parent().hide();
                    }
                    else{
                        if(currentField.hasClass('cameraclass')){
                            fieldSettings.find('#field-value-setting').val(currentField.find('input:first').val());
                        }else{
                            fieldSettings.find('#field-value-setting').val($(this).attr('value'));
                        }
                    }
                    selected_parent = $(this).attr('parent_id');
                    selected_dependon = $(this).attr('dependon_id');
                    fieldSettings.find('#field-dependon-value-setting').val($(this).attr('dependon_value'));
                //}
            });
            
            var isselectele = 0;
            currentField.find('[field_setting="main"]').each(function(){
                 if($(this).is('select')){
                     
                    if($(this).attr('parent_id') != undefined && $(this).attr('parent_id').length > 0){
                        
                        var selid = $(this).attr('id');
                        if($('#'+selid+'_temp') != undefined && $('#'+selid+'_temp').html().trim() != '')
                        {
                            $('#'+selid).html($('#'+selid+'_temp').html());
                            $('#'+selid+'_temp').html('');
                        }
                    }
                    isselectele=1;
                   
                    var api_url = $(this).attr('api_url');
                    var select_id = $(this).attr('id');
                    fieldSettings.find('#field-api-setting').val(api_url);
                    fieldSettings.find('#field-api-setting').attr('select_id',select_id);

                    fieldSettings.find('#field-parent-setting').parent().show();
                    selected_parent = $(this).attr('parent_id');
                    selected_dependon = $(this).attr('dependon_id');
                    fieldSettings.find('#field-dependon-value-setting').val($(this).attr('dependon_value'));
                    current_field_name = $(this).attr('name');
                    
                    
                }
                
            });
            
                    
                    $('#field-dependon-setting').empty();
                    $('#field-parent-setting').empty();
                    var templist = [];
                    $('#field-parent-setting').append('<option value="" display_value="" selected>Select Parent</option>');
                    $('#field-dependon-setting').append('<option value="" display_value="" selected>Select Depend on</option>');
                    $('#form-preview').find('select,input[type="radio"],input[type="number"]').each(function(){

                        if($(this).attr('name') != undefined && current_field_name != $(this).attr('name')){
                            var field_name = $(this).attr('name');
                            var field_id = $(this).attr('id');
                            var skip = $(this).attr('rel');
                            var type = $(this).attr('type');
                            var selected_p = selected_parent;
                            var selected_d = selected_dependon;
                            if($.inArray(field_name,templist)=='-1')
                            {
                                templist.push(field_name);
                                if(field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
                                {

                                    field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g,"");
                                    if(type=='radio')
                                    {
                                        field_id = field_name;
                                    }
                                    if(selected_p==field_id)
                                    {
                                        $('#field-parent-setting').append('<option value="'+field_id+'" selected>'+field_name+'</option>');
                                    }
                                    else{
                                        $('#field-parent-setting').append('<option value="'+field_id+'" >'+field_name+'</option>');
                                    }
                                    
                                    if(selected_d==field_id)
                                    {
                                        $('#field-dependon-setting').append('<option value="'+field_id+'" selected>'+field_name+'</option>');
                                    }
                                    else{
                                        $('#field-dependon-setting').append('<option value="'+field_id+'" >'+field_name+'</option>');
                                    }
                                }
                            }
                        }
                    });
                    
                    
                    
                    //Checkin and Checkout related functionality
                    $('.actionbefore').hide();
                    
                    $('#field-action-required-setting').empty();
                    $('#field-action-required-setting').append('<option value="" display_value="" selected>Select Action</option>');
                    $('#form-preview').find('.taketimeclass').each(function(){
                   
                            
                            var inputFieldTime = $(this).find('input');
                            var field_name = inputFieldTime.attr('name');
                            var field_id = inputFieldTime.attr('id');
                            var action_before_selected='';
                            if(currentField.find('input').attr('action_before')!=undefined){
                                action_before_selected = currentField.find('input').attr('action_before');
                                $('.actionbefore').show();
                            }
                            if(currentField.find('input').attr('id')==field_id)
                            {}else{
                                if(action_before_selected == field_id)
                                {
                                    $('#field-action-required-setting').append('<option value="'+field_id+'" selected>'+field_name+'</option>');
                                }
                                else{
                                    $('#field-action-required-setting').append('<option value="'+field_id+'" >'+field_name+'</option>');
                                }
                            }
                    });
                    
                    $('.timevalidation').hide();
                    $('#field-time-validation-setting').empty();
                    $('#field-time-validation-setting').append('<option value="" display_value="" selected>Select Action</option>');
                    $('#form-preview').find('.time-field-widget').each(function(){
                            
                            
                            var inputFieldTime = $(this).find('input');
                            var field_name = inputFieldTime.attr('name');
                            var field_id = inputFieldTime.attr('id');
                            var action_before_selected='';
                            if(currentField.find('input').attr('time_validation')!=undefined){
                                action_before_selected = currentField.find('input').attr('time_validation');
                                $('.timevalidation').show();
                            }
                            if(currentField.find('input').attr('id')==field_id)
                            {}else{
                                if(action_before_selected == field_id)
                                {
                                    $('#field-time-validation-setting').append('<option value="'+field_id+'" selected>'+field_name+'</option>');
                                }
                                else{
                                    $('#field-time-validation-setting').append('<option value="'+field_id+'" >'+field_name+'</option>');
                                }
                            }
                    });
                    
                   

            if(isselectele==0){
                fieldSettings.find('#field-api-setting').parent().hide();
                
            }
            else{
                fieldSettings.find('#field-api-setting').parent().show();
                
            }

            inputSettings.css('display', 'none');
            settingsLoadingInd.css('display', 'block');
            //loop through each of the input or option fields for the current element
            //append the form to edit that element, bind events to inputs
            var element_option_exist = 0;
            currentField.find("input, option, textarea").each(function()
            {
                element_option_exist=1;
                //if ($(this).attr('field_setting') != 'undefined' && $(this).attr('field_setting') == 'main')
                //{
                    var thisInputField = $(this),
                            thisInputFieldId = thisInputField.attr('id'),
                            thisInputFieldSettings,
                            typedValue;


                    fieldType = getInputOptionParentType(thisInputField);
                    //alert(thisInputField.val());

                    if (fieldType == 'checkbox' || fieldType == 'radio' || fieldType == 'select')
                    {


                        if (fieldType == 'select') {
                            //add an "option name" field for each option/choice
                            inputSettings.append($('#select-settings-tmpl').tmpl({id: thisInputFieldId + '-setting'}));
                        } else {
                            //add an "option name" field for each option/choice
                            inputSettings.append($('#input-settings-tmpl').tmpl({id: thisInputFieldId + '-setting'}));
                        }

                        //the handle to the "option name" field we just created
                        thisInputFieldSettings = $('#' + thisInputFieldId + '-setting');

                        //populate existing values
                        var settingsInput = thisInputFieldSettings.find('input');


                        if (fieldType == 'select') {
                            if ($(this).parent().attr('field_setting') != 'undefined' && $(this).parent().attr('field_setting') == 'main')
                            {
                                thisInputFieldSettings.find("input").each(function() {

                                    if ($(this).attr('name') == 'option-title')
                                    {
                                        $(this).val(thisInputField.val());
                                    }
                                    if ($(this).attr('name') == 'option-display-value')
                                    {
                                        $(this).val(thisInputField.attr('display_value'));
                                    }
                                    if ($(this).attr('name') == 'option-parent-value')
                                    {
                                        $(this).val(thisInputField.attr('parent_value'));
                                    }
                                });
                            }
                        } else {
                            settingsInput.val(getInputOptionValue(thisInputField, fieldType));
                        }





                        //bind text change event for option
                        //changed from thisInputFieldSettings.find('input[name=option-title]');
                        bindInputOptionToFieldSettings(settingsInput, thisInputField);

                    }
                    if(fieldType=='text' || fieldType =='date' || fieldType =='number' || fieldType =='textarea' || fieldType == 'hidden')
                    {
                        $('.add-option-main').hide();
                        $('#remove-option-all').hide();
                    }
                    else
                    {
                        $('.add-option-main').show();
                        $('#remove-option-all').show();
                    }
                //}
            });

            settingsLoadingInd.css('display', 'none');
            inputSettings.css('display', 'block');

            //android_field="yes" then hide main
            if (currentField.find('input[type="hidden"]').attr('android_field') != 'undefined' && currentField.find('input[type="hidden"]').attr('android_field') == 'yes')
            {
                $('#main-settings').find('.label').hide();
                $('#main-settings').find('.name').hide();
                //$('#main-settings').find('.defaultvalue').hide();
                $('#input-settings-container').hide();
                //$('#ui-id-1').hide();
                //$('#ui-id-2').click();
            }
            else
            {
                $('#main-settings').find('.label').show();
                $('#main-settings').find('.name').show();
                //$('#main-settings').find('.defaultvalue').show();
                $('#input-settings-container').show();
                //$('#ui-id-1').show();
            }
            
            if(element_option_exist==1)
            resetValidationSettings(currentField, fieldType);
        }
        
        function getMainSettingElement(currentField){
            
        }
        /** Reset the field settings dialog for the current field **/
        function resetPageSettingsDialog(currentField)
        {
            
            
            var current_active_page = currentField.attr('id');
            //console.log($('.pageli').find('[rel="'+current_active_page+'"]').text());
            $('#title_page').val($('.pageli').find('[rel="'+current_active_page+'"]').text());
            //load next and previous dropdown here
            var next_page_id = currentField.find('.page-next').attr('page_id');
            var previous_page_id = currentField.find('.page-previous').attr('page_id');
            $('#next_page').empty();
            $('#previous_page').empty();
            $('#next_page').append('<option value="" display_value="" selected>Select Next page</option>');
            $('#previous_page').append('<option value="" display_value="" selected>Select Previous page</option>');
            
            $('.pages').find('li').each(function(){
                
                var page_title = $(this).find('a').html();
                var page_id = $(this).find('a').attr('rel');

                if(current_active_page!=page_id)
                {
                    if(next_page_id == page_id){
                        $('#next_page').append('<option value="'+page_id+'" selected>'+page_title+'</option>');
                    }
                    else{
                        $('#next_page').append('<option value="'+page_id+'" >'+page_title+'</option>');
                    }
                    if(previous_page_id == page_id)
                    {
                        $('#previous_page').append('<option value="'+page_id+'" selected>'+page_title+'</option>');
                    }
                    else{
                        $('#previous_page').append('<option value="'+page_id+'">'+page_title+'</option>');
                    }
                }
            });

            if(currentField.find('.page-skip').attr('skip')=='yes'){
                $('#skip_page').attr('checked',true);
                currentField.find('.page-skip').show();
            }
            else{
                $('#skip_page').attr('checked',false);
                currentField.find('.page-skip').hide();
            }
            
            if(next_page_id=='')
            {
                currentField.find('.page-next').hide();
            }
            else{
                currentField.find('.page-next').show();
            }
            
            if(previous_page_id=='')
            {
                currentField.find('.page-previous').hide();
            }
            else{
                currentField.find('.page-previous').show();
            }
            
            //Get all elements of Select and Radio boxes
            $('#element_value').empty();
            $('#page_elements').empty();
            var skip_list=[];
            $('#page_elements').append('<option value="" display_value="" selected>Select Element</option>');
            currentField.find('select,input[type="radio"],input[type="number"]').each(function(){
                if($(this).attr('type') != undefined && $(this).attr('type')=='radio')
                {
                    if($.inArray($(this).attr('name'),skip_list) == -1){
                        $('#page_elements').append('<option element_type="radio" value="'+$(this).attr('name')+'">'+$(this).attr('name')+'</option>');
                        skip_list.push($(this).attr('name'));
                    }
                }
                else if($(this).attr('type') != undefined && $(this).attr('type')=='number')
                {
                    if($.inArray($(this).attr('name'),skip_list) == -1){
                        $('#page_elements').append('<option element_type="number" value="'+$(this).attr('id')+'">'+$(this).attr('name')+'</option>');
                        skip_list.push($(this).attr('name'));
                    }
                }
                else{
                    $('#page_elements').append('<option element_type="select" value="'+$(this).attr('id')+'">'+$(this).attr('name')+'</option>');
                }
            });
            
            
        }




        /** Hide the field settings dialog **/
        function hideFieldSettings()
        {
            fieldSettings.animate({opacity: 0}, 'fast', function()
            {
                $(this).css({display: 'none'});
            });
        }
        /** Hide the field settings dialog **/
        function hidePageSettings()
        {
            pageSettings.animate({opacity: 0}, 'fast', function()
            {
                $(this).css({display: 'none'});
            });
        }




        /** Position the field settings dialog next to the current field **/
        function positionFieldSettings(less12)
        {
            //Workaround b/c jQuery UI position not functioning as expected in webkit browsers
            fieldSettings.css('display', 'block').offset({top: hoverField.offset().top + 6});
            fieldSettings.css('float', 'right');
            fieldSettings.css('right', '12px');
            fieldSettings.css('background-color', '#dfeffc');
        }
        /** Position the field settings dialog next to the current field **/
        function positionPageSettings(less12)
        {
            //Workaround b/c jQuery UI position not functioning as expected in webkit browsers
            pageSettings.css('display', 'block').offset({top: hoverPage.offset().top + 6});
            pageSettings.css('float', 'right');
            pageSettings.css('right', '12px');
            pageSettings.css('background-color', '#dfeffc');
        }




        /** This adds an element to a select field, radio group, or checkbox group **/
        function addFieldOption()
        {
        	var active_loop = getActiveLoop();
        	var subtable_id = '';
        	if(active_loop)
    		{
        		subtable_id = getInputIdForTable();
        		
    		}
            if (hoverField.find('select').is('select')) {
                

                if(hoverField.find('select option:last').attr('id') == undefined)
                {
                    fieldName = hoverField.find('select').attr('id');
                    appendToParent = hoverField.find('select'),
                    fieldType = 'select';
                    currentNumOptions = 0;
                    newOptionNum = currentNumOptions + 1;
                }
                else{
                    var currentFieldId = hoverField.attr('id'),
                            currentNumOptions = hoverField.find('select option:last').attr('id'),
                            tmpInputObj = hoverField.find('select option:first'),
                            fieldName = tmpInputObj.is('input') ? tmpInputObj.attr('name') : tmpInputObj.parent().attr('id'),
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
                }
                id = fieldName.replace('[]', '') + '-' + newOptionNum;
                option = {
                    fieldType: fieldType + '-option',
                    type: fieldType,
                    id: id,
                    option: 'Option ' + newOptionNum,
                    subtable_id:subtable_id
                };

            } 
            else
            {
                 if(hoverField.find('input:last').attr('id') == undefined)
                {
                    var fieldtypeof = hoverField.hasClass('radio-group');
                    if(fieldtypeof)
                    {
                        fieldType = 'radio';
                    }
                    else
                    {
                        fieldType = 'checkbox';
                    }
                    var fieldNamesplid = hoverField.attr('id')
                    fieldName = fieldNamesplid.split('-')[0];
                    appendToParent = hoverField,
                    currentNumOptions = 0;
                    newOptionNum = currentNumOptions + 1;
                }
                else{
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
                }
                id = fieldName.replace('[]', '') + '-' + newOptionNum;
               
                option = {
                    fieldType: fieldType + '-option',
                    type: fieldType,
                    name: fieldName,
                    id: id,
                    option: 'Option ' + newOptionNum,
                    subtable_id:subtable_id
                };
            }

            

            

            //append to tmpInputObj rather than hover field to handle select field case
            $('#form-elements-tmpl').tmpl(option).appendTo(appendToParent);
            
            if (fieldType == 'select')
            {
                $('#select-settings-tmpl').tmpl({id: id + '-setting'}).appendTo(inputSettings);
            }
            else {
                $('#input-settings-tmpl').tmpl({id: id + '-setting'}).appendTo(inputSettings);
            }

            //get handles to the field settings input and input/option that we just created
            settingsInput = $('#' + id + '-setting').find('input');
            thisInputField = $('#' + id);


            //set the newly added field settings input to the value of the new input/option
            thisInputFieldCurrentValue = getInputOptionValue(thisInputField, fieldType);
            settingsInput.val(thisInputFieldCurrentValue);
            $('#' + id + '-setting').find('.option-parent-value').attr('value','');
            //$('.option-parent-value').val('');
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
                        cachedOption[1] = thisInputField.attr('value', typedValue).next('label').html(typedValue);
                    }
                }
                else
                {
                    typedValue = $(this).val();
                    if ($(this).attr('name') == 'option-title')
                    {
                        thisInputField.html(typedValue).attr('value', typedValue);
                        //thisInputField.html(typedValue).attr('display_value', typedValue);
                    }
                    if ($(this).attr('name') == 'option-display-value')
                    {
                        thisInputField.html(typedValue).attr('display_value', typedValue);
                        if(thisInputField.is('option:first-child')){
                            thisInputField.parent().prev().html(typedValue);
                        }
                    }
                    if ($(this).attr('name') == 'option-parent-value')
                    {
                        thisInputField.attr('parent_value', typedValue);
                    }
                    //This is a select

                    //thisInputField.html(typedValue).attr('value', typedValue);

                    

                }
            });
        }




        /** Get the value of a specific option in a select, radio, checkbox group**/
        function getInputOptionValue(thisInputField, fieldType)
        {
                return (fieldType != 'select') ? thisInputField.next('label').html() : thisInputField.html();
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
                        hidden: ['required','caption'],
                        text: ['required', 'pattern', 'minlength', 'maxlength', 'exactlength','editable','save_last_activity','text_validation'],
                        number: ['required', 'min', 'max','editable', 'minlength', 'maxlength', 'exactlength','save_last_activity'],
                        email: ['required','editable'],
                        url: ['required','editable'],
                        select: ['required','editable','multiple','save_last_activity'],
                        textarea: ['required','editable','save_last_activity'],
                        radio: ['required','save_last_activity'],
                        checkbox: ['required','save_last_activity'],
                        password: ['required'],
                        date: ['required','editable','save_last_activity'],
                        time: ['required','editable','save_last_activity'],
                        range: ['required', 'min', 'max','editable','save_last_activity'],
                        file: ['required']
                    };

            fieldRules = rules[fieldType];

            //reset all the values to blank or attribute value on currentField, unbind previous field, bind event handler for current field
            validationSettings.find('input,select').each(function()
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
            $('#val-editable').show();

            //loop through the rules for the field type and re-enable them (set back to default layout)
            for (var i = 0; i < fieldRules.length; i++)
            {
                
                
                var thisValField = validationSettings.find('#val-' + fieldRules[i]).css('display', '').find('input');
                thisValField.val(currentField.find('input:first').attr(fieldRules[i]));
                
                if (fieldRules[i] == 'required')
                {
                	
                	$('#val-required').show();
                	if(currentField.hasClass('hidden-field-widget')){
                		$('#val-required').hide();
                		currentField.find('input').removeAttr('required');
                		thisValField.attr('checked', false);
                		
                	}
                    //if the required attribute is missing, the user has made this field optional, remove default required state
                	else if (!isRequired(currentField))
                        thisValField.attr('checked', false);
                    
                    
                }
                else if (fieldRules[i] == 'text_validation')
                {
                    var thisValField = validationSettings.find('#val-' + fieldRules[i]).css('display', '').find('select');
                    thisValField.val(currentField.find('input:first').attr(fieldRules[i]));
                    //console.log(thisValField);
                    $('#val-text_validation').show();
                    if(currentField.hasClass('hidden-field-widget')){
                            $('#val-text_validation').hide();
                            currentField.find('input').removeAttr('text_validation');
                            thisValField.attr('checked', false);

                    }
                }
                else if (fieldRules[i] == 'save_last_activity')
                {
                    
                    $('#val-save_last_activity').show();
                    if(currentField.hasClass('hidden-field-widget')){
                            $('#val-save_last_activity').hide();
                            currentField.find('input').removeAttr('save_last_activity');
                            thisValField.attr('checked', false);

                    }
                    else{
                        if (isSaveLastActivity(currentField))
                        {
                            thisValField.attr('checked', true);
                        }
                        else{
                            thisValField.attr('checked', false);
                        }
                    }
                }
                else if (fieldRules[i] == 'editable')
                {
                    if(currentField.hasClass('hidden-field-widget'))
                    {
                        $('#val-editable').hide();
                    }
                    else{
                        //if the required attribute is missing, the user has made this field optional, remove default required state
                        if (isEditable(currentField))
                        {
                            thisValField.attr('checked', true);
                        }
                        else{
                            thisValField.attr('checked', false);
                        }
                    }
                        
                }
                else if (fieldRules[i] == 'filter')
                {
                    //if the required attribute is missing, the user has made this field optional, remove default required state
                    if (!isFilter(currentField))
                        thisValField.attr('checked', false);
                }
                else if (fieldRules[i] == 'multiple')
                {
                    //if the required attribute is missing, the user has made this field optional, remove default required state
                    if (isMultiple(currentField))
                        thisValField.attr('checked', true);
                    else
                        thisValField.attr('checked', false);
                }
                else if (fieldRules[i] == 'caption')
                {
                    //if($('#yourID').css('display') == 'none')
                    if(currentField.find('.link-field').parent().css('display') == 'none')
                    {
                        $('#val-caption input').attr('checked', false);
                        $('#val-caption').css('display', '');
                    }
                }

            }

        }




        /** Update the validation rules on a specific field once changes are made in the field settings dialog**/
        function updateValidationRules(currentField, validationField, validationRule)
        {
            var currentInput = currentField.find('input:first, select, textarea');

            validationRule = validationRule.slice(4);

            if (validationRule == 'pattern' || validationRule == 'min' || validationRule == 'max' || validationRule == 'minlength' || validationRule == 'maxlength' || validationRule == 'exactlength')
            {
                currentInput.attr(validationRule, validationField.val());
            }
            else if (validationRule == 'required')
            {
                //alert(currentField.filter('[field_setting="main"]').attr('name'));
                
                currentField.find('input,select,textarea').each(function(){
                    
                    if($(this).attr('field_setting') != undefined){
                        //alert($(this).attr('field_setting'));
                        currentInput = $(this);
                        return false;
                    }
                });
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
            else if (validationRule == 'text_validation')
            {
                currentField.find('input').each(function(){
                    if($(this).attr('field_setting') != undefined){
                        currentInput = $(this);
                        return false;
                    }
                });
                var valui = validationField.val();
                currentInput.attr('text_validation', valui);
            }
            else if (validationRule == 'save_last_activity')
            {
                
                var type = currentInput.attr('type');

                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').addClass('save_last_activity');
                    else
                        currentInput.attr('save_last_activity', validationRule);
                }
                else
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').removeClass('save_last_activity');
                    else
                        currentInput.removeAttr(validationRule)
                }
                //alert(currentField.filter('[field_setting="main"]').attr('name'));
                
                
            }
            else if (validationRule == 'editable')
            {
                var type = currentInput.attr('type');

                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').addClass('editable');
                    else
                        currentInput.attr('editable', validationRule);
                }
                else
                {
                    if (type == 'checkbox' || type == 'radio')
                        currentInput.parents('.field:first').removeClass('editable');
                    else
                        currentInput.removeAttr(validationRule)
                }
            }
            else if (validationRule == 'multiple')
            {
                var type = currentInput.attr('type');

                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                        currentInput.attr('multiple', validationRule);
                }
                else
                {
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
            else if (validationRule == 'caption')
            {
                //required attribute for checkbox group and radio group will go on the parent div since default html5 required behavior validates each individual field rather than the group
                if (validationField.attr('checked'))
                {
                    currentInput.parent().show();
                    currentInput.parent().next().show();
                    //currentInput.next().next().show();
                }
                else
                {
                    currentInput.parent().hide();
                    currentInput.next().hide();
                }
            }
        }




        /** Determine if a particular field has the required attribute set**/
        function isRequired(field)
        {
            var attr = field.find('input:first, textarea, select').attr('required');
            return field.hasClass('required') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }
        
        function isEditable(field)
        {
            
            var attr = field.find('input:first, textarea, select').attr('editable');
            return field.hasClass('editable') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }
        function isSaveLastActivity(field)
        {
            
            var attr = field.find('input:first, textarea, select').attr('save_last_activity');
            return field.hasClass('save_last_activity') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }
        function isMultiple(field)
        {
            
            var attr = field.find('select').attr('multiple');
            return field.hasClass('multiple') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }
        /** Determine if a particular field has the required attribute set**/
        function isFilter(field)
        {
            var attr = field.find('input:first, textarea, select').attr('filter');
            return field.hasClass('filter') || ((attr !== undefined) && (attr !== false)); //since some browsers (chrome) will strip require=required to just required
        }



        /** Change the form theme **/
        function setTheme(newTheme)
        {
            theme = newTheme;
            $('#style').attr('href', base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/css/bootstrap.min.css');
            $('#style').attr('href', base_url + 'assets/form_builder/application/form_resources/themes/' + theme + '/css/common.css');
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
                },
                stop: function(event, ui)
                {
                    var $this = $(this), sizeClass = setFieldSizeClass($this, ui, columnWidth);

                    //because jquery explictly sets height on resize which breaks the border around the field container
                    $this.css('height', '');
                }
            });
        }





        function getMargin()
        {
            return  .04 * (columnWidth * 4);
        }




        /** Format the validation rules for a particular field**/
        function getValidationRules(field)
        {
            var input = field.find('input:first, select, textarea'),
                    type = getType(field),
                    rules = {
                        'type': type,
                        'pattern': input.attr('pattern'),
                        'minlength': input.attr('minlength'),
                        'maxlength': input.attr('maxlength'),
                        'exactlength': input.attr('exactlength'),
                        'min': input.attr('min'),
                        'max': input.attr('max'),
                        'required': (type == 'checkbox' || type == 'radio') ? field.hasClass('required') : input.attr('required'),
                        'editable': (type == 'checkbox' || type == 'radio') ? field.hasClass('editable') : input.attr('editable')
                    },
            thisFieldRules = {};

            $.each(rules, function(name, value)
            {
                if (rules[name] != undefined && rules[name] != '')
                    thisFieldRules[name] = rules[name];

            });


            return thisFieldRules;

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
                },
                buttons: {
                    "Apply": function()
                    {
                        var input, val;

                        input = $(this).find('input, select');
                        val = input.is('input') ? input.val() : input.find(':selected').val();

                        if(val=='')
                        {
                            val = $(this).find('a').html();
                        }
                        
                        
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
        function promptForSubmit(form, action)
        {
            
            form.dialog({
                resizable: false,
                modal: true,
                open: function() {
                },
                buttons: {
                    "Delete": function()
                    {
                        if($(this).attr('id')=='set-form-submit'){
                            $('#form-submit').remove();
                        }else{
                            $('#form-save').remove();
                            $('#form-submitedit').remove();
                        }

                        $(this).dialog("close");
                    },
                    "Apply": function()
                    {
                        
                        var input, val;

                        input = $(this).find('input, select');
                        val = input.is('input') ? input.val() : input.find(':selected').val();
                       
                        if(val=='')
                        {
                            val = $(this).find('span').html();
                        }
                        //Set tracking Start/Stop
                        var tracking_stat = $(this).find('#form_submit_tracking').val();
                        $('.edit-submit-sep').attr('tracking_status',tracking_stat);
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
function submitform(){
    return false;
}
