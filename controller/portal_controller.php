<?php
  class PortalController {
    public function home() {
      $current_page = 'home';
      require_once('views/ajax_post.php');
      require_once('views/portal/header.php');
      require_once('views/portal/home.php');
      require_once('views/portal/footer.php');
    }
    public function register() {
      $current_page = 'register';
      session_start();
      $_SESSION['need_register'] = false;
      require_once('models/user.php');
      require_once('views/ajax_post.php');
      require_once('views/portal/header.php');
      require_once('views/portal/register.php');
      require_once('views/portal/footer.php');
    }
    public function register_success() {
      $current_page = 'other';
      session_start();
      require_once('views/portal/header.php');
      require_once('views/portal/register_success.php');
      require_once('views/portal/footer.php');
    }
    public function ranking() {
      $current_page = 'ranking';
      include_once('models/game_config.php');
      require_once('models/user.php');
      $users = User::all();
      require_once('views/portal/header.php');
      require_once('views/portal/ranking.php');
      require_once('views/portal/footer.php');
    }
    public function setting() {
      $current_page = 'setting';
      include_once('models/game_config.php');
      require_once('views/portal/header.php');
      require_once('views/portal/setting.php');
      require_once('views/portal/footer.php');
    }
    public function error() {
      require_once('views/portal/error.php');
    }
    public function test() {
      include_once('models/game_config.php');
      require_once('models/user.php');
      $users = User::all();
      require_once('views/portal/test.php');
    }
  }
?>