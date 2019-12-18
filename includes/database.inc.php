<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Pagebuilder framework database
*/
	// database implemented abstract because only one implementation required
	abstract class Database {

		private static function dbConnect() {
            $dbhost		= "localhost";
            $dbname		= "pagebuilder24";
            $dbuser		= "db_pagebuilder";
            $dbpass		= "db_pass_pagebuilder";
            $conn		= "";                        // connection string
            $pdo;                                    // handler
            $charset = 'utf8mb4';

            $conn = "mysql:host=" . $dbhost . "; dbname=" . $dbname . ";charset=". $charset;

			$options = [ // define options for PDO connection
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,	// give error in case of issue
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   	// get row indexed by column name
				PDO::ATTR_EMULATE_PREPARES   => false,
			];
			try {
				$pdo = new PDO($conn, $dbuser, $dbpass, $options); // create connection
                return $pdo;
			} // try
			catch (\PDOException $e) {
				throw new \PDOException($e->getMessage(), (int)$e->getCode());
			} // catch

		} //

		public static function getData($p_sSql, $p_aData = "") {
			// execute query on Mysql server
			// $p_sSQL contains MySql query string with parameter ?'s
			// $paData contains array with query parameters
            $pdo = Database::dbConnect();
			$stmt = $pdo->prepare($p_sSql);	// prepare the query
			if(is_array($p_aData)) {		// add the data
				$stmt->execute($p_aData);	// execute the query
			} else {
				$stmt->execute();			// execute when no parameters
			}
			
			$result = $stmt->fetchAll(); // get result

			return $result; // dabase query result
		}

		public static function jsonParse($p_sValue) {
			if(is_array($p_sValue)) {
				return json_encode($p_sValue);
			}
			if(is_string($p_sValue)) {
				return json_decode($p_sValue);
			}
		}

	}
