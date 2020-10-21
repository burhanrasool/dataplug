<div >
            <?php
            if(!empty($final_forms)) {
                ?>
                <section id="tabbed-inner">

                    <?php
                    $i = 1;

                    foreach ($final_forms as $key => $val){
                    $form_id = $val['id'];
                    echo form_open("app/newappsettings/".$app_id, array("id" => "form_settings_$form_id"));
                    echo form_hidden('setting_type', 'form_settings');
                    echo form_hidden('form_id', $form_id);
                    $form_id = $val['id'];

                    if ($i == 1) {
                        $j = 1;
                        foreach ($final_forms as $key1 => $val1) {
                            ?>
                            <input id="inner-t-<?php echo $j; ?>" name="tabbed-tabs" type="radio"
                                   <?php if ($j == 1){ ?>checked="checked" <?php } ?> >
                            <label for="inner-t-<?php echo $j; ?>"
                                   class="tabs shadow entypo-pencil"><?php echo $key1; ?></label>
                            <?php
                            $j++;
                        }
                    }
                    ?>


                    <!-- Tabs wrapper -->
                    <div class="wrapper-inner">
                        <!-- Tab 1 content -->

                        <div class="inner-tab-<?php echo $i; ?> mytab" style="max-height: 300px; overflow: auto;">
                            <?php $attributes = array('id' => 'form_settings_' . $val['id']); ?>
                            <!--                    --><?php //echo form_open("dd",$attributes); ?>
                            <!--                    --><?php //echo form_hidden('setting_type', 'form_settings'); ?>
                            <!--                    --><?php //echo form_hidden('form_id', $form_id); ?>

                            <div class="row">
                                <label for="d1_textfield">
                                    POST URL
                                </label>

                                <div>
                                    <input class="textBoxLogin" type="text" name="post_url" id="d1_textfield"
                                           value="<?php echo $val['post_url']; ?>"/>
                                </div>
                                <!--                --><?php //echo $this->form_validation->error('first_name'); ?>
                            </div>
                            <br/>
                            <br/>
                            <div class="row">
                                <label for="d1_textfield">
                                    Row Key for android unsent listing
                                </label>

                                <div>
                                    <input class="textBoxLogin" type="text" name="row_key" id="d1_textfield"
                                           value="<?php echo $val['row_key']; ?>"/>
                                </div>
                                <!--                --><?php //echo $this->form_validation->error('first_name'); ?>
                            </div>
                            <br/>
                            <br/>

<!--                            <div class="row">-->
<!--                                <label for="d1_textfield">-->
<!--                                    Possible Filters-->
<!--                                </label>-->
<!---->
<!--                                <div>-->
<!--                                    <select name="possible_filters[]" id="possible_filters" multiple="multiple"-->
<!--                                            class="multiselect possible_filters" style="width: 150px;">-->
<!--                                        --><?php
//                                        $selected_possible_filters = explode(",", $possible_and_defaults[$form_id]['possible_filter_selected']);
//                                        $form_filter = $filters_array[$form_id];
//                                        foreach ($form_filter as $filter) {
//
//                                            ?>
<!--                                            <option --><?php //if (in_array($filter, $selected_possible_filters)) {
//                                                echo "selected";
//                                            } ?><!-- value="--><?php //echo $filter; ?><!--">--><?php //echo $filter; ?><!--</option>-->
<!--                                        --><?php
//                                        }
//
//                                        ?>
<!--                                    </select>-->
<!--                                </div>-->
<!--                                <!--                --><?php ////echo $this->form_validation->error('last_name'); ?>
<!--                            </div>-->
<!--                            <br/>-->
<!--                            <br/>-->
<!--                            <br/>-->
                            <br/>

                            <div class="row">
                                <label for="d1_textfield">
                                    Default Filters
                                </label>

                                <div>
                                    <select name="default_filter">
                                        <?php
                                        $form_filter = $filters_array[$form_id];
                                        foreach ($form_filter as $filter) {

                                            ?>
                                            <option <?php if ($filter == $possible_and_defaults[$form_id]['default_filter_selected']) {
                                                echo "selected";
                                            } ?> value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                                <?php //echo $this->form_validation->error('last_name'); ?>
                            </div>
                            <br/>
                            <br/>


                            <div class="actions">
                                <div class="right">
                                    <input type="button" class="genericBtn form_settings filter_button"
                                           id="form_settings_<?php echo $val['id']; ?>" value="Update"><br><br>
                    <span class="form_settings_<?php echo $val['id']; ?>_msg"
                          style="width:530px; color: green; text-align: right;float: right;">

                    </span>
                                    <!--                    <button class="genericBtn general_settings" >Update</button>-->
                                </div>

                            </div>
                            <?php

                            echo form_close();


                            echo form_open("app/newappsettings/".$app_id, array("id" => "form_column_settings_$form_id"));
                            echo form_hidden('setting_type', 'form_column_settings');
                            echo form_hidden('form_id', $form_id);
                            echo form_hidden('app_id', $app_id);

                            ?>
                            <br><br><div style="clear:both"></div>
                            <h2>Column Settings</h2>
                            <table style="width: 80%">
                                <tr>
                                    <th>Column</th>
                                    <th>Alternate Name</th>
                                    <th>Order</th>
                                    <th>Visible <br><input type="checkbox" id="check_all_column" form_id="<?php echo $form_id;?>" > </th>
                                </tr>
                                <?php
                                if(empty($column_settings)){
                                    $default_checked='checked=checked';
                                }else{
                                    $default_checked='';
                                }

                                $form_filter[]='sent_by';
//                                echo "<pre>";
//                                print_r($form_filter);die;
                                $schema_list[$form_id]=array_merge($schema_list[$form_id],array('sent_by'=>'sent_by'));
                                foreach($schema_list[$form_id] as $key => $column){
//                                    echo "<pre>";
//                                    print_r($column);die;
                                    $column_value=(isset($column_settings[$form_id]['columns'][$column])&&$column_settings[$form_id]['columns'][$column]!="")?$column_settings[$form_id]['columns'][$column]:$column;
                                    $order=(isset($column_settings[$form_id]['order'][$column]) && $column_settings[$form_id]['order'][$column]!="")?$column_settings[$form_id]['order'][$column]:"";


                                    if(array_key_exists($form_id,$column_settings)) {
                                        if (isset($column_settings[$form_id]['visible'][$column])) {
                                            $selected = "checked";
                                        } else {
                                            $selected = "";
                                        }
                                    }else{
                                        $selected = "checked";
                                    }

                                    ?>
                                    <tr style="height: 50px;">
                                        <td><?php echo $column; ?></td>
                                        <td> <input type="text" name="columns[<?php echo $column; ?>]" value="<?php echo $column_value; ?>"> </td>
                                        <td> <input style="width:50px;" type="text" name="order[<?php echo $column; ?>]" value="<?php echo $order; ?>"> </td>
                                        <td> <input type="checkbox" class="chk_column_setting<?php echo $form_id;?>" name="visible[<?php echo $column; ?>]" <?php echo $selected; echo $default_checked; ?>> </td>
                                    </tr>

                                <?php
                                }
                                ?>

                            </table>
                            <div class="actions">
                                <div class="right">
                                    <input type="button" class="genericBtn form_settings filter_button"
                                           id="form_column_settings_<?php echo $val['id']; ?>" value="Update"><br><br>
                    <span class="form_column_settings_<?php echo $val['id']; ?>_msg"
                          style="width:530px; color: green; text-align: right;float: right;">

                    </span>
                                </div>


                            </div>
                        </div>
                            <?php

                            $i++;
                            echo form_close();
                            }

                            ?>




                    </div>


                </section>
            <?php
            }
            ?>
        </div>