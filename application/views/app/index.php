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
</style>
<div class="applicationText">
    <?php if ($this->acl->hasPermission('app', 'add')) { ?>
        <a id="add_more_app">Add Application</a>
    <?php } ?>

    <h2>Applications</h2>
    <br clear="all" />
</div>
<?php
if ($this->acl->hasSuperAdmin()) {
    $width="56%";
}else{
    $width='24%';
}
?>

<table style="width:<?php echo $width; ?>;margin-bottom:10px;">
<tr id="filter_global">
    <td>Search By: </td>
    <td class="custom_filters" align="center"> <p id="renderingNameFilter"></p></td>
    <?php if ($this->acl->hasSuperAdmin()) { ?>
    <td class="custom_filters" align="center"> <p id="renderingDeptNameFilter"></p></td>
    <td class="custom_filters" align="center"> <p id="renderingCreatedByFilter"></p></td>
    <?php } ?>
<!--    <td><input type="button" value="Advance Filters" onclick="$('.custom_filters').toggle()"> </td>-->

</tr>
</table>
<div class="tableContainer">
    <div>
        <table id="application-listing-app3" class="display appajaxtable cell-border" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Application Name</th>
                <th>Icon</th>
                <?php if ($this->acl->hasSuperAdmin()) { ?>
                    <th >Department Name</th>
                    <th >Created By</th>
                <?php } ?>
                <th >Create Date</th>
                <th >QR Code</th>
                <th style="width: 140px;">Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr><td colspan="5">No record found!!!</td></tr>
            </tbody>
<!--            <tfoot>-->
<!--            <tr>-->
<!--                <th>Application Name</th>-->
<!--                <th>Application Icon</th>-->
<!--                --><?php //if ($this->acl->hasSuperAdmin()) { ?>
<!--                    <th >Department Name</th>-->
<!--                    <th >Created By</th>-->
<!--                --><?php //} ?>
<!--                <th >QR Code</th>-->
<!--                <th >Create Date</th>-->
<!--                <th >Actions</th>-->
<!--            </tr>-->
<!--            </tfoot>-->
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