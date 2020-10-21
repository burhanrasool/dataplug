//Start ready body
$(document).ready(function() {
	if(!$('#form-title').hasClass('navbar'))
	{
	    var old_title = $('#form-title').find('h2').html();
	    $('#form-title').remove();
	    $("#add-form-title li").trigger('click');
	    $('#form-title').find('.navbar-brand').html(old_title);
	}
	//add outer body of form
	if(!$('#form-preview').parent().hasClass('form-hd')){
	    $( "#form-preview" ).wrap( "<div class='form-hd'></div>" );
	    $( ".form-hd" ).wrap( "<div class='col-lg-12'></div>" );
	    $( ".col-lg-12" ).wrap( "<div class='row'></div>" );
	}
	
	setTimeout(function() {
	    $("#add-form-title li").trigger('click');
	}, 500);

	//Change app and form icon path according to web and mobile
	if($('#form-title img.formobile').attr('src_mobile')!=undefined){
        var web_url = $('#form-title img.formobile').attr('src_web');
        $('#form-title img.formobile').attr('src',web_url);
        $('#form-preview .landing_form_icon').find('.formobile img').each(function () {
        	var web_url = $(this).attr('src_web');
        	$(this).attr('src',web_url);
        });
    }
	
	
	if(Settings.description == '')
	{
	    setTimeout(function() {
	        $("#saveForm").trigger('click');
	    }, 550);
	}
	
	$('#saveForm ,#create_application').click(function(event) {
	
	    event.preventDefault();
	    
	    $('#form-preview').find('select').each(function () {
	        var element_id = $(this).attr('id');
	        if ($(this).attr('parent_id') != undefined && $(this).attr('parent_id') != '') {
	            var new_select = '<select id="' + element_id + '_temp" style="display:none">';
	
	            $('#' + element_id).find('option').each(function () {
	                new_select += '<option  id="' + $(this).attr('id') + '" parent_value="' + $(this).attr('parent_value') + '" value="' + $(this).attr('value') + '" display_value="' + $(this).attr('display_value') + '">' + $(this).attr('display_value') + '</option>';
	            });
	            new_select += '</select>';
	            $('#' + element_id + '_temp').remove();
	            $('#' + element_id).parent().append(new_select);
	            $('#' + element_id + '_temp').hide();
	        }
	        else{
	            if ($('#' + element_id + '_temp').length > 0) {
	                $('#' + element_id + '_temp').remove();
	            }
	        }
	    });

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
	        $('#form-preview .landing_form_icon').find('.formobile img').each(function () {
        		var mob_url = $(this).attr('src_mobile');
	        	$(this).attr('src',mob_url);
	        });
	    }
	    
	    var data = $('#form-builder').html();
	    $.post(Settings.base_url+'app-landing-page/'+Settings.app_id,{
	        apphtml: data,
	        app_id: Settings.app_id,
	        success: function() {
	
	            $("#app_edit").submit();
	        }
	    });
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
	
	$('#view_id').change(function() {
	    $("#app_view").submit();
	});
	
	$('#create_application').click(function() {
	    $('#overlay_loading').show();
	});
	loading_image();



    $('.delete_form').live('click', function() {
        if (confirm('Are you sure you want to delete this Form?')) {
            var form_id = $(this).attr('form_id');
            $.ajax({
                url: Settings.base_url+"form/delete/" + form_id,
                type: 'POST',
                success: function(data) {
                	console.log('delete form');
                    window.location.reload();
                },
            });
        } else {
            return true;
        }
    });
});

            
//end of ready body


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
function check_file_splash(ele) {

 var _URL = window.URL || window.webkitURL;
//console.log('zkkdjskfd');
    var image, file,wid,hei;
    if ((file = ele.files[0])) {
        image = new Image();
        
        image.onload = function () {
            wid = this.width;
            hei = this.height;

            str = document.getElementById('splashfile').value.toUpperCase();
            suffix = ".png";
            suffix2 = ".PNG";

            if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                    str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                alert('File type not supported, only (.png) files allowed.');
                document.getElementById('splashfile').value = '';
            }
            else if(wid < 720 || hei < 1280){
                        alert("You Image is smaller. Size should be (720x1280)");
                        document.getElementById('splashfile').value = '';
                        //return false;
            }
            else{
                readURLappSplash(document.getElementById('splashfile'));
            }
        };
         image.src = _URL.createObjectURL(file);
    }
}
function readURLapp(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#img_app_icon').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function readURLappSplash(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            
            $('#img_splash_screen').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(window).load(function() {
    $('#overlay_loading').hide();
});

setTimeout(function() {
    $("#screen_size").trigger('change');
}, 1000);

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


function loading_image() {
    $(function() {
        var docHeight = $(document).height();
        $("body").append('<div  id="overlay_loading" title="Please wait while new application builds"><img  alt="" src="'+Settings.base_url+'assets/images/loading_map.gif">< /div>');
        $("#overlay_loading").height(docHeight)
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





















