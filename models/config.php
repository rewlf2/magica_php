<?php
class Config {
    const AP_INTERVAL   = 300;         // Seconds for 1 AP to restore
    const HP_INTERVAL   = 1;           // Seconds for 1 HP to restore
    const AP_MAX_INIT   = 60;          // Maximum AP when player is created

    const ACC_EXPIRE    = 365;          // When account has not logged for certain days, it will be deactivated
    const PURGE_INTERVAL    = 5;        // A write-level action must not be performed quicker than this interval in second
    const IP_ATTACK_COUNT   = 5;   // After certain number of consecutive failed logins, the shielding system issues IP ban
    const IP_BAN_INTERVAL   = 2;   // This is time of IP ban in hours
    const SESSION_EXPIRE    = 168;    // After how many hours of inactivity a session will be considered expired
}
?>