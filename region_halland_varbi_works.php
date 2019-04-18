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

		// vid 'init', anropa funktionen region_halland_register_utbildning()
	add_action('init', 'region_halland_register_lediga_jobb');

	// Denna funktion registrerar en ny post_type och gör den synlig i wp-admin
	function region_halland_register_lediga_jobb() {
		
		// Vilka labels denna post_type ska ha
		$labels = array(
	        'name' => _x( 'Lediga jobb', 'Post type general name', 'textdomain' ),
	        'singular_name' => _x( 'Lediga jobb', 'Post type singular name', 'textdomain' )
	    );
		
		// Inställningar för denna post_type 
	    $args = array(
	        'labels' => $labels,
	        'rewrite' => array('slug' => 'alla-lediga-jobb'),
			'show_ui' => false,
			'has_archive' => true,
			'publicly_queryable' => true,
			'public' => true,
			'show_in_menu' => false,
			'query_var' => false,
			'menu_icon' => 'dashicons-megaphone',
	        'supports' => array( 'title', 'editor', 'author', 'thumbnail')
	    );

	    // Registrera post_type
	    register_post_type('alla-lediga-jobb', $args);
	    
	}
		
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
		
		// Definiera 'post_title'
		$myPageTitle = "Visa ledigt jobb";
		
		// Kontrollera om denna post redan finns
		$ifExist = post_exists($myPageTitle);

		// Om posten inte finns så skapa en ny post
		if ($ifExist == 0) {
			
			// Definiera en array med aktuella värden
			$postarr = array();
			$postarr['post_title'] = "Visa ledigt jobb";
			$postarr['post_name'] = "ett-ledigt-jobb";
			$postarr['post_status'] = "publish";
			$postarr['post_type'] = "alla-lediga-jobb";
			$postarr['post_parent'] = 999999999;

			// Skapa en ny post
			wp_insert_post($postarr, $wp_error = false);
		
		}

		// Vi aktivering, registrera post_type "utbildning"
		region_halland_register_lediga_jobb();

		// Tala om för wordpress att denna post_type finns
		// Detta gör att permalink fungerar
	    flush_rewrite_rules();
	
	}

	// Metod som anropas när pluginen avaktiveras
	function region_halland_varbi_works_deactivate() {
		
		// Definiera 'post_title'
		$myPageTitle = "Visa ledigt jobb";
		
		// Definiera 'post_type'
		$myPostType = "alla-lediga-jobb";

		// Hämta post
		$myPost = get_page_by_title($myPageTitle, "", $myPostType);
		
		// Plocka ut ID ur objektet
		$myID = $myPost->ID;

		// Radera aktuell post
		wp_delete_post($myID, $force_delete = true);
	
	}
	
	// Vilken metod som ska anropas när pluginen aktiveras
	register_activation_hook( __FILE__, 'region_halland_varbi_works_activate');

	// Vilken metod som ska anropas när pluginen avaktiveras
	register_deactivation_hook( __FILE__, 'region_halland_varbi_works_deactivate');

?>