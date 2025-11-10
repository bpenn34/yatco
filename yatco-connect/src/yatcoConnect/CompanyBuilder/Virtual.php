<?php 
	
	class yatcoConnect_CompanyBuilder_Virtual extends yatcoConnect_Base_VirtualPost {	
		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();

		}

		public function add_actions_and_filters() {

			add_action('init', function() {

				add_rewrite_rule('company-builder/(\d+)/?$', 'index.php?post_type=yatco_c_builder&company_builder_id=$matches[1]', 'top');
				
				add_rewrite_rule('company-builder/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_c_builder&company_builder_name=$matches[1]&company_builder_id=$matches[2]', 'top');			
					
				// add_rewrite_rule('sitemap-yachts.xml$', 'index.php?xml_yatchs_sitemap=true', 'top');
				
			}, -1);

			add_filter('query_vars', [$this, 'query_vars'], 15, 1);

			add_filter('the_posts', [$this, 'the_posts'], 15, 2);

			
		}

		public function query_vars($vars) {
				
			$vars[] = 'company_builder_id';
			$vars[] = 'company_builder_name';
			$vars[] = 'xml_company_builder_sitemap';

			return $vars;
		
		}

		public function the_posts($posts, $this_query) {

			if (count($posts) == 0 && isset($this_query->query_vars['company_builder_id'])) {
				if ($this_query->query_vars['company_builder_id'] == '') {

				}
				else if (
					$this_query->query_vars['company_builder_id'] != ''
				) {
					
					$company = $this->data->get_company($this_query->query_vars['company_builder_id']);

					//3443

					if (isset($company) && ! isset($company->Message) && in_array(3443, $company->CompanyTypeIDs)) {
						$company = apply_filters('wp_process_post_company_builder', $company);
						
						$posts=array();

						$aPost=$this->virtual_post(array(

							'post_title' => $company->CompanyName,
							'post_content' => $company->AboutCompany,
							'post_name' => $this_query->query_vars['company_builder_id'],
							'post_type' => 'yatco_c_builder',
							'guid' =>  $company->a_url,

						));

						$aPost->boss_data = $company;

						$posts[]=$aPost;

			            $this_query->is_page = FALSE;
		                $this_query->is_single = TRUE;
		                $this_query->is_home = FALSE;
		                $this_query->is_archive = FALSE;
		                $this_query->is_post_type_archive = FALSE;
		                $this_query->is_category = FALSE;
		                $this_query->is_search = FALSE;

		                //$this_query->query_vars['yatco_yachts'] = $this_query->query_vars['yacht_name'] .'-'. $this_query->query_vars['yacht_id'];
		                $this_query->query_vars['post_type'] = $aPost->post_type;
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