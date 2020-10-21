<div class="applicationText">
    <a href="<?= base_url() ?>form/add/<?php echo $app_id ?>"><img src="<?= base_url() ?>assets/images/addform-1.png" alt="" /></a>
    <a href="<?= base_url() ?>app/createapk/<?php echo $app_id ?>"><img src="<?= base_url() ?>assets/images/buildnewversion.png" alt="" /></a>
    <h2><?php echo $app_name; ?> - Forms</h2>
    <br clear="all" />
</div>
<div class="tableContainer">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing" class="display">
            <thead>
                <tr>
                    <th class="Categoryh">Form Name</th>
                    <th class="Categoryh">Form Icon</th>
                    <th class="Categoryh">Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($form)):
                    foreach ($form as $form_item):
                        ?>
                        <tr class="trSelect" >
                            <td class="Category">
                                <?php
                                $formRes = $this->form_results_model->get_form_results($form_item['form_id']);
                                if (!empty($formRes)) {
                                    ?>
                                    <a href="<?= base_url() ?>form/results/<?php echo $form_item['form_id']; ?>"><?php echo $form_item['form_name'] ?></a>
                                <?php }else{ ?>
                                    <?php echo $form_item['form_name'] ?>
                                <?php } ?>
                                </td>
                            <td class="Category">
                                <?php
                                if (!empty($form_item['icon'])) {
                                    ?>
                                    <img width="50" height="50" src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $form_item['icon']; ?>" alt="" />
                                    <?php
                                }
                                ?>
                                    </td>
                            
                            
                            <td class="Links">

                                <a href="<?= base_url() ?>app-form/<?php echo $form_item['form_id']; ?>" title="Edit"><img src="<?= base_url() ?>assets/images/tableLink1.png" alt="" /></a> 
                                <?php if ($this->acl->hasPermission('form', 'delete')) {?>
                                <a href="<?= base_url() ?>form/delete/<?php echo $form_item['form_id']; ?>"><img src="<?= base_url() ?>assets/images/tableLink3.png" alt="" /></a>
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

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        oTable = $('#application-listing').dataTable({
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1] // <-- gets last column and turns off sorting
                }
            ],
            "jQueryUI": true,
            "paginationType": "full_numbers"
        });
    });
</script>


<script type="text/javascript">
    var Settings = {
        base_url: '<?php echo base_url(); ?>'
    }
</script>