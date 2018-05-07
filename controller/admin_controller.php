<?php
  require_once('models/session.php');
  require_once('models/user.php');
  require_once('models/user_log.php');
  require_once('models/config.php');
  require_once('models/auth.php');
  class AdminController {
    public $sessions;
    public $auth;
    
    public function __construct() {
      session_start(); // Must be done inside constructor before initializing class var
      $this->sessions = Session::getSessionsByUid($_SESSION['uid']);
      $this->auth = new Auth($this->sessions);
    }
    public function home() {
      if (strcmp($this->auth->getAuth(), 'admin') == 0) {
        $this->auth->continueSession();

        require_once('models/ip_block.php');
        $number_of_block = Ip_block::getNumberOfBlock();
        $number_of_user = User::getNumberOfUser();
        $number_of_session = Session::getNumberOfSession();
        $current_page = 'account';
        require_once('views/admin/header.php');
        require_once('views/admin/home.php');
        require_once('views/admin/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function ip_block() {
      if (strcmp($this->auth->getAuth(), 'admin') == 0) {
        $this->auth->continueSession();
        
        $get_limit = isset($_GET['limit']) && strcmp($_GET['limit'], "")!=0 ? $_GET['limit']: 10;
        $get_offset = isset($_GET['offset']) && strcmp($_GET['offset'], "")!=0 ? $_GET['offset']: 0;
        $get_type = isset($_GET['type']) && strcmp($_GET['type'], "")!=0 ? $_GET['type']: "";
        
        require_once('models/ip_block.php');
        require_once('views/pagination.php');
        $number_of_session = 1;
        switch ($get_type) {
          case 'active':
            $ip_blocks = Ip_block::all($get_limit, $get_offset, 'active');
            $number_of_session = Ip_block::getNumberOfBlock('active');
          break;
          case 'expired':
            $ip_blocks = Ip_block::all($get_limit, $get_offset, 'expired');
            $number_of_session = Ip_block::getNumberOfBlock('expired');
          break;
          default:
            $ip_blocks = Ip_block::all($get_limit, $get_offset);
            $number_of_session = Ip_block::getNumberOfBlock();
          break;
        }
        $pagination = new Pagination($get_limit, $get_offset, $number_of_session);
        $current_page = 'other';
        require_once('views/ajax_post.php');
        require_once('views/admin/header.php');
        require_once('views/admin/ip_block.php');
        require_once('views/admin/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function user() {
      if (strcmp($this->auth->getAuth(), 'admin') == 0) {
        $this->auth->continueSession();

        $get_cred = isset($_GET['cred']) && strcmp($_GET['cred'], "")!=0 ? $_GET['cred']: NULL;
        $get_limit = isset($_GET['limit']) && strcmp($_GET['limit'], "")!=0 ? $_GET['limit']: 10;
        $get_offset = isset($_GET['offset']) && strcmp($_GET['offset'], "")!=0 ? $_GET['offset']: 0;

        require_once('models/game_config.php');
        require_once('views/pagination.php');
        
        if (!ISSET($_GET['cred'])) {
          $users = User::all($get_limit, $get_offset);
          $number_of_user = User::getNumberOfUser();

          $total_user = $number_of_user['player']+$number_of_user['admin'];
          $pagination = new Pagination($get_limit, $get_offset, $total_user);
        }
        else {
          $uid = User::findUid($_GET['cred']);
          $users = User::findSingleAsList($uid);
          $pagination = new Pagination(1, 1, 1);
        }
        $current_page = 'other';
        require_once('views/ajax_post.php');
        require_once('views/admin/header.php');
        require_once('views/admin/user.php');
        require_once('views/admin/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function session() {
      if (strcmp($this->auth->getAuth(), 'admin') == 0) {
        $this->auth->continueSession();

        $get_uid = isset($_GET['uid']) && strcmp($_GET['uid'], "")!=0 ? $_GET['uid']: NULL;
        $get_limit = isset($_GET['limit']) && strcmp($_GET['limit'], "")!=0 ? $_GET['limit']: 10;
        $get_offset = isset($_GET['offset']) && strcmp($_GET['offset'], "")!=0 ? $_GET['offset']: 0;

        $number_of_session;
        require_once "models/game_config.php";
        if (!ISSET($_GET['uid'])) {
          $sessions = Session::all($get_limit, $get_offset);
          $number_of_session = Session::getNumberOfSession();
        }
        else {
          $sessions = Session::all($get_limit, $get_offset, $_GET['uid']);
          $number_of_session = Session::getNumberOfSession($_GET['uid']);
          $user = User::find($_GET['uid']);
        }
        require_once('views/pagination.php');
        $pagination = new Pagination($get_limit, $get_offset, $number_of_session['sessions']);
        $current_page = 'other';
        require_once('views/ajax_post.php');
        require_once('views/admin/header.php');
        require_once('views/admin/session.php');
        require_once('views/admin/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
    public function user_log() {
      if (strcmp($this->auth->getAuth(), 'admin') == 0) {
        $this->auth->continueSession();

        $get_cred = isset($_GET['cred']) && strcmp($_GET['cred'], "")!=0 ? $_GET['cred']: NULL;
        $get_type = isset($_GET['type']) && strcmp($_GET['type'], "")!=0 ? $_GET['type']: NULL;
        $get_ip = isset($_GET['ip']) && strcmp($_GET['ip'], "")!=0 ? $_GET['ip']: NULL;
        $get_min_date = isset($_GET['min_date']) && strcmp($_GET['min_date'], "")!=0 ? $_GET['min_date']: NULL;
        $get_max_date = isset($_GET['max_date']) && strcmp($_GET['max_date'], "")!=0 ? $_GET['max_date']: NULL;
        $get_min_importance = isset($_GET['min_importance']) && strcmp($_GET['min_importance'], "")!=0 ? $_GET['min_importance']: 1;
        $get_limit = isset($_GET['limit']) && strcmp($_GET['limit'], "")!=0 ? $_GET['limit']: 100;
        $get_offset = isset($_GET['offset']) && strcmp($_GET['offset'], "")!=0 ? $_GET['offset']: 0;

        // $user_logs = User_log::getLogs($_GET['cred'], $_GET['type'], $_GET['ip'], $_GET['min_date'], $_GET['max_date'], $_GET['min_importance'], $_GET['limit'], $_GET['offset']);
        $user_logs = User_log::getLogs($get_cred, $get_type, $get_ip, $get_min_date, $get_max_date, $get_min_importance, $get_limit, $get_offset);
        $number_of_logs = User_log::getNumberOfLogs($get_cred, $get_type, $get_ip, $get_min_date, $get_max_date, $get_min_importance);
      
        require_once('views/pagination.php');
        $pagination = new Pagination($get_limit, $get_offset, $number_of_logs);
        $current_page = 'other';
        require_once('views/ajax_post.php');
        require_once('views/admin/header.php');
        require_once('views/admin/user_log.php');
        require_once('views/admin/footer.php');
      }
      else {
        $new_location = $this->auth->manageUnauthorized();
        require_once('views/unauthorized_redirect.php');
      }
    }
  }
?>