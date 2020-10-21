

<div class="tableContainer">
    <div class="success"></div>
    <div style="overflow: auto">
        <form accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?= base_url() . 'form/edit_form_result_ajax'; ?>" id='form_edit'>
            <table cellspacing="0" cellpadding="0" id="application-listing-listview" class="display">
                <thead>
                    <tr>
                        <?php
                        $heading_label_array = array(
                            'image' => 'تصویر',
                            'Name' => 'نام',
                            'Father_Name' => 'والد کا نام',
                            'Gender' => 'جنس',
                            'Do_you_or_your_family_affected_by_flood' => 'کیا آپ یا آپ کا خاندان موجودہ سیلاب سے متاثر ہوا ہے؟',
                            'Nature_of_financial_damage' => 'مالی  نقصان کس نوعیت کا ہوا ہے؟',
                            'Do_you_get_any_aid' => 'آپ کے خاندان کو کسی ذریعہ یا ادارے کی طرف سے امداد ملی تھی؟',
                            'From_which_institution_you_ged_aid' => 'ملی تو کہاں سے؟',
                            'Facilities_Available' => 'مندرجہ ذیل میں سے کون کون سی سہولیات آپکو مہیا ہیں؟',
                            'What_You_Get_in_Cooked_Food' => 'پکے ہوئے کھانے میں کیا ملتا ہے؟',
                            'Government_performed_well_compared_to_past' => 'کیا آپ کہہ سکتے ہیں کہ اس بار حکومتی اقدامات پہلے کی نسبت بہتر تھے؟',
                            'Interview_Point' => 'سروے کی جگہ',
                            'Phone_Number' => ' فون نمبر',
                            'district_name' => ' ضلع کا نام',
                            'created_datetime' => 'Date & Time',
                        );
                        $total_headings = count($headings);
                        if ($app_id == 1293) {
                            foreach ($headings as $heading):
                                if ($heading != 'is_take_picture'):
                                    if (array_key_exists($heading, $heading_label_array)) {
                                        $lable_to_display = $heading_label_array[$heading];
                                    } else {
                                        $lable_to_display = $heading;
                                    }
                                    ?>
                                    <th class="Categoryh"><?php
                                        if ($heading == 'created_datetime') {
                                            echo "Date & Time";
                                        } else {
                                            echo $lable_to_display;
//                                            echo trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', urldecode($lable_to_display)));
                                        }
                                        ?></th>
                                    <?php
                                endif;
                            endforeach;
                        } else {
                            foreach ($headings as $heading):
                                if ($heading != 'is_take_picture'):
                                    ?>
                                    <th class="Categoryh"><?php
                                        if ($heading == 'created_datetime') {
                                            echo "Date & Time";
                                        } else {
                                            echo trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', urldecode($heading)));
                                        }
                                        ?></th>
                                    <?php
                                endif;
                            endforeach;
                        }
                        ?>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($form as $form_item): ?>
                        <tr class="trSelect" id="<?php echo $form_item['actions']; ?>">
                            <?php
                            for ($i = 0; $i < $total_headings; $i++) {

                                if ($headings[$i] != 'is_take_picture') {
                                    $leftaligh = '';
                                    if ($headings[$i] == 'image' || $headings[$i] == 'actions') {
                                        $leftaligh = 'appIconHead';
                                        $padding = '';
                                    } else {
                                        $padding = '0px ' . strlen($headings[$i]) . 'px' . ' 0px ' . strlen($headings[$i]) * 2 . 'px';
                                    }
                                    ?>
                                    <td class="Category <?php echo $leftaligh; ?>" style="position:relative; padding: <?php echo $padding; ?> ;" >
                                        <?php
                                        if (isset($form_item[$headings[$i]])) {
                                            if ($headings[$i] == 'image') {

                                                $image_colorbox = $form_item['actions'];
                                                $inside_title = str_replace(' ', '_', $form_item[$headings[$i]][0]['title']);

                                                if (array_key_exists($inside_title, $form_item)) {
                                                    $title = $form_item[$inside_title];
                                                } else {
                                                    $title = $form_item[$headings[$i]][0]['title'];
                                                }
                                                ?>
                                                <a href="<?php echo $form_item[$headings[$i]][0]['image']; ?>" rel="lightbox['<?php echo $image_colorbox; ?>']" title='<b><?php echo $title; ?></b>'>
                                                    <img align="left" src="<?php echo $form_item[$headings[$i]][0]['image']; ?>" width="50" height="50" alt="Record Images" title="<b>Record Images</b>" />
                                                </a>
                                                <?php
//                                                
                                                array_splice($form_item[$headings[$i]], 0, 1);

                                                foreach ($form_item[$headings[$i]] as $multi_image) {
                                                    $multi_title_inside = str_replace(' ', '_', $multi_image['title']);

                                                    if (array_key_exists($multi_title_inside, $form_item)) {
                                                        $title_multi = $form_item[$multi_title_inside];
                                                    } else {
                                                        echo $title_multi = $multi_image['title'];
                                                    }
                                                    ?>
                                                    <a rel="lightbox['<?php echo $image_colorbox; ?>']" href="<?php echo $multi_image['image']; ?>" title="<b><?php echo $title_multi; ?></b>" name = " ITU Government of Punjab - Pakistan">
                                                    </a>
                                                    <?php
                                                }
                                            } elseif ($headings[$i] == 'actions') {
                                                if ($this->acl->hasSuperAdmin()) {
                                                    ?>
                                                    <img src="<?= base_url() ?>assets/images/tableLink3.png" alt=""  title='Delete' form_id = '<?php echo $form_item['form_id']; ?>' class='delete_icon'/>&nbsp;&nbsp;&nbsp;
                                        <!-- <a class="edit_color_box" form_id = '<?php echo $form_item['form_id']; ?>' href="<?= base_url() . 'form/edit_form_partial'; ?>">
                                                        <img src="<?= base_url() ?>assets/images/tableLink1.png" alt=""  title='Update' class='update_icosn'/>
                                                    </a>-->
                                                    <?php
                                                } else {
                                                    if ($this->acl->hasPermission('form', 'delete')) {
                                                        ?>
                                                        <img src="<?= base_url() ?>assets/images/tableLink3.png" alt=""  title='Delete' form_id = '<?php echo $form_item['form_id']; ?>' class='delete_icon'/>&nbsp;&nbsp;&nbsp;
                                          <!--<a class="edit_color_box" form_id = '<?php echo $form_item['form_id']; ?>' href="<?= base_url() . 'form/edit_form_partial'; ?>">
                                                            <img src="<?= base_url() ?>assets/images/tableLink1.png" alt=""  title='Update' class='update_icosn'/>
                                                        </a>-->
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                &nbsp;&nbsp;
                                                <a href="<?= base_url() . 'single-record-map/' . $form_item['form_id'] . '-' . $form_item['actions']; ?>" target="_blank" >
                                                    <img src="<?= base_url() ?>assets/images/viewmap.png" alt=""  title='Map View'/>
                                                </a>

                                                <?php
                                            } else {
                                                echo '<span class="row_text">';
//                                                $data = ($headings[$i] != "Description") ? $form_item[$headings[$i]] : strtoupper($form_item[$headings[$i]]);
                                                echo ucwords($form_item[$headings[$i]]);
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <?php
                                }
                            }
                            ?>                            
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </form>
    </div>
</div>

<div id="pagination_div" style="">
    <span id="restult_div" >
        <?php
        $first_limit = $page_variable;
        $first_limit = ($first_limit == 0) ? 1 : $first_limit;
        $last_limit = $page_variable + $perPage;
        $last_limit = ($last_limit < $total_record_return) ? $last_limit : $total_record_return;
        echo 'Showing  ' . $first_limit . "  to  " . $last_limit;
        echo ' out of  ' . $total_record_return . '  records';
        ?>
    </span>
    <span style="margin:0px 0px 0px -13px;color: #333333 !important;">
        <?php
        $counter_page = ceil($total_record_return / $perPage);
        echo '(';
        echo ($counter_page > 1) ? $counter_page . ' Pages' : $counter_page . ' Page';
        echo ')';
        ?>
    </span>
    <span id="pag_span" style="margin:-2px 9px 0 0;float:right" >
        <?php
        if ($view_page == 'paging') {
            include 'paging.php';
        } elseif ($view_page == 'paging_category_filter') {
            include 'paging_category_filter.php';
        } else {
            include 'paging_date_filter.php';
        }
        ?>
    </span>
</div> 
<?php
if ($app_id == 1293) {
    ?>
    <style>

        table.display thead th div.DataTables_sort_wrapper span{
            display: none;
        }
    </style>
    <?php
}
?>

<style type="text/css">
    .applicationText{
        padding-bottom: 5px !important;
        padding-top: 2px;
    }
    .filter_class{
        color:#777777; display:table; padding-top:10px;
    }
    .Category input{
        display: none;
    }




    body{
        font-family:Arial, Helvetica, sans-serif; 
        font-size:13px;
    }
    .info, .success, .warning, .error, .validation {
        border: 1px solid;
        margin: 10px 0px;
        padding:15px 70px 15px 96px;
        background-repeat: no-repeat;
        background-position: 10px center;
    }

    .success {
        color: #4F8A10;
        background-color: #E5BF00;
        position: absolute;
        z-index: 11;
        margin: 0px 0px 11px 227px;
        display: none;
    }

    #restult_div{
        color: #333333 !important;
        padding: 0 15px 1px 37px;
    }
    #pagination_div{
        background: #CFCFCF; width: 100%;
        margin-top: -2px;
        padding-bottom:16px;
        border-bottom: 2px solid #AAAAAA;
        border-radius: 0px 0px 9px 9px;
        height: 11px;
        padding-top: 7px;
    }
    #pagination_div a {
        background: none repeat scroll 0 0 #DDDDDD;
        border: 1px solid #AAAAAA;
        border-radius: 5px 5px 5px 5px;
        color: #955533 !important;
        cursor: pointer;
        margin: 0;
        padding: 5px 4px 5px 4px;

    }
    #pagination_div input {
        background: none repeat scroll 0 0 #DDDDDD;
        border: 0 solid #AAAAAA;
        border-radius: 5px 5px 5px 5px;
        color: #333333 !important;
        cursor: pointer;

    }
    #pag_span b{
        color: #A8BD1E;
        font-size: 16px;
        border: 1px solid #AAAAAA;
        border-radius: 5px 5px 5px 5px;
        padding: 4px;
    }

    .fg-toolbar{
        display: none;
    }

</style>
