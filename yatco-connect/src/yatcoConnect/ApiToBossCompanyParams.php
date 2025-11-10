<?php 
	class yatcoConnect_ApiToBossCompanyParams extends yatcoConnect_Base_Params {

		public $params_from_pro=array(
			'SpecialtyID' => 1,
			'AdminSearch' => true,
			'CompanyTypeIDs' => 
			array (
				0 => 1,
				1 => 2,
			),
			'ExcludeCompanyIDs' => 
			array (
				0 => 1,
				1 => 2,
			),
			'Offset' => 3,
			'Records' => 4,
			'CompanyID' => 5,
			'CompanyName' => 'sample string 6',
			'Address1' => 'sample string 7',
			'Address2' => 'sample string 8',
			'City' => 'sample string 9',
			'State' => 'sample string 10',
			'PostalCode' => 'sample string 11',
			'Country' => 'sample string 12',
			'MainPhone' => 'sample string 13',
			'Fax' => 'sample string 14',
			'Website' => 'sample string 15',
			'Email' => 'sample string 16',
			'PhotoID' => 1,
			'isActive' => true,
			'CreateDate' => '2021-07-12T19:58:47.1017791Z',
			'ActiveDate' => '2021-07-12T19:58:47.1017791Z',
			'ModifiedDate' => '2021-07-12T19:58:47.1017791Z',
			'ImageSmallUrl' => 'sample string 19',
			'ImageMediumUrl' => 'sample string 20',
			'ImageLargeUrl' => 'sample string 21',
			'SortBy' => 'sample string 22',
			'SortOrder' => 0,

			'LocationRegionID' => '',
			'LocationCountryID' => '',
			'LocationStateID' => '',
			'LocationCityID' => ''
		);

		public $added_criteria=[
			'page_index' => '',
			'page_size' => 12,
		];
		
		public $int_fields = [
			'records',
			'page_size', 
		];

		public $range_fields = array(
			
		);

		public $required_params = [

		];

		public $ExcludeCompanyIDs=[1, 1193];

		public function __construct() {
			parent::__construct();

		}

		public function prepare_search_parameters( $atts ) {
			$criteria = parent::prepare_search_parameters( $atts );
			
			$criteria['isActive'] = true;

			$criteria['ExcludeCompanyIDs']=$this->ExcludeCompanyIDs;

			return $criteria;
		}
	}