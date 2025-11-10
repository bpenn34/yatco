<?php 
	
	class yatcoConnect_Yachts_Virtual extends yatcoConnect_Base_VirtualPost {	
		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}

		public function add_actions_and_filters() {

			add_action('init', [$this, 'url_rewrite_rules'], -1);

			add_filter('query_vars', [$this, 'query_vars'], 15, 1);

			add_filter('the_posts', [$this, 'the_posts'], 15, 2);

			add_filter('template_redirect', [$this, 'yacht_url_redirect'], 15, 0);
			
		}

		public function query_vars($vars) {
				
			$vars[] = "yacht_id";
			$vars[] = "yacht_name";
			$vars[] = "xml_yachts_sitemap";

			return $vars;
		
		}

		public function dynamically_generate_url_from_options( $pattern ){
			
			// Convert placeholders to regex capture groups
			$regex = preg_replace_callback('/{(\w+)}/', function ($matches) {
				return '([^/]+)'; // Each placeholder becomes a regex group
			}, trim($pattern, '/'));

			// Match the values in the same order as placeholders
			preg_match_all('/{(\w+)}/', $pattern, $placeholders);

			// Construct the query string based on matches
			$query = 'index.php?post_type=yatco_yachts';
			foreach ($placeholders[1] as $index => $key) {
				if($key == 'MLSID'){
					$key = 'yacht_id';
				}
				if($key =='VesselName'){
					$key = 'yacht_name';
				}
				$query .= '&' . strtolower($key) . '=$matches[' . ($index + 1) . ']';
			}

			// Add the rewrite rule
			add_rewrite_rule($regex . '/?$', $query, 'top');
		}

		public function url_rewrite_rules() {
			//add_rewrite_rule('yachts/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&yacht_id=$matches[2]', 'top');

			$pattern = get_option('yatcoseo_seo_url_singleYacht'); // e.g., /boat/{VesselName}-{MLSID}

			if (!$pattern) {
				add_rewrite_rule('yacht/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&yacht_id=$matches[2]', 'top');
				add_rewrite_rule(
					'sold-yacht/([^/]+?)-(\d+)/?$',
					'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&yacht_id=$matches[2]',
					'top'
				);
			}else{
				$this->dynamically_generate_url_from_options( $pattern );
			}
			
			
			add_rewrite_rule('sitemap-yachts.xml$', 'index.php?xml_yachts_sitemap=true', 'top');
			
		}

		public function the_posts($posts, $this_query) {
			
			global $wp, $wp_query;

			if (count($posts) == 0 && $this_query->query_vars['post_type'] == 'yatco_yachts') {
				
				if ($this_query->query_vars['yacht_id'] == '') {
					
				}
				else if ($this_query->query_vars['yacht_id'] != '' && is_numeric($this_query->query_vars['yacht_id'])) {

					$vessel = $this->data->get_vessel($this_query->query_vars['yacht_id']);

					if (
						isset($vessel) 
						&& 
						$vessel !== -1 && $vessel !== "-1"
						&& 
						! isset($vessel->Message)
						&&
						isset($vessel->Result)
						&&
						$vessel->Result->VesselID != 0
					) {

						if ( ! isset($vessel->from_cache) ) {
							$vessel = apply_filters('wp_process_post_yacht', $vessel);
			
						}

						$posts=array();

						$aYachtPost=$this->virtual_post(array(
							'post_title' => $vessel->BasicInfo->BoatName,
							'post_content' => (isset($vessel->MiscInfo->VesselDescriptionShortDescription))?$vessel->MiscInfo->VesselDescriptionShortDescription:'',
							'post_name' => $this_query->query_vars['yacht_name'] .'-'. $this_query->query_vars['yacht_id'],
							'post_type' => 'yatco_yachts',
							'guid' => $vessel->a_url,
						));

						$aYachtPost->boss_data = $vessel;

						$posts[]=$aYachtPost;

			            $this_query->is_page = FALSE;
		                $this_query->is_single = TRUE;
		                $this_query->is_home = FALSE;
		                $this_query->is_archive = FALSE;
		                $this_query->is_post_type_archive = FALSE;
		                $this_query->is_category = FALSE;
		                $this_query->is_search = FALSE;

		                //$this_query->query_vars['yatco_yachts'] = $this_query->query_vars['yacht_name'] .'-'. $this_query->query_vars['yacht_id'];
		                $this_query->query_vars['post_type'] = $aYachtPost->post_type;
		                $this_query->query_vars['pagename'] = '';
		               	
		                unset($this_query->query['error']);
		                $this_query->query_vars['error'] = '';
		                
		                $this_query->is_404 = FALSE;

						do_action('ptrc_save_details', $vessel, 'YachtDetails');

						
						//if( $vessel->Result->VesselStatus == 99 ){

							//echo "hi";exit;

						//}

					}
					else {
						$this_query->set_404();
						status_header(404);	
					}	
				}					
			}

		 	return $posts;
		}
		
		public function yacht_url_redirect() {
			global $post;

			if ( isset( $post->boss_data->Result->VesselID ) ) {
				$vessel = $this->data->get_vessel( $post->boss_data->Result->VesselID );

				if ( isset( $vessel->Result->VesselStatus ) ) {

					// Decide target prefix
					$target_prefix = ( $vessel->Result->VesselStatus == 99 ) ? 'sold-yacht/' : 'yacht/';

					// Base slug from post_name
					$slug = $post->post_name;

					// Ensure slug ends with vesselID only once
					if ( !preg_match( '/-' . $vessel->Result->VesselID . '$/', $slug ) ) {
						$slug .= '-' . $vessel->Result->VesselID;
					}

					// Build final redirect URL
					$redirect_url = home_url( '/' . $target_prefix . $slug . '/' );

					// Current request path (relative, like /yacht/boat-123/)
					$current_path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
					$redirect_path = parse_url( $redirect_url, PHP_URL_PATH );

					// If already correct, stop
					if ( trailingslashit( $current_path ) === trailingslashit( $redirect_path ) ) {
						return;
					}

					// Debug log
					//error_log("Redirecting yacht → {$redirect_url}");

					// Redirect once
					wp_redirect( $redirect_url, 301 );
					exit;
				}
			}
		}

	}