<div class="applicationText">
    <h2>Application Views   </h2>
    <br clear="all" />
</div>
<?php if ($this->acl->hasPermission('app_views', 'add')) { ?>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
        <tbody>
            <tr>
                <td>
                    <?php echo form_open("app/appusersview"); ?>

                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                        <div class="row">
                            <label for="d1_textfield">
                                <strong>*Department: </strong>
                            </label>
                            <div>

                                <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id"'); ?>
                            </div>
                            <?php echo $this->form_validation->error('department_id'); ?>
                        </div>

                    <?php } ?>
                    <div class="row" id="dep_name">
                        <label for="d1_textfield">
                            <strong>*Application </strong>
                        </label>
                        <div>

                            <select id="app_id" name="app_id">
                                <option value="">Select App</option>
                                <?php
                                if (isset($app_list)) {
                                    foreach ($app_list as $value) {
                                        ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </div>
                        <?php echo $this->form_validation->error('app_id'); ?>
                    </div>

                    <div class="row">
                        <label for="d1_textfield">
                            <strong>*Name </strong>
                        </label>
                        <div>
                            <input class="textBoxLogin" type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" />
                        </div>
                        <?php echo $this->form_validation->error('name'); ?>
                    </div>


                    <button class="genericBtn">Add</button>



                    <?php echo form_close(); ?>
                </td></tr>
        </tbody>
    </table>
<?php } ?>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">Application Name</th>
                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                        <th class="Categoryh">Department Name</th>

                    <?php } ?>
                    <th class="Categoryh">View Name</th>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($app_views_list)):
                    foreach ($app_views_list as $app_view):
                        ?>
                        <tr class="trSelect" >
                            <td class="Category">
                                <?php echo $app_view['app_name'] ?>

                            </td>

                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <td class="Category"><?php echo $app_view['department_name'] ?></td>
                            <?php } ?>
                            <td class="Category"><?php echo $app_view['view_name'] ?></td>
                            <td class="Links">
                                <?php if ($this->acl->hasPermission('app', 'delete')) { ?>
                                    <a href="javascript:void(0)"><img class="delete_views" view_id="<?php echo $app_view['view_id'] ?>"  src="<?= base_url() ?>assets/images/tableLink3.png" alt="" title="Delete View"/></a>
                                <?php } ?>
                                <br clear="all" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>


<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0;
    }
    #department_id, #app_id {
        background-color: #FFFFFF;
        border: 1px solid #0E76BD;
        color: #444444;
        cursor: pointer;
        height: 27px;
        line-height: 26px;
        margin-right: 8px;
        overflow: hidden;
        padding: 0;
        text-align: left;
        text-decoration: none;
        white-space: nowrap;
        width: 240px;
    }

    #example strong {
        float: none;
        font-weight: none;
        height: 0;
        line-height: 33px;
        margin: none;
    }
    .row div{
        float: left;
    }
    .row label{
        display: block;
        float: left;
        width: 127px;
    }
    .row div input {
        float: none;
        width: 240px;
    }
    .genericBtn {
        float: left;
        margin: 32px 0 10px 10px;
    }
    .row {
        display: block;
        float: left;
        width: 250px;
    }
    tr:hover{
        background-color: #FFFFFF;

    }
    table.dataTable td {
        padding: 10px 10px;
    }
</style>