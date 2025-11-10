<?php 
	class yatcoConnect_Brokers_SiteMap {

		
		public function __construct() {

			$this->virtual_post_type_url = new yatcoConnect_VirtualPostTypeURL();
			$this->data = new yatcoConnect_ApiToBoss();
			
		}

		public function add_actions_and_filters() {

			add_filter('template_redirect', [$this, 'xml'], 1);
			
			// add_filter('template_redirect', [$this, '_404'], 1);

		}

		public function xml() {

			global $wp_query;
			
			if(! empty($wp_query->query_vars["xml_brokers_sitemap"]) ) {
				$wp_query->is_404 = false;
				
				header('Content-type: application/xml; charset=utf-8');

				// $brokers = $this->data->call_api('GET', '/Common/Account/Brokers/0');
				$brokers = $this->data->call_api('POST', '/Common/Account/Brokers/Active', [
					'records' => -1,
					'GetForSaleStats' => 0,
					'GetCompanyInfo' => 1,
					'GetCharterStats' => 0,
					'LicenseTypeIDs' => [2,3],
				]);

				$brokers = $brokers->Results;

				foreach ($brokers as $broker) {

					$broker->a_url = $this->virtual_post_type_url->wp_broker_url($broker, $broker->Company);

				}

				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
					foreach ($brokers as $broker) {
						$mod_date = strtotime($broker->ModifiedDate);

						echo '<url>';
							echo '<loc>'. htmlspecialchars($broker->a_url, ENT_QUOTES, 'UTF-8') .'</loc>';
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