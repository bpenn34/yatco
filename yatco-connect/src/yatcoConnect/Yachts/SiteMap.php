<?php 
	class yatcoConnect_Yachts_SiteMap {

		
		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			
			$this->options = new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('template_redirect', [$this, 'xml'], 1);
			
			add_filter('template_redirect', [$this, '_404'], 1);

			// add_filter('wpseo_sitemap_index', [$this, 'add_to_yoast_index']);

		}

		public function xml() {

			global $wp_query;
			
			if (! empty($wp_query->query_vars["xml_yachts_sitemap"]) ) {

				$wp_query->is_404 = false;
				
				header('Content-type: application/xml; charset=utf-8');

				$params = [
					'records' => -1,
					'page_index' => 1,
				];

				if ($this->options->is_brokerage_site && $this->options->getOption('override_leads_company_id') != '') {
					$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
				}

				$yachts = $this->data->get_search($params);
				
				echo '<?xml version="1.0" encoding="UTF-8"?>';

				echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
					foreach ($yachts->Results as $yacht) {

						$yacht_date = strtotime($yacht->ModifiedDate);

						echo '<url>';
							echo '<loc>'. htmlspecialchars($yacht->a_url, ENT_QUOTES, 'UTF-8') .'</loc>';
							echo '<lastmod>'. date('Y-m-d', $yacht_date) . '</lastmod>';
							echo '<changefreq>daily</changefreq>';
							echo '<priority>0.8</priority>';
						echo '</url>';
					}
				
				$params['Concept']=true;

				$yachts_concept = $this->data->get_search($params);

					foreach ($yachts_concept->Results as $yacht) {

						$yacht_date = strtotime($yacht->ModifiedDate);

						echo '<url>';
							echo '<loc>'. htmlspecialchars($yacht->a_url, ENT_QUOTES, 'UTF-8') .'</loc>';
							echo '<lastmod>'. date('Y-m-d', $yacht_date) . '</lastmod>';
							echo '<changefreq>daily</changefreq>';
							echo '<priority>0.8</priority>';
						echo '</url>';
					}
					
				echo '</urlset>';

				exit;
			}
		}

		public function _404() {

			global $wp_query;

			if ($wp_query->is_404() && $wp_query->get('yacht_name') != "") {
				wp_redirect( $this->options->getOption('yacht_search_url') );
				exit;

			}

		}

		public function add_to_yoast_index() {
			global $wpseo_sitemaps;
			//$date = $wpseo_sitemaps->get_last_modified('page');

			$smp = '';

		    $smp .= '<sitemap>' . "\n";
				$smp .= '<loc>' . site_url() .'/?xml_yachts_sitemap=true</loc>' . "\n";
				//$smp .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
			$smp .= '</sitemap>' . "\n";

			return $smp;
		}

	}
