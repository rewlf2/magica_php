<?php
  class Session {
    // they are public so that we can access them using $post->attri directly
    public $uid;
    public $session_id;
    public $series_id;
    public $ip;
    public $date;
    public $hour;
    public $minute;
    public $second;

    public function __construct($uid, $session_id, $series_id, $ip, $date, $hour, $minute, $second) {
      $this->uid             = $uid;
      $this->session_id      = $session_id;
      $this->series_id       = $series_id;
      $this->ip              = $ip;
      $this->date            = $date;
      $this->hour            = $hour;
      $this->minute          = $minute;
      $this->second          = $second;
    }

    public static function all($limit = 50, $offset = 0, $uid = NULL) {
      $list = [];
      $db = Db::getInstance();

      $query = 'SELECT session_id, uid, ip, date, HOUR(TIMEDIFF(NOW(), date)) as hour, MINUTE(TIMEDIFF(NOW(), date)) as minute, SECOND(TIMEDIFF(NOW(), date)) as second FROM '.Db::getPrefix().'session';
      if (!is_null($uid) && strcmp($uid, "")!=0)
        $query .= ' WHERE uid=:uid';

      $query .= ' ORDER BY uid ASC LIMIT :limit OFFSET :offset';

      $req = $db->prepare($query);
      $req->bindValue(':limit', intval($limit), PDO::PARAM_INT);
      $req->bindValue(':offset', intval($offset), PDO::PARAM_INT);
      if ($uid >0)
        $req->bindValue(':uid', intval($uid), PDO::PARAM_INT);
      
      $req->execute();

      // we create a list of Post objects from the database results
      foreach($req->fetchAll() as $session) {
        $list[] = new Session($session['uid'], $session['session_id'], "", $session['ip'], $session['date'], $session['hour'], $session['minute'], $session['second']);
      }
      return $list;
    }

    public static function getDistinctIp($limit = 20, $offset = 0, $uid = NULL) {
        $list = [];
        $db = Db::getInstance();
  
        $query = 'SELECT DISTINCT(ip) as ip FROM '.Db::getPrefix().'session';
        if (!is_null($uid) && strcmp($uid, "")!=0)
          $query .= ' WHERE uid=:uid';
  
        $query .= ' ORDER BY uid ASC LIMIT :limit OFFSET :offset';
  
        $req = $db->prepare($query);
        $req->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $req->bindValue(':offset', intval($offset), PDO::PARAM_INT);
        if ($uid >0)
          $req->bindValue(':uid', intval($uid), PDO::PARAM_INT);
        
        $req->execute();
  
        // we create a list of Post objects from the database results
        foreach($req->fetchAll() as $session) {
          $list[] = $session['ip'];
        }
        return $list;
    }

    public static function insert($uid) {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        $session_id = substr(md5(rand()), 0, 16);
        $series_id = substr(md5(rand()), 0, 16);
      
        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO '.Db::getPrefix().'session (uid, ip, session_id, series_id) VALUES (:uid, :ip, :session_id, :series_id)');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('uid' => $uid, 'ip' => $ip, 'session_id' => $session_id, 'series_id' => $series_id)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return $session_id;
    }

    public static function getSession($session_id) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT uid, session_id, series_id, ip, date, HOUR(TIMEDIFF(NOW(), date)) as hour, MINUTE(TIMEDIFF(NOW(), date)) as minute, SECOND(TIMEDIFF(NOW(), date)) as second FROM '.Db::getPrefix().'session WHERE session_id = :sessionid');

      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('sessionid' => $session_id));
        $session = $req->fetch();

        return new Session($session['uid'], $session['session_id'], $session['series_id'], $session['ip'], $session['date'], $session['hour'], $session['minute'], $session['second']);
    }

    /*
    TODO:
    carrySession(session_id, ip) - return new_series_id
    destroySession - doesn't return
    */

    public static function getSessionsByUid($uid) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT uid, session_id, series_id, ip, date, HOUR(TIMEDIFF(NOW(), date)) as hour, MINUTE(TIMEDIFF(NOW(), date)) as minute, SECOND(TIMEDIFF(NOW(), date)) as second FROM '.Db::getPrefix().'session WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));

      $list = [];
      foreach($req->fetchAll() as $session) {
        $list[] = new Session($session['uid'], $session['session_id'], $session['series_id'], $session['ip'], $session['date'], $session['hour'], $session['minute'], $session['second']);
      }
      return $list;

    }

    public static function refreshSession($session_id) {
        $new_series_id = substr(md5(rand()), 0, 16);
      
        $db = Db::getInstance();
        $req = $db->prepare('UPDATE '.Db::getPrefix().'session SET series_id=:new_series_id, date=NOW() WHERE session_id = :session_id');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('new_series_id' => $new_series_id, 'session_id' => $session_id)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return $new_series_id;
    }

    public static function carrySession($session_id, $ip) {
        $new_series_id = substr(md5(rand()), 0, 16);
      
        $db = Db::getInstance();
        $req = $db->prepare('UPDATE '.Db::getPrefix().'session SET series_id=:new_series_id, date=NOW(), ip=:ip WHERE session_id = :session_id');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('new_series_id' => $new_series_id, 'ip' => $ip, 'session_id' => $session_id)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return $new_series_id;
    }

    public static function destroySession($session_id) {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM '.Db::getPrefix().'session WHERE session_id=:session_id');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('session_id' => $session_id)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return "Success";
    }

    public static function destroyAllSession($uid) {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM '.Db::getPrefix().'session WHERE uid=:uid');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('uid' => $uid)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return "Success";
    }

    public static function isolateSessionByIp($uid, $ip) {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM '.Db::getPrefix().'session WHERE uid=:uid AND ip<>:ip');
        // the query was prepared, now we replace :id with our actual $id value
        if (!$req->execute(array('uid' => $uid, 'ip' => $ip)))
            return "Error writing collection: ".mysqli_stmt_error($req);
        else
            return "Success";
    }

    public static function getNumberOfSession($uid = NULL) {
  
        $db = Db::getInstance();
        $query = 'SELECT count(DISTINCT(uid)) as uids, count(session_id) as sessions FROM '.Db::getPrefix().'session';
        if (!is_null($uid)) {
            $query .= " WHERE uid=:uid";
            $req = $db->prepare($query);
            $req->execute(array('uid' => $uid));
        }
        else {
            $req = $db->query($query);
        }

        $res = $req->fetch();
        return $res;
    }
}