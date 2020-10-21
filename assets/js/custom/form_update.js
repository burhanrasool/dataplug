//image change when copy the form
if($('#form-title img.formobile').attr('src') != undefined){
    var old_name_mobile = $('#form-title img.formobile').attr('src');
    var new_name_all = 'formicon_'+Settings.form_id+'.png';
    if(old_name_mobile != new_name_all){

        var old_name_web = $('#form-title img.forweb').attr('src');
        var new_name_web = old_name_web.replace(old_name_mobile,'../'+Settings.app_id+'/'+new_name_all);
        $('#form-title img.formobile').attr('src',new_name_all);
        $('#form-title img.forweb').attr('src',new_name_web);
    }
}
if($('#loader_div').hasClass('loader') == false){
    var loader_string ='<div id="loader_div" class="loader" style="background:#fff;opacity: 0.79;position:fixed;top:0px;left:0px; height:100%; width:100%;color:#333; text-align: center;padding-top:100px;display:block;z-index:10;"><img src="loading.gif" class="center-block formobile" height="200" /><br /><h3 id="waitText" style="color:#333;font-size:14pt;">Be patient, we are processing your request.</h3></div>';
    var fbhtml=$('#form-builder').html();

    $('#form-builder').html(loader_string+fbhtml);
    //$('#form-builder').append(loader_string);
}
 $('#loader_div').hide();
//adding icon of submit and save button of old application
if($('#form-submit').find('span').length < 1){
    var submit_text = $('#form-submit').find('a').html();
    $('#form-submit').find('a').html('<i class="glyphicon glyphicon-send pull-right"></i><span>'+submit_text+'</span>');

}
if($('#form-save').find('span').length < 1){
    var save_text = $('#form-save').find('a').html();
    $('#form-save').find('a').html('<i class="glyphicon glyphicon-floppy-disk pull-right"></i><span>'+save_text+'</span>');

}
if($('#form-submitedit').find('span').length < 1){
    var submitedit_text = $('#form-submitedit').find('a').html();
    $('#form-submitedit').find('a').html('<i class="glyphicon glyphicon-send pull-right"></i><span>'+submitedit_text+'</span>');

}
if($('#form-title img.formobile').attr('src_mobile')!=undefined){
    var web_url = $('#form-title img.formobile').attr('src_web');
    $('#form-title img.formobile').attr('src',web_url);
}
    
    
$(".field").sortable({
            tolerance: 'pointer',
            cursor: 'move',
            forcePlaceholderSize: true,
            dropOnEmpty: true,
            //connectWith: 'ol.word-list',
            placeholder: "ui-state-highlight"
        });

        // Words tabs
        var $tabs = $("#form-preview").tabs();
        // Make tab names dropable
        var $tab_items = $(".tabs li", $tabs).droppable({
            accept: ".field",
            //hoverClass: "ui-state-hover",
            tolerance: 'pointer',
            drop: function(event, ui) {
                var item = $(ui.draggable);
                var drop_to_item_id = 'title-' + $(this).attr('id');
                var tab_id = $('#' + drop_to_item_id).attr('rel');
                ui.draggable.remove();
                $('#' + tab_id).prepend(item.context.outerHTML);
                $('#' + ui.draggable.attr('id')).attr('style', 'left:auto;right:auto;position:relative;opacity:1')
            }
        });





        $('ul.tabs a').each(function() {
            active_tab = $(this).attr('rel');
            if ($(this).hasClass('active'))
            {
                $("#" + active_tab).show();
            }
            else
            {
                $("#" + active_tab).hide();
            }
        });

        $('#val-minlength input,#val-exactlength input,#val-maxlength input,#val-min input, #val-max input').keydown(function(event) {
            // Allow special chars + arrows 
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 
                || event.keyCode == 27 || event.keyCode == 13 
                || (event.keyCode == 65 && event.ctrlKey === true) 
                || (event.keyCode >= 35 && event.keyCode <= 39)){
                    return;
            }else {
                // If it's not a number stop the keypress
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                    event.preventDefault(); 
                }   
            }
        });
        

        $('ul.tabs a').live('click', function(e) {
            $('#add_after_widget').val('');
            $('a').removeClass('active');
            // Make the old tab inactive.
            $('ul.tabs a').each(function() {
                active_tab = $(this).attr('rel');
                $("#" + active_tab).hide();
            });

            // Make the tab active.
            $("#" + $(this).attr('id')).addClass('active');
            $("#" + $(this).attr('rel')).show();

            // Prevent the anchor's default click action
            e.preventDefault();
        });
        
        var $page_items = $(".pages li", $tabs).droppable({
            accept: ".field",
            //hoverClass: "ui-state-hover",
            tolerance: 'pointer',
            drop: function(event, ui) {
                var item = $(ui.draggable);
                var drop_to_item_id = 'title-' + $(this).attr('id');
                var page_id = $('#' + drop_to_item_id).attr('rel');
                ui.draggable.remove();
                $('#' + page_id).prepend(item.context.outerHTML);
                $('#' + ui.draggable.attr('id')).attr('style', 'left:auto;right:auto;position:relative;opacity:1')
            }
        });
        $('ul.pages a').each(function() {
            active_page = $(this).attr('rel');
            if ($(this).hasClass('active'))
            {
                $("#" + active_page).show();
            }
            else
            {
                $("#" + active_page).hide();
            }
        });
        $('ul.pages a').live('click', function(e) {
            $('#add_after_widget').val('');
            $('a').removeClass('active');
            // Make the old tab inactive.
            $('ul.pages a').each(function() {
                active_page = $(this).attr('rel');
                $("#" + active_page).hide();
            });
            // Make the tab active.
            $("#" + $(this).attr('id')).addClass('active');
            $("#" + $(this).attr('rel')).show();
            // Prevent the anchor's default click action
            e.preventDefault();
        });

        $('#remove-option-all').click(function(e) {
            $('.remove-option').trigger('click');
        });
        
        var ci_base_url = Settings.base_url;
        $(document).ready(function() {



            
          //add new field for check that application must require location
            if ($('#location_required').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="location_required" value="'+Settings.location_required+'" />');
            }
            else{
                $('#location_required').val(Settings.location_required);
            }

            if ($('#save_draft_edit_id').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="save_draft_edit_id" />');
            }
            
            
             //if form_id hidden missing
            if ($('#form_id').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="form_id" name="form_id" value="'+Settings.form_id+'" />');
            }
            else{
            	$('#form_id').val(Settings.form_id);
            }
            
            if ($('#row_key').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="row_key" name="row_key" value="" column_name="'+Settings.row_key+'" />');
            }
            else{
                $('#row_key').attr('column_name',Settings.row_key);
            }            

            if ($('#activity_duration_in_seconds').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="activity_duration_in_seconds" name="activity_duration_in_seconds" value="0" />');
            }

            
        	//if security hidden missing
            if ($('#security_key').val() == undefined)
            {
                $('#form_id').after('<input type="hidden" name="security_key" id="security_key" value="'+Settings.security_key+'" />');
            }
            else{
            	$('#security_key').val(Settings.security_key);
            }
            
            //if form operation hidden missing
            if ($('#form_operation').val() == undefined)
            {
            	$('#form_id').after('<input type="hidden" id="form_operation" value="formsubmit" />');
            }
            //if form upgrade_from_google_play hidden missing
            if ($('#upgrade_from_google_play').val() == undefined)
            {
            	$('#form_id').after('<input type="hidden" id="upgrade_from_google_play" value="'+Settings.upgrade_from_google_play+'" />');
            }
            else{
            	$('#upgrade_from_google_play').val(Settings.upgrade_from_google_play);
            }
            //if form operation hidden missing
            if ($('#caption_sequence').val() == undefined)
            {
                $('#form_id').after('<input type="hidden" id="caption_sequence" name="caption_sequence" value="" />');
            }
            //if form operation hidden missing
            if ($('.cameraclass').length > 0){
                if ($('#takepic').val() == undefined)
                {
                    $('#form_id').after('<input type="hidden" value="" id="takepic" name="takepic">');
                }
            }
            if ($('#form_version_date').val() == undefined)
            {
                $('#form-preview').append('<input type="hidden" id="form_version_date" name="form_version_date" value="" />');
                var fullDate = new Date();
                var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
                var localdate= fullDate.getFullYear()+ "-" + twoDigitMonth + "-" + fullDate.getDate() + ' ' + fullDate.getHours() + ':' + fullDate.getMinutes()+':'+fullDate.getSeconds();
                $('#form_version_date').val(localdate);
            }
            

            
            
            $('#filter').empty();
            var templist = [];
            $('#form-preview').find('input, textarea, select').each(function() {

                if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
                {
                    if ($(this).attr('name') != undefined) {
                        var field_name = $(this).attr('name');
                        field_name = field_name.replace('[]', '');
                        var skip = $(this).attr('rel');
                        var type = $(this).attr('type');
                        var subtable_id = $(this).attr('subtable_id');
                        var selected = '';
                        if ($.inArray(field_name, templist) == '-1')
                        {
                            templist.push(field_name);
                            if (field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit' && subtable_id == '')
                            {
                                if (selected == field_name)
                                {
                                    field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                    $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name + '</option>');
                                }
                                else {
                                    field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
                                    $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name + '</option>');
                                }
                            }
                        }
                    }
                }
            });


//For new design Implementation - start
//            if(!$('#form-title').hasClass('navbar'))
//            {
//                var old_title = $('#form-title').find('h2').html();
//                $('#form-title').remove();
//                $("#add-form-title li").trigger('click');
//                $('#form-title').find('.navbar-brand').html(old_title);
//            }
//            if(!$('#form-submit').hasClass('form-group'))
//            {
//                $('#form-submit').remove();
//                $("#add-form-submit li").trigger('click');
//            }
//            if(!$('#form-save').hasClass('form-group'))
//            {
//                $('#form-save').remove();
//                $('#form-edit-submit').remove();
//                $("#add-form-save li").trigger('click');
//            }
            
            
            //Script for converting old html to new html - Start
            //add outer body of form
//            if(!$('#form-preview').parent().hasClass('form-hd')){
//                $( "#form-preview" ).wrap( "<div class='form-hd'></div>" );
//                $( ".form-hd" ).wrap( "<div class='col-lg-12'></div>" );
//                $( ".col-lg-12" ).wrap( "<div class='row'></div>" );
//            }
            
            
            
//            $('.cameraclass').each(function() {
//                
//                if(!$(this).hasClass('form-group'))
//                {
//                    var after_widget_id = $(this).prev().attr('id');
//                    $('#add_after_widget').val(after_widget_id);
//                     setTimeout(function() {
//                        $("#camera-field").trigger('click');
//                    }, 1000);
//                    $(this).remove();
//                        
//                }
//            });
//            $('.field').each(function() {
//                if(!$(this).hasClass('form-group'))
//                {
//                    if($(this).find('input').attr('type')=='text' && $(this).find('input').attr('field_setting')=='main')
//                    {
//                        $(this).addClass('form-group');
//                        $(this).find('input[type="text"]').addClass('form-control');
//                    }
//                    else if($(this).find('input').attr('type')=='number' && $(this).find('input').attr('field_setting')=='main')
//                    {
//                        $(this).addClass('form-group');
//                        $(this).find('input[type="number"]').addClass('form-control');
//                    }
//                    if($(this).find('select').attr('field_setting')=='main')
//                    {
//                        
//                        if($(this).find('select').parent().hasClass('selector'))
//                        {
//                            $(this).addClass('form-group');
//                            $(this).addClass('select_widget');
//                            $(this).find('select').parent().addClass('form-control');
//                            $(this).find('select').parent().addClass('fake-dropdown');
//                            $(this).find('select').parent().removeClass('selector');
//                            $(this).find('select').parent().attr('id','');
//                            
//                            //$(this).find('select').unwrap();
//                        }
//                    }
//                    //$(this).addClass('form-group');
//                    //$(this).find('input[type="text"]').addClass('form-control');
//                    //$(this).find('input[type="number"]').addClass('form-control');
//                }
//               
//            });
//            $('.field').each(function() {
//                
//                if(!$(this).hasClass('cameraclass')){
//                    if($(this).find('select').parent().hasClass('fake-dropdown'))
//                    {
//                        
//                        $(this).find('select').parent().addClass('selector');
//                        //alert($(this).find('select').parent().find('span'));
//                        if($(this).find('select').parent().find('span').length < 1)
//                        {
//                            var spanVal = $(this).find('select').find('option:first').html();
//                            $(this).find('select').before('<span>'+spanVal+'</span>');
//                        }
//                        $(this).find('select').attr('style','');
//                    }
//                }
//            });
//            $('.checkbox-group').each(function() {
//                if(!$(this).hasClass('form-group'))
//                {
//                    $(this).addClass('form-group');
//                    $(this).find('.option').each(function() {
//                        var pinput = $(this).find('input');
//
//                        if ( pinput.parent().is( "span" ) ) {
//                            pinput.unwrap();
//                        }
//                        if ( pinput.parent().is( "div" ) ) {
//                            pinput.unwrap();
//                        }
//                        pinput.addClass('css-checkbox');
//                        pinput.parent().addClass('checkbox');
//                        pinput.parent().removeClass('clearfix');
//                        var spanval = pinput.next().html();
//                        var spanfor = pinput.attr('id');
//                        pinput.next().remove();
//                        pinput.after('<label class="css-label" for="'+spanfor+'">'+spanval+'</label>');
//                    });
//                }
//            });
            
//            $('.radio-group').each(function() {
//                if(!$(this).hasClass('form-group'))
//                {
//                    $(this).addClass('form-group');
//                    $(this).find('.option').each(function() {
//                        var pinput = $(this).find('input');
//
//                        if ( pinput.parent().is( "span" ) ) {
//                            pinput.unwrap();
//                        }
//                        if ( pinput.parent().is( "div" ) ) {
//                            pinput.unwrap();
//                        }
//                        pinput.addClass('css-checkbox');
//                        pinput.parent().addClass('radio');
//                        pinput.parent().removeClass('clearfix');
//                        var spanval = pinput.next().html();
//                        var spanfor = pinput.attr('id');
//                        pinput.next().remove();
//                        pinput.after('<label class="css-label" for="'+spanfor+'">'+spanval+'</label>');
//                    });
//                }
//            });
            
            //Script for converting old html to new html - End
            


            setTimeout(function() {
                $("#add-form-title li").trigger('click');
            }, 500);
            setTimeout(function() {
                if($('#form-preview').find('.field').length == 0){
                    $('#add-form-submit li').trigger('click');
                }
            }, 500);
            setTimeout(function() {
                $("#screen_size").trigger('change');
            }, 500);


            $('.selector select').live('change',function() {
                
                var curdiv = $(this).attr('id');
                var display_value = $('#' + curdiv + ' option:selected').attr('display_value');
                if (display_value == '')
                {
                    display_value = $('#' + curdiv + ' option:selected').attr('value');
                }
                $('#' + curdiv).prev().html(display_value);
            });
            $('#view_id').change(function() {
                $("#app_view").submit();
            });
            $('#screen_size').change(function() {
                var screen_size = $('#screen_size').val();
                var app_id = Settings.app_id;
                
                    $.post(Settings.base_url+'form/stateviewbuilder',
                            {
                                screen_size: screen_size,
                                app_id: app_id,
                                success: function(response) {
                                }
                            });
                
            });
//            $('#form-preview').find('select').each(function () {
//                    if ($(this).attr('parent_id') != undefined && $(this).attr('parent_id') != '') {
//                        var element_id = $(this).attr('id');
//                        if ($('#' + element_id).length > 0) {
//                            $('#' + element_id).empty();
//                        }
//                    }
//            });
            $('#saveForm ,#create_application').click(function(e) {
                e.preventDefault();
                loading_image();

                
                if($('#pages-header') != undefined){
                    $('ul.pages li:first a').trigger('click');;
                }
                if($('#tabs-header') != undefined){
                    $('ul.tabs li:first a').trigger('click');;
                }
                
                                
                $('#form-preview').find('[field_setting="main"]').each(function () {
                	if($(this).is('select')){
	                    var element_id = $(this).attr('id');
	                    if ($(this).attr('parent_id') != undefined && $(this).attr('parent_id') != '') {
	                        //var is_api_used = $(this).attr('api_url');
	                        if($('#' + element_id).text().trim().length >0)
	                        {
	                            
	                            var new_select = '<select id="' + element_id + '_temp" style="display:none">';
	
	                            $('#' + element_id).find('option').each(function () {
	                                new_select += '<option  id="' + $(this).attr('id') + '" parent_value="' + $(this).attr('parent_value') + '" value="' + $(this).attr('value') + '" display_value="' + $(this).attr('display_value') + '">' + $(this).attr('display_value') + '</option>';
	                            });
	                            new_select += '</select>';
	
	                            $('#' + element_id + '_temp').remove();
	                            $('#' + element_id).parent().append(new_select);
	                            $('#' + element_id + '_temp').hide();
	                            //if(is_api_used.length > 0)
	                            $('#' + element_id).empty();
	                        }
	
	                    }
	                    else{
	                        if ($('#' + element_id + '_temp').length > 0) {
	                            $('#' + element_id + '_temp').remove();
	                        }
	                    }
                    }
                });
                var fullDate = new Date();
                var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
                var localdate= fullDate.getFullYear()+ "-" + twoDigitMonth + "-" + fullDate.getDate() + ' ' + fullDate.getHours() + ':' + fullDate.getMinutes()+':'+fullDate.getSeconds();
                $('#form_version_date').val(localdate);

                //Change app and form icon path according to web and mobile
                if($('#form-title img.formobile').attr('src_mobile')==undefined){
                    var web_url = $('#form-title img.forweb').attr('src');
                    var mob_url = $('#form-title img.formobile').attr('src');
                    $('#form-title img.formobile').attr('src_web',web_url);
                    $('#form-title img.formobile').attr('src_mobile',mob_url);
                }
                if($('#form-title img.formobile').attr('src_mobile')!=undefined){
                    var mob_url = $('#form-title img.formobile').attr('src_mobile');
                    $('#form-title img.formobile').attr('src',mob_url);
                }
                $('#loader_div').show();
                var data = $('#form-builder').html();

                $('#htmldesc').val(data);
                $("#form_edit").submit();
                return false;
            });

            $('#create_application').click(function(e) {
                $.ajax(Settings.base_url+'app/calculatetimebuild',
                        {
                            success: function(response) {
                                var estimated_time = response;
                                $.colorbox({
                                    innerWidth: 485,
                                    innerHeight: 200,
                                    escKey: false,
                                    overlayClose: false,
                                    html: '<div class="cboxLoadedContent"><div class="inner-wrap"><h2 style="text-transform:capitalize;margin-top:38px;font-size:26px;">Please wait while application builds</h2></div></div><div class="J_countdown1" data-diff="' + estimated_time + '" style="text-align:center;font-size:24px;color:#ED1C24;"></div>',
                                    onLoad: function() {
                                        $('#cboxClose').remove();
                                    }
                                });

                                $('.J_countdown1').countdown({
                                    end: 'Wait a moment...',
                                    tmpl: 'Estimated Time<span style="font-size:30px;font-weight:bold;" class="minute"> %{m}:</span><span style="font-size:30px;font-weight:bold;" class="second">%{s}</span>'
                                });
                            }
                        });
            });


        if(Settings.description == '')
        {
                setTimeout(function() {
                    $("#saveForm").trigger('click');
                }, 1000);
        }


        });

        $(window).load(function() {
        	$('#overlay_loading').hide();
        	
            var filter_values = '';
            $('#filter option').each(function() {
                filter_values += $(this).text() + ',';
            })
            filter_values = filter_values.substring(0, filter_values.length - 1);
            if (filter_values != '') {
                $('#possible_filters').val(filter_values);
            } else {
                $('#possible_filters').val('version_name');
            }
        });
        
        $('.delete_form').live('click', function() {
            if (confirm('Are you sure you want to delete this Form?')) {
                var form_id = $(this).attr('form_id');
                $.ajax({
                    url: Settings.base_url+"form/delete/" + form_id,
                    type: 'POST',
                    success: function(data) {
                        window.location = Settings.base_url+'app-landing-page/'+Settings.app_id;
                    },
                });
            } else {
                return true;
            }
        });
        
        function loading_image() {
            $(function() {

                var docHeight = $(document).height();
                $("body").append('<div  id="overlay_loading" title="Please wait while new application builds">\n\
        <img  alt=""  \n\
        src="'+Settings.base_url+'assets/images/loading_map.gif">\n\
        < /div>');

                $("#overlay_loading")
                        .height(docHeight)
                        .css({
                            'opacity': 0.16,
                            'position': 'absolute',
                            'top': 0,
                            'left': 0,
                            'background-color': 'black',
                            'width': '100%',
                            'z-index': 5000
                        });
            });
        }

        function linkfield(element_id)
        {
            var selected_value = $('#linked_field-' + element_id).val();
            if (selected_value == 'custom')
            {
                $('#div_caption-' + element_id).empty();
                $('#div_caption-' + element_id).append('<input type="text" rel="norepeat" name="caption-' + element_id + '" placeholder="Enter image title here" class="form-control"/>');
            }
            else {
                $('#div_caption-' + element_id).empty();
                $('#div_caption-' + element_id).append('<input type="text" readonly rel="norepeat" name="caption-' + element_id + '" value="' + selected_value + '" class="form-control"/>');
            }
        }

        function loadselect() {
            
            var selectbox = $("#field-api-setting").attr('select_id');
            var selectbox_value = $("#"+selectbox).attr('api_url');
            
            if(selectbox_value=='')
            {
                alert("Please enter API url for getting options");
                return false;
            }
            var url_cross_domain = encodeURI($("#field-api-setting").val());
            var url_same_domain = ci_base_url + 'api/getOptions';

            $.ajax({
                url: url_same_domain,
                data: {'url_cross_domain': url_cross_domain},
                type: 'POST',
                async: true,
                dataType: 'json',
                success: function(response) {

                    var options = JSON.stringify(response);
                    var options = JSON.parse(options);
                    if (options == null)
                    {
                        alert('Error: URL not compatible.');
                        return false;
                    }

                    if (options.options.length > 0)
                    {
                        $('#' + selectbox).empty();
                    }
                    $('#' + selectbox).prev().html('Please Select');
                    $('#' + selectbox).append('<option id="' + selectbox + "-0" + '" value="" display_value="Please Select" parent_value="" data-alternative-spellings="" >Please Select</option>');
                    for (var i = 0; i < options.options.length; i++) {
                        var strval = options.options[i].value;
                        var parentval = options.options[i].parent_value;
                        var strdispval = options.options[i].display_value;
                        var option_id = selectbox + "-" + (i + 1);

                        if(strval != null && strval.length > 0){
	                        strval.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
	                        strdispval.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
	                        $('#' + selectbox).append('<option id="' + option_id + '" value="' + strval + '" display_value="' + strdispval + '" parent_value="' + parentval + '" data-alternative-spellings="' + options.options[i].id + '" >' + strdispval + '</option>');
	                    }
                        //console.log('<option id="' + option_id + '" value="' + strval + '" display_value="' + strdispval + '" parent_value="' + parentval + '" data-alternative-spellings="' + options.options[i].id + '" field_setting="main">' + strdispval + '</option>');
                    }
                    alert('New options has been added successfully.');
                },
                error: function(e) {
                    alert('Error: URL not compatible.');
                }
            });

        }

        function check_file() {
            str = document.getElementById('userfile').value.toUpperCase();
            suffix = ".png";
            suffix2 = ".PNG";
            if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                    str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                alert('File type not supported, only (.png) files allowed.');
                document.getElementById('userfile').value = '';
            }
            else
            {
                readURLapp(document.getElementById('userfile'));
            }
        }

        function readURLapp(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img_form_icon').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function getFilter(f)
        {
            var parse_array = {};
            var querystring = f.serialize();
            var querystring = querystring.split('&');
            $.each(querystring, function(i, v) {
                var field_value = v.split('=');
                if ($('[name="' + field_value[0] + '"]').attr('filter') == 'filter')
                {
                    parse_array[field_value[0]] = field_value[0];
                }

            });
            return parse_array;
        }