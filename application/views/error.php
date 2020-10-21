<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title> <?php echo $error_type; ?></title>
    </head>
    <body>
        <div class="error_content">

            <img src="<?= base_url() ?>assets/images/404.jpg" />
        </div>
        <div class="error_status">
            <?php echo $error_type; ?>
        </div>
    </body>
</html>
<style>
    .error_content{
        float: right;
        width: 60%;
    }
    .error_status {
  color: red;
  font-size: 27px;
  margin: 321px 0 0 494px;
  position: absolute;
}
</style>



