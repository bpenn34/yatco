<?php
	class yatcoConnect_Shortcodes_Forms {
		public function __construct() {
			$this->options = new yatcoConnect_Options();

			add_shortcode('yatco-general-contact-form', [$this, 'general_contact_form']);
		}	

		public function general_contact_form($atts = array(), $content = null) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            	
            ], $atts);

            $options=$this->options;

            ob_start();

            	$file_to_include=__DIR__.'/../partials/shortcode-general-contact-form.php';
				
				include apply_filters(
					'yatco_connect_shortcode_general_contact_form_template', 
					$file_to_include
				);

		    return ob_get_clean();


		}
		

	}	