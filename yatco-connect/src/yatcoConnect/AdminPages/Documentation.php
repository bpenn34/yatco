<?php
	class yatcoConnect_AdminPages_Documentation {
		public function __construct() {

			$fontOptions = new Custom_Font_Manager();

		}

		public function add_actions_and_filters() {

			add_action( 'admin_menu', [$this, 'add_to_menu'], 10 );

			add_action( 'admin_init', function () {
				if ( is_admin() && isset($_GET['page']) && $_GET['page'] === 'yatco-settings' ) {
					wp_safe_redirect( admin_url( 'options-general.php?page=yatco_connect' ) );
					exit;
				}
			});
			
			add_action( 'admin_init', function () {
				if ( is_admin() && isset($_GET['page']) && $_GET['page'] === 'yatco-forms' ) {
					wp_safe_redirect( admin_url( 'edit.php?post_type=cfp_form' ) );
					exit;
				}
			});

		}

		public function add_to_menu() {

			add_menu_page(
		        'YATCO',
		        'YATCO',
		        'manage_options',
		        'yatco_plugin_pages',
		        [$this, 'root_page_callback'],
		        //'dashicons-buddicons-replies',
				YATCO_PLUGIN_ASSETS . 'img/Yatco-Logo-ice-Blue.png',
	      		65
	   		);

	   		add_submenu_page( 
	   			'yatco_plugin_pages', 
	   			'Shortcode Examples', 
	   			'Shortcode Examples',
   				'manage_options', 
   				'yatco_shortcode_examples',
   				[$this, 'shortcode_examples_page_callback'],
   				0
			);

			add_submenu_page( 
	   			'yatco_plugin_pages', 
	   			'SEO Examples', 
	   			'SEO Examples',
   				'manage_options', 
   				'yatco_seo_examples',
   				[$this, 'seo_examples_page_callback'],
   				0
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'Template Builder',
				'Design Templates',
				'manage_options',
				'design-yatco-templates',
				[$this, 'template_builder_callback'],
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'Template Options',
				'Template Options',
				'manage_options',
				'yatco-template-options',
				[$this, 'template_options_callback'],
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'API Data Filter Options',
				'API Data Filter',
				'manage_options',
				'yatco-api-data-filter',
				[$this, 'api_data_filter_options'],
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'Search From Options',
				'Search Form Options',
				'manage_options',
				'yatco-search-form-options',
				[$this, 'search_form_options'],
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'Install Fonts',
				'Install Fonts',
				'manage_options',
				'yatco-font-options',
				[$this, 'yatco_font_options'],
			);

			//Added this menu keep all menu in a single place.
			add_submenu_page(
				'yatco_plugin_pages',
				'YATCO Forms',
				'YATCO Forms',
				'manage_options',
				'yatco-forms',
				[$this, 'yatco_forms'],
			);

			add_submenu_page(
				'yatco_plugin_pages',
				'YATCO SEO Options',
				'YATCO SEO Options',
				'manage_options',
				'yatco-seo-options',
				[$this, 'yatco_seo_options'],
			);

			//Added this menu keep all menu in a single place.
			add_submenu_page(
				'yatco_plugin_pages',
				'YATCO Settings',
				'YATCO Settings',
				'manage_options',
				'yatco-settings',
				[$this, 'yatco_settings'],
			);

		}

		public function yatco_forms(){

			//Nothing to display here as the admin_init hook will send this page to the YATCO Forms 'cfp_form' post type page

		}

		public function yatco_settings(){

			//Nothing to display here as the admin_init hook will send this page to the main settings page of YATCO plugin

		}

		public function root_page_callback() {

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/Base.php';

		}

		public function shortcode_examples_page_callback() {


			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/ShortcodeExamples.php';

		}

		public function seo_examples_page_callback() {


			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/SEOExamples.php';

		}

		public function template_builder_callback(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/templateBuilder.php';

		}

		public function template_options_callback(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/templateOptions.php';

		}

		public function api_data_filter_options(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/filterOptions.php';

		}

		public function search_form_options(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/search_formOptions.php';

		}

		public function yatco_font_options(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/yatco_font_options.php';

		}

		public function yatco_seo_options(){

			include YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/yatco_seo_options.php';

		}
	}