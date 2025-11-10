<?php
	class yatcoConnect_Yachts_AIOseo{

		public function __construct() {
			
			$this->option = new yatcoConnect_Options();

			$this->data = new yatcoConnect_ApiToBoss();

			// $this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}
			
		public function add_actions_and_filters() {
			// add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			//add_filter( 'wpseo_opengraph_image', [$this, 'filter_seo_image'], 10, 1);

			add_filter( 'aioseo_title', [$this, 'filter_seo_title'], 10, 1 );

			add_filter( 'aioseo_description', [$this, 'filter_seo_metadesc'], 10, 1);

			add_filter( 'aioseo_canonical_url',  [$this, 'filter_canonical'], 10, 1 );
			
			add_filter( 'get_canonical_url',  [$this, 'filter_canonical'], 10, 1 );

			add_filter( 'aioseo_facebook_tags', [$this, 'aioseo_filter_facebook'], 10, 1 );

		}

		public function aioseo_filter_facebook($facebookMeta) {

			global $wp_query;

			if (is_singular('yatco_yachts')) {

				if(isset($vessel->PhotoGallery[0]->mediumImageURL))
				{
					$mediumImageURL = $vessel->PhotoGallery[0]->mediumImageURL;
				}
				else
				{
					$mediumImageURL = "";
				}

				if(isset($vessel))
				{
				$facebookMeta['og:title'] = $this->get_seo_vessel_title($vessel);
				$facebookMeta['og:description'] = $this->get_seo_vessel_metadesc($vessel);
				}
				else
				{
					
				}

				
				$facebookMeta['og:image'] = $mediumImageURL;
			}

			return $facebookMeta;

		}

		public static function get_seo_vessel_title( $data ) {

			/* define variables with value */

		    if(isset($data->BasicInfo->ModelYear)) {
				$modelyear = $data->BasicInfo->ModelYear;
			}
			else
			{
				$modelyear = "";
			}

			if(isset($data->Dimensions->LOAFeet)) {
				$LOAFeet = $data->Dimensions->LOAFeet;
			}
			else
			{
				$LOAFeet = "";
			}

			if(isset($data->BasicInfo->Builder)) {
				$Builder = $data->BasicInfo->Builder;
			}
			else
			{
				$Builder = "";
			}

			if(isset($data->BasicInfo->City)) {
				$City = $data->BasicInfo->City;
			}
			else
			{
				$City = "";
			}

			if(isset($data->BasicInfo->State)) {
				$State = $data->BasicInfo->State;
			}
			else
			{
				$State = "";
			}
			if(isset($data->BasicInfo->Country)) {
				$Country = $data->BasicInfo->Country;
			}
			else
			{
				$Country = "";
			}

			if(isset($data->MiscInfo->MLSID)) {
				$MLSID = $data->MiscInfo->MLSID;
			}
			else
			{
				$MLSID = "";
			}

			return trim(preg_replace('/\s\s+/', ' ',
				sprintf('%s %s %s in %s %s %s (%s) | %s',
					$modelyear,
					$LOAFeet,
					$Builder,
					$City,
					$State,
					$Country,
					$MLSID,
					get_bloginfo('name')
			)));
		}

		public static function get_seo_vessel_metadesc( $data ) {
			if (isset($data) && isset($data->BasicInfo)) {
				return str_replace(array(' ""', ' .'), array(' ', '.'), preg_replace('/\s,?\s+/', ' ',
					sprintf('Yacht for sale is a %s %s %s "%s" %s %s, %s, %s, %s. Find the boat that best meets your needs today.',
						$data->BasicInfo->ModelYear,
						$data->BasicInfo->Builder,
						$data->Dimensions->LOAFeet,
						$data->BasicInfo->BoatName,
						$data->BasicInfo->Model,
						$data->BasicInfo->MainCategory,
						$data->BasicInfo->City,
						$data->BasicInfo->State,
						$data->BasicInfo->Country
				)));
			} 

			return "";

		}

		public function filter_seo_title($title) {
			global $wp_query;

			if (is_singular('yatco_yachts')) {
				$results = $wp_query->queried_object->boss_data;

				//If values present in YATCO seo settings page then it will override
				$title_singleYacht = get_option('yatcoseo_seo_title_singleYacht');

				if(isset($title_singleYacht) && !empty($title_singleYacht)){

					$title = htmlentities( yatcoConnect_YatcoSeo::get_yatcoseo_yatchData( $results, $title_singleYacht ) );

					return $title;

				}

				$title = htmlentities(self::get_seo_vessel_title($results));

				//var_dump($this->option->use_boss_seo);

				if ($this->option->use_boss_seo) {
					$yacht = $wp_query->queried_object->boss_data;

					if (isset($yacht->SEO->MyWebsiteSEOPageTitle)) {

						return $yacht->SEO->MyWebsiteSEOPageTitle;
						
					}

				}
			}else if (is_singular('yatco_charters')) {

				$results = $wp_query->queried_object->boss_data;

				//If values present in YATCO seo settings page then it will override
				$title_singleCharter = get_option('yatcoseo_seo_title_singleCharter');

				if(isset($title_singleCharter) && !empty($title_singleCharter)){

					$title = htmlentities( yatcoConnect_YatcoSeo::get_yatcoseo_charterData( $results, $title_singleCharter ) );

					return $title;
				}

			}else{
				
			}

			return $title;
		}

		public function filter_seo_metadesc($metadesc) {
			global $wp_query;

			if (is_singular('yatco_yachts')) {
				$results = $wp_query->queried_object->boss_data;

				//If values present in YATCO seo settings page then it will override
				$desc_singleYacht = get_option('yatcoseo_seo_description_singleYacht');

				if(isset($desc_singleYacht) && !empty($desc_singleYacht)){

					$desc = htmlentities( yatcoConnect_YatcoSeo::get_yatcoseo_yatchData( $results, $desc_singleYacht ) );

					return $desc;

				}

				$metadesc = htmlentities(self::get_seo_vessel_metadesc($results));

				
				if ($this->option->use_boss_seo) {
					$yacht = $wp_query->queried_object->boss_data;
					if(isset($yacht->SEO->MyWebsiteSEOPageDescription)){
						return $yacht->SEO->MyWebsiteSEOPageDescription;
					}

				}
			}else if (is_singular('yatco_charters')) {

				$results = $wp_query->queried_object->boss_data;

				//If values present in YATCO seo settings page then it will override
				$desc_singleCharter = get_option('yatcoseo_seo_description_singleCharter');

				if(isset($desc_singleCharter) && !empty($desc_singleCharter)){

					$desc = htmlentities( yatcoConnect_YatcoSeo::get_yatcoseo_charterData( $results, $desc_singleCharter ) );

					return $desc;

				}

			}else{

			}

			return $metadesc;
		}

		public function filter_canonical($url) {
			if (is_singular('yatco_yachts')) {

				global $post;

				$yacht = $post->boss_data;

				$url = $yacht->a_url;
				
			}
			else {

				$current_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				$http_current_url = 'http://'.$current_url;
				$https_current_url = 'https://'.$current_url;

				$yacht_search_url = $this->option->getOption('yacht_search_url');

				if (! empty($yacht_search_url)) {
					if (
						(strpos($http_current_url, $yacht_search_url) !== false || strpos($https_current_url, $yacht_search_url) !== false)
						&&
						count($_GET) > 0
					) {
						$url .= '?'.$_SERVER['QUERY_STRING'];
					}

				}
			}

			return $url;

		}

	}