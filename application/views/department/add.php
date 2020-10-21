<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Add Department</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open(base_url() . 'new-department', ' id="new-dept-adding" class="full validate add_task_form "'); ?>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Department Name</strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="department_name" id="department_name" required />
                                </div>
                                <span class="phperror"><?php echo form_error("department_name"); ?></span>

                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Is Public</strong>
                                </label>
                                <div>
                                    <select name="is_public" id="is_public">
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="actions">
                                <div class="right">
                                    <button class="genericBtn">Add</button>
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
        $.validator.addMethod("dep_avail", function(value, element) 
        {
            var dep_name = value;
            var result = true;
            $.ajax({
                type: "post",
                async: false,
                data: {dep_name: dep_name},
                url: '<?= base_url() . 'department/dep_already_exist' ?>',
                success: function(data) {
                    if(data)
                    {
                        result = false;
                    }
                }
            });
            
            return result;
        }, "Department already taken.");

        $("#new-dept-adding").validate({
                rules: {
                    department_name: {required:true,dep_avail:true},
                },
                messages: {
                    department_name : {required: "Department name is required"}
                }
            });
    });
</script>
