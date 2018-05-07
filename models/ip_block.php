<?php
  include_once("config.php");
  class Ip_block {
    // they are public so that we can access them using $post->attri directly
    public $ip;
    public $ban_time;
    public $attack_count;
    public $ban_remain;
    public $remarks;
    
  public function __construct($ip, $ban_time, $attack_count, $ban_remain, $remarks) {
    $this->ip           = $ip;
    $this->ban_time     = $ban_time;
    $this->attack_count = $attack_count;
    $this->ban_remain   = $ban_remain;
    $this->remarks      = $remarks;
  }

  public static function all($limit = 50, $offset = 0, $type = "") {
    $list = [];
    $db = Db::getInstance();
    $query = 'SELECT ip, ban_time, attack_count, TIMEDIFF(ban_time,NOW()) as ban_remain, remarks FROM '.Db::getPrefix().'ip_block';

    switch ($type) {
      case 'active':
        $query .= ' WHERE ban_time>NOW()';
      break;
      case 'expired':
        $query .= ' WHERE ban_time<=NOW()';
      break;
    }

    $query .= ' ORDER BY ip ASC LIMIT :limit OFFSET :offset';

    $req = $db->prepare($query);
    $req->bindValue(':limit', intval($limit), PDO::PARAM_INT);
    $req->bindValue(':offset', intval($offset), PDO::PARAM_INT);
    $req->execute();

    // we create a list of Post objects from the database results
    foreach($req->fetchAll() as $ip_block) {
      $list[] = new Ip_block($ip_block['ip'], $ip_block['ban_time'], $ip_block['attack_count'], $ip_block['ban_remain'], $ip_block['remarks']);
    }
    return $list;
  }

  public static function getBanRemain() {
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
    $req = $db->prepare('SELECT TIMEDIFF(ban_time,NOW()) as ban_remain FROM '.Db::getPrefix().'ip_block WHERE ip=:ip;');
    $req->execute(array('ip' => $ip));
    $res = $req->fetch();
    return $res['ban_remain'];
  }

  public static function addCount() {
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
    $req = $db->prepare('INSERT INTO '.Db::getPrefix().'ip_block (ip) VALUES (:ip) ON DUPLICATE KEY UPDATE attack_count=attack_count+1');
    // the query was prepared, now we replace :id with our actual $id value
    $req->execute(array('ip' => $ip));
    $req2 = $db->prepare('SELECT attack_count FROM '.Db::getPrefix().'ip_block WHERE ip=:ip');
    $req2->execute(array('ip' => $ip));
    $res = $req2->fetch();

    if ($res['attack_count']>= Config::IP_ATTACK_COUNT) {
      $req3 = $db->prepare('UPDATE '.Db::getPrefix().'ip_block SET attack_count=0, ban_time = DATE_ADD(NOW(), interval :ban_time hour) WHERE ip = :ip');
      $req3->execute(array('ip' => $ip, 'ban_time' => Config::IP_BAN_INTERVAL ));
    }
  }

  public static function reset() {
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
      $req = $db->prepare('SELECT remarks FROM '.Db::getPrefix().'ip_block WHERE ip = :ip');
      $req->execute(array('ip' => $ip));
      $res = $req->fetch();
      
      // If an IP ban has remarks, successfully logging in will not erase the record, but merely reset the attack count
      if (strcmp($res['remarks'], '') ==0) {
        $req2 = $db->prepare('DELETE FROM '.Db::getPrefix().'ip_block WHERE ip = :ip');
        if (!$req2->execute(array('ip' => $ip)))
            return "Error writing collection: ".mysqli_stmt_error($req2);
        else
            return "Success";
      }
      else {
        $req2 = $db->prepare('UPDATE '.Db::getPrefix().'ip_block SET attack_count=0 WHERE ip = :ip');
        if (!$req2->execute(array('ip' => $ip)))
            return "Error writing collection: ".mysqli_stmt_error($req2);
        else
            return "Success";
      }
  }

  public static function delete($ip) {
      $db = Db::getInstance();
      $req = $db->prepare('DELETE FROM '.Db::getPrefix().'ip_block WHERE ip = :ip');
      if (!$req->execute(array('ip' => $ip)))
        return "Error writing collection: ".mysqli_stmt_error($req);
      else
        return "Success";
  }

  public static function deleteAllExpiredUnmarked() {
      $db = Db::getInstance();
      $req = $db->query('DELETE FROM '.Db::getPrefix().'ip_block WHERE remarks="" AND attack_count=0 AND ban_time<NOW()');
  }

  public static function insert($ip, $ban_time, $remarks) {
    $db = Db::getInstance();
    $req = $db->prepare('INSERT INTO '.Db::getPrefix().'ip_block (ip, ban_time, remarks, attack_count) VALUES (:ip, :ban_time, :remarks, 0) ON DUPLICATE KEY UPDATE ban_time=:ban_time, remarks=:remarks');
    // the query was prepared, now we replace :id with our actual $id value
    $req->execute(array('ip' => $ip, 'ban_time' => $ban_time, 'remarks' => $remarks));
  }

  public static function update($ip, $ban_time, $remarks) {
    $db = Db::getInstance();
    $req = $db->prepare('UPDATE '.Db::getPrefix().'ip_block SET ban_time=:ban_time, remarks=:remarks WHERE ip = :ip');
    // the query was prepared, now we replace :id with our actual $id value
    $req->execute(array('ip' => $ip, 'ban_time' => $ban_time, 'remarks' => $remarks));
  }

  public static function get($ip) {
    $db = Db::getInstance();
    $req = $db->prepare('SELECT ip, ban_time, attack_count, TIMEDIFF(ban_time,NOW()) as ban_remain, remarks FROM '.Db::getPrefix().'ip_block WHERE ip = :ip');
    $req->execute(array('ip' => $ip));
    $res = $req->fetch();
    return $res;
  }

  public static function getNumberOfBlock($type = "") {
    $db = Db::getInstance();
    $query = 'SELECT count(ip) as c FROM '.Db::getPrefix().'ip_block';

    switch ($type) {
      case 'active':
        $query .= ' WHERE ban_time>NOW()';
      break;
      case 'expired':
        $query .= ' WHERE ban_time>NOW()';
      break;
    }

    $req = $db->query($query);
    $res = $req->fetch();
    return $res['c'];
  }
}
?>