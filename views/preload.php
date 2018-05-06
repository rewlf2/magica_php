<?php
switch ($controller) {
    case 'admin': {
        switch ($action) {
            case 'ip_block':
            case 'user':
            case 'user_log':
                echo '
                <script src="views/assets/js/vendor/jquery.min.js"></script>
                <script type="text/javascript" src="views/dist/js/bootstrap-datepicker.min.js"></script>
                ';
            break;
        default:
            echo '';
        break;
        }
    }
    break;
    default:
        echo '';
    break;
}
?>