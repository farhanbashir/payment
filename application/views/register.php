<?php 
$first_name = "";
$last_name 	= "";
$email 		= "";
$password 	= "";
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<title>iCannPay</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
	<link rel="apple-touch-icon" href="pages/ico/60.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo pages_url('ico/76.png');?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo pages_url('ico/120.png');?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo pages_url('ico/152.png');?>">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="<?php echo asset_url('plugins/pace/pace-theme-flash.css');?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo asset_url('plugins/boostrapv3/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo asset_url('plugins/font-awesome/css/font-awesome.css');?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo asset_url('plugins/jquery-scrollbar/jquery.scrollbar.css');?>" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo asset_url('plugins/bootstrap-select2/select2.css');?>" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo asset_url('plugins/switchery/css/switchery.min.css');?>" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo pages_url('css/pages-icons.css');?>" rel="stylesheet" type="text/css">
	<link class="main-stylesheet" href="<?php echo pages_url('css/pages.css');?>" rel="stylesheet" type="text/css" />
	<!--[if lte IE 9]>
	<link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<script type="text/javascript">
		window.onload = function()
		{
			// fix for windows 8
			if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
			document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="<?php echo pages_url('css/windows.chrome.fix.css');?>" />'
		}
	</script>
</head>
<body class="fixed-header ">
	
	<div class="login-wrapper ">
	
		<!-- START Login Background Pic Wrapper-->
		<div class="bg-pic">
			
			<!-- START Background Pic-->
			<img src="<?php echo asset_url('img/demo/hong-kong-busy-street.jpg');?>" data-src="<?php echo asset_url('img/demo/hong-kong-busy-street.jpg');?>" data-src-retina="<?php echo asset_url('img/demo/hong-kong-busy-street.jpg');?>" alt="" class="lazy" />
			<!-- END Background Pic-->
			
			<!-- START Background Caption-->
			<div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
				<h2 class="semi-bold text-white">iCannPay started on the busy streets of Hong Kong</h2>
				<p class="small">iCannPay can virtually be used for any business you can dream of and imagine.</p>
				<p class="small">&copy;2016 Artisan Markets, Limited  All Right Reserved</p>
			</div>
			<!-- END Background Caption-->
			
		</div>		
		<!-- END Login Background Pic Wrapper-->
		
		<!-- START Login Right Container-->		
		<div class="login-container bg-white">
		
			<div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">

				<a href="<?php echo site_url(); ?>">
					<img src="<?php echo asset_url('img/logo.png');?>" alt="logo" data-src="<?php echo asset_url('img/logo.png');?>" data-src-retina="<?php echo asset_url('img/logo_2x.png');?>">
				</a>
				
				<?php
					if($error!='')
					{
						?>
							<p>
								<div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
									<button class="close" data-dismiss="alert"></button>
									<?php echo $error; ?>
								</div>
							</p>
						<?php
					}
				?>

				<!-- START Login Form -->
				<form id="form-register" class="p-t-15" role="form" action="<?php echo site_url('auth/register'); ?>"  method="post">
					
					<div class="form-group ">
						<label>First Name</label>
						<div class="controls">
							<input type="text" class="form-control" name="first_name" value="<?php echo $last_name; ?>" required>
						</div>
					</div>

					<div class="form-group ">
						<label>Last Name</label>
						<div class="controls">
							<input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required="required">
						</div>
					</div>

					<div class="form-group ">
						<label>Email Address</label>
						<div class="controls">
							<input type="email" name="email" class="form-control" autocomplete="off" value="<?php echo $email; ?>" required>
						</div>
					</div>					
					
					<div class="form-group ">
						<label>Password</label>
						<div class="controls">
							<input type="password" class="form-control" name="password" autocomplete="off" value="<?php echo $password; ?>" required>
						</div>
					</div>
					
					
					<button class="btn btn-primary btn-cons m-t-10" name="btn-submit" value="submit" type="submit">Register</button>
				</form>
				<!--END Login Form-->
			</div>
		</div>
		<!-- END Login Right Container-->
		
		
	  
	</div> <!-- End: .login-wrapper -->
	
    <!-- BEGIN VENDOR JS -->
    <script src="<?php echo asset_url('plugins/pace/pace.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/modernizr.custom.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-ui/jquery-ui.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/boostrapv3/js/bootstrap.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery/jquery-easy.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-unveil/jquery.unveil.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-bez/jquery.bez.min.js');?>"></script>
    <script src="<?php echo asset_url('plugins/jquery-ios-list/jquery.ioslist.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-actual/jquery.actual.min.js');?>"></script>
    <script src="<?php echo asset_url('plugins/jquery-scrollbar/jquery.scrollbar.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('plugins/bootstrap-select2/select2.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('plugins/classie/classie.js');?>"></script>
    <script src="<?php echo asset_url('plugins/switchery/js/switchery.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-validation/js/jquery.validate.min.js');?>" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <script src="<?php echo pages_url('js/pages.min.js');?>"></script>
    <script>
		$(function()
		{
		  $('#form-register').validate()
		})
    </script>
</body>
</html>