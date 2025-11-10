<?php 
	class yatcoConnect_AdminPages_NewSettingsPanel {

		/**
		 * The options name to be used in this plugin
		 */
		const SLUG = 'yatco_connect';

		public function __construct() {
			
			$this->panelAjax = new yatcoConnect_AdminPages_Ajax();

			$this->Settings_Api = new yatcoConnect_AdminPages_Settings_Api();
			$this->Settings_Pages = new yatcoConnect_AdminPages_Settings_Pages();
			$this->Settings_Leads = new yatcoConnect_AdminPages_Settings_Leads();
			$this->Settings_Styling = new yatcoConnect_AdminPages_Settings_Styling();

		}

		public function add_actions_and_filters() {

			$this->panelAjax->add_actions_and_filters();

			add_action( 'admin_init', [$this, 'register_setting'] );
			add_action( 'admin_menu', [$this, 'add_options_page'] );

		}

		public function register_setting() {
			$this->Settings_Api->register_setting();
			$this->Settings_Pages->register_setting();
			$this->Settings_Leads->register_setting();
			$this->Settings_Styling->register_setting();

			register_setting( self::SLUG, self::SLUG . '_api_url', array( $this, '_sanitize_api_url' ) );

			register_setting( self::SLUG, self::SLUG . '_api_token', array( $this, '_sanitize_api_token' ) );
			
			// register_setting( self::SLUG, self::SLUG . '_tracking_token' );

			register_setting( self::SLUG, self::SLUG . '_google_recap_api_key' );

			register_setting( self::SLUG, self::SLUG . '_google_recap_api_secret' );

			register_setting( self::SLUG, self::SLUG . '_google_analytics_code' );
			
			register_setting( self::SLUG, self::SLUG . '_yacht_search_url');
			
			register_setting( self::SLUG, self::SLUG . '_yacht_search_result_args');
			
			register_setting( self::SLUG, self::SLUG . '_yacht_search_image_size');

			register_setting( self::SLUG, self::SLUG . '_charter_search_url');

			register_setting( self::SLUG, self::SLUG . '_broker_search_url');

			register_setting( self::SLUG, self::SLUG . '_company_search_url');

			// register_setting( self::SLUG, self::SLUG . '_shortcode_styling');

			register_setting( self::SLUG, self::SLUG . '_yacht_single_template');

			register_setting( self::SLUG, self::SLUG . '_enable_intl_phone');

			register_setting( self::SLUG, self::SLUG . '_is_brokerage_site');
			register_setting( self::SLUG, self::SLUG . '_is_euro_site');
			register_setting( self::SLUG, self::SLUG . '_use_boss_seo');

			register_setting( self::SLUG, self::SLUG . '_override_leads_company_id');

			register_setting( self::SLUG, self::SLUG . '_lead_modal_title');
			register_setting( self::SLUG, self::SLUG . '_lead_success_message');

			register_setting('yatcoseo_options_group', 'yatcoseo_seo_title_singleYacht', [
				'sanitize_callback' => 'sanitize_text_field',
			]);
			register_setting('yatcoseo_options_group', 'yatcoseo_seo_description_singleYacht', [
				'sanitize_callback' => 'sanitize_textarea_field',
			]);
			register_setting('yatcoseo_options_group', 'yatcoseo_seo_url_singleYacht', [
				'sanitize_callback' => 'sanitize_text_field',
			]);

			register_setting('yatcoseo_options_group', 'yatcoseo_seo_title_singleCharter', [
				'sanitize_callback' => 'sanitize_text_field',
			]);
			register_setting('yatcoseo_options_group', 'yatcoseo_seo_description_singleCharter', [
				'sanitize_callback' => 'sanitize_textarea_field',
			]);
			register_setting('yatcoseo_options_group', 'yatcoseo_seo_url_singleCharter', [
				'sanitize_callback' => 'sanitize_text_field',
			]);
		}

		/**
		 * Add an options page under the Settings submenu
		*/
		public function add_options_page() {

			add_options_page(
				'YATCO Connect Settings',
				'YATCO Connect',
				'manage_options',
				self::SLUG,
				
				array( $this, 'display_options_page' )
			);

		}

		/**
		 * Render the options page for plugin
		*/
		public function display_options_page() {

			include_once YATCO_PLUGIN_DIR.'/src/yatcoConnect/partials/AdminPages/Settings.php';
		
		}
		
	}