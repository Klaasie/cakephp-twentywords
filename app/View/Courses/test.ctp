<div class="row page-content">
	<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
		<ul class="test">

			<li class="sentence-lang-<?php echo $curLang; ?>">
				<span class="lang-flag"></span>
				<div class="currentLanguage">
<!--
					<span class="part1"></span>
					<span class="asking">Goedendag</span>
					<span class="part2">, jongen en meisje. Hoe heten jullie?</span>
-->
				</div>
			</li>

			<span class="answer-wrong"></span>

			<li class="sentence-learn-<?php echo $learnLang; ?>">
				<span class="lang-flag"></span>
				<div class="toTranslate">
<!--			
					<span class="part1"></span>
					<input class="answer" autocomplete="off" type="text">
					<span class="part2">, niño y niña. ¿Cómo os llamáis?</span>
-->
				</div>
			</li>

			<li>
				<button class="checkAnswer"><?php echo __('Controleer'); ?></button>
			</li>

		</ul>
	</div>
</div>