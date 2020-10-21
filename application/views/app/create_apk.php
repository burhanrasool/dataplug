<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <a href="<?= base_url() ?>apps" class="backBtn">Back</a>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <form action="<?= base_url() ?>app/createapk/<?php echo $app_id; ?>" method="POST" class="full validate add_task_form" enctype="multipart/form-data" />
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>App Name</strong>
                                </label>
                                <div>
                                    <input class="required" type="text" name="app_name" id="d1_textfield" value="<?php echo $app_name; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>App Version</strong>
                                </label>
                                <div>
                                    <input class="required" readonly type="text" name="app_version" id="d1_textfield" value="<?php echo $app_version; ?>"  />
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Icon</strong>
                                </label>
                                <div>
                                    <img width="50" height="50" src="<?php echo FORM_IMG_DISPLAY_PATH . '../form_icons/' . $app_id . '/' . $icon; ?>" alt="" />
                                </div>
                            </div>
                            <div class="actions">
                                <div class="right">
                                    <button class="submit">Create App</button>
                                </div>

                            </div>

                            </form>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
        function check_file() {
            str = document.getElementById('userfile').value.toUpperCase();
            suffix = ".png";
            suffix2 = ".PNG";
            if (!(str.indexOf(suffix, str.length - suffix.length) !== -1 ||
                    str.indexOf(suffix2, str.length - suffix2.length) !== -1)) {
                alert('File type not supported, only (.png) files allowed.');
                document.getElementById('userfile').value = '';
            }
        }
</script>