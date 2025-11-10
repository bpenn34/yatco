<?php 
	class yatcoConnect_Shortcodes_GrabbedYachtDetails {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->yacht_params = new yatcoConnect_ApiToBossYachtParams();
			
		    $this->options = new yatcoConnect_Options();
		    $this->opt_shortcode_styling = $this->options->getOption('shortcode_styling');

			add_shortcode('yatco-grab-yacht', [$this, 'grab_yacht']);
			add_shortcode('yatco-print-grabbed-yacht', [$this, 'print_grabbed_yacht_data']);
			
			add_shortcode('yatco-grabbed-yacht-details-gallery', [$this, 'yacht_details_gallery_section']);
			add_shortcode('yatco-grabbed-yacht-details-full-spec', [$this, 'yacht_details_full_spec_section']);
			add_shortcode('yatco-grabbed-yacht-details-lead-form', [$this, 'yacht_details_lead_form_section']);
		}

		public function grab_yacht($atts = array(), $content = null) {
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            	'yacht_id' => '',
            ], $atts);

			$vessel = $this->data->get_vessel($atts['yacht_id']);

			if (
				isset($vessel) 
				&& 
				$vessel !== -1 && $vessel !== "-1"
				&&
				($vessel->MiscInfo->Status == 1 || $vessel->MiscInfo->Status == 4)
			) {

				$GLOBALS['YATCO_GRABBED_YACHT']=$vessel;

			}

		}

		public function print_grabbed_yacht_data($atts = array(), $content = null) {
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            	'var' => '',
            	'var_sub' => '',
            ], $atts); 

            if (isset($GLOBALS['YATCO_GRABBED_YACHT'])) {

			    $vessel = $GLOBALS['YATCO_GRABBED_YACHT'];

				if (isset($atts['var']) && isset($atts['var_sub'])) {

					print $vessel->{$atts['var']}->{$atts['var_sub']};
				
				}

            }

        }


        public function yacht_details_full_spec_section($atts = array(), $content = null) {

        	ob_start();
				
        		if (isset($GLOBALS['YATCO_GRABBED_YACHT'])) {

			    	$vessel = $GLOBALS['YATCO_GRABBED_YACHT'];

			    }

				$file_to_include=__DIR__.'/../partials/yacht-details/full-spec.php';
				
				include apply_filters(
					'yatco_connect_shortcode_yacht_details_full_spec', 
					$file_to_include
				);
				
		    return ob_get_clean();

        }

        public function yacht_details_gallery_section($atts = array(), $content = null) {

        	ob_start();
			
        		if (isset($GLOBALS['YATCO_GRABBED_YACHT'])) {

			    	$vessel = $GLOBALS['YATCO_GRABBED_YACHT'];

			    }

				$file_to_include=__DIR__.'/../partials/yacht-details/gallery.php';
				
				include apply_filters(
					'yatco_connect_shortcode_yacht_details_gallery', 
					$file_to_include
				);
				
		    return ob_get_clean();

        }

        public function yacht_details_lead_form_section($atts = array(), $content = null) {

			ob_start();
			
				if (isset($GLOBALS['YATCO_GRABBED_YACHT'])) {

			    	$vessel = $GLOBALS['YATCO_GRABBED_YACHT'];

			    }

			    $options = $this->options;

				$file_to_include=__DIR__.'/../partials/yacht-details/lead-form.php';
				
				include apply_filters(
					'yatco_connect_shortcode_yacht_details_lead_form', 
					$file_to_include
				);
				
		    return ob_get_clean();


        }

		
	}