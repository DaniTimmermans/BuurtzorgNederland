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
	class SollicitantPage extends Core implements iPage {

		public function getHtml() {
			if(defined('ACTION')) {			// process the action obtained is existent
				switch(ACTION) {
					// get html for the required action
					case "update"	: return $this->update();break;
				}
			} else { // no ACTION so normal page
				$table 	= $this->getData();		// get users from database in tableform
				// first show button, then table
				$html = "<br />" . $table;
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
			$sql='SELECT * FROM `tb_soll` WHERE status = 1 ORDER BY naam';
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
			if($_SESSION['user']['role'] == ROLE_PZ){
			$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
			$table = "<table border='1'>";
			$table .= "
							<th>Naam id</th>
							<th>Naam</th>
							<th>Adres</th>
							<th>Geboorte datum</th>
							<th>Mail</th>
							<th>Vacture id</th>
							<th>Status</th>
							<th>Punten</th>
							<th>Keur goed</th>";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$table .= "<tr>";
						foreach ($row as $col) {
							$table .= "<td>" . $col . "</td>";
						}
	                    // calculate url and trim all parameters [0..9]
	                    $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");

						// create new link with parameter (== update)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/update/" . $row["naamid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to delete icon
					$table .= "</tr>";

				} // foreach
			$table .= "</table>";
			return $table;
		}else {
			$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
			$table = "<table border='1'>";
			$table .= "
							<th>Naam id</th>
							<th>Naam</th>
							<th>Adres</th>
							<th>Geboorte datum</th>
							<th>Mail</th>
							<th>Vacture id</th>
							<th>Status</th>
							<th>Punten</th>";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$table .= "<tr>";
						foreach ($row as $col) {
							$table .= "<td>" . $col . "</td>";
					}
					$table .= "</table>";
					return $table;
				}	//foreach
			}	//else
		} //function

		//cr[U]d action
		private function update() {
			// present form with all user information editable and process
			$button = $this->addButton("/../", "Terug");
			// first show button, then table
			$sql = 'UPDATE tb_soll
									SET status = 2 WHERE naamid= "'. PARAM .'"';

			Database::getData($sql);

			return $button ."<br> Status: " . PARAM . " is geupdate";
		}

	}// class gebruikerPage
?>
