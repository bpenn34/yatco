<?php
	class yatcoConnect_ApiToBossConnectWarning {

		public function __construct() {

			$this->data = new yatcoConnect_ApiToBoss();
			
		}


		public function add_actions_and_filters() {

			add_action( 'admin_bar_menu', [$this, 'wp_admin_bar_boss_api_ping'], 100, 1 );
 
		}

		public function wp_admin_bar_boss_api_ping($admin_bar){
			$ping = $this->data->ping();

		    $admin_bar->add_menu( array(
		        'id'    => 'boss-ping',
		        'title' => 'YATCO BOSS CONNECT PING: '.(($ping)?'Successful':'Error'),
		        'href'  => '#',
		        'meta'  => array(
		            'title' => __('BOSS CONNECTION STATUS FROM AT THE BEGINING OF PAGE LOAD'),       
		            'class' => 'boss_connect_ping',
		        ),
		    ));

		}
	}