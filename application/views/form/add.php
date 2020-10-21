<style>
    label.error{
    font:15px Tahoma,sans-serif;
    color:red;
    display:inline;
}
</style>
<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Add Form</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open(base_url().'form/add/'.$app_id,'id="addform" class="full validate add_task_form " enctype="multipart/form-data"'); ?>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Form Name</strong>
                                </label>
                                <div>
                                    <input class="required" type="text" name="form_name" id="form_name" value="" onKeyPress="$('#addformbtn').show();"/>
                                    <input type="hidden" name="app_id" id="d1_textfield" value="<?php echo $app_id; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Form Icon (.png only)</strong>
                                </label>
                                <div style="float: left;">
                                    <input type="file" name="userfile_addform" id="userfile_addform" accept="*.png" onchange="check_file()"/>
                                </div>
                            </div>
                            <div class="actions">
                                <div class="right">
                                    <button class="submit genericBtn" id="addformbtn" onclick="$(this).hide();">Add Form</button>
                                </div>

                            </div>
                            <?php echo form_close(); ?>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function() {
        $("#addform").validate({
                rules: {
                    form_name: {required:true,
                        remote:{
                            url:'<?= base_url() . 'form/check_form_name_availability' ?>',
                            type:'post',
                            data:{
                                form_name:function() {return $('#form_name').val();},
                                app_id:function() {return $('#app_id').val();}
                            }
                        }
                    }
                },
                messages: {
                    form_name : {required:"Form name is missing.",remote:"Form name already taken"}
                }
            });
    });
    
    function check_file() {
        str = document.getElementById('userfile_addform').value.toUpperCase();
        suffix = ".png";
        suffix2 = ".PNG";
        if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
            alert('File type not supported, only (.png) files allowed...');
            document.getElementById('userfile_addform').value = '';
        }
    }
</script>