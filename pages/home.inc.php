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
class HomePage extends Core implements iPage {
	public function getHtml() {
		if(defined('ACTION')) {			// process the action obtained is existent
			switch(ACTION) {
				// get html for the required action
				case "create"	: return $this->create(); break;
				case "read"		: return $this->read(); break;
			}
		} else { // no ACTION so normal page
			$table 	= $this->getData();		// get users from database in tableform
			$button = $this->addButton("/create", "Toevoegen");	// add "/add" button. This is ACTION button
			// first show button, then table
			$html = "<h1>Beschikbare vacatures</h1>" . "<br>" . $table;
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
		$sql='SELECT vac_id, vac_titel, vac_tekst FROM tb_vacature ORDER BY CAST(vac_id AS int)';
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
			$table .= "<th>Vacature id</th>
						<th>Vacature titel</th>
						<th>Vacature tekst</th>
						<th>Vacature bekijken</th>
						<th>Solliciteer op deze vacature</th>";
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
					// create new link with parameter (== update)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/create/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
				$table .= "</tr>";

			} // foreach
		$table .= "</table>";
		return $table;
	} //function

	private function create() {
		// use variabel field  from form for processing -->
		if(isset($_POST['frmAddSoll'])) { // in this case the form is returned
			return $this->processFormSoll();
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
				<legend>Solliciteer op deze vacature</legend>
					<form action="$url" enctype="multipart/formdata" method="post">
						<label>Naam</label>
						<input type="text" name="naam" required id="" value="" placeholder="Naam" />

						<label>Adres</label>
						<input type="text" name="adres" required id="" value="" placeholder="Adres" />

						<label>Geboortedatum</label>
						<input type="date" name="gebdatum" required id="" value="" placeholder="Geboortedatum" />

						<label>E-mail</label>
						<input type="text" name="mail" required id="" value="" placeholder="E-mail" />

						<label></label>
						<input name="vac_id" type="hidden" id="" value="" placeholder="vac_id" />

						<label></label>
						<!-- add hidden field for processing -->
						<input type="hidden" name="frmAddSoll" value="frmAddSoll" />
						<input type="submit" name="submit" value="Verstuur sollicitatie" />
					</form>
			</fieldset>
HTML;
		return $html;
	} // function

	private function processFormSoll() {
		$vac_id											= $_POST['vac_id'];
		$naamid											= $this->createUuid(); // in code
		$naam 											= $_POST['naam'];
		$adres											= $_POST['adres'];
		$gebdatum										= $_POST['gebdatum'];
		$mail												= $_POST['mail'];
		$status 										=	"1";
		$punten											=	"0";
		// create insert query with all info above
		$sql = "INSERT
					INTO tb_soll
						(vac_id, naamid, naam, adres, gebdatum, mail, status, punten)
							VALUES
								('$vac_id', '$naamid', '$naam', '$adres', '$gebdatum', '$mail', '$status', $punten)";

		Database::getData($sql);
		/*
			echo "<br />";
			echo $hash . "<br />";
			echo $uuid . "<br />";
			echo $hashDate . "<br />";
		*/

		return "<br>De sollicitatie is verstuurd.";
	} //function

	// c[R]ud action
	private function read() {
		return "<br>Dit zijn de details van vacature " . PARAM;
	} // function details
}// class homePage
