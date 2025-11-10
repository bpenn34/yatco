<?php
		
	class yatcoConnect_ListsAndOptions_Charter {
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_fill_options_and_lists', [$this, 'version_one_options'], 10, 2);

		}

		public function version_one_options($results, $label) {

			switch ( $label ) {
				case 'DDCharterDestinations':
					$results = $this->data->get_charter_dropdown_destinations();
					break;

				case 'CharterSeasons':
					$results = $this->data->get_charter_seasons();
					break;

				case 'CharterFreqTypes':
					$results = $this->data->get_charter_freq_types();
					break;

				/*case 'CharterAmenities':
					$results = $this->data->get_charter_amenities();
					break;*/
				
				case 'CharterAmenities_SpecialAccess':
					$results = $this->data->get_charter_amenities_special_access();
					break;		
				
				case 'CharterAmenities_Air':
					$results = $this->data->get_charter_amenities_special_air();
					break;
				
			
			}

			return $results;
		}

	}