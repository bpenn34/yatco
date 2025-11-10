<?php 
	class yatcoConnect_ApiToBoss {

		public function __construct() {

			$this->yacht_params = new yatcoConnect_ApiToBossYachtParams();
			$this->charter_params = new yatcoConnect_ApiToBossCharterParams();
			$this->broker_params = new yatcoConnect_ApiToBossBrokerParams();
			$this->brokerage_params = new yatcoConnect_ApiToBossCompanyParams();

			$this->leads = new yatcoConnect_ApiToBossLeads();

			$this->options = new yatcoConnect_Options();
			
		}

		public function get_option( $option ) {
			return get_option( 'yatco_connect_' . $option );
		}

		public function call_api( $verb, $path, $data = array() ) {
			// Build endpoint
			$option_url = $this->get_option('api_url');

			$curl = curl_init();
			
			$endpoint = $option_url . $path; 

			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Basic ' . $this->get_option('api_token'),
				'Accept: application/json',
				'Content-Type: application/json'
	    	));

	    	switch ($verb) {
				case 'POST':
					curl_setopt($curl, CURLOPT_POST, 1);

					if (is_array($data) || is_object($data)) {
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
					}

					break;

				case 'PUT':
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
					
					if (is_array($data) || is_object($data)) {
						curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
					}

					break;

				default:
					if ((is_array($data) && count($data) > 0) || is_object($data)) {
						$endpoint = sprintf("%s?%s", $endpoint, http_build_query($data));
					}
					break;

			}

			// Optional Authentication:
			//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			//curl_setopt($curl, CURLOPT_USERPWD, "username:password");

			/*set_time_limit(5);
			ini_set('max_execution_time', 5);
			
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2); 
			curl_setopt($curl, CURLOPT_TIMEOUT, 5);*/

			curl_setopt($curl, CURLOPT_URL, $endpoint);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($curl);

			$info = curl_getinfo($curl);

			//var_dump($info);

			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			//var_dump($http_code);

			curl_close($curl);

			if ($http_code == 401) {
				return ['error' => 401, 'message' => 'NOT AUTH_ED'];
			}
			elseif ($http_code == 200) {

				$json_result = json_decode($result);

				return $json_result;

			}
			else{
				return (object) ['error' => $http_code, 'message' => 'Error'];
			}
		}

		public function ping() {

			$list_ping = $this->build_simpler_list($this->call_api( 'GET', '/Common/Lists/VesselCategory'));
			
			if (count($list_ping) > 2) {
				return true;
			}

			return false;

		}

		public function get_company($company_id) {

			if ($company_id > 0) {
				$_company = $this->call_api('GET', '/Common/Company/' . $company_id);

				$company = apply_filters('wp_process_company', $_company);

				return $company;
			}
		}

		public function get_company_builder($company_id) {

			if ($company_id > 0) {
				$_company_builder = $this->call_api('GET', '/Common/Company/'. $company_id .'/companybuilder');

				//$company = apply_filters('wp_process_company', $_company);

				return $_company_builder;
			}
		}

		public function get_company_search($args) {

			$params = $this->brokerage_params->prepare_search_parameters($args);			

			$companies = $this->call_api('POST', '/Common/Company/Search', $params);

			$companies->Params = $params;

			foreach ($companies->Results as $company) {
				$company = apply_filters('wp_process_company', $company);
			}

			return $companies;

		}

		public function get_broker($broker_id) {
			if ($broker_id > 0) {

				$broker = apply_filters("ptrc_get_details", [], 'BrokerDetails');

				if ( ! isset($broker->from_cache) ) {
					$broker = $this->call_api('GET', '/Common/AccountConsumer/'.$broker_id);

					$broker = apply_filters('wp_process_broker', $broker);

				}
			}

			return $broker;

		}

		public function get_broker_stats($broker_id) {

			$stats = $this->call_api('GET', '/ForSale/BrokerStatsConsumer/'. $broker_id .'/Currency/USD');

			return $stats;

		}

		public function get_broker_finder($args) {

			$params = $this->broker_params->prepare_search_parameters($args);			
			
			$brokers = (object) $this->call_api('POST', '/Common/Account/Brokers/AllActive', $params);
	
			//$brokers = $this->call_api('POST', '/ForSale/Broker/GetAllBrokers', $params);

			if (is_object($brokers)) {
				$brokers->Params=$params;


				if (! isset($brokers->Records)) {

					$brokers->Records = count($brokers->Results);

				}

				foreach ($brokers->Results as $key => $value) {
					$brokers->Results[ $key ] = apply_filters('wp_process_broker', $value);
				}
			}

			return $brokers;

		}

		public function get_office($office_id) {

			if ($office_id > 0) {

				$office = $this->call_api('GET', '/Common/Office/'.$office_id);

				$office = apply_filters('wp_process_office', $office);

			}

			return $office;

		}

		public function get_company_offices($company_id) {

			if ($company_id > 0) {
				$offices = $this->call_api('GET', '/Common/Company/'. $company_id .'/ActiveOffices');

			}

			return $offices;
		}

		public function get_company_brokers($company_id) {
			if ($company_id > 0) {

				$brokers = $this->call_api('GET', '/Common/Account/Brokers/'.$company_id);

				foreach ($brokers as $key => $value) {
					$brokers[ $key ] = apply_filters('wp_process_broker', $brokers[ $key ]);
				}

			}

			return $brokers;

		}


		public function get_company_brokers_fast_call($company) {
		
			$brokers = $this->call_api('GET', '/Common/Account/Brokers/'.$company->CompanyID);
			
			foreach ($brokers as $key => $value) {
				$brokers[ $key ]->Company = $company;

				$offices = array_filter($company->Offices, function($o) use ($value) {

					return $o->OfficeID == $value->OfficeID;
				});
				
				$offices = array_values($offices);

				$brokers[ $key ]->Office = $offices[0];

				$brokers[ $key ] = apply_filters('wp_process_broker', $brokers[ $key ]);
			
			}

			return $brokers;
		}


		public function get_vessel( $vessel_id ) {
			$vessel_id = intval($vessel_id);

			if ($vessel_id > 0) {

				$vessel = apply_filters("ptrc_get_details", $vessel_id, 'YachtDetails') ?? [];

				if ( ! isset($vessel->from_cache) ) {
					$vessel = $this->get_vessel_from_mlsid($vessel_id);

					if (isset($vessel) && isset($vessel->BasicInfo)) {
						$vessel->RandomCheck = rand(1, 100);
						
						if (isset($vessel->Result)) {						
							if ($vessel->Result->MLSID != $vessel_id) {
								$vessel = $this->get_vessel_from_vesselid($vessel_id);
							}
						}
					}				
					else {
						return -1;
					}
				}
			}
			else {
				return -1;
			}

			return $vessel;
		}

		public function get_vessel_from_mlsid($mlsid) {

			$vessel = $this->call_api( 'GET', "/ForSale/Vessel/$mlsid/Details/FullSpecsAllByMLSID");

			if (isset($vessel->BasicInfo)) {
				$vessel = apply_filters('wp_process_yacht_details', $vessel);
			}

			return $vessel;

		}

		public function get_vessel_from_vesselid($vesselid) {
			$vessel = $this->call_api( 'GET', "/ForSale/Vessel/$vesselid/Details/fullSpecsAll");

			if (isset($vessel->BasicInfo)) {
				$vessel = apply_filters('wp_process_yacht_details', $vessel);
			}

			return $vessel;
		}

		public function get_search_pretty_parameters_to_ids($atts) {

			$params=(array)$this->call_api( 'POST', '/ForSale/SearchParams', $atts);

			return $params;

		}

		public function get_search( $args ) {

			$params=$this->yacht_params->prepare_search_parameters($args);

			$vessels = (object) $this->call_api('POST', '/ForSale/Vessel/Search', $params);

			$vessels->Params=$params;
			
			$vessels=apply_filters('wp_process_yachts', $vessels);
			
			return $vessels;
		}

		public function get_builders($filter) {
			$params = array('filter' => $filter);

			$cache_list = get_transient('yatco_cache_list_of_builders_'.$filter);

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api( 'GET', '/Common/Builders/SearchList', $params), 
					'BuilderID', 
					'BuilderName'
				);

				set_transient( 'yatco_cache_list_of_builders_'.$filter, $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_popular_builders() {
			$params = array();

			$cache_list = get_transient('yatco_cache_list_of_popular_builders');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api( 'GET', '/Common/Lists/Counts/Builder/40', $params),
					'ID',
					'Text'
				);
				
				set_transient( 'yatco_cache_list_of_popular_builders', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_active_builders() {
			$params = array();

			$cache_list = get_transient('yatco_cache_list_of_active_builders');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api( 'GET', '/Common/BuildersWithActiveVessels', $params),
					'ListItemID',
					'ListItemDescription'
				);
				
				set_transient( 'yatco_cache_list_of_active_builders', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_list_of_vessel_names() {
			$params = array();

			$cache_list = get_transient('yatco_cache_list_of_vessel_names');

			if (! $cache_list) {

				$cache_list = $this->call_api( 'POST', '/ForSale/Vessel/ActiveVesselName', $params);
	
				$cache_list = array_unique($cache_list);

				foreach ($cache_list as $index => $name) {
					if (
						$name === '0' || $name === '-' 
						|| 
						$name === '--' || $name === '- ' 
						|| 
						$name === ' '
	 				) {
						unset($cache_list[$index]);	
					}
				}

				sort($cache_list);
				
				set_transient( 'yatco_cache_list_of_vessel_names', $cache_list, 4 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_countries() {

			$cache_countries = get_transient('yatco_cache_list_countries');

			if (! $cache_countries) {

				$cache_countries = $this->build_simpler_list($this->call_api( 'GET', '/Common/VesselRegion/Country'));

				set_transient( 'yatco_cache_list_countries', $cache_countries, 48 * HOUR_IN_SECONDS );

			}			

			return $cache_countries;
		}

		public function get_country_states( $id ) {
			$cache_list = get_transient('yatco_cache_list_of_states_id_'.$id);

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list($this->call_api( 'GET', '/Common/VesselRegion/Country/' . $id));

				set_transient( 'yatco_cache_list_of_states_id_'.$id, $cache_list, 48 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_list( $listname ) {
			//TODO: There is some use of a filter param in the UI - do we need that?
			
			$cache_list = get_transient('yatco_cache_list_'.$listname);

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list($this->call_api( 'GET', '/Common/Lists/' . $listname));

				set_transient( 'yatco_cache_list_'.$listname, $cache_list, 48 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function summary_stats() {

			// array(4) { ["NewListings"]=> int(686) ["TotalActivePrice"]=> float(29690131949.5) ["SoldListings"]=> int(99) ["TotalSales"]=> float(2481829281.86) } 

			$cache_stat = get_transient('yatco-stat-summary');

			if (! $cache_stat) {

				$cache_stat = $this->call_api('GET', '/ForSale/Vessel/SummaryStats');

				set_transient( 'yatco-stat-summary', $cache_stat, 4 * HOUR_IN_SECONDS );
				
			}

			return $cache_stat;
		}

		public function get_location_stats($location) {

			$cache_stat = get_transient('yatco-stat-summary-location-'.$location);

			if (! $cache_stat) {

				$cache_stat = $this->call_api('GET', '/ForSale/CityStats/'.$location);

				$cache_stat = (array) $cache_stat;

				if (isset($cache_stat['VesselCount']) && $cache_stat['VesselCount'] > 0) {
					set_transient( 'yatco-stat-summary-location-'.$location, $cache_stat, 4 * HOUR_IN_SECONDS );
				
				}
			}

			return $cache_stat;

		}
		
		public function post_location_stats($result_args) {

			$result_args = (array) $result_args;

			$location = '';

			if (isset($result_args['City'])) {

				$location .= $result_args['City'];

			}
			elseif (isset($result_args['Cities'])) {

				$location .= ''.join('-', $result_args['Cities']);

			} 
			elseif (isset($result_args['LocationCityID'])) {
				$location .= $result_args['LocationCityID'];
			}
			
			if (isset($result_args['State'])) {

				$location .= $result_args['State'];

			} elseif (isset($result_args['StateID'])) {

				$location .= 'StateID-'. $result_args['StateID'];

			} elseif (isset($result_args['LocationStateID'])) {

				$location .= 'StateID-'. $result_args['LocationStateID'];

			} 

			if (isset($result_args['Country'])) {

				$location .= $result_args['Country'];

			} elseif (isset($result_args['CountryID'])) {

				$location .= 'CountryID-'. $result_args['CountryID'];

			} elseif (isset($result_args['LocationCountryID'])) {

				$location .= 'CountryID-'. $result_args['LocationCountryID'];

			}
			

			if (isset($result_args['RegionID'])) {

				$location .= 'RegionID-'. $result_args['RegionID'];

			} elseif (isset($result_args['LocationRegionID'])) {

				$location .= 'RegionID-'. $result_args['LocationRegionID'];

			}

			$cache_stat = get_transient('yatco-stat-summary-location-'.$location);

			if (! $cache_stat) {

				$cache_stat = $this->call_api('POST', '/ForSale/LocationStats/', $result_args);

				$cache_stat = (array) $cache_stat;

				if (isset($cache_stat['VesselCount']) && $cache_stat['VesselCount'] > 0) {
					set_transient( 'yatco-stat-summary-location-'.$location, $cache_stat, 4 * HOUR_IN_SECONDS );
				}

				
			}

			return $cache_stat;

		}

		public function association_regions($id) {
			return $this->build_simpler_list(
				$this->call_api('GET', '/Common/AssociationRegionFilter/'.$id), 
				'RegionID', 
				'RegionName'
			);
		}

		public function build_simpler_list( $list, $id_field='ListItemValue', $desc_field='ListItemDescription' ) {
			$simple_list = array();

			foreach ($list as $item) {
				$new_item=array (
					// 'SeoLink' => $this->seo_path_encode($item->$desc_field),
					'id' => $item->$id_field,
					//'text' => preg_replace('/[^A-Za-z0-9\-\ ]/', '', $item->$desc_field)
					'text' => ($item->$desc_field)
			  	);

			  	if (isset($item->Count)) {

			  		$new_item['count'] = $item->Count;

			  	}

				$simple_list[] = $new_item;
			}

			return $simple_list;
		}

		public function sendContactFormData($params) {
			return $this->leads->sendContactFormData($params);
		}
		
		public function SendVesselLead($params) {
			return $this->leads->sendVesselLead($params);
		}

		public function SendCharterLead($params) {
			return $this->leads->sendCharterLead($params);
		}

		public function SendGeneralContact($params) {
			return $this->leads->sendGeneralContact($params);	
		}

		public function SendBrokerContact($params) {
			return $this->leads->sendBrokerContact($params);
		}

		public function SendServiceMLSContact($params) {
			return $this->leads->sendServiceContact($params);
		}

		public function user_signup_newsletter($params) {
			return $this->leads->userSignupNewsletter($params);
		}

		public function user_client_signup_newsletter($params) {
			return $this->leads->userClientSignupNewsletter($params);
		}

		public function user_client_signup_newsletter_cfp($params) {
			return $this->leads->userClientSignupNewsletter_cfp($params);
		}

		public function get_user_subscriptions($email) {

			return $this->call_api( 'GET', '/CRM/Contact/Subscriptions?emailID='.$email);

		}
		
		public function update_user_subscriptions($email, $data = []) {

			return $this->call_api( 'PUT', '/CRM/Contact/Subscriptions?emailID='.$email, $data);

		}

		public function get_search_charter($args) {

			$params=$this->charter_params->prepare_search_parameters($args);
			
			$charters = (object) $this->call_api('POST', '/Charter/Search', $params);

			$charters->Params = $params;

			$count = 0;

			if(isset($charters->Results)){
				$count = count($charters->Results);
			}

			if (! isset($charters->Records)) {

				$charters->Records = $count;

			}

			$charters = apply_filters('wp_process_charters', $charters);

			return $charters;
		
		}

		public function get_charter_details($charter_id) {

			$charter = [];

			$charter = apply_filters("ptrc_get_details", $charter, 'CharterDetails');

			if ( ! isset($charter->from_cache) ) {

				$charter = $this->call_api('GET', '/Charter/Vessel/'. $charter_id .'/Details/View');

				$charter = apply_filters('wp_process_charter_details', $charter);

				if(is_object($charter)){
					$charter->from_boss = true;
				}
 
			}

			return $charter;

		}

		public function get_charter_details_tenders($charter_id) {
			$charter_toys = $this->call_api('GET', '/Charter/Vessel/'. $charter_id .'/ToyTenderList/1');

			return $charter_toys;

		}

		public function get_charter_details_toyslist($charter_id) {
			$charter_toys = $this->call_api('GET', '/Charter/Vessel/'. $charter_id .'/ToysListView');

			return $charter_toys;

		}

		public function get_charter_details_amenities($charter_id) {
			$charter_amenities = $this->call_api('GET', '/Charter/Vessel/'. $charter_id .'/Amenities');

			return $charter_amenities;

		}

		public function get_charter_destination() {

			$cache_list = get_transient('yatco_cache_list_of_charter_destination');

			if (! $cache_list) {
				$cache_list = $this->call_api('GET', '/Charter/List/Destination');

				set_transient( 'yatco_cache_list_of_charter_destination', $cache_list, 4 * HOUR_IN_SECONDS );
			}

			return $cache_list;

		}	

		public function get_charter_all_destination() {
			$cache_list = get_transient('yatco_cache_list_of_charter_destinationALL');

			if (! $cache_list) {
				$cache_list = $this->call_api('GET', '/Charter/List/DestinationAll');

				set_transient( 'yatco_cache_list_of_charter_destinationALL', $cache_list, 4 * HOUR_IN_SECONDS );
			}

			return $cache_list;

		}

		public function get_charter_seasons() {

			$cache_list = get_transient('yatco_cache_list_of_charter_seasons');

			 if (! $cache_list) {
				$_sons = $this->call_api('GET', '/Charter/List/Season/Actual');
				$sons = [];

				foreach ($_sons as $son) {
					$sons[] = [
						'text' => $son->SeasonName,
						'id' => $son->SeasonID,
					];
				}

				$cache_list = $sons;
				
				set_transient( 'yatco_cache_list_of_charter_seasons', $cache_list, 4 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}

		public function get_charter_amenities() {
			$cache_list = get_transient('yatco_cache_list_of_charter_amenities');

			if (! $cache_list) {
				$amenities = $this->call_api('GET', '/Charter/Amenities');

				$cache_list = $amenities;
				
				set_transient( 'yatco_cache_list_of_charter_amenities', $cache_list, 6 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_charter_amenities_special_access() {
			$cache_list = get_transient('yatco_cache_list_of_charter_amenities_special_access');

			if (! $cache_list) {
				$amenities = $this->get_charter_amenities();
				$amenities = $amenities->Groups;

				$special_ones = array_values(array_filter($amenities, function($an) {

					return $an->AmenityGroupID == 7 || $an->AmenityGroupName  == 'SPECIAL ACCESS';

				}));

				$results = [];

				foreach ($special_ones[0]->Items as $key => $value) {
					$results[] = [
						'text' => $value->AmenityName,
						'id' => $value->AmenityID,
					];
				}		

				$cache_list = $results;
				
				set_transient( 'yatco_cache_list_of_charter_amenities_special_access', $cache_list, 4 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function get_charter_amenities_special_air() {
			$cache_list = get_transient('yatco_cache_list_of_charter_amenities_air');

			if (! $cache_list) {
				$amenities = $this->get_charter_amenities();
				$amenities = $amenities->Groups;

				$special_ones = array_values(array_filter($amenities, function($an) {

					return $an->AmenityGroupID == 6;

				}));

				$results = [];

				foreach ($special_ones[0]->Items as $key => $value) {
					$results[] = [
						'text' => $value->AmenityName,
						'id' => $value->AmenityID,
					];
				}					

				$cache_list = $results;
				
				set_transient( 'yatco_cache_list_of_charter_amenities_air', $cache_list, 4 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}
		
		public function get_charter_freq_types() {

			$cache_list = get_transient('yatco_cache_list_of_charter_freq_types');

			if (! $cache_list) {
				$_freq_types = $this->call_api('GET', '/Charter/List/CharterRateFrequencyList');
				$freq_types = [];

				foreach ($_freq_types as $key => $value) {
					$freq_types[]=[
						'text' => $key,
						'id' => $value,
					];
				}

				$cache_list = $freq_types;
				
				set_transient( 'yatco_cache_list_of_charter_freq_types', $cache_list, 4 * HOUR_IN_SECONDS );
			}

			return $cache_list;
		}

		public function get_charter_dropdown_destinations() {
			//$cache_list = get_transient('yatco_cache_list_of_charter_dropdown_destination');

			//if (! $cache_list) {
				$parents = $this->get_charter_destination();

				$children = $this->get_charter_all_destination();

				$results = [];

				foreach ($parents as $pd) {
					$results[] = [
						'text' => $pd->Name,
						//'id' => $pd->Name,
						'id' => $pd->ID,
					];

					foreach ($children as $cd) {
						if ($cd->ParentID == $pd->ID) {

							$results[] = [
								'text' => '-- '.$cd->Name,
								//'id' => $cd->Name,
								'id' => $cd->ID,
							];
						}
					}
				}

				$cache_list = $results;
				
				//set_transient( 'yatco_cache_list_of_charter_dropdown_destination', $cache_list, 4 * HOUR_IN_SECONDS );
		//	}

			return $cache_list;
		}

		public function post_crm_contact($contact) {

			$data = [
				'AutoMerge' => 1,
				'CompanyID' => 1,
				'CRMCompanyID' => 1,
			];

			$data = array_merge($data, $contact);

			return $this->call_api('POST', '/CRM/Contact', $data);

		}

		public function post_crm_contact_and_form($form_fields) {

			/*$form_id = $form_data[ 'form_id' ];

		    $__form_fields =  $form_data[ 'fields' ];

		    $form_fields = [];

		    foreach( $__form_fields as $field ){
		        $field_id    = $field[ 'id' ];
		        $field_key   = $field[ 'key' ];
		        $field_value = $field[ 'value' ];
		        
		        $form_fields[ $field_key ] = $field_value;
		    }

		    $form_settings = $form_data[ 'settings' ];
		    $form_title    = $form_data[ 'settings' ][ 'title' ];*/

		    /*
				Form Fields:
					- CompanyID4
					- FirstName
					- LastName
					- Email
					- Phone
					- Message
		
		    */

		    if ($this->options->getOption('override_leads_company_id') != '') {
				$form_fields['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Contact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></Contact>');
			
			$xml->addChild('CompanyID', $form_fields['CompanyID']);
			
			if (isset($form_fields['Subject'])) {
				$xml->addChild('Subject', $form_fields['Subject']);
			}
			else {
				$xml->addChild('Subject', 'General Contact Form Submitted');

			}

			$xml->addChild('FromEmail', $form_fields['Email']);
			$xml->addChild('ClientDomain', $_SERVER['HTTP_HOST']);
			
			$xml->addChild('FirstName', $form_fields['FirstName']);
			$xml->addChild('LastName', $form_fields['LastName']);
			// $xml->addChild('CCRecipientEmails', 'mail@joshuahoffman.me');
			$xml->addChild('Email', $form_fields['Email']);
			$xml->addChild('Mobile', $form_fields['Phone']);
			$xml->addChild('Message', $form_fields['Message']);
			$xml->addChild('InquiryType', 1);
			$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');

			$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

			if (isset($form_fields['FormTypeID'])) {
				
			}
			else {
				$form_fields['FormTypeID'] = 1;
			}

			$data = [
				'Form' => [
					'FormTypeID' => $form_fields['FormTypeID'],
					'CompanyID' => $form_fields['CompanyID'],
					'PageURL' => $form_fields['PageURL'],
					//'isBrokerageSite' => 0,

					'FormData' => $xml->asXML()
				],

				'Contact' => [
					"FirstName" => $form_fields['FirstName'],
					"LastName" => $form_fields['FirstName'],
					"Mobile" => $form_fields['Phone'],
					"Email" => $form_fields['Email'],
					'CompanyID' => $params['CompanyID'],
				],
			];

			$the_call = $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function memberships($params) {

			$members = $this->call_api('POST', '/CRM/Company/Search', $params);

			$members->Params = $params;

			$members = apply_filters('wp_process_services', $members);

			return $members;
		}

		public function member_details($memberID) {
			
			$member_detail = $this->call_api('GET', '/CRM/USSACRMCompany/'.$memberID);

			$member_detail = apply_filters('wp_process_service_details', $member_detail);

			return $member_detail;
			
		}

		public function membership_country_list() {
			
			$list = $this->call_api('GET', '/common/Lists/CountryState');

			return $list;

		}
		
		public function membership_region_list() {
					
			$list = $this->call_api('GET', '/USSA/CompanyRegion/Company');

			return $list;

		}


		public function membership_states_list($countryId) {
			
			$list = $this->call_api('GET', '/Common/ListsIncludeAllPID/CountryState/'.$countryId);

			return $list;

		}

		public function membership_reps_list() {
			$list = $this->call_api('GET', '/CRM/USSACRMCompanyRepsList/');

			return $list;
		}

		public function membership_companies_list() {
			$list = $this->call_api('GET', '/CRM/USSACRMCompanyList/1045');

			return $list;
		}

		public function membership_category_list() {
			
			$list = $this->call_api('GET', '/CRM/CompanyCategory/Company/1045');

			return $list;

		}

		public function common_builder_models($params) {
			$models = $this->call_api('POST', '/Common/BuilderModels', $params);

			$models = apply_filters('wp_process_builder_models', $models);

			return $models;
		}

		public function builder_model_details($id) {
			$params = [
				
				"BuilderModelID" => $id,

			];

			$model = $this->call_api('POST', '/Common/BuilderModels', $params);

			$model = $model[0];

			$model = apply_filters('wp_process_builder_model_details', $model);

			return $model;
		}


		public function common_last_modified_dates() {
			$dates = $this->call_api('GET', '/Common/Company/LastModifiedDates');

			return $dates;
		}

		public function geo_regions() {

			$regions = $this->call_api('GET', '/Common/Location/Region');

			return $regions;

		}

		public function geo_list_regions() {
			$cache_list = get_transient('yatco_cache_list_of_geo_regions');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_regions(),
					'SubRegionID',
					'SubRegion'
				);
				
				set_transient( 'yatco_cache_list_of_geo_regions', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function geo_list_regions_with_countries_only() {
			$cache_list = get_transient('yatco_cache_list_of_geo_regions_with_only_active_countries');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('GET', '/Common/Location/Region/?RemoveRegionsWithNoCountries=true'),
					'SubRegionID',
					'SubRegion'
				);
				
				set_transient( 'yatco_cache_list_of_geo_regions_with_only_active_countries', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;
		}

		public function geo_region_countries( $region_id ) {

			$countries = $this->call_api('GET', "/Common/Location/Region/$region_id/Country");

			return $countries;

		}

		public function geo_list_region_countries( $region_id ) {
			$cache_list = false;

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('GET', "/Common/Location/Region/$region_id/Country"),
					'CountryID',
					'Country'
				);

			}

			return $cache_list;

			/*$countries = $this->call_api('GET', "/Common/Location/Region/$region_id/Country");
			return $countries;*/

		}

		public function geo_list_region_countries_only_active_vessels( $region_id ) {
			$cache_list = false;

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('GET', "/Common/Location/Region/$region_id/Country/?RemoveEntriesWithNoVessels=true"),
					'CountryID',
					'Country'
				);
			}

			return $cache_list;

			/*$countries = $this->call_api('GET', "/Common/Location/Region/$region_id/Country");
			return $countries;*/

		}

		

		public function geo_all_countries( ) {

			$countries = $this->call_api('GET', "/Common/Location/Country");

			return $countries;

		}

		public function geo_list_all_countries() {

			//$cache_list = get_transient('yatco_cache_list_of_geo_countries');
			$cache_list = false;

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_all_countries(),
					'CountryID',
					'Country'
				);
				
				set_transient( 'yatco_cache_list_of_geo_countries', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;


		}

		public function geo_states( $country_id ) {

			$states = $this->call_api('GET', "/Common/Location/Country/$country_id/State");

			return $states;

		}

		public function geo_list_states( $country_id ) {

			$cache_list = get_transient('yatco_cache_list_of_geo_country_'.$country_id.'_states');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_states($country_id),
					'StateID',
					'State'
				);
				
				set_transient( 'yatco_cache_list_of_geo_country_'.$country_id.'_states', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}

		public function geo_list_states_only_active_vessels( $country_id ) {
			//$cache_list = get_transient('yatco_cache_active_list_of_geo_country_'.$country_id.'_states');

			//if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('GET', "/Common/Location/Country/$country_id/State/"),
					'StateID',
					'State'
				);
				
			//	set_transient( 'yatco_cache_active_list_of_geo_country_'.$country_id.'_states', $cache_list, 2 * HOUR_IN_SECONDS );

			//}

			return $cache_list;

		}

		public function geo_all_states( ) {

			$states = $this->call_api('GET', "/Common/Location/StatesByCountries");

			return $states;

		}

		public function geo_list_all_states( ) {

			//$cache_list = get_transient('yatco_cache_list_of_geo_all_states');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_states($country_id),
					'StateID',
					'State'
				);
				
				set_transient( 'yatco_cache_list_of_geo_all_states', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}


		public function geo_cities( $state_id ) {

			$cities = $this->call_api('GET', "/Common/Location/State/$state_id/City");

			return $cities;

		}
	
		public function geo_list_cities( $state_id ) {


			$cache_list = get_transient('yatco_cache_list_of_geo_state_'. $state_id .'_cities');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_cities($state_id),
					'CityID',
					'CityName'
				);
				
				set_transient( 'yatco_cache_list_of_geo_state_'. $state_id .'_cities', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}

		public function geo_list_cities_only_active_vessels( $state_id ) {

			$cache_list = get_transient('yatco_cache_active_list_of_geo_state_'. $state_id .'_cities');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('GET', "/Common/Location/State/$state_id/City/?RemoveEntriesWithNoVessels=true"),
					'CityID',
					'CityName'
				);
				
				set_transient( 'yatco_cache_active_list_of_geo_state_'. $state_id .'_cities', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;


		}

		public function geo_country_cities($country_id) {

			$cities = $this->call_api('POST', '/Common/Location/Country/'. $country_id .'/Cities');

			return $cities;

		}

		public function geo_list_country_cities( $country_id ) {


			$cache_list = get_transient('yatco_cache_list_of_geo_state_'. $country_id .'_cities');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->geo_country_cities($country_id),
					'CityID',
					'CityName'
				);

				if (count($cache_list) > 15000) {

					$cache_list = [];

				}

				set_transient( 'yatco_cache_list_of_geo_cities_'. $country_id .'_cities', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}


		public function geo_list_country_cities_only_active_vessels( $country_id ) {


			$cache_list = get_transient('yatco_cache_active_list_of_geo_state_'. $country_id .'_cities');

			if (! $cache_list) {

				$cache_list = $this->build_simpler_list(
					$this->call_api('POST', '/Common/Location/Country/'. $country_id .'/Cities/?RemoveEntriesWithNoVessels=true'),
					'CityID',
					'CityName'
				);

				if (count($cache_list) > 15000) {

					$cache_list = [];

				}

				set_transient( 'yatco_cache_active_list_of_geo_cities_'. $country_id .'_cities', $cache_list, 2 * HOUR_IN_SECONDS );

			}

			return $cache_list;

		}

	}