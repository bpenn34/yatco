<?php 
	class yatcoConnect_VirtualPostTypeURL {
		public function seo_path_encode($val) { 
			return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($val)),'-'); 
		}

		public function wp_company_url($company) {

			$url = get_site_url().'/company/'.$company->CompanyID.'/';

			return apply_filters('wp_yatco_company_url', $url, $company);
			
		}

		public function wp_company_builder_url($company) {

			$url = get_site_url().'/company/'.$company->CompanyID.'/';

			return apply_filters('wp_yatco_company_builder_url', $url, $company);
			
		}

		public function wp_broker_url($broker, $company) {
			$url = '';

			if (isset($broker->FirstName)) {
				$url .= $broker->FirstName.'-'.$broker->LastName;
			}
			else {
				$url .= $broker->BrokerName;
			}

			if (isset($broker->CompanyName)) {
				$url .= '-'.$broker->CompanyName;
			}
			elseif (isset($broker->Company->CompanyName)) {
				$url .= '-'.$broker->Company->CompanyName;				
			}
			elseif (isset($company->CompanyName)) {
				$url .= '-'.$company->CompanyName;				
			}
			
			if (isset($broker->Office) && isset($broker->Office->Country)) {
				if (isset($broker->Office->City)) {
					$url .= '-'.$broker->Office->City;
				}

				if (isset($broker->Office->State)) {
					$url .= '-'.$broker->Office->State;
				}

				if (isset($broker->Office->Country)) {
					$url .= '-'.$broker->Office->Country;
				}
			}
			elseif (isset($company) && isset($company->Country)) {
				if (isset($company->City)) {
					$url .= '-'.$company->City;
				}

				if (isset($company->State)) {
					$url .= '-'.$company->State;
				}

				if (isset($company->Country)) {
					$url .= '-'.$company->Country;
				}
			}

			if (isset($broker->BrokerID)) {
				$url .= '-'.$broker->BrokerID;
			}
			elseif (isset($broker->AccountID)) {
				$url .= '-'.$broker->AccountID;
			}
			
			$url = get_site_url().'/broker/'. $this->seo_path_encode($url).'/';

			//$url = $this->seo_path_encode($url);

			return apply_filters('wp_yatco_broker_url', $url, $broker, $company);

		}

		public static function get_vessel_target_path( $data ) {

			if (! isset($data->State)) {
				$data->State='';
			}

			if (! isset($data->Model)) {
				$data->Model='';
			}

			if (! isset($data->ModelYear)) {
				$data->ModelYear='';
			}

			return sprintf('%s-%s-%s-yacht-%s-%s',
				$data->LOAFeet,
				$data->BuilderName,
				$data->Model,
				$data->ModelYear,
				$data->MLSID
			);
		}

		public function generate_url_from_options($vessel, $pattern) {
		
			// Replace all placeholders like {VesselName}, {MLSID}, etc.
			$url_path = preg_replace_callback('/{(\w+)}/', function ($matches) use ($vessel) {
				$key = $matches[1];
				return isset($vessel->$key) ? sanitize_title($vessel->$key) : '';
			}, $pattern);
		
			// Trim slashes and build full URL
			$url_path = trim($url_path, '/');
			$full_url = get_site_url() . '/' . $url_path . '/';
		
			return apply_filters('wp_yatco_yacht_url', $full_url, $vessel);
		}
		
		public function wp_yacht_url($vessel) {
			$url_singleYacht = get_option('yatcoseo_seo_url_singleYacht');
			if( $url_singleYacht ){
				return $this->generate_url_from_options( $vessel, $url_singleYacht );
			}
			$url = get_site_url(). '/yacht/' . $this->seo_path_encode($this->get_vessel_target_path($vessel)) .'/';
			return apply_filters('wp_yatco_yacht_url', $url, $vessel);
		}

		public function wp_charter_url($charter_veseel) {
			$url_singleCharter = get_option('yatcoseo_seo_url_singleCharter');
			if( $url_singleCharter ){
				return $this->generate_url_from_options( $charter_veseel, $url_singleCharter );
			}
			$url = get_site_url().'/yachts-for-charter/'.$this->seo_path_encode($charter_veseel->VesselName .'-'. $charter_veseel->VesselID) .'/';		

			return apply_filters('wp_yatco_charter_url', $url, $charter_veseel);

		}

		public function wp_builder_model($model) {

			$model_name_no_numbers = preg_replace('/[0-9]+/', '', $model->ModelName); 

			$url = get_site_url();

			$url .= '/model';

			$url .= '/'.$this->seo_path_encode($model->BuilderName .' '. $model_name_no_numbers);

			$url .= '/'. $this->seo_path_encode($model->ModelName.' '.$model->BuilderModelID); 

			$url .= '/';

			return apply_filters('wp_builder_model_url', $url, $model);
		}

		public function wp_services_mls_url($service) {

			$url = get_site_url();

			$url .= '/Services/'. $service->CRMCompanyID .'/';

			return apply_filters('wp_yatco_service_url', $url, $service);

		}
	}