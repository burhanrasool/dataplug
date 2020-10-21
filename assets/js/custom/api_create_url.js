var api_secret = $('#api_secret').val();
        var bas_url = Settings.base_url+'api/getoptionapi?api='+Settings.api_id+'&secret='+api_secret;
    
        $('#urldiv').click(function () {
            //alert('zahid');
            $(this).focus();
        });
        $('#mainvalue').change(function () {
            var para_ex = '';
            if($(this).val()!=''){
                para_ex = '&value='+$(this).val();

                if($('#parentvalue').val()!='')
                {
                    para_ex += '&parent_value='+$('#parentvalue').val();
                }
                if($('#sortvalue').val()!='')
                {
                    para_ex += '&sort='+$('#sortvalue').val();
                }
            }
            else{ 
                $('#urldiv').html(''); 
                $('#checkurl').hide();
                $('#checkurl').attr('href',bas_url+para_ex);
                $('#parentvalue').val('');
                $("#parentvalue option:first").attr('selected','selected');
                return false;
            }

            $('#checkurl').show();
            $('#checkurl').attr('href',bas_url+para_ex);
            $('#urldiv').html(bas_url+para_ex);
        });
        $('#parentvalue').change(function () {
            var para_ex = '';
            if($('#mainvalue').val()!=''){
                para_ex = '&value='+$('#mainvalue').val();
                if($(this).val()!='')
                {
                    para_ex += '&parent_value='+$(this).val();

                }
                if($('#sortvalue').val()!='')
                {
                    para_ex += '&sort='+$('#sortvalue').val();
                }
                $('#urldiv').html(bas_url+para_ex);
                $('#checkurl').show();
                $('#checkurl').attr('href',bas_url+para_ex);
            }else{
                    $('#checkurl').hide();
                    alert('Please must select Value if you want Parent Value');
                    $('#parentvalue').val('');
                    $("#parentvalue option:first").attr('selected','selected');
                    return false;
                }

        });
        $('#sortvalue').change(function () {
            var para_ex = '';
            
            if($('#mainvalue').val()!='')
            {
                para_ex = '&value='+$('#mainvalue').val();
            }
            if($('#parentvalue').val()!='')
            {
                para_ex += '&parent_value='+$('#parentvalue').val();
            }
            para_ex += '&sort='+$(this).val();
            
            if($('#mainvalue').val()!=''){
                $('#urldiv').html(bas_url+para_ex);
                $('#checkurl').show();
                $('#checkurl').attr('href',bas_url+para_ex);
            }
            

        });

        function select_all(el) {
            if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
                var range = document.createRange();
                range.selectNodeContents(el);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            } else if (typeof document.selection != "undefined" && typeof document.body.createTextRange != "undefined") {
                var textRange = document.body.createTextRange();
                textRange.moveToElementText(el);
                textRange.select();
            }
        }