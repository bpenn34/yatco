<?php
	
	class yatcoConnect_Shortcodes_ServicesMLS {

		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->yacht_params = new yatcoConnect_ApiToBossYachtParams();
			
		    $this->options = new yatcoConnect_Options();
		    $this->opt_shortcode_styling = $this->options->getOption('shortcode_styling');

			add_shortcode('yatco-services-mls-search', [$this, 'search_form']);
			add_shortcode('yatco-services-mls-results', [$this, 'results']);

		}

		public function search_form( $atts = array(), $content = null ) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            
            ], $atts);	

            $apiToPro = $this->data;

            $_params = $_REQUEST;

			$_params = array_merge($_params, $atts);

           	ob_start();

				$file_to_include=""; 
				
				include apply_filters('yatco_connect_shortcode_service_mls_search_template', $file_to_include);

		    return ob_get_clean();

        }

        public function results( $atts = array(), $content = null ) {
        	global $post, $wp_query;

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>SERVICES MLS SHORTCODE<br><br>';
			
			}

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([

            ], $atts);	

		    $_params = [
		    	'records' => 10
		    ];

		    $_params = $_REQUEST;

			$_params = array_merge($_params, $atts);

			if ($_params['page_index'] > 1) {
				$_params['offset'] = ($_params['page_index']-1) * 10;
			}
			else {
				$_params['offset'] = 0;
			}

		    $results = $this->data->memberships($_params);

			$total_count = $results->TotalRecordCount;

			// Padination

			if ($results->Count > 0) {
				$page_index = (isset($_params['page_index']))?$_params['page_index']:1;

				$page_count = ceil($results->TotalRecordCount / $results->Count);

				$apiToPro = $this->data;

				$options = $this->options;

			}

		    ob_start();

		    	//var_dump($_params);


				$file_to_include=""; 
				
				include apply_filters('yatco_connect_shortcode_service_mls_results_template', $file_to_include);

		    return ob_get_clean();

        }


	}