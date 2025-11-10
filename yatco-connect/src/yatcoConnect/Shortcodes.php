<?php 
	class yatcoConnect_Shortcodes {
		public function __construct() {

	
		}

		public function add_actions_and_filters() {
			$this->General = new yatcoConnect_Shortcodes_General();
			
			$this->Forms = new yatcoConnect_Shortcodes_Forms();
			
			$this->GeneralYacht = new yatcoConnect_Shortcodes_GeneralYacht();

			$this->CharterSearch = new yatcoConnect_Shortcodes_CharterSearch();
			
			$this->GrabbedYachtDetails = new yatcoConnect_Shortcodes_GrabbedYachtDetails();
		
			$this->UserSubscriptions = new yatcoConnect_Shortcodes_UserSubscriptions();

			$this->CRM_Members = new yatcoConnect_Shortcodes_CrmMembers();

			$this->BrokerFinder = new yatcoConnect_Shortcodes_BrokerFinder();
		
			$this->Services_MLS = new yatcoConnect_Shortcodes_ServicesMLS();

			$this->Boatdeck_listings = new yatcoConnect_Shortcodes_BoatdeckListings();
			
		}
		
	}