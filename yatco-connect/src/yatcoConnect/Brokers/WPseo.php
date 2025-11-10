<?php
	
	class yatcoConnect_Brokers_WPseo{

		public function __construct() {
			
			$this->data = new yatcoConnect_ApiToBoss();
			
			$this->option = new yatcoConnect_Options();

			// $this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();
						
		}
	
		public function add_actions_and_filters() {
			add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			add_filter('wpseo_title',  [$this, 'filter_seo_title']);

			add_filter('wpseo_metadesc',  [$this, 'filter_seo_metadesc']);

			add_filter('wpseo_canonical', [$this, 'filter_canonical'], 10, 1);

			add_action('wp_head', [$this, 'head_pagination_links']);

			add_filter('wpseo_schema_webpage', [$this, 'filter_ld_json'], 10, 1);

			add_action('template_redirect', [$this, 'oneurlnomatterwhat'] );

			add_filter('wpseo_breadcrumb_links', [$this, 'breadcrumb_links'] );
		}
		
		public function filter_wp_title( $title, $sep, $sep_location ) {
			global $wp_query;

			if (is_singular('yatco_brokers')) {
				$results = $wp_query->queried_object->boss_data;
			
				$title = htmlentities(self::get_seo_vessel_title($results));
			}

			return $title;
		}

		
		public static function get_seo_vessel_title( $data ) {
			if (isset($data->Office) && isset($data->Office->Country)) {

				return trim(preg_replace('/\s\s+/', ' ',
					sprintf('Yacht Broker %s %s - %s | %s',
						$data->FirstName,
						$data->LastName,

						apply_filters('yatco_full_address', $data->Office, false, true),			
						get_bloginfo('name')
					)));

			}
			else {
				return trim(preg_replace('/\s\s+/', ' ',
					sprintf('Yacht Broker %s %s - %s | %s',
						$data->FirstName,
						$data->LastName,

						apply_filters('yatco_full_address', $data->Company, false, true),
						get_bloginfo('name')
					)));
			}
		}

		public static function get_seo_vessel_metadesc( $data ) {
			if (isset($data)) {
				if (isset($data->Office) && isset($data->Office->Country)) {
					return trim(preg_replace('/\s\s+/', ' ',
						sprintf('%s %s is yacht broker for %s in %s',
							$data->FirstName,
							$data->LastName,

							$data->Yachts->Results[0]->MainCategoryText,

							apply_filters('yatco_full_address', $data->Office, false, true)
						)));
				}
				else {
					return trim(preg_replace('/\s\s+/', ' ',
						sprintf('%s %s is yacht broker for %s in %s',
							$data->FirstName,
							$data->LastName,

							$data->Yachts->Results[0]->MainCategoryText,
							apply_filters('yatco_full_address', $data->Company, false, true)
						)));
				}
			} 

			return "";

		}

		public function filter_seo_title($title) {
			global $wp_query;

			if (is_singular('yatco_brokers')) {
				$results = $wp_query->queried_object->boss_data;

				$title = htmlentities(self::get_seo_vessel_title($results));
			}

			return $title;
		}

		public function filter_seo_metadesc($metadesc) {
			global $wp_query;

			if (is_singular('yatco_brokers')) {
				$results = $wp_query->queried_object->boss_data;

				$metadesc = htmlentities(self::get_seo_vessel_metadesc($results));
			}

			return $metadesc;
		}

		public function filter_canonical($url) {
			if (is_singular('yatco_brokers')) {

				global $post;

				$broker = $post->boss_data;

				return $broker->a_url;

			}
			else {

				$current_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				$http_current_url = 'http://'.$current_url;
				$https_current_url = 'https://'.$current_url;

				$broker_search_url = $this->option->getOption('broker_search_url');	
				
				if (
					(strpos($http_current_url, $broker_search_url) !== false || strpos($https_current_url, $broker_search_url) !== false)
					&&
					count($_GET) > 0
				) {
					$url .= '?'.http_build_query($_GET);
				}
			}

			return $url;

		}

		public function head_pagination_links() {
			global $post;

			$_search_url = $this->option->getOption('broker_search_url');	

			$wp_page_url = get_the_permalink();

			
			
			if (
				isset($_search_url) && $_search_url != ''
				&&
				(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_search_url) !== false)
				||
				( isset( $post->post_content ) && strpos($post->post_content, '[/yatco-broker-finder]') !== false)
			) {

				$total_number_of_pages = get_transient('yatco_cache_max_number_of_brokers_pages');

				if (! $total_number_of_pages) {

					$results = $this->data->get_broker_finder([
						"page_index" => 1,
						"page_size" => 1,
					]);

					//var_dump($results);

					$total_number_of_pages = ceil($results->Count / 12 );

					set_transient( 'yatco_cache_max_number_of_brokers_pages', $total_number_of_pages, 24 * HOUR_IN_SECONDS );

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
					// echo '<link rel="canonical" href="'. $wp_page_url .'">';

				}
			}

		}

		public function oneurlnomatterwhat() {
			if (is_singular('yatco_brokers')) {

				global $post;

				$broker = $post->boss_data;

				$broker_url=$broker->a_url;

				$broker_path=(parse_url($broker_url)['path']);
				$current_path=($_SERVER['REQUEST_URI']);

				if ($broker_path != $current_path) {
					wp_redirect($broker_url, 301);
				}
			}
		}

		public function filter_ld_json($data) {
			if (is_singular('yatco_brokers')) {
				global $post;

				$broker = $post->boss_data;

				$data['name'] = $post->post_title;
				$data['url'] = $post->guid;

			}

			return $data;

		}

		public function breadcrumb_links( $links ) {
			global $post;

			if (is_singular('yatco_brokers')) {
				$links = [];
	
			    $links[] = [
			        'url' => home_url(),
			        'text' => 'Home',
			    ];
		

			    if (isset($post->boss_data->Company->CompanyName) && $post->boss_data->Company->CompanyName != '') {

				    $links[] = [
				        'url' => $post->boss_data->Company->a_url,
				        'text' => $post->boss_data->Company->CompanyName,
				    ];

			    }
			    else {

			    	$links[] = [
				        'url' => $this->option->getOption('broker_search_url'),
				        'text' => 'Broker Finder',
				    ];

			    }
		
			    $links[] = [
			        'url' => $post->boss_data->a_url,
			        'text' => $post->post_title
			    ];
			}

		    return $links;
		}


	} 