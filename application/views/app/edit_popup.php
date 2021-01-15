<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Edit Application</h2>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <tbody>

        <tr>
            
            <td style='width: 80px;'>
                <?php echo form_open(base_url().'app/editpopup/'.$app_id,'id="appeditform" style="margin: auto;" class="full validate add_task_form" enctype="multipart/form-data"'); ?>
                <div class="row">
                    <label for="d1_textfield">
                        <strong>Application Name</strong>
                    </label>
                    <div>
                        <input class="required" type="text" name="app_name" id="d1_textfield" value="<?php echo $name; ?>" />
                    </div>
                </div>
                <div class="row" style="width: 47%; float: left;">
                    <label for="d1_textfield">
                        <strong>Application Icon (.png only)</strong>
                    </label>
                    <div >
                        <br />
                        <img id='img_app_icon_popup' width="50" height="50" src="<?php
                        $file_headers = @get_headers(FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $icon);
                        if ($file_headers[0] == 'HTTP/1.1 200 OK') {
                            echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $icon;
                        } else {
                            echo FORM_IMG_DISPLAY_PATH . '../form_icons/default_app.png';
                        }
                        ?>" alt="" onClick="$('#userfile_popup').click();" style="width: 23%"/>
                        <input type="file" name="userfile_popup" id="userfile_popup" accept="*.png" onchange="check_file_popup()" style='display:none;'/>
                    </div>
                </div>
                <div class="row" style="width: 50%; float: left;">
                   
                    <strong style="">Splash Screen (.png only)</strong>
                    <div >
                        <br />
                        <img id='img_splash_screen' width="50" height="50" src="<?php
                        if(file_exists(FCPATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'form_icons'.DIRECTORY_SEPARATOR.$app_id.DIRECTORY_SEPARATOR.$splash_icon)){
                            echo $image_path_icon = base_url() . 'assets/images/data/form_icons/' . $app_id . '/' .$splash_icon;
                        } else {
                            echo $imagepathicon = FORM_IMG_DISPLAY_PATH . '../form_icons/splash.png';
                        }
                        ?>" alt="" onClick="$('#splashfile').click();" style="margin: 5px 0px;"/>
                        <input type="file" name="splashfile" id="splashfile" accept="*.png" onchange="check_file_splash(this)" style='display:none;'/>
                    </div>
                </div>
                
              
                <div class="actions" style="float: right;">
                    <div class="right">
                        <button class="genericBtn" id="saveFormPopup" style="margin-top: 15px">Update</button>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </td>
        </tr>
<!--        
        <tr>
            <td>
               
            </td>
            <td >
                <a class="saveForm" id="saveFormPopup"><img style="float:right; width: 110px;height: 30px;" src="<?= base_url() ?>assets/images/save_btn.png" class="float_left" alt="" /></a>


            </td>
            
        </tr>-->
    </tbody>

</table>
                    </div>
    </div>
</div>

<script>
    
                    $('#saveFormPopup').click(function(event) {

                        event.preventDefault();
                        $("#appeditform").submit();
                    });
                    function check_file_popup() {
                        
                        str = document.getElementById('userfile_popup').value.toUpperCase();
                        suffix = ".png";
                        suffix2 = ".PNG";
                        if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                                str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                            alert('File type not supported, only (.png) files allowed.');
                            document.getElementById('userfile_popup').value = '';
                        }
                        else
                        {
                            readURLappPopup(document.getElementById('userfile_popup'));
                        }
                    }
                    function readURLappPopup(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                $('#img_app_icon_popup').attr('src', e.target.result);
                            }

                            reader.readAsDataURL(input.files[0]);
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
                    function readURLappSplash(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {

                                $('#img_splash_screen').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
</script>
