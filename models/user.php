<?php
require_once('config.php');
  class User {
    // they are public so that we can access them using $post->attri directly
    public $uid;
    public $username;
    public $email;
    public $password;
    public $creation_date;
    public $last_login_date;
    public $role;
    public $nickname;
    public $ban_time;
    public $banned;

    public $ap_max;
    public $ap_losstick;
    public $ap_extra;
    public $hp_max;
    public $hp_losstick;
    public $hp_extra;
    public $ap_current;
    public $hp_current;

    public function __construct($uid, $username, $email, $password, $creation_date, $last_login_date, $role, $nickname, $ban_time, $banned,
                                $ap_max, $ap_losstick, $ap_extra, $hp_max, $hp_losstick, $hp_extra) {
      $this->uid             = $uid;
      $this->username        = $username;
      $this->email           = $email;
      $this->password        = $password;
      $this->creation_date   = $creation_date;
      $this->last_login_date = $last_login_date;
      $this->role            = $role;
      $this->nickname        = $nickname;
      $this->ban_time        = $ban_time;
      $this->banned          = $banned;

      $this->ap_max         = $ap_max;
      $this->ap_losstick    = $ap_losstick;
      $this->ap_extra       = $ap_extra;
      $this->hp_max         = $hp_max;
      $this->hp_losstick    = $hp_losstick;
      $this->hp_extra       = $hp_extra;

      if ($this->ap_losstick >0)
        $this->ap_current = $this->ap_max;
      else
        $this->ap_current = floor ($this->ap_max + $ap_losstick/Config::AP_INTERVAL);

      if ($this->hp_losstick >0)
        $this->hp_current = $this->hp_max;
      else
        $this->hp_current = floor ($this->hp_max + $hp_losstick/Config::HP_INTERVAL);
    }

    //Prototype get function that retrieves even security information

    public static function all($limit = 10, $offset = 0) {
      $list = [];
      $db = Db::getInstance();

      $query = 'SELECT uid, username, email, password, creation_date, last_login_date, role, nickname, ban_time,
      ap_max, now()-ap_tick AS ap_losstick, ap_extra, hp_max, now()-hp_tick AS hp_losstick, hp_extra, ban_time>NOW() as banned FROM '.Db::getPrefix().'user';
      $query .= ' ORDER BY uid ASC LIMIT :limit OFFSET :offset';

      $req = $db->prepare($query);
      $req->bindValue(':limit', intval($limit), PDO::PARAM_INT);
      $req->bindValue(':offset', intval($offset), PDO::PARAM_INT);
      $req->execute();

      // we create a list of Post objects from the database results
      foreach($req->fetchAll() as $user) {
        $list[] = new User($user['uid'], $user['username'], $user['email'], $user['password'], $user['creation_date'], $user['last_login_date'], $user['role'], $user['nickname'], $user['ban_time'], $user['banned'],
                           $user['ap_max'], $user['ap_losstick'], $user['ap_extra'], $user['hp_max'], $user['hp_losstick'], $user['hp_extra']);
      }
      return $list;
    }

    public static function find($uid) {
      $db = Db::getInstance();
      // we make sure $id is an integer
      $uid = intval($uid);
      $req = $db->prepare('SELECT uid, username, email, password, creation_date, last_login_date, role, nickname, ban_time,
      ap_max, now()-ap_tick AS ap_losstick, ap_extra, hp_max, now()-hp_tick AS hp_losstick, hp_extra, ban_time>NOW() as banned FROM '.Db::getPrefix().'user WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));
      $user = $req->fetch();

      return new User($user['uid'], $user['username'], $user['email'], $user['password'], $user['creation_date'], $user['last_login_date'], $user['role'], $user['nickname'], $user['ban_time'], $user['banned'],
      $user['ap_max'], $user['ap_losstick'], $user['ap_extra'], $user['hp_max'], $user['hp_losstick'], $user['hp_extra'], $user['banned']);
    }
    public static function findSingleAsList($uid) {
      $list = [];
      $list[] = User::find($uid);
      return $list;
    }

    public static function findUid($cred) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT uid FROM '.Db::getPrefix().'user WHERE username = :username OR email = :email');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('username' => $cred, 'email' => $cred));
      $user = $req->fetch();

      return $user['uid'];
    }

    public static function findByUsername($username) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT uid, username, email, password, creation_date, last_login_date, role, nickname, ban_time,
      ap_max, now()-ap_tick AS ap_losstick, ap_extra, hp_max, now()-hp_tick AS hp_losstick, hp_extra, ban_time>NOW() as banned FROM '.Db::getPrefix().'user WHERE username = :username');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('username' => $username));
      $user = $req->fetch();

      $list = [];
      $list[] = new User($user['uid'], $user['username'], $user['email'], $user['password'], $user['creation_date'], $user['last_login_date'], $user['role'], $user['nickname'], $user['ban_time'], $user['banned'],
                         $user['ap_max'], $user['ap_losstick'], $user['ap_extra'], $user['hp_max'], $user['hp_losstick'], $user['hp_extra']);
      return $list;
    }

    public static function findByEmail($email) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT uid, username, email, password, creation_date, last_login_date, role, nickname, ban_time,
      ap_max, now()-ap_tick AS ap_losstick, ap_extra, hp_max, now()-hp_tick AS hp_losstick, hp_extra, ban_time>NOW() as banned FROM '.Db::getPrefix().'user WHERE email = :email');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('email' => $email));
      $user = $req->fetch();

      $list = [];
      $list[] = new User($user['uid'], $user['username'], $user['email'], $user['password'], $user['creation_date'], $user['last_login_date'], $user['role'], $user['nickname'], $user['ban_time'], $user['banned'],
                         $user['ap_max'], $user['ap_losstick'], $user['ap_extra'], $user['hp_max'], $user['hp_losstick'], $user['hp_extra']);
      return $list;
    }

    public static function usernameExists($username) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT count(uid) AS c FROM '.Db::getPrefix().'user WHERE username = :username');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('username' => $username));

      $user = $req->fetch();
      return $user['c'];
    }

    public static function emailExists($email) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT count(uid) AS c FROM '.Db::getPrefix().'user WHERE email = :email');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('email' => $email));

      $user = $req->fetch();
      return $user['c'];
    }

    public static function getRole($uid) {
      $db = Db::getInstance();
      $req = $db->prepare('SELECT role FROM '.Db::getPrefix().'user WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));

      $user = $req->fetch();
      return $user['role'];
    }

    public static function getNumberOfUser() {
      $db = Db::getInstance();
      $req = $db->query('SELECT (SELECT count(uid) FROM '.Db::getPrefix().'user WHERE role <> "admin") AS player, (SELECT count(uid) FROM '.Db::getPrefix().'user WHERE role = "admin") AS admin');
      $res = $req->fetch();
      return $res;
    }

    public static function verifyCred($cred, $password) {      
      $db = Db::getInstance();
      $req = $db->prepare('SELECT count(password) AS c, password FROM '.Db::getPrefix().'user WHERE username = :username OR email = :email');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('username' => $cred, 'email' => $cred)))
        return "Error writing collection: ".mysqli_stmt_error($req);
      else {
        $user = $req->fetch();
        if ($user['c'] == 0)
          return "Incorrect credential";
        else if (password_verify($password, $user['password']))
            return "Success";
        else
            return "Incorrect credential";
        }
    }

    public static function verifyCredAdmin($cred, $password) {      
      $db = Db::getInstance();
      $req = $db->prepare('SELECT count(password) AS c, password, role FROM '.Db::getPrefix().'user WHERE username = :username OR email = :email');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('username' => $cred, 'email' => $cred)))
        return "Error writing collection: ".mysqli_stmt_error($req);
      else {
        $user = $req->fetch();
        if ($user['c'] == 0)
          return "Incorrect credential";
        else if (strcmp($user['role'], 'admin') !=0)
            return "Not admin";
        else if (password_verify($password, $user['password']))
            return "Success";
        else
            return "Incorrect credential";
        }
    }

    public static function refreshLoginTime($uid) {
      $db = Db::getInstance();
      $req = $db->prepare('UPDATE '.Db::getPrefix().'user SET last_login_date=NOW() WHERE uid=:uid');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid)))
          return "Error writing collection: ".mysqli_stmt_error($req);
      else
          return "Success";
    }

    public static function insert($username, $email, $password, $nickname) {
      $pwhash = password_hash($password, PASSWORD_DEFAULT);
      
      $db = Db::getInstance();
      $req = $db->prepare('INSERT INTO '.Db::getPrefix().'user 
        (username, email, password, nickname) VALUES (:username, :email, :password, :nickname)');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('username' => $username, 'email' => $email, 'password' => $pwhash, 'nickname' => $nickname)))
        return "Error writing collection: ".mysqli_stmt_error($req);
      else 
        return "Success";
    }

    public static function findBanTime($uid) {
      $db = Db::getInstance();
      // we make sure $id is an integer
      $uid = intval($uid);
      $req = $db->prepare('SELECT ban_time FROM '.Db::getPrefix().'user WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));
      $user = $req->fetch();

      return $user['ban_time'];
    }

    public static function manageBan($uid, $ban_time) {
      $db = Db::getInstance();
      $req = $db->prepare('UPDATE '.Db::getPrefix().'user SET ban_time=:ban_time WHERE uid=:uid');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid, 'ban_time' => $ban_time)))
          return "Error writing collection: ".mysqli_stmt_error($req);
      else
          return "Success";
    }

    public static function update($uid, $username, $email, $nickname) {
      $db = Db::getInstance();
      $req = $db->prepare('UPDATE '.Db::getPrefix().'user SET username=:username, email=:email, nickname=:nickname WHERE uid=:uid');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid, 'username' => $username, 'email' => $email, 'nickname' => $nickname)))
          return "Error writing collection: ".mysqli_stmt_error($req);
      else
          return "Success";
    }

    public static function findRole($uid) {
      $db = Db::getInstance();
      // we make sure $id is an integer
      $uid = intval($uid);
      $req = $db->prepare('SELECT role FROM '.Db::getPrefix().'user WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));
      $user = $req->fetch();

      return $user['role'];
    }

    public static function changeRole($uid, $role) {
      $db = Db::getInstance();
      $req = $db->prepare('UPDATE '.Db::getPrefix().'user SET role=:role WHERE uid=:uid');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid, 'role' => $role)))
          return "Error writing collection: ".mysqli_stmt_error($req);
      else
          return "Success";
    }

    public static function findTicks($uid) {
      $db = Db::getInstance();
      // we make sure $id is an integer
      $uid = intval($uid);
      $req = $db->prepare('SELECT ap_tick, hp_tick FROM '.Db::getPrefix().'user WHERE uid = :uid');
      // the query was prepared, now we replace :id with our actual $id value
      $req->execute(array('uid' => $uid));
      $user = $req->fetch();

      return $user;
    }

    public static function resetTicks($uid) {
      $db = Db::getInstance();
      $req = $db->prepare('UPDATE '.Db::getPrefix().'user SET ap_tick=NOW(), hp_tick=NOW() WHERE uid=:uid');
      // the query was prepared, now we replace :id with our actual $id value
      if (!$req->execute(array('uid' => $uid)))
          return "Error writing collection: ".mysqli_stmt_error($req);
      else
          return "Success";
    }
  }
?>