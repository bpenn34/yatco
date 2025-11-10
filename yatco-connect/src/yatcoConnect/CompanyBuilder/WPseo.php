<?php
	
	class yatcoConnect_CompanyBuilder_WPseo{

		const QUERY_VAR_PREFIX = 'company_builder';

		public function __construct() {
			
			// $this->data = new yatcoConnect_ApiToBoss();

			$this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}
			
		public function add_actions_and_filters() {
			// call our custom wp_title (title tag) filter, with normal (10) priority, and 3 args
			// add_filter( 'wp_title', [$this, 'filter_wp_title'], 10, 3 );

			add_action( 'wpseo_add_opengraph_images', [$this, 'add_images'] );

			// call our custom wpseo_title (in page title) filter, with normal (10) priority, and 2 args
			add_filter('wpseo_title',  [$this, 'filter_seo_title']);

			// call our custom wpseo_title (in page title) filter, with normal (10) priority, and 2 args
			add_filter('wpseo_metadesc',  [$this, 'filter_seo_metadesc']);

			add_filter('wpseo_canonical', [$this, 'filter_canonical'], 10, 1);

			add_action('template_redirect', [$this, 'oneurlnomatterwhat'] );

			add_filter('wpseo_schema_webpage', [$this, 'filter_ld_json'], 10, 1);

			add_filter('wpseo_breadcrumb_links', [$this, 'breadcrumb_links'] );

		}

		public function add_images($object) {
			global $wp_query;

			$v_id = self::QUERY_VAR_PREFIX . '_id';

			if (isset($wp_query->query_vars[$v_id])) {
				$company = $wp_query->queried_object->boss_data;

				$image = $company->ImageMediumUrl;

				$object->add_image( $image );
			}
		}

		public function filter_canonical($url) {
			if (is_singular('yatco_c_builder')) {

				global $post;

				$company = $post->boss_data;

				return $this->VirtualPostTypeURL->wp_company_builder_url($company);

			}

			return $url;

		}

		public function oneurlnomatterwhat() {
			if (is_singular('yatco_c_builder')) {

				global $post;

				$data = $post->boss_data;

				$_url=$this->VirtualPostTypeURL->wp_company_builder_url($data);

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

			$c_id = self::QUERY_VAR_PREFIX . '_id';

			if (isset($wp_query->query_vars[$c_id])) {
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

			$c_id = self::QUERY_VAR_PREFIX . '_id';

			if (isset($wp_query->query_vars[$c_id])) {
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

			$v_id = self::QUERY_VAR_PREFIX . '_id';

			if (isset($wp_query->query_vars[$v_id])) {
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