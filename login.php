<?php
    include_once "config/superglobal_var.php";
	  include_once "config/app_param.php";
	  $_BASE_PARAMS = $_APP_PARAM['base'];
  	$_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
	  $_ASSETS_PATH = "assets/";
    
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $_BASE_PARAMS['sys_name_full'];?> | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH;?>bootstrap/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="<?=$_ASSETS_PATH;?>dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH;?>plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo" style="position:relative;">
        <!--img src="<?=$_ASSETS_PATH;?>images/logo_mks_xltl.png"/-->
        <br />
        <a href="#">
          <b>SISTEM</b> <small>Penilaian Properti</small> <br style="margin:2px!important;" />
        </a>
        <div style="font-size:0.7em;
                    width:100%;
                    position:absolute;
                    top:85px;">
        <center>KJPP Karmanto & Rekan</center></div>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form id="login-form" action="login_process.php" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" class="form-control" placeholder="Username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-7">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-5">
              <button type="submit" id="login-btn" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>
        
        <a href="#">I forgot my password</a><br>        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?=$_ASSETS_PATH?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <script src="<?=$_ASSETS_PATH?>bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?=$_ASSETS_PATH?>plugins/iCheck/icheck.min.js"></script>
    <!-- Noty -->
    <script src="<?=$_ASSETS_PATH?>plugins/noty/jquery.noty.js"></script>
    <script src="<?=$_ASSETS_PATH?>plugins/noty/layouts/topRight.js"></script>
    <script src="<?=$_ASSETS_PATH?>plugins/noty/themes/default.js"></script>

    <script type="text/javascript">
      (function($) {
        $(document).ready(function(){
            
          var $form=$('#login-form'),$btnLogin=$('#login-btn'),$loadImg="<img src='assets/images/ajax-loaders/ajax-loader-1.gif'/>";
          
          $form.submit(function(){

            $.ajax({
              type:'POST',
              url:$form.attr('action'),
              data:$form.serialize(),
              beforeSend:function(){    
                $btnLogin.html($loadImg+"please wait...");
              },
              success:function(data){                    
                $btnLogin.html("Log in");
                
                if(data=='success')
                {                        
                    noty({text: "I know you and I\'m redirecting you to Dasboard Page "+$loadImg, layout: 'topRight', type: 'success'})
                    window.location.assign('index.php');
                }
                else
                {                    
                    noty({text: 'Sory, I don\'t know you. Please, try again !', layout: 'topRight', type: 'error', timeout:2000});
                }
              }
            });
            return false;
          });
        });
      })(jQuery);
    </script>

    <script>    	
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
