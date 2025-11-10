<?php
	$view_type = (isset($_GET['viewtype']))?$_GET['viewtype']:'gallery';
	
    $template_manager = new yatcoConnect_TemplateManager();

	$yt_grid_template_forCharter = $yt_list_template_forCharter = '';

	$use_templateBuilder = false;

	if ( get_option('yatco_connect_yacht_single_template') === 'template-builder' ) {
		$use_templateBuilder = true;
	}

	if($view_type == 'list'){

		$yt_list_template_forCharter = get_option('yt_list_template_forCharter');

	}else{

		$yt_grid_template_forCharter = get_option('yt_grid_template_forCharter');

		$gridCount = $template_manager->get_gridCount_by_id($yt_grid_template_forCharter);

		$colClass = 4;

		if($gridCount){

			$colClass = 12/$gridCount;

		}
	}

	//double check if the template exists in database
	$grid_template_exists = $template_manager->template_exists($yt_grid_template_forCharter);
	$list_template_exists = $template_manager->template_exists($yt_list_template_forCharter);

	//if both template is assigned and exists in db then true or false
	$yt_grid_ok = ( !empty($yt_grid_template_forCharter) && $grid_template_exists === true && $use_templateBuilder === true ) ? true : false;
	$yt_list_ok = ( !empty($yt_list_template_forCharter) && $list_template_exists === true && $use_templateBuilder === true ) ? true : false;
    
	if( $yt_grid_ok === true ){
		//display the grid styles
		$template_manager->display_template_styles($yt_grid_template_forCharter);
	}
	
	if( $yt_list_ok === true ){
		//display the list styles
		$template_manager->display_template_styles($yt_list_template_forCharter);
	}
?>

<div class="<?php if( $yt_grid_ok || $yt_list_ok ){ echo 'yt-template-custom';} ?> yatco-shortcode-charter-results yatco-shortcode-yacht-results <?= join(' ', $root_classes) ?>" data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

	<?php if ($attributes['sort'] === 1) : ?>
		<div class="yt-row top">
			<div class="yt-col-md-8 yt-col-12">
				<div class="list-or-grid">
	                <label>
	                    <input type="radio" name="viewtype" value="gallery" style="display: none;" <?php checked('gallery', $view_type); ?> form="desktop-expanded-search">
	                    <span>
	                        <!-- <span class="glyphicon glyphicon-th-large"></span>  -->
	                        GALLERY VIEW
	                    </span>
	                </label> 
	                
					<label>                        
	                    <input type="radio" name="viewtype" value="list" style="display: none;" <?php checked('list', $view_type); ?> form="desktop-expanded-search">
	                    <span>
	                        <!-- <span class="glyphicon glyphicon-th-list"></span>  -->
	                        LIST VIEW
	                    </span>
	                </label>     

				</div>
			</div>
			
			<div class="yt-col-md-4 yt-col-12 yt-text-right">
				<span class="sortby">
					<span class="label">Sort By: </span>

					<span class="">
						<select class="yt-input" name="sortField" form="charter-expanded-search" style="width: auto; display: inline;">
							<option value="">Default</option>
							<option value="HighRate">PRICE</option>
							<option value="LOA">LENGTH</option>
							<option value="NumberOfGuests">GUEST</option>
						</select>

						<select name="sortDirection" form="charter-expanded-search" style="width: auto; display: inline;">
							<option value="asc">ASC</option>
							<option value="desc">DESC</option>
						</select>
					</span>
				</span>
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
			
			<div class="yt-text-right" style="font-size: 16px;">
				
				<?= $total_count ?> Results 

			</div>

		<?php endif; ?>

	<?php endif; ?>

	<div class="<?php if($yt_grid_ok){ echo 'yt-card-container';}else{ echo 'card-container'; } ?>">
		<div class="<?php if($yt_grid_ok){ echo 'grid-view';} ?>">
			<div class="yt-row <?php if($yt_grid_ok){ echo 'row';} ?>">
				<?php
					if ($total_count == 0) : 
						include 'results-not-found.php';
					
						else :
				
						foreach ($results->Results as $charter) :
							if (isset($_GET['viewtype']) && $_GET['viewtype'] == 'list') :
								if($yt_list_ok){
									include 'templates/list-result.php';
								}else{
									include 'shortcode-charter-results-list.php';
								}
							else :
								if($yt_grid_ok){
									include 'templates/grid-result.php';
								}else{
									include 'shortcode-charter-results-grid.php';
								}
							endif; 
						endforeach;

					endif;
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
					            'end_size' => 3,
					            'prev_text' => "<",
					            'next_text' => ">"
					        ));
						?>					
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<?php include 'shortcode-charter-results-modal.php'; ?>

	<?php
	if(isset($results->Results)){
		$vessel_ids = join(',', array_map(function($vessel) {return $vessel->VesselID;}, $results->Results));
	}
	?>

	<script type="text/javascript">
		window.addEventListener('load', function() {
			if (typeof _YCT != 'undefined') {
				_YCT.charter.impressions("<?= $vessel_ids ?>");
			}
		});
	</script>
</div>