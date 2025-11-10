<?php 
	/*
		Filters List:
			- yatco_connect_shortcode_yacht_quick_search_template
			- yatco_connect_shortcode_yacht_search_template
			- yatco_connect_shortcode_yacht_results_template

		
	*/
	class yatcoConnect_Shortcodes_CharterSearch {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->_params = new yatcoConnect_ApiToBossCharterParams();		
		    $this->options = new yatcoConnect_Options();

			add_shortcode('yatco-charter-quick-search', [$this, 'charter_quick_search_form']);
			add_shortcode('yatco-charter-search-page-form', [$this, 'charter_search_page_form']);
			
			add_shortcode('yatco-charter-search-page-form-with-sliders', [$this, 'charter_search_page_form_with_sliders']);

			add_shortcode('yatco-charter-results', [$this, 'charter_results']);

			add_shortcode('yatco-charter-grid-slider', [$this, 'yatco_charter_grid_slider']);
		}

		public function yatco_charter_grid_slider( $atts = array(), $content = null ) {

			global $post, $wp_query;

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>CHARTER SEARCH SHORTCODE<br><br>';
			
			}

			// normalize attribute keys, lowercase
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
			
			$result_args = get_post_meta($post->ID, 'results_args', true);

			if ($result_args != '') {

				$decoded_args = json_decode($result_args, true);

				$atts = array_merge($atts, $decoded_args);

			}
			
			// override default attributes with user attributes
			$attributes = shortcode_atts([
				// 'template_style' => $this->opt_shortcode_styling,
				'sort' => 1,
				'pagination' => 1,
				'total_result_count' => false,
				'view-all-button' => false,
			], $atts);

			//$params=self::get_params_from_attributes($atts);

			$options=$this->options;

			// get search params checked out and ready
			$_params = $this->_params->prepare_search_parameters($atts);
			//echo "<pre>"; print_r($_params);echo "</pre>";exit;
			// call for results
			$results = $this->data->get_search_charter($_params);

			if( isset($results->error) ){
				return '';
			}

			$total_count = 0;

			if(isset($results->Count)){

				$total_count = $results->Count;

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

			// Padination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;
			$page_count = ceil( $total_count / $_params['page_size'] );

			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}

			ob_start();
				//var_dump($results);
				//var_dump($_params);
				//var_dump($attributes);

				$file_to_include=__DIR__.'/../partials/charter-grid-slider.php';
				
				include apply_filters('yatco_connect_charter_grid_slider', $file_to_include);
				
			return ob_get_clean();
		}

		public function get_params_from_attributes($atts) {

			$params_criteria = $this->_params->create_criteria();
		    $params_criteria = array_change_key_case((array)$params_criteria, CASE_LOWER);
		    $params_criteria =  array_merge($params_criteria, ['page_size' => 12, 'pageindex'=>1]);
		    $params = shortcode_atts($params_criteria, $atts);

		    return $params;

		}

		public function charter_quick_search_form( $atts = array(), $content = null ) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling
                'action_url' =>  $this->options->getOption('charter_search_url'),
            ], $atts);	

            $_params = $this->_params->prepare_search_parameters($atts);

		    $options = $this->options;

		    $action_url = $attributes['action_url'];

		    $advanced_search_params=json_decode(json_encode($_params), true);

		    $advanced_search_params['openSearchForm'] = 'yes';

		    $advanced_search_url = $action_url.'?'.http_build_query($advanced_search_params);

		    ob_start();

				$file_to_include=__DIR__.'/../partials/charter/shortcode-charter-quick-search-form.php'; 
				
				include apply_filters('yatco_connect_shortcode_charter_quick_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function charter_search_page_form( $atts = array(), $content = null ) {

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/charter/shortcode-charter-search-form.php'; 

		    	include apply_filters('yatco_connect_shortcode_charter_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function charter_search_page_form_with_sliders( $atts = array(), $content = null ) {

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
               
            ], $atts);	

		    $options = $this->options;

		    // $param_atts=self::get_params_from_attributes($atts);

            // get search params checked out and ready
			$_params = $this->_params->prepare_search_parameters($atts);

		    ob_start();
		  		
				$file_to_include=__DIR__.'/../partials/charter/shortcode-charter-search-form-with-sliders.php'; 

		    	include apply_filters('yatco_connect_shortcode_charter_search_template', $file_to_include);

		    return ob_get_clean();

		}

		public function charter_results( $atts = array(), $content = null ) {

			global $post, $wp_query;

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>CHARTER SEARCH SHORTCODE<br><br>';
			
			}

			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    $result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}
	    	
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
                // 'template_style' => $this->opt_shortcode_styling,
		    	'sort' => 1,
		    	'pagination' => 1,
		    	'total_result_count' => false,
		    	'view-all-button' => false,
            ], $atts);

		    //$params=self::get_params_from_attributes($atts);

		    $options=$this->options;

			// get search params checked out and ready
			$_params = $this->_params->prepare_search_parameters($atts);

			// call for results
			$results = $this->data->get_search_charter($_params);
			$total_count = 0;

			if(isset($results->Count)){

				$total_count = $results->Count;

			}

			// Padination
			$page_index = (isset($_params['page_index']))?$_params['page_index']:1;
			$page_count = ceil( $total_count / $_params['page_size'] );

			$root_classes=[];

			if (isset($attributes['view-all-button']) && ($attributes['view-all-button'] == 'true' || $attributes['view-all-button'] == '1')) {
				$root_classes[] = 'view-all-button';
			}

			ob_start();
				//var_dump($results);
				//var_dump($_params);
				//var_dump($attributes);

				$file_to_include=__DIR__.'/../partials/charter/shortcode-charter-results.php';
				
				include apply_filters('yatco_connect_shortcode_charter_results_template', $file_to_include);
				
		    return ob_get_clean();

		}
		
	}