<div class="row">
	<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-4 col-xs-offset-4 page-content">
		<form class="form-signin login-block" role="form" action="<?php echo $this->Html->url('/users/resetPassword/'); ?>" method="POST">
		<h2 class="form-signin-heading"><?php echo __('Wachtwoord resetten'); ?></h2>
			<input type="email" class="form-control" name="data[User][email]" placeholder="<?php echo __('Email-adres'); ?>" required="" autofocus="">
			<button class="btn btn-lg btn-primary btn-block btn-login btn-green" type="submit"><?php echo __('Wachtwoord resetten'); ?></button>
		</form>
	</div>
</div>