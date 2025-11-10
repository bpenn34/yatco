<?php
	/*
		Filters List:
			- yatco_connect_shortcode_yacht_quick_search_template
			- yatco_connect_shortcode_yacht_search_template
			- yatco_connect_shortcode_yacht_results_template

		
	*/
	class yatcoConnect_Shortcodes_GeneralYacht {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->yacht_params = new yatcoConnect_ApiToBossYachtParams();
			
		    $this->options = new yatcoConnect_Options();
		    $this->opt_shortcode_styling = $this->options->getOption('shortcode_styling');

			add_shortcode('yatco-yachts-quick-search', [$this, 'yacht_quick_search_form']);
			add_shortcode('yt-yachts-quick-search', [$this, 'yt_quick_search_form']);

			add_shortcode('yatco-vertical-yachts-quick-search', [$this, 'vertical_yacht_quick_search_form']);

			add_shortcode('yatco-yachts-search-page-form', [$this, 'yacht_search_page_form']);
			add_shortcode('yt-search-form', [$this, 'yt_search_form']);

			add_shortcode('yatco-yachts-vertical-search-page-form', [$this, 'yacht_vertical_search_page_form']);

			add_shortcode('yatco-yachts-advanced-search-form', [$this, 'yacht_adavnced_search_form']);

			add_shortcode('yatco-yachts-search-page-form-with-sliders', [$this, 'yachts_search_page_form_with_sliders']);

			add_shortcode('yatco-yachts-result-tags', [$this, 'yacht_result_tags']);
			add_shortcode('yatco-yachts-results', [$this, 'yacht_results']);
			add_shortcode('yatco-featured-yachts-results', [$this, 'featured_yacht_results']);
			
			add_shortcode('yatco-link-list-to-search', [$this, 'yachts_link_list_to_search']);

			//Shortcode for grid slider
			add_shortcode('yatco-yachts-grid-slider', [$this, 'yatco_yachts_grid_slider']);

		}

		public function yatco_yachts_grid_slider( $atts = array(), $content = null ){
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

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>YACHT SEARCH SHORTCODE<br><br>';
			
			}
			
			$result_args = get_post_meta($post->ID, 'results_args', true);

			if ($result_args != '') {

				$decoded_args = json_decode($result_args, true);

				$atts = array_merge($atts, $decoded_args);

			}

			$SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
			if ( is_array($SUPER_ROOT_ARGS) ) {
				$atts = array_merge($atts, $SUPER_ROOT_ARGS);
			}

			//$params=self::get_params_from_attributes($atts);

			$options=$this->options;

			$image_size = $options->getOption('yacht_search_image_size');

			// get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);
			// call for results
			$results = $this->data->get_search($_params);
			
			if( isset($results->error) ){
				return '';
			}
			
			//Define display template id as shortcode attribute 'template_id'
			$template_id = '';
			if(isset($atts['template_id']) && !empty($atts['template_id'])){
				$template_id = $atts['template_id'];
			}

			//Define number of card to display in desktop as shortcode attribute 'card_count'
			$card_count = 3;
			if(isset($atts['card_count']) && !empty($atts['card_count'])){
				$card_count = $atts['card_count'];
			}

			$total_count = $results->Count ?? 0;
			// Pagination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;

			$page_count = 0;

			if (isset($results->Records) && $results->Records > 0) {

				$page_count = ceil($results->Count / $results->Records);

			}

			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}
	
			ob_start();

			$file_to_include=__DIR__.'/../partials/yacht-grid-slider.php';
				
			include apply_filters('yatco_connect_shortcode_yacht_grid_slider', $file_to_include);

			return ob_get_clean();
		}

		public function get_params_from_attributes($atts) {

			$params_criteria = $this->yacht_params->create_criteria();
		    $params_criteria = array_change_key_case((array)$params_criteria, CASE_LOWER);
		    $params_criteria =  array_merge($params_criteria, ['page_size' => 12, 'pageindex'=>1]);
		    $params = shortcode_atts($params_criteria, $atts);

		    return $params;

		}

		public function yacht_quick_search_form( $atts = array(), $content = null ) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling
                'action_url' =>  $this->options->getOption('yacht_search_url'),
                'builder_input_type' => 'text',
            ], $atts);	

            $_params = $this->yacht_params->prepare_search_parameters($atts);

		    $options = $this->options;

		    $action_url = $attributes['action_url'];

		    ob_start();

				$file_to_include=__DIR__.'/../partials/quick-search.php'; 
				
				include apply_filters('yatco_connect_shortcode_yacht_quick_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yt_quick_search_form( $atts = array(), $content = null ) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling
                'action_url' =>  $this->options->getOption('yacht_search_url'),
                'builder_input_type' => 'text',
            ], $atts);	

            $_params = $this->yacht_params->prepare_search_parameters($atts);

		    $options = $this->options;

		    $action_url = $attributes['action_url'];

		    ob_start();

				$file_to_include=__DIR__.'/../partials/yt-quick-search.php'; 
				
				include apply_filters('yatco_connect_shortcode_yacht_quick_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function vertical_yacht_quick_search_form( $atts = array(), $content = null ) {

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling
                'action_url' =>  $this->options->getOption('yacht_search_url'),
                'builder_input_type' => 'text',
            ], $atts);	

            $_params = $this->yacht_params->prepare_search_parameters($atts);

		    $options = $this->options;

		    $action_url = $attributes['action_url'];

		    ob_start();

				$file_to_include=__DIR__.'/../partials/vertical-quick-search.php'; 
				
				include apply_filters('yatco_connect_shortcode_vertical_yacht_quick_search_template', $file_to_include);

		    return ob_get_clean();


		}

		public function yacht_search_page_form( $atts = array(), $content = null ) {

			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

	
		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }
		    		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               'builder_input_type' => 'text',
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/search-form.php'; 

		    	include apply_filters('yatco_connect_shortcode_yacht_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yt_search_form( $atts = array(), $content = null ){
			
			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

	
		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }
		    		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               'builder_input_type' => 'text',
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/boatdeck-search-form.php'; 

		    	include apply_filters('yatco_connect_shortcode_boatdeck_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yachts_search_page_form_with_sliders( $atts = array(), $content = null ) {

			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

	
		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }
		    		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               'builder_input_type' => 'text',
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/search-form-with-sliders.php'; 

		    	include apply_filters('yatco_connect_shortcode_yacht_search_with_sliders_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yacht_adavnced_search_form( $atts = array(), $content = null ) {

			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}
	
		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }
		    		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               'builder_input_type' => 'text',
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/search-form-advanced.php'; 

		    	include apply_filters('yatco_connect_shortcode_yacht_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yacht_vertical_search_page_form( $atts = array(), $content = null ) {
			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

	
		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }
		   	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               'builder_input_type' => 'text',
            ], $atts);	

		    $options = $this->options;

		    $data = $this->data;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/vertical-search-form.php'; 

		    	include apply_filters('yatco_connect_shortcode_yacht_vertical_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function yacht_result_tags( $atts = array(), $content = null ) {
			global $post;

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);

		    // et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>YACHT SEARCH TAGS SHORTCODE<br><br>';
			
			}

			$result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

		    $_params = $this->yacht_params->prepare_search_parameters($atts);

		    $param_to_tag_label = [

		    	'BuilderID' => 'Builder',

		    	'VesselName' => 'Vessel Name',

		    ];

		    ob_start();
					
		    	
					
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

		public function yacht_results( $atts = array(), $content = null ) {
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

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>YACHT SEARCH SHORTCODE<br><br>';
			
			}
			
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

		    $SUPER_ROOT_ARGS = json_decode($this->options->getOption('yacht_search_result_args'), true);
			
		    if ( is_array($SUPER_ROOT_ARGS) ) {
	    		$atts = array_merge($atts, $SUPER_ROOT_ARGS);
		    }

		    //$params=self::get_params_from_attributes($atts);

		    $options=$this->options;

			$image_size = $options->getOption('yacht_search_image_size');

			// get search params checked out and ready
			$_params = $this->yacht_params->prepare_search_parameters($atts);
			// call for results
			$results = $this->data->get_search($_params);
			
			$total_count = $results->Count ?? 0;

			//if ( ! is_admin() ) {
			//echo "<pre>"; print_r($results); echo "</pre>";
			//echo "<pre>Count : "; print_r($total_count); echo "</pre>";
			//}
			
			// Padination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;

			$page_count = 0;

			if (isset($results->Records) && $results->Records > 0) {

				$page_count = ceil($results->Count / $results->Records);

			}

			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}

			ob_start();
				// var_dump($results);
				// var_dump($_params);
				//var_dump($attributes);
				//jpEdit

				$file_to_include=__DIR__.'/../partials/yacht-results.php';
				
				include apply_filters('yatco_connect_shortcode_yacht_results_template', $file_to_include);
				
		    return ob_get_clean();

		}

		public function featured_yacht_results( $atts = array(), $content = null ) {

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array) $atts, CASE_LOWER);
		 	
		    // override default attributes with user attributes
			$attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling,
		    	'sort' => 1,
		    	'pagination' => 1,
		    	'total_result_count' => false,
		    	'view-all-button' => false
            ], $atts);

		    //$params=self::get_params_from_attributes($atts);

		    $options=$this->options;

		    if (isset($atts['vessel_ids']) || isset($atts['mls_ids'])) {
		    	
		    }
			else {
				$atts['featured'] = '1';
			}

			$_params = $this->yacht_params->prepare_search_parameters($atts);
			
			// call for results
			$results = $this->data->get_search($_params);
			$total_count = $results->Count;
			
			// Padination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;
			$page_count = ceil($results->Count / $results->Records);
			
			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}

			ob_start();
				//var_dump($results);
				//var_dump($_params);
				//var_dump($attributes);

				$file_to_include=__DIR__.'/../partials/featured-yacht-results.php';
				
				//include apply_filters('yatco_connect_shortcode_featured_yacht_results_template', $file_to_include);
				include apply_filters('yatco_connect_shortcode_yacht_results_template', $file_to_include);
				
		    return ob_get_clean();

		}
		
		public function yachts_link_list_to_search( $atts = array(), $content = null ) {

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                'list_label' => '',
                'param' => ''
            ], $atts);

		    $options = $this->options;

		    switch ($attributes['list_label']) {
		    	case 'PopularBuilders':
		    		$list = $this->data->get_popular_builders();
		    		# code...
		    		break;
		    	
		    	default:
		    		$list = $this->data->get_list($attributes['list_label']);
		    		break;
		    }

		    ob_start();

			    // var_dump($list);

			    echo '<div class="shortcode-yachts-link-list-to-search">';
			    	echo '<ul>';

			    		foreach ($list as $item) {
			    			$item = (object) $item;

			    			echo '<li><a href="'. $options->getOption('yacht_search_url') .'?'. $attributes['param'] .'='. $item->id .'">'. $item->text .'</a></li>';

			    		}

			    	echo '</ul>';
			   	echo '</div>';


		    return ob_get_clean();
		}
		
	}