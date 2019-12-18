<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the page menu (in html)
	// called by pagebuilder.inc.php CheckPage method
	//
	// return depending from PAGE
	// returns if page is before (false) of after (true) login
	//
	abstract class Secure {
		public static function checkPage() {
			switch(PAGE) {
				case "home"			:
				case "vacature"		: return false; break;
				case "admin"		: return true; break;
				case "gebruikers"	: return true; break;
				case "geheim" 		: return true; break;
				default				: return true;
			}
		}
	}