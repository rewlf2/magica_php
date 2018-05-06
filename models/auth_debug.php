<?php
  if (!isset($_SESSION['uid'])) {
    session_destroy();
    header("Location: ?");
  }
  else {
      // Get ip of this user
      if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
      }
      elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
          $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      }
      else {
          $ip = $_SERVER["REMOTE_ADDR"];
      }

      // Get session which has same session_id and series_id
      echo "Old local session_id: ".$_SESSION['session_id']."<br/>";
      echo "Old local series_id: ".$_SESSION['series_id']."<br/>";
      echo "Session creation date: ".$_SESSION['date']."<br/><br/>";
      $session_id = $_SESSION['session_id'];
      $series_id = $_SESSION['series_id'];

      $matched_session = false;
      $rogue_session = false;
      $multi_session_same_ip = false;
      $otherdevice_session = false;
      $carried_session = false;
      
      $count = 1;
      foreach($sessions as $session) {
        echo " ".$count." - IP: ".$session->ip.", last login date: ".$session->date."<br/>";
        $count ++;
        $local_session = NULL;
        if (strcmp($session->ip, $ip) ==0) {
          if (strcmp($session->session_id, $session_id) ==0) {
            if (strcmp($session->series_id, $series_id) ==0) {
              echo "Matched session<br/>";
              $matched_session = true;
            }
            else {
              echo "Possible stolen session<br/>";
              $rogue_session = true;
            }
          }
          else {
            echo "Different session from same device<br/>";
            $multi_session_same_ip = true;
          }
        }
        else {
          if (strcmp($session->session_id, $session_id) ==0) {
            if (strcmp($session->series_id, $series_id) ==0) {
              echo "Matched session from another device<br/>";
              $carried_session = true;
            }
            else {
              echo "Possible stolen session<br/>";
              $rogue_session = true;
            }
          }
          else {
            echo "Different session from different device<br/>";
            $otherdevice_session = true;
          }
        }
      }
      echo "<br/>";

      if (!$matched_session && !$carried_session) {
        // No loggable sessions
        session_destroy();
        header("Location: ?");
      }
      else {
        // Booleans can never be true at the same time since session_id must be unique
        if ($matched_session) {
            echo "Session exists, refreshing session.<br/>";
            // Session exists on same device, same browser
            $new_series_id = Session::refreshSession($session_id);
            $_SESSION['series_id'] = $new_series_id;
            echo "New local series_id: ".$_SESSION['series_id']."<br/><br/>";
        }
        else { //$carried_session == true
            echo "Session match on different IP.<br/>";
            // Wire session ip to local ip and continue
            $new_series_id = Session::carrySession($session_id, $ip);
        }
        // Session alert can only be triggered when user is authenticated to login
        if ($rogue_session) {
            echo "Session compromised.<br/>";
            // Rogue access detected, should put into a log and kill all sessions of user afterward, and warn user to change password and check device safety
            // The random character generator has randomness of 2^32, should be impossible to roll same session_id twice unless bruted
        }
        if ($multi_session_same_ip) {
            echo "Multiple sessions on same device.<br/>";
            // Should delete sessions except the latest one
        }
      }
  }
?>