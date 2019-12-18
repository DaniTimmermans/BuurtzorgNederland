<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
// interface for getHtml function. 
// Interface hence not implemented
// Every page class (extended core class) implements the getHtml
// used by all page.inc.php (interface implementations)
	interface iPage {
		public function getHtml();
	}