<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	require_once('class/inspire/plugin2.php');

	require_once('class/gielda.php');

    class GieldaAdmin extends inspirePlugin2
    {
    	protected $_pluginNamespace = 'woocommerce_gielda';

    	protected $scripts_version = '3.2.11';

        public function __construct()
        {
        	$this->_initBaseVariables();

        	// security check
        	if ( !is_admin() ) die;

        	// gielda settings
        	add_action( 'admin_init', array($this, 'initSettingsAction') );
        	add_action( 'admin_init', array($this, 'updateSettingsAction') );
        	add_action( 'admin_enqueue_scripts', array($this, 'initCssAction'), 75 );
        	add_action( 'admin_enqueue_scripts', array($this, 'initJsAction'), 75 );
        	add_action( 'admin_head', array($this, 'initJsAction'), 75 );
        	add_action( 'admin_menu', array($this, 'initAdminMenuAction'), 70);

        	add_action('wp_ajax_gielda_category_autocomplete', array($this, 'ajaxGieldaCategoryAutocomplete'));

        	add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'woocommerce_product_after_variable_attributes' ), 10, 3 );

        	add_action( 'woocommerce_save_product_variation', array( $this, 'woocommerce_save_product_variation' ), 10, 2 );

        	add_action( 'woocommerce_gielda_clear_cache', array( $this, 'woocommerce_gielda_clear_cache' ) );

	        add_filter( 'woocommerce_screen_ids', array ( $this, 'woocommerce_screen_ids' ) );

        }

	    function woocommerce_screen_ids( $screen_ids ) {
		    $screen_ids[] = 'woocommerce_page_woocommerce_gielda';
		    return $screen_ids;
	    }

        public function woocommerce_gielda_clear_cache() {
	        delete_transient( WCI_Gielda::$transient_name_generated );
        }

        public function woocommerce_save_product_variation( $variation_id, $i ) {
	        if ( !empty( $_POST[$this->getNamespace()] ) && !empty( $_POST[$this->getNamespace()][$i] ) ) {
		        update_post_meta( $variation_id, 'woocommerce_gielda_disabled', '' );

		        foreach ( $_POST[$this->getNamespace()][$i] as $name => $value ) {
			        if ( !get_post_meta( $variation_id, 'woocommerce_gielda_' . $name ) ) {
				        add_post_meta( $variation_id, 'woocommerce_gielda_' . $name, $value );
			        }
			        else {
				        update_post_meta( $variation_id, 'woocommerce_gielda_' . $name, $value );
			        }
		        }
	        }
        }

        public function woocommerce_product_after_variable_attributes( $loop, $variation_data, $variation ) {
	        $gielda = new WCI_Gielda($this->_pluginPath);
	        $post = get_post( $variation );
	        echo $this->loadTemplate('product_metabox', 'admin', array(
		        'post'          => $post,
		        'gieldaGroups'   => $gielda->getGieldaGroupsArray(),
		        'loop'          => $loop,
	        ));
        }

        public function ajaxGieldaCategoryAutocomplete()
        {
        	$gielda = new WCI_Gielda($this->_pluginPath);

        	$gieldaCategoriesOptions = $gielda->getGieldaCategoryTreeHTMLOptionsArray();

        	$result = array();

			if ( !empty( $_GET['term'] ) ) {
				foreach ( $gieldaCategoriesOptions as $key => $value ) {
					if ( stripos( $value, $_GET['term'] ) !== false && stripos( $key, 'group' ) === false ) {
						$result[] = array( 'value' => $key, 'text' => $value );
					}
				}
			}
        	echo json_encode( $result );
        	exit;
        }

        public function renderProductMetabox( $post )
        {
        	$gielda = new WCI_Gielda($this->_pluginPath);

        	echo $this->loadTemplate('product_metabox', 'admin', array(
        		'post' => $post,
        		'gieldaGroups' => $gielda->getGieldaGroupsArray()
        	));

        }

        public function saveProductMetabox($post_id)
        {
        	//if saving in a custom table, get post_ID

        	if (!empty($_POST[$this->getNamespace()]))
        	{
        		update_post_meta($post_id, 'woocommerce_gielda_disabled', '');


        		foreach ($_POST[$this->getNamespace()] as $name => $value)
        		{
        			if (!get_post_meta($post_id, 'woocommerce_gielda_' . $name))
        			{
        				add_post_meta($post_id, 'woocommerce_gielda_' . $name, $value);
        			} else {
        				update_post_meta($post_id, 'woocommerce_gielda_' . $name, $value);
        			}
        		}
        	}

		}

        /**
         * action
         */
        public function initMetaBoxes()
        {
        	add_meta_box('woocommerce_gielda', 'Gielda', array($this, 'renderProductMetabox'), 'product', 'normal', 'high');
        }

        public function initSettingsAction()
        {
        	// gielda fields
        	add_action( 'add_meta_boxes', array($this, 'initMetaBoxes') );
        	add_action( 'save_post', array($this, 'saveProductMetabox'), 10, 1);

        }

        /**
         * wordpress action
         *
         * should-be-protected method to save/update settings when changed by POST
         */
        public function updateSettingsAction()
        {
        	if (!empty($_POST))
        	{
        		// checkboxes
        		if (!empty($_POST['option_page']))
        		{
        			if ($_POST['option_page'] == 'woocommerce_gielda_settings')
        			{
        				update_option('woocommerce_gielda_catmap', '');
				        update_option('woocommerce_gielda_variants', '');
        				update_option('woocommerce_gielda_show_stock', '');
        				update_option('woocommerce_gielda_kupteraz', '');
        				update_option('woocommerce_gielda_disable_the_content_filter', '');

        				flush_rewrite_rules();
        			}

        			if (in_array($_POST['option_page'], array('woocommerce_gielda_settings', 'woocommerce_gielda_categories')))
        			{
        			    if (!empty($_POST[$this->getNamespace()]))
        			    {
    	        			foreach ($_POST[$this->getNamespace()] as $name => $value)
    	        			{
    	        				update_option('woocommerce_gielda_' . $name, $value);
    	        			}
        			    }
        			    do_action( 'woocommerce_gielda_clear_cache' );
        			}
        		}
        	}
        }

        /**
         * wordpress action
         *
         * inits css
         */
        public function initCssAction() {
			$current_screen = get_current_screen();

			if ( in_array( $current_screen->id, array( 'woocommerce_page_woocommerce_gielda', 'product' ) ) ) {
        	    wp_enqueue_style( 'gielda_admin_style', $this->getPluginUrl() . 'assets/css/admin.css' );
            }
        }

        /**
         * wordpress action
         *
         * inits js
         */
        public function initJsAction() {
        	$wp_scripts = wp_scripts();
        	$current_screen = get_current_screen();

            if ( in_array( $current_screen->id, array( 'woocommerce_page_woocommerce_gielda', 'product' ) ) ) {
	            wp_enqueue_script( 'select2' );
	            wp_enqueue_script( 'wc-enhanced-select' );
                wp_enqueue_script( 'woocommerce-gielda-admin', $this->getPluginUrl() . '/assets/js/admin.js', array( 'jquery' ), $this->scripts_version );
            }
        }

        /**
         * wordpress action
         *
         * inits menu
         */
        public function initAdminMenuAction()
        {
        	$gielda_page = add_submenu_page( 'woocommerce', __( 'Twoja Giełda', $this->getNamespace() ),  __( 'Twoja Giełda', $this->getNamespace() ) , 'manage_woocommerce', $this->getNamespace(), array( $this, 'renderGieldaPage') );
        }

        /**
         * wordpress action
         *
         * renders gielda submenu page
         */
	    public function renderGieldaPage()
	    {
		    $current_tab = ( empty( $_GET['tab'] ) ) ? 'settings' : sanitize_text_field( urldecode( $_GET['tab'] ) );

		    if (!in_array($current_tab, array('settings', 'categories')))
		    {
			    die('!.');
		    } else {


			    $product_categories = get_terms( 'product_cat' );

			    $gielda = new WCI_Gielda($this->_pluginPath);

			    $ceno_xml_url = site_url( Gielda::GIELDA_XML_URL );
//			    if ( get_option( 'permalink_structure', '' ) != '' ) {
//				    $ceno_xml_url = site_url( Gielda::GIELDA_XML_PRETTY_URL );
//			    }

			    echo $this->loadTemplate('submenu_gielda', 'admin', array(
					    'current_tab' => $current_tab,
					    'product_categories' => $product_categories,
					    'gieldaCategoriesOptions' => $gielda->getGieldaCategoryTreeHTMLOptionsArray(),
					    'ceno_xml_url' => $ceno_xml_url
				    )
			    );
		    }
	    }
    }
