<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Add Group</h2>
            <!--<a href="<?= base_url() ?>groups" class="backBtn">Back</a>-->

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 130px;">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>add-group" method="POST" class="full validate add_task_form" />
                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <div class="row">
                                    <label for="d1_textfield">
                                        <strong>Department Name</strong>
                                    </label>
                                    <div>
                                        <?php echo form_dropdown('department_id', $departments, $batch, 'id="department_id"'); ?>
                                    </div>
                                    <?php echo $this->form_validation->error('department_id'); ?>
                                </div>
                                <div class="row" id="dep_name" style="display:none;">
                                    <label for="d1_textfield">
                                        <strong>Department Name </strong>
                                    </label>
                                    <div>
                                        <input class="textBoxLogin" type="text" name="department_name" id="department_name" value="<?php echo set_value('department_name'); ?>" />
                                    </div>
                                    <?php echo $this->form_validation->error('department_name'); ?>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Group Name</strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="group_name" id="d1_textfield" value="<?php echo set_value('app_name'); ?>" />
                                </div>
                                <?php echo $this->form_validation->error('group_name'); ?>
                            </div>
                            <div class="actions">
                                <div class="right">
                                    <button class="genericBtn">Add Group</button>
                                </div>

                            </div>

                            </form>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .row p{
        color:red;
        font-size: 12px;
        margin: 0px;
    }
    #department_id {
        background-color: #FFFFFF;
        border: 1px solid #0E76BD;
        color: #444444;
        cursor: pointer;
        height: 26px;
        line-height: 26px;
        margin-right: 8px;
        max-width: 151px;
        overflow: hidden;
        padding: 0;
        text-align: left;
        text-decoration: none;
        white-space: nowrap;
        width: 140px;
    }

    #example strong {
        float: none;
        height: 0;
        line-height: 33px;
        margin: none;
        font-size: 12px;
    }
    .row div{
        float: left;
    }
    .row label{
        display: block;
        float: left;
        width: 148px;
    }
    .row div input{
        float: none;

    }
    .genericBtn{
        margin-left: 208px;
    }
    .row {
        display: block;
        float: left;
        width: 700px;
        color:#333333;
        font-size: 100%;
    }
    tr:hover{
        background-color: #FFFFFF;
    }

    .genericBtn {
        margin-left: 225px;
    }
    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>