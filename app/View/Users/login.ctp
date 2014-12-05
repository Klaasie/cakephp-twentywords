<div class="row">
	<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-4 col-xs-offset-4 page-content">
		<form class="form-signin login-block" role="form" action="<?php echo $this->Html->url('/users/login/'); ?>" method="POST">
		<h2 class="form-signin-heading"><?php echo __('Inloggen'); ?></h2>
			<input type="text" class="form-control" name="data[User][username]" placeholder="<?php echo __('Gebruikersnaam'); ?>" required="" autofocus="">
			<input type="password" class="form-control" name="data[User][password]" placeholder="<?php echo __('Wachtwoord'); ?>" required="">
			<a href="<?php echo $this->Html->url('/resetpassword') ?>" class="forgotPassword pull-right"><?php echo __('Wachtwoord vergeten?'); ?></a>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="data[User][rememberme]"> <?php echo __('Herinner mij'); ?>
				</label>
			</div>
			<button class="btn btn-lg btn-primary btn-block btn-login btn-green" type="submit"><?php echo __('Inloggen'); ?></button>
		</form>
	</div>
</div>