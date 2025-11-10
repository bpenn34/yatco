<?php 
	class yatcoConnect_Charter_SiteMap {

		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			
		}

		public function add_actions_and_filters() {

			add_filter('template_redirect', [$this, 'xml'], 1);

		}

		public function xml() {

			global $wp_query;
			
			if(! empty($wp_query->query_vars["xml_charter_sitemap"]) ) {
				$wp_query->is_404 = false;
				
				header('Content-type: application/xml; charset=utf-8');

				$charters = $this->data->get_search_charter([
					//'records' => -1,
					'page_size' => -1,
					//'offset' => 0,
				]);

				//var_dump($charters);

				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
					foreach ($charters->Results as $charter) {
						$mod_date = strtotime($charter->ModifyDate);

						echo '<url>';
							echo '<loc>'. htmlspecialchars($charter->a_url, ENT_QUOTES, 'UTF-8') .'</loc>';
							echo '<lastmod>'. date('Y-m-d', $mod_date).'</lastmod>';
							echo '<changefreq>daily</changefreq>';
							echo '<priority>0.5</priority>';
						echo '</url>';
					}
				echo '</urlset>';

				exit;
			}
		}
	}