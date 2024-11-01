<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
	
	if (!class_exists('inspirePluginHelper2'))
	{
	    abstract class inspirePluginWidget2 extends WP_Widget
	    { 
	    	protected $_plugin;
	    	
	    	public function __construct(inspirePlugin $plugin)
	    	{
	    		$this->_plugin = $plugin;
	    	}
	    }
  
	}
