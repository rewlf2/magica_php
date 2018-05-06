<?php
switch ($controller) {
    case 'admin': {
        switch ($action) {
            case 'ip_block':
            case 'user':
            case 'user_log':
                echo '';
            break;
        default:
            echo '
            <script src="views/assets/js/vendor/jquery.min.js"></script>
            ';
        break;
        }
    }
    break;
    default:
        echo '
        <script src="views/assets/js/vendor/jquery.min.js"></script>
        ';
    break;
}
?>