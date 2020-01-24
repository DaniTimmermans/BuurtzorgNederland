<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Contains details of page information
	returns the built html
	Class Name convention: <pagename>Page
	Must contain iPage interface implementation ie getHtml()
	Called by content.inc.php
*/
class VacaturePage extends Core implements iPage{
	public function getHtml() {
		if(defined('ACTION')) {			// process the action obtained is existent
			switch(ACTION) {
				// get html for the required action
				case "create"	: return $this->create(); break;
				case "read"		: return $this->read(); break;
				case "update"	: return $this->update();break;
				case "delete"	: return $this->delete();

//uitnodiging knop voor goedgekeurde sollicitant.
				case "updateSoll"	: return $this->updateSoll();break;
			}
		} elseif($_SESSION['user']['role'] == ROLE_WTV) { // no ACTION so normal page
			$table 	= $this->getData();		// get users from database in tableform
			$table2 = $this->getDataSoll();
			$button = $this->addButton("/create", "Toevoegen");	// add "/add" button. This is ACTION button
			// first show button, then table
			$html = "<h1> Welkom " . $_SESSION['user']['username'] . " </h1>" . "<br/>" . "<br/>" . "<h1>Vacatures</h1>" .  $button . "<br/>" . $table . "<br/>" . "<h1> Goedgekeurde Sollicitanten </h1>" . $table2;
			return $html;
		} else {
			$table 	= $this->getData();		// get users from database in tableform
			$html = "<h1> Welkom " . $_SESSION['user']['username'] . " </h1>" . "<br/>" . $table;
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
		$sql='SELECT * FROM tb_vacature ORDER BY CAST(vac_id AS int)';
					$result = $this->createTable(Database::getData($sql));

		return $result;
	} // end function getData()

	private function createTable($p_aDbResult){ // create html table from dbase result
		if ($_SESSION['user']['role'] == ROLE_WTV) {
		$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
		$table = "<table border='1'>";
			$table .= "	<th>Vacature id</th>
									<th>Vacature titel</th>
									<th>Vacature tekst</th>
									<th>Wijkteamverantwoordige id</th>
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
							. "/read/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to edit icon
					//create new link with parameter (== delete user)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/delete/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
					// create new link with parameter (== update)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/update/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
				$table .= "</tr>";

			} // foreach
		$table .= "</table>";
		return $table;
	}else {
		$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
		$table = "<table border='1'>";
			$table .= "	<th>Vacature id</th>
						<th>Vacature titel</th>
						<th>Vacature tekst</th>
						<th>Wijkteamverantwoordige id</th>
						<th>Bekijk</th>";
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
							. "/read/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to edit icon
				$table .= "</tr>";

			} // foreach
		$table .= "</table>";
		return $table;
	}
	} //function

	private function getDataSoll(){
		// execute a query and return the result
		$sql='SELECT naamid, naam, adres, gebdatum, mail, punten FROM tb_soll WHERE status = 4 ORDER BY punten DESC';
					$result = $this->createTableSoll(Database::getData($sql));

		return $result;
	} // end function getData()

// hier worden alle goedgekeurde sollicitanten weergegeven die door de teamleden zijn goedgekeurd.
	private function createTableSoll($p_aDbResult){ // create html table from dbase result
		$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
		$table2 = "<table border='1'>"; //$table2 laat de door de teamleden gekeurde sollicitanten zien.
		$table2 .= "<th>Naam ID</th>
						<th>Naam</th>
						<th>Adres</th>
						<th>Geboorte Datum</th>
						<th>E-mail</th>
						<th>Punten</th>
						<th>Stuur uitnodiging</th>";
			// now process every row in the $dbResult array and convert into table
			foreach ($p_aDbResult as $row){
				$table2 .= "<tr>";
					foreach ($row as $col) {
						$table2 .= "<td>" . $col . "</td>";
					}
					// calculate url and trim all parameters [0..9]
										$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
					// create new link with parameter (== edit user link!)
					$table2 	.= "<td><a href="
							. $url 							// current menu
							. "/updateSoll/" . $row["naamid"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to edit icon
				$table2 .= "</tr>";

			} // foreach
		$table2 .= "</table>";
		return $table2;
	} //function

	// [C]rud action
	// based on sent form 'frmAddVac' fields
	private function create() {
		// use variabel field  from form for processing -->
		if(isset($_POST['frmAddVac'])) { // in this case the form is returned
			return $this->processFormVac();
		} // ifisset
		else {								// in this case the form is made
			return $this->addForm();
		} //else
	}


// Hier wordt het sollicitatie formulier aangeroepen.

	private function addForm() { // processed in $this->processFormAddUser()
		$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
		// heredoc statement. Everything between 2 HTML labels is put into $html
		$html = <<<HTML
			<fieldset>
				<legend>Voeg een nieuwe vacature toe</legend>
					<form action="$url" enctype="multipart/formdata" method="post">

						<label>Vacature titel</label>
						<input type="text" name="vacatureTitel" required id="" value="" placeholder="Vacature titel" />

						<label>Vacature tekst</label>
						<textarea rows="15" cols="75" name="vacatureTekst" required id="" value="" placeholder="Vacature tekst"></textarea>

						<label>Wijkteamverantwoordige ID</label>
						<input type="text" name="wijkteamverantwoordigeId" required id="" value="" placeholder="Wijkteamverantwoordige id" />

						<label></label>
						<!-- add hidden field for processing -->
						<input type="hidden" name="frmAddVac" value="frmAddVac" />
						<input type="submit" name="submit" value="Vacature toevoegen" />
					</form>
			</fieldset>
HTML;
		return $html;
	} // function

	private function processFormVac() {
		$vacatureId 								= $this->createUuid(); // in code
		$vacatureTitel							= $_POST['vacatureTitel'];
		$vacatureTekst							= $_POST['vacatureTekst'];
		$wijkteamverantwoordigeId		= $_POST['wijkteamverantwoordigeId'];
		// create insert query with all info above
		$sql = "INSERT
					INTO tb_vacature
						(vac_id, vac_titel, vac_tekst, wtv_id)
							VALUES
								('$vacatureId', '$vacatureTitel', '$vacatureTekst', '$wijkteamverantwoordigeId')";

		Database::getData($sql);

		$button = $this->addButton("/../../", "Terug");

		return $button . "<br>De vacature is toegevoegd.";
	} //function

	// c[R]ud action
	private function read() {
		// get and present information from the user with uuid in PARAM
		$button = $this->addButton("/../../../", "Terug");
		// first show button, then table

		return $button ."<br>Dit zijn de details van " . PARAM;
	} // function details

	//cr[U]d action
	private function update() {
		// present form with all user information editable and process
		$button = $this->addButton("/../../../", "Terug");
		// first show button, then table

		return $button ."<br>" .  "Vacature " . PARAM . " wordt momenteel aangepast";
	}

	//cru[D] action
	private function delete() {
		// remove selected record based om uuid in PARAM
		$sql='DELETE FROM tb_vacature WHERE vac_id="' . PARAM . '"';
					$result = Database::getData($sql);
		$button = $this->addButton("/../../../", "Terug");	// add "/add" button. This is ACTION button
		// first show button, then table

		return $button ."<br>Vacature " . PARAM . " is verwijderd";
	}

	private function updateSoll() {
		// present form with all user information editable and process
		$sql = 'UPDATE tb_soll SET status = 5 WHERE naamid = "' . PARAM .'"';
		Database::getData($sql);
		$button = $this->addButton("/../../../", "Terug");
		// first show button, then table

		return $button ."<br>" .  "Sollicitant:" . PARAM . "Uitnodiging is verstuurd.";
	}
}// class vacaturePage
