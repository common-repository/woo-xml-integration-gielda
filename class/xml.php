<?php

class GieldaXml extends inspirePlugin2 {

	/**
	 * Default empty XML content.
	 */
	const DEFAULT_XML_CONTENT = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<offers xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" version=\"1\">\n</offers>";

	protected $_pluginNamespace = 'woocommerce_gielda';

	protected $gielda;

	const DEFAULT_EXPORT_LANG = 'pl';
	const WPDESK_FILTER_PRODUCT_SHOULD_EXPORT = 'wpdesk_gielda_product_should_export';


	public function __construct() {
		$this->_initBaseVariables();
		$this->gielda = new WCI_Gielda( $this->_pluginPath );
	}

	public function isSecure() {
		return true;
	}

	/**
	 * Get XML.
	 *
	 * @param bool $force_generate Force generate - ignore cache.
	 *
	 * @return string
	 */
	public function get_xml( $force_generate = false ) {
		$gielda_xml_content   = get_option( 'gielda_xml_content', self::DEFAULT_XML_CONTENT );
		$gielda_xml_generated = get_transient( WCI_Gielda::$transient_name_generated );
		if ( $force_generate || ! $gielda_xml_generated ) {
			$gielda_xml_content = $this->render();
			delete_option( 'gielda_xml_content' );
			if ( add_option( 'gielda_xml_content', $gielda_xml_content, '', 'no' ) ) {
				set_transient( WCI_Gielda::$transient_name_generated, '1', 60 * 120 );
			}
		}

		return $gielda_xml_content;
	}


	/**
	 *
	 * @param array $productPost
	 *
	 * @return WC_Product_Simple
	 */
	protected function getProduct( $productPost ) {
		if ( function_exists( 'wc_get_product' ) ) {
			return wc_get_product( $productPost );
		} else {
			return new WC_Product( $productPost->ID );
		}
	}

	protected function getProductCategory( $product ) {
		$categories = get_the_terms( wpdesk_get_product_id( $product ), 'product_cat' );

		if ( $this->getSettingValue( 'catmap' ) != '' ) // mapowanie
		{

			if ( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
			return		$newGieldaCategoryId = $this->getSettingValue( 'term_' . $category->term_id );

				}
			}


		}

		// jesli tu dotarl, to znaczy ze nie udalo sie z roznych powodow zmapowac kategorii
		//$terms = get_the_terms( $product->id, 'product_cat' );
		if ( ! empty( $categories ) ) {
			$catArray =array();
			foreach ( $categories as $cat ) {
				$catArray[] = $cat->name;
			}

			return implode( ' - ', $catArray );
		} else {
			return false;
		}

	}

	protected function renderProductPost( $productPost, $groupArray ) {
		global $product;
		$product = $this->getProduct( $productPost );

		if ( $this->getSettingValue( 'variants', '' ) != '' ) {
			if ( $product->is_type( 'variable' ) ) {
				return '';
			}
		}
		$productPost = wpdesk_get_product_post( $product );

		$variation = false;
		if ( $product->is_type( 'variation' ) ) {
			$variation      = true;
			$parent_product = wc_get_product( wpdesk_get_variation_parent_id( $product ) );
			if ( $parent_product === false ) {
				// variation without parent?
				// uncomment for debug
				//error_log( print_r( $productPost->ID, true ) );
				//error_log( print_r( $productPost->post_title, true ) );
				return '';
			}
			$product_parent_post = wpdesk_get_product_post( $parent_product );
		} else {
			$parent_product = $product;
		}

		global $post;
		global $post_id;
		$post_id = $productPost->ID;
		$post    = $productPost;

		if ( $variation ) {
			$disabled = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_disabled', true );
		} else {
			$disabled = wpdesk_get_product_meta( $product, 'woocommerce_gielda_disabled', true );
		}
		if ( $disabled == 1 ) {
			return "";
		}
		if ( $variation ) {
			$disabled = wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_disabled', true );
			if ( $disabled == 1 ) {
				return "";
			}
		}

		$xml = '<o ';
		$xml .= 'id="' . wpdesk_get_product_id( $product ) . '" ';
		$xml .= 'url="' . htmlspecialchars( get_permalink( wpdesk_get_product_id( $product ) ) ) . '" ';
		$xml .= 'sku="'.get_post_meta( wpdesk_get_product_id( $product ), '_sku', true ).'" ';

		add_filter( 'woocommerce_get_tax_location', array( $this, 'woocommerce_get_tax_location' ), 10, 3 );
		$xml .= 'price="' . round( wpdesk_get_price_including_tax( $product ), 2 ) . '" ';
		remove_filter( 'woocommerce_get_tax_location', array( $this, 'woocommerce_get_tax_location' ), 10, 3 );

		if ( $product->get_stock_quantity() == '0' || ! $product->is_in_stock() ) {
			$xml .= 'avail="' . '99' . '" ';
		} else {
			$xml .= 'avail="' . '1' . '" ';
		}

		if ( $product->has_weight() ) {
			$xml .= 'weight="' . wc_get_weight( wc_format_decimal( $product->get_weight() ), 'kg' ) . '" ';
		} else {
			if ( $variation ) {
				if ( $parent_product->has_weight() ) {
					$xml .= 'weight="' . wc_get_weight( wc_format_decimal( $parent_product->get_weight() ), 'kg' ) . '" ';
				}
			}
		}

		if ( $this->getSettingValue( 'show_stock' ) == 'on' ) {
			$xml .= 'stock="' . $product->is_in_stock() . '" ';
		}
		$xml .= '>' . "\n";

		if ( $variation ) {
			$name = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_alternative_name', true );
		} else {
			$name = wpdesk_get_product_meta( $product, 'woocommerce_gielda_alternative_name', true );
		}
		if ( $variation && empty( $name ) ) {
			$name = wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_alternative_name', true );
			if ( ! empty( $name ) ) {
				/** @var  WC_Product_Variation $product */
				$attributes = $product->get_attributes();
				$name       .= ' - ';
				$first      = true;
				foreach ( $attributes as $attribute_name => $attribute_value ) {
					if ( taxonomy_exists( $attribute_name ) ) {
						$term = get_term_by( 'slug', $attribute_value, $attribute_name );
						if ( ! is_wp_error( $term ) && ! empty( $term->name ) ) {
							$attribute_value = $term->name;
						}
					}
					if ( ! $first ) {
						$name .= ', ';
					} else {
						$first = false;
					}
					$name .= $attribute_value;
				}
			}
		}
		if ( empty( $name ) ) {
			$name = $product->get_title();
			if ( $variation ) {
				/** @var  WC_Product_Variation $product */
				$name       = $product->get_title();
				$attributes = $product->get_attributes();
				$name       .= ' - ';
				$first      = true;
				foreach ( $attributes as $attribute_name => $attribute_value ) {
					if ( taxonomy_exists( $attribute_name ) ) {
						$term = get_term_by( 'slug', $attribute_value, $attribute_name );
						if ( ! is_wp_error( $term ) && ! empty( $term->name ) ) {
							$attribute_value = $term->name;
						}
					}
					if ( ! $first ) {
						$name .= ', ';
					} else {
						$first = false;
					}
					$name .= $attribute_value;
				}
			}
		}

		if ( $variation ) {
			$desc = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_alternative_desc', true );
		} else {
			$desc = wpdesk_get_product_meta( $product, 'woocommerce_gielda_alternative_desc', true );
		}
		if ( empty( $desc ) ) {
			if ( $variation ) {
				$desc = get_post_meta( wpdesk_get_variation_id( $product ), '_variation_description', true );
				if ( empty( $desc ) ) {
					$desc = wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_alternative_desc', true );
					if ( empty( $desc ) ) {
						if ( $this->getSettingValue( 'disable_the_content_filter' ) == 'on' ) {
							$desc = $product_parent_post->post_content;
						} else {
							$desc = apply_filters( 'the_content', $product_parent_post->post_content );
						}
					}
				}
			} else {
				if ( $this->getSettingValue( 'disable_the_content_filter' ) == 'on' ) {
					$desc =  $productPost->post_content ;
				} else {
					$desc =  apply_filters( 'the_content', $productPost->post_content ) ;
				}
			}
		}
		$category = $this->getProductCategory( $parent_product );
		if ( ! empty( $category ) ) {
		$xml .= '<na cat="' . $category . '"><![CDATA[' . $name . ']]></na>' . "\n";
		}
else {
		$xml .= '<na><![CDATA[' . $name . ']]></na>' . "\n";
}
		
		
		$xml .= '<de><![CDATA[' . $desc . ']]></de>' . "\n";


		$thumbId = get_post_thumbnail_id( wpdesk_get_product_id( $product ) );

		if ( $variation && empty( $thumbId ) ) {
			$thumbId = get_post_thumbnail_id( wpdesk_get_product_id( $parent_product ) );
		}

		if ( ! empty( $thumbId ) ) {
			$thumb = wp_get_attachment_image_src( $thumbId, 'full' );
			$xml   .= '<img>';
			$img= str_replace("https","http",$thumb[0]);
			$xml   .= esc_attr( $img ) . '|';
			 
	    $attachment_ids = $product->get_gallery_image_ids();

    foreach( $attachment_ids as $attachment_id ) 
        {
          // Display the image URL
           $Original_image_url[] = wp_get_attachment_url( $attachment_id );

        }
		if(!empty($Original_image_url[0])){
						$img0= str_replace("https","http",$Original_image_url[0]);

			$xml   .= $img0 . '|';
		}
		if(!empty($Original_image_url[1])){
				$img1= str_replace("https","http",$Original_image_url[1]);
			$xml   .= $img1 . '|';
		}
		if(!empty($Original_image_url[2])){
						$img2= str_replace("https","http",$Original_image_url[2]);
			$xml   .= $img2 . '|';
		}
			
			
			$xml   .= '</img>' . "\n";
		}

		if ( $this->getSettingValue( 'brandxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'brandxml' ) );
			$xml   .= '<br><![CDATA['.$slug.']]></br>' . "\n";
		}

		if ( $this->getSettingValue( 'kupteraz' ) != '' ){
			$kt_product = wpdesk_get_product_id( $product );
			$kt_product2 = wc_get_product( $kt_product );
if( $kt_product2->is_type( 'simple' ) ) {
$stock=$kt_product2->get_stock_quantity();
			$xml   .= '<type item="'.$stock.'">simple</type>' . "\n";
			$xml   .= '<va>0</va >' . "\n";

}
else {
	
	$namekt = $product->get_title();
	$kt_product = wpdesk_get_product_id( $product );
		global $wpdb;

	$results2 = $wpdb->get_results( "SELECT p.post_title FROM {$wpdb->prefix}posts as p INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID=pm.post_id WHERE p.post_parent='$kt_product' and p.post_type='product_variation' and pm.meta_key='_stock' and pm.meta_value>0");
	
	
	$app=array();
foreach($results2 as $term2){
	if($term2->post_title!=$product->get_title()){
$app[]= str_replace($namekt.' - ', '', $term2->post_title);
	}
}
$stock=count($app);
$commas = implode("|", $app);
	
			$xml   .= '<type item="'.$stock.'">nosimple</type>' . "\n";
			$xml   .= '<va>'.$commas.'</va >' . "\n";
}
		}

		if ( $this->getSettingValue( 'colorsxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'colorsxml' ) );
			$xml   .= '<color><![CDATA['.$slug.']]></color>' . "\n";
		}
		if ( $this->getSettingValue( 'collectsxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'collectsxml' ) );
			$xml   .= '<collec><![CDATA['.$slug.']]></collec>' . "\n";
		}
		if ( $this->getSettingValue( 'ramxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'ramxml' ) );
			$xml   .= '<ram><![CDATA['.$slug.']]></ram>' . "\n";
		}
		if ( $this->getSettingValue( 'procesorxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'procesorxml' ) );
			$xml   .= '<proces><![CDATA['.$slug.']]></proces>' . "\n";
		}
		if ( $this->getSettingValue( 'dyskxml' ) != '' ){
$slug=$product->get_attribute( 'pa_'.$this->getSettingValue( 'dyskxml' ) );
			$xml   .= '<hard><![CDATA['.$slug.']]></hard>' . "\n";
		}


		
		
		
		

		$parent_product_same_group = false;
		if ( $variation ) {
			$variation_gielda_group = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_gielda_group', true );
			if ( $variation_gielda_group
			     == wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_gielda_group', true )
			) {
				$parent_product_same_group = true;
			}
		}
/*		$xml .= '<attrs>' . "\n";
		foreach ( $groupArray['fields'] as $attrKey => $attrField ) {
			$value = null;
			if ( $variation ) {
				if ( ! empty( $variation_gielda_group ) ) {
					$value = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_' . $attrKey, true );
					if ( empty( $value ) ) {
						$value = wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_' . $attrKey, true );
					}
				} else {
					$value = wpdesk_get_product_meta( $parent_product, 'woocommerce_gielda_' . $attrKey, true );
				}
			} else {
				$value = wpdesk_get_product_meta( $product, 'woocommerce_gielda_' . $attrKey, true );
			}
			if ( ! empty( $value ) ) {
				$xml .= '<a name="' . $attrKey . '"><![CDATA[' . $value . ']]></a>' . "\n";
			}
		}
		$xml .= '</attrs>' . "\n"; */
		$xml .= '</o>' . "\n";

		return $xml;
	}

	public function getIdsFromPostsQuery( $query ) {
		global $wpdb;
		$objects = $wpdb->get_results( $query );

		$result = array();
		foreach ( $objects as $object ) {
			$result[] = $object->ID;
		}

		return $result;
	}

	public function getProductGieldaGroup( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( $product && $product->is_type( 'variation' ) ) {
			$parent_product = wpdesk_get_variation_parent_id( $product );
			if ( $parent_product ) {
				$woocommerce_gielda_gielda_group = wpdesk_get_variation_meta( $product, 'woocommerce_gielda_gielda_group', true );
				if ( ! empty( $woocommerce_gielda_gielda_group ) ) {
					return $woocommerce_gielda_gielda_group;
				} else {
					$woocommerce_gielda_gielda_group = wpdesk_get_product_meta( wpdesk_get_variation_parent_id( $product ), 'woocommerce_gielda_gielda_group', true );
					if ( ! empty( $woocommerce_gielda_gielda_group ) ) {
						return $woocommerce_gielda_gielda_group;
					}
				}
			} else {
				return 'other';
			}
		} else {
			$woocommerce_gielda_gielda_group = wpdesk_get_product_meta( $product, 'woocommerce_gielda_gielda_group', true );
			if ( ! empty( $woocommerce_gielda_gielda_group ) ) {
				return $woocommerce_gielda_gielda_group;
			}
		}

		return 'other';
	}

	/**
	 * Returns product language from WPML if it's possible
	 *
	 * @param WC_Product $product
	 *
	 * @return null|string
	 */
	private function getSitepressProductLanguage( WC_Product $product ) {
		/** @var SitePress $sitepress */
		global $sitepress;

		if ( $sitepress instanceof SitePress ) {
			return $sitepress->get_language_for_element( wpdesk_get_product_id( $product ), 'post_product' );
		} else {
			return null;
		}
	}

	/**
	 * Should product be exported or not
	 *
	 * @param WC_Product $product
	 *
	 * @return bool
	 */
	private function shouldProductBeExported( WC_Product $product ) {
		$language = $this->getSitepressProductLanguage( $product );

		$shouldBeSaved = is_null( $language ) || self::DEFAULT_EXPORT_LANG === $language;

		return apply_filters( self::WPDESK_FILTER_PRODUCT_SHOULD_EXPORT, $shouldBeSaved, $product, $language );
	}

	/**
	 * @return string
	 */
	public function render() {
		global $wpdb;

		do_action( 'gielda_render' );

		$variants = false;
		if ( $this->getSettingValue( 'variants', '' ) != '' ) {
			$variants = true;
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";

		$xml .= '<offers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1">' . "\n";

		$groupsArray = $this->gielda->getGieldaGroupsArray();

		$where_post_type = " and p.post_type = 'product' ";
		if ( $variants ) {
			$where_post_type = " and p.post_type in ( 'product', 'product_variation' ) ";
		}
		$ids = $this->getIdsFromPostsQuery( "select DISTINCT p.ID from {$wpdb->prefix}posts p where p.post_status = 'publish' " . $where_post_type . " order by ID, post_date desc" );

		foreach ( $ids as $id ) {
			$groupKey   = $this->getProductGieldaGroup( $id );
			$groupArray = $groupsArray[ $groupKey ];

			if ( $this->shouldProductBeExported( $this->getProduct( $id ) ) ) {
				$product_xml = $this->renderProductPost( $id, $groupArray );

				if ( ! empty( $product_xml ) ) {
					if ( ! isset( $groupsArray[ $groupKey ]['xml'] ) ) {
						$groupsArray[ $groupKey ]['xml'] = array();
					}
					$groupsArray[ $groupKey ]['xml'][] = $product_xml;
				}
			}
		}

		foreach ( $groupsArray as $groupKey => $groupArray ) {
			if ( isset( $groupsArray[ $groupKey ]['xml'] ) && count( $groupsArray[ $groupKey ]['xml'] ) ) {
				$xml .= '<group name="' . $groupKey . '">' . "\n";
				foreach ( $groupsArray[ $groupKey ]['xml'] as $product_xml ) {
					$xml .= $product_xml;
				}
				$xml .= '</group>' . "\n";
			}
		}

		$xml .= '</offers>' . "\n";

		do_action( 'gielda_render_end' );

		return $xml;

	}

	public function checkIdsGroupIfNotEmpty( $ids ) {
		if ( empty( $ids ) ) {
			return false;
		}

		foreach ( $ids as $id ) {
			$disabled = get_post_meta( $id, 'woocommerce_gielda_disabled', true );
			if ( ! $disabled ) {
				return true;
			}
		}

		return false;
	}

	public function woocommerce_get_tax_location( $location, $tax_class, $customer ) {
		$location = array( 'PL', '', '', '' );

		return $location;
	}

	public function __toString() {
		return $this->render();
	}


}
