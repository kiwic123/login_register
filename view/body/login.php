<div class="container">
<div class="row">
	  <div class="col-sm-10 col-lg-6 mx-auto pt-3">
	  <h2>Please Login</h2>
	  <div class="card mb-3">
        <div class="card-body">
          <p class="card-text">
		  <form role="form" method="post" action="<?=Config::BASE_URL?>do_login" autocomplete="off">
				
				<p><a href='<?=Config::BASE_URL?>register'>Register a New Account</a></p>
				<hr>

				<?php if ($msg->hasMessages()) $msg->display(); ?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if($msg->hasErrors()){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>

				<div class="form-group">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
				</div>
				
				<div class="row">
					<div class="col-xs-9 col-sm-9 col-md-9">
						 <a href='<?=Config::BASE_URL?>forget'>Forgot your Password?</a>
					</div>
				</div>
				
				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Login" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
					<?php include("view/_component/fb-login.php");?>
				</div>
			</form>
			</p>
        </div>
      </div>
      </div>
      </div>
</div>
