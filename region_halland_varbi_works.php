<?php

	/**
	 * @package Region Halland Varbi Works
	 */
	/*
	Plugin Name: Region Halland Varbi Works
	Description: Front-end-plugin som returnerar lediga jobb från Varbi
	Version: 1.3.0
	Author: Roland Hydén
	License: MIT
	Text Domain: regionhalland
	*/
		
	// Returnera lediga jobb (endast test-data)
	function get_region_halland_varbi_works($type = 1, $id = 0) {
		
		// Variabel för sökväg
    	if (!defined('VARBI_DIR')) {
	    	define('VARBI_DIR', __DIR__);
		}

    	// Autoload all classes in incs-folder
    	spl_autoload_register(function($className) {
			include_once VARBI_DIR . '/incs/' . $className . '.php';
		});

		// Preparera data
		$objVarbi = new Varbi();
		$intVarbi = $objVarbi->prepareVarbiData();

		if ($type == 1) {
			$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
		} else {
			$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_".$id.".txt";
		}
		
		$myVarbifile = fopen($strVarbiFilePath, "r");
		$myVarbiFileContent = fread($myVarbifile,filesize($strVarbiFilePath));
		$myUnSerializedVarbiFileContent = unserialize($myVarbiFileContent);
		
		// Returnera aktuell enhet
		return $myUnSerializedVarbiFileContent;

	}

	// Metod som anropas när pluginen aktiveras
	function region_halland_varbi_works_activate() {
		// Ingenting just nu...
	}

	// Metod som anropas när pluginen avaktiveras
	function region_halland_varbi_works_deactivate() {
		// Ingenting just nu...
	}
	
	// Vilken metod som ska anropas när pluginen aktiveras
	register_activation_hook( __FILE__, 'region_halland_varbi_works_activate');

	// Vilken metod som ska anropas när pluginen avaktiveras
	register_deactivation_hook( __FILE__, 'region_halland_varbi_works_deactivate');

?>