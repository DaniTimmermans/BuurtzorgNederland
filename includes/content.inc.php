<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the page content (in html)
	// called by pagebuilder.inc.php init method
	//
	// Content depending on PAGE
	//
	// The requested page and classname is calculated and the
	// appropiate page file is loaded from the pages folder
	class Content {
		public function getHtml() {
			// use of variable class name.
			// Each page it's own class
			// Every class contains getHtml() method
            if(file_exists(PAGES_PATH . PAGE . ".inc.php")) {
				 require_once(PAGES_PATH . PAGE . ".inc.php");
				 $classNaam = PAGE . "Page";
				 $objPage = new $classNaam;
				 return $objPage->getHtml();
			 } else { // non existant page also gives output
				 return "Deze pagina bestaat niet.";
			 }
		}
	} // class
?>