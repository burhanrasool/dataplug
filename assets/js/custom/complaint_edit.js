    var old_status='';
    var selected_status ='';
    $('#c_status').change(function() {
        var selected_status = $(this).val();
        old_status = Settings.old_status;
        if(selected_status != old_status)
        {
            $('#c_description').show();
            $('#submit_change_status').show();
        }
        else{
            $('#c_description').hide();
            $('#submit_change_status').hide();
        }

    });