<?php

  require("../../config.php");

	// see fail peab olema siis seotud kõigiga kus
	// tahame sessiooni kasutada
	// saab kasutada nüüd $_SESSION muutujat
	session_start();

	$database = "if16_stenly_4";
	// functions.php

	function signup($email, $password) {

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUE (?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ss", $email, $password);

		if ( $stmt->execute() ) {
			echo "õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}

	}

	function login($email, $password) {

		$notice = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("
			SELECT id, email, password, created
			FROM user_sample
			WHERE email = ?
		");

		echo $mysqli->error;

		//asendan küsimärgi
		$stmt->bind_param("s", $email);

		//rea kohta tulba väärtus
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);

		$stmt->execute();

		//ainult SELECT'i puhul
		if($stmt->fetch()) {
			// oli olemas, rida käes
			//kasutaja sisestas sisselogimiseks
			$hash = hash("sha512", $password);

			if ($hash == $passwordFromDb) {
				echo "Kasutaja $id logis sisse";

				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;

				header("Location: data.php");
        exit();

			} else {
				$notice = "parool vale";
			}


		} else {

			//ei olnud ühtegi rida
			$notice = "Sellise emailiga ".$email." kasutajat ei ole olemas";
		}

		return $notice;


	}


	function saveEvent($color, $age) {

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO whistle (color, age) VALUE (?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ss", $color, $age);

		if ( $stmt->execute() ) {
			echo "Õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}

	}

  function getAllpeople(){

  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

  $stmt = $mysqli->prepare("SELECT id, color, age FROM whistle");
  $stmt->bind_result($id, $color, $age);
  $stmt->execute();

  $results = array();

  //tsukli sisu tehakse nii mitu korda, mitu rida SQL-lausega tuleb(kuni tingimus taidetud)
  while($stmt->fetch()) {

    $human = new StdClass();
    $human->id = $id;
    $human->lightcolor = $color;
    $human->age = $age;
    //echo $color."<br>";

    array_push($results, $human);
  }

  return $results;
  function cleanInput ($input) {

    //input = " romil ";
    $input = trim($input);

    //input = "romil";
    $input = stripslashes

    //html asendab, nt "<" saab "&lt;"
    $input = htmlspecialchars($input);

    return $input;
  }

  }


	/*function sum($x, $y) {

		return $x + $y;

	}

	echo sum(12312312,12312355553);
	echo "<br>";


	function hello($firstname, $lastname) {
		return
		"Tere tulemast "
		.$firstname
		." "
		.$lastname
		."!";
	}

	echo hello("romil", "robtsenkov");
	*/
?>
