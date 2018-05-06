<div class="site-wrapper">

<div class="site-wrapper-inner">

  <div class="cover-container">

    <div class="masthead clearfix">
      <div class="inner hideable-header">
        <h3 class="masthead-brand">Magica Admin</h3>
        <nav>
          <ul class="nav masthead-nav">
          <?php
          switch ($current_page) {
            case 'account':
            echo '
              <li class="active"><a href="#">Account</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?action=setting">TBA</a></li>
            ';
            break;
            case 'register':
            echo '
              <li><a href="?">Home</a></li>
              <li class="active"><a href="#">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?action=setting">TBA</a></li>
            ';
            break;
            case 'ranking':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li class="active"><a href="#">TBA</a></li>
              <li><a href="?action=setting">TBA</a></li>
            ';
            break;
            case 'setting':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li class="active"><a href="#">TBA</a></li>
            ';
            break;
            case 'contact':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?action=setting">TBA</a></li>
            ';
            break;
            default:
            echo '
              <li><a href="?controller=admin">Account</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">TBA</a></li>
            ';
            break;
          }
          ?>
          <li><a href="?controller=game_menu">Back</a></li>
          </ul>
        </nav>
      </div>
    </div>