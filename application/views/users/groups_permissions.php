<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2> Group Permissions</h2>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            Check All <input id="check_all_column" form_id="23" type="checkbox">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo form_open("group-permission/" . $group_id); ?>

                            <?php
                            foreach ($modules as $module) {
                                $string = '<label for="d1_textfield">
                                            <br><br><strong>' . ucfirst(str_replace("_",  " ", $module)) . ' </strong><br>
                                        </label>';

                                foreach ($roles as $key => $role) {
                                    if (in_array($role, $skip[$module]))
                                        continue;
                                    ?>
                                    <div class="row" id="dep_name" style="display: inline; margin: 6px 5px 11px 10px">
                                        <?php
                                        echo $string;
                                        $string = '';
                                        $checktrue = '';
                                        if (isset($checked[$module][$role]))
                                            $checktrue = 'checked="checked"';
                                        ?>

                                        <input type="checkbox" class="permission_chkbox" <?php echo $checktrue; ?> name="<?php echo $module . '[]' ?>" id="<?php echo $module . '_' . $role; ?>" value="<?php echo $role; ?>" /><?php echo ucfirst($role); ?>

                                    </div>
                                    <?php
                                }
                                ?><hr><?php
                            }
                            ?>




                            <div class="actions" style="margin-top: 30px;">
                                <div class="right">
                                    <button class="genericBtn" name="submitpermission">Submit</button>
                                    <a  href="<?= base_url() ?>groups" class="genericBtn" style="height: 18px;padding: 5px;text-align: center;width: 54px;">Back</a>
                                </div>

                            </div>

                            <?php echo form_close(); ?>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>