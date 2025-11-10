<?php
	class yatcoConnect_AdminPages_Settings_Leads{

		const SLUG = 'yatco_connect';


		public function __construct() {

			$this->options = new yatcoConnect_Options();

		}

		public function register_setting() {
			add_settings_section(
				self::SLUG . '_lead_messages',
				'Lead Messages',
				[$this, '_lead_messages_cb'],
				self::SLUG
			);
				add_settings_field(
					self::SLUG . '_lead_modal_title',
					__( 'Lead Modal Title', self::SLUG ),
					array( $this, '_lead_modal_title_field' ),
					self::SLUG,
					self::SLUG . '_lead_messages',
					array( 'label_for' => self::SLUG . '_lead_modal_title' )
				);
				
				add_settings_field(
					self::SLUG . '_',
					__( 'Lead Success Message (HTML)', self::SLUG ),
					array( $this, '_lead_success_message_field' ),
					self::SLUG,
					self::SLUG . '_lead_messages',
					array( 'label_for' => self::SLUG . '_lead_success_message' )
				);
		}

		public function _lead_messages_cb() {
			echo '<p>' . __( 'Lead Settings', self::SLUG ) . '</p>';
		}

		public function _lead_modal_title_field() {

			$nameOfField=self::SLUG.'_lead_modal_title';
			$valOfField=get_option($nameOfField);

			?>

			<input type="text" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

		public function _lead_success_message_field() {

			$nameOfField=self::SLUG.'_lead_success_message';
			$valOfField=get_option($nameOfField);

			?>

			<textarea name="<?= $nameOfField ?>" autocomplete="off" cols="50" rows="10"><?= $valOfField ?></textarea><?php 

		}

		public function _cfp_message_field() {

			$nameOfField=self::SLUG.'_cfp_message';
			$valOfField=get_option($nameOfField);

			?>

			<input type="text" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}
	}