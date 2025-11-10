<?php 
	class yatcoConnect_Base_Params {

		public $params_from_pro=array();

		public $added_criteria=[];

		public $int_fields = [];

		public $range_fields = array();

		public $required_params = [];

		public function __construct() {
			$this->max_page_size=50;
			
			// Empty Pro Data
			foreach ($this->params_from_pro as $key => $value) {
				
				$this->params_from_pro[ $key ] = '';

			}

			foreach ($this->int_fields as $fleid) {
				$fleid_lowercase = strtolower($fleid);

				if (! in_array($fleid_lowercase, $this->int_fields)) {

					$this->int_fields[]= $fleid_lowercase;

				}
			}

			if (isset($this->array_fields) && is_array($this->array_fields)) {
				$array_fields_lowercase = array_map( 'strtolower', $this->array_fields);

				$this->array_fields = array_merge($this->array_fields, $array_fields_lowercase);

				foreach ($this->array_fields as $arrayField) {

					$this->added_criteria[$arrayField.'_single'] = '';

				}
			}
		}

		public function create_criteria() {
			$criteria = $this->params_from_pro;
			
			$criteria = array_merge($criteria, $this->added_criteria);
			$criteria = array_merge($criteria, array_change_key_case($criteria, CASE_LOWER));

			return $criteria;
		}

		public function prepare_search_parameters( $atts ) {
			/*if (isset($criteria[ 'has_been_processed' ]) && $criteria[ 'has_been_processed' ] == true) {

				return $criteria;

			}*/
			
			$criteria = self::create_criteria();
			
			// required search parameters (not using this for now but could do a merge with this array first)
			$required = $this->required_params;

			// apply attributes from Shortcode Exec PHP to criteria (overrides REQUEST parameters) uses same trick as above
			if (isset($atts) && is_array($atts)) {
				$criteria = array_merge(
					$criteria, 
					array_intersect_key($atts, $criteria)
				);
			}

			// apply any REQUEST (GET/POST) criteria (only allows search parameters already defined in $criteria)
			$request_get_post_in_lowercase = array_change_key_case($_REQUEST, CASE_LOWER);


			// to do add results args old and new meta keys here!


			$criteria = array_merge($criteria, array_intersect_key($request_get_post_in_lowercase, $criteria));

			// for small int fields, we make sure they are vaild because otherwise API barfs
			$int_fields = $this->int_fields;

			foreach ($int_fields as $field) {
				if (isset($criteria[ $field ])) {
					$criteria[$field] = urldecode( $criteria[$field] );
					
					$criteria[$field] = filter_var($criteria[$field], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					$criteria[$field] = (int) $criteria[$field];

					if ($criteria[$field] === 0) {
						$criteria[$field] = '';
					}
				}
			}				

			// make sure page_size is not crazy high
			if ($criteria['page_size'] > 0) {
				$criteria['page_size'] = min($criteria['page_size'], $this->max_page_size);
			}

			// remove any blank fields
			foreach ($criteria as $key => $value) {

				if (is_string($value)) {
					// Trim Fields
					$criteria[$key]=trim($value);

					// Strip Slashes
					$criteria[ $key ] = stripslashes( $value );
				}

				if ($value === '') {
					unset($criteria[$key]);
				}
				
				if ($value === 0) {
					unset($criteria[$key]);
				}

			}

			// phasing ranges, - and +
			foreach ($this->range_fields as $field) {
				$special_key = $field.'_ranged';

				if (isset($criteria[ $special_key ])) {


					$special_field = $criteria[ $special_key ];

					if (
						isset($criteria[ $special_key ]) 
						&& 
						(

							preg_match("/[0-9]*(\-)[0-9]*/", $special_field)
							||
							preg_match("/[0-9]*(\-)/", $special_field)
							||
							preg_match("/[0-9]*(\+)/", $special_field)

						)
					) {

						if (preg_match("/[0-9]*(\-)[0-9]*/", $special_field)) {
							$split=explode('-', $special_field);

							// both _from and _to
							$criteria[ $field.'_from' ] = $split[0];
							$criteria[ $field.'_to' ] = $split[1];

							//unset($criteria[ $special_key]);


						}

						elseif (preg_match("/[0-9]*(\-)/", $special_field)) {
							$matching = [];

							preg_match("/[0-9]*/", $special_field, $matching);

							// max _to
							$criteria[ $field.'_to' ] = $matching[0];

							//unset($criteria[ $special_key]);

						}

						elseif (preg_match("/[0-9]*(\+)/", $special_field)) {
							$matching = [];

							preg_match("/[0-9]*/", $criteria[ $special_key ], $matching);

							//min _from
							$criteria[ $field.'_from' ] = $matching[0];
							
							//unset($criteria[ $special_key]);

						}

					}

				}
			}

			foreach ($this->range_fields as $field) {
				if (isset($criteria[ $field ]) && is_string($criteria[ $field ]) && strpos($criteria[ $field ], ',') !== false) {

					$split=explode(',', $criteria[ $field ]);

					$criteria[ $field ] = [
						'Start' => $split[0],
						'End' => $split[1]
					];

				}
				elseif ( isset( $criteria["{$field}_from"]) || isset( $criteria["{$field}_to"]) ) {
					//$criteria['_'.$field ] = $this->convert_to_range($criteria["{$field}_from"], $criteria["{$field}_to"]);
					if (isset( $criteria["{$field}_from"])) {
						$from = filter_var($criteria["{$field}_from"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					}
					else {
						$from = 0;
					}

					if (isset( $criteria["{$field}_to"] )) {
						$to = filter_var($criteria["{$field}_to"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					}
					else {
						//$to = 999999999999999;
						$to=0;
					}

					if ($to && $from) {
						$criteria[ $field ] = [
							'Start' => $from,
							'End' => $to
						];
					}
					else if ($to && ! $from) {
						$criteria[ $field ] = [
							'Start' => 0,
							'End' => $to
						];
					} elseif ( ! $to && $from) {
						$criteria[ $field ] = [
							'Start' => $from,
							'End' => 999999999,
						];
					}
				}
			}			

			if (! isset($criteria['page_index'])) {
				$criteria['page_index'] = 1;
			}

			if (isset($criteria['page_size'])) {
				$criteria['records'] = $criteria['page_size'];
			}

			if (! isset($criteria['records'])) {
				$criteria['records'] = 12;
			}

			if (! isset($criteria['offset']) || empty($criteria['offset']) || is_null($criteria)) {

				if ($criteria['page_index'] > 1) {
					$criteria['offset'] = ($criteria['page_index']-1) * $criteria['records'];
				}
				else {
					$criteria['offset'] = 0;
				}

			}

			// ARRAY FIELD
			if (isset($this->array_fields) && is_array($this->array_fields)) {
				foreach ($this->array_fields as $arrayField) {

					$field_key = $arrayField.'_single';

					if (isset($criteria[$field_key])) {

						$criteria[ $arrayField ] = [ $criteria[$field_key] ];					

					}

				}

				foreach ($this->array_fields as $arrayField) {

					if (isset($criteria[$arrayField]) && ! is_array($criteria[ $arrayField ]) && ! empty($criteria[ $arrayField ])) {

						$split=explode(',', $criteria[ $arrayField ]);

						$criteria[ $arrayField ] = $split;					

					}

				}
			}

			/*if (isset($criteria['limit_mobile_size']) && $criteria['limit_mobile_size'] > 0 && wp_is_mobile()) {

				$criteria['records'] = $criteria['limit_mobile_size'];

			}*/

			if (defined('YATCO_ONLY_USE_COMPANY_ID') && ! isset($criteria['CompanyID'])) {
				$criteria['CompanyID'] = YATCO_ONLY_USE_COMPANY_ID;
			}

			$criteria[ 'has_been_processed' ] = true;

			foreach ($criteria as $key => $input) {
				if (is_string($input)) {
					$criteria[$key] = urldecode($input);
				}

			}

			return $criteria;
		}

	}