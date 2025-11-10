<?php
	class yatcoConnect_AdminPages_Settings_Pages{
		const SLUG = 'yatco_connect';


		public function __construct() {

			$this->options = new yatcoConnect_Options();

		}

		public function register_setting() {

			add_settings_section(
				self::SLUG . '_yacht_settings',
				__( 'YATCO Page Settings', self::SLUG),
				array( $this, '_yacht_settings_cb' ),
				self::SLUG
			);

			add_settings_field(
				self::SLUG . '_yacht_search_url',
				__( 'Yachts Search Page', self::SLUG ),
				array( $this, '_yacht_search_page_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_yacht_search_url' )
			);

			add_settings_field(
				self::SLUG . '_yacht_search_result_args',
				__( 'Yachts Search Result Args', self::SLUG ),
				array( $this, '_yacht_search_result_args_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_yacht_search_result_args' )
			);	

			add_settings_field(
				self::SLUG . '_yacht_search_image_size',
				__( 'Yachts Search Image Size', self::SLUG ),
				array( $this, '_yacht_search_image_size_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_yacht_search_image_size' )
			);				

			add_settings_field(
				self::SLUG . '_charter_search_url',
				__( 'Charter Search Page', self::SLUG ),
				array( $this, '_charter_search_page_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_charter_search_url' )
			);

			add_settings_field(
				self::SLUG . '_broker_search_url',
				__( 'Broker Search Page', self::SLUG ),
				array( $this, '_broker_search_page_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_broker_search_url' )
			);

			add_settings_field(
				self::SLUG . '_company_search_url',
				__( 'Company Search Page', self::SLUG ),
				array( $this, '_company_search_page_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_company_search_url' )
			);

			add_settings_field(
				self::SLUG . '_yacht_single_template',
				__( 'Template Pack', self::SLUG ),
				array( $this, '_yacht_single_template_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_yacht_single_template_url' )
			);

			add_settings_field(
				self::SLUG . '_use_boss_seo',
				__( 'Want to use SEO inputs from boss?', self::SLUG ),
				array( $this, '_use_boss_seo_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array()
			);
			
			add_settings_field(
				self::SLUG . '_',
				__( 'Override For Call For Price', self::SLUG ),
				array( $this, '_cfp_message_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_cfp_message' )
			);

			add_settings_field(
				self::SLUG . '_enable_intl_phone',
				__( 'Enable International Phone Format', self::SLUG ),
				array( $this, '_enable_intl_phone_field' ),
				self::SLUG,
				self::SLUG . '_yacht_settings',
				array( 'label_for' => self::SLUG . '_enable_intl_phone' )
			);

		}


		public function _yacht_settings_cb() {
			echo '<p>Which pages have shortcode been applied too? What do you details to look like?</p>';
		}

		public function _yacht_search_page_field() {
			$_search_page = $this->options->getOption('yacht_search_url' );

			?>

			<select name="<?=  self::SLUG . '_yacht_search_url' ?>"> 
				<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
				<?php 
					$pages = get_pages(['post_status' => ['draft', 'publish'], 'order' => 'ASC', 'orderby' => 
						'title']); 

					foreach ( $pages as $page ) {
						$url = get_page_link( $page->ID );
						$option = '<option value="' . $url . '" '. selected($url, $_search_page, false) .'>';

						$option .= $page->post_title;
						$option .= ($page->post_status == 'draft')?' (DRAFT)':"";
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select>
			<?php 

		}

		public function _yacht_search_result_args_field() {
			$_search_result_args = $this->options->getOption('yacht_search_result_args' );

			?>

			<textarea name="<?=  self::SLUG . '_yacht_search_result_args' ?>"  cols="50" rows="20"><?= $_search_result_args ?></textarea>
			<?php 


		}

		public function _yacht_search_image_size_field() {
			$_image_size = $this->options->getOption('yacht_search_image_size' );

			$sizes = [
				'' => 'Default',
				'medium' => 'Medium',
				'large' => 'Large'
			];

			?>

				<select name="<?=  self::SLUG . '_yacht_search_image_size' ?>">
					<?php 
						foreach ( $sizes as $s_val => $s_label ) {
							$url = get_page_link( $page->ID );
							$option = '<option value="' . $s_val . '" '. selected($s_val, $_image_size, false) .'>';

							$option .= $s_label;
							
							$option .= '</option>';

							echo $option;
						}
					?>
				</select>


			<?php 


		}

		public function _charter_search_page_field() {
			$_search_page = $this->options->getOption('charter_search_url');

			?>

			<select name="<?=  self::SLUG . '_charter_search_url' ?>"> 
				<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
				<?php 
					$pages = get_pages(['post_status' => ['draft', 'publish'], 'order' => 'ASC', 'orderby' => 
						'title']); 

					foreach ( $pages as $page ) {
						$url = get_page_link( $page->ID );
						$option = '<option value="' . $url . '" '. selected($url, $_search_page, false) .'>';

						$option .= $page->post_title;
						$option .= ($page->post_status == 'draft')?' (DRAFT)':"";
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select>
			<?php 

		}

		public function _broker_search_page_field() {
			$_search_page = $this->options->getOption('broker_search_url' );

			?>

			<select name="<?=  self::SLUG . '_broker_search_url' ?>"> 
				<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
				<?php 
					$pages = get_pages(['post_status' => ['draft', 'publish'], 'order' => 'ASC', 'orderby' => 
						'title']); 

					foreach ( $pages as $page ) {
						$url = get_page_link( $page->ID );
						$option = '<option value="' . $url . '" '. selected($url, $_search_page, false) .'>';

						$option .= $page->post_title;
						$option .= ($page->post_status == 'draft')?' (DRAFT)':"";
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select>
			<?php 

		}
		
		public function _company_search_page_field() {
			$_search_page = $this->options->getOption('company_search_url' );

			?>

			<select name="<?=  self::SLUG . '_company_search_url' ?>"> 
				<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
				<?php 
					$pages = get_pages(['post_status' => ['draft', 'publish'], 'order' => 'ASC', 'orderby' => 
						'title']); 

					foreach ( $pages as $page ) {
						$url = get_page_link( $page->ID );
						$option = '<option value="' . $url . '" '. selected($url, $_search_page, false) .'>';

						$option .= $page->post_title;
						$option .= ($page->post_status == 'draft')?' (DRAFT)':"";
						
						$option .= '</option>';

						echo $option;
					}
				?>
			</select>
			<?php 

		}

		public function _yacht_single_template_field() {
			$options=[
				'yatco-single-yacht-for-brokerage-template' => 'BROKERAGE',
				'yatco-single-yacht-for-yatco-template' => 'YATCO ONLY',
				'current' => 'Current Theme',
				'template-builder' => 'Template Builder',
			];

			$nameOfField=self::SLUG.'_yacht_single_template';
			$valOfField=get_option($nameOfField);

			?>

			<select name="<?=  $nameOfField ?>"> 
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

		public function _use_boss_seo_field() {
			$options=[
				'' => '---- Not Picked Yet ----',
				'yes' => 'YES',
				'no' => 'NO',
			];

			$nameOfField=self::SLUG . '_use_boss_seo';
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

		public function _cfp_message_field() {

			$nameOfField=self::SLUG.'_cfp_message';
			$valOfField=get_option($nameOfField);

			?>

			<input type="text" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

		public function _enable_intl_phone_field() {
			$nameOfField = self::SLUG . '_enable_intl_phone';
			$valOfField  = get_option($nameOfField);
			?>
			<label class="yt-mui-switch">
				<input type="checkbox" name="<?= esc_attr($nameOfField) ?>" value="1" <?= checked($valOfField, 1, false) ?>>
				<span class="yt-mui-slider"></span>
			</label>
			<p class="description">Enable international phone number input with country code selector.</p>
			<?php
		}

	}