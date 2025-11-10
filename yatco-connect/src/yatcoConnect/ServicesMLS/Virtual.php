<?php 
	
	class yatcoConnect_ServicesMLS_Virtual extends yatcoConnect_Base_VirtualPost {	
		
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
				
			$vars[] = "services_mls_id";

			return $vars;
		
		}

		public function url_rewrite_rules() {
			add_rewrite_rule('Services/(\d+)/?$', 'index.php?post_type=yatco_services_mls&services_mls_id=$matches[1]', 'top');
		}

		public function the_posts($posts, $this_query) {
			global $wp, $wp_query;

			if (count($posts) == 0 && $this_query->query_vars['post_type'] == 'yatco_services_mls') {
				if ($this_query->query_vars['services_mls_id'] == '') {
					
				}
				else if ($this_query->query_vars['services_mls_id'] != '' && is_numeric($this_query->query_vars['services_mls_id'])) {

					$member = $this->data->member_details($this_query->query_vars['services_mls_id']);

					/*var_dump($this_query->query_vars['services_mls_id']);
					var_dump($member);*/

					if (
						isset($member) 
						&& 
						$member !== -1 && $member !== "-1"
						&& 
						! isset($member->Message)
					) {
						//var_dump($vessel);

						//$vessel = apply_filters('wp_process_post_yacht', $vessel);

						$posts=array();

						$aMemberPost=$this->virtual_post(array(
							'post_title' => $member->CompanyName,
							'post_content' => $member->Description,
							'post_name' => 'okay',
							'post_type' => 'yatco_services_mls',
							'guid' => '',
						));

						$aMemberPost->boss_data = $member;

						$posts[]=$aMemberPost;

			            $this_query->is_page = FALSE;
		                $this_query->is_single = TRUE;
		                $this_query->is_home = FALSE;
		                $this_query->is_archive = FALSE;
		                $this_query->is_post_type_archive = FALSE;
		                $this_query->is_category = FALSE;
		                $this_query->is_search = FALSE;

		                //$this_query->query_vars['yatco_yachts'] = $this_query->query_vars['yacht_name'] .'-'. $this_query->query_vars['yacht_id'];
		                $this_query->query_vars['post_type'] = $aMemberPost->post_type;
		                $this_query->query_vars['pagename'] = '';
		               	
		                unset($this_query->query['error']);
		                $this_query->query_vars['error'] = '';
		                
		                $this_query->is_404 = FALSE;
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