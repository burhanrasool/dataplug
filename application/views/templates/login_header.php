<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $pageTitle;?></title>
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="wrapper">  
            
            <?php
            $messages = $this->session->flashdata('validate');

            if ($messages) {
                if ($messages['type'] == 'success') {
                    $type = 'flashdiv-success';
                } else if ($messages['type'] == 'error') {
                    $type = 'flashdiv-error';
                } else if ($messages['type'] == 'warning') {
                    $type = 'flashdiv-warning';
                }
                ?>
                <div class="<?php echo $type; ?>">
                    <div class="flash-nav-container">
                        <div>
    <?php echo $messages['message']; ?>
                        </div>
                    </div>
                </div>
<?php
}?>