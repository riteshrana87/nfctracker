<?php
$admin_session = $this->session->userdata('nfc_admin_session');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Welcome to dashboard.</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                    </div><!-- /.box-body -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- Main row -->
        <div class="row">

        </div><!-- /.row (main row) -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">

    function save_data()
    {
        $('div .error').html('');
    }
</script>