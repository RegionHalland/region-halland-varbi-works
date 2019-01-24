<?php

	/**
	 * @package Region Halland Varbi Works
	 */
	/*
	Plugin Name: Region Halland Varbi Works
	Description: Front-end-plugin som returnerar lediga jobb från Varbi
	Version: 1.0.0
	Author: Roland Hydén
	License: MIT
	Text Domain: regionhalland
	*/

	// Returnera lediga jobb (endast test-data)
	function get_region_halland_varbi_works_test() {
		
		// Hämta data från Varbi		
		$arrData = file_get_contents(env('VARBI_TEST_FEED'));
		
		// Decoda data till rätt json-format
		$arrDecodedData = json_decode($arrData, true);
		
		// Returnera data
		return $arrDecodedData;
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