<?php
	
	class yatcoConnect_ListsAndOptions_Core {
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_fill_options_and_lists', [$this, 'version_one_options'], 10, 2);

		}

		public function version_one_options($results, $label) {

			switch ( $label ) {
				case 'Builders':
					$results = $this->data->get_builders($filter);
					break;

				case 'PopularBuilders':
					$results = $this->data->get_popular_builders($filter);
					break;

				case 'ActiveBuilders':
					$results = $this->data->get_active_builders($filter);
					break;

				case 'VesselNames':
					$results = $this->data->get_list_of_vessel_names();
					break;

				case 'Categories':
					$results = $this->data->get_list('VesselCategory');
					break;

				case 'SailCategories':
					$results = $this->data->get_list('VesselSailCategory');
					break;

				case 'BothCategories':
					$results = $this->data->get_list('VesselCategory');

					$sail_results = $this->data->get_list('VesselSailCategory');
					foreach ($sail_results as $sailC) {
						$results[] = $sailC;
					}
					
					break;

				case 'SailCategories':
					$results = $this->data->get_list('VesselSailCategory');
					break;

				case 'SubCategories':
					$results = $this->data->get_list($_REQUEST['mainID']);
					break;

				case 'Fuel':
					$results = $this->data->get_list('VesselEngineFuelType');
					break;

				case 'Engine':
					$results = $this->data->get_list('VesselEngineType');
					break;

				case 'HullType':
					$results = $this->data->get_list('HullType');
					break;

				case 'HullMaterial':
					$results = $this->data->get_list('VesselHullMaterial');
					break;
					
				case 'Sorts':
					$results = $this->data->get_list('vscsortoptions');
					break;

				case 'AssociationRegions': 
					$results = $this->data->association_regions($_REQUEST['id']);
					break;

				case '':
					$results = [];
					break;

				default: 
					$results = $this->data->get_list($label);
					break;

			}

			return $results;
		}


	}