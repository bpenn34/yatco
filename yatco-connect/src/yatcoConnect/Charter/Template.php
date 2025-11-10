<?php 
	class yatcoConnect_Charter_Template {

		public function __construct() {

			$this->option = new yatcoConnect_Options();

			$this->template_manager = new yatcoConnect_TemplateManager();

		}

		public function add_actions_and_filters() {

			add_filter('single_template', [$this, 'use_single_template']);

		}


		public function use_single_template($single_template) {

			global $post, $wp_query;

			if ($wp_query->is_singular) {

				if ($post->post_type == 'yatco_charters') {

					$templateToUse = $this->option->getOption('yacht_single_template');	

					$yt_single_template_forCharter = get_option('yt_single_template_forCharter');

					//double check if the template exists in database
					$templateExists = $this->template_manager->template_exists($yt_single_template_forCharter);

					if( $templateToUse === 'template-builder' && !empty($yt_single_template_forCharter) && $templateExists === true ){

						$single_template = YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-charter-template-builder.php';

					}else{

						if ($templateToUse != 'current') {

							$single_template = YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-charter.php';
							
						}

					}

				}

			}

			return $single_template;

		}

	}