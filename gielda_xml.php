<?php
/**
 * @deprecated
 *
 * This handle is deprecated. Do not use it.
 */

$plugin_dir = dirname(__FILE__);

chdir('../../../');
require_once( 'wp-load.php' );
require_once( $plugin_dir . '/class/inspire/plugin2.php' );
require_once( $plugin_dir . '/class/gielda.php' );
require_once( $plugin_dir . '/class/xml.php' );

$gielda = new GieldaXml();
if ( $gielda->isSecure() ) {
	header( 'Content-Type: text/xml' );
	echo $gielda->get_xml();
} else {
	echo _( 'Dostęp zabroniony.' );
}

