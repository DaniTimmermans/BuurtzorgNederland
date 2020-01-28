<?php
//Read form
class vacaturetable{
private function tableBekijk($p_aDbResult){ // create html table from dbase result
  $image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
  $tableBekijk = "<table border='1'>";
    $tableBekijk .= "<th>Vacature id</th>
          <th>Vacature titel</th>
          <th>Vacature tekst</th>
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

  $sql = 'SELECT vac_id, vac_titel, vac_tekst FROM tb_vacature WHERE vac_id = "' . PARAM .'"';
    $result = $this->tableBekijk(Database::getData($sql));
    $button = $this->addButton("/../../../../../..".HOME_PATH, "Terug");	// add "/add" button. This is ACTION button

    return "<br><h1>Dit zijn de details van vacature :". "<br/>" . PARAM."</h1>". $result. $button;

} // function details
} // end class vacature table
