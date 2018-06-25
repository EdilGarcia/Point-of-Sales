<?php
   session_start();
   unset($_SESSION["user_username"]);
   unset($_SESSION["user_name"]);
   unset($_SESSION["user_id"]);
   unset($_SESSION["user_account_type"]);
   
   header('Refresh: 0; URL = ../index.php');
?>
