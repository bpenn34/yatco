<?php
		
	class yatcoConnect_ListsAndOptions_CRM {
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_fill_options_and_lists', [$this, 'version_one_options'], 10, 2);

		}

		public function version_one_options($results, $label) {

			switch ( $label ) {
				case 'Membership_Company_List':
					$results =  $this->data->build_simpler_list(
						$this->data->membership_companies_list(),
						'CompanyID',
						'CompanyName'
					);
					
					break;

				case 'Membership_Reps_List':
					$results =  $this->data->build_simpler_list(
						$this->data->membership_reps_list(),
						'ContactID',
						'ContactName'
					);

					break;
				
				case 'Membership_Category_List':
					$results =  $this->data->build_simpler_list(
						$this->data->membership_category_list(),
						'CategoryID',
						'CategoryName'
					);				

					break;

				case 'Membership_Region_List':
					$results =  $this->data->build_simpler_list(
						$this->data->membership_region_list(),
						"RegionID",
						'Name'
					);

					break;

				case 'Membership_Country_List':
					$results =  $this->data->build_simpler_list(
						$this->data->membership_country_list(),
						'ListItemID',
						'ListItemDescription'
					);

					break;

				case 'Membership_States_List':
					$results = $this->data->membership_states_list($_REQUEST['countryId']);

					break;

			}

			return $results;
		}

	}