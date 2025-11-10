<?php
	class yatcoConnect_WpSeo_FourZeroFour {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->options = new yatcoConnect_Options();

		}


		public function add_actions_and_filters() {

			add_filter('template_redirect', [$this, '_404'], 1);

		}

		public function _404() {

			global $wp_query;

			if ($wp_query->is_404()) {
				if ($wp_query->get('yacht_name') != "" || $wp_query->get('yacht_id') != '') {
					wp_redirect( $this->options->getOption('yacht_search_url') );
					exit;

				}
				elseif ($wp_query->get('charter_name') != "" || $wp_query->get('charter_id') != '') {
					wp_redirect( $this->options->getOption('charter_search_url') );
					exit;

				}
				elseif ($wp_query->get('broker_name') != "" || $wp_query->get('broker_id') != '') {
					wp_redirect( $this->options->getOption('broker_search_url') );
					exit;

				}
				elseif (
					$wp_query->get('company_name') != "" || $wp_query->get('company_id')
					||
					$wp_query->get('company_builder_name') != "" || $wp_query->get('company_builder_id')
				) {
					wp_redirect( $this->options->getOption('company_search_url') );
					exit;

				}
				


			}

		}

	}