<div class="applicationText">
    <h2>Assign application to other users   </h2>
    <br clear="all" />
</div>

<style>
    .addappusertable tr:hover{
        background: none !important;

    }
    .addappusertable tr td{
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .row p{
        color: red;
    }
    
</style>
<?php 
$session_data = $this->session->userdata('logged_in');
//echo form_open(base_url()."applicatioin-users"); ?>

<form style='margin: auto;width: 100%;' action="<?= base_url() ?>assign-applicatioin-users/<?php echo $app_id?>" method="POST" class="full validate add_task_form"  enctype="multipart/form-data"  id='form_edit'/>
<input type="hidden" name="htmldesc" id="htmldesc" />
<table cellpadding="0" cellspacing="0" border="0" class="display addappusertable" id="example" style='width: 100%;box-shadow:0 0 6px rgba(0, 0, 0, 0.25);margin:15px 0 14px 0'>
    <tbody>
        <tr>
            <td style='width: 50px;'>
                <label for="d1_textfield">
                    <strong>*Users </strong>
                </label>
            </td>
            <td style='width: 50px;'>
                <div class="assign_error">

                    <div>

                        <select id="user_id" name="user_id" style="width: 135px;" class="customSelect">
                            <option value="">Select User</option>
                            <?php
                            
                            if (isset($usr_list)) {
                                foreach ($usr_list as $value) {
                                    if($session_data['login_user_id']!=$value['id']){
                                    ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['first_name'].' '.$value['last_name'].' ('.$value['email'].')'; ?></option>
                                    <?php }
                                }
                            }
                            ?>

                        </select>
                    </div>
                    <?php echo $this->form_validation->error('user_id'); ?>
                </div>
            </td>
            <td  style='width: 110px;'>

                <button class="genericBtn" style="">Assign</button>

                
            </td>
            
        </tr>
       
    </tbody>
</table>
</form>

<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">Application Name</th>
                    <th class="Categoryh">User Name</th>
                    <?php if ($this->acl->hasSuperAdmin()) { ?>
                        <th class="Categoryh">Department Name</th>
                    <?php } ?>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($app_user_list)):
                    foreach ($app_user_list as $app_user):
                    if($session_data['login_user_id']!=$app_user['user_id']){
                        ?>
                        <tr class="trSelect" >
                            <td class="Category"><a href=""><?php echo htmlspecialchars($app_user['app_name']) ?></a></td>
                           
                           
                            <td class="Category"><?php echo $app_user['first_name']." ".$app_user['last_name'] ?></td>
                            <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <td class="Category"><?php echo $app_user['department_name'] ?></td>

                            <?php } ?>
                            <td class="Category">
                                <a href="<?= base_url() ?>app/deleteasignuser/<?php 
								echo $app_user['assigned_id']?>/<?php echo $app_id?>">
                                    <img src="<?= base_url() ?>assets/images/tableLink3.png" alt="" 
									title ="Delete assigned user" />
                                </a>
                               
                            </td>
                        </tr>
                    <?php } endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>


<style>
    
    .assign_error p {
        color: red;
        margin: 0;
        padding: 11px;
        text-align: right;
    }
    .row p{
        color:red;
        font-size: 12px;
        margin: 0;
    }
    #department_id,#app_id,#district,#view_id {
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
        width: 139px;
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
    .row div input{
        float: none;
        width: 123px;

    }
   .genericBtn {
  float: right;
  margin: 0px 24px 0;
}

    tr:hover{
        background-color: #FFFFFF;
    }
    table.dataTable thead th{
        padding: 3px 0 3px 10px;
        text-align: left;
    }
</style>