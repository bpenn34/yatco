<?php

	class yatcoConnect_AdminPages_Settings_Styling{

		const SLUG = 'yatco_connect';

		public function __construct() {

			$this->options = new yatcoConnect_Options();

		}

		public function register_setting() {
			add_settings_section(
				self::SLUG . '_colors',
				'Customize Colors',
				[$this, '_colors_cb'],
				self::SLUG
			);
				add_settings_field(
					self::SLUG . '_button_color_one',
					__( 'Color #1', self::SLUG ),
					array( $this, '_button_color_one_field' ),
					self::SLUG,
					self::SLUG . '_colors',
					array( 'label_for' => self::SLUG . '_button_color_one' )
				);

				register_setting( self::SLUG, self::SLUG . '_button_color_one');

				add_settings_field(
					self::SLUG . '_button_color_two',
					__( 'Color #2', self::SLUG ),
					array( $this, '_button_color_two_field' ),
					self::SLUG,
					self::SLUG . '_colors',
					array( 'label_for' => self::SLUG . '_button_color_two' )
				);

				register_setting( self::SLUG, self::SLUG . '_button_color_two');
				
				add_settings_field(
					self::SLUG . '_bg_color_one',
					__( 'Background Color #1', self::SLUG ),
					array( $this, '_bg_color_one_field' ),
					self::SLUG,
					self::SLUG . '_colors',
					array( 'label_for' => self::SLUG . '_bg_color_one' )
				);

				register_setting( self::SLUG, self::SLUG . '_bg_color_one');

		}

		public function _colors_cb() {
			echo '<p>' . __( 'Customize Buttons, Backgrounds, And More', self::SLUG ) . '</p>';
		}

		public function _button_color_one_field() {

			$nameOfField=self::SLUG.'_button_color_one';
			$valOfField=get_option($nameOfField);

			?>

			<input type="color" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}
		
		public function _button_color_two_field() {

			$nameOfField=self::SLUG.'_button_color_two';
			$valOfField=get_option($nameOfField);

			?>

			<input type="color" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

		public function _bg_color_one_field() {

			$nameOfField=self::SLUG.'_bg_color_one';
			$valOfField=get_option($nameOfField);

			?>

			<input type="color" name="<?= $nameOfField ?>" value="<?= $valOfField ?>" autocomplete="off"><?php 

		}

	}