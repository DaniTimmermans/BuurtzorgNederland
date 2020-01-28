<?php
	/**
	 * GebruikersPage class.
	 *
	 * @extends Core
	 * @implements iPage
	 */
/*
	Contains details of page information
	returns the built html
	Class Name convention: <pagename>Page
	Must contain iPage interface implementation
	Called by content.inc.php

	Possible ACTION: create, read, update , delete
	Possible PARAM: uuid (@details)
*/
	class GebruikersPage extends Core implements iPage {

		public function getHtml() {
			if(defined('ACTION')) {			// process the action obtained is existent
				switch(ACTION) {
					// get html for the required action
					case "create"	: return $this->create(); break;
					case "read"		: return $this->read(); break;
					case "update"	: return $this->update();break;
					case "delete"	: return $this->delete();
				}
			} else { // no ACTION so normal page
				$table 	= $this->getData();		// get users from database in tableform
				$button = $this->addButton("/create", "Toevoegen");	// add "/add" button. This is ACTION button
				// first show button, then table
				$html = $button . "<br />" . $table;
				return $html;
			}
		}

		// show button with the PAGE $p_sAction and the tekst $p_sActionText
		private function addButton($p_sAction, $p_sActionText) {
			// calculate url and trim all parameters [0..9]
            $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
			// create new link with PARAM for processing in new page request
			$url = $url . $p_sAction;
			$button = "<button onclick='location.href = \"$url\";'>$p_sActionText</button>";
			return $button;
		}

		private function getData(){
			// execute a query and return the result
			$sql='SELECT * FROM `tb_users` WHERE status = 0 OR status = 1 ORDER BY username';
            $result = $this->createTable(Database::getData($sql));

			//TODO: generate JSON output like this for webservices in future
			/*
				$data = Database::getData($sql);
				$json = Database::jsonParse($data);
				$array = Database::jsonParse($json);

				echo "<br />result: ";  print_r(Database::getData($sql));
	            echo "<br /><br />json :" . $json;
	            echo "<br /><br />array :"; print_r($array);
			*/

			return $result;
		} // end function getData()

		private function createTable($p_aDbResult){ // create html table from dbase result
			$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
			$table = "<table border='1'>";
				$table .= "	<th>uuid</th>
							<th>Inlognaam</th>
							<th>Wachtwoord</th>
							<th>E-mailadres</th>
							<th>Rechten</th>
							<th>Status</th>
							<th>Hash</th>
							<th>Hashdatum</th>
							<th>Timestamp</th>
							<th>Bekijk</th>
							<th>Verwijder</th>
							<th>Aanpassen</th>";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$table .= "<tr>";
						foreach ($row as $col) {
							$table .= "<td>" . $col . "</td>";
						}
	                    // calculate url and trim all parameters [0..9]
	                    $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
						// create new link with parameter (== edit user link!)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/read/" . $row["uuid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to edit icon
						//create new link with parameter (== delete user)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/delete/" . $row["uuid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to delete icon
						// create new link with parameter (== update)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/update/" . $row["uuid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to delete icon
					$table .= "</tr>";

				} // foreach
			$table .= "</table>";
			return $table;
		} //function

		// [C]rud action
		// based on sent form 'frmAddUser' fields
		private function create() {
			// use variabel field  from form for processing -->
			if(isset($_POST['frmAddUser'])) { // in this case the form is returned
				return $this->processFormAddUser();
			} // ifisset
			else {								// in this case the form is made
				return $this->addForm();
			} //else
		}

		private function addForm() { // processed in $this->processFormAddUser()
			$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
			// heredoc statement. Everything between 2 HTML labels is put into $html
			$html = <<<HTML
				<fieldset>
					<legend>Voeg een nieuwe gebruiker toe</legend>
						<form action="$url" enctype="multipart/formdata" method="post">
							<label>Inlognaam</label>
							<input type="text" name="loginname" required id="" value="" placeholder="Inlognaam" />

							<label>Wachtwoord</label>
							<input type="text" name="password" required id="" value="" placeholder="Wachtwoord" />

							<label>Rol</label>
							<input type="text" name="role" id="" required value="" placeholder="Rol" />

							<label>E-mail</label>
							<input type="text" name="email" id="" required value="" placeholder="E-mailadres" />


							<label></label>
							<!-- add hidden field for processing -->
							<input type="hidden" name="frmAddUser" value="frmAddUser" />
							<input type="submit" name="submit" value="Gebruiker toevoegen" />
						</form>
				</fieldset>
HTML;
			return $html;
		} // function

		private function processFormAddUser() {
			$hash 		= $this->createHash(); // in code
			$uuid 		= $this->createUuid(); // in code
			$hashDate 	= $this->createHashDate(); // in core
			// get transfered datafields from form "$this->addForm()"

			$username 	= $_POST['loginname'];
			$password	= password_hash($_POST['password'], PASSWORD_DEFAULT);
			$role		= $_POST['role'];
			$email		= $_POST['email'];
			$status	= 1;
			// create insert query with all info above
			$sql = "INSERT
						INTO tb_users
							(uuid, username, password, email, role, hash, hash_date, status)
								VALUES
									('$uuid', '$username', '$password', '$email', '$role', '$hash', '$hashDate', '$status')";

			Database::getData($sql);
			/*
				echo "<br />";
				echo $hash . "<br />";
				echo $uuid . "<br />";
				echo $hashDate . "<br />";
			*/
			return "Gebruiker is toegevoegd.";
		} //function

		private function tableBekijk($p_aDbResult){ // create html table from dbase result
			$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
			$tableBekijk = "<table border='1'>";
				$tableBekijk .= "<th>uuid</th>
							<th>inlognaam</th>
							<th>Wachtwoord</th>
							<th>E-mailadres</th>
							<th>Rechten</th>
							<th>Status</th>
							<th>Hash</th>
							<th>Hashdatum</th>
							<th>Timestamp</th>
							";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$tableBekijk .= "<tr>";
						foreach ($row as $col) {
							$tableBekijk .= "<td>" . $col . "</td>";
						}
				} // foreach
			$tableBekijk .= "</table>";
			return $tableBekijk;
		} //function



		// c[R]ud action
		private function read() {

			$sql = 'SELECT * FROM tb_users WHERE uuid = "' . PARAM .'"';
				$result = $this->tableBekijk(Database::getData($sql));
				$button = $this->addButton("/../../../../../..".GEBRUIKERS_PATH, "Terug");	// add "/add" button. This is ACTION button

				return "<br><h1>Dit zijn de details van vacature :". "<br/>" . PARAM."</h1>". $result. $button;

		} // function detail

		//cr[U]d action
		private function update() {
			// present form with all user information editable and process
			$button = $this->addButton("/../../../../../.." .GEBRUIKERS_PATH , "Terug");
			// first show button, then table

			return $button ."<br>Momenteel wordt " . PARAM . " aangepast";
		}

		//cru[D] action
		private function delete() {
			// remove selected record based om uuid in PARAM
			$sql='DELETE FROM tb_users WHERE uuid="' . PARAM. '"';
            $result = Database::getData($sql);
			$button = $this->addButton("/../../../../../.." . GEBRUIKERS_PATH , "Terug");	// add "/add" button. This is ACTION button
			// first show button, then table

			return $button ."<br>Vacature " . PARAM . " is verwijdert";
		}
	}// class gebruikerPage
?>
