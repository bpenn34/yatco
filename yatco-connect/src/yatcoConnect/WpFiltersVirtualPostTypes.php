<?php 
	class yatcoConnect_WpFiltersVirtualPostTypes {

		public function __construct() {

			$this->apiToBoss = new yatcoConnect_ApiToBoss();
			$this->virtual_post_type_url = new yatcoConnect_VirtualPostTypeURL();

		}

		public function add_actions_and_filters() {
			add_filter('wp_process_post_yacht', [$this, 'wp_filter_process_yacht_details'], 15, 1);
			add_filter('wp_process_post_charter', [$this, 'wp_filter_process_charter_details'], 15, 1);
			add_filter('wp_process_post_company', [$this, 'wp_filter_process_company'], 15, 1);
			add_filter('wp_process_post_company_builder', [$this, 'wp_filter_process_company_builder'], 15, 1);
			add_filter('wp_process_post_broker', [$this, 'wp_filter_process_broker'], 15, 1);
			add_filter('wp_process_post_service', [$this, 'wp_filter_process_service'], 15, 1);

			//remove these filters are this creates warning Attempt to read property "post_type" on null
			add_action('wp', function() {
				if ( is_singular() ) {
					remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
					remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
				}
			});

		}

		public function wp_filter_process_yacht_details($yacht) {
			
			foreach ($yacht->Brokers as $ib => $broker) {
				$broker->NoStats=true;

				$yacht->Brokers[$ib] = apply_filters('wp_process_broker', $broker);
			}

			return $yacht;
		}

		public function wp_filter_process_charter_details($charter) {
			if(isset($charter->BrokerList)){
				foreach ($charter->BrokerList as $broker) {
					$broker = apply_filters('wp_process_broker', $broker);
				}
			}

			return $charter;
		}

		
		public function wp_filter_process_company($company) {

			$company->a_url = $this->virtual_post_type_url->wp_company_url($company);

			$company->Offices=$this->apiToBoss->get_company_offices($company->CompanyID);

			$company->Brokers = $this->apiToBoss->get_company_brokers_fast_call($company);

			$company->Yachts = $this->apiToBoss->get_search([
				'CompanyID' => $company->CompanyID,
				'page_size' => 2,
				'records' => 2,
				'sortId' => 3,
			]);

			return $company;

		}

		public function wp_filter_process_company_builder($company) {

			$company->a_url = $this->virtual_post_type_url->wp_company_builder_url($company);

			$company->Offices=$this->apiToBoss->get_company_offices($company->CompanyID);

			$company->Brokers = $this->apiToBoss->get_company_brokers_fast_call($company);

			$company->Yachts = $this->apiToBoss->get_search([
				'CompanyID' => $company->CompanyID,
				'page_size' => 2,
				'records' => 2,
				'sortId' => 3,
			]);

			$company->BUILDER_DATA = $this->apiToBoss->get_company_builder($company->CompanyID);

			return $company;

		}

		public function wp_filter_process_broker($broker) {

			// $broker->Company = $this->apiToBoss->get_company($broker->CompanyID);

			$broker->Yachts = $this->apiToBoss->get_search([
				'BrokerID' => $broker->AccountID,
				'page_size' => 2, 
				'records' => 2,
				'sortId' => 3,
			]);

			$broker->a_url = $this->virtual_post_type_url->wp_broker_url($broker, $broker->Company);

			return $broker;

		}

		public function wp_filter_process_office($office) {

			return $office;

		}

		public function wp_filter_process_service($service) {

			$service->a_url = $this->virtual_post_type_url->wp_services_mls_url($service);

			return $service;

		}

	}