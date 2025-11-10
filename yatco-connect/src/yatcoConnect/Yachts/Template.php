<?php 
	class yatcoConnect_Yachts_Template {

		public function __construct() {

			$this->option = new yatcoConnect_Options();

			$this->template_manager = new yatcoConnect_TemplateManager();

		}

		public function add_actions_and_filters() {

			add_filter('single_template', [$this, 'use_single_template'], 10, 1);

			add_filter('body_class', [$this, 'yacht_details_body_class']);

			add_filter( 'yatco_connect_shortcode_yacht_results_template', [$this, 'display_yatco_connect_pro_template'] );

		}

		public function use_single_template($single_template) {

			global $post, $wp_query;

			if (is_singular('yatco_yachts')) {

				$templateToUse = $this->option->getOption('yacht_single_template');	

				$vessel = $post->boss_data;
				
				if ($templateToUse != 'current') {
					if ($vessel->MiscInfo->Status == 1 || $vessel->MiscInfo->Status == 4) {
						$yt_single_template = get_option('yt_single_template');
						
						//double check if the template exists in database
						$template_exists = $this->template_manager->template_exists($yt_single_template);
						
						if($templateToUse == 'template-builder' && !empty($yt_single_template) && $template_exists === true){
							$single_template = YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-yacht-template-builder.php';
						}else{	
							$single_template = YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-yacht-for-brokerage-sites.php';
						}	
					}
					else {
						$single_template = YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-yacht-off-market.php';
					}
				}
				
			}

			return $single_template;

		}

		public function yacht_details_body_class($classes) {

			global $post;

			if (is_singular('yatco_yachts') && isset($post->boss_data)) {
				//is_boat_by_length
				if ($post->boss_data->is_boat_by_length) {
					$classes[]='is_boat_by_length';
				}
				else {
					$classes[]='is_yacht_by_length';
				}

				// sail or motor
				if ($post->boss_data->BasicInfo->VesselType == 1) {
					$classes[]='vessel_type_sail';
				}
				elseif ($post->boss_data->BasicInfo->VesselType == 2) {
					$classes[]='vessel_type_motor';
				}

				// On The Market
				if (
					$post->boss_data->MiscInfo->Status == 1 
					|| 
					$post->boss_data->MiscInfo->Status == 4
				) {
					$classes[]='vessel_status_on_the_market';
				}
				else {

					$classes[]='vessel_inactive';

				}
			}

			return $classes;

		}

		public function display_yatco_connect_pro_template( $yt_data ) {
			
			if(get_option('yatco_connect_yacht_single_template') == 'template-builder'){
				$file_to_include = __DIR__.'/templates/yacht-results.php';
				return $file_to_include;
			}else{
				return $yt_data;
			}
		}

	}