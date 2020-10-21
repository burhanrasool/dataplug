var is_edit_mode = false;
var intervalId=null;
$(document).ready(function () {
    console.log("Loading the form");
    //Form onload area

    if ($('#activity_duration_in_seconds').val() != undefined)
    {
        var totalSeconds = parseInt($('#activity_duration_in_seconds').val());
        intervalId = setInterval(setTime, 1000);

        function setTime()
        {
            ++totalSeconds;
            $('#activity_duration_in_seconds').val(totalSeconds);
            // if ($('#second_count').attr('id') == undefined)
            //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
            // $('#second_count').html(totalSeconds+' Seconds');
        }
    }

    if ($('#form-save').val() != undefined)
    {
        $('#form-submitedit').hide();
        $('#form-save').show();
    }

    if ($('#bs-example-navbar-collapse-1 ul>li').length == 2) {
        $('#bs-example-navbar-collapse-1 ul').append('<li><a onClick="aboutApp();"><i class="glyphicon glyphicon-info-sign"></i> About</a></li>');
    }
    console.log("Loading Last saved Activity");
    try {
        loadLastActivity();
    } catch (err) {
        console.log(err);

    }    


    try {
        

        $('.time').datebox({
            mode: "timebox",
            lockInput:true,
            overrideTimeFormat:"%k:%M",
            options:{
                lockInput:true,
                overrideTimeFormat:"%k:%M"
            }
            // ...etc...
        });


        $('.calendar').datebox({
            mode: "calbox",
            lockInput:true,
            options:{
                lockInput:true
            }
            // ...etc...
        });


    } catch (err) {
        console.log(err);

    }
    try {
        AndroidFunction.onBackPressShowAlert(false)
    } catch (err) {
        console.log(err);
    }


    // Hide all elements which are depend on other elements, 
    // Hide all elements which have parent id, 
    console.log("Hiding all elements which depend on other element");
    $('#form-preview').find('[field_setting="main"]').each(function () {
        var element_id = $(this).attr('id');
        if ($(this).is(':radio') || $(this).is(':checkbox')) {
            element_id = element_id.split('-')[0];
        }

        if ($(this).attr('dependon_id') != undefined && $(this).attr('dependon_id') != '') {
            if ($(this).parent().hasClass('edit-field-loop'))
            {
                var loop_id = $(this).attr('loop_id');
                $('#loop-' + loop_id).hide();
            } else {
                $('#' + element_id + '-container').hide();
            }


        }
        if ($(this).attr('editable') != undefined) {

            $('#' + element_id + '-container').hide();
        }
        if ($(this).is('select')) {
            if ($(this).attr('parent_id') != undefined && $(this).attr('parent_id') != '') {
                $(this).empty();
                $(this).parent().parent().hide();
            }
        }
    });

    console.log("Auto complete creation");
    $('.select_autocomplete').each(function () {
        if ($(this).attr('parent_id')!= undefined && $(this).attr('parent_id')=='') {
             $(this).selectToAutocomplete();
        }
    });

    var active_tab = '';
    $('ul.tabs a').each(function () {
        active_tab = $(this).attr('rel');
        if ($(this).hasClass('active'))
        {
            $("#" + active_tab).show();
        } else
        {
            $("#" + active_tab).hide();
        }
    });


    $('ul.tabs a').on('click', function (e) {

        $('a').removeClass('active');
        // Make the old tab inactive.
        $('ul.tabs a').each(function () {
            active_tab = $(this).attr('rel');
            $("#" + active_tab).hide();
        });

        // Make the tab active.
        $("#" + $(this).attr('id')).addClass('active');
        $("#" + $(this).attr('rel')).show();

        // Prevent the anchor's default click action
        e.preventDefault();
    });

    var active_page = '';
    $('ul.pages a').each(function () {
        active_page = $(this).attr('rel');
        if ($(this).hasClass('active'))
        {
            $("#" + active_page).show();
        } else
        {
            $("#" + active_page).hide();
        }
    });

console.log("Bubble update unsent item");
    //if unsent item contain value then add counter
    if ($('#icon_notify').length > 0) {
        try {
            var counter_unsent = AndroidFunction.GetCountOfUnSentActivities();
        } catch (err) {
            counter_unsent = 0;
            console.log(err);
        }
        //var counter_unsent = AndroidFunction.GetCountOfUnSentActivities();
        if (counter_unsent > 0) {
            $('#icon_notify').show();
            $('#icon_notify_counter').html(counter_unsent);//this counter from unsent items listing
        } else {
            $('#icon_notify').hide();
        }
    }

console.log("Time widget dependencies loop");
    $('#form-preview').find('.taketimeclass').each(function () {
        var form_id_main = $('#form_id').val();
        var inputFieldTime = $(this).find('input:first');
        var field_id = inputFieldTime.attr('id');
        //var action_before = inputFieldTime.attr('action_before');

        console.log("Time widget form_id="+form_id_main+' inputFieldTime='+inputFieldTime+' field_id='+field_id);

        if ($(this).find('input:first').attr('time_taken') == undefined || $(this).find('input:first').attr('time_taken') == 'multiple') {
            console.log("Remove old time string");
            AndroidFunction.removeTimeData('');
            AndroidFunction.removeTimeData(form_id_main + '_submited');
        }

        var recordExist = AndroidFunction.checkTimeStatus(field_id);
        //AndroidFunction.showAlertDialog(recordExist);
        var savestate = '';
        if (recordExist)
        {
             console.log("Time already exist");
            //var dateString = '31.63768733,71546353#2015:03:11 00:00:00#source#once';
            var dateString = recordExist;
            var dateStrParts = dateString.split('#');
            var dateStrParts_sep = dateStrParts[1].split(' ');
            var date_old = dateStrParts_sep[0].replace(/\:/g, '/');
            var saved_date = new Date(date_old);

            //savestate = recordExist.split('#');
            //savestate = savestate[3];//'once' and 'multiple'

            var current_date = new Date(Date.now());

            if (current_date.getDate() !== saved_date.getDate())
            {
                console.log("Time not equal today");
                //empty database table if saved record is old dated
                //AndroidFunction.removeTimeData('');
                AndroidFunction.removeTimeData('');
                AndroidFunction.removeTimeData(form_id_main + '_submited');
                recordExist = AndroidFunction.checkTimeStatus(field_id);
            }

            var old_time = recordExist.split('#');
            $('#' + field_id).val(old_time[1]);//Datetime string
            $('#' + field_id + '_location').val(old_time[0]);//Datetime string
            try {
                $('#' + field_id + '_source').val(old_time[2]);//Datetime string
            } catch (err) {

            }

            var alreadySubmitted = AndroidFunction.checkTimeStatus(form_id_main);

            if (alreadySubmitted)
            {
                var alreadysubmit = alreadySubmitted.split('#');
                if (alreadysubmit[1] == form_id_main + '_submited')
                {
                    if ($(this).find('input:first').attr('action_before') != '' || $(this).find('input:first').attr('time_taken') == 'once') {
                        $('.edit-submit-sep').hide();
                    }
                }
            }
        }

    });


//Events Call area

    $('input[type="text"]').keyup(function (e) {
        console.log("Text field keyup occur");
        trackerCall($(this));
        var valu = $(this).val();
        if ($(this).attr('text_validation') != undefined && $(this).attr('text_validation') == 'alphabet_only')
        {
            if (valu == valu.match(/^[a-zA-Z.\- ]+$/)) {
            } else {
                valu = valu.replace(/[^a-zA-Z.\- ]/g, "");
                $(this).val(valu);
            }
        }
        if ($(this).attr('text_validation') != undefined && $(this).attr('text_validation') == 'numeric_only')
        {
            if (valu == valu.match(/^[0-9.]+$/)) {
            } else {
                valu = valu.replace(/[^0-9.]/g, "");
                $(this).val(valu);
            }
        }
        if ($(this).attr('text_validation') != undefined && $(this).attr('text_validation') == 'alphanumeric')
        {
            if (valu == valu.match(/^[a-zA-Z0-9.\- ]+$/)) {
            } else {
                valu = valu.replace(/[^a-zA-Z0-9.\- ]/g, "");
                $(this).val(valu);
            }
        }
    });


    $('input[type="number"]').keyup(function () {
        console.log("Number field keyup occur");

        trackerCall($(this));
        var current_page_id = $(this).parent().parent().attr('id');
        //var old_page_id = $('#' + current_page_id).find('.page-next').attr('page_id');
        var element_value = parseInt($(this).val());
        var element_id = $(this).attr('id');
        var min_value = parseInt(0);
        var max_value = parseInt(0);
        var page_id = '';

        if ($('#' + element_id + '_condition').length > 0) {
            $('#' + element_id + '_condition').find('option').each(function () {
                min_value = parseInt(0);
                max_value = parseInt(0);
                if ($(this).attr('min_value').length > 0)
                    min_value = parseInt($(this).attr('min_value'));
                if ($(this).attr('max_value').length > 0)
                    max_value = parseInt($(this).attr('max_value'));

                //AndroidFunction.showAlertDialog(element_value+'='+min_value+'='+max_value);
                if (element_value >= min_value && element_value <= max_value)
                {
                    if ($(this).attr('page_id').length > 0)
                        page_id = $(this).attr('page_id');
                    return false;
                } else if (min_value == 0 && element_value <= max_value) {
                    if ($(this).attr('page_id').length > 0)
                        page_id = $(this).attr('page_id');
                    return false;
                } else if (max_value == 0 && element_value >= min_value) {
                    if ($(this).attr('page_id').length > 0)
                        page_id = $(this).attr('page_id');
                    return false;
                } else if (max_value == 0 && min_value == 0) {
                    if ($(this).attr('page_id').length > 0)
                        page_id = $(this).attr('page_id');
                    return false;
                }
            });

            if (page_id != '' && page_id != 0) {
                $('#' + current_page_id).find('.page-next').attr('page_id', page_id);
                $('#' + current_page_id).find('.page-next').show();
            }
        }
    });

    //dropdown populate on number widget
    $('input[type="number"]').blur(function () {

        var current_select_element_value = $(this).val();
        var current_select_element = $(this).attr('id');
        hideshowchildelement(current_select_element, current_select_element_value);
        $('#form-preview').find('[field_setting="main"]').each(function () {
            if ($(this).is('select')) {
                if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == current_select_element) {
                    create_droptown($(this).attr('id'), current_select_element_value);
                    return true;
                }
            }
        });
    });
    $('.ui-autocomplete-input').focus(function (e) {
        $(this).val("");
    });
    //Call select box change event
    $('.selector select').change(function () {
        console.log("Select box change occur");
        trackerCall($(this));
        var curdiv = $(this).attr('id');
        var spanvalue = $('#' + curdiv).prev().html();
        var ismultiple = $('#' + curdiv).attr('multiple');

        if (ismultiple != undefined) {
            $i = 0;
            $('#' + curdiv + ' option:selected').each(function () {
                var display_value = $(this).attr('display_value');
                if ($i > 0) {
                    if (display_value == '') {
                        spanvalue = spanvalue + ',' + $(this).val();
                    } else {
                        spanvalue = spanvalue + ',' + display_value;
                    }

                } else {
                    if (display_value == '') {
                        spanvalue = $(this).val();
                    } else {
                        spanvalue = display_value;
                    }

                }
                $i++;
                $('#' + curdiv).prev().html(spanvalue);
            });
            if ($i == 0) {
                $('#' + curdiv).prev().html('');
            }
        } else {
            //Logical rules
            if ($('#' + curdiv + ' option:selected').attr('page_id') != undefined) {
                var page_id = $('#' + curdiv + ' option:selected').attr('page_id');
                var current_page_id = $('#' + curdiv).parent().parent().parent().attr('id');
                $('#' + current_page_id).find('.page-next').attr('page_id', page_id);
                $('#' + current_page_id).find('.page-next').show();
            }

            var display_value = $('#' + curdiv + ' option:selected').attr('display_value');
            if (display_value == '')
            {
                display_value = $('#' + curdiv + ' option:selected').attr('value');
            }
            $('#' + curdiv).prev().html(display_value);
        }

        var current_select_element = $('#' + curdiv).attr('id');
        var current_select_element_value = $('#' + curdiv).val();

        if (ismultiple != undefined && current_select_element_value != null) {
            var sep_value = current_select_element_value;
            $.each(sep_value, function (i, v) {
                //This loop is for parent-clield of dropdown - Just like Ajax functionality
                $('#form-preview').find('[field_setting="main"]').each(function () {
                    if ($(this).is('select')) {
                        if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == current_select_element) {
                            create_droptown($(this).attr('id'), v);
                            return true;
                        }
                    }
                });
                //Hide and show dependent elements
                hideshowchildelement(current_select_element, v);
                
            });

        }
        else{
            //This loop is for parent-clield of dropdown - Just like Ajax functionality
            $('#form-preview').find('[field_setting="main"]').each(function () {
                if ($(this).is('select')) {
                    if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == current_select_element) {
                        create_droptown($(this).attr('id'), current_select_element_value);
                        return true;
                    }
                }
            });
            //Hide and show dependent elements
            hideshowchildelement(current_select_element, current_select_element_value);
        }
    });


    //Call checkbox change event
    $('input[type="checkbox"]').change(function () {
        console.log("Checkbox field change occur");
        trackerCall($(this));
    });
    $('input[type="radio"]').change(function () {
        console.log("Radio field change occur");
        trackerCall($(this));
        var curdiv = $(this).attr('name');
        var current_select_element = curdiv;
        var current_select_element_value = $(this).attr('value');

        if ($(this).attr('page_id') != undefined) {
            var page_id = $(this).attr('page_id');
            var current_page_id = $(this).parent().parent().parent().attr('id');
            $('#' + current_page_id).find('.page-next').attr('page_id', page_id);
            $('#' + current_page_id).find('.page-next').show();
        }

        //Hide and show dependent elements with required management
        hideshowchildelement(current_select_element, current_select_element_value);
        $('#form-preview').find('[field_setting="main"]').each(function () {
            if ($(this).is('select')) {
                if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == current_select_element) {
                    create_droptown($(this).attr('id'), current_select_element_value);
                    return true;
                }
            }
        });
    });

    $('ul.pages a').on('click', function (e) {

        $('a').removeClass('active');
        // Make the old tab inactive.
        $('ul.pages a').each(function () {
            active_page = $(this).attr('rel');
            $("#" + active_page).hide();
        });

        // Make the tab active.
        $("#" + $(this).attr('id')).addClass('active');
        $("#" + $(this).attr('rel')).show();

        // Prevent the anchor's default click action
        e.preventDefault();
    });

    if ($('#pages-header') != undefined) {
        $('ul.pages li:first a').trigger('click');
    }
    if ($('#tabs-header') != undefined) {
        $('ul.tabs li:first a').trigger('click');
    }

    //Add page functionality
    $('.page-next').on('click', function (e) {
        console.log("Go to next page click");
        var next_page_id = $(this).attr('page_id');
        var current_page_id = $(this).parent().parent().attr('id');
        
        $('a').removeClass('active');
        $('ul.pages a').each(function () {
            active_page = $(this).attr('rel');
            if(active_page == next_page_id){
                $(this).addClass('active');
            }
        });

        if (isrequiredednext(current_page_id))
        {
            $('#' + current_page_id).hide();
            $('#' + next_page_id).show('slow');
            $('#' + next_page_id).find('.page-previous').attr('page_id', current_page_id);
            $('#' + next_page_id).find('.page-previous').show();
        } else {
            //Add Validation here
            return false;
        }
    });
    $('.page-previous').on('click', function (e) {
        console.log("Go to previous page click");
        var prev_page_id = $(this).attr('page_id');
        var current_page_id = $(this).parent().parent().attr('id');
        $('a').removeClass('active');
        $('ul.pages a').each(function () {
            active_page = $(this).attr('rel');
            if(active_page == prev_page_id){
                $(this).addClass('active');
            }
        });

        //Add Validation here
        $('#' + current_page_id).hide();
        $('#' + prev_page_id).show('slow');

    });

    $('.page-skip').on('click', function (e) {
        console.log("Skip page click");
        var current_page_id = $(this).attr('page_id');
        var next_page_id = $('#' + current_page_id).find('.page-next').attr('page_id');
        $('a').removeClass('active');
        $('ul.pages a').each(function () {
            active_page = $(this).attr('rel');
            if(active_page == next_page_id){
                $(this).addClass('active');
            }
        });

        //Remove Validation here
        $('#' + current_page_id).hide();
        $('#' + next_page_id).show('slow');

    });

    //Loop widget changings


    //Open PopUp for enter new record
    $('.edit-field-loop').on('click', function (e) {

        var current_loop_id = $(this).attr('loop_id');
        var cou_fiel=0;
        $('#popup-'+current_loop_id).find('[field_setting="main"]').each(function () {
            cou_fiel++;
        });
        if(cou_fiel>0){
            $('#delete-' + current_loop_id).hide();
            $('#popup-' + current_loop_id).show('scale');
        }
        else{
            AndroidFunction.showAlertDialog('Popup is empty, Add element from server side.');
        }

    });


    //Open PopUp for edit record
    $('.loop-listing').on('click', function () {

        var current_list_id = $(this).attr('id');

        var current_loop_id = $(this).parent().attr('loop_id');

        //Add Here populate the record to Popup
        populate_popup(current_loop_id, current_list_id);

        $('#delete-' + current_loop_id).attr('current_list_id', current_list_id);
        $('#save-' + current_loop_id).attr('current_list_id', current_list_id);
        $('#delete-' + current_loop_id).show();
        $('#popup-' + current_loop_id).show('scale');


    });


    //Close popup without change or add record
    $('.cancel-cls').on('click', function (e) {
        var current_loop_id = $(this).attr('loop_id');
        $('#popup-' + current_loop_id).hide('scale');

        $('#delete-' + current_loop_id).attr('current_list_id', '');
        $('#save-' + current_loop_id).attr('current_list_id', '');
        reset_popup(current_loop_id);

    });


    //Save or Update PopUp values
    $('.save-cls').on('click', function (e) {

        var checkboxes_aray = {};
        var current_loop_id = $(this).attr('loop_id');
        var for_listing = '';
        var for_input = '';
        var required_check = false;
        var field_label_l = '';
        $('#popup-' + current_loop_id).find('[field_setting="main"]').each(function () {

            if ($('#' + $(this).id + '-container').css('display') != 'none' && $(this).attr('required') != undefined && $(this).val() == '')
            {
                //show required message
                if ($(this).is('select'))
                {
                    field_label_l = $(this).parent().prev().text();
                } else
                {
                    field_label_l = $(this).prev().text();
                }
                if (field_label_l == '')
                {
                    field_label_l = $(this).attr('name');
                    field_label_l = field_label_l.replace(/\+/g, ' ');
                }
                var message = field_label_l + ' required';
                AndroidFunction.showAlertDialog(message);
                required_check = true;
                return false;
            }


            if ($(this).is(':checkbox')) {
                if ($(this).is(':checked')) {
                    var name_chk = $(this).attr('name');
                    //AndroidFunction.showAlertDialog(name_chk);
                    name_chk = name_chk.replace('[]', '');
                    //AndroidFunction.showAlertDialog(name_chk);
                    if (checkboxes_aray[name_chk] == undefined)
                        checkboxes_aray[name_chk] = $(this).val() + ',';
                    else
                        checkboxes_aray[name_chk] += $(this).val() + ',';
                }

            } else if ($(this).is(':radio')) {
                if ($(this).is(':checked')) {
                    for_listing += $(this).val() + ',';
                    for_input += $(this).attr('name') + ':' + $(this).val() + '&';
                }

            } else if ($(this).is('option')) {
            } else {
                if ($(this).val() != '' && $(this).hasClass('ui-autocomplete-input') == false) {
                    for_listing += $(this).val() + ',';
                    for_input += $(this).attr('name') + ':' + $(this).val() + '&';
                }

            }
            if($(this).parent().hasClass('taketimeclass')){
                    for_listing += $('#'+$(this).attr('id')+'_location').val() + ',';
                    for_input += $(this).attr('name')+'_location' + ':' + $('#'+$(this).attr('id')+'_location').val() + '&';
                    for_listing += $('#'+$(this).attr('id')+'_source').val() + ',';
                    for_input += $(this).attr('name')+'_source' + ':' + $('#'+$(this).attr('id')+'_source').val() + '&';
            }
        });

        if (required_check == true) {
            return false;
        }
        $.each(checkboxes_aray, function (ind, va) {

            for_listing += va + ',';
            for_input += ind + ':' + va + '&';
        });

        for_listing = for_listing.substring(0, for_listing.length - 1);
        for_input = for_input.substring(0, for_input.length - 1);

        //In case of edit record
        if ($(this).attr('current_list_id') != undefined && $(this).attr('current_list_id') != '') {
            var current_list_id = $(this).attr('current_list_id');
            if(for_input!=""){
                $('#' + current_list_id).find('input').val(for_input);
                $('#' + current_list_id).find('label').html(for_listing);
            }else{
                $('#' + current_list_id).remove();
            }

        } else {//in case of new record
            if(for_input!=""){
                var loop_list_id = count_loop_listing(current_loop_id);
                for_listing = '<div class="loop-listing" id="loop_' + current_loop_id + '_list_' + loop_list_id + '"><input type="text" style="display:none" value="' + for_input + '" id="" loop_id="' + current_loop_id + '"><label>' + for_listing + '</label></div>';
                $('#loop-list-' + current_loop_id).append(for_listing);
            }
        }


        $('#delete-' + current_loop_id).attr('current_list_id', '');
        $('#save-' + current_loop_id).attr('current_list_id', '');
        $('#popup-' + current_loop_id).hide('scale');
        reset_popup(current_loop_id);

        

    });




    //Record row remove which entered in Loop widget
    $('.delete-cls').on('click', function () {
        var current_list_id = $(this).attr('current_list_id');
        $('#' + current_list_id).remove();
        var current_loop_id = $(this).attr('loop_id');
        $('#popup-' + current_loop_id).hide('scale');

        $('#delete-' + current_loop_id).attr('current_list_id', '');
        $('#save-' + current_loop_id).attr('current_list_id', '');
        reset_popup(current_loop_id);
    });



        ////////////////////////////////////////
//////////////////////////// Hassan
        ////////////////////////////////////////

        window.hideLoader();


        ////////////////////////////////////////
//////////////////////////// Hassan
        ////////////////////////////////////////


});

//This function call on back button or back from unsent item of android device
function trackerCall(ele) {

        if (!is_edit_mode) {
            try {
                AndroidFunction.onBackPressShowAlert(true)
                if(ele.attr('tracking_status') != undefined){
                    if(ele.attr('tracking_status')=='start'){
                        AndroidFunction.startTracking()
                    }
                    else if(ele.attr('tracking_status')=='stop'){
                        AndroidFunction.stopTracking()
                    }
                }
            } catch (err) {

            }
        }
}


//This function call on blur and filled form save to draft and show in unsent listing of android device
function save_to_draft() {
        var save_draft_edit_id = "";
        if($('#save_draft_edit_id').val()!=undefined && $('#save_draft_edit_id').val()!=null){
            save_draft_edit_id = $('#save_draft_edit_id').val();
        }
        
        var form_ser = $('#form-preview').serialize();
        var form_values = parse_query(form_ser,false);
        var myJsonString = JSON.stringify(form_values);//This function will return json {"field1":"zad","field2":"Option+1"}
        //submit form and save record if internal not available

        //AndroidFunction.showAlertDialog(myJsonString+save_draft_edit_id);
        try {
            save_draft_edit_id = AndroidFunction.onSaveDraft(save_draft_edit_id,myJsonString);
            $('#save_draft_edit_id').val(save_draft_edit_id);
        } catch (err) {

        }
        AndroidFunction.showAlertDialog("Draft saved");
        //call save to draft
        
}
//This function call on back button or back from unsent item of android device
function resetCounter() {
    if ($('#icon_notify').length > 0) {
        var counter_unsent = AndroidFunction.GetCountOfUnSentActivities();
        if (counter_unsent > 0) {
            $('#icon_notify').show();
            $('#icon_notify_counter').html(counter_unsent);//this counter from unsent items listing
        } else {
            $('#icon_notify').hide();
        }
    }
}

//This function call on back button or back from unsent item of android device
function loadLastActivity() {
    var form_id = $('#form_id').val();
    var security_key = $('#security_key').val();
    var old_sent_activity = AndroidFunction.GetLastActivity(form_id);
    if (old_sent_activity != '')
    {
        //var jsonData = $.parseJSON(old_sent_activity);
        var json = $.parseJSON(old_sent_activity);
        $(json).each(function (i, val) {
            $.each(val, function (k, v) {

                if (k == 'form_id' || k == 'security_key' || k == 'is_take_picture') {
                } else
                {
                    var valstring = v;
                    valstring = valstring.replace(security_key, "");
                    v = Base64.decode(valstring);
                }


                //In case of checkbox
                if ($('#form-preview').find('[name="' + k + '[]"]').attr('name') != undefined && $('#form-preview').find('[name="' + k + '[]"]').parent().parent().hasClass('save_last_activity'))
                {
                    v = v.replace(/\+/g, ' ');
                    var valueofcoma = new Array();
                    valueofcoma = v.split(',');

                    $('#form-preview').find('[name="' + k + '[]"]').each(function () {
                        if ($.inArray($(this).val(), valueofcoma) != '-1') {
                            $(this).attr('checked', true);
                        } else {
                            $(this).attr('checked', false);
                        }
                        $(this).attr('onclick', 'return false');
                    });
                }

                var ele_id = '';
                //this condition will all fields but enable only editable fields in edit mode.
                if ($('#form-preview').find('[name="' + k + '"]')) {
                    var element_obj = $('#form-preview').find('[name="' + k + '"]');

                    if (v != "" && v != null && v != undefined)
                    {
                        v = v.replace(/\+/g, ' ');
                    }
                    if (element_obj.is('select') && element_obj.attr('save_last_activity') != undefined)
                    {

                        if (element_obj.attr('dependon_value') != undefined) {
                            var dep_id = element_obj.attr('dependon_id');
                            if (element_obj.attr('dependon_value') == $('#' + dep_id).val()) {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            } else {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').hide();
                            }
                            if (element_obj.attr('dependon_value') == '') {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            }
                        }


                        element_obj.val(v);
                        element_obj.prev().text(v);
                    } else if (element_obj.is(':radio') && element_obj.parent().parent().hasClass('save_last_activity')) {
                        element_obj.each(function () {
                            if ($(this).val() == v) {
                                $(this).prop('checked', true);
                            }
                        });
                    } else if (element_obj.attr('save_last_activity') != undefined)
                    {
                        if (element_obj.attr('dependon_value') != undefined) {
                            var dep_id = element_obj.attr('dependon_id');
                            if (element_obj.attr('dependon_value') == $('#' + dep_id).val()) {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            } else {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').hide();
                            }
                            if (element_obj.attr('dependon_value') == '') {
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            }

                        }

                        element_obj.val(v);
                        //AndroidFunction.showAlertDialog(k+' = '+v);
                    }

                }
            });
        });
    }
}

function populate_popup(current_loop_id, current_list_id) {

    //get old values
    var record_for_update = $('#' + current_list_id).find('input').val();
    record_for_update = record_for_update.split('&');

    $.each(record_for_update, function (i, v) {

        var name_value = v.split(':');
        var name_of_element = name_value[0];
        var value_of_element = name_value[1];


        if ($('#popup-' + current_loop_id).find('[name="' + name_of_element + '"]')) {
            var element_obj = $('#popup-' + current_loop_id).find('[name="' + name_of_element + '"]');

            value_of_element = value_of_element.replace(/\+/g, ' ');

            if (element_obj.is('select')) {
                element_obj.prev().text(value_of_element);
                element_obj.val(value_of_element);
            } else if (element_obj.is(':radio')) {
                element_obj.each(function () {
                    if ($(this).val() == value_of_element) {
                        $(this).prop('checked', true);
                    }
                });
            } else if (element_obj.is(':checkbox')) {
                var checkbox_str = value_of_element.split(',');
                element_obj.each(function () {
                    if ($.inArray($(this).val(), checkbox_str) != '-1') {
                        $(this).prop('checked', true);
                    }
                });

            } else {
                element_obj.val(value_of_element);
            }
        }

    });

}


function count_loop_listing(current_loop_id) {
    var i = 1;
    $('#loop-list-' + current_loop_id).find('.loop-listing').each(function () {

        if ($(this).attr('id') != 'loop_' + current_loop_id + '_list_' + i)
        {
            return i;
        }
        i++;

    });
    return i;
}

function reset_popup(current_loop_id)
{

    $('#popup-' + current_loop_id).find('[field_setting="main"]').each(function () {

        if ($(this).is(':radio')) {
            $(this).prop('checked', false);
        } else if ($(this).is(':checkbox')) {
            $(this).prop('checked', false);
        } else if ($(this).is('select')) {
            $(this).val('');
            $(this).prev().html('Please Select');
        } else if ($(this).is('option')) {
        } else
        {
            $(this).val('');
        }
    });


}


window.showLoader=function(){
    console.log("showing loader");
    jQuery('.loader').show();
}
window.hideLoader=function(){
    
    setTimeout(function() {console.log("hiding loader");jQuery('.loader').hide();}, 2000);
    
}

//Function area out of readydocument
function hideshowchildelement(element_id, selected_element_value) {
    console.log("call hideshowclildelement elsement="+element_id+' => value='+selected_element_value);
    var element_repeted = [];
    if (selected_element_value == undefined)
    {
        selected_element_value = '';
    }



    $('[dependon_id="' + element_id + '"]').each(function () {
        var element_multi_value = [];

        var child_element_id = $(this).attr('id');//for showing/hiding the main container

        if ($(this).is(':radio') || $(this).is(':checkbox')) {
            child_element_id = child_element_id.split('-')[0];//for showing/hiding the main container
        }

        if ($.inArray(child_element_id, element_repeted) == '-1') {
            //AndroidFunction.showAlertDialog(child_element_id); 
            element_repeted.push(child_element_id);
            if ($(this).attr('dependon_value') != undefined && $(this).attr('dependon_value') != '')
            {
                var number_correct = false;
                if ($('#' + element_id).attr('type') == 'number') {
                    console.log("check is number in hideshowclildelement function");
                    var cond_value = $(this).attr('dependon_value').split(',');
                    $.each(cond_value, function (key, value) {
                        if (value.indexOf('>') != -1) {
                            console.log("Is > condition true="+value);
                            var operator_value = value.substring(0, 1);
                            var num_value = value.substring(1);
                            if (parseInt(selected_element_value) > parseInt(num_value)) {
                                console.log("Is > condition true="+selected_element_value+'>'+num_value);
                                number_correct = true;
                            }
                        }
                        if (value.indexOf('<') != -1) {
                            var operator_value = value.substring(0, 1);
                            var num_value = value.substring(1);
                            if (parseInt(selected_element_value) < parseInt(num_value)) {
                                number_correct = true;
                            }
                        }
                        if (value.indexOf('-') != -1) {
                            var num_value = value.split('-');
                            if (parseInt(selected_element_value) >= parseInt(num_value[0]) && parseInt(selected_element_value) <= parseInt(num_value[1])) {
                                number_correct = true;
                            }
                        }
                    });

                }
                element_multi_value = $(this).attr('dependon_value').split(',');
            }


            if (($(this).attr('dependon_value') != undefined && $.inArray(selected_element_value, element_multi_value) != '-1') || ($(this).attr('dependon_value') == '' && selected_element_value.length > 0) || number_correct) {

                if ($(this).attr('req') != undefined)
                {
                    $(this).attr('required', 'required');
                    $(this).removeAttr("req");
                }

                if (is_edit_mode) {
                    if ($(this).parent().hasClass('edit-field-loop'))
                    {
                        var loop_id = $(this).attr('loop_id');
                        $('#loop-' + loop_id).show();
                    } else {
                        $('#' + child_element_id + '-container').show();
                    }
                } else {
                    if ($(this).attr('editable') != undefined) {
                        if ($(this).parent().hasClass('edit-field-loop'))
                        {

                            var loop_id = $(this).attr('loop_id');
                            $('#loop-' + loop_id).hide();
                        } else {
                            $('#' + child_element_id + '-container').hide();
                        }
                    } else {
                        if ($(this).parent().hasClass('edit-field-loop'))
                        {
                            var loop_id = $(this).attr('loop_id');
                            $('#loop-' + loop_id).show();
                        } else {
                            $('#' + child_element_id + '-container').show();
                        }
                    }
                }

            } else
            {

                if ($(this).attr('required') != undefined)
                {
                    $(this).attr('req', 'yes');
                    $(this).removeAttr("required");
                }

                var hideelement = $('#' + child_element_id + '-container').find('[field_setting="main"]');
                if (hideelement.is('select')) {
                    hideelement.prop('selectedIndex', -1);


                    if ($('#' + child_element_id).find("option").length > 1)
                    {
                        //hideelement.trigger('change');

                    }

                    hideshowchildelement(child_element_id, $(this).val());
                } else if (hideelement.is(':radio') || hideelement.is(':checkbox')) {
                    hideelement.prop('checked', false);
                } else if ($(this).is('option')) {
                } else {
                    $('#' + child_element_id + '-container').hasClass('cameraclass');
                    {
                        $('#' + child_element_id + '-container').find('.formobile').attr("src", "takepicture.png");
                    }
                    hideelement.val('');
                }
                if ($(this).parent().hasClass('edit-field-loop'))
                {
                    var loop_id = $(this).attr('loop_id');
                    $('#loop-' + loop_id).hide();
                } else {
                    $('#' + child_element_id + '-container').hide();
                }
            }
        }
        //hideshowchildelement(radio_element_id, selected_element_value, inner_element_type);
    });
    return true;

}
//Return False if some validation missing 
function isrequiredednext(current_page) {

    var requiredval = true;

    $('#' + current_page).find('[field_setting="main"]').each(function () {

        if ($(this).is('option')) {
        }
        if ($(this).attr('subtable_id') != undefined && $(this).attr('subtable_id') != '')
        {
            //return true;
        } else if ($(this).attr('subtable') != undefined && $(this).attr('subtable') == 'true')
        {
            var containter_loop_id = $(this).attr('loop_id');
            if ($(this).attr('required') != undefined && $('#loop-list-' + containter_loop_id).html() == '') {
                field_label = $('#addloop' + containter_loop_id).attr('name');
                field_label = field_label.replace(/\_/g, ' ');
                var message = field_label + ' required';
                AndroidFunction.showAlertDialog(message);
                //return false;
                $('.edit-submit-sep').show();
                requiredval = false;
            }
            //return true;
        } else
        {
            var ele_val = $(this).val();
            console.log("Element Value : "+ele_val);
            console.log("Element Id : "+$(this).attr('id'));
            console.log("Is Required status : "+$(this).attr('required'));

            if ($(this).attr('required') != undefined && $('#'+$(this).attr('id') + '-container').css('display') != 'none') {

                var field_label = '';
               
                if (ele_val == null || ele_val == "") {
                    if ($(this).is('select'))
                    {
                        field_label = $(this).parent().prev().text();
                    } else
                    {
                        field_label = $(this).prev().text();
                    }
                    if (field_label == '')
                    {
                        field_label = $(this).attr('name');
                        field_label = field_label.replace(/\+/g, ' ');
                    }
                    var message = field_label + ' required';
                    AndroidFunction.showAlertDialog(message);
                    //alert(message);
                    $('.edit-submit-sep').show();
                    requiredval = false;
                    //return false;
                }
                
                if (!isValidTime($(this), ele_val)) {
                    $('.edit-submit-sep').show();
                    requiredval = false;
                }
                if (!lengthValidation($(this), ele_val)) {
                    $('.edit-submit-sep').show();
                    requiredval = false;
                }
                if (!minMaxValidation($(this), ele_val)) {
                    $('.edit-submit-sep').show();
                    requiredval = false;
                }
                if (!isNumericValue($(this), ele_val)) {
                    $('.edit-submit-sep').show();
                    requiredval = false;
                }
            }


        }
        if(!requiredval){
            return requiredval;
        }
    });
    return requiredval;
}

function minMaxValidation(element_object_sent, orignal_value) {

    if (element_object_sent.attr('min') != undefined && element_object_sent.attr('min') != '')
    {
        if (parseInt(orignal_value) < parseInt(element_object_sent.attr('min')))
        {
            var message = ' Your entered number should not less then ' + element_object_sent.attr('min') + ' in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    if (element_object_sent.attr('max') != undefined && element_object_sent.attr('max') != '')
    {
        if (parseInt(orignal_value)  > parseInt(element_object_sent.attr('max')))
        {
            var message = ' Your entered number should not greater then ' + element_object_sent.attr('max') + ' in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    return true;

}

function lengthValidation(element_object_sent, orignal_value) {

    if (element_object_sent.attr('minlength') != undefined && element_object_sent.attr('minlength') != '')
    {
        if (parseInt(element_object_sent.attr('minlength')) > orignal_value.length)
        {
            var message = ' Your entered value length should be minimum ' + element_object_sent.attr('minlength') + ' in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    //Due to html conflict maxlength work as exact length and exact as maxlength.
    if (element_object_sent.attr('maxlength') != undefined && element_object_sent.attr('maxlength') != '')
    {
        if (parseInt(element_object_sent.attr('maxlength')) != orignal_value.length)
        {
            var message = ' Your entered value length should be ' + element_object_sent.attr('maxlength') + ' in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    if (element_object_sent.attr('exactlength') != undefined && element_object_sent.attr('exactlength') != '')
    {
        if (parseInt(element_object_sent.attr('exactlength')) < orignal_value.length)
        {
            var message = ' Your entered value length should be maximum ' + element_object_sent.attr('exactlength') + ' in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    return true;

}
function isNumericValue(element_object_sent, orignal_value) {

    if (element_object_sent.attr('text_validation') != undefined && element_object_sent.attr('text_validation') == 'numeric_only')
    {
        if (isNaN(orignal_value))
        {
            var message = ' Please enter numeric value in ' + element_object_sent.attr('name') + ' field';
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }

    return true;

}


function isValidTime(element_object_sent, orignal_value) {



    if (element_object_sent.attr('type') == 'time') {
        var validTime = orignal_value.match(/^(0?[0-9]|1[0-9]|2[0-3])(:[0-5]\d)$/);

        if (!validTime) {
            var message = ' Please enter (HH:MM) using 24 hours format in ' + element_object_sent.attr('name') + ' field';
            //AndroidFunction.showAlertDialog(message);
            return true;
        }
    }
    if (element_object_sent.attr('time_validation') != undefined && element_object_sent.attr('time_validation') != '')
    {
        var start_time_field = element_object_sent.attr('time_validation');

        var start_time_value = $('#' + start_time_field).val();//start time
        var end_time_value = element_object_sent.val();//end time

        var start = start_time_value;
        var end = end_time_value;

        var start_ampm = start.split(' ');
        var add_start = 0;
        if(start_ampm[1] == 'PM'){
            add_start = 12;
        } 

        var end_ampm = end.split(' ');
        var add_end = 0;
        if(end_ampm[1] == 'PM'){
            add_end = 12;
        }


        var s = start.split(':');
        var e = end.split(':');

        if (s[0] == '00')
        {
            s[0] = '24';
        }
        if (e[0] == '00')
        {
            e[0] = '24';
        }

        var e_hour = parseInt(e[0])+add_end;
        var e_min = parseInt(e[1]);
        var s_hour = parseInt(s[0])+add_start;
        var s_min = parseInt(s[1]);

        var valid_time = false;
        if (e_hour == s_hour && e_min == s_min) {
            valid_time = true;
        } else if (e_hour == s_hour && e_min < s_min) {
            valid_time = true;
        } else if (e_hour < s_hour) {
            valid_time = true;
        }

        if (valid_time) {
            var message = element_object_sent.attr('name') + ' time should be greater then ' + $('#' + start_time_field).attr('name');
            AndroidFunction.showAlertDialog(message);
            return false;
        }
    }
    return true;

}



function load_record(json_val) {
    //AndroidFunction.showAlertDialog('call on load_record');
    //var json_val = '{"Name":"zahid","Gender":"Male"}';
    var json = JSON.parse(json_val);
    is_edit_mode = true;
    $('.cameraclass').hide();
    $('.edit-editsubmit-sep').show();
    $('.edit-save-sep').hide();
    $('.edit-submit-sep').hide();
    $('#form-title').find('.formobile').hide();
    var security_key = $('#security_key').val();
    $(json).each(function (i, val) {

        $.each(val, function (k, v) {


            if (k == 'form_id' || k == 'security_key' || k == 'is_take_picture' || k == 'row_key')
            {
                if (k == 'is_take_picture') {
                    v = v.toString();
                }
            } else if (k == 'deviceTS') {
                var newInput = $('<input type="hidden" name="deviceTS" id="deviceTS" value="' + v + '" class="form-control"/>');
                $('input#form_id').after(newInput);
                return true;
            } else
            {
                var valstring = v;
                if (v != null && v != undefined)
                {
                    if (v.indexOf(security_key) > -1) {
                        valstring = valstring.replace(security_key, "");
                        v = Base64.decode(valstring);
                        v = v.replace(/\+/g, ' ');
                    }
                }
            }
            if (!(k == 'deviceTS' || k == 'is_take_picture')) {
                //In case of checkbox
                if ($('#form-preview [name="' + k + '[]"]') != undefined)
                {
                    //AndroidFunction.showAlertDialog(k);
                    var valueofcoma = new Array();

                    if (v != "" && v != null && v != undefined) {
                        valueofcoma = v.split(',');
                    }
                    $('#form-preview [name="' + k + '[]"]').each(function () {
                        if ($.inArray($(this).val(), valueofcoma) != '-1') {
                            $(this).attr('checked', true);
                        } else {
                            $(this).attr('checked', false);
                        }
                        $(this).attr('onclick', 'return false');
                    });
                }

                var ele_id = '';
                //this condition will all fields but enable only editable fields in edit mode.
                if ($('#form-preview [name="' + k + '"]') != undefined) {

                    var element_obj = $('#form-preview [name="' + k + '"]');

                    if (v != "" && v != null && v != undefined)
                    {
                        v = v.replace(/\+/g, ' ');
                    }
                    if (element_obj.is('select'))
                    {
                        ele_id = element_obj.attr('id');
                        $('#' + ele_id + '-container').show();
                        if (!element_obj.attr('editable')) {
                            element_obj.attr('disabled', true);
                        } else {
                            if (element_obj.attr('dependon_value') != undefined) {
                                var dep_id = element_obj.attr('dependon_id');
                                if (element_obj.attr('dependon_value') == '') {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                } else if (element_obj.attr('dependon_value') == $('#' + dep_id).val()) {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                } else {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').hide();
                                }

                            }

                        }
                        element_obj.val(v);
                        element_obj.prev().text(v);
                    } else if (element_obj.is(':radio')) {
                        element_obj.each(function () {
                            if ($(this).val() == v) {
                                $(this).prop('checked', true);
                            }
                        });
                        if (!element_obj.attr('editable')) {
                            element_obj.attr('disabled', true);
                        }
                    } else
                    {
                        ele_id = element_obj.attr('id');
                        $('#' + ele_id + '-container').show();
                        //AndroidFunction.showAlertDialog('k='+k+');
                        if (!element_obj.attr('editable')) {
                            element_obj.attr('readonly', true);
                        } else {

                            if (element_obj.attr('dependon_value') != undefined) {

                                var dep_id = element_obj.attr('dependon_id');
                                //AndroidFunction.showAlertDialog('k='+k+' ='+$('#' + dep_id).val());
                                if (element_obj.attr('dependon_value') == $('#' + dep_id).val()) {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                } else {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').hide();
                                }
                                if (element_obj.attr('dependon_value') == '') {
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                }

                            }
                        }
                        element_obj.val(v);
                        //AndroidFunction.showAlertDialog(k+' = '+v);
                    }

                }
            }


//            AndroidFunction.showAlertDialog("i ="+k+" &val="+v);


        });
    });

    if ($('#activity_duration_in_seconds').val() != undefined)
    {
        clearInterval(intervalId);
        var totalSeconds = parseInt($('#activity_duration_in_seconds').val());
        intervalId = setInterval(setTime1, 1000);
        function setTime1()
        {
            ++totalSeconds;
            $('#activity_duration_in_seconds').val(totalSeconds);
            // if ($('#second_count').attr('id') == undefined)
            //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
            // $('#second_count').html(totalSeconds+' Seconds');
        }
    }
}



function load_draft(row_id_draft,json_val,image_data) {
    console.log("loading draft");
    window.showLoader();
    if(image_data!=''){
        //AndroidFunction.showAlertDialog(image_data);
        var json_image = JSON.parse(image_data);

        $('#form-preview').find('.cameraclass').each(function () {
            var container_image = $(this).attr('id');
            var element_obj = container_image.split('-');
            var element_id = element_obj[0];

            $(json_image).each(function (i_image, i_val) {
                if (i_val.indexOf("_caption-"+element_id) >= 0){
                    $('#picselect-'+element_id).attr('src',i_val);
                    $('#'+element_id).val('yes');
                    $('#takepic').val('takepicture');
                }
            });

        });

    }

    $('#save_draft_edit_id').val(row_id_draft);
    var json = JSON.parse(json_val);
    console.log(json_val);
    $('#form-title').find('.formobile').hide();
    $(json).each(function (i, val) {
        $.each(val, function (k, v) {
            if (k == 'form_id' || k == 'security_key' || k == 'is_take_picture' || k == 'row_key')
            {
                if (k == 'is_take_picture') {
                    v = v.toString();
                }
            } else if (k == 'deviceTS') {
                v = decodeURIComponent(v);
                var newInput = $('<input type="hidden" name="deviceTS" id="deviceTS" value="' + v + '" class="form-control"/>');
                $('input#form_id').after(newInput);
                return true;
            } 
            if (!(k == 'deviceTS' || k == 'is_take_picture')) {
                //In case of checkbox
                if ($('#form-preview [name="' + k + '[]"]') != undefined)
                {
                    if ($('#form-preview [name="' + k + '[]"]').is(':checkbox')){
                        //AndroidFunction.showAlertDialog(k);
                        var valueofcoma = new Array();

                        if (v != "" && v != null && v != undefined) {
                            valueofcoma = v.split(',');
                        }
                        $('#form-preview [name="' + k + '[]"]').each(function () {
                            if ($.inArray($(this).val(), valueofcoma) != '-1') {
                                $(this).attr('checked', true);
                            } else {
                                $(this).attr('checked', false);
                            }
                        });
                    }
                }

                var ele_id = '';
                //this condition will all fields but enable only editable fields in edit mode.
                if ($('#form-preview [name="' + k + '"]') != undefined) {
                    console.log("subtable="+$('#form-preview [name="' + k + '"]').attr('subtable'));
                    if($('#form-preview [name="' + k + '"]').attr('subtable')=="true")
                    {
                        var current_loop_id = $('#form-preview [name="' + k + '"]').attr('loop_id');
                        if($.isArray(v) &&  v.length > 0 ) {
                            //console.log('this is array'+v);
                            $.each(v, function (ka, va) {
                                var loop_list_id = count_loop_listing(current_loop_id);
                                for_listing = '<div class="loop-listing" id="loop_' + current_loop_id + '_list_' + loop_list_id + '"><input type="text" style="display:none" value="' + va + '" id="" loop_id="' + current_loop_id + '"><label>' + va + '</label></div>';
                                $('#loop-list-' + current_loop_id).append(for_listing);
                            });
                        }
                        console.log(k+" = This is a loop widget");
                        return true;
                    }

                    var element_obj = $('#form-preview [name="' + k + '"]');
                    console.log("#Before Replace========"+k+"=>"+v);

                    console.log(k+" === "+v);

                    if (v != "" && v != null && v != undefined)
                    {
                        try { 
                            v = v.replace(/\+/g, ' ');
                              v = unescape(v); 
                              v = unescape(v); 
                            } catch(e) { 
                              console.error(e); 
                            }
                        //v = v.replace(/\+/g, ' ');
                    }
                    console.log("##After Replace========"+k+"=>"+v);
                    var dependon_value_main = '';
                    ele_id = element_obj.attr('id');
                    if (element_obj.attr('dependon_value') != undefined) {

                        var in_case_multi = element_obj.attr('dependon_value').split(',');
                        var dep_id = element_obj.attr('dependon_id');
                        
                        if($('#' + dep_id).val() == undefined){
                            $('#form-preview [name="' + dep_id + '"]').each(function(){
                                if ($(this).is(':radio')){

                                    if( $.inArray($(this).val(), in_case_multi) != -1 ) {
                                        dependon_value_main = $(this).val();
                                    }

                                }

                            });

                        }else{
                            dependon_value_main = $('#' + dep_id).val();
                        }
                    }
                    console.log("Element Id = "+ele_id+" ---Field Name = "+k+" ----Depend On Value = "+element_obj.attr('dependon_value')+" ----Depend on ele value = " +dependon_value_main);
                    console.log(in_case_multi);




                    if (element_obj.is('select'))
                    {
                            
                            $('#' + ele_id + '-container').show();
                           
                            if (element_obj.attr('dependon_value') != undefined) {
                               
                                if( $.inArray(dependon_value_main, in_case_multi) != -1 ) {
                                    //console.log('Show the element = '+k);
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                } else {
                                    //console.log('Hide the element = '+k);
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').hide();
                                }
                                if (element_obj.attr('dependon_value') == '') {
                                    //console.log('Show in empty the element = '+k);
                                    ele_id = element_obj.attr('id');
                                    $('#' + ele_id + '-container').show();
                                }

                            }
                            
                            element_obj.val(v);
                            element_obj.prev().text(v);
                            element_obj.trigger('change')
                        //}

                    } else if (element_obj.is(':radio')) {

                        element_obj.each(function () {
                            if ($(this).val() == v) {
                                $(this).prop('checked', true);
                                //$(this).trigger('click');

                                var curdiv = $(this).attr('name');
                                var current_select_element = curdiv;
                                var current_select_element_value = $(this).attr('value');
                                console.log('current selected')


                                if ($(this).attr('page_id') != undefined) {
                                    var page_id = $(this).attr('page_id');
                                    var current_page_id = $(this).parent().parent().parent().attr('id');
                                    $('#' + current_page_id).find('.page-next').attr('page_id', page_id);
                                    $('#' + current_page_id).find('.page-next').show();
                                }


                                //Hide and show dependent elements with required management
                                hideshowchildelement(current_select_element, current_select_element_value);
                                $('#form-preview').find('[field_setting="main"]').each(function () {
                                    if ($(this).is('select')) {
                                        if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == current_select_element) {
                                            create_droptown($(this).attr('id'), current_select_element_value);
                                            return true;
                                        }
                                    }
                                });
                            }
                        });
                            
                    } 
                    else
                    {
                       // ele_id = element_obj.attr('id');
                        $('#' + ele_id + '-container').show();
                        //var dependon_value_main = '';
                        //AndroidFunction.showAlertDialog('Other field ='+k+'='+v);

                        if (element_obj.attr('dependon_value') != undefined && element_obj.attr('dependon_value') != "") {
                            
                            if( $.inArray(dependon_value_main, in_case_multi) != -1 ) {
                                console.log('Show the element = '+k);
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            } else {
                                console.log('Hide the element = '+k);
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').hide();
                            }
                            if (element_obj.attr('dependon_value') == '') {
                                console.log('Show in empty the element = '+k);
                                ele_id = element_obj.attr('id');
                                $('#' + ele_id + '-container').show();
                            }
                            

                        }
                        else{
                            console.log('Show finaly the element = '+k);
                            ele_id = element_obj.attr('id');
                            $('#' + ele_id + '-container').show();
                        }
                        
                        element_obj.val(v);
                    }

                }
            }
        });
    });
    if ($('#activity_duration_in_seconds').val() != undefined)
    {
        clearInterval(intervalId);
        var totalSeconds = parseInt($('#activity_duration_in_seconds').val());
        intervalId = setInterval(setTime1, 1000);
        function setTime1()
        {
            ++totalSeconds;
            $('#activity_duration_in_seconds').val(totalSeconds);
            // if ($('#second_count').attr('id') == undefined)
            //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
            // $('#second_count').html(totalSeconds+' Seconds');
        }
    }
    window.hideLoader();
}

function create_droptown(element_id, element_value)
{
    $('#' + element_id + '_temp').hide();
    $('#' + element_id).parent().parent().show();

    $('#' + element_id).empty();
    var i = 0;
    var option_total = 0;
    var final_options_first = '';
    var final_options = '';
    $('#' + element_id + '_temp').find('option').each(function () {
        if (i == 0)
        {
            if ($('#' + element_id).attr('multiple') != undefined && $('#' + element_id).attr('multiple') == 'multiple') {
            } else {
                final_options_first = '<option  id="' + $(this).attr('id') + '1111" parent_value="" value="" display_value="Please Select">Please Select</option>';
            }
        }
        i++;
        if (element_value == $(this).attr('parent_value') && $(this).attr('value') != '') {
            option_total++;
            final_options += '<option  id="' + $(this).attr('id') + '" parent_value="' + $(this).attr('parent_value') + '" value="' + $(this).attr('value') + '" display_value="' + $(this).attr('display_value') + '">' + $(this).attr('display_value') + '</option>';
        }
    });
    if (option_total == 0) {
        final_options = final_options_first; //+ final_options;
        $('#' + element_id).append(final_options);
        $('#' + element_id + '-container').hide();
    } else if (option_total == 1) {
        $('#' + element_id).append(final_options);
        $('#' + element_id).trigger('change');//this change will occur when only one option in select box
    } else {
        final_options = final_options_first + final_options;
        $('#' + element_id).append(final_options);
    }
    if ($('#' + element_id).attr('multiple') != undefined && $('#' + element_id).attr('multiple') == 'multiple') {
        $('#' + element_id).prop('selectedIndex', -1);
    }

    if ($('#' + element_id).attr('class') == 'select_autocomplete') {
        $('#' + element_id).next().remove('.ui-autocomplete-input');
        $('#' + element_id).selectToAutocomplete();
        $('#' + element_id).next().show('.ui-autocomplete-input');
    } else {
        $('#' + element_id).prev().html($('#' + element_id + ' option:first').text());
    }

    //var current_select_element = $('#'+element_id).attr('id');
//    var current_select_element_value = $('#' + element_id).val();
//
//    $('#form-preview').find('select').each(function () {
//        if ($(this).is('select')) {
//            if ($(this).attr('parent_id') != 'undefined' && $(this).attr('parent_id') != '' && $(this).attr('parent_id') == element_id) {
//                create_droptown($(this).attr('id'), current_select_element_value);
//            }
//        }
//    });
    return true;
}







var pathcurfile = getCurentFileName();
var form_id_main = $('#form_id').val();

function getCurentFileName() {
    var pagePathName = window.location.pathname;
    return pagePathName.substring(pagePathName.lastIndexOf("/") + 1);
}

//Form submission and validation area
function submitform(f) {
    
    console.log("hiding all submit buttons: ");
    $('.edit-submit-sep').hide();
    //Validation in case of pages
    var form_id_main = $('#form_id').val();
    var page_validation = false;
    var  active_page = '';
    if ($('#pages-header') != undefined) {
        $('ul.pages a').each(function () {
            
            if ($(this).hasClass('active'))
            {
               active_page = $(this).attr('rel');
            }
        });


        console.log("submitted form page id: "+active_page);


        var page_id = active_page;
        if ($('#form-submit').parent().hasClass('pagediv'))
        {
            if (isrequiredednext(page_id))
            {
                page_validation = true;//its true because validation perform on specific page, so no need to validate other pages
            } else {
                return false;
            }
        }
    }


    //AndroidFunction.showAlertDialog(form_icon);
    var form_operation = $('#form_operation').val();
    $('#form-preview').find('[field_setting="main"]').each(function () {
        if ($(this).attr('disabled') == 'disabled')
        {
            $(this).attr('disabled', false);
        }
        if ($(this).attr('readonly') == 'readonly')
        {
            $(this).attr('readonly', false);
        }
    });
    if (form_operation == 'formeditsubmit')//if user click on submit button then this part will execute
    {
        if (isrequirededitsubmit(f, page_validation))
        {
            var form_ser = $(f).serialize();
            var form_values = parse_query(form_ser);
            var myJsonString = JSON.stringify(form_values);//This function will return json {"field1":"zad","field2":"Option+1"}
            //submit form and save record if internal not available

            if ($('#activity_duration_in_seconds').val() != undefined)
            {
                clearInterval(intervalId);
                totalSeconds=0;
                intervalId = setInterval(setTime1, 1000);
                function setTime1()
                {
                    ++totalSeconds;
                    $('#activity_duration_in_seconds').val(totalSeconds);
                    // if ($('#second_count').attr('id') == undefined)
                    //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
                    // $('#second_count').html(totalSeconds+' Seconds');
                }
            }

            AndroidFunction.onSubmitClickAfterEdit(myJsonString);
            AndroidFunction.insertTimeIntoDB(form_id_main, 'form_id', form_id_main + '_submited');
            //AndroidFunction.onGoBackClick();
            //reset picture and location
            //reset_images();
            if ($('#pages-header') != undefined) {
                $('ul.pages li:first a').trigger('click');

            }
            $('.loop-list-main').html('');
            if ($('#tabs-header') != undefined) {
                $('ul.tabs li:first a').trigger('click');

            }
        }
        console.log("showing all submit buttons: ");
        $('.edit-submit-sep').show();
    } else if (form_operation == 'formsave')//if user click on submit button then this part will execute
    {
        if (isrequiredsave(f, page_validation))
        {
            var form_ser = $(f).serialize();
            var form_values = parse_query(form_ser);
            try {
                var deviceTS = AndroidFunction.getDeviceTS();
                form_values['deviceTS'] = deviceTS;
            } catch (err) {

            }

            var myJsonString = JSON.stringify(form_values);//This function will return json {"field1":"zad","field2":"Option+1"}
            //submit form and save record if internal not available

            AndroidFunction.onSaveClick(myJsonString);
            if ($('#activity_duration_in_seconds').val() != undefined)
            {
                clearInterval(intervalId);
                totalSeconds=0;
                intervalId = setInterval(setTime1, 1000);
                function setTime1()
                {
                    ++totalSeconds;
                    $('#activity_duration_in_seconds').val(totalSeconds);
                    // if ($('#second_count').attr('id') == undefined)
                    //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
                    // $('#second_count').html(totalSeconds+' Seconds');
                }
            }
            AndroidFunction.onGoBackClick();
            //reset picture and location
            reset_images();
            $('.loop-list-main').html('');
            if ($('#pages-header') != undefined) {
                $('ul.pages li:first a').trigger('click');

            }
            if ($('#tabs-header') != undefined) {
                $('ul.tabs li:first a').trigger('click');

            }
        }
    } else {//if user click on Save button then this part will execute
        if (isrequiredsubmit(f, page_validation))
        {
            var ele = $('#form-submit');
            if(ele.attr('tracking_status') != undefined){
                if(ele.attr('tracking_status')=='start'){
                    AndroidFunction.startTracking()
                }
                else if(ele.attr('tracking_status')=='stop'){
                    AndroidFunction.stopTracking()
                }
            }
            var form_ser = $(f).serialize();
            var form_values = parse_query(form_ser);
            var myJsonString = JSON.stringify(form_values);//This function will return json {"field1":"zad","field2":"Option+1"}
            //submit form and save record if internal not available
            if ($('#activity_duration_in_seconds').val() != undefined)
            {
                clearInterval(intervalId);
                totalSeconds=0;
                intervalId = setInterval(setTime1, 1000);
                function setTime1()
                {
                    ++totalSeconds;
                    $('#activity_duration_in_seconds').val(totalSeconds);
                    // if ($('#second_count').attr('id') == undefined)
                    //     $('#form-title').after('<div id="second_count" style="float: right;">'+totalSeconds+' seconds</div>');
                    // $('#second_count').html(totalSeconds+' Seconds');
                }
            }
            AndroidFunction.onSubmitClick(myJsonString);
            AndroidFunction.insertTimeIntoDB(form_id_main, 'form_id', form_id_main + '_submited');
           
            AndroidFunction.onGoBackClick();
            //reset picture and location
            reset_images();
            $('.loop-list-main').html('');
            if ($('#pages-header') != undefined) {
                $('ul.pages li:first a').trigger('click');
                ;
            }
            if ($('#tabs-header') != undefined) {
                $('ul.tabs li:first a').trigger('click');

            }

        }
         console.log("showing all submit buttons: ");
        $('.edit-submit-sep').show();
    }
    return false;
}


/**
 * 
 * @returns {boolean}
 * To validate the form fields in case of sumbit form
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function isrequirededitsubmit(f, page_validation) {

//If Page already validate then page_validation will be true, no need to further validation
    if (!page_validation)
    {
        for (var i = 0; i < f.elements.length; i++) {
            var e = f.elements[i];
            var field_label = '';
            if (e.getAttribute('editable')) {
                if (e.getAttribute('subtable') != undefined || (e.getAttribute('subtable_id') != undefined && e.getAttribute('subtable_id') != ''))
                {
                    //return true;
                } else
                {
                    if (e.required && e.value == '' && $('#' + e.id + '-container').css('display') != 'none')
                    {
                        //show required message
                        if ($('#' + e.id).is('select'))
                        {
                            field_label = $('#' + e.id).parent().prev().text();
                        } else
                        {
                            field_label = $('#' + e.id).prev().text();
                        }
                        if (field_label == '')
                        {
                            field_label = e.name;
                            field_label = field_label.replace(/\+/g, ' ');
                        }
                        var message = field_label + ' required';
                        AndroidFunction.showAlertDialog(message);
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;

                    }
                    if (e.value != '') {
                        if (!isValidTime($('#' + e.id), e.value)) {
                            console.log("showing all submit buttons: ");
        		    $('.edit-submit-sep').show();
                            return false;
                        }
                        if (!isNumericValue($('#' + e.id), e.value)) {
                            console.log("showing all submit buttons: ");
        		    $('.edit-submit-sep').show();
                            return false;
                        }
                        if (!lengthValidation($('#' + e.id), e.value)) {
                            console.log("showing all submit buttons: ");
        		    $('.edit-submit-sep').show();
                            return false;
                        }
                        if (!minMaxValidation($('#' + e.id), e.value)) {
                            console.log("showing all submit buttons: ");
        		    $('.edit-submit-sep').show();
                            return false;
                        }
                    }
                }
            }
        }
    }

    return true;
}
/**
 * 
 * @returns {boolean}
 * To validate the form fields in case of sumbit form
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function isrequiredsubmit(f, page_validation) {

    //If Page already validate then page_validation will be true, no need to further validation
    if (!page_validation)
    {
        for (var i = 0; i < f.elements.length; i++) {
            var e = f.elements[i];
            var field_label = '';
            if (e.getAttribute('subtable_id') != undefined && e.getAttribute('subtable_id') != '')
            {
                //return true;
            } else if (e.getAttribute('subtable') != undefined && e.getAttribute('subtable') == 'true')
            {
                var containter_loop_id = e.getAttribute('loop_id');
                if (e.required && $('#loop-list-' + containter_loop_id).html() == '') {
                    field_label = $('#' + e.id).prev().text();
                    var message = field_label + ' required';
                    AndroidFunction.showAlertDialog(message);
                    return false;
                }
                //return true;
            } else {
                if (e.required && $('#' + e.id + '-container').css('display') != 'none')
                {
                    if (e.value == '')
                    {
                        //show required message
                        if ($('#' + e.id).is('select'))
                        {
                            field_label = $('#' + e.id).parent().prev().text();
                        } else
                        {
                            field_label = $('#' + e.id).prev().text();
                        }
                        if (field_label == '')
                        {
                            field_label = e.name;
                            field_label = field_label.replace(/\+/g, ' ');
                        }
                        var message = field_label + ' required';
                        AndroidFunction.showAlertDialog(message);
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;
                    }
                    
                }
                if (e.value != '' && $('#' + e.id + '-container').css('display') != 'none')
                {
                    if (!isValidTime($('#' + e.id), e.value)) {
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;
                    }
                    if (!isNumericValue($('#' + e.id), e.value)) {
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;
                    }
                    if (!lengthValidation($('#' + e.id), e.value)) {
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;
                    }
                    if (!minMaxValidation($('#' + e.id), e.value)) {
                        console.log("showing all submit buttons: ");
        		$('.edit-submit-sep').show();
                        return false;
                    }
                }

            }

        }
    }
    
    if($('#location_required').val() != undefined && $('#location_required').val() == '0'){
        return true;
    }
    var location = AndroidFunction.isLocationAvailable();
    if (!location)
    {
        AndroidFunction.showAlertDialog('Location not found, please try again later');
        console.log("showing all submit buttons: ");
        $('.edit-submit-sep').show();
        return false;
    }
    
    return true;
}

/**
 * 
 * @returns {boolean}
 * To validate the form fields in case of save form
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function isrequiredsave(f, page_validation) {

//If Page already validate then page_validation will be true, no need to further validation
    if (!page_validation)
    {
        for (var i = 0; i < f.elements.length; i++) {
            var e = f.elements[i];
            var field_label = '';
            if (!e.getAttribute('editable')) {
                if (e.getAttribute('subtable_id') != undefined && e.getAttribute('subtable_id') != '')
                {
                    //return true;
                } else if (e.getAttribute('subtable') != undefined && e.getAttribute('subtable') == 'true')
                {
                    var containter_loop_id = e.getAttribute('loop_id');
                    if (e.required && $('#loop-list-' + containter_loop_id).html() == '') {
                        field_label = $('#' + e.id).prev().text();
                        var message = field_label + ' required';
                        AndroidFunction.showAlertDialog(message);
                        return false;
                    }
                    //return true;
                } else {

                    if (e.getAttribute('required') && $('#' + e.id + '-container').css('display') != 'none')
                    {
                        if (e.value == '')
                        {
                            //show required message
                            if ($('#' + e.id).is('select'))
                            {
                                field_label = $('#' + e.id).parent().prev().text();
                            } else
                            {
                                field_label = $('#' + e.id).prev().text();
                            }
                            if (field_label == '')
                            {
                                field_label = e.name;
                                field_label = field_label.replace(/\+/g, ' ');
                            }
                            var message = field_label + ' required';
                            AndroidFunction.showAlertDialog(message);
                            return false;
                        }
                    }
                    if (e.value != '' && $('#' + e.id + '-container').css('display') != 'none')
                    {
                        
                        if (!isValidTime($('#' + e.id), e.value)) {
                            return false;
                        }
                        if (!isNumericValue($('#' + e.id), e.value)) {
                            return false;
                        }
                        if (!lengthValidation($('#' + e.id), e.value)) {
                            return false;
                        }
                        if (!minMaxValidation($('#' + e.id), e.value)) {
                            return false;
                        }
                    }
                }

            }
        }
    }
    if($('#location_required').val() != undefined && $('#location_required').val() == '0'){
        AndroidFunction.showAlertDialog('Location setting is off in this application.');
        return true;
    }
    var location = AndroidFunction.isLocationAvailable();
    if (!location)
    {
        AndroidFunction.showAlertDialog('Location not found, please try again later');
        return false;
    }
    return true;
}


/**
 * 
 * @returns {parse_array}
 * TO parse the string of form
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function parse_query(querystring,encription)
{
    
    if(encription==undefined || encription == null){
        encription = true;
    }
    
    var parse_array = {};
    var take_image = false;
    var field_name = '';
    var security_key = '';
    var row_key_value = '';
    var caption_sequence = '';
    var field_val = '';
    var row_key = [];
    try {
        row_key = $('#row_key').attr('column_name');
        row_key = row_key.split(',');
        $.each(row_key, function (k, va) {
            if($('[name="' + va + '"]').val() != undefined){
                field_val = $('[name="' + va + '"]').val();
                va = va.replace('_', ' ');
                if(row_key_value == ''){
                    row_key_value+=va+': '+field_val;
                }else{
                    row_key_value+=', '+va+': '+field_val;
                }
                
                field_val = '';
            }
        });
    } catch (err) {
    }
    
    querystring = querystring.split('&');
    console.log(querystring);
    $.each(querystring, function (i, v) {
        field_name = '';
               
        var field_value = v.split('=');
        if (field_value[0] == 'caption_sequence') {
            caption_sequence = field_value[1];       
        }else if (field_value[0] == 'security_key') {
            security_key = field_value[1];
        } else if (field_value[0] == 'takepic')
        {
            //AndroidFunction.showAlertDialog('Take Pic='+field_value[1]);
            if (field_value[1] == 'takepicture') {
                take_image = true;
            }
        } else
        {

            if ($('[name="' + field_value[0] + '"]').attr('subtable') != undefined && $('[name="' + field_value[0] + '"]').attr('subtable') == 'true') {
                console.log("In loop element parse query = "+field_value[0]+", value = "+field_value[1]);
                var loop_id = $('[name="' + field_value[0] + '"]').attr('loop_id');
                var field_name_loop = field_value[0];

                parse_array[field_name_loop] = [];
                $('#loop-list-' + loop_id).find('input').each(function () {

                    parse_array[field_name_loop].push($(this).val());

                });

            } else if ($('[name="' + field_value[0] + '"]').attr('rel') != undefined && $('[name="' + field_value[0] + '"]').attr('rel') == 'skip' || $('[name="' + field_value[0] + '"]').attr('subtable_id') != undefined && $('[name="' + field_value[0] + '"]').attr('subtable_id') != '')
            {
                console.log("Skip the element parse query= "+field_value[0]+", value = "+field_value[1]);
                //skip the element parsing
            } else
            {
                console.log("In default case parse query = "+field_value[0]+", value = "+field_value[1]);
                field_name = field_value[0].replace('%5B%5D', '');
                var isTrue = false;
                $.each(parse_array, function (key, value) {
                    if (field_name == key)
                    {
                        isTrue = true;
                    }
                });
                if (isTrue)
                {
                    if (field_value[1] == '') {

                    } else if (parse_array[field_name] == '' || parse_array[field_name] == field_value[1])
                    {
                        parse_array[field_name] = field_value[1];
                    } else {
                        parse_array[field_name] = parse_array[field_name] + ',' + field_value[1];
                    }

                } else
                {
                    parse_array[field_name] = field_value[1];
                }
            }
        }
    });
//    if ($('#takepic').val() == 'takepicture')
//    {
//        take_image = true;
//    }
    parse_array['security_key'] = security_key;
    parse_array['is_take_picture'] = take_image;
    

    var encrypt_array;
    if(encription){
        encrypt_array = encrypt_record(parse_array, security_key);
    
    }
    else{
        encrypt_array = parse_array;
    }
    encrypt_array['landing_page'] = pathcurfile;
    encrypt_array['row_key'] = row_key_value;
    encrypt_array['caption_sequence'] = caption_sequence;
    var form_icon_name = '';
    if ($('#form-title').find('.formobile').attr('src') != undefined)
    {
        form_icon_name = $('#form-title').find('.formobile').attr('src');
    }

    encrypt_array['form_icon_name'] = form_icon_name;
    return encrypt_array;
}


/**
 * 
 * @returns {undefined}
 */
function reset_images()
{

    $('#form-preview')[0].reset();
    $('#form-preview').find('img').each(function () {
        if ($(this).attr('class') == 'formobile')
        {
            $(this).attr("src", "takepicture.png");
        }
    });

    $('#form-preview').find('input[type="hidden"]').each(function () {
        if ($(this).attr('android_field') != undefined)
        {
            $(this).val('');
        }
    });
    $('#takepic').val('');

    $('#form-preview').find('[field_setting="main"]').each(function () {
        if ($(this).is('select')) {
            $(this).prev().html('Please Select');
        }
    });

}

function encrypt_record(encrypt_array, security_key)
{
    var return_parsed_array = {};
    $.each(encrypt_array, function (i, v) {

        if (i == 'form_id' || i == 'is_take_picture' || i == 'security_key' || $('[name="' + i + '"]').attr('subtable') != undefined && $('[name="' + i + '"]').attr('subtable') != '')
        {
            return_parsed_array[i] = v;
        } else
        {
            return_parsed_array[i] = security_key + Base64.encode(v);
        }


    });
    return return_parsed_array;
}


/**
 * 
 * @returns {undefined}
 * TO take the Picture by 
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function take_picture(pic_id)
{
    AndroidFunction.takePicture(pic_id);
}

/**
 * 
 * @returns {undefined}
 * TO take the time by 
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function take_time(time_id)
{
    var form_id_main = $('#form_id').val();
    if ($('.taketimeclass').find('input:first').attr('time_taken') == undefined || $('.taketimeclass').find('input:first').attr('time_taken') == 'multiple') {
        AndroidFunction.removeTimeData('');
        AndroidFunction.removeTimeData(form_id_main + '_submited');
    }
    //AndroidFunction.removeTimeData('');
    var recordExist = AndroidFunction.checkTimeStatus(time_id);
    //AndroidFunction.showAlertDialog(recordExist);
    //if() date is not old and status is once then remove
    //AndroidFunction.removeTimeData('once');
    var savestate = '';
    if (recordExist)
    {
        //var dateString = '31.678364,72.45443#2015:03:11 00:00:00#once';

        var dateString = recordExist;
        var dateStrParts = dateString.split('#');
        var dateStrParts_sep = dateStrParts[1].split(' ');
        var date_old = dateStrParts_sep[0].replace(/\:/g, '/');
        var saved_date = new Date(date_old);


        //savestate = dateStrParts[2];//'once' and 'multiple'

        var current_date = new Date(Date.now());

        if (current_date.getDate() !== saved_date.getDate())
        {
            //empty database table if saved record is old dated
            AndroidFunction.removeTimeData('');
            recordExist = AndroidFunction.checkTimeStatus(time_id);
        }
    }
    if (recordExist)
    {

        var old_time = recordExist.split('#');
        $('#' + time_id).val(old_time[1]);//datetime string
        $('#' + time_id + '_location').val(old_time[0]);//location string
        try {
            $('#' + time_id + '_source').val(old_time[2]);//location string
        } catch (err) {

        }

        AndroidFunction.showAlertDialog('You have already performed ' + $('#' + time_id).attr('name').replace(/[^a-zA-Z 0-9]+/g, " "));
        //reassign the time from database
        return false;

    } else {
        var action_before = '';
        if ($('#' + time_id).attr('action_before') != undefined && $('#' + time_id).attr('action_before') != '')
        {
            action_before = $('#' + time_id).attr('action_before');
            if ($('#' + action_before).val() == '') {
                AndroidFunction.showAlertDialog('You should perform ' + $('#' + action_before).attr('name').replace(/[^a-zA-Z 0-9]+/g, " ") + ' before this');
                return false;
            }
        }
        AndroidFunction.takeTime(time_id);
    }
}

/**
 * 
 * @returns {undefined}
 * After time taken, time will return and put into time hidden
 * Auth:Ubd
 */
function timeTaken(time_id, time_value, time_source)
{
    trackerCall($('#' + time_id));
    time_source = typeof time_source !== 'undefined' ? time_source : '';
//    var timeobj = document.getElementById(time_id);
//    timeobj.setAttribute('value', time_value);
    var location = AndroidFunction.myCurrentLocation();//getting current best location from GPS or Network
    AndroidFunction.insertTimeIntoDB(time_id, location + '#' + time_value + '#' + time_source, '');
    $('#' + time_id).val(time_value);
    $('#' + time_id + '_location').val(location);
    try {
        $('#' + time_id + '_source').val(time_source);
    } catch (err) {

    }
}

/**
 * 
 * @returns {undefined}
 * Generate random key
 * Auth:Zahid Nadeem
 */
function generate_random_key(element_id)
{
    if($('#' + element_id).val() == ''){
        var random_num = generateUUID();
        $('#' + element_id).val(random_num);
        //$('#' + element_id+'-container a').attr('onClick','');
        //$('#' + element_id+'-container a label').text(random_num);
    }

}

function generateUUID() {
    var text = "";
    var num = "";
    var possible = "ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghjklmnopqrstuvwxyz";
    var possible1 = "123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    for( var i=0; i < 2; i++ )
        num += possible1.charAt(Math.floor(Math.random() * possible1.length));


    var d = new Date().getTime();
    var uuid = 'xxxxx'.replace(/[xy]/g,function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x7|0x8)).toString(16);
    });

    uuid =uuid+"-"+text+"-"+num; 
    return uuid.toUpperCase();
}

/**
 * 
 * @returns {undefined}
 * TO scan CNIC 
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function scan_cnic()
{
    AndroidFunction.OnScanIDCard();
//call here android function for scann cnic    
}
/**
 * 
 * @returns {undefined}
 * TO load the scanned cnic data to fields
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function afterScanCnic(json_val)
{
    json_val = json_val.replace(/\u0000/g, '');

    //$('#IDno').val(json_val);
    //var json_val = '{"IDno":"31202-0252801-1","Name":"zahid","Father_Name":"M. Sarwar Nadeem","Family_No":"FRt3e1P","Date_Of_Birth":"25/01/1982","Address":"132-kv grid station wapda colony","District":"Bahawalpur","City":"Bahawalpur"}';
    var results = JSON.parse(json_val);
    $(results).each(function (i, val) {
        $('#IDno').val(val.IDno);
        $('#Name').val(val.Name);
        $('#Father_Name').val(val.Father_Name);
        $('#Family_No').val(val.Family_No);
        $('#Date_Of_Birth').val(val.Date_Of_Birth);
        $('#Address').val(val.Address);
        $('#District').val(val.District);
        $('#City').val(val.City);

    });
}

/**
 * 
 * @returns {undefined}
 * TO take the lat and longitue by 
 * Auth:Ubd
 */
function take_latlong(locationid)
{
    AndroidFunction.onTakeLocationCLick();
    $('#' + locationid).val('yes');
}

/**
 * 
 * @returns {undefined}
 * TO take the picture data 
 * Auth:Ubd
 */
function pictureTaken(pic_id, path)
{
    
    var image1 = document.getElementById(pic_id);
    image1.setAttribute('src', path);
    var hiddenid = $('#' + pic_id).attr('name');
    trackerCall($('#' + hiddenid));
    
    if($('#caption_sequence').val()!=undefined)
    {
        var c = $('#caption_sequence').val();
        if(c == '')
        {
            $('#caption_sequence').val('caption-'+hiddenid);
        }
        else
        {
            var capseq_array = c.split(",");
            if( $.inArray('caption-'+hiddenid, capseq_array) == -1 ) {
                capseq_array.push('caption-'+hiddenid);
                $('#caption_sequence').val(capseq_array.join(','));
            }
            
        }
    }

    $('#' + hiddenid).val('yes');
    $('#takepic').val('takepicture');

}

/**
 * 
 * @param {type} path
 * @returns {undefined}
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function unsentData()
{
    AndroidFunction.onUnsentDataClick();
}
/**
 * 
 * @param {type} path
 * @returns {undefined}
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function savedData()
{
    console.log('in function saved');
    AndroidFunction.showOnlyUnsentAndNotSaved();
}
/**
 * 
 * @param {type} path
 * @returns {undefined}
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function savedDataNot()
{
    console.log('in function saved Not');
    AndroidFunction.showOnlySavedAndNotUnsent();
}

/**
 * 
 * @returns {undefined}
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */
function refreshData()
{
    if ($('#upgrade_from_google_play').val() != undefined && $('#upgrade_from_google_play').val() == 1) {
        AndroidFunction.onRefreshDataClick(true);
    } else {
        AndroidFunction.onRefreshDataClick(false);
    }
    AndroidFunction.onGoBackClick();
}
/**
 * 
 * @returns {undefined}
 * @author Zahid Nadeem <zahidiubb@yahoo.com>
 */

function aboutApp()
{
    var aboutinfo = AndroidFunction.getAboutData();
    AndroidFunction.showAlertDialog('Application Id : ' + aboutinfo.split('_')[0] + '\nVersion : ' + aboutinfo.split('_')[1] + '\nVersion Code : ' + aboutinfo.split('_')[2]);
}


function linkfield(element_id)
{
    var selected_value = $('#linked_field-' + element_id + ' option:selected').val();
    if (selected_value == 'custom')
    {
        $('#div_caption-' + element_id).empty();
        $('#div_caption-' + element_id).append('<input type="text" rel="norepeat" name="caption-' + element_id + '" placeholder="Enter image title here" class="form-control"/>');
    } else {
        var caption_selected = get_caption_value_selected_element(selected_value);
        $('#div_caption-' + element_id).empty();
        $('#div_caption-' + element_id).append('<input type="text" readonly rel="norepeat" name="caption-' + element_id + '" value="' + caption_selected + '" class="form-control"/>');
    }
}

function get_caption_value_selected_element(selected_element) {
    var final_value = '';
    $('#' + selected_element + '-container').find('[field_setting="main"]').each(function () {
        if ($(this).is(':checkbox')) {
            if ($(this).is(':checked')) {
                final_value += $(this).val() + ',';
            }
        } else if ($(this).is(':radio')) {
            if ($(this).is(':checked')) {
                final_value += $(this).val() + ',';
            }
        } else if ($(this).is('option')) {
            if ($(this).prop("selected") == true) {
                final_value += $(this).val() + ',';
            }
        } else {
            final_value += $(this).val() + ',';
        }
    });
    final_value = final_value.substring(0, final_value.length - 1);
    return final_value;
}


function add_photo(element_id_img) {
    // if ($('.cameraclass').length > 4)
    // {
    //     $('.addPhotoClass').hide();
    //     //AndroidFunction.showAlertDialog('Widget added only five time in a form');
    //     return;
    // }
    var count_field = countField();
    var new_field = count_field + 1;

    
    var html_old_element = $('#' + element_id_img + '-container').prop('outerHTML').toString();

    var pattern = new RegExp(element_id_img, 'g');
    html_old_element = html_old_element.replace(pattern, "field" + new_field);
    $('#' + element_id_img + '-container').after(html_old_element);
    $('#picselect-field' + new_field).attr('src','takepicture.png');
    $('#remove_photo_field'+new_field).remove();
    var remove_image = '<div id="remove_photo_field'+new_field+'" class="removePhotoClass" onclick="remove_photo('+new_field+');" ><i class="glyphicon glyphicon-camera pull-center"></i>  Remove Photo</div>';
    $('#add_photo_field' + new_field).after(remove_image);
}
function remove_photo(element_id_img) {

    $('.addPhotoClass').show();
    $('#field' + element_id_img + '-container').remove();
}

function countField()
{
    var i = 1;
    $(".field").each(function () {
        if ($('#field' + i + '-container').length == 0)
        {
            return i - 1;
        }
        i++;
    });
    return i - 1;
}
/**
 *
 *  Base64 encode / decode
 *  http://www.webtoolkit.info/
 *
 **/
var Base64 = {
    // private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    // public method for encoding
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    // public method for decoding
    decode: function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    // private method for UTF-8 encoding
    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    },
    // private method for UTF-8 decoding
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
};