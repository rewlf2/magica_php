<?php
class GameConfig {
    private $purge_interval;        // A write-level action must not be performed quicker than this interval in second
    private $ap_interval;         // Seconds for 1 AP to restore
    private $hp_interval;           // Seconds for 1 HP to restore
    private $ap_max_init;          // Maximum AP when player is created
    private $acc_expire;          // When account has not logged for certain days, it will be deactivated

    private $ip_attack_count;   // After certain number of consecutive failed logins, the shielding system issues IP ban
    private $ip_ban_interval;   // This is time of IP ban in hours
    private $session_expire;    // After how many hours of inactivity a session will be considered expired

    public function __construct() {
        $this->purge_interval = 5;
        $this->ap_interval    = 300;
        $this->hp_interval    = 1;
        $this->ap_max_init    = 60;
        $this->acc_expire     = 365;

        $this->ip_attack_count    = 5;
        $this->ip_ban_interval    = 2;
        $this->session_expire     = 168;
    }
    public function gcPurgeInterval() {
        return $this->purge_interval;
    }
    public function gcApInterval() {
        return $this->ap_interval;
    }
    public function gcHpInterval() {
        return $this->hp_interval;
    }
    public function gcApMaxInit() {
        return $this->ap_max_init;
    }
    public function gcAccExpire() {
        return $this->acc_expire;
    }
    public function gcIpAttackCount() {
        return $this->ip_attack_count;
    }
    public function gcIpBanInterval() {
        return $this->ip_ban_interval;
    }
    public function gcSessionExpire() {
        return $this->session_expire;
    }
}
?>