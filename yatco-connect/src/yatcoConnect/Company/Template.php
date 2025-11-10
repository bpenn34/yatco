<?php 
	class yatcoConnect_Company_Template {

		public function __construct() {

			$this->option = new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('single_template', [$this, 'use_single_template']);

		}

		public function use_single_template($single_template) {

			global $post, $wp_query;

			if ($wp_query->is_singular) {

				if ($post->post_type == 'yatco_companies') {

					$templateToUse = $this->option->getOption('yacht_single_template');			

					if ($templateToUse != 'current') {

						$single_template=YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/single-yatco-company.php';

					}
					
				}

			}

			return $single_template;

		}

	}