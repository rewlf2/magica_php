<div class="site-wrapper">

  <div class="site-wrapper-inner">
<?php
$need_register = false;
if (isset($_SESSION['need_register'])) {
  if ($_SESSION['need_register']) {
    $_SESSION['need_register'] = false;
    $need_register = true;
  }
}

if ($need_register)
  echo '<h3>Thank you for registering.<br/><a href="?">You may now log in to the game.</a></h3>';
else
  echo '<h3>Please register or sign in.</h3>';
?>