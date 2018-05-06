<?php
// Dependencies:
// Session
// User
// User_log
class Auth {
    public $ip;
    public $sessions;
    public $uid;
    public $session_id;
    public $series_id;

    public function __construct($sessions) {
        // Get ip of this user
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $this->ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $this->ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else {
            $this->ip = $_SERVER["REMOTE_ADDR"];
        }

        $this->sessions = $sessions;

        if (isset($_SESSION['uid']))
            $this->uid = $_SESSION['uid'];

        if (isset($_SESSION['session_id']))
            $this->session_id = $_SESSION['session_id'];

        if (isset($_SESSION['series_id']))
            $this->series_id = $_SESSION['series_id'];
    }

    public function getAuth() {
        $result = 'Not logged in';
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            $result = 'Not logged in';
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->session_id, $this->session_id) ==0) {
                    if (strcmp($session->series_id, $this->series_id) ==0) {
                        $result = User::getRole($this->uid);
                    }
                }
            }
        }
        return $result;
    }

    public function createSession() {
        $result = 'Not logged in';
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            if (isset($_SESSION['need_session'])) {
                if ($_SESSION['need_session']) {
                    $_SESSION['need_session'] = false;
                    // Create new session
                    $session_handle = Session::insert($this->uid);
                    $ss = Session::getSession($session_handle);

                    $_SESSION['session_id'] = $ss->session_id;
                    $_SESSION['series_id'] = $ss->series_id;
                    $_SESSION['ip'] = $ss->ip;
                    $_SESSION['date'] = $ss->date;
                    $this->session_id = $ss->session_id;
                    $this->series_id = $ss->series_id;
                    $this->ip = $ss->ip;
                    $result = 'Create';
                    User_log::insert($_SESSION["uid"], "Login", "User signed in, granted new session", 1, NULL);
                }
            }
            else {
                User_log::insert(NULL, "Access", "Attempting to access unauthorized page: ".$_GET['controller'].",".$_GET['action'], 2, NULL);
            }
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->ip, $this->ip) ==0) {
                    if (strcmp($session->session_id, $this->session_id) ==0) {
                        if (strcmp($session->series_id, $this->series_id) ==0) {
                            $new_series_id = Session::refreshSession($this->session_id);
                            $_SESSION['series_id'] = $new_series_id;
                            $this->session_id = $new_series_id;
                            $result = 'Refresh';
                            User_log::insert($_SESSION["uid"], "Login", "User relogin at same device", 1, NULL);
                        }
                    }
                }
                else {
                    if (strcmp($session->session_id, $this->session_id) ==0) {
                        if (strcmp($session->series_id, $this->series_id) ==0) {
                            $new_series_id = Session::carrySession($this->session_id, $this->ip);
                            $_SESSION['series_id'] = $new_series_id;
                            $this->session_id = $new_series_id;
                            $result = 'Carry';
                            User_log::insert($_SESSION["uid"], "Login", "User relogin at different device", 1, NULL);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function continueSession($ajax = false) {
        $result = 'Not logged in';
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            $result = 'Not logged in';
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->ip, $this->ip) ==0) {
                    if (strcmp($session->session_id, $this->session_id) ==0) {
                        if (strcmp($session->series_id, $this->series_id) ==0) {
                            $new_series_id = Session::refreshSession($this->session_id);
                            $_SESSION['series_id'] = $new_series_id;
                            $this->session_id = $new_series_id;
                            $result = 'Refresh';
                        }
                    }
                }
                else {
                    if (strcmp($session->session_id, $this->session_id) ==0) {
                        if (strcmp($session->series_id, $this->series_id) ==0) {
                            $new_series_id = Session::carrySession($this->session_id, $this->ip);
                            $_SESSION['series_id'] = $new_series_id;
                            $this->session_id = $new_series_id;
                            $result = 'Carry';
                            User_log::insert($_SESSION["uid"], "Login", "User relogin at different device", 1, NULL);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function manageUnauthorized($ajax = false) {
        $header = 'Location: ?';

        if (!is_null($this->uid)&&!is_null($this->session_id)&&!is_null($this->series_id)) {
            foreach($this->sessions as $session) {
                if (strcmp($session->session_id, $this->session_id) ==0) {
                    if (strcmp($session->series_id, $this->series_id) ==0) {
                        // At this point the user must be logged in, since this system has no more than 2 tiers of roles, user is redirected towards game_menu main page
                        $header = 'Location: ?controller=game_menu&action=auth_test';
                    }
                }
            }
            if ($ajax)
                User_log::insert($this->uid, "Access", "Attempting to access unauthorized ajax: ".basename($_SERVER['PHP_SELF']), 3, NULL);
            else
			    User_log::insert($this->uid, "Access", "Attempting to access unauthorized page: ".$_GET['controller'].",".$_GET['action'], 3, NULL);
        }
        else {
            if ($ajax)
                User_log::insert(NULL, "Access", "Attempting to access unauthorized ajax: ".basename($_SERVER['PHP_SELF']), 3, NULL);
            else
			    User_log::insert(NULL, "Access", "Attempting to access unauthorized page: ".$_GET['controller'].",".$_GET['action'], 3, NULL);
        }
        return $header;
    }

    public function hasRogue() {
        $result = false;
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            $result = false;
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->session_id, $this->session_id) ==0) {
                    if (strcmp($session->series_id, $this->series_id) !=0) {
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }

    public function hasOtherDevice() {
        $result = false;
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            $result = false;
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->ip, $this->ip) !=0) {
                    if (strcmp($session->session_id, $this->session_id) !=0) {
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }

    public function hasMultiSession() {
        $result = false;
        
        if (is_null($this->uid)||is_null($this->session_id)||is_null($this->series_id)) {
            $result = false;
        }
        else {
            foreach($this->sessions as $session) {
                if (strcmp($session->ip, $this->ip) ==0) {
                    if (strcmp($session->session_id, $this->session_id) !=0) {
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }
}
?>