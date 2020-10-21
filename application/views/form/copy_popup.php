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
            <h2>Copy Form</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open(base_url().'form/copy/'.$form_id,'id="addform" class="full validate add_task_form " enctype="multipart/form-data"'); ?>
                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Copy to Department</strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id"'); ?>
                                    </div>
                                    <span class="error">
                                        <?php echo $this->form_validation->error('department_id'); ?>
                                    </span>
                            </div>
                                
                                <?php }?>
                            
                            <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Copy to Application</strong>
                                    </label>
                                    <div>
                                        <select id="app_id_popup" name="app_id_popup">
                                            <option value="<?=$app_id?>">Copy to Same Application</option>
                                            <?php
                                            if (isset($app_list)) {
                                                foreach ($app_list as $value) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $value['id'] ?>"><?php echo $value['name']; ?></option>
                                                <?php
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                    
                            </div>
                            
                            
                            <div class="actions">
                                <div class="right">
                                    <button class="submit genericBtn" id="addformbtn">Start Copy</button>
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
        
        $('#department_id').change(function() { //any select change on the dropdown with id country trigger this code
            $("#app_id_popup > option").remove(); //first of all clear select items
            var department_id = $(this).val(); // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>app/getapps/" + department_id, //here we are calling our user controller and get_cities method with the country_id

                success: function(groups) //we're calling the response json array 'cities'
                {
                    $.each(groups, function(id, name) //here we're doing a foeach loop round each city with id as the key and city as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option with for each city
                        opt.val(id);
                        opt.text(name);
                        $('#app_id_popup').append(opt); //here we will append these new select options to a dropdown with the id 'cities'
                    });
                }

            });
        });
    });
    
</script>