<?php
  session_start();
  include_once "config/superglobal_var.php";
  include_once "config/db_connection.php";
  include_once "libraries/user_controller.php";

  $uc = new user_controller($db);
  $uc->check_access();

	include_once "config/app_param.php";  
	$_BASE_PARAMS = $_APP_PARAM['base'];
  $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
	$_ASSETS_PATH = "assets/";
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $_BASE_PARAMS['sys_name_full'];?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">    
    <!-- DataTables -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>dist/css/skins/_all-skins.min.css">    

    <link rel="stylesheet" href="<?=$_ASSETS_PATH?>dist/css/preload-style.css">

    <!-- jQuery 2.1.4 -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

    <!-- Bootstrap 3.3.4 -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>bootstrap/js/bootstrap.min.js"></script>
    
    <!-- AdminLTE App -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>dist/js/app.min.js"></script>    
    
    <!-- AdminLTE for demo purposes -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>dist/js/demo.js"></script>

    <script type="text/javascript" src="<?=$_ASSETS_PATH?>js/ajax_manipulating.js"></script>
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>js/global_function.js"></script>
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>js/jquery.validate.js"></script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-yellow sidebar-mini">

    <!-- PRELOAD OBJECT -->
    <div id="preloadAnimation" class="preload-wrapper">
        <div id="preloader_1">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>        
        </div>
    </div>
    <!-- /PRELOAD OBJECT -->

    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><?php echo $_BASE_PARAMS['sys_name_acr2'];?></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Admin</b><?php echo $_BASE_PARAMS['sys_name_acr1'];?></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span><font style="margin-left:10px;"><?php echo strtoupper($_SYSTEM_PARAMS['nama_instansi']); ?></font>
          </a>

          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?=$_ASSETS_PATH?>dist/img/avatar5.png" class="user-image" alt="User Image">                  
                  <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?=$_ASSETS_PATH?>dist/img/avatar5.png" class="img-circle" alt="User Image">                    
                    <p>
                      <?php echo $_SESSION['username']." - ".$_SESSION['user_type']; ?>
                    </p>
                  </li>
                  
                  <!-- Menu Body -->
                  <!--li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li-->

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout_process.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?=$_ASSETS_PATH?>dist/img/avatar5.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?=$_SESSION['username']?></p>
              <a href="#"><i class="fa fa-user text-success"></i> <?=$_SESSION['user_type']?></a>
            </div>
          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php
              include_once "templates/menu.php";
            ?>
          
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php include_once "content_manager.php"; ?>
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> <?=$_BASE_PARAMS['version']?>
        </div>
        <strong>Copyright &copy; <?=$_BASE_PARAMS['release_year']?> <a href="#"><?=$_BASE_PARAMS['sys_name_full']?></a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
                
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->
        
    
    <!-- DATATABLES -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datatables/dataTables.bootstrap.min.js"></script>
        
    <!-- Noty -->
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/noty/themes/default.js"></script>

    <script>
      $(document).ready(function () {
        $("#"+ajax_manipulate.dataTable_id).DataTable();
        
        $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false
        });
      });
    </script>
  </body>
</html>
