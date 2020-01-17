<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the login part (in html)
	// called by pagebuilder.inc.php init method
	// only one implementation so abstract implementation
	abstract class Login {

		// create login form with hidden name frmLogin to be processed in this->checkCredentials()
		public static function getLoginForm() {
			$form = "<h3>". PAGE ."</h3><br>";
			$form .= "<form action='" . $_SERVER['REQUEST_URI'] . "' enctype='multipart/formdata' method='post'>";
				$form .= "<input type='text' name='email' value='wie ben je' />";
				$form .= "<input type='password' name='password' value='' />";
				// add hidden form Login for further processing
				$form .= "<input type='hidden' name='frmLogin' value='frmLogin' />";
				$form .= "<input type='submit' value='Inloggen' />";
			$form .= "</form>";
			return $form;
		} // function

		public static function checkIfLoggedIn() {
			//check if user is already logged in or not
			if(isset($_SESSION['user']['isloggedin'])) {
				if($_SESSION['user']['isloggedin'] === true) {
					return true; //do not show loginform but content, user already loggedin
				} else {
					return false; //show loginform, not loggedin yet
				}
			} // if isset
			else {
				return false; //show loginform, not loggedin yet
			} // else
		} // function

		public static function checkCredentials() {
			// LETOP: return true -> show LOGINFORM
			// LETOP: return false -> show CONTENT

			if(isset($_POST['frmLogin'])) {
				//echo "form submitted, credentials checked and they are ok!!";


				$username 	= $_POST['email'];
				$password 	= $_POST['password'];
				// check credentials in database
				$sql = "SELECT uuid,password,username,email,role FROM tb_users WHERE username = ? AND status = ?";
				$aData = array($username, '1');
				// run query and obtain result
				$result = Database::getData($sql, $aData);
				// result = arrays with matching user

				// check length array (user not matching role)
				if (count($result) == 0) { // no user found
					return true; // go back to login screen
				} // if
				// get passwordHASH
				$pwh = $result[0]['password'];
				// use this method to verify if password matches
				$bool = password_verify($password, $pwh);

				if($bool === true) {
					// current status: login. Must be replaced by real user check
					$_SESSION['user']['isloggedin'] = true;
					$_SESSION['user']['uuid'] 		= $result[0]['uuid'];
					$_SESSION['user']['username'] 	= $result[0]['username'];
					$_SESSION['user']['email'] 		= $result[0]['email'];
					$_SESSION['user']['role'] 		= $result[0]['role'];
					return false; 	// == show content
				} else {
					return true;	// == show login form again
				} // else
			} // if isset
			//show loginform, there is no post or whatever
			return true; 			// == show login form again
		} // function
	} // class
