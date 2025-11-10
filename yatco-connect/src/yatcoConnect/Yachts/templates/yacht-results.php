<!-- UPDATE PLUGIN USE HQ -->
<?php

	$view_type = (isset($_params['viewtype']))?$_params['viewtype']:'gallery';
    $template_manager = new yatcoConnect_TemplateManager();
	$yt_list_template = $yt_grid_template = '';
	if($view_type == 'list'){
		$yt_list_template = get_option('yt_list_template');
    	$template_manager->display_template_styles($yt_list_template);
	}else{
		$yt_grid_template = get_option('yt_grid_template');
		$template_manager->display_template_styles($yt_grid_template);
	}
?>
<div class="yt-template-custom yatco-shortcode-yacht-results <?= join(' ', $root_classes) ?>" data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">
	<div class="container">
		<?php if ($attributes['sort'] === 1) : ?>
			<div class="yt-row top">
				<div class="yt-col-md-4 yt-col-12 yt-text-right yt-pull-right">
					<span class="sortby">
						<span class="yt-sort-box">
							<select class="yt-input fill-sortby" name="sortId" form="desktop-expanded-search" data-yatco-fill-options="Sorts" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'sortId', $_params, '0') ?>">
								<option>Loading...</option>
							</select>
						</span>
					</span>
					
				</div>
				
				<div class="yt-col-md-8 yt-col-12">
					<div class="list-or-grid yt-text-left">
		                <label>
		                    <input type="radio" name="viewtype" value="gallery" style="display: none;" <?php checked('gallery', $view_type); ?> form="desktop-expanded-search">
		                    <span>
		                        <!-- <span class="glyphicon glyphicon-th-large"></span>  -->
		                        <i class="fa fa-th-large"></i> Gallery
		                    </span>
		                </label> 
		                
						<label>                        
		                    <input type="radio" name="viewtype" value="list" style="display: none;" <?php checked('list', $view_type); ?> form="desktop-expanded-search">
		                    <span>
		                        <!-- <span class="glyphicon glyphicon-th-list"></span>  -->
		                        <i class="fa fa-bars"></i> Lists
		                    </span>
		                </label>     

					</div>
					<br>
					<div class="yt-search-count yt-text-left"><?= $total_count ?> listings matched your search.</div>
					<br>
				</div>
			</div>

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
	</div>

	<?php
	//double check if the template exists in database
	$grid_template_exists = $template_manager->template_exists($yt_grid_template);
	$list_template_exists = $template_manager->template_exists($yt_list_template);

	//if both template is assigned and exists in db then true or false
	$yt_grid_ok = (!empty($yt_grid_template) && $grid_template_exists === true) ? true : false;
	$yt_list_ok = (!empty($yt_list_template) && $list_template_exists === true) ? true : false;

	$layoutView = 'grid-view';

	if (isset($_params['viewtype']) && $_params['viewtype'] == 'list'){
		$layoutView = 'list-view';
	}
	
	$containerClass = 'card-container';

	if($layoutView == 'grid-view' && $yt_grid_ok){
		//this class is giving to override the hardcode card styles and make the template card styles work
		$containerClass = 'yt-card-container';
	}

	if($layoutView == 'list-view' && $yt_list_ok){
		//this class is giving to override the hardcode card styles and make the template card styles work
		$containerClass = 'yt-card-container';
	}
	?>
	<div class="<?php echo $layoutView; ?>">
		<div class="<?php echo $containerClass; ?>">
			<div class="yt-row <?php if($yt_grid_ok){ echo 'row';} ?>" id="yacht-loop">
				<?php

					if (!isset($results->Results) || !is_array($results->Results) || count($results->Results) === 0) : 

						include plugin_dir_path(__FILE__) . '../../partials/vessel-results-not-found.php';

					endif;
					if (isset($results->Results) && is_array($results->Results)) {

						foreach ($results->Results as $vessel) :
							if (isset($_params['viewtype']) && $_params['viewtype'] == 'list') :
								if($yt_list_ok){
									include 'list-result.php';
								}else{
									include plugin_dir_path(__FILE__) . '../../partials/yacht-result-parts/list-result.php';
								}
							else :
								if($yt_grid_ok){
									include 'grid-result.php';
								}else{
									include plugin_dir_path(__FILE__) . '../../partials/yacht-result-parts/grid-result.php';
								}
							endif;
						endforeach;
						
					}
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
		include plugin_dir_path(__FILE__) . '../../partials/yacht-result-parts/modal-form-for-brokerage-site.php';

		/// include apply_filters('yatco_connect_shortcode_yacht_results_lead_modal_template', $file_to_include);
	?>

	<?php 
		$vessel_ids = join(',', array_map(function($vessel) { return $vessel->VesselID;	}, $results->Results ?? []));
	?>

	<script type="text/javascript">
		window.addEventListener('load', function() {
			if (typeof _YCT != 'undefined') {
				_YCT.forsale.impressions("<?= $vessel_ids ?>");
			}
		});
	</script>
</div>