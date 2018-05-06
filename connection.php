<?php
  class Db {
    private static $instance = NULL;

    // Change the prefix if you wish to have a different table name prefix.
    // It is a measure of security through obscurity.
    private static $prefix = 'magica_';

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
      if (!isset(self::$instance)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$instance = new PDO('mysql:host=localhost;dbname=magica', 'root', '123456', $pdo_options);
      }
      return self::$instance;
    }
    public static function getPrefix() {
      return self::$prefix;
    }
  }
?>