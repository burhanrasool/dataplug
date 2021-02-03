<style>
    .customers {
        font-size: 16px;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
    .customers th {
        padding-top: 11px;
        padding-bottom: 11px;
        background-color: #2da5da;
        color: white;
    }
    .customers td, .customers th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 8px;
        /*width:200px;*/
    }
    .customers tr:nth-child(even) { 
        background-color: #f2f2f2;
    }

</style>
<?php
$exclude_array=array('id','form_id','zform_result_id');
$i=1;
if(!empty($subtable)) {
?>
    <h2><?php echo ucwords($heading); ?></h2>
    <table class="customers">
    <tbody>
<?php
    foreach ($subtable as $key => $val) {
        ?>
            <?php
            if ($i == 1) {
                ?>
                <tr>
                    <?php
//                    this loop is just printing table headings....
                    foreach ($val as $key1 => $val1) {

                        if (!in_array($key1, $exclude_array)) {
                            ?>
                            <th>
                                <?php
                                echo str_replace("_"," ",$key1);
                                ?>
                            </th>
                        <?php
                        }
                    }
                    ?>
                </tr>
            <?php
            }
            ?>
            <tr>
                <?php
//                this loop is printing table data ....
                foreach ($val as $key1 => $val1) {
                    if (!in_array($key1, $exclude_array)) {
                        ?>
                        <td>
                            <?php
                            echo $val1;
                            ?>
                        </td>
                    <?php
                    }
                }
                ?>
            </tr>

        <?php
        $i++;
    }
    ?>
    </tbody>
    </table>
<?php
}else{
    echo "<b>No Record Found!!!</b>";
}
?>
