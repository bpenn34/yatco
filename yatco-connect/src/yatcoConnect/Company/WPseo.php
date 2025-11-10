<?php
	
	class yatcoConnect_Company_WPseo{
		
		public function __construct() {
			
			$this->data = new yatcoConnect_ApiToBoss();

			//$this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

			$this->option = new yatcoConnect_Options();

		}
			
		public function add_actions_and_filters() {
			// add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			add_filter('wpseo_title',  [$this, 'filter_seo_title']);
			add_filter('wpseo_metadesc',  [$this, 'filter_seo_metadesc']);

			add_filter('wpseo_canonical', [$this, 'filter_canonical'], 10, 1);

			add_action('wp_head', [$this, 'head_pagination_links']);

			add_action('template_redirect', [$this, 'oneurlnomatterwhat'] );

			add_filter('wpseo_schema_webpage', [$this, 'filter_ld_json'], 10, 1);

			add_filter('wpseo_breadcrumb_links', [$this, 'breadcrumb_links'] );

		}

		public function filter_canonical($url) {
			if (is_singular('yatco_companies')) {

				global $post;

				$company = $post->boss_data;

				return $company->a_url;

			}

			return $url;

		}

		public function head_pagination_links() {
			global $post;

			$_search_url = $this->option->getOption('company_search_url');	

			$wp_page_url = get_the_permalink();

			
			
			if (
				isset($_search_url) && $_search_url != ''
				&&
				(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_search_url) !== false)
				||
				( isset( $post->post_content ) && strpos($post->post_content, '[/yatco-company-results]') !== false)
			) {
							
				$total_number_of_pages = get_transient('yatco_cache_max_number_of_company_pages');

				if (! $total_number_of_pages) {

					$results = $this->data->get_company_search([
						"page_index" => 1,
						"page_size" => 1,
					]);

					//var_dump($results);

					$total_number_of_pages = ceil($results->Count / 12 );

					set_transient( 'yatco_cache_max_number_of_company_pages', $total_number_of_pages, 24 * HOUR_IN_SECONDS );

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
			if (is_singular('yatco_companies')) {

				global $post;

				$data = $post->boss_data;

				$_url=$data->a_url;

				$right_path=(parse_url($_url)['path']);
				$current_path=($_SERVER['REQUEST_URI']);

				if (
					$right_path != $current_path 
				) {
					wp_redirect($_url, 301);
				}
			}
		}

		public function filter_seo_title($title) {
			global $wp_query;

			if (is_singular('yatco_companies')) {
				$company = $wp_query->queried_object->boss_data;

				$title = htmlentities(
					sprintf(
						'%s %s (%s) | %s',
						$company->CompanyName,
						apply_filters('yatco_full_address', $company, false, true),
						(string) $company->CompanyID,
						get_bloginfo('name')
					)
				);
			}

			return $title;
		}

		public function filter_seo_metadesc($metadesc) {
			global $wp_query;

			if (is_singular('yatco_companies')) {
				$company = $wp_query->queried_object->boss_data;

				$metadesc = sprintf(
					'%s (%s) is a yacht brokerage in %s specialized in %s',
					$company->CompanyName,
					(string) $company->CompanyID,
					apply_filters('yatco_full_address', $company, false, true),
					$company->Yachts->Results[0]->MainCategoryText
				);
			}

			return $metadesc;	
		}

		public function filter_ld_json($data) {
			if (is_singular('yatco_companies')) {
				global $post;

				$company = $post->boss_data;

				$data['name'] = $post->post_title;
				$data['url'] = $post->guid;

			}

			return $data;
		}

		public function breadcrumb_links( $links ) {
			global $wp_query;

			if (is_singular('yatco_companies')) {
				$company_post = $wp_query->queried_object;

				$links = [];
	
			    $links[] = [
			        'url' => home_url(),
			        'text' => 'Home',
			    ];
		
			    $links[] = [
			        'url' => $company_post->boss_data->a_url,
			        'text' => $company_post->post_title
			    ];
			}

		    return $links;
		}
	}