<?php 
	class yatcoConnect_RestApi{


		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->data_leads=new yatcoConnect_ApiToBossLeads();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_action('rest_api_init', [$this, 'register_rest_routes']);

		}

		public function register_rest_routes() {

			register_rest_route( 'yatco', '/form-data-common', array(
		        'callback' => [$this, 'common_form_data'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array(
		            'label' => array(
		                'required' => true,
		                'default' => [],
		            ),
		        )
		    ) );
			
			register_rest_route( 'yatco', '/yachts', array(
		        'callback' => [$this, 'yachts'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/yacht-detail', array(
		        'callback' => [$this, 'yacht_detail'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

			register_rest_route( 'yatco', '/send-lead', array(
		        'callback' => [$this, 'submit_any_lead'],
		        'methods'  => [WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array(
		            'label' => array(
		                'required' => true,
		                'default' => [],
		            ),
		        	'form_data' => array(
		                'required' => true,
		                'default' => [],
		            ),

		        )
		    ) );

			register_rest_route( 'yatco', '/submit-crm-contact', array(
		        'callback' => [$this, 'submit_crm_contact'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/submit-crm-contact-and-form', array(
		        'callback' => [$this, 'submit_crm_contact_and_form'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/charters', array(
		        'callback' => [$this, 'charters'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/brokers', array(
		        'callback' => [$this, 'brokers'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/brokerages', array(
		        'callback' => [$this, 'brokerages'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		    register_rest_route( 'yatco', '/services-mls-search', array(
		        'callback' => [$this, 'services_mls_search'],
		        'methods'  => [WP_REST_Server::READABLE, WP_REST_Server::CREATABLE],
		        'permission_callback' => '__return_true',
		        'args'     => array()
		    ) );

		}

		public function verify_captcha($user_response) {
	        $fields_string = '';
	        $remoteip = $_SERVER['REMOTE_ADDR'];
	        $fields = array(
	            'secret' => $this->options->getOption('google_recap_api_secret'),
	            'response' => $user_response,
	            'remoteip' => $remoteip
	        );

	        $fields_string=http_build_query($fields);

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	        curl_setopt($ch, CURLOPT_POST, count($fields));
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

	        $result = curl_exec($ch);
	        curl_close($ch);

	        return json_decode($result, true);
	    }

	    public function common_form_data_label_switch($label, $filter) {
	    	global $_REQUEST;

			if (! isset($label) && ! is_string($label)) {

				return [
					'error' => 'No label or error with label'
				];

			}

			$results=[];

			$results = apply_filters('yatco_fill_options_and_lists', $results, $label );

			return $results;
	    }

		public function common_form_data(WP_REST_Request $request) {

			$label = $request->get_param('label');
			
			$labels = explode(',', $label);

			$justText = $request->get_param('justText');

			$justTextAsArray = $request->get_param('justTextAsArray');

			$return = [];

			foreach ($labels as $lk => $lb) {

				$data = $this->common_form_data_label_switch($lb, $request->get_param('filter'));

				if (isset($justText) && ($justText === "1" || $justText === 1)) {
					foreach($data as $index => $item) {
						unset($data[ $index ]['id']);
					}
				}

				if (isset($justTextAsArray) && ($justTextAsArray === "1" || $justTextAsArray === 1)) {
					$data = array_map(function($item) {return $item['text'];}, $data);
				}

				$return[ $lb ] = $data;
			}

			return $return;

		}

		public function yachts(WP_REST_Request $request) {
			$results = $this->data->get_search($request->get_params());
		
			return $results;
		}

		
		public function yacht_detail(WP_REST_Request $request) {
			$result = $this->data->get_vessel($request->get_param('vessel_id'));
		
			return $result;
			
		}

		public function charters(WP_REST_Request $request) {
			$results = $this->data->get_search_charter($request->get_params());
		
			return $results;
		}
		
		public function brokers(WP_REST_Request $request) {
			$results = $this->data->get_broker_finder($request->get_params());
				
			return $results;
		}

		public function brokerages(WP_REST_Request $request) {
			$results = $this->data->get_company_search($request->get_params());
				
			return $results;
		}

		public function services_mls_search(WP_REST_Request $request) {
			$results = $this->data->memberships( $request->get_params() );
				
			return $results;
		}

		public function reCaptchaOrHoneyPot($request, $success_action) {

			$form_data=$request->get_param('form_data');

			include 'Akismet.php';

			/*if (class_exists('yatcoConnect_Akismet')) {*/

			// https://akismet.com/development/api/#comment-check

			$comment = array(
			    'comment_type' => 'contact-form',
			    
			    'comment_author' => $form_data['FirstName'].' '.$form_data['LastName'],
			    'comment_author_email' => $form_data['Email'],

			    'comment_content' => $form_data['Message'],

			    'permalink' => $form_data['ReferrerUrl'],

			    'honeypot_field_name' => 'contact_me_by_fax_only',
			    
			    'hidden_honeypot_field' => $form_data['contact_me_by_fax_only'],

			    'contact_me_by_fax_only' => $form_data['contact_me_by_fax_only'],

			);

			// good comment until proven bad
			$akismet = new yatcoConnect_Akismet('406cfe013421');
			
			if(!$akismet->error) {

			    //Check to see if the key is valid
			    if($akismet->valid_key()) {

				    $results = ['spam_aki' => true, 'error' => 'Please refresh and try again. Spam Key Issue.'];
			    
			    }
			    				    
			    if ($akismet->is_spam($comment)) {

				    $results = ['spam_aki' => true, 'error' => 'Please refresh and try again.'];
			    
			    }
			    else {
				
					$results = ['not_spam_aki' => true];
			    
			    }
			}

			/*}
			elseif (isset($form_data['g-recaptcha-response'])) {

			    // Call the function post_captcha
			    $result_recaptcha = self::verify_captcha($form_data['g-recaptcha-response']);

			    if (! $result_recaptcha['success']) {

			        // What happens when the CAPTCHA wasn't checked
			        $results = ['error' => 'Please go back and make sure you check the security CAPTCHA box.'];

			    } else {
			        // If CAPTCHA is successfully completed...
			        $results = ['not_spam' => true];
			    }
			}
			else {
				$honeypot = FALSE;

				if (!empty($form_data['contact_me_by_fax_only'])) {
				    
				    $results = ['error' => 'Please refresh and try again.'];

				} 
				else {
					$results = ['not_spam' => true];
				}
			}*/

			return $results;

		}

		public function submit_any_lead(WP_REST_Request $request) {

			$label=$request->get_param('label');
	
			$results = $this->reCaptchaOrHoneyPot($request, null);

			if (! isset($results['error'])) {
				$form_data=$request->get_param('form_data');
	
				switch ( $label ) {
					case 'sendContactFormData':

						$results = $this->data->sendContactFormData($form_data);
						
						break;

					case 'SendVesselLead':

						$results = $this->data->SendVesselLead($form_data);
						
						break;

					case 'BrokerContact':
						
						$results = $this->data->SendBrokerContact($form_data);
						
						break;

					case 'ServicesMlsContact':
						
						$results = $this->data->SendServiceMLSContact($form_data);
						
						break;

					case 'SendCharterLead':
						$results = $this->data->SendCharterLead($form_data);
						
						break;

					case 'SendGeneralContact':
						
						$results = $this->data->SendGeneralContact($form_data);
						
						break;		

					case 'UpdateUserSubscriptions':

				        $user_email = $form_data['user_email_address'];
				    	$user_subscriptions = $this->data->get_user_subscriptions($user_email);
				    	
				    	// var_dump($form_data);

				        $subscription_body = [];

				        foreach ($user_subscriptions as $subscription) {
				        	$input = $form_data['type_'.$subscription->EmailTypeID];

				        	// var_dump($input);

				        	if (isset($input) && $input == "1") {
					        	$subscription_body[] = [
					        		// "ContactID" => $subscription->ContactID,
							        "EmailTypeID" => $subscription->EmailTypeID,
							        "Enabled" => ( isset($input) && $input == "1" )?true:false,
					        	];
				        	}

				        }

				       	$results = $this->data->update_user_subscriptions($user_email, $subscription_body);
						

						break;		    

					case "YatcoNewsletter":
		
				    	$results = $this->data->user_signup_newsletter($form_data);

						break;

					case "ClientNewsletter":
		
				    	$results = $this->data->user_client_signup_newsletter($form_data);
		
						break;

					case "ClientNewsletter_cfp":
	
						$results = $this->data->user_client_signup_newsletter_cfp($form_data);
		
						break;

					case "YatcoProducts_11_2022":
				    	$results = $this->data_leads->yatcoBossProduct_11_2022_Quote($form_data);
						break;

					default: 
						$results = [
							'error' => 'Label Action Not Found',
						];

						break;
					
				}
			}

			return $results;

		}

		public function submit_crm_contact( WP_REST_Request $request ) {

			$call = $this->data->post_crm_contact( $request->get_params() );

			return $call;
			
		}

		public function submit_crm_contact_and_form( WP_REST_Request $request ) {

			$call = $this->data->post_crm_contact_and_form( $request->get_params() );

			return $call;
			
		}


		
	}