<?php 
	
	class yatcoConnect_Brokers_Virtual extends yatcoConnect_Base_VirtualPost {	
		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();

		}


		public function add_actions_and_filters() {

			add_action('init', [$this, 'url_rewrite_rules'], -1);

			add_filter('query_vars', [$this, 'query_vars'], 15, 1);

			add_filter('the_posts', [$this, 'the_posts'], 15, 2);
			
		}

		public function query_vars($vars) {
				
			$vars[] = 'broker_id';
			$vars[] = 'broker_name';
			$vars[] = 'xml_brokers_sitemap';

			return $vars;
		
		}

		public function url_rewrite_rules() {

			add_rewrite_rule('brokers/(\d+)/?$', 'index.php?post_type=yatco_brokers&broker_id=$matches[1]', 'top');
			
			add_rewrite_rule('broker/(\d+)/?$', 'index.php?post_type=yatco_brokers&broker_id=$matches[1]', 'top');

			add_rewrite_rule('broker/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_brokers&broker_name=$matches[1]&broker_id=$matches[2]', 'top');
			
			add_rewrite_rule('sitemap-brokers.xml$', 'index.php?xml_brokers_sitemap=true', 'top');
		}

		public function the_posts($posts, $this_query) {
			global $wp, $wp_query;

			if (count($posts) == 0 && $this_query->query_vars['post_type'] == 'yatco_brokers') {
				if ($this_query->query_vars['broker_id'] == '') {


				}
				else if ($this_query->query_vars['broker_id'] != '' && is_numeric($this_query->query_vars['broker_id'])) {

					$broker = $this->data->get_broker($this_query->query_vars['broker_id']);

					if (
						isset($broker->isActive) && $broker->isActive == true
						&&
						isset($broker->Company->CompanyID) && $broker->Company->CompanyID != 0	
					) {
						if ( ! isset($broker->from_cache) ) {
							$broker = apply_filters('wp_process_post_broker', $broker);
							
						}

						$posts=array();

						$aBrokerPost=$this->virtual_post(array(

							'post_title' => $broker->FirstName.' '.$broker->LastName,
							'post_content' => '',
							'post_name' => $this_query->query_vars['broker_id'],
							'post_type' => 'yatco_brokers',
							'guid' =>  $broker->a_url,

						));

						$aBrokerPost->boss_data = $broker;

						$posts[]=$aBrokerPost;

			            $this_query->is_page = FALSE;
		                $this_query->is_single = TRUE;
		                $this_query->is_home = FALSE;
		                $this_query->is_archive = FALSE;
		                $this_query->is_post_type_archive = FALSE;
		                $this_query->is_category = FALSE;
		                $this_query->is_search = FALSE;

		                //$this_query->query_vars['yatco_yachts'] = $this_query->query_vars['yacht_name'] .'-'. $this_query->query_vars['yacht_id'];
		                $this_query->query_vars['post_type'] = $aBrokerPost->post_type;
		                $this_query->query_vars['pagename'] = '';
		               	
		                unset($this_query->query['error']);
		                $this_query->query_vars['error'] = '';
		                
		                $this_query->is_404 = FALSE;

						do_action('ptrc_save_details', $broker, 'BrokerDetails');
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