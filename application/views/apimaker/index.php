<style>
	.applicationText a{position:relative;}.Category
		a{text-decoration:underline!important;}#cboxCurrent{display:none!important;}
		</style>
<div class="applicationText">
        <a id="add_more_app">Add Dropdown API</a>
    <h2>Dropdown API Maker</h2>
    <br clear="all" />
</div>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0"
			id="application-listing-app" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">API Title</th>
                    <th class="Categoryh">Department</th>
                    <th class="Categoryh">API file</th>
                    <th class="ActionH text_center" style="min-width: 160px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($apis)):
                    foreach ($apis as $api):
                        $api_id = $api['id'];
                        ?>
                        <tr class="trSelect" >
                            <td class="Category">
                                <?php
                                echo $api['title'];
                                ?>
                            </td>
                            <td class="Category appIconHead">
                                <?php
                                echo (isset($api['name']))?$api['name']:"Not Set";
                                ?>
                            </td>
                            <td class="Category appIconHead">
                                <?php
                                echo $api['file_name'];
                                ?>
                            </td>
                            <td class="Links" style="width: 70px; border-bottom: 1px solid #D5D5D5;">
                                    <a href="<?php echo base_url(); ?>createapiurl/<?php
										echo $api['id']; ?>"><img src="<?= base_url()
											?>assets/images/settings-ico.png" alt="" title="Create Url" width="28px"/></a>
                                    <a class="edit_api" api_id="<?= $api['id'] ?>"><img src="<?= base_url()
										?>assets/images/tableLink1.png" alt="" title="Edit"/></a>
                                    <a href="javascript:void(0)">
										<img src="<?= base_url() ?>assets/images/tableLink3.png"
											alt="" title="Delete" id ="delete_api" title="Delete Api"
												api_id ="<?= $api['id'] ?>"/></a>
                                    <a href="<?php echo base_url(); ?>assets/data/<?php echo $api['file_name'];
										?>"><img src="<?= base_url() ?>assets/images/tableLink6.png"
											alt="" title="Download"/></a>
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
    table.display thead th{
        cursor: initial;
    }
</style>


<script type="text/javascript" charset="utf-8">
    var Settings = {
        base_url: '<?php echo base_url(); ?>'
    }
</script>