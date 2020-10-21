<style>
    .applicationText a{position:relative;}.
    Category a{text-decoration:underline!important;}
    #cboxCurrent{display:none!important;}
    .text_filter{
        border:solid 1px #CCC;
        color: #c4c4c4;
    }

    .text_filter:focus{
        color: #000000;
    }

    .custom_filters{
        /*display:none;*/
    }

    .imagecenter{
        text-align: center;
    }
    .custom_filters_style tbody tr td p span input {
        width: 130px;
    }
    .custom_filters_style tbody tr td p span select {
        width: 130px;
    }
</style>
<div class="applicationText">
    <?php if ($this->acl->hasPermission('complaint', 'add')) { ?>
        <a id="add_more_complaint" href="<?= base_url() ?>complaint/add">Add Complaint</a>
    <?php } ?>

    <h2>Complaints Management System</h2>
    <br clear="all" />
</div>
<?php
if ($this->acl->hasSuperAdmin()) {
    $width="56%";
}else{
    $width='24%';
}
?>
<div>
    <table class="custom_filters_style" style="width:100%;margin-bottom:10px;">
        <tr style="width:100%;margin-bottom:10px;">
            <td style="font-size: 20px;">Search By: </td>

        </tr style="width:100%;margin-bottom:10px;">
        <tr id="filter_global" style="height: 50px;">
            <td class="custom_filters" align="center"> <p id="renderingComplaintId"></p></td>
            
            <td class="custom_filters" align="center"> <p id="renderingDeptNameFilter"></p></td>
            <td class="custom_filters" align="center"> <p id="renderingApplicationFilter"></p></td>
            <td class="custom_filters" align="center"> <p id="renderingComplaintType"></p></td>
            <td class="custom_filters" align="center"> <p id="renderingComplaintTitle"></p></td>
            <td class="custom_filters" align="center"> <p id="renderingComplaintStatus"></p></td>
            <td class="custom_filters" align="center"> <p id="renderingComplaintBy"></p></td>
            </tr>
            <tr>
            <td class="custom_filters" align="left" colspan="4"> <p id="renderingComplaintDate"></p></td>

        </tr>
        </tr>
    </table>
</div>

<div class="tableContainer">

    <div style="overflow: auto;">

        <table id="application-listing-app3" class="display appajaxtable cell-border" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Image </th>
                <th>Complaint # </th>
                <th>Date & Time</th>
                <th>Department</th>
                <th>Application</th>
                <th>Type</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Complaint By</th>
                <th style="width: 140px;">Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr><td colspan="8">No record found!!!</td></tr>
            </tbody>
        </table>

    </div>
</div>
<style>
    table.display thead th{
        cursor: initial;
    }
</style>
<?php 
$super_admin = false;
if ($this->acl->hasSuperAdmin()) {
	$super_admin = true;
} ?>

    
<script type="text/javascript" charset="utf-8">
    var Settings = {
        base_url: '<?php echo base_url(); ?>',
        login_department_id: '<?php echo $login_department_id; ?>',
        super_admin: '<?php echo $super_admin; ?>'
    }
</script>