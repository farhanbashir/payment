<!DOCTYPE html>
<html class="bg-light-gray">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="<?php echo asset_css('bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo asset_css('font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo asset_css('AdminLTE.css'); ?>" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-light-gray">

        <div class="form-box" id="login-box">
             <img src="<?php echo asset_img('logo-small.png');?>" class="logo icon" style="margin: -29px 0px 30px 109px;"alt="User Image" />
            <br/>
            <div class="header">Sign In</div>
            <form action="<?php echo base_url(); ?>index.php/auth/check_database" method="post">
                <div class="body bg-gray">
                    <?php
                    if (isset($error)) {
                        echo '<div class="callout callout-danger">
                                ' . $error . '
                            </div>';
                    }
                    ?>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="User ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                    <div class="form-group">
                        <input type="checkbox" name="remember_me"/> Remember me
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  

<!-- <p><a href="#">I forgot my password</a></p>

<a href="register.html" class="text-center">Register a new membership</a> -->
                </div>
            </form>
        </div>

        <script src="<?php echo asset_js('jquery.min.js'); ?>"></script>
        <script src="<?php echo asset_js('bootstrap.min.js'); ?>" type="text/javascript"></script>

    </body>
</html>