<div class="applicationText" style="margin-bottom: 15px;">
    <?php if ($this->acl->hasPermission('groups', 'add')) { ?>
    <a href="<?= base_url() ?>add-group" id="add_groupsBtn">Add Group</a>
    <?php }?>
    <h2>Groups</h2>
    <br clear="all" />
</div>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">Group Name</th>
                    <th class="Categoryh">Department Name</th>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (!empty($groups)):
                        foreach ($groups as $group):
                            ?>
                        <tr class="trSelect" >
                            <td class="Category"><?php echo $group['type'] ?></td>
                            <td class="Category"><?php echo $group['name'] ?></td>
                            <td class="Links">
                                <?php if ($this->acl->hasPermission('groups', 'edit')) { ?>
                                <a style="color:blue"href="<?= base_url() ?>group-permission/<?php echo $group['id'] ?>" title="Add Group Permissions">Group Permissions</a>
                                <?php }?>
                                <br clear="all" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

