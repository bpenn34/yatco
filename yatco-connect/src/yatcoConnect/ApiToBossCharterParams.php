<?php 
	class yatcoConnect_ApiToBossCharterParams extends yatcoConnect_Base_Params {

		public $params_from_pro=array (
			"records" => 1, 
			"offset" => 2, 
			"sortID" => 3, 
			"sortDirection" => "sample string 4", 
			"sortField" => "sample string 5", 
			"CurrencyID" => 6, 
			"SeasonID" => 7, 
			"RateFrequency" => 8, 
			"StatusFilter" => [
				1, 
				2 
			], 
			
			"LocationList" => [
				1, 
				2 
			], 

			"LoaRange" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"OperatingRange" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"RateRange" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"YearRange" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"Guests" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"Cabins" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"Sleeps" => [
				"Start" => 1, 
				"End" => 2 
			], 
			"Availability" => [
				"Start" => "2020-08-13T22:28:50.7267259+00:00", 
				"End" => "2020-08-13T22:28:50.7267259+00:00" 
			], 
			"BasePort" => "sample string 9", 
			"fStatus" => "sample string 10", 
			"fLOA" => "sample string 11", 
			"TagsList" => [
				1, 
				2 
			], 
			"CharterAgents" => [
				1, 
				2 
			], 
			"Amenities" => [
				1, 
				2 
			], 
			"VesselID" => 12, 
			"VesselName" => "sample string 13", 
			"PrevName1" => "sample string 14", 
			"PrevName2" => "sample string 15", 
			"PrevName3" => "sample string 16", 
			"LOAMetric" => 17, 
			"LOA" => 18, 
			"RangeMetric" => 1, 
			"Range" => 1, 
			"GrossTonsMetric" => 1, 
			"GrossTons" => 1, 
			"DraftMin" => 1, 
			"DraftMax" => 1, 
			"BeamMin" => 1, 
			"BeamMax" => 1, 
			"ModelYear" => 1, 
			"RefitYear" => 1, 
			"SpeedMetric" => 1, 
			"CruisingSpeed" => 1, 
			"MaxSpeed" => 1, 
			"BuilderID" => 1, 
			"VesselTypeID" => 1, 
			"VesselCategoryID" => 1, 
			"VesselSubCategoryID" => 1, 
			"HullConfigurationID" => 1, 
			"HullMaterialID" => 1, 
			"HullID" => "sample string 19", 
			"PortOfRegistry" => "sample string 20", 
			"ISMCompliant" => true, 
			"MCACompliant" => true, 
			"PublishYATCOCharter" => true, 
			"PublishMyWebsite" => true, 
			"PublishThirdParty" => true, 
			"PublishCharterLinx" => true, 
			"CompanyID" => 25, 
			"OfficeID" => 1, 
			"BrokerID" => 26, 
			"Deleted" => true, 
			"VesselStatus" => 1, 
			"ExtDesigner" => "sample string 28", 
			"IntDesigner" => "sample string 29", 
			"NumberOfGenerators" => 1, 
			"EngineCount" => 1, 
			"EngineManufacturer" => 1, 
			"EngineModel" => "sample string 30", 
			"EngineType" => 1, 
			"EngineFuelType" => 1, 
			"EnginePropulsionType" => 1, 
			"FuelUnitType" => 1, 
			"FuelCapcity" => 1, 
			"NumberOfCrew" => 31, 
			"PublishYATCOConsumer" => true, 
			"NumberOfGuests" => 32, 
			"TotalStateRooms" => 1, 
			"TotalHeads" => 1, 
			"PublishLybraProOnly" => true, 
			"FuelConsumption" => 1, 
			"FuelConsumptionSpeed" => 1, 
			"Generators" => "sample string 33", 
			"BuilderName" => "sample string 34", 
			"Category" => "sample string 35", 
			"SubCategory" => "sample string 36", 
			"NumberOfSleeps" => 37, 
			"FlagID" => 1, 
			"MainPhotoID" => 38, 
			"LOAFeet" => 39, 
			"LOAMeters" => 40, 
			"LoaFormat" => "sample string 41"
		);

		public $added_criteria=[
/*			'loa_from' => '',
			'loa_to' => '',

			'pricerange_from' => '',
			'pricerange_to' => '',

			'year_from' => '',
			'year_to' => '',

			'grosstonnage_from' => '',
			'grosstonnage_to' => '',
*/
			"LoaRange_from" => '', "OperatingRange_from" => '', "RateRange_from" => '', "YearRange_from" => '', "Guests_from" => '', "Cabins_from" => '', "Sleeps_from" => '', "Availability_from" => '',
			"LoaRange_to" => '', "OperatingRange_to" => '', "RateRange_to" => '', "YearRange_to" => '', "Guests_to" => '', "Cabins_to" => '', "Sleeps_to" => '', "Availability_to" => '',


			'page_index' => '',
			'page_size' => 12,

			'limit_mobile_size' => 0,

			'featured' => '',	

			'Destinations'	=> '',

			'Amenities_SpecialAccess' => '',
			'Amenities_SpecialAccess_E' => '',
			'Amenities_SpecialAccess_W' => '',
			'Amenities_Air' => '',

			'EmbarkationPort'=>'',
			'DisembarkationPort'=>'',
			'Location' => '',

			'VesselIds' => '',
			'Eventid' => '',
			'EventId' => '',
			'EventID' => '',

			'Keywords' => '',
			// 'LengthUnit' => '',
			'LOAMetric' => '',

			'Bareboat' => ''

		];
		
		public $int_fields = [
			'num_staterooms',
			'records',
			'page_size', 
			'FeaturedNotFirst',
			'limit_mobile_size',
			'Amenities_SpecialAccess',
			'Amenities_Air',
			'NumberOfSleeps',
			'NumberOfGuests',
			'NumberOfGenerators',
			'NumberOfCrew'
		];

		public $range_fields = array(
			"LoaRange", "OperatingRange", "RateRange", "YearRange", "Guests", "Cabins", "Sleeps", "Availability",
			"loarange", "operatingrange", "raterange", "yearrange", "guests", "cabins", "sleeps", "availability"
		);

		public $required_params = [

		];

		public $array_fields = [
			'LocationList',
			'VesselIds',
			'tagslist'
		];


		public function __construct() {
			parent::__construct();

		}

		public function prepare_search_parameters( $atts ) {
			$criteria = parent::prepare_search_parameters( $atts );
			
			if (isset($criteria['Amenities_SpecialAccess'])) {
				if (isset($criteria['Amenities']) && is_array($criteria['Amenities'])) {
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess'];
				}
				else {
					$criteria['Amenities'] = [];
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess'];
				}
			}
			
			if (isset($criteria['Amenities_SpecialAccess_W'])) {
				if (isset($criteria['Amenities']) && is_array($criteria['Amenities'])) {
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess_W'];
				}
				else {
					$criteria['Amenities'] = [];
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess_W'];
				}
			}

			if (isset($criteria['Amenities_SpecialAccess_E'])) {
				if (isset($criteria['Amenities']) && is_array($criteria['Amenities'])) {
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess_E'];
				}
				else {
					$criteria['Amenities'] = [];
					$criteria['Amenities'][] = $criteria['Amenities_SpecialAccess_E'];
				}
			}

			if (isset($criteria['Amenities_Air'])) {

				if (isset($criteria['Amenities']) && is_array($criteria['Amenities'])) {
					$criteria['Amenities'][] = $criteria['Amenities_Air'];
				}
				else {
					$criteria['Amenities'] = [];
					$criteria['Amenities'][] = $criteria['Amenities_Air'];
				}
				
			}

			if (isset($criteria['Location']) && ! isset($criteria['LocationList'])) {

				$criteria['LocationList'] = [ $criteria['Location'] ];
				
			}

			if (isset($criteria['location']) && ! isset($criteria['LocationList'])) {

				$criteria['LocationList'] = [ $criteria['location'] ];
				
			}

			if (! isset($criteria['sortField']) && ! isset($criteria['sortDirection']) ) {

				$criteria['sortField'] = 'HighRate';
				$criteria['sortDirection'] = 'desc';
				
			}
			
			return $criteria;

		}


	}