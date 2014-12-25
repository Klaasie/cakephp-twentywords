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
				<div class="btn-toolbar entities" role="toolbar">
					<div class="btn-group" role="group" aria-label="First group">
						<?php foreach($entities as $entity): ?>
							<button type="button" class="btn btn-default" data-value="<?php echo $entity['Entities']['entity']; ?>"><?php echo $entity['Entities']['entity']; ?></button>
						<?php endforeach; ?>
					</div>
				</div>
				<!--<span class="alert alert-danger wrongAnswer"><?php //echo __('Helaas! Verkeerde antwoord.'); ?></span>-->
			</li>

			<li>
				<button class="checkAnswer"><?php echo __('Controleer'); ?></button>
				<button class="moveOn"><?php echo __('Volgende vraag'); ?></button>
			</li>

		</ul>

		<div class="test_finished" style="display: none;">
			<p><?php echo __('Je bent klaar voor vandaag.'); ?></p>
			<p><?php echo __('Kom morgen terug!'); ?></p>
		</div>
	</div>
</div>