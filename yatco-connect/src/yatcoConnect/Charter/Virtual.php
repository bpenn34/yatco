<?php 
	
	class yatcoConnect_Charter_Virtual extends yatcoConnect_Base_VirtualPost {	
		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->VirtualPostTypeURL = new yatcoConnect_VirtualPostTypeURL();

		}

		public function add_actions_and_filters() {

			add_action('init', [$this, 'url_rewrite_rules'], -1);

			add_filter('query_vars', [$this, 'query_vars'], 15, 1);

			add_filter('the_posts', [$this, 'the_posts'], 15, 2);
			
		}

		public function query_vars($vars) {
				
			$vars[] = "charter_id";
			$vars[] = "charter_name";
			$vars[] = "xml_charter_sitemap";

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
			$query = 'index.php?post_type=yatco_charters';
			foreach ($placeholders[1] as $index => $key) {
				if($key == 'VesselID'){
					$key = 'charter_id';
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

			$pattern = get_option('yatcoseo_seo_url_singleCharter'); // e.g., /boat/{VesselName}-{MLSID}

			if (!$pattern) {

				add_rewrite_rule('yacht-for-charter/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&charter_id=$matches[2]', 'top');
				
				add_rewrite_rule('yachts-for-charter/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&charter_id=$matches[2]', 'top');
			
			}else{

				$this->dynamically_generate_url_from_options( $pattern );

			}
			/*

			add_rewrite_rule('yachts/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&charter_id=$matches[2]', 'top');
			
			add_rewrite_rule('yacht/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&charter_id=$matches[2]', 'top');
			
			*/

			add_rewrite_rule('sitemap-charter.xml$', 'index.php?xml_charter_sitemap=true', 'top');
			
		}

		public function the_posts($posts, $this_query) {
			global $wp;

			if (count($posts) == 0 && $this_query->query_vars['post_type'] == 'yatco_charters') {

				if ($this_query->query_vars['charter_id'] == '') {
					
				}
				else if ($this_query->query_vars['charter_id'] != '' && is_numeric($this_query->query_vars['charter_id'])) {

					$charter = $this->data->get_charter_details($this_query->query_vars['charter_id']);

					//var_dump($charter);

					if (
						isset($charter) 
						&& 
						! isset($charter->Message)
					) {
						if ( ! isset($charter->from_cache) ) {
							$charter = apply_filters('wp_process_post_charter', $charter);
						}					

						$posts = $aYachtPost = array();

						if(is_object($charter)){
							
							$aYachtPost=$this->virtual_post(array(
								'post_title' => $charter->VesselName,
								'post_content' => $charter->VesselDescriptionShortDescription,
								'post_name' => trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($charter->VesselName)),'-'),
								'post_type' => 'yatco_charters',
								//'post_status' => 'draft',
								'guid' => $charter->a_url,

							));

							$aYachtPost->boss_data = $charter;
						}else{
							//There is issue with api. We are not getting response, 
							// so we entire few details what we have; just not to break the page and to hide errors

							//echo "<pre>"; print_r($this_query->query_vars);exit;
							$aYachtPost=$this->virtual_post(array(
								'post_title' => 'title : '.$this_query->query_vars['yacht_name'],
								'post_content' => '',
								'post_name' => trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($this_query->query_vars['yacht_name'])),'-'),
								'post_type' => 'yatco_charters',

							));

							$aYachtPost->boss_data = $charter;
						}

						$posts[]=$aYachtPost;

			            $this_query->is_page = FALSE;
		                $this_query->is_single = TRUE;
		                $this_query->is_home = FALSE;
		                $this_query->is_archive = FALSE;
		                $this_query->is_post_type_archive = FALSE;
		                $this_query->is_category = FALSE;
		                $this_query->is_search = FALSE;

						if(isset($aYachtPost->post_type)){
		                	$this_query->query_vars['post_type'] = $aYachtPost->post_type;
						}
		                $this_query->query_vars['pagename'] = '';
		               	
		                unset($this_query->query['error']);
		                $this_query->query_vars['error'] = '';
		                
		                $this_query->is_404 = FALSE;

		                do_action('ptrc_save_details', $charter, 'CharterDetails');
		                
					}
					else {
						$this_query->set_404();
						status_header(404);	
					}	
				}					
			}

		 	return $posts;
		}
		
		
	}