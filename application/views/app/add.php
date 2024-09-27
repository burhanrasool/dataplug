<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Add Application</h2>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo
								form_open(base_url().'add-new-app/'.$login_department_id,'id="addform" 
									class="full validate add_task_form " enctype="multipart/form-data"'); ?>
                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Department Name</strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('department_id', $departments,
											$batch, 'id="department_id"'); ?>
                                    </div>
                                    <span class="error">
                                        <?php echo $this->form_validation->error('department_id'); ?>
                                    </span>
                                </div>
                                <div class="row" id="dep_name" style="display:none;">
                                    <label for="d1_textfield">
                                        <strong>Department Name </strong>
                                    </label>
                                    <div>
                                        <input class="required" type="text" name="department_name"
											id="department_name" value="<?php echo set_value('department_name'); ?>" />
                                    </div>
                                    <span class="error">
                                        <?php echo $this->form_validation->error('department_name'); ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Application Name</strong>
                                </label>
                                <div>
                                    <input class="required" type="text" name="app_name" id="app_name"
										value="<?php echo set_value('app_name'); ?>"
											onkeyup='check_app_name_availability($(this).val())'/>
                                    <span id='availability_status'></span>
                                </div>
                                <span class="error">
                                    <?php echo $this->form_validation->error('app_name'); ?>
                                </span>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Application Icon (.png only)</strong>
                                </label>
                                <div>
                                    <input type="file" name="userfile_addapp" id="userfile_addapp" 
										accept="*.png" onchange="check_file()"/>
                                </div>
                            </div>
                            <div class="actions">
                                <div class="right">
                                    <button class="genericBtn" id="addappbtn">Add Application</button>
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
                    app_name: "required",
                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                                    department_id : "required"
                    <?php }?>
                    
                },
                messages: {
                    app_name : "Application name is missing.",
            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                    department_id : "Please select department"
                    <?php }?>
                }
            });
        $('#department_id').trigger('change');
    });
    function check_app_name_availability(obj) {
        var dept_id = $('#department_id :selected').val();
        var app_name = obj;
        if (dept_id != "" && app_name != "") {
            $.ajax({
                url: '<?= base_url() . 'app/check_app_name_availability' ?>',
                data: {app_name: app_name, department_id: dept_id},
                type: "post",
                success: function(response) {
                    var response = $.parseJSON(response);
                    if (response.status == 0) {
                        $('#availability_status').css('color', 'red');
                        $('#availability_status').css('font-weight', 'bold');
                        $('#addappbtn').attr('disabled',true);
                    } else {
                        $('#availability_status').css('color', 'green');
                        $('#addappbtn').attr('disabled',false);
                    }
                    $('#availability_status').text(response.message);
                }
            });
        }
    }


    $('#department_id').change(function() {
        var department = $(this).val();
        if (department == 'new')
        {
            $('#dep_name').show();
        } else
        {
            $('#dep_name').hide();
            $('#department_name').val('');
        }
    });

    function check_file() {
        str = document.getElementById('userfile_addapp').value.toUpperCase();
        suffix = ".png";
        suffix2 = ".PNG";
        if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
            alert('File type not supported, only (.png) files allowed.');
            document.getElementById('userfile_addapp').value = '';
        }
    }
</script>
<style>
    .error p{
        color: red;
        font-weight: bold;
    }
    #availability_status {
        color: green;
    }
</style>