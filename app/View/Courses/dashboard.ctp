<?php
	$img = $this->Html->image("subcategory_placeholder_unavailable.png", array("class" => "subcat_img subcategory_unavailable"));
	$color = "#7fc1da";
	$finishedToday = false;
?>
<div class="row">
<?php //pr($categories); ?>
<?php //pr($status); ?>

	<?php if($status != NULL): ?>
		<?php if($status['Status']['status'] == "progress"): ?>
			<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2 alert alert-warning" role="alert"><?php echo __('Je heb de test nog niet afgerond. <a href="/test">Klik hier</a> om naar de test te gaan.'); ?></div>
		<?php endif; ?>
		<?php if($status['Status']['status'] == "completed"): ?>
			<?php $finishedToday = true; ?>
			<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2 alert alert-success" role="alert"><?php echo __('Je bent klaar voor vandaag!'); ?></div>
		<?php endif; ?>
	<?php endif; ?>

	<?php foreach($categories as $category): ?>
		<?php
		foreach($statistics as $statistic):
			if($statistic['Statistic']['category_id'] == $category[$categoryName]['id']):
				if($statistic['Statistic']['status'] == "success"):
					$class =  "success";
				elseif($statistic['Statistic']['status'] == "process"):
					$class = "process";
				endif;
			else:
				$class = "locked";
			endif;
		endforeach;
		?>

		<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2 category <?php echo $class; ?>">
			<h1><?php echo $category[$categoryName]['name']; ?></h1>
		</div><!-- .category -->


		<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2 subcategory">
			<?php foreach($category['Subcategories'] as $subcategory): ?>
				<?php
				$process = false;
				foreach($statistics as $statistic):
					if($statistic['Statistic']['subcategory_id'] == $subcategory['id']):
						if($statistic['Statistic']['status'] == "success"):
							$img = $this->Html->image("subcategory_placeholder_finished.png", array("class" => "subcat_img subcategory_finished"));
							$color =  "#78bc6e";
						elseif($statistic['Statistic']['status'] == "process"):
							$img = $this->Html->image("subcategory_placeholder_active.png", array("class" => "subcat_img subcategory_process"));
							$color = "#062a4c";
							$process = true;
						endif;
					else:
						$img = $this->Html->image("subcategory_placeholder_unavailable.png", array("class" => "subcat_img subcategory_unavailable"));
						$color = "#7fc1da";
					endif;
				endforeach;
				?>
				<?php if($process && !$finishedToday): ?>
					<a href="<?php echo $this->Html->url('/test/'); ?>">
				<?php endif; ?>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 block">
						<?php echo $img; ?>
						<div class="title" style="color: <?php echo $color; ?>;">
							<?php echo $subcategory['name']; ?>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>