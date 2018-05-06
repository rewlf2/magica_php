
		  <form id="join" class="form-signin">
			<label for="inputUser" class="sr-only">Username</label>
			<input type="text" id="inputUser" class="form-control" placeholder="User name" name="username" required pattern="^[a-zA-Z][a-zA-Z0-9]{5,19}$" title="6-20 characters starting with alphabet">
			<label for="inputEmail" class="sr-only">Email address</label>
			<input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" autofocus autocomplete="on" required type="email">
			

			<div class="input-group">
				<label for="inputPassword" class="sr-only">Password</label>
					<input type="password" id="inputPassword" class="form-control" placeholder="Password" required pattern="^[a-zA-Z0-9]{6,20}$" title="6-20 characters">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" onClick="togglePassword()">
							<span id="tbutton" class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
						</button>
					</span>
				</div><!-- /input-group -->
			
			<label for="inputNickname" class="sr-only">Nickname</label>
			<input type="text" id="inputNickname" class="form-control" placeholder="Nickname" required pattern="^[a-zA-Z0-9]{3,20}$" title="3-20 characters starting with alphabet">
			
			<div class="btn-group btn-block" role="group">
				<button class="btn btn-primary" style="width:50%" id="sbutton" type="button" onclick="ajaxPost()">Sign Up</button>
				<button type="reset" class="btn btn-default" style="width:50%">Reset</button>
			</div>
					
			<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
          </form>
          