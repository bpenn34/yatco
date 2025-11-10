<?php 
	class yatcoConnect_PostTypes {

		public function __construct() {

		}
	
		public function add_actions_and_filters() {

			add_action( 'init', [ $this, 'addPostTypes' ], 12);

		}

		public function addPostTypes() {
			$post_types=[
				'yatco_yachts' => [
					'labels' => array(
						'name' => 'Yachts',
						'singular_name' => 'Yacht',
					),
					
					'has_archive' => false,
					'query_var' => 'yacht_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_yachts_gallery' => [
					'labels' => array(
						'name' => 'Yacht Galleries',
						'singular_name' => 'Yacht Gallery',
					),
					
					'has_archive' => false,
					'query_var' => 'yacht_gallery_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_charters' => [
					'labels' => array(
						'name' => 'Charters',
						'singular_name' => 'Charter',
					),
					
					'has_archive' => false,
					'query_var' => 'charter_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_brokers' => [
					'labels' => array(
						'name' => 'Brokers',
						'singular_name' => 'Broker',
					),
					
					'has_archive' => false,
					'query_var' => 'broker_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_companies' => [
					'labels' => array(
						'name' => 'Companies',
						'singular_name' => 'Company',
					),
					
					'has_archive' => false,
					'query_var' => 'company_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_c_builder' => [
					'labels' => array(
						'name' => 'Builder Companies',
						'singular_name' => 'Builder Company',
					),
					
					'has_archive' => false,
					'query_var' => 'company_builder_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_builder_models' => [
					'labels' => array(
						'name' => 'Builder Models',
						'singular_name' => 'Builder Models',
					),
					
					'has_archive' => false,
					'query_var' => 'builder_model_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_services_mls' => [
					'labels' => array(
						'name' => 'Services MLS Members',
						'singular_name' => 'Services MLS Member',
					),
					
					'has_archive' => false,
					'query_var' => 'services_mls_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				],

				'yatco_members' => [
					'labels' => array(
						'name' => 'Members',
						'singular_name' => 'Member',
					),
					
					'has_archive' => false,
					'query_var' => 'member_id',

					'public' => false,
					'publicly_queryable' => true,
					'can_export' => false,
				]

			];

			foreach ($post_types as $type_key => $type) {

				if ( ! post_type_exists($type_key) && ! taxonomy_exists($type_key)) {

		           register_post_type($type_key, $type);
		        
		        }

			}

		}

	}