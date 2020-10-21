<?php
/**
 * for x and y - axix data
 */
$list_x_axix = '';
$list_y_axix = '';
foreach ($town_wise_counter as $key => $counts) {
    $list_x_axix .= "'$key'" . ',';
    $list_y_axix .= "$counts" . ',';
}
$list_x_axix = substr($list_x_axix, 0, -1);
$list_y_axix = substr($list_y_axix, 0, -1);

/**
 * Pie chart system
 */
$pie_array = $town_wise_counter;
$highest_point = '';
$pie_chart_data = '';
array_shift($pie_array);
foreach ($pie_array as $key => $counts) {
    $pie_chart_data .= '[' . "'$key'" . ',' . $counts . '],';
}
/*
 * getting only highest value from data to make
 * it bit poped in graph
 */
foreach ($town_wise_counter as $key => $counts) {
    $highest_name = $key;
    $highest_count = $counts;
    break;
}
$pie_chart_data = substr($pie_chart_data, 0, -1);

echo '<div class="form_class" style="float: left">';
if (count($form_lists) > 1) {
    echo form_open(base_url() . 'graph/dashboard/' . $app_id, 'id=date_filter_form name=date_filter_form');
    echo 'Forms : ';
    ?>
    <select id="form_lists" name ='form_lists' >
        <?php foreach ($form_lists as $values) { ?>
            <option value = "<?php echo $values['form_id'] ?>" <?php if ($values['form_id'] == $selected_form) echo 'selected'; ?> />
            <?php echo $values['form_name']; ?>
        </option>
    <?php } ?>
    </select>
    <?php
    echo form_close();
}
echo '</div>';
?>
<div id='selecter'> 	&nbsp;Graph 
    <select id='filter_graph_type'>
        <option value='town'>Town Graph</optinon>
            <?php
            if (isset($users_list)) {
                ?>
            <option value='user'>User Graph</optinon>
            <?php } ?>
        <option value='category'>Category Graph</optinon>
    </select>
    <?php
    if (isset($users_list)) {
        ?>
        <span id="user_span">
            Users :

            <select id="users_graph" name="users_graph">
                <option value=''>Select</option>
                <?php
                foreach ($users_list as $key => $value) {
                    echo $key;
                    ?>
                    <option value="<?php echo $value; ?>"><?php echo $key; ?></option>
                    <?php
                }
                ?>

            </select>
        </span>
    <?php } ?>
    <span id="filter_span">
        <?php
        if (!empty($filter_attribute)) {
            foreach ($filter_attribute as $filter_attribute_value) {
                echo '' . ucwords(str_replace("_", " ", $filter_attribute_value));
                ?>
                <select id="cat_filter" name="cat_filter_2[]">
                    <option value=''>Select</option>
                    <?php
                    if (isset($category_list)) {
                        foreach ($category_list as $key => $value) {
                            echo $key;
                            ?>
                            <option value="<?php echo $value; ?>" data-filter='<?php echo $filter_attribute_value ?>'><?php echo $key; ?></option>
                            <?php
                        }
                    }
                    ?>

                </select>               
                <?php
            }
        }
        ?>

    </span>
</div>
<div class="applicationText">

    <div id="container" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">

    </div>
    <hr>
    <div id="container_pie" style="width: 100%; height: 441px; overflow: auto; margin: 0 auto">
    </div>
</div>

<style>
    #overlay_loading img{
        margin: 20% 0 0 47%;
    }
    #cat_filter{
        max-width: 130px;
        width: 130px;
    }
    #users_graph{
        max-width: 130px;
        width: 130px;
    }
</style>