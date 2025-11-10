<?php 
	class yatcoConnect_WpFiltersApiToBoss {

		public function __construct() {

			$this->apiToBoss = new yatcoConnect_ApiToBoss();
			$this->options = new yatcoConnect_Options();
			$this->virtual_post_type_url = new yatcoConnect_VirtualPostTypeURL();

		}

		public function add_actions_and_filters() {
			add_filter('wp_process_yachts', [$this, 'wp_filter_process_yachts'], 15, 1);
			add_filter('wp_yatco_yacht_url', [$this, 'wp_yatco_yacht_url'], 5, 2);
			add_filter('wp_process_yacht_details', [$this, 'wp_filter_process_yacht_details'], 15, 1);

			add_filter('wp_process_company', [$this, 'wp_filter_process_company'], 15, 1);
			add_filter('wp_process_broker', [$this, 'wp_filter_process_broker'], 15, 1);
			add_filter('wp_process_office', [$this, 'wp_filter_process_office'], 15, 1);

			add_filter('wp_process_charters', [$this, 'wp_filter_process_charters'], 15, 1);
			add_filter('wp_process_charter_details', [$this, 'wp_filter_process_charter_details'], 15, 1);

			add_filter('wp_process_builder_models', [$this, 'wp_filter_process_builder_models'], 15, 1);
			add_filter('wp_process_builder_model_details', [$this, 'wp_filter_process_builder_model_details'], 15, 1);

			add_filter('wp_process_services', [$this, 'wp_filter_process_services'], 15, 1);
			add_filter('wp_process_service_details', [$this, 'wp_filter_process_service_details'], 15, 1);

		}

		public function wp_yatco_yacht_url($url, $vessel) {
			return $url;
		}

		public function wp_filter_process_yachts($result) {
			$cfp_message = $this->options->getOption('cfp_message');

			if (!empty($result->Results) && (is_array($result->Results) || is_object($result->Results))) {

				foreach ($result->Results as $vessel) {

					/*$vessel->title = sprintf('%s %s %s %s %s %s, %s, %s',
						$vessel->BoatName,
						$vessel->ModelYear,
						$vessel->Builder,
						$vessel->LOAFeet,
						
						$vessel->Model,
						$vessel->MainCategory,
						$vessel->State,
						$vessel->Country
					);*/

					$vessel->short_title = '';

					$vessel->a_url = $this->virtual_post_type_url->wp_yacht_url($vessel);

					//PATCH:
					if (strpos($vessel->AskingPriceFormatted, '(') !== false) {
						$vessel->AskingPriceFormatted = preg_replace('/[^)]+?\(([^)]+?)\)/', '${1}', $vessel->AskingPriceFormatted);
					}

					// Swap Image CDN 
					$vessel->MainPhotoUrl = str_replace('s3-external-1.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $vessel->MainPhotoUrl);

					foreach ($vessel->BrokerList as $b_index => $broker) {
						
						$broker->Company_A_URL = $this->virtual_post_type_url->wp_company_url($broker);
						$broker->a_url = $this->virtual_post_type_url->wp_broker_url($broker, null);

					}


					if ($vessel->AskingPriceFormattedNoCurrency === 'Price on Application') {

						if (!empty($cfp_message)) {

							$vessel->AskingPriceFormattedNoCurrency = $cfp_message;
						}

					}

					if ($vessel->IsStartingPrice) {
						$vessel->AskingPriceFormattedNoCurrency = 'Price Starting At '. $vessel->AskingPriceFormattedNoCurrency;
					}

					if ($vessel->VesselStatus == 99) {
						$vessel->AskingPriceFormattedNoCurrency = '';
					}


					if (isset($vessel->LocationCountry)) {
						$vessel->Country = $vessel->LocationCountry;
					}

					if (isset($vessel->BasicInfo->LocationState)) {
						$vessel->State = $vessel->LocationState;
					}

					if (isset($vessel->LocationCity)) {
						$vessel->City = $vessel->LocationCity;
					}

					$vessel->LocationBlurb=apply_filters('yatco_full_address', $vessel, false, true);
				}

			}

			return $result;
		}

		public function wp_filter_process_yacht_details($yacht) {
			if (! isset($yacht->Company)) {
				$yacht->Company = $this->apiToBoss->get_company($yacht->Brokers[0]->CompanyID);
			}
			else {
				$yacht->Company = apply_filters('wp_process_company', $yacht->Company);

			}

			if (isset($yacht->BasicInfo->MainPhotoURL) && !empty($yacht->BasicInfo->MainPhotoURL)) {
				$yacht->BasicInfo->MainPhotoURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $yacht->BasicInfo->MainPhotoURL);
			}

			$vessel_data_for_url = $yacht->Result;
			$vessel_data_for_url = (object) $vessel_data_for_url;

			$yacht->a_url = $this->virtual_post_type_url->wp_yacht_url($vessel_data_for_url);

			foreach ($yacht->PhotoGallery as $key => $pic) {

				$pic->smallImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->smallImageURL);

				$pic->mediumImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->mediumImageURL);

				$pic->largeImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->largeImageURL);

				$yacht->PhotoGallery[ $key ] = $pic;
			}

			if ($yacht->BasicInfo->BoatName == '') {
				$yacht->BasicInfo->BoatName = 'NA';
			} 

			if ($yacht->VD->VesselDescriptionShortDescriptionNoStyles == 'N/A') {
				$yacht->VD->VesselDescriptionShortDescriptionNoStyles='';
			}

			$yacht->is_boat_by_length=(
				($yacht->Dimensions->LengthUnit == 1 && $yacht->Dimensions->Length <= 78)
				||
				($yacht->Dimensions->LengthUnit == 2 && $yacht->Dimensions->Length <= 24)
			);

			$yacht->is_eur=($yacht->BasicInfo->Currency == 'EUR');

			if (! isset($yacht->BasicInfo->Model)) {
				$yacht->BasicInfo->Model='';
			}

			if (! isset($yacht->BasicInfo->MainCategory)) {
				$yacht->BasicInfo->MainCategory='';
			}

			if (isset($yacht->BasicInfo->LocationCountry)) {
				$yacht->BasicInfo->Country = $yacht->BasicInfo->LocationCountry;
			}

			if (isset($yacht->BasicInfo->LocationState)) {
				$yacht->BasicInfo->State = $yacht->BasicInfo->LocationState;
			}

			if (isset($yacht->BasicInfo->LocationCity)) {
				$yacht->BasicInfo->City = $yacht->BasicInfo->LocationCity;
			}

			if (! isset($yacht->BasicInfo->City)) {
				$yacht->BasicInfo->City='';
			}

			if (! isset($yacht->BasicInfo->State)) {
				$yacht->BasicInfo->State='';
			}

			if (! isset($yacht->BasicInfo->Country)) {
				$yacht->BasicInfo->Country='';
			}

			$yacht->LocationBlurb=apply_filters('yatco_full_address', $yacht->BasicInfo, false, true);

			if (! isset($yacht->Dimensions->DraftFeet)) {
				$yacht->Dimensions->DraftFeet="";
			}

			if (! isset($yacht->Dimensions->DraftMeter)) {
				$yacht->Dimensions->DraftMeter="";
			}

			if (! isset($yacht->BasicInfo->RefitYear)) {
				$yacht->BasicInfo->RefitYear="";
			}

			if (is_array($yacht->Sections)) {

				foreach($yacht->Sections as $section) {

					$section->SectionText=str_replace('<h1', '<h2', $section->SectionText);
				}

			}

			return $yacht;
		}

		public function wp_filter_process_broker($broker) {
			if (! isset($broker->BrokerID) && isset($broker->AccountID)) {
				$broker->BrokerID = $broker->AccountID;
			}

			if (isset($broker->ImageMediumUrl) && $broker->ImageMediumUrl != '') {
				$broker->IMG = $broker->ImageMediumUrl;
			}
			elseif (isset($broker->PhotoURL) && $broker->PhotoURL != "https://placehold.it/300x150/" && $broker->PhotoURL != 'https://s3.amazonaws.com/boss.yatco.com/CMS/Account/Photo/small_0.jpg') {
				$broker->IMG = $broker->PhotoURL;
			}
			else {
				$broker->IMG = YATCO_PLUGIN_ASSETS.'/img/default-broker.jpeg';
			}

			if (isset($broker->BrokerName)) {
				$broker->FullName = $broker->BrokerName;
			}
			else {
				$broker->FullName = $broker->FirstName.' '.$broker->LastName;
			}

			if (isset($broker->ForSaleStats) && ! isset($broker->Stats)) {
				$broker->Stats = $broker->ForSaleStats;
			}
			
			if (isset($broker->CharterStats) && ! isset($broker->Stats)) {
				$broker->Stats = $broker->CharterStats;
			}

			if (isset($broker->OfficeID) && ! isset($broker->Office)) {
				$broker->Office = $this->apiToBoss->get_office( $broker->OfficeID );
			}

			if (! isset($broker->Stats) && ! isset($broker->NoStats)) {
				$broker->Stats = $this->apiToBoss->get_broker_stats($broker->BrokerID);
			}

			if (! isset($broker->Company) && isset($broker->CompanyID)) {
				$broker->Company = $this->apiToBoss->get_company( $broker->CompanyID );
			}
			else if (isset($broker->Company)) {
				$broker->Company = apply_filters('wp_process_company', $broker->Company );
			}

			if (isset($broker->Office) && isset($broker->Office->LocationCountry)) {
				$broker->RightAddress = apply_filters('yatco_full_address', $broker->Office, false, true);
			}
			elseif (isset($broker->Company) && isset($broker->Company->LocationCountry)) {
				$broker->RightAddress = apply_filters('yatco_full_address', $broker->Company, false, true);
			}

			if (isset($broker->Company)) {
				$broker->a_url = $this->virtual_post_type_url->wp_broker_url($broker, $broker->Company);
			}
			else {
				$broker->a_url = $this->virtual_post_type_url->wp_broker_url($broker, false);
			}
			
			$broker->PrintStats='';

				if (isset($broker->Stats) && isset($broker->Stats->VesselCount) && isset($broker->Stats->StartLOAFormatted)) {

					$broker->PrintStats.='<p><b>LISTINGS:</b> '. $broker->Stats->VesselCount .'</p>';

					$broker->PrintStats.='<p><b>LOA RANGE:</b> '. apply_filters('yatco_range', $broker->Stats->StartLOAFormatted, $broker->Stats->EndLOAFormatted, false) .'</p>';

					$broker->PrintStats.='<p><b>PRICE RANGE:</b> '. apply_filters('yatco_range', $broker->Stats->PriceRangeUSD->Start, $broker->Stats->PriceRangeUSD->End, true) .'</p>';

				}
				else {
					$broker->PrintStats.='<p><b>LISTINGS:</b> N/A</p>';

					$broker->PrintStats.='<p><b>LOA RANGE:</b> N/A</p>';

					$broker->PrintStats.='<p><b>PRICE RANGE:</b> N/A</p>';

				}

			return $broker;

		}

		public function wp_filter_process_company($company) {

			$company->a_url = $this->virtual_post_type_url->wp_company_url($company);

			$company->_ADDRESS = apply_filters('yatco_full_address', $company, false, true);

			return $company;

		}

		public function wp_filter_process_office($office) {

			return $office;

		}

		public function wp_filter_process_charters($charters) {

			if(isset($charters->Results)){

				foreach ($charters->Results as $vessel) {

					$vessel->a_url = $this->virtual_post_type_url->wp_charter_url($vessel);

					if (! isset($vessel->Company)) {
						//$vessel->Company=$this->apiToBoss->get_company($vessel->CompanyID);
					}

					if(isset($vessel->OfficeID) && !empty($vessel->OfficeID)){
						//$vessel->Office = $this->apiToBoss->get_office($vessel->OfficeID);
					}

					foreach ($vessel->Brokers as $b_index => $broker) {

						$broker->NoStats = true;

						//$broker->Office = (object) ["Country" => 'NULL'];

						$vessel->Brokers[$b_index] = $this->wp_filter_process_broker($broker);

					}

				}

			}
			return $charters;
		}

		public function wp_filter_process_charter_details($charter) {

			if (is_object($charter)) {

				$charter->a_url = $this->virtual_post_type_url->wp_charter_url($charter);

				$charter->charter_tenders = $this->apiToBoss->get_charter_details_tenders($charter->VesselID);

				$charter->charter_toys = $this->apiToBoss->get_charter_details_toyslist($charter->VesselID);

				$charter->charter_amenities = $this->apiToBoss->get_charter_details_amenities($charter->VesselID);

				if (isset($charter->RatesList[0])) {
					$ParentLocations = array_map(function($l) {
						if(isset($l->ParentDestinationName)){
							return $l->ParentDestinationName;
						}
					}, $charter->RatesList[0]->RateLocations );
					
					$charter->ParentLocations = array_unique($ParentLocations);
				}

				foreach ($charter->Photos as $key => $pic) {

					$pic->smallImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->smallImageURL);

					$pic->mediumImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->mediumImageURL);

					$pic->largeImageURL = str_replace('s3.amazonaws.com/boss.yatco.com', 'cloud.yatco.com', $pic->largeImageURL);

					$charter->Photo[ $key ] = $pic;
				}

				$charter->Company = $this->apiToBoss->get_company($charter->CompanyID);

				// Remove First Photo
				unset($charter->Photos[0]);
				// $charter->Photos = array_values($charter->Photos);
			}

			return $charter;
		}

		public function wp_filter_process_builder_models($models) {

			foreach ($models as $model) {
				$model->a_url = $this->virtual_post_type_url->wp_builder_model($model);
			}

			return $models;
		}

		public function wp_filter_process_builder_model_details($model) {
			$model->a_url = $this->virtual_post_type_url->wp_builder_model($model);
			
			return $model;
		}


		public function wp_filter_process_services( $services  ) {

			//var_dump($services);

			foreach ($services->Results as $is => $s) {
				$s = (object) $s;

				$s->a_url = $this->virtual_post_type_url->wp_services_mls_url($s);

				$services->Results[$is] = $s;
			}

			return $services;

		}

		public function wp_filter_process_service_details( $s  ) {

			$s->a_url = $this->virtual_post_type_url->wp_services_mls_url($s);

			return $s;

		}
	}