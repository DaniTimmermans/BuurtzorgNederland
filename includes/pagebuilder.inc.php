<?php
    //phpinfo();
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Pagebuilder framework controller

	Instantiated by index.php
*/
	class Pagebuilder {
		// Properties (mainly objects to store page parts)
		private $objHead;		// page header object
		private $objFooter;		// page footer object
		private $login;			// Boolean logged in or not
		private $objMainmenu;	// Mainmenu object
		private $objContent;	// content object (page dependant)
		private $goBack;		// return location

		// methods
		public function __construct() {
			//Constructor for a complete page
			include_once("includes/config.inc.php"); 	// contains page selector
			$this->checkPostAndGet();
			$this->definePage(); 						// gets PAGE, ACTION and PARAM
			$this->init(); 								// instantiates Head,Mainmenu,Login and Content objects
			$this->login = $this->checkPage(); 			// Flag: true or false to allow for further login processing
		}

		private function checkPostAndGet() {
			// filter all POST and GET variables from unwanted content
			if(isset($_POST)) {
				foreach($_POST as $key => $value) {
					$_POST[$key] = trim(strip_tags($value));
				}
			}
			if(isset($_GET)) {
				foreach($_GET as $key => $value) {
					$_GET[$key] = trim(strip_tags($value));
				}
			}
		}

		private function checkPage() { 					// checks if page is "secret"
			$bool = Secure::checkPage(); 				// returns true (secret) or false (show content)
			if($bool === true) {
				$bool = Login::checkIfLoggedIn();
				if($bool === false) {					// compare type & value
					return Login::checkCredentials(); 	// show login or not, depends on valid credentials
				} else {
					return false; 						// no loginform, secret page but user is already logged in
				}
			} else {
				return false; 							// no loginform, no secretpage
			}
		}

		private function init() { 						// called by __construct
			//Initializes page and builds page object properties
			include_once(INCLUDES_PATH . "head.inc.php");
			$this->objHead = new Head(); 				// instantiates Head and returns page heading

			include_once(INCLUDES_PATH . "mainmenu.inc.php");
			$this->objMainmenu = new Mainmenu(); 		// instantiates Mainmenu and returns page manu

			//Include some abstract classes, no instance needed
			require_once(INCLUDES_PATH 		. "secure.inc.php");
			require_once(INCLUDES_PATH 		. "login.inc.php");
            require_once(INCLUDES_PATH 		. "database.inc.php");
            require_once(PAGES_PATH 		. "core.inc.php"); //extends pages
            require_once(INTERFACES_PATH 	. "ipage.inc.php"); //implements pages

			include_once(INCLUDES_PATH . "content.inc.php");
			$this->objContent = new Content(); 			// instantiates Content and resurns page content

			include_once(INCLUDES_PATH . "footer.inc.php");
			$this->objFooter = new Footer();
		}

		private function definePage() { // called by __construct()
			// when parameter 'url' parsed, set variable and strip to PAGE
			$url = isset($_GET['url']) ? $_GET['url'] : null;
			$url = rtrim($url, '/');					// strip unwanted spaces
			$url = explode('/',$url);					// split the URI into parameters
			$page = $url[0];							// get the first parameter
			if(strlen($page == "")) { $page = "home"; }
            define("PAGE", $page);						// Define the requested PAGE

			// Get ACTION and PARAM if existing from url
			$this->goBack = "";
			if (array_key_exists("1", $url)){ 			// Check if action is parsed
				define("ACTION", $url[1]);
				$this->goBack = "../";
			}

			if (array_key_exists("2", $url)){ 			// Check if parameter is parsed
				define("PARAM", $url[2]);
				$this->goBack = "../../";
			}
			// check if root

			if (count($url)==1 && ($url[0]==""  || $url[0]=="index.php")) {
				echo "Dit zou root moeten zijn";
				// nu rootfolder aanpassen en daarmee alle programma subfolders
				// eventueel in $_SESSION zetten

				// gaat nog mis als je pad expliciet intypt
				//
			}
		}

		public function createPage() {
			// heredoc requestred by index
			// build and return string from the page properties
				$html = "<!DOCTYPE html>"; 				// create outputvariable
					$html .= "<html>";					// add pagehtml codes
						$html .= "<head>";
						$html .= "<link rel='icon' href='images/icons/favicon.png' type='image/x-icon'>";
						$html .= $this->objHead->getHtml($this->goBack);
						$html .= "</head>";
						$html .= "<body>";				// page body
							$html .= "<div id='maincontainer'>";

								//MAINMENU
								$html .= "<nav>";
									$html .= $this->objMainmenu->getHtml();
								$html .= "</nav>";

								$html .= "<div id='aftermenudiv'></div>";

								$html .= "<main>";
									//CONTENT: if login screen selected
									if($this->login === true) { 		// check login status value and type
										// in case of login screen show login form
										// present login screen
										$html .= Login::getLoginForm(); // abstract class, direct access
									} else { 							// login === false
										// in case of normal screen show regular page content
										$html .= $this->objContent->getHtml();
									}

									//FOOTER
									$html .= "<footer>";
										$html .= $this->objFooter->getHtml();
									$html .= "</footer>";

								$html .= "</main>";

							$html .= "</div><!--end maincontainer-->";

						$html .= "</body>";				// end of page body

					$html .= "</html>";					// end of entirehtml build-up
			// $html contains ENTIRE page. This is returned to index for echoing

			return $html;
		}
	}