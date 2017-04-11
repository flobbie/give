<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @subpackage  Classes/Give_Settings_Data
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Give_Settings_Data' ) ) :

	/**
	 * Give_Settings_Data.
	 *
	 * @sine 1.8
	 */
	class Give_Settings_Data {

		/**
		 * Setting page id.
		 *
		 * @since 1.8
		 * @var   string
		 */
		protected $id = '';

		/**
		 * Setting page label.
		 *
		 * @since 1.8
		 * @var   string
		 */
		protected $label = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'data';
			$this->label = esc_html__( 'Data', 'give' );

			add_filter( 'give-tools_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( "give-tools_settings_{$this->id}_page", array( $this, 'output' ) );

			// Do not use main form for this tab.
			if( give_get_current_setting_tab() === $this->id ) {
				add_action( "give-tools_open_form", '__return_empty_string' );
				add_action( "give-tools_close_form", '__return_empty_string' );
			}

			// Render data field type.
			add_action( 'give_admin_field_data', array( $this, 'render_data_field' ) );
		}

		/**
		 * Add this page to settings.
		 *
		 * @since  1.8
		 * @param  array $pages Lst of pages.
		 * @return array
		 */
		public function add_settings_page( $pages ) {
			$pages[ $this->id ] = $this->label;

			return $pages;
		}

		/**
		 * Get settings array.
		 *
		 * @since  1.8
		 * @return array
		 */
		public function get_settings() {
			// Hide save button.
			$GLOBALS['give_hide_save_button'] = true;

			// Get settings.
			$settings = apply_filters( 'give_settings_data', array(
				array(
					'id'   => 'give_tools_tools',
					'type' => 'title',
					'table_html' => false
				),
				array(
					'id'   => 'api',
					'name' => esc_html__( 'Tools', 'give' ),
					'type' => 'data',
				),
				array(
					'id'   => 'give_tools_tools',
					'type' => 'sectionend',
					'table_html' => false
				)
			));

			/**
			 * Filter the settings.
			 *
			 * @since  1.8
			 * @param  array $settings
			 */
			$settings = apply_filters( 'give_get_settings_' . $this->id, $settings );

			// Output.
			return $settings;
		}

		/**
		 * Output the settings.
		 *
		 * @since  1.8
		 * @return void
		 */
		public function output() {
			$settings = $this->get_settings();

			Give_Admin_Settings::output_fields( $settings, 'give_settings' );
		}


		/**
		 * Render data field.
		 *
		 * @since 2.0
		 * @access public
		 * @param $field
		 */
		public function render_data_field( $field ){
			give_tools_recount_stats_display();
			echo Give_Admin_Settings::get_field_description($field);
		}
	}

endif;

return new Give_Settings_Data();
