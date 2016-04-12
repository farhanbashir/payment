<!DOCTYPE html>
<html>
    <head>
        <script>
            var BASE_URL = "<?php echo base_url(); ?>";
        </script>
      
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="<?php echo asset_css('bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <!--<link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />-->
        <!-- Morris chart -->
        <link href="<?php echo asset_css('morris/morris.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="<?php echo asset_css('jvectormap/jquery-jvectormap-1.2.2.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Date Picker -->
        <link href="<?php echo asset_css('datepicker/datepicker3.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('select2/select2.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!--<link href="<?php // echo asset_css('timepicker/bootstrap-timepicker.css'); ?>" rel="stylesheet" type="text/css" />-->
        <link href="<?php echo asset_css('jquery.timepicker.css'); ?>" rel="stylesheet" type="text/css" />

        <!-- Date Time Picker -->
        <link href="<?php echo asset_css('jquery.datetimepicker.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="<?php echo asset_css('daterangepicker/daterangepicker-bs3.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?php echo asset_css('bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo asset_css('AdminLTE.css?v=0.1'); ?>" rel="stylesheet" type="text/css" />

        <link href="<?php echo asset_css('admin-custom.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('jquery.filer-dragdropbox-theme.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('jquery.filer.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('chosen.min.css'); ?>" rel="stylesheet" type="text/css" />
        <script src="<?php echo asset_js('jquery.min.js'); ?>"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <?php $this->load->view('header'); ?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <?php $this->load->view('sidebar'); ?>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <?php
                //$this->load->view('content');
                echo $content;
                ?>
            </aside>
            <!-- /.right-side -->
        </div>
        <!-- ./wrapper -->

        <!-- add new calendar event modal -->



        <script src="<?php echo asset_js('bootstrap.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('jquery-ui.min.js'); ?>" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <!-- <script src="<?php echo asset_js('raphael-min.js'); ?>"></script> -->
        <!-- <script src="<?php echo asset_js('plugins/morris/morris.min.js'); ?>" type="text/javascript"></script> -->
        <!-- Sparkline -->
        <script src="<?php echo asset_js('plugins/sparkline/jquery.sparkline.min.js'); ?>" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="<?php echo asset_js('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="<?php echo asset_js('plugins/jqueryKnob/jquery.knob.js'); ?>" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="<?php echo asset_js('plugins/daterangepicker/daterangepicker.js'); ?>" type="text/javascript"></script>
        <!-- datepicker -->
        <script src="<?php echo asset_js('plugins/datepicker/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
        <!-- datetimepicker -->
        <script src="<?php echo asset_js('plugins/jquery.datetimepicker.js'); ?>" type="text/javascript"></script>
        <!--<script src="<?php // echo asset_js('plugins/timepicker/bootstrap-timepicker.js');   ?>" type="text/javascript"></script>-->

        <script src="<?php echo asset_js('plugins/jquery.timepicker.js'); ?>" type="text/javascript"></script>


        <!-- Bootstrap WYSIHTML5 -->
        <script src="<?php echo asset_js('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="<?php echo asset_js('plugins/iCheck/icheck.min.js'); ?>" type="text/javascript"></script>

        <!-- AdminLTE App -->
        <script src="<?php echo asset_js('AdminLTE/app.js'); ?>" type="text/javascript"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="<?php echo asset_js('AdminLTE/dashboard.js'); ?>" type="text/javascript"></script>

        <script src="<?php echo asset_js('plugins/select2/select2.js'); ?>" type="text/javascript"></script>

        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo asset_js('AdminLTE/demo.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('AdminLTE/pages.js'); ?>" type="text/javascript"></script>

        <script src="<?php echo asset_js('jquery.filer.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('chosen.jquery.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('admin-custom.js'); ?>" type="text/javascript"></script>


        <script src="<?php echo asset_js('jquery.validate.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo asset_js('validator.js'); ?>" type="text/javascript"></script>
		
		<style>
			.no-print { display: none;}
		</style>
    </body>
</html>
