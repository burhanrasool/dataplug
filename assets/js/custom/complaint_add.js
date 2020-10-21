    
    $('.comp_types').hide();
    $('.comp_sub_types').hide();
    $('#app_id').change(function() {
        var app_id = $(this).val();
        if(app_id){
            $('#cnic_lable').show();
        }
        else{
            $('#cnic_lable').hide();
        }
    });

    $('#cnic').keyup(function() {

        var app_id = $('#app_id').val();
        var cnic = $('#cnic').val();
        $.ajax({
            method: "POST",
            data: {app_id: app_id,cnic: cnic},
            url: Settings.base_url+"complaint/get_app_user_ajax",
            success: function(response) {
                var response = $.parseJSON(response);
                if(response.status){

                    var user_data = response.user_rec;
                    $('#app_user_id').val(user_data.user_id);
                    $('#old_user_name').val(user_data.user_name);
                    $('#old_imei_no').val(user_data.imei_no);
                    $('#old_mobile_number').val(user_data.mobile_number);
                    $('#old_district').val(user_data.district);
                    $('#app_user_li').show();
                    $('#app_user_li_error').hide();
                    $('#complaint_type_div').show();
                    $('#add_complaint_button').show();
                }
                else{
                    $('#app_user_li').hide();
                    $('#app_user_li_error').show();
                    $('#complaint_type_div').hide();
                }
            },
            async: false

        });
    });

    $('#complaint_type').change(function() {
        var complaint_type = $(this).val();
        $('.comp_types').hide();
        $('.comp_sub_types').hide();
        $('.comp_types :checkbox').attr('checked',false);
        if(complaint_type=='Telecom'){
            $('.Telecom').show();
            $('.Other').show();
        }
        else if(complaint_type=='Application'){
           $('.Application').show();
           $('.Other').show();
        }
        else if(complaint_type=='Dashboard'){
            $('.Dashboard').show();
            $('.Other').show();
        }
        else if(complaint_type=='Other'){
            //$('.Other').show();
            $('.Other').show();
        }
        else{
            $('.comp_sub_types').hide();
            $('.Other').hide();
        }
    });

    $('.comp_types :checkbox').change(function() {

        if($(this).val()=='Duplicate SIM'){
            if($(this).attr('checked')){
                $('.dup_sim').show();
            }
            else{
                $('.dup_sim').hide();
            }
        }

        if($(this).val()=='Internet & Balance Issue'){
            if($(this).attr('checked')){
                $('.int_bal_issue').show();
            }
            else{
                $('.int_bal_issue').hide();
            }
        }

        
        if($(this).val()=='Signal Problem'){
            if($(this).attr('checked')){
                $('.signal_problem').show();
            }
            else{
                $('.signal_problem').hide();
            }
        }

        
        if($(this).val()=='Balance Deduction'){
            if($(this).attr('checked')){
                $('.balance_deduction').show();
            }
            else{
                $('.balance_deduction').hide();
            }
        }
        
        if($(this).val()=='Sim Mapping/Activation'){
            if($(this).attr('checked')){
                $('.sim_mapping_activation').show();
            }
            else{
                $('.sim_mapping_activation').hide();
            }
        }

        if($(this).val()=='Ownership Change'){
            if($(this).attr('checked')){
                $('.ownership_change').show();
            }
            else{
                $('.ownership_change').hide();
            }
        }
        
        if($(this).val()=='User Status Change'){
            if($(this).attr('checked')){
                $('.user_status_change').show();
            }
            else{
                $('.user_status_change').hide();
            }
        }
        
        if($(this).val()=='IMEI Update'){
            if($(this).attr('checked')){
                $('.imei_update').show();
            }
            else{
                $('.imei_update').hide();
            }
        }        

        if($(this).val()=='Mark Leave'){
            if($(this).attr('checked')){
                $('.mark_leave').show();
            }
            else{
                $('.mark_leave').hide();
            }
        }        

        if($(this).val()=='Login Credentials Issues'){
            if($(this).attr('checked')){
                $('.login_credentials_change').show();
            }
            else{
                $('.login_credentials_change').hide();
            }
        }        

        if($(this).val()=='User Transferred'){
            if($(this).attr('checked')){
                $('.user_transfered').show();
            }
            else{
                $('.user_transfered').hide();
            }
        }        

        if($(this).val()=='Dashboard Not Working'){
            if($(this).attr('checked')){
                $('.dashboard_not_working').show();
            }
            else{
                $('.dashboard_not_working').hide();
            }
        }        

        if($(this).val()=='User Showing Absent on dashboard'){
            if($(this).attr('checked')){
                $('.showing_absent_dashboard').show();
            }
            else{
                $('.showing_absent_dashboard').hide();
            }
        }        

        if($(this).val()=='Activities Missing'){
            if($(this).attr('checked')){
                $('.activities_missing').show();
            }
            else{
                $('.activities_missing').hide();
            }
        }        

        if($(this).val()=='Data Not Showing'){
            if($(this).attr('checked')){
                $('.data_not_showing').show();
            }
            else{
                $('.data_not_showing').hide();
            }
        }

        if($(this).val()=='App not Working'){
            if($(this).attr('checked')){
                $('.app_not_working').show();
            }
            else{
                $('.app_not_working').hide();
            }
        }        

        if($(this).val()=='App Crash/Foreced Stopped'){
            if($(this).attr('checked')){
                $('.app_crashed').show();
            }
            else{
                $('.app_crashed').hide();
            }
        }        

        if($(this).val()=='APK Required'){
            if($(this).attr('checked')){
                $('.apk_required').show();
            }
            else{
                $('.apk_required').hide();
            }
        }       

        if($(this).val()=='Unautherized User'){
            if($(this).attr('checked')){
                $('.unautherized_user').show();
            }
            else{
                $('.unautherized_user').hide();
            }
        }       

        if($(this).val()=='Other'){
            if($(this).attr('checked')){
                $('.other_comments').show();
            }
            else{
                $('.other_comments').hide();
            }
        }
    });

function submit_add_complaint(){

    if($('#complaint_type').val() == ''){
        alert('Please select complaint type');
        return false;
    }  

    var checked_complaint_type = $('.comp_types :checkbox:checked').map(function () {
            return this.value;
        }).get();

    if(checked_complaint_type.length < 1){
        alert('Please select at least one complaint type');
        return false;
    }

    if($('#signal_problem_checkbox').attr('checked')){
        if($('#signal_problem_district').val()==''){

            alert('Please select district in signal problem issue');
            return false;
        }
    }     
    
    if($('#ownership_change_checkbox').attr('checked')){

        var ownership_change_value = $('#ownership_cnic').val();
        if($('#ownership_cnic').val()==''){

            alert('CNIC # should not be empty or alphabet');
            return false;
        }
        if(ownership_change_value.length!=13){

            alert('CNIC # should be 13 digits');
            return false;
        }
    }    

    if($('#imei_update_checkbox').attr('checked')){

        var imei_update = $('#imei_update').val();
        if($('#imei_update').val()==''){

            alert('IMEI # should not be empty or alphabet');
            return false;
        }
        if(imei_update.length!=15){

            alert('IMEI # should be 15 digits');
            return false;
        }
    }    

    if($('#unautherized_user_checkbox').attr('checked')){

        var unautherized_user_imei = $('#unautherized_user_imei').val();
        if($('#unautherized_user_imei').val()==''){

            alert('IMEI # should not be empty or alphabet');
            return false;
        }
        if(unautherized_user_imei.length!=15){

            alert('IMEI # should be 15 digits');
            return false;
        }
    }

    if($('#user_transfered_checkbox').attr('checked')){
        if($('#transfered_district').val()==''){

            alert('Please select district in transfered user');
            return false;
        }
    }
    
   $('#add_c_form').submit();
}