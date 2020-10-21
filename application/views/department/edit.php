<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Edit Department</h2>

<!--            <a href="<?= base_url() ?>department" class="backBtn">
                <img src="<?= base_url() ?>assets/images/back.png" alt='Go Back' height="29" width="35" title="Go Back" />
            </a>-->
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>department/edit/<?php echo $id; ?>" method="POST" class="full validate add_task_form" />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Department Name</strong>
                                </label>
                                <div>
                                    <input class="textBoxLogin" type="text" name="department_name" id="d1_textfield" value="<?php echo $name; ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Is Public</strong>
                                </label>
                                <div>

                                    <input type="radio" name="is_public" value="no" <?php echo ($is_public == 'no') ? 'checked' : '' ?> size="17">No
                                    <input type="radio" name="is_public" value="yes" <?php echo ($is_public == 'yes') ? 'checked' : '' ?> size="17">Yes
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Public Group</strong>
                                </label>
                                <div>

                                    <select id="group_id" name="group_id">
                                        <option value=''>Select Group</option>
                                        <?php
                                        if (isset($group_list)) {
                                            foreach ($group_list as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($group_id == $key) ? 'selected="selected"' : ''; ?> ><?php echo $value; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="actions" style="clear: both;">
                                <div class="right">
                                    <button class="genericBtn" style="margin-left: 148px;">Update</button>
                                    <a  href="<?= base_url() ?>departments" class="genericBtn" style="height: 18px;padding: 5px;text-align: center;width: 54px;margin-left: 0px;">Back</a>
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
    #group_id {
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