<?php
  require_once('models/session.php');
  require_once('models/user.php');
  require_once('models/user_log.php');
  require_once('models/config.php');
  require_once('models/auth.php');
  class GameMenuController {
    public $sessions;
    public $auth;
    public $config;
    
    public function __construct() {
      session_start(); // Must be done inside constructor before initializing class var
      $this->sessions = isset($_SESSION['uid']) ? Session::getSessionsByUid($_SESSION['uid']) :NULL;
      $this->auth = new Auth($this->sessions);
      $this->config = new Config();
    }
    public function signin_success() {
      $result = $this->auth->createSession();
      if (strcmp($result, 'Not logged in') !=0 && strcmp($result, 'expired') !=0) {
        $auth_level = 'player';
        $current_page = 'other';
        require_once('views/game_menu/header.php');
        require_once('views/game_menu/signin_success.php');
        require_once('views/game_menu/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
        session_destroy();
      }
    }
    public function signout() {
      require_once('views/game_menu/signout.php');
    }
    public function home() {
      $auth_level = $this->auth->getAuth();
      if (strcmp($auth_level, 'admin') ==0|| strcmp($auth_level, 'player') ==0) {
        $this->auth->continueSession();
        
        $current_page = 'home';
        require_once('views/game_menu/header.php');
        require_once('views/game_menu/home.php');
        require_once('views/game_menu/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function setting() {
      $auth_level = $this->auth->getAuth();
      if (strcmp($auth_level, 'admin') ==0|| strcmp($auth_level, 'player') ==0) {
        $this->auth->continueSession();
        
        $user = User::find($_SESSION['uid']);
        $number_of_session = Session::getNumberOfSession($_SESSION['uid']);

        $current_page = 'setting';
        require_once('views/ajax_post.php');
        require_once('views/game_menu/header.php');
        require_once('views/game_menu/setting.php');
        require_once('views/game_menu/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function setting_session() {
      $auth_level = $this->auth->getAuth();
      if (strcmp($auth_level, 'admin') ==0|| strcmp($auth_level, 'player') ==0) {
        $this->auth->continueSession();

        $get_limit = isset($_GET['limit']) && strcmp($_GET['limit'], "")!=0 ? $_GET['limit']: 20;
        $get_offset = isset($_GET['offset']) && strcmp($_GET['offset'], "")!=0 ? $_GET['offset']: 0;
        
        $sessions = Session::all($get_limit, $get_offset, $_SESSION['uid']);
        $number_of_session = Session::getNumberOfSession($_SESSION['uid']);
        require_once('views/pagination.php');
        $pagination = new Pagination($get_limit, $get_offset, $number_of_session['sessions']);

        $current_page = 'other';
        require_once('views/ajax_post.php');
        require_once('views/game_menu/header.php');
        require_once('views/game_menu/setting_session.php');
        require_once('views/game_menu/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function auth_test() {
      if (strcmp($auth_level, 'admin') ==0|| strcmp($auth_level, 'player') ==0) {
        $this->auth->continueSession();

        $current_page = 'other';
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function auth_debug() {
      $sessions = Session::getSessionsByUid($_SESSION['uid']);
      require_once('models/auth_debug.php'); // Has dependence of Session
    }
  }
?>