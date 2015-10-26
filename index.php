<?php
   ini_set('display_errors',1);
   ini_set('register_globals',0);
   ini_set('error_reporting',E_ALL);
   //от xss
   ini_set('session.cookie_httponly',true);

   require_once('application/bootstrap.php');

?>