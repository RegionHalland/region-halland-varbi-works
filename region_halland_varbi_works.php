<?php

	/**
	 * @package Region Halland Varbi Works
	 */
	/*
	Plugin Name: Region Halland Varbi Works
	Description: Front-end-plugin som returnerar lediga jobb från Varbi
	Version: 1.4.1
	Author: Roland Hydén
	License: MIT
	Text Domain: regionhalland
	*/
		
	// Returnera lediga jobb (endast test-data)
	function get_region_halland_varbi_works($type = 1, $id = 0, $mid = 0, $fid = 0, $gid = 0) {
		
		// Variabel för sökväg
    	if (!defined('VARBI_DIR')) {
	    	define('VARBI_DIR', __DIR__);
		}

    	// Autoload all classes in incs-folder
    	spl_autoload_register(function($className) {
			include_once VARBI_DIR . '/incs/' . $className . '.php';
		});

    	$myEnvToken = ENV("VARBI_UPDATE_TOKEN");
    	$myToken = 0;
		if(isset($_GET["token"])){
            $myToken = $_GET["token"];
        }
        $myStatus = 0;
        if ($myToken == $myEnvToken) {
	       $myStatus = 1;
        }

        if ($myStatus == 1) {
			
			// Preparera sök-data
			// Inte klart ännu
			//$objFindwise = new Findwise();
			//$intFindwise = $objFindwise->prepareFindwiseData();

			// Preparera data
			$objVarbi = new Varbi();
			$intVarbi = $objVarbi->prepareVarbiData();
			
			// Preparera filter-data
			$objFilter = new Filter();
			$intFilter = $objFilter->prepareFilterData();

			// Preparera dropdown-data
			// Inte klart ännu
			//$objDropdown = new Dropdown();
			//$intDropdown = $objDropdown->prepareDropdownData();

        }

		$myVarbiFileContent = region_halland_varbi_works_get_list_data($type, $id, $mid, $fid, $gid);
//
		// Returnera aktuell enhet
		return $myVarbiFileContent;

	}
	
	function region_halland_varbi_works_get_list_data($type, $id, $mid, $fid, $gid) {

		if ($type == 1) {
			$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_lista.txt";
		} elseif ($type == 2) {
			$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_".$mid."_".$fid."_".$gid.".txt";
		} else {
			$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "varbi_".$id.".txt";
		}
		
		$varbiFleExist = file_exists($strVarbiFilePath);

		if ($varbiFleExist) {
			$myVarbifile = fopen($strVarbiFilePath, "r");
			$myVarbiFileContent = fread($myVarbifile,filesize($strVarbiFilePath));
			$myUnSerializedVarbiFileContent = unserialize($myVarbiFileContent);
		} else {
			$myUnSerializedVarbiFileContent = array();
		}
		
		// Returnera aktuell enhet
		return $myUnSerializedVarbiFileContent;
	
	}

	// Hämta array med kommuner
	function region_halland_varbi_works_get_kommun_data() {
		
		$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_kommun_0_0_0.txt";
		
		$myVarbifile = fopen($strVarbiFilePath, "r");
		$myVarbiFileContent = fread($myVarbifile,filesize($strVarbiFilePath));
		$myUnSerializedVarbiFileContent = unserialize($myVarbiFileContent);

		// Returnera aktuell enhet
		return $myUnSerializedVarbiFileContent;

	}

	// Hämta array med grupper
	function region_halland_varbi_works_get_field_data() {
		
		$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_0_0_0.txt";
		
		$myVarbifile = fopen($strVarbiFilePath, "r");
		$myVarbiFileContent = fread($myVarbifile,filesize($strVarbiFilePath));
		$myUnSerializedVarbiFileContent = unserialize($myVarbiFileContent);

		// Returnera aktuell enhet
		return $myUnSerializedVarbiFileContent;

	}

	// Hämta array med field
	function region_halland_varbi_works_get_group_data() {
		
		$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "dropdown/varbi_jobtype_field_group_0_0_0.txt";
		
		$myVarbifile = fopen($strVarbiFilePath, "r");
		$myVarbiFileContent = fread($myVarbifile,filesize($strVarbiFilePath));
		$myUnSerializedVarbiFileContent = unserialize($myVarbiFileContent);

		// Returnera aktuell enhet
		return $myUnSerializedVarbiFileContent;

	}

	// Hämta array med grupper
	function region_halland_varbi_works_get_test_data() {
		
		$strVarbiFilePath = ENV("VARBI_DATA_PATH") . "filter/varbi_filter_1384_3_0.txt";
		
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