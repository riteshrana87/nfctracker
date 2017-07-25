<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b></b>
    </div>
    <strong>Copyright &copy; <?php echo date('Y');?> </strong> All rights reserved.
</footer>

<!--
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-3.1.1.min.js"></script>-->
        <!-- jQuery 2.1.4 -->
    <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-1.11.3.min.js"></script><!-- jQuery UI 1.11.4 -->
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-ui.min.js"></script><!-- jQuery UI 1.11.4 -->
        <!-- DataTables -->

        <script src="<?= base_url() ?>uploads/assets/js/admin/common.js"></script>
        <!-- end-->
        
<!-- Bootstrap 3.3.5 -->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/moment.js"></script>
<link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-confirm.js"></script>


<!-- Block ui-->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery.blockUI.js"></script>

<?php
if (isset($footerJs) && count($footerJs) > 0) {
    foreach ($footerJs as $js) {
        ?>
        <script src="<?php echo $js; ?>" ></script>
        <?php
    }
}
?>
<!-- AdminLTE App -->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/app.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 8000);

    });
</script>
<?=$this->load->view($this->type.'/common/common','',true);?>

</body>
</html>