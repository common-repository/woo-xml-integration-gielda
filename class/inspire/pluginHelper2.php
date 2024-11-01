<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
	
	if (!class_exists('inspirePluginHelper2'))
	{
	    abstract class inspirePluginHelper2
	    {  
	    	protected $_plugin;
	    	
	    	public function __construct(inspirePlugin2 $plugin)
	    	{
	    		$this->_plugin = $plugin;
	    	}
	    }
  
	}
