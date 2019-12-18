<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the page footer (in html)
	// called by pagebuilder.inc.php init method
	class Footer {
		public function getHtml() {
			$footer = "<copyright> Vista College, Erik Steens/Jeroen Wilmes, A24M & A24N";
			$footer .= "<br />";
			$footer .= "<div id='colorcontainer'>";
			$footer .= "<div id='color1'></div>";
			$footer .= "</div>";


			return $footer;
		}
	}