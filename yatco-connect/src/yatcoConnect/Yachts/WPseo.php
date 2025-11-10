<?php
	
	class yatcoConnect_Yachts_WPseo{

		public function __construct() {
			
			$this->option = new yatcoConnect_Options();

			$this->data = new yatcoConnect_ApiToBoss();

			// $this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}
			
		public function add_actions_and_filters() {
			// add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			add_filter('wp_title',  [$this, 'filter_seo_title'], 10);

			add_filter('wpseo_title',  [$this, 'filter_seo_title'], 10, 1);

			add_filter('wpseo_metadesc',  [$this, 'filter_seo_metadesc'], 10, 1);

			add_filter('wpseo_schema_webpage', [$this, 'filter_ld_json'], 10, 1);

			add_filter('wpseo_canonical', [$this, 'filter_canonical'], 10, 1);

			add_filter('wpseo_opengraph_url', [$this, 'update_og_url'], 10, 1);

			add_filter('wpseo_breadcrumb_links', [$this, 'my_custom_breadcrumb_links'] );

			add_action('wp_head', [$this, 'head_pagination_links']);

			add_action('wp_head', [$this, 'meta_tag_yacht_vessel_id']);

			add_action('template_redirect', [$this, 'oneurlnomatterwhat'] );

			add_filter('yatco_seo_heading_yacht', [$this, 'just_return']);
			add_filter('yatco_seo_heading_yacht', [$this, 'just_return_boss_h']);
			add_filter('yatco_seo_sentence_yacht', [$this, 'just_return']);
			add_filter('yatco_seo_sentence_yacht', [$this, 'just_return_boss_h']);
			add_filter('yatco_seo_last_sentence_yacht', [$this, 'just_return']);

			add_filter('yatco_seo_heading_brokerage', [$this, 'just_return']);
			add_filter('yatco_seo_paragraph_brokerage', [$this, 'just_return']);

			add_filter('yatco_seo_heading_broker', [$this, 'just_return']);
			add_filter('yatco_seo_paragraph_broker', [$this, 'just_return']);

			add_filter('yatco_yacht_params', [$this, 'just_return']);

			add_action("wp_head", [$this, 'pre_load']);
		}

		public function pre_load() {
			global $post;

			if (is_singular('yatco_yachts')) {
				$vessel = $post->boss_data;

				if ( wp_is_mobile() ) {
					$image = $vessel->PhotoGallery[0]->mediumImageURL;
				}
				else {
					$image = $vessel->PhotoGallery[0]->largeImageURL;
				}

				// <link rel="preload" as="image" href="wolf.jpg" imagesrcset="wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w" imagesizes="50vw">

				echo '<link rel="preload" fetchpriority="high" as="image" href="'. $image .'">';
			}
		}

		public function just_return($txt) {
			return $txt;
		}

		public function my_custom_breadcrumb_links( $links ) {
			global $wp_query;

			if (is_singular('yatco_yachts')) {
				$vessel_post = $wp_query->queried_object;

				$links = [];
	
			    $links[] = [
			        'url' => home_url(),
			        'text' => 'Home',
			    ];

			    $links[] = [
			        'url' => $this->option->getOption('yacht_search_url'),
			        'text' => 'Yacht Search',
			    ];

			    $links[] = [
			        'url' => $vessel_post->boss_data->a_url,
			        'text' => $vessel_post->post_title
			    ];
			}

		    return $links;
		}

		public static function get_seo_vessel_title( $data ) {
			if (!isset($data->BasicInfo->ModelYear)) {
				$data->BasicInfo->ModelYear = '';
			}
			return trim(preg_replace('/\s\s+/', ' ',
				sprintf('%s %s %s in %s %s %s (%s) | %s',
					$data->BasicInfo->ModelYear,
					$data->Dimensions->LOAFeet,
					$data->BasicInfo->Builder,
					$data->BasicInfo->City,
					$data->BasicInfo->State,
					$data->BasicInfo->Country,
					$data->MiscInfo->MLSID,
					get_bloginfo('name')
			)));
		}

		public static function get_seo_vessel_metadesc( $data ) {
			if (isset($data) && isset($data->BasicInfo)) {
				if (!isset($data->BasicInfo->ModelYear)) {
					$data->BasicInfo->ModelYear = '';
				}
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

				if ($this->option->use_boss_seo) {
					$yacht = $wp_query->queried_object->boss_data;

					if (isset($yacht->SEO->SEOPageTitle)) {

						$title = $yacht->SEO->SEOPageTitle;
						
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
					return $yacht->SEO->SEOMetaDescription;

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

		public function head_pagination_links() {
			global $post;

			$yacht_search_url = $this->option->getOption('yacht_search_url');	

			$wp_page_url = get_the_permalink();

			
			if (
				(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $yacht_search_url) !== false)
				||
				(strpos($post->post_content, '[/yatco-yachts-results]') !== false)
			) {
				$total_number_of_pages = get_transient('yatco_cache_max_number_of_yacht_pages');

				if (! $total_number_of_pages) {

					$yachts = $this->data->get_search([
						"page_index" => 1,
						"page_size" => 1,
					]);

					$total_number_of_pages = 0;

					if( isset($yachts->Count) ){
						$total_number_of_pages = ceil( $yachts->Count / 12 );
					}

					set_transient( 'yatco_cache_max_number_of_yacht_pages', $total_number_of_pages, 24 * HOUR_IN_SECONDS );

				}
							
				if (isset($_REQUEST['page_index'])) {
					$current_index = $_REQUEST['page_index'];

					if ($_REQUEST['page_index'] == $total_number_of_pages) {
						echo '<link rel="prev" href="'. $wp_page_url .'?page_index='. ($current_index-1) .'">';

					}
					elseif ($_REQUEST['page_index'] == 2 ) {

						echo '<link rel="prev" href="'. $wp_page_url .'">';
						echo '<link rel="next" href="'. $wp_page_url .'?page_index='. ($current_index+1) .'">';
						//echo '<link rel="canonical" href="'. $wp_page_url .'?page_index='. $current_index .'">';

					} 
					elseif ($_REQUEST['page_index'] > 2) {

						echo '<link rel="prev" href="'. $wp_page_url .'?page_index='. ($current_index-1) .'">';
						echo '<link rel="next" href="'. $wp_page_url .'?page_index='. ($current_index+1) .'">';
						//echo '<link rel="canonical" href="'. $wp_page_url .'?page_index='. ($current_index) .'">';

					}
				}
				else {

					$current_index = 1;

					echo '<link rel="next" href="'. $wp_page_url .'?page_index='. ($current_index+1) .'">';
					//echo '<link rel="canonical" href="'. $wp_page_url .'">';

				}
			}

		}

		public function meta_tag_yacht_vessel_id() {
			if (is_singular('yatco_yachts')) {

				global $post;

					$yacht = $post->boss_data;
					$yacht = $yacht->Result;

				echo '<meta name="yatco-vessel-id" content="'. $yacht->VesselID .'">';
				echo '<meta name="yatco-vessel-mlsid-id" content="'. $yacht->MLSID .'">';
			}
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

						//$url .= '?'.$_SERVER['QUERY_STRING'];
					
						$url = $https_current_url;
					}
					elseif (strpos($http_current_url, $yacht_search_url) !== false || strpos($https_current_url, $yacht_search_url) !== false) {

						$url = $https_current_url;

					}

				}
			}

			return $url;

		}

		public function update_og_url($url) {

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

					//$url .= '?'.$_SERVER['QUERY_STRING'];
				
					$url = $current_url;
				}
				elseif (strpos($http_current_url, $yacht_search_url) !== false || strpos($https_current_url, $yacht_search_url) !== false) {

					$url = $current_url;

				}

			}

			return $url;
		}

		public function oneurlnomatterwhat() {
			if ( is_singular('yatco_yachts') ) {

				global $post;

				$yacht = $post->boss_data;
				$yacht_url = $yacht->a_url;

				// If Vessel info exists, adjust yacht_url based on status
				if ( isset( $post->boss_data->Result->VesselID ) ) {
					$vessel = $this->data->get_vessel( $post->boss_data->Result->VesselID );

					if ( isset( $vessel->Result->VesselStatus ) && $vessel->Result->VesselStatus == 99 ) {
						// Force yacht_url to use /yacht/ instead of /sold-yacht/
						$yacht_url = str_replace('/sold-yacht/', '/yacht/', $yacht_url);
					}
				}

				$yacht_path   = trailingslashit( parse_url( $yacht_url, PHP_URL_PATH ) );
				$current_path = trailingslashit( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

				// Allow match even if current path contains "/sold-yacht/"
				if ( $yacht_path !== $current_path && strpos( $current_path, '/sold-yacht/' ) === false ) {
					wp_redirect( $yacht_url, 301 );
					exit;
				}
			}
		}


		public function filter_ld_json($data) {

			if (is_singular('yatco_yachts')) {
				global $post;
				
				$yacht = $post->boss_data;
				
				$data['name'] = $post->post_title;
				$data['url'] = $post->guid;
				
			}

	  		return $data;

		}

		public function just_return_boss_h($h) {
			global $wp_query;

			if ($this->option->use_boss_seo) {
				$yacht = $wp_query->queried_object->boss_data;

				if (isset($yacht->SEO->H1Tag)) {

					$h = $yacht->SEO->H1Tag;
					
				}

			}

			return $h;

		}


		public function just_return_boss_p($p) {

			global $wp_query;

			if ($this->option->use_boss_seo) {
				$yacht = $wp_query->queried_object->boss_data;

				if (isset($yacht->SEO->FirstParagraph)) {

					$p = $yacht->SEO->FirstParagraph;
					
				}

			}

			return $p;



		}
	}