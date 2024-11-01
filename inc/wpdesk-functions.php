<?php

/**
 * Load installer for the WP Desk Helper.
 * @return $api Object
 */

/**
 * WP Desk Helper Installation Prompts
 */

if ( ! class_exists( 'WPDesk_Helper_Plugin' ) ) {
	class WPDesk_Helper_Plugin {


		protected $plugin_data;

		protected $text_domain;

		protected $ame_activated_key;

		protected $ame_activation_tab_key;




		function is_active( $add_notice = false ) {
				return true;
			}
	}
}
