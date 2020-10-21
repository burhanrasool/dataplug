<div class="applicationText" style="margin-bottom: 15px;">
    <?php if ($this->acl->hasPermission('users', 'add')) { ?>
    <a href="<?= base_url() ?>add-new-user" id="add_userBtn">Add User</a>
    <?php }?>
    <h2>Users</h2>
    <br clear="all" />
</div>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">User Name</th>
                    <th class="Categoryh">Email</th>
                    <th class="Categoryh">Department Name</th>
                    <th class="Categoryh">Group Name</th>
                    <th class="Categoryh">Status</th>
                    <th class="ActionH text_center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($users_listing)):
                    foreach ($users_listing as $user):
                        ?>
                        <tr class="trSelect" >
                            <td class="Category"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                            <td class="Category"><?php echo $user['email'] ?></td>
                            <td class="Category"><?php echo $user['department_name'] ?></td>
                            <td class="Category"><?php echo $user['group_name'] ?></td>
                            <td class="Category">
                                <?php if ($user['group_id'] == '0') {
                                    ?>
                                    <div class="status edit_status" id="<?php echo $user['id']; ?>">
                                        Inactive <br />Create/Assign group first
                                    </div>
                                <?php } else {
                                    ?>
                                    <div class="status edit_status" id="<?php echo $user['id']; ?>">
                                        <span id="edit_status_<?php echo $user['id']; ?>" class="text_status"><?php
                                            if ($user['status'] == '0') {
                                                echo 'Inactive';
                                            } else {
                                                echo 'Active';
                                            }
                                            ?></span>
                                        <select id="edit_status_input_<?php echo $user['id']; ?>" class="intxt2 edit_status_box" style="display: none;width: 100%;">
                                            <option <?php
                                            if ($user['status'] == '0') {
                                                echo 'selected';
                                            }
                                            ?> value="0">Inactive</option>
                                            <option <?php
                                            if ($user['status'] == '1') {
                                                echo 'selected';
                                            }
                                            ?> value="1">Active</option>
                                        </select>
                                    </div>
                                <?php } ?>
                            </td>
                            <td class="Links">
                                <?php if ($this->acl->hasSuperAdmin()) { ?>
                                <a href="<?= base_url() ?>users/autologin/<?php echo $user['id'] ?>">
                                    <img src="<?= base_url() ?>assets/images/auto_login.png" alt="" width="24px" title ="Login" />
                                </a>
                                <?php }?>
                                <?php if ($this->acl->hasPermission('users', 'edit')) { ?>
                                <a href="<?= base_url() ?>edit-user-account/<?php echo $user['id'] ?>">
                                    <img src="<?= base_url() ?>assets/images/tableLink1.png" alt="" title ="Edit" />
                                </a>
                                <?php }else{?>
                                
                                <a href="javascript:void(0)">
                                        <img style="visibility: hidden;" src="<?= base_url() ?>assets/images/tableLink3.png" alt="" title="Edit"/>
                                    </a>
                                <?php }?>
                                <?php if ($this->acl->hasPermission('users', 'delete')) { ?>
                                <a href="javascript:void(0)">
                                    <img src="<?= base_url() ?>assets/images/tableLink3.png" alt="" user_id ="<?= $user['id'] ?>" class="delete_user" title ="Delete user" />
                                </a>
                                <?php }else{?>
                                <a href="javascript:void(0)">
                                        <img style="visibility: hidden;" src="<?= base_url() ?>assets/images/tableLink3.png" alt="" title="Delete"/>
                                    </a>
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



