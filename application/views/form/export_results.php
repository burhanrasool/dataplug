<div id="container" style="min-height: 300px">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Export <?php echo $app_name; ?> Results </h2>
<!--            <a href="<?= base_url() . 'application-results/' . $app_id ?>" class="backBtn">
                <img src="<?= base_url() ?>assets/images/back.png" alt='Go Back' height="29" width="35" title="Go Back" />
            </a>-->
<!--            <br>-->
<!--            <br>-->

            <?php
            $app_name = str_replace(' ', '-', $app_name);
            $slug = $app_name . '-' . $app_id;
            echo '<div class="form_class" style="margin-left:10px">';
            if (count($form_lists) > 1) {
                echo form_open(base_url() . 'export-result/' . $app_id, 'id=date_filter_form name=date_filter_form');
                echo '<strong>Forms : </strong>';
                ?>
                <select id="form_lists" name ='form_lists' >
                    <option value="all_forms_result_export" selected="selected">All Forms</option>
                    <?php foreach ($form_lists as $values) { ?>
                        <option value = "<?php echo $values['form_id'] ?>" <?php if ($values['form_id'] == $selected_form) echo 'selected'; ?> />
                        <?php echo $values['form_name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <?php
                echo form_close();
            }
            echo '</div>';
            ?>
            <br>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 100px;">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>form/exportresults/<?php echo $app_id ?>" method="POST" class="full validate add_task_form" enctype="multipart/form-data"/>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Date From : </strong>
                                </label>
                                <div style="display: inline">
                                    <input type="hidden" value="<?php echo $form_id; ?>" name="form_id" id="form_id_export">

                                    <input class="textBoxLogin"  size ="10" type="text" id="datepicker" readonly="readonly" value="<?php echo!empty($selected_date_to) ? $selected_date_to : ""; ?>" name="filter_date_to" onchange="check_date_validity()" ondblclick="clear_field(this)">
                                    To :
                                    <input class="textBoxLogin" size="10" type="text" id="datepicker2" readonly="readonly" value="<?php echo!empty($selected_date_from) ? $selected_date_from : ""; ?>" name="filter_date_from" onchange="check_date_validity()" ondblclick="clear_field(this)">
                                </div>
                            </div>
                            <div class="row">
                                <button class="genericBtn">Export</button>

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
    #form_lists {
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
    }
    .row div input{
        float: none;

    }
    .genericBtn{
        float: left;
    }
    .row {
        display: block;
        float: left;

        color:#333333;
        font-size: 100%;
    }
    tr:hover{
        background-color: #FFFFFF;
    }

    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
    .ui-widget-content {
        border: medium none !important;
        color: #222222 !important;
    }
</style>