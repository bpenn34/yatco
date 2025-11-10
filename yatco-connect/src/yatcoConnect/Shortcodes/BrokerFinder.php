<?php 
	class yatcoConnect_Shortcodes_BrokerFinder {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->options = new yatcoConnect_Options();
			$this->broker_params = new yatcoConnect_ApiToBossBrokerParams();

			add_shortcode('yatco-broker-finder-form', [$this, 'broker_finder_form']);
			add_shortcode('yatco-broker-finder', [$this, 'broker_finder']);

		}

		public function broker_finder_form($atts = array(), $content = null) {
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
		 	
			ob_start();
          		//var_dump($_params);
            	
            	$file_to_include=__DIR__.'/../partials/broker/broker-finder-form.php'; 

		    	include apply_filters('yatco_connect_shortcode_broker_finder_form_template', $file_to_include);

		    return ob_get_clean();

		}

		public function broker_finder($atts = array(), $content = null) {
			global $post, $wp_query;
			
			$atts = array_change_key_case((array)$atts, CASE_LOWER);

			// et_pb_preview
			if (isset($wp_query->query['et_pb_preview']) && $wp_query->query['et_pb_preview'] == 'true') {

				return '<br><br>BROKER FINDER SHORTCODE <br><br>';
			
			}

		 	$result_args = get_post_meta($post->ID, 'results_args', true);

	    	if ($result_args != '') {

	    		$decoded_args = json_decode($result_args, true);

	    		$atts = array_merge($atts, $decoded_args);

	    	}

		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            	
            ], $atts);
            
           	$_params = $this->broker_params->prepare_search_parameters($atts);	

            $results = $this->data->get_broker_finder($_params);
			
			$options = $this->options;

            ob_start();
          		//echo '<br>';
          		//var_dump($results);
          		/*echo '<br>';
          		var_dump($_params);*/
          		//var_dump($_params);
            	
            	$file_to_include=__DIR__.'/../partials/broker/broker-finder.php'; 

		    	include apply_filters('yatco_connect_shortcode_broker_finder_template', $file_to_include);

		    return ob_get_clean();

		}

	}