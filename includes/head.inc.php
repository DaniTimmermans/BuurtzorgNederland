<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the page header (in html)
	// called by pagebuilder.inc.php init method
	class Head {
		public function getHtml($p_sGoBack) {
			// build page header with information from config.inc.php
			// $p_sGoBack contains string to move upwards in path (ie: ../)
			$head = "<title>" . TITLE . "</title>";
			$head .= "";
			$head .= "<meta name='keywords' content='your, tags' />";
			$head .= "<meta name='description' content='150 words' />";
			$head .= "<meta name='subject' content='your website\'s subject' />";
			$head .= '<link rel="stylesheet" type="text/css" href="'.$p_sGoBack.CSS_PATH.'styles.css">';
			$head .= '<script src="'.$p_sGoBack.JS_PATH.'functions.js"></script>';

			return $head;
		}
	}