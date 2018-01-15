<?php
  session_start();
  include_once "config/superglobal_var.php";
  include_once "config/db_connection.php";
  include_once "libraries/user_controller.php";
  include_once "helpers/mix_helper.php";
  include_once "libraries/global_obj.php";

  $uc = new user_controller($db);  
  $global = new global_obj($db);
  
  $ip = get_ip();

  $activity = "logout";
  $global->insert_logs($activity,$ip);

  $uc->logout_process();
  
?>
