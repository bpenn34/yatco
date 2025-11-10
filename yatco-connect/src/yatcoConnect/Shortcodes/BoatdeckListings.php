<?php 
	class yatcoConnect_Shortcodes_BoatdeckListings {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBossForBoatdeck();

			$this->boatdeck_params = new yatcoConnect_ApiToBossBoatdeckParams();

			$this->options = new yatcoConnect_Options();

			add_shortcode('boatdeck-products', [$this, 'get_boatdeck_products']);

		}

		public function get_boatdeck_products(  $atts = array(), $content = null ){

			global $post, $wp_query;
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);

			// override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling,
		    	'sort' => 1,
		    	'pagination' => 1,
		    	'total_result_count' => false,
		    	'view-all-button' => false
            ], $atts);

			//get the values from API Data Filter Options
			$atts = $this->api_data_filter($atts);

			$result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }

			$options=$this->options;

			$image_size = $options->getOption('yacht_search_image_size');
			
			// get search params checked out and ready
			$_params = $this->boatdeck_params->prepare_search_parameters($atts);

			$products = $this->data->get_boatdeck_products( $_params );

			//echo "<pre>"; print_r($products); echo "</pre>";exit;

			$total_count = $products->total_results ?? 0;
			// Padination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;

			$page_count = 0;

			if (isset($results->per_page) && $results->per_page > 0) {

				$page_count = ceil($results->Count / $results->per_page);

			}

			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}
			
			ob_start();

				$file_to_include=__DIR__.'/../partials/boatdeck-products.php';
				
				include apply_filters('yatco_connect_shortcode_boatdeck_products', $file_to_include);
				
		    return ob_get_clean();

		}

		public function api_data_filter( $atts ){

			$filterData = array();

			if(!empty(get_option('yt_yacht_api_pricerange_from'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['pricerange_from'] = get_option('yt_yacht_api_pricerange_from');
			}
			if(!empty(get_option('yt_yacht_api_pricerange_to'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['pricerange_to'] = get_option('yt_yacht_api_pricerange_to');
			}
			if(!empty(get_option('yt_yacht_api_loa_from'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['loa_from'] = get_option('yt_yacht_api_loa_from');
			}
			if(!empty(get_option('yt_yacht_api_loa_to'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['loa_to'] = get_option('yt_yacht_api_loa_to');
			}
			if(!empty(get_option('yt_yacht_api_lengthunit'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['lengthunit'] = get_option('yt_yacht_api_lengthunit');
			}
			if(!empty(get_option('yt_yacht_api_currencytype'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['currencytype'] = get_option('yt_yacht_api_currencytype');
			}
			if(!empty(get_option('yt_yacht_api_exclude_vessels'))){
				//Assigning value only if it exists in API Data Filter
				$filterData['ExcludeVesselIDs'] = explode(",", get_option('yt_yacht_api_exclude_vessels'));
			}

			$atts = array_merge($filterData, $atts);
			
			return $atts;

		}

	}