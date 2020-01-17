<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
//	Create global constants for file locations
	define("TITLE", "Buurtzorg, uw zorg is onze zorg");

//  rootfolder contains the LOCAL root path of the index.php file
//  createdto avoid sticking url arguments (repeated arguments)
    //$rootfolder = "/wilmesjeroen/pagebuilder24/";
    //$rootfolder = "/pagebuilderjeroen/";
    //$rootfolder = "/wilmesjeroen/versie2.0/";
    //$rootfolder = "/pagebuilder24/";
    //$rootfolder = "https://buurtzorg.eriksteens.nl/";
    $rootfolder = "/steenserik/121835/BuurtzorgNederland/";
		// gemaakt door Dani Timmermans
    // url:  /PAGE/ACTION/PARAM

    define("ROOT"			    , "");

    //Menuitems paths
    define("HOME_PATH"          							, $rootfolder . ROOT . "home");
    define("VACATURE_PATH"      							, $rootfolder . ROOT . "vacature");
    define("GEBRUIKERS_PATH"    							, $rootfolder . ROOT . "gebruikers");
    define("GEHEIM_PATH"        							, $rootfolder . ROOT . "geheim");
    define("LOGOUT_PATH"        							, $rootfolder . ROOT . "logout");
    define("ADMIN_PATH"         							, $rootfolder . ROOT . "admin");
		define("SOLLICITANT_PATH"         				, $rootfolder . ROOT . "sollicitant");


    //Rootfolder paths
	define("CSS_PATH"		    	, ROOT 			. "css/");
	define("IMAGES_PATH"	    , ROOT 			. "images/");
	define("ICONS_PATH"		    , IMAGES_PATH 	. "icons/");
	define("USERS_PATH"		    , IMAGES_PATH 	. "users/");
	define("INCLUDES_PATH"	  , ROOT 			. "includes/");
	define("JS_PATH"		    	, ROOT 			. "js/");
	define("PAGES_PATH"				, ROOT			. "pages/");
	define("INTERFACES_PATH"	, ROOT			. "interfaces/");
	define("ROLE_WTV" 				, 3);
	define("ROLE_LID" 				, 2);
	define("ROLE_PZ" 					, 1);
	define("ROLE_ADMIN" 			, 4);
