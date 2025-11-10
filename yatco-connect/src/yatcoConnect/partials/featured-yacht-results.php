<div class="yatco-shortcode-yacht-results yatco-shortcode-featured-yacht-results">

	<div class="">
		<div class="card-container">
			<div class="yt-row">
				<?php foreach ($results->Results as $vessel) : ?>
					<?php 
						$vessel_medium_main_img=str_replace('small', 'medium', htmlentities($vessel->MainPhotoUrl));

						//var_dump($vessel);
					?>

					<?php if ($total_count == 1) : ?>

						<?php include 'yacht-result-parts/list-result.php'; ?>

					<?php elseif ($total_count == 2) : ?>

						<?php include 'yacht-result-parts/grid-result-halfs.php'; ?>

					<?php else : ?>	

						<?php include 'yacht-result-parts/grid-result.php'; ?>
					
		            <?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<?php 
		include __DIR__.'/yacht-result-parts/modal-form-for-brokerage-site.php';
		
		//include apply_filters('yatco_connect_shortcode_yacht_results_lead_modal_template', $file_to_include);
	?>
</div>