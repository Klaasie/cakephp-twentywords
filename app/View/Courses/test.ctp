<div class="row page-content">
	<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
		<ul class="test">

			<li class="sentence-lang-<?php echo $curLang; ?>">
				<span class="lang-flag"></span>
				<div class="currentLanguage">

				</div>
			</li>

			<li class="sentence-learn-<?php echo $learnLang; ?>">
				<span class="lang-flag"></span>
				<div class="toTranslate">

				</div>
				<span class="alert alert-danger wrongAnswer"><?php echo __('Helaas! Verkeerde antwoord.'); ?></span>
			</li>

			<li>
				<button class="checkAnswer"><?php echo __('Controleer'); ?></button>
			</li>

		</ul>
	</div>
</div>