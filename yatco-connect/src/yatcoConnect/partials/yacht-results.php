<!-- UPDATE PLUGIN USE HQ -->
<?php
	$view_type = (isset($_params['viewtype']))?$_params['viewtype']:'gallery';
?>

<div class="yatco-shortcode-yacht-results <?= join(' ', $root_classes) ?>" data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">
	<?php // var_dump($_params); ?>

	<!-- <div class="yt-container"> -->
		<?php if ($attributes['sort'] === 1) : ?>
			<div class="yt-row top">
				<div class="yt-col-md-4 yt-col-12 yt-text-right yt-pull-right">
					<span class="sortby">
						<span class="label">Sort By: </span>

						<span class="">
							<select class="yt-input fill-sortby" name="sortId" form="desktop-expanded-search" data-yatco-fill-options="Sorts" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'sortId', $_params, '0') ?>">
								<option>Loading...</option>
							</select>
						</span>
					</span>
				</div>
				
				<div class="yt-col-md-8 yt-col-12">
					<div class="list-or-grid">
		                <label>
		                    <input type="radio" name="viewtype" value="gallery" style="display: none;" <?php checked('gallery', $view_type); ?> form="desktop-expanded-search">
		                    <span>
		                        <!-- <span class="glyphicon glyphicon-th-large"></span>  -->
		                        GALLERY
		                    </span>
		                </label> 
		                
						<label>                        
		                    <input type="radio" name="viewtype" value="list" style="display: none;" <?php checked('list', $view_type); ?> form="desktop-expanded-search">
		                    <span>
		                        <!-- <span class="glyphicon glyphicon-th-list"></span>  -->
		                        LIST
		                    </span>
		                </label>     

					</div>
				</div>
				
			</div>
			
			<!-- 
			<div class="yt-hidden-lg yt-hidden-md">
				<div class="yt-row">
					<div class="yt-col-12">
						<span class="sortby">
							<span class="label">Sort By: </span>

							<span class="">
								<select class="yt-input fill-sortby" name="sortId" form="desktop-expanded-search" onchange="SubmitSortOnChange()" data-value="<?= apply_filters('yatco_data_value', 'sortId', $_params) ?>">
									<option>Loading...</option>
								</select>
							</span>
						</span>
					</div>
				</div>
			</div> -->

			<div class="yt-clearfix"></div>

			<?php if ($attributes['total_result_count'] == "true" || $attributes['total_result_count'] === '1') : ?>
				
				<div class="yt-text-right" style="font-size: 13px;">
					
					<?= $total_count ?> Results 

				</div>

			<?php endif; ?>

		<?php elseif ($attributes['sort'] === 'only') : ?>
			<div class="yt-row top" style="border-bottom: 0px;">
				<div class="yt-col-md-4 yt-col-12 yt-text-right yt-pull-right">
					<span class="sortby">
						<span class="label">Sort By: </span>

						<span class="">
							<select class="yt-input fill-sortby" name="sortId" form="desktop-expanded-search" data-yatco-fill-options="Sorts" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'sortId', $_params, '0') ?>">
								<option>Loading...</option>
							</select>
						</span>
					</span>
				</div>

				<div class="yt-col-md-8 yt-col-12">
					<br>
				</div>				
			</div>

		<?php endif; ?>
	<!-- </div> -->

	<div class="">
		<div class="card-container">
			<div class="yt-row" id="yacht-loop">
				<?php 
					if (count($results->Results) == 0) : 
						include 'vessel-results-not-found.php';
					endif; 
				
					foreach ($results->Results as $vessel) :
						$vessel_medium_main_img=str_replace('small', 'medium', htmlentities($vessel->MainPhotoUrl));
						
						if (isset($_params['viewtype']) && $_params['viewtype'] == 'list') : 
							include 'yacht-result-parts/list-result.php';
						else :
							include 'yacht-result-parts/grid-result.php'; 
						endif; 
					endforeach;
				?>
			</div>
		
		</div>

		<?php if ($attributes['pagination'] === 1) : ?>
			<?php if ($page_count > 1) : ?>
				<div class="yatco-shortcode-yacht-results-pagination">
					<div class="inner">
						<?php 
						
							echo paginate_links(array(
					            'base' => get_the_permalink().'%_%',
					            'format' => '?page_index=%#%',
					            'current' => $page_index,
					            'total' => $page_count,
					            'mid_size' => 3,
					            'end_size' => 2,
					            'prev_text' => "<",
					            'next_text' => ">"
					        ));

						?>					
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<!-- @ToDo Add Powered By Here Via Attr -->

	<?php
		include __DIR__.'/yacht-result-parts/modal-form-for-brokerage-site.php';
		
		/// include apply_filters('yatco_connect_shortcode_yacht_results_lead_modal_template', $file_to_include);
	?>

	<?php 
		$vessel_ids = join(',', array_map(function($vessel) {return $vessel->VesselID;}, $results->Results));
	?>

	<script type="text/javascript">
		window.addEventListener('load', function() {
			if (typeof _YCT != 'undefined') {
				_YCT.forsale.impressions("<?= $vessel_ids ?>");
			}
		});
	</script>
</div>