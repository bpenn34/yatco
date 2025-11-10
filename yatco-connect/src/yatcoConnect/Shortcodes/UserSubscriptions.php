<?php 
	class yatcoConnect_Shortcodes_UserSubscriptions {
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			
		    $this->options = new yatcoConnect_Options();
		    
			add_shortcode('yatco-user-newsletter-signup', [$this, 'html_newsletter_signup']);
			add_shortcode('yatco-user-subscriptions', [$this, 'html_user_subscriptions']);
		}

		public function html_user_subscriptions($atts = array(), $content = null) {
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([

            ], $atts);

		   	$options=$this->options;

		    $user_email=$_GET['email'];

		    if ($user_email && !empty($user_email)) {
		    	$user_subscriptions = $this->data->get_user_subscriptions($user_email);
		    }
		    else {
		    	$user_subscriptions=[];
		    }

		    ob_start();

				$file_to_include=__DIR__.'/../partials/shortcode-user-subscriptions.php';
				
				include apply_filters(
					'yatco_connect_shortcode_user_subscriptions', 
					$file_to_include
				);

		    return ob_get_clean();

        }

        public function html_newsletter_signup($atts = array(), $content = null) {
        	$atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([

            ], $atts);

		   	$options=$this->options;

		   	if (isset($_GET['email'])) {
		    	$user_email=$_GET['email'];
		   	}
		   	else {
		    	$user_email='';
		   	}

		    if ($user_email && !empty($user_email)) {
		    	$user_subscriptions = $this->data->get_user_subscriptions($user_email);
		    }
		    else {
		    	$user_subscriptions=[];
		    }

		    ob_start();

				$file_to_include=__DIR__.'/../partials/shortcode-newsletter-signup.php';
				
				include apply_filters(
					'yatco_connect_shortcode_user_newsletter', 
					$file_to_include
				);

		    return ob_get_clean();


        }



		
	}