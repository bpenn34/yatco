<?php
	
	class yatcoConnect_ListsAndOptions_Locations {
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_fill_options_and_lists', [$this, 'version_one_options'], 10, 2);

		}

		public function version_one_options($results, $label) {

			switch ( $label ) {
				case 'Regions':
					$results = $this->data->get_list('Region', '');
					break;

				case 'GeoRegions':
					$results = $this->data->geo_regions();
					break;

				case 'GeoListRegions':
					$results = $this->data->geo_list_regions();
					break;

				case 'GeoListRegionsWithActiveCountries':
					$results = $this->data->geo_list_regions_with_countries_only();
					break;

				case 'Countries':
					$results = $this->data->get_countries();
					break;
				
				case 'GeoRegionCountries':
					$results = $this->data->geo_region_countries($_REQUEST['RegionId']);
					break;

				case 'GeoListRegionCountries':
					$results = $this->data->geo_list_region_countries($_REQUEST['RegionId']);
					break;
				
				case 'GeoListActiveVesselRegionCountries':
						$results = $this->data->geo_list_region_countries_only_active_vessels($_REQUEST['RegionId']);
						break;

				case 'GeoAllCountries':
					$results = $this->data->geo_all_countries();
					break;

				case 'GeoListAllCountries':
					$results = $this->data->geo_list_all_countries();
					break;

				case 'States':
					$results = $this->data->get_country_states($_REQUEST['countrySEO']);
					break;

				case 'GeoStates':
					$results = $this->data->geo_states($_REQUEST['CountryId']);
					break;
				
				case 'GeoListStates':
					$results = $this->data->geo_list_states($_REQUEST['CountryId']);
					break;

				case 'GeoListActiveVesselStates':
					$results = $this->data->geo_list_states_only_active_vessels($_REQUEST['CountryId']);
					break;

				case 'GeoListAllStates':
					$results = $this->data->geo_all_states();
					break;

				case 'GeoCities':
					$results = $this->data->geo_cities($_REQUEST['StateId']);
					break;

				case 'GeoListCities':
					$results = $this->data->geo_list_cities($_REQUEST['StateId']);
					break;

				case 'GeoListActiveCities':
					$results = $this->data->geo_list_cities_only_active_vessels($_REQUEST['StateId']);
					break;

				case 'GeoCountryCities':
					$results = $this->data->geo_country_cities($_REQUEST['CountryId']);
					break;

				case 'GeoListCountryCities':
					$results = $this->data->geo_list_country_cities($_REQUEST['CountryId']);
					break;

				case 'GeoListActiveVesselCountryCities':
					$results = $this->data->geo_list_country_cities_only_active_vessels($_REQUEST['CountryId']);
					break;

			}

			return $results;
		}


	}