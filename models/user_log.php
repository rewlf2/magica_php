<?php
  class User_log {
    // they are public so that we can access them using $post->attri directly
    public $log_id;
    public $uid;
    public $type;
    public $ip;
    public $description;
    public $date;
    public $importance;
    
    public function __construct($log_id, $uid, $type, $ip, $description, $date, $importance) {
      $this->log_id        = $log_id;
      $this->uid            = $uid;
      $this->type           = $type;
      $this->ip             = $ip;
      $this->description    = $description;
      $this->date           = $date;
      $this->importance     = $importance;
    }

    public static function all() {
      $list = [];
      $db = Db::getInstance();
      $req = $db->query('SELECT * FROM '.Db::getPrefix().'user_log ORDER BY date DESC LIMIT 100');

      // we create a list of Post objects from the database results
      foreach($req->fetchAll() as $user_log) {
        $list[] = new User_log($user_log['log_id'], $user_log['uid'], $user_log['type'], $user_log['ip'], $user_log['description'], $user_log['date'], $user_log['importance']);
      }
      return $list;
    }

    public static function getLogs($cred = NULL, $type = null, $ip = NULL, $min_date = NULL, $max_date = NULL, $min_importance = 1, $limit = 100, $offset = 0) {
      $list = [];
      $query_array = [];
      $db = Db::getInstance();
      $query = 'SELECT * FROM '.Db::getPrefix().'user_log WHERE importance>=:min_importance';
      $query_array['min_importance'] = $min_importance;

      if (!is_null($cred)) {
        $uid = User::findUid($cred);
        $query .= ' AND uid=:uid';
        $query_array['uid'] = $uid;
      }
      if (!is_null($type)) {
        $query .= ' AND type=:type';
        $query_array['type'] = $type;
      }
      if (!is_null($ip)) {
        $query .= ' AND ip=:ip';
        $query_array['ip'] = $ip;
      }
      if (!is_null($min_date)) {
        $query .= ' AND date>=:min_date';
        $query_array['min_date'] = $min_date." 00:00:00";
      }
      if (!is_null($max_date)) {
        $query .= ' AND date<=:max_date';
        $query_array['max_date'] = $max_date." 23:59:59";
      }

      $query .= ' ORDER BY date DESC LIMIT '.$limit.' OFFSET '.$offset;

      // echo $query."<br/>";
      // echo var_dump($query_array);
      $req = $db->prepare($query);
      // we create a list of Post objects from the database results
      $req->execute($query_array);
      foreach($req->fetchAll() as $user_log) {
        $list[] = new User_log($user_log['log_id'], $user_log['uid'], $user_log['type'], $user_log['ip'], $user_log['description'], $user_log['date'], $user_log['importance']);
      }
      return $list;
    }

    public static function getNumberOfLogs($cred = NULL, $type = null, $ip = NULL, $min_date = NULL, $max_date = NULL, $min_importance = 1) {
      $db = Db::getInstance();
      $query = 'SELECT count(log_id) as c FROM '.Db::getPrefix().'user_log WHERE importance>=:min_importance';
      $query_array['min_importance'] = $min_importance;

      if (!is_null($cred)) {
        $uid = User::findUid($cred);
        $query .= ' AND uid=:uid';
        $query_array['uid'] = $uid;
      }
      if (!is_null($type)) {
        $query .= ' AND type=:type';
        $query_array['type'] = $type;
      }
      if (!is_null($ip)) {
        $query .= ' AND ip=:ip';
        $query_array['ip'] = $ip;
      }
      if (!is_null($min_date)) {
        $query .= ' AND date>=:min_date';
        $query_array['min_date'] = $min_date." 00:00:00";
      }
      if (!is_null($max_date)) {
        $query .= ' AND date<=:max_date';
        $query_array['max_date'] = $max_date." 23:59:59";
      }
      $req = $db->prepare($query);
      $req->execute($query_array);
      $res = $req->fetch();
      return $res['c'];
    }

    public static function insert($uid, $type, $description, $importance = 1, $backup = NULL) {
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

      $db = Db::getInstance();
      $req = $db->prepare('INSERT INTO '.Db::getPrefix().'user_log 
        (uid, type, ip, description, date, importance, backup) VALUES (:uid, :type, :ip, :description, NOW(), :importance, :backup)');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid, 'type' => $type, 'ip' => $ip, 'description' => $description, 'importance' => $importance, 'backup' => $backup)))
        return "Error writing collection: ".mysqli_stmt_error($req);
      else
        return "Success";
    }
  }
?>