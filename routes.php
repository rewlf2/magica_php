<?php
  function call($controller, $action) {
    // require the file that matches the controller name
    require_once('controller/' . $controller . '_controller.php');

    // create a new instance of the needed controller
    switch($controller) {
      case 'game_menu':
        $controller = new GameMenuController();
      break;
      case 'admin':
        $controller = new AdminController();
      break;
      // Default controller is portal
      case 'portal':
      default:
        $controller = new PortalController();
        break;
    }

    // call the action
    $controller->{ $action }();
  }

  // we're adding an entry for the new controller and its actions
$controllers = array('portal' => ['home', 'register', 'register_success', 'ranking', 'setting', 'error', 'test'],
                     'game_menu' => ['signin_success', 'signout', 'home', 'setting', 'setting_session', 'auth_test', 'auth_debug'],
                     'admin' => ['home', 'ip_block', 'user', 'session', 'user_log']);

  // check that the requested controller and action are both allowed
  // if someone tries to access something else he will be redirected to the error action of the pages controller
  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      call($controller, $action);
    } else {
      call('portal', 'error');
    }
  } else {
    call('portal', 'error');
  }
?>