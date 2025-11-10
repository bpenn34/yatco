<?php
	class yatcoConnect_AdminPages_Settings_Api{

		const SLUG = 'yatco_connect';
		

		public function __construct() {

			$this->options = new yatcoConnect_Options();
		}

		public function register_setting() {

			add_settings_section(
				self::SLUG . '_api_settings',
				'YATCO BOSS API Settings',
				[$this, '_api_settings_cb'],
				self::SLUG
			);

				add_settings_field(
					self::SLUG . '_api_url',
					'BOSS API Base URL',
					array( $this, '_api_url_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_api_url' )
				);

				add_settings_field(
					self::SLUG. '_api_token',
					__( 'BOSS API Token', self::SLUG ),
					array( $this, '_api_token_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_api_token' )
				);

				add_settings_field(
					self::SLUG . '_is_brokerage_site',
					__( 'Is this brokerage website?', self::SLUG ),
					array( $this, '_is_brokerage_site_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array()
				);

				add_settings_field(
					self::SLUG . '_is_euro_site',
					__( 'Is this a euro website?', self::SLUG ),
					array( $this, '_is_euro_site_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array()
				);

				add_settings_field(
					self::SLUG . '_override_leads_company_id',
					__( 'Send Leads To CompanyID', self::SLUG ),
					array( $this, '_leads_company_id_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_leads_company_id' )
				);

				/*add_settings_field(
					self::SLUG. '_tracking_token',
					__( 'Tracking Token', self::SLUG ),
					array( $this, '_tracking_token_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( )
				);*/

				add_settings_field(
					self::SLUG. '_check_api_connection',
					__( 'Test Connection', self::SLUG ),
					array( $this, '_check_api_connection_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( )
				);
				
				add_settings_field(
					self::SLUG. '_google_recap_api_key',
					__( 'Google ReCaptcha API Key', self::SLUG ),
					array( $this, '_google_recap_api_key_field' ),
					self::SLUG, 
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_google_recap_api_key' )
				);

				add_settings_field(
					self::SLUG. '_google_recap_api_secret',
					__( 'Google ReCaptcha API Secret', self::SLUG ),
					array( $this, '_google_recap_api_secret_field' ),
					self::SLUG, 
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_google_recap_api_secret' )
				);

				add_settings_field(
					self::SLUG. '_google_analytics_code',
					__( 'Google Analytics Code', self::SLUG ),
					array( $this, '_google_analytics_code_field' ),
					self::SLUG, 
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_google_analytics_code' )
				);

				add_settings_field(
					self::SLUG . '_purge_transients',
					__( 'Purge Options Cache', self::SLUG ),
					array( $this, '_purge_transients_field' ),
					self::SLUG,
					self::SLUG . '_api_settings',
					array( 'label_for' => self::SLUG . '_purge_transients' )
				);
		}

		public function _api_settings_cb() {
			echo '<p>' . __( 'Consult with YATCO on the appropriate values for your WordPress site.', self::SLUG ) . '</p>';
		}

		public function _api_url_field() {
			$api_url = get_option( self::SLUG . '_api_url' );

			echo '<input type="text" name="' . self::SLUG . '_api_url' . '" id="' . self::SLUG . '_api_url' . '" value="' . esc_attr($api_url) . '" size="60">';
		}

		public function _api_token_field() {
			$api_token = get_option( self::SLUG . '_api_token' );

			echo '<input type="text" name="' . self::SLUG . '_api_token' . '" id="' . self::SLUG . '_api_token' . '" value="' . esc_attr($api_token) . '" size="60" autocomplete="off">';
		}


		public function _sanitize_api_url( $api_url ) {
			return $api_url;
		}

		public function _sanitize_api_token( $api_token ) {
			return $api_token;
		}


		public function _is_brokerage_site_field() {
			$options=[
				'' => '---- Not Picked Yet ----',
				'yes' => 'YES',
				'no' => 'NO',
			];

			$nameOfField=self::SLUG.'_is_brokerage_site';
			$valOfField=get_option($nameOfField);

			?>

			<select name="<?= $nameOfField ?>"> 
				<?php 
					foreach ( $options as $opt_value => $opt_label ) {
						$option = '<option value="' . $opt_value . '" '. selected($opt_value, $valOfField, false) .'>';

						$option .= $opt_label;
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select><?php 
		}

		public function _is_euro_site_field() {
			$options=[
				'' => '---- Not Picked Yet ----',
				'yes' => 'YES',
				'no' => 'NO',
			];

			$nameOfField=self::SLUG.'_is_euro_site';
			$valOfField=get_option($nameOfField);

			?>

			<select name="<?= $nameOfField ?>"> 
				<?php 
					foreach ( $options as $opt_value => $opt_label ) {
						$option = '<option value="' . $opt_value . '" '. selected($opt_value, $valOfField, false) .'>';

						$option .= $opt_label;
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select><?php 
		}

		public function _leads_company_id_field() {

			$valOfField=get_option(self::SLUG.'_override_leads_company_id');

			?>

			<input type="number" name="<?=  self::SLUG . '_override_leads_company_id' ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

		public function _check_api_connection_field() {
			ob_start(); ?>

				<button type="button" class="button button-primary" onclick="yatco_ajax_api_connection();" style="display: inline-block; vertical-align: middle;">Check BOSS API Connect</button> <strong >(After A Fresh Saving)</strong>
				&nbsp;
				<span id="yatco_check_api_connection_status"></span>
				<script type="text/javascript">
					// #yatco_purge_transients_status
					function yatco_ajax_api_connection() {

						jQuery('#yatco_check_api_connection_status').html('<strong>Testing...</strong>');

						jQuery.ajax({
							url: "<?= admin_url( 'admin-ajax.php' ) ?>",
							dataType: 'json',
							data: {
								'action': 'yatco_check_api_connection',
							},
							success: function( result ) {
								if (typeof result.Results != 'undefined') {
									jQuery( "#yatco_check_api_connection_status" ).html( "<strong>Sucessfull..!</strong>" );
								}
							}
						});

					}

				</script>

			<?php echo ob_get_clean(); 
		}


		// Google Re-Cap Fields
		public function _google_recap_api_key_field() {
			$nameOfField=self::SLUG.'_google_recap_api_key';
			$valOfField=get_option($nameOfField);

			?>

			<input type="text" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}
		
		public function _google_recap_api_secret_field() {
			$nameOfField=self::SLUG.'_google_recap_api_secret';
			$valOfField=get_option($nameOfField);

			?>

			<input type="text" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

		public function _google_analytics_code_field() {
			$nameOfField=self::SLUG.'_google_analytics_code';
			$valOfField=get_option($nameOfField);

			?>

			<textarea name="<?= $nameOfField ?>" style="resize:auto;" autocomplete="off" resize="true"><?= $valOfField ?></textarea><?php 

		}

		public function _purge_transients_field() {
			ob_start(); ?>	
				<button type="button" class="button button-primary" onclick="yatco_ajax_purge_transients();">Purge Transients</button>
				&nbsp;
				<span id="yatco_purge_transients_status"></span>
				<script type="text/javascript">
					// #yatco_purge_transients_status
					function yatco_ajax_purge_transients() {

						jQuery('#yatco_purge_transients_status').html('<strong>Purging...</strong>');

						jQuery.ajax({
							url: "<?= admin_url( 'admin-ajax.php' ) ?>",
							dataType: 'json',
							data: {
								'action': 'yatco_purge_transients',
							},
							success: function( result ) {
								if (typeof result.success != 'undefined') {
									jQuery( "#yatco_purge_transients_status" ).html( "<strong>Purged..!</strong>" );
								}

							}
						});

					}

				</script>
			<?php echo ob_get_clean();
		}


	}

