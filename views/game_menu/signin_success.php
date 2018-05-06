<?php
switch ($result) {
    case 'Create':
        echo 'Welcome.';
    break;
    case 'Refresh':
        echo 'Welcome back.';
    break;
    case 'Carry':
        echo 'Welcome back. You login record has been carried from another IP.';
    break;
    default:
        echo 'Unexpected error. Please contact adminstrator with page name.';
    break;
}
?>