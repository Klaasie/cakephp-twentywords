<div class="row page-content">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="block profile">
				<?php if(!$foreign): ?>
					<span class="glyphicon glyphicon-cog pull-right editUser"></span>
				<?php endif; ?>
				<h2 class="title"><?php echo __('Profiel'); ?></h2>
				<div class="content userInfo">
					<div id="username">
						<label class="profileLabel"><?php echo __('Username'); ?></label>
						<span><?php echo $user['User']['username']; ?></span>
					</div>
					<div id="email">
						<label class="profileLabel"><?php echo __('E-mail adres'); ?></label>
						<span><?php echo $user['User']['email']; ?></span>
					</div>
					<div id="language">
						<label class="profileLabel"><?php echo __('Taal'); ?></label>
						<span><?php echo $user['User']['language']; ?></span>
					</div>
					<div id="learning">
						<label class="profileLabel"><?php echo __('Lerend'); ?></label>
						<span><?php echo $user['User']['learn']; ?></span>
					</div>
				</div><!-- .user_info -->
				<?php if(!$foreign): ?>
					<div class="content editUserForm hidden">
						<form class="form-horizontal" method="POST" action="<?php echo $this->Html->url('/users/editUser/'); ?>" role="form">
							<div class="form-group">
								<label for="username" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('Gebruikersnaam'); ?></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="username" name="data[User][username]" placeholder="<?php echo $user['User']['username']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('E-mail adres'); ?></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="email" name="data[User][email]" placeholder="<?php echo $user['User']['email']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="taal" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('Taal'); ?></label>
								<div class="col-sm-7">
									<select class="form-control" name="data[User][language]">
										<?php foreach($availableLanguages as $language): ?>
											<?php 
												$selected = "";
												if($language['Language']['shortcode'] == $user['User']['language']) $selected = "selected"; ?>
											<option value="<?php echo $language['Language']['shortcode']; ?>" <?php echo $selected; ?>>
												<?php echo $language['Language']['name']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="taal" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('Ik wil leren'); ?></label>
								<div class="col-sm-7">
									<select class="form-control" name="data[User][learn]">
										<?php foreach($availableLanguages as $language): ?>
											<?php 
												$selected = "";
												if($language['Language']['shortcode'] == $user['User']['learn']) $selected = "selected"; ?>
											<option value="<?php echo $language['Language']['shortcode']; ?>" <?php echo $selected; ?>>
												<?php echo $language['Language']['name']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-7 col-sm-5">
									<button type="submit" class="btn btn-default btn-green"><?php echo __('Profiel aanpassen'); ?></button>
								</div>
							</div>
						</form>
							<hr />
						<form class="form-horizontal" method="POST" action="<?php echo $this->Html->url('/users/editPassword/'); ?>" role="form">
							<div class="form-group">
								<label for="password" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('Oud wachtwoord'); ?></label>
								<div class="col-sm-7">
									<input type="password" class="form-control" id="password" name="data[User][currentpassword]" placeholder="<?php echo __('Oud wachtwoord'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-lg-5 col-md-5 col-sm-5 control-label"><?php echo __('Nieuw wachtwoord'); ?></label>
								<div class="col-sm-7">
									<input type="password" class="form-control" id="password" name="data[User][newpassword]" placeholder="<?php echo __('Nieuw wachtwoord'); ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-6 col-sm-6">
									<button type="submit" class="btn btn-default btn-green"><?php echo __('Wachtwoord aanpassen'); ?></button>
								</div>
							</div>
						</form>
					</div>
				<?php endif; ?>
			</div><!-- .profile -->
		</div><!-- .col-lg-6 -->

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="block rankings">
				<h2 class="title"><?php echo __('Ranglijst'); ?></h2>
				<div class="content">
					<div class="item">
						<?php echo __('Nog niets :('); ?><br />
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					</div>
				</div>
			</div><!-- .rankings -->
		</div><!-- .col-lg-6 -->

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-right">
			<div class="block achievements">
				<h2 class="title"><?php echo __('Verworven'); ?></h2>
				<div class="content">
					<div class="item">
						<?php echo __('Nog niets :('); // Eventually dynamic ?>
					</div>
				</div>
			</div> <!-- .achievements -->
		</div><!-- .col-lg-6 -->
</div>