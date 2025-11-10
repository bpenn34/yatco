<?php 
	
	class yatcoConnect_Plugin {

		static public $indicateActivated;

		public function __construct() {

			$this->installer = new yatcoConnect_Installer();
 			$this->options = new yatcoConnect_Options();
			
			$this->adminPanel = new yatcoConnect_AdminPages_NewSettingsPanel();
			$this->adminPanelDocumentation = new yatcoConnect_AdminPages_Documentation();
			
			$this->postTypes = new yatcoConnect_PostTypes();

			$this->WpFiltersApiToBoss = new yatcoConnect_WpFiltersApiToBoss();
			$this->WpFiltersVirtualPostTypes = new yatcoConnect_WpFiltersVirtualPostTypes();

			$this->EchoPrintFilters = new yatcoConnect_EchoPrintFilters();

			$this->Yachts_Virtual=new yatcoConnect_Yachts_Virtual();
			$this->Yachts_Template=new yatcoConnect_Yachts_Template();
			$this->Yachts_WPseo=new yatcoConnect_Yachts_WPseo();
			$this->Yachts_AIOseo=new yatcoConnect_Yachts_AIOseo();
			$this->Yachts_SiteMap=new yatcoConnect_Yachts_SiteMap();

			$this->Charter_Virtual=new yatcoConnect_Charter_Virtual();
			$this->Charter_Template=new yatcoConnect_Charter_Template();
			$this->Charter_WPseo=new yatcoConnect_Charter_WPseo();
			$this->Charter_SiteMap=new yatcoConnect_Charter_SiteMap();

			$this->Broker_Virtual=new yatcoConnect_Brokers_Virtual();
			$this->Broker_Template=new yatcoConnect_Brokers_Template();
			$this->Broker_WPseo=new yatcoConnect_Brokers_WPseo();
			$this->Broker_SiteMap=new yatcoConnect_Brokers_SiteMap();

			$this->WpSeo_ogImage = new yatcoConnect_WpSeo_ogImage();
			$this->WpSeo_404 = new yatcoConnect_WpSeo_FourZeroFour();

			$this->Company_Builder_Virtual=new yatcoConnect_CompanyBuilder_Virtual();
			$this->Company_Builder_Template=new yatcoConnect_CompanyBuilder_Template();
			$this->Company_Builder_WPSeo=new yatcoConnect_CompanyBuilder_WPseo();
			
			$this->Company_Virtual=new yatcoConnect_Company_Virtual();
			$this->Company_Template=new yatcoConnect_Company_Template();
			$this->Company_WPseo=new yatcoConnect_Company_WPseo();
			$this->Company_SiteMap=new yatcoConnect_Company_SiteMap();

			$this->Members_Virtual=new yatcoConnect_Members_Virtual();

			$this->ServiceMLS_Virtual=new yatcoConnect_ServicesMLS_Virtual();
			
			$this->metabox_results_args = new yatcoConnect_MetaBox_YachtResultsArgs();

			//$this->bossConnectWarning = new yatcoConnect_ApiToBossConnectWarning();

			$this->stylesAndScripts = new yatcoConnect_StylesAndScripts();

			$this->ListsAndOptions_Core = new yatcoConnect_ListsAndOptions_Core();
			$this->ListsAndOptions_Locations = new yatcoConnect_ListsAndOptions_Locations();
			$this->ListsAndOptions_Charter = new yatcoConnect_ListsAndOptions_Charter();
			$this->ListsAndOptions_Static = new yatcoConnect_ListsAndOptions_Static();
			$this->ListsAndOptions_CRM = new yatcoConnect_ListsAndOptions_CRM();

			$this->restApi = new yatcoConnect_RestApi();

			$this->shortcodes = new yatcoConnect_Shortcodes();
		}

		public function isInstalled() {
			return $this->options->isInstalled();
		}

		public function install() {
			$this->installer->install();
		}

		public function upgrade() {
			# Cleanup and additions/subtractions pertaining to the version upgrade
		}

		public function activate() {
			// activation logic
			self::$indicateActivated = $this->options->addOption('_activated', 1);

			$this->installer->install();

		}

		public function deactivate() {
			self::$indicateActivated = $this->options->deleteOption('_activated');
		}

		public function addActionsAndFilters() {
			
			foreach ($this as $this_key_value) {

				if (method_exists($this_key_value, 'add_actions_and_filters')) {

					$this_key_value->add_actions_and_filters();

				}

			}
		}

	}