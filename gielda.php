<?php
/*
	Plugin Name: WooCommerce Twoja Giełda XML
	Plugin URI: https://www.twojagielda.com
	Description: Integracja WooCommerce z serwisem Twoja Giełda.
	Version: 3.3
	Author: Twoja Giełda
	Author URI: https://www.twojagielda.com/
	Text Domain: woocommerce_gielda
	Domain Path: /lang/ 
	Requires at least: 4.5
    Tested up to: 5.2.3
    WC requires at least: 3.1.0
    WC tested up to: 3.7.0

	Copyright 2019 Twoja Giełda.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$plugin_version = '3.2.11';
define( 'WOOCOMMERCE_GIELDA_VERSION', $plugin_version );

$wpdesk_helper_text_domain = 'woocommerce_gielda';
require_once( plugin_basename( 'inc/wpdesk-functions.php' ) );

$plugin_data = array(
    'plugin' => plugin_basename( __FILE__ ),
    'product_id' => 'WooCommerce Twoja Gielda XML',
    'version'   => WOOCOMMERCE_GIELDA_VERSION,
    'config_uri' => admin_url( 'admin.php?page=woocommerce_gielda' )
);
$helper = new WPDesk_Helper_Plugin( $plugin_data );

require_once( plugin_basename( 'inc/wpdesk-woo27-functions.php' ) );

require_once('gielda_admin.php');
require_once( 'class/xml.php' );

class Gielda extends inspirePlugin2 {

	protected $_pluginNamespace = 'woocommerce_gielda';
	protected $gieldaAdmin;

	const XML_GENERATE_AJAX_HANDLE = 'generate_gielda_xml';
	const GIELDA_XML_URL = 'wp-admin/admin-ajax.php?action=generate_gielda_xml';
	const GIELDA_XML_PRETTY_URL = 'gielda.xml';

	public function __construct() {
		$this->_initBaseVariables();

		// load locales
		load_plugin_textdomain('woocommerce_gielda', FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');

		if ( is_admin() )
		{
			$this->gieldaAdmin = new GieldaAdmin();
		}
	}

	public function attach_hooks() {
		add_action( 'woocommerce_gielda_generate_xml', array( $this, 'woocommerce_gielda_generate_xml' ) );
		add_action( 'wp_ajax_' . self::XML_GENERATE_AJAX_HANDLE, array($this, 'ajax_generate_xml_action' ) );
		add_action( 'wp_ajax_nopriv_' . self::XML_GENERATE_AJAX_HANDLE, array($this, 'ajax_generate_xml_action') );
		add_action( 'wp_ajax_foobar', 'ajax_generate_xml_action' );

		add_action( 'init', array( $this, 'init_pretty_gielda_url_action' ) );

			if ( !wp_next_scheduled( 'woocommerce_gielda_generate_xml' ) ) {
			wp_schedule_event( time(), 'daily', 'woocommerce_gielda_generate_xml' );
		}
}

	/**
	 * Pretty gielda url
	 */
	public static function init_pretty_gielda_url_action() {
		add_rewrite_rule('^' . self::GIELDA_XML_PRETTY_URL . '?', self::GIELDA_XML_URL, 'top');
	}

	/**
	 * Ajax handle for xml generatation
	 * @return void
	 */
	public function ajax_generate_xml_action() {
		header( 'Content-Type: text/xml' );
		$gielda = new GieldaXml();
		//		echo $gielda->get_xml( );
	$upload_dir   = wp_upload_dir();
    $file = $upload_dir['basedir'] . '/gielda.xml'; 
    $open = fopen( $file, "w" ); 
    $write = fputs( $open, $gielda->get_xml( ) ); 
    fclose( $open );
		exit();
	}

	/**
	 * Cron handle for xml generatation
	 * @return void
	 */
	public function woocommerce_gielda_generate_xml() {
		$gielda = new GieldaXml();
		$gielda->get_xml( true );
		$upload_dir   = wp_upload_dir();
    $file = $upload_dir['basedir'] . '/gielda.xml'; 
    $open = fopen( $file, "w" ); 
    $write = fputs( $open, $gielda->get_xml( ) ); 
    fclose( $open );
		exit();
	}

	/**
	 * action_links function.
	 *
	 * @access public
	 * @param mixed $links
	 * @return void
	 */
	public function linksAction( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=woocommerce_gielda' ) . '">' . __( 'Ustawienia', 'woocommerce_gielda' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}

}

	$_GLOBALS['woocommerce_gielda'] = $gielda = new Gielda();
	$gielda->attach_hooks();


register_deactivation_hook(__FILE__, 'woocommerce_gielda_deactivation');
function woocommerce_gielda_deactivation() {
	wp_clear_scheduled_hook('woocommerce_gielda_generate_xml');
}

register_activation_hook(__FILE__, 'woocommerce_gielda_activation');
function woocommerce_gielda_activation() {
	Gielda::init_pretty_gielda_url_action();
	flush_rewrite_rules();
}
