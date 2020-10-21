<div class="applicationText" style="margin-bottom:15px;">
    <a id="add_dept">Add Department</a>

    <h2>Departments</h2>
    <br clear="all" />
</div>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">Department Name</th>
                    <th class="Categoryh">Public</th>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($departments)):
                    foreach ($departments as $department):
                        ?>
                        <tr class="trSelect" >
                            <td class="Category"><?php echo $department['name'] ?></td>
                            <td class="Category"><?php echo ucfirst($department['is_public']) ?></td>
                            <td class="Links">
                                <a href="<?= base_url() ?>edit-departments/<?php echo $department['id']; ?>" title="Edit"><img src="<?= base_url() ?>assets/images/tableLink1.png" alt="" /></a> 
                                &nbsp;
                                
                                <a href="javascript:void(0)" title="Delete"><img class="delete_department" department_id="<?php echo $department['id']; ?>"  src="<?= base_url() ?>assets/images/tableLink3.png" alt="" title="Delete department"/></a>
                                <br clear="all" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

