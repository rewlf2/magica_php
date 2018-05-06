<div class="site-wrapper">

<div class="site-wrapper-inner">

  <div class="cover-container">

    <div class="masthead clearfix">
      <div class="inner">
        <h3 class="masthead-brand">Magica</h3>
        <nav>
          <ul class="nav masthead-nav">
          <?php
          switch ($current_page) {
            case 'home':
            echo '
              <li class="active"><a href="#">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">Setting</a></li>
            ';
            break;
            case 'register':
            echo '
              <li><a href="?controller=game_menu">Home</a></li>
              <li class="active"><a href="#">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">Setting</a></li>
            ';
            break;
            case 'ranking':
            echo '
              <li><a href="?controller=game_menu">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li class="active"><a href="#">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">Setting</a></li>
            ';
            break;
            case 'setting':
            echo '
              <li><a href="?controller=game_menu">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li class="active"><a href="#">Setting</a></li>
            ';
            break;
            case 'contact':
            echo '
              <li><a href="?controller=game_menu">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">Setting</a></li>
            ';
            break;
            default:
            echo '
              <li><a href="?controller=game_menu">Home</a></li>
              <li><a href="?action=register">TBA</a></li>
              <li><a href="?action=ranking">TBA</a></li>
              <li><a href="?controller=game_menu&action=setting">Setting</a></li>
            ';
            break;
          }
          if (strcmp($auth_level, 'admin') == 0) {
            echo '<li><a href="?controller=admin">Admin</a></li>';
          }
          ?>
          <li><a href="?controller=game_menu&action=signout">Sign out</a></li>
          </ul>
        </nav>
      </div>
    </div>