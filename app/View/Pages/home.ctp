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
 * @package       Cake.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

<div class="row">
	<div id="intro" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<h1><?php echo __('Ontdek, herhaal en leer twintig woorden per dag'); ?></h1>
	</div>
</div><!-- #intro -->
<div class="row page-content">
	<div id="info" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="panel">
			<div class="left">
				<div class="title"><?php echo __('Ontdek zeven nieuwe woorden'); ?></div>
				<div class="body"><?php echo __('Door telkens zeven nieuwe woorden te ontdekken, is het leren van een nieuwe taal opeens wel heel makkelijk!'); ?></div>
			</div>
			<div class="right">
				<?php echo $this->Html->image("ontdek.png", array("alt" => "ontdek icon")); ?>
			</div>
		</div>
		<div class="panel">
			<div class="left">
				<div class="title"><?php echo __('Herhaal zeven woorden en nog eens zes'); ?></div>
				<div class="body"><?php echo __('Herhaal je geleerde woorden en zorg er zo voor dat je elk woord opslaat in je langetermijngeheugen!'); ?></div>
			</div>
			<div class="right">
				<?php echo $this->Html->image("herhaal.png", array("alt" => "herhaal icon")); ?>
			</div>
		</div>
		<div class="panel">
			<div class="left">
				<div class="title"><?php echo __('Leer twintig woorden'); ?></div>
				<div class="body"><?php echo __('Leer met Twenty Words op deze manier gemakkelijk een nieuwe taal om je beter te kunnen redden op vakantie!'); ?></div>
			</div>
			<div class="right">
				<?php echo $this->Html->image("leer.png", array("alt" => "leer icon")); ?>
			</div>
		</div>
	</div> <!-- #info -->
	<div id="register" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<?php if(AuthComponent::user('id')): ?>
			<div class="currentCourse">
				<div class="title">
					<h2><?php echo __('Huidige cursus'); ?>
				</div>
			</div>
		<?php else: ?>
			<form class="form-horizontal" role="form" method="post" action="<?php echo $this->Html->url('/users/add/'); ?>">
				<div class="title">
					<h2><?php echo __('Registreer'); ?></h2>
				</div>
				<div class="form-group">
					<label for="input[User][username]" class="col-sm-4 control-label"><?php echo __('Gebruikersnaam'); ?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="data[User][username]" placeholder="<?php echo __('Gebruikersnaam'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="data[User][email]" class="col-sm-4 control-label"><?php echo __('E-mail'); ?></label>
					<div class="col-sm-8">
						<input type="email" class="form-control" name="data[User][email]" placeholder="<?php echo __('E-mail adres'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="data[User][password]" class="col-sm-4 control-label"><?php echo __('Wachtwoord'); ?></label>
					<div class="col-sm-8">
						<input type="password" class="form-control" name="data[User][password]" placeholder="<?php echo __('Wachtwoord'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="data[User][language]" class="col-sm-4 control-label"><?php echo __('Taal'); ?></label>
					<div class="col-sm-8">
						<select class="form-control" name="data[User][language]">
							<option><?php echo __('Selecteer een taal'); ?></option>
							<?php foreach($languages as $language): ?>
								<option value="<?php echo $language['Language']['shortcode']; ?>"><?php echo $language['Language']['name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="data[User][learn]" class="col-sm-4 control-label"><?php echo __('Ik wil leren:'); ?></label>
					<div class="col-sm-8">
						<select class="form-control" name="data[User][learn]">
							<option><?php echo __('Selecteer een taal'); ?></option>
							<?php foreach($languages as $language): ?>
								<option value="<?php echo $language['Language']['shortcode']; ?>"><?php echo $language['Language']['name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-default pull-right btn-green btn-register"><?php echo('Registreer'); ?></button>
					</div>
				</div>
			</form>
		<?php endif; ?>
	</div>  <!-- #register -->
</div><!-- .page-content -->