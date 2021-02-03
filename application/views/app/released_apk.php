
        <div class="applicationText">

            <h2 style="text-transform: capitalize;">Version History</h2>
        </div>
        <div class="tableContainer" style="margin-top: 50px;">
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="version-listing">
                <thead>
                    <tr>
                        <th width="30%" class="Categoryh">Application Name</th>
                        <th width="30%" class="Categoryh">Version</th>
                        <th width="20%" class="Categoryh">Release Date</th>
                        <th width="20%" class="Categoryh">QR Code</th>
                        <th width="20%" class="ActionH text_center">Download </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($app as $app_item):
                        $file_name = './assets/android/qr_code/'.$app_item['qr_code_file'];
                        $result = '';
                        if(file_exists($filename)){
                            $result='
            <a style="padding-left:0px;" rel="lightbox" href="'.FORM_IMG_DISPLAY_PATH.'../../../android/qr_code/'.$app_item['qr_code_file'].'">
                <img class="formIconsUpload" src="'.FORM_IMG_DISPLAY_PATH.'../../../android/qr_code/'.$app_item['qr_code_file'].'" alt="" />
            </a>';
                        }
                        ?>
                        <tr class="trSelect" >
                            <td class="Category"><?php echo htmlspecialchars($app_item['app_name']); ?></td>
                            <td class="Category"><?php print $app_item['version']; ?></td>
                            <td class="Category"><?php print $app_item['created_datetime']; ?></td>
                            <td class="Category"><?php print $result; ?></td>
                            <td class="Links"><a target="_blank" href="<?php echo base_url(); ?>assets/android/apps/<?php print $app_item['app_file']; ?>" title="Download"><img src="<?= base_url() ?>assets/images/tableLink6.png" alt="" title="Download"/></a></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
