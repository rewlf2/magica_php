<?php
  require_once('connection.php');

  if (isset($_GET['controller']))
    $controller = $_GET['controller'];
  else
    $controller = 'portal';

  if (isset($_GET['action']))
    $action     = $_GET['action'];
  else
    $action     = 'home';
  
  require_once('views/layout.php');
?>