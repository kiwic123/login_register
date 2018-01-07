<div class="container">
	<div class="row">
	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form role="form" method="post" action="<?=Config::BASE_URL?>do_reset" autocomplete="off">
					<h2>Change Password</h2>
					<hr>
					<?php if ($msg->hasMessages()) $msg->display(); ?>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="1">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="2">
							</div>
						</div>
					</div>
					
					<hr>
					<div class="row">
						<div class="col-xs-6 col-md-6">
						<input type="hidden" name="resetToken" value="<?=$resetToken?>">
						<input type="submit" name="submit" value="Change Password" class="btn btn-primary btn-block btn-lg" tabindex="3">
						</div>
					</div>
				</form>
		</div>
	</div>


</div>