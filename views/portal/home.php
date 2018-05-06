    <div class="inner cover">Browser adventure game</h1>
      <p class="lead">Magica is a retro web game that runs on PHP,<br/>easily deployed and leisurely played.</p>
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">
			  <form id="signin" class="form-signin">
				<label for="inputCred" class="sr-only" name="email">Username or Email address</label>
				<input id="inputCred" class="form-control" placeholder="User name or email" required autofocus name="Cred">
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" class="form-control" placeholder="Password" required maxlength="20">
          		<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
				<div class="checkbox">
        </div>
        <div class="btn-group-justified">
          <div class="btn-group">
            <button class="btn btn-lg btn-default" id="sbutton" type="button" onclick="ajaxPost()">Sign in</button>
          </div>
          <div class="btn-group">
            <a class="btn btn-lg btn-default" id="regbutton" type="button" href="?action=register">Register</a>
				  </div>
			  </div>
			  </form>
          </div>
          <h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
          <h5><div class="error" name="error2" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>