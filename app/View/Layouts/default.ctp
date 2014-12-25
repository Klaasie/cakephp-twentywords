<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'Twenty Words');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bring the favicon magic -->
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->webroot; ?>img/favicon/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon/favicon-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon/favicon-160x160.png" sizes="160x160">
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon/favicon-16x16.png" sizes="16x16">
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon/favicon-32x32.png" sizes="32x32">
	<meta name="msapplication-TileColor" content="#062a4c">
	<meta name="msapplication-TileImage" content="<?php echo $this->webroot; ?>img/favicon/mstile-144x144.png">

	<?php
		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('custom');

		echo $this->Html->script('jquery');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('TweenMax.min');
		echo $this->html->script('base64/jquery.base64.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<link href='http://fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
</head>
<body>
	<div id="header">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
				<?php echo $this->Html->link($this->Html->image("logo.png", array("alt" => "Twenty Words logo")),'/', array('class' => 'logo', 'escape' => false)); ?>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
				<div class="pull-right">
					<?php if(!AuthComponent::user('id')): // If not logged in. ?>
						<a href="#" class="showLogin"><?php echo __('Inloggen'); ?></a>
						<form id="login" class="form-inline login" role="form" method="POST" action="<?php echo $this->Html->url('/users/login/'); ?>">
							<div class="row">
								<div class="form-group">
									<label class="sr-only" for="data[User][username]"><?php echo __('Gebruikersnaam'); ?></label>
									<input type="text" class="form-control" name="data[User][username]" placeholder="<?php echo __('Gebruikersnaam'); ?>">
								</div>
								<div class="form-group">
									<label class="sr-only" for="data[User][password]"><?php echo __('Wachtwoord'); ?></label>
									<input type="password" class="form-control" name="data[User][password]" placeholder="<?php echo __('Wachtwoord'); ?>">
								</div>
								<button type="submit" class="btn btn-default btn-green btn-login"><?php echo __('Log in'); ?></button>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="passwordReminder">
										<a href="<?php echo $this->Html->url('/resetpassword'); ?>"> <?php echo __('Wachtwoord vergeten?'); ?></a>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="checkbox pull-right">
										<label>
											<input type="checkbox" name="data[User][rememberme]"> <?php echo __('Herinner mij'); ?>
										</label>
									</div>
								</div>
							</div>
					</form>
					<?php else: // If logged in. ?>
						<nav class="navbar navbar-default" role="navigation">

							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo ucfirst($this->Session->read('User.username')); ?><span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="<?php echo $this->Html->url('/dashboard/'); ?>"><?php echo __('Dashboard'); ?></a></li>
										<li><a href="<?php echo $this->Html->url('/profile/'); ?>"><?php echo __('Profiel') ?></a></li>
										<li class="divider"></li>
										<li><a href="<?php echo $this->Html->url('/users/logout/'); ?>"><?php echo __('Uitloggen') ?></a></li>
									</ul>
								</li>
							</ul>

						</nav>
					<?php endif;?>
				</div> <!-- .pull-right -->
			</div> <!-- .col-lg-9 -->
	</div> <!-- #Header -->
	<div id="container" class="container">

		<div id="content" class="row">
			<div class="row">
				<?php echo $this->Session->flash('auth'); ?>
				<?php echo $this->Session->flash(); ?>
			</div>

			<?php echo $this->fetch('content'); ?>
		</div> <!-- #content -->
		<div id="footer" class="row">
			<span><?php echo __('Twenty Words | 2014');?></span>
		</div> <!-- #footer -->

	</div> <!-- #container -->
	<?php
		// Dynamic way of loading javascript per controller action.
		if(isset($jsIncludes)){
			foreach($jsIncludes as $js){
				echo $this->Html->script($js);
			}
		}
	?>
	<?php echo $this->Html->script('custom'); ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
