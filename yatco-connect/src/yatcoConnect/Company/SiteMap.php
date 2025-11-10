<?php 
	class yatcoConnect_Company_SiteMap {

		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			$this->params = new yatcoConnect_ApiToBossCompanyParams();
			
		}

		public function add_actions_and_filters() {

			add_filter('template_redirect', [$this, 'xml'], 1);

		}

		public function xml() {

			global $wp_query;
			
			if(! empty($wp_query->query_vars["xml_company_sitemap"]) ) {
				$wp_query->is_404 = false;
				
				header('Content-type: application/xml; charset=utf-8');

				$companies = $this->data->get_company_search([
					'page_size' => -1,
					'isActive' => 1,
					'ExcludeCompanyIDs' => $this->params->ExcludeCompanyIDs,
				]);
				
				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
					foreach ($companies->Results as $company) {

						$mod_date =  strtotime($company->ModifiedDate);

						echo '<url>';
							echo '<loc>'. htmlspecialchars($company->a_url, ENT_QUOTES, 'UTF-8') .'</loc>';
							echo '<lastmod>'. date('Y-m-d', $mod_date) .'</lastmod>';

							echo '<changefreq>daily</changefreq>';
							echo '<priority>0.5</priority>';
						echo '</url>';
					}
				echo '</urlset>';
				
				exit;
			}
		}
	}