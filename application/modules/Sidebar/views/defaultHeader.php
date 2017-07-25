<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <title>NFC Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?= base_url() ?>uploads/assets/front/css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/style.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/rits.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/font-awesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/front/css/dropzone.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
    <link id="bsdp-css" href="<?= base_url() ?>uploads/assets/css/datepicker.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/front/css/custom.css" rel="stylesheet">
    <script src="<?= base_url() ?>uploads/assets/front/js/jquery-1.11.1.min.js"></script>   
<?php
/*
  @Author : Ritesh rana
  @Desc   : Used for the custom CSS
  @Input 	:
  @Output	:
  @Date   : 06/03/2017
 */
if (isset($headerCss) && count($headerCss) > 0) {
    foreach ($headerCss as $css) {
        ?>
        <link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
        <?php
    }
}
?>