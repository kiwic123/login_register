<div class="container">
	<div class="row">
	  	<div class="col-sm-10 col-lg-6 mx-auto pt-3">
	  	<h2>Reset Password</h2>
	  		<div class="card mb-3">
			  <div class="card-body">
          		<p class="card-text">
					<form role="form" method="post" action="<?=Config::BASE_URL?>do_forget" autocomplete="off">
						<p><a href='login'>Back to login page</a></p>
						<hr>
						<?php if ($msg->hasMessages()) $msg->display(); ?>
						<div class="form-group">
							<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email" value="" tabindex="1">
						</div>
						<hr>
						<div class="row">
							<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Sent Reset Link" class="btn btn-primary btn-block btn-lg" tabindex="2"></div>
						</div>
					</form>
				</p>
			</div>
		</div>
	</div>
</div>