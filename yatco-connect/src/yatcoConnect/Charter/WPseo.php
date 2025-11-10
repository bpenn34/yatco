<?php

	class yatcoConnect_Charter_WPseo{

		public function __construct() {
			
			$this->data = new yatcoConnect_ApiToBoss();
				
			$this->option = new yatcoConnect_Options();

			$this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}
			
		public function add_actions_and_filters() {

			// add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			add_filter('wpseo_title',  [$this, 'filter_seo_title']);
			add_filter('wpseo_metadesc',  [$this, 'filter_seo_metadesc']);

			add_filter('wpseo_canonical', [$this, 'filter_canonical'], 10, 1);

			add_action('template_redirect', [$this, 'oneurlnomatterwhat'] );

			add_action('wp_head', [$this, 'head_pagination_links']);

			add_filter('wpseo_breadcrumb_links', [$this, 'breadcrumb_links'] );

			//add_filter( 'wpseo_robots', [$this, 'remove_robots'] );
		}

		public function remove_robots( $robots ) {
			global $wp_query;

			if (is_singular('yatco_charters')) {				
				return 'noindex,nofollow';
			} else {
				return $robots;
			}

		}

		public function filter_canonical($url) {
			if (is_singular('yatco_charters')) {

				global $post;

				$charter = $post->boss_data;

				return $this->VirtualPostTypeURL->wp_charter_url($charter);

			}

			return $url;

		}

		public function oneurlnomatterwhat() {
			if (is_singular('yatco_charters')) {

				global $post;

				$yacht = $post->boss_data;

				$yacht_url = '';

				if(isset($yacht->a_url)){

					$yacht_url = $yacht->a_url;
					
				}

				$yacht_path=(parse_url($yacht_url)['path']);
				$current_path=($_SERVER['REQUEST_URI']);

				if (
					$yacht_path != $current_path 
					//&& 
					//($post->boss_data->MiscInfo->Status == 1 || $post->boss_data->MiscInfo->Status == 4)
				) {
					wp_redirect($yacht_url, 301);
				}

			}
		}

		public function head_pagination_links() {
			global $post;

			$_search_url = $this->option->getOption('charter_search_url');	

			$wp_page_url = get_the_permalink();

			if (
				isset($_search_url) && $_search_url != ''
				&&
				(
					(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_search_url) !== false)
					||
					(strpos($post->post_content, '[/yatco-charters-results]') !== false)
				)
			) {
				
				$total_number_of_pages = get_transient('yatco_cache_max_number_of_charter_pages');

				if (! $total_number_of_pages) {

					$results = $this->data->get_search_charter([
						"page_index" => 1,
						"page_size" => 1,
					]);

					//var_dump($results);

					$total_number_of_pages = ceil($results->Count / 12 );

					set_transient( 'yatco_cache_max_number_of_charter_pages', $total_number_of_pages, 24 * HOUR_IN_SECONDS );

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

		public function filter_seo_title($title) {
			// {Charter Yacht Name} - {Charter Type} {Length} Yacht for Charter | YATCO
			global $wp_query;

			if (is_singular('yatco_charters')) {
				$charter = $wp_query->queried_object->boss_data;

				$title = htmlentities(
					sprintf(
						'%s - %s %s Yacht for Charter | %s',
						$charter->VesselName,
						$charter->VesselCategoryText,
						$charter->LOA_Format,

						get_bloginfo('name')
					)
				);
			}

			return $title;
		}

		public function filter_seo_metadesc($metadesc) {
			// {Charter Yacht Name} is a {builder} {length} {yacht type} available for {charter type} charter, built in {year}, price ranges from {price}, allowing up to {number of guests} guests. More details.

			global $wp_query;

			if (is_singular('yatco_charters')) {
				$charter = $wp_query->queried_object->boss_data;

				$metadesc = sprintf(
					'%s is a %s %s %s available for %s charter, built in %s, price ranges from %s, allowing up to %s guests. More details.',
					$charter->VesselName,
					$charter->BuilderName,
					$charter->LOA_Format,
					$charter->VesselCategoryText,
					$charter->VesselType,
					(isset($charter->RefitYear))?$charter->RefitYear:'', // just removing warnings
					$charter->RatesList[0]->HighRateFormat,
					$charter->NumberOfGuests
				);
			}

			return $metadesc;	
		}

		public function breadcrumb_links( $links ) {
			global $wp_query;

			if (is_singular('yatco_charters')) {
				$vessel_post = $wp_query->queried_object;

				$links = [];
	
			    $links[] = [
			        'url' => home_url(),
			        'text' => 'Home',
			    ];

			    $links[] = [
			        'url' => $this->option->getOption('charter_search_url'),
			        'text' => 'Charter Search',
			    ];

			    $links[] = [
			        'url' => $vessel_post->boss_data->a_url,
			        'text' => $vessel_post->post_title
			    ];
			}

		    return $links;
		}
	}