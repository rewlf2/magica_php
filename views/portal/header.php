<div class="site-wrapper">

<div class="site-wrapper-inner">

  <div class="cover-container">

    <div class="masthead clearfix">
      <div class="inner">
        <h3 class="masthead-brand">Magica Portal</h3>
        <nav>
          <ul class="nav masthead-nav">
          <?php
          switch ($current_page) {
            case 'home':
            echo '
              <li class="active"><a href="#">Home</a></li>
              <li><a href="?action=register">Register</a></li>
              <li><a href="?action=ranking">Ranking</a></li>
              <li><a href="?action=setting">Setting</a></li>
              <li><a href="?action=contact">Contact</a></li>
            ';
            break;
            case 'register':
            echo '
              <li><a href="?">Home</a></li>
              <li class="active"><a href="#">Register</a></li>
              <li><a href="?action=ranking">Ranking</a></li>
              <li><a href="?action=setting">Setting</a></li>
              <li><a href="?action=contact">Contact</a></li>
            ';
            break;
            case 'ranking':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">Register</a></li>
              <li class="active"><a href="#">Ranking</a></li>
              <li><a href="?action=setting">Setting</a></li>
              <li><a href="?action=contact">Contact</a></li>
            ';
            break;
            case 'setting':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">Register</a></li>
              <li><a href="?action=ranking">Ranking</a></li>
              <li class="active"><a href="#">Setting</a></li>
              <li><a href="?action=contact">Contact</a></li>
            ';
            break;
            case 'contact':
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">Register</a></li>
              <li><a href="?action=ranking">Ranking</a></li>
              <li><a href="?action=setting">Setting</a></li>
              <li class="active"><a href="#">Contact</a></li>
            ';
            break;
            default:
            echo '
              <li><a href="?">Home</a></li>
              <li><a href="?action=register">Register</a></li>
              <li><a href="?action=ranking">Ranking</a></li>
              <li><a href="?action=setting">Setting</a></li>
              <li><a href="?action=contact">Contact</a></li>
            ';
            break;
          }
          ?>
          </ul>
        </nav>
      </div>
    </div>