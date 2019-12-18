<?php
/*
	pagebuilder framework application
	index.html
	creates new Pagebuilder
	echos page

*/
	session_start(); //be sure there is no html output before session_start()
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	// standaard voor debugging, weghalen in productie want er mag geen html output zijn voor regel 19.
	//echo '<meta http-equiv="refresh" content="10" >';

	include_once("includes/pagebuilder.inc.php");
	$objPage = new Pagebuilder();

	$webPage = $objPage->createPage();

	echo $webPage;