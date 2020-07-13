<?php
	session_start();

	if(isset($_SESSION['loggedIN'])){
		$conn = mysqli_connect("localhost", "root", "root", "clothingstore");
		$sessionuserid = array();
		$sessionuserid = $_SESSION['sessionuserid'];
		$adress = $_POST['adressPHP'];
		$date = $_POST['datePHP'];

		$cart = $_POST['cartPHP']; //{"4":4,"6":6,"7":3}
		$cart1 = str_replace("{", "", $cart);					//убираем {
		$cart2 = str_replace("}", "", $cart1);					//убираем }
		//print($cart2);										//"4":4,"6":6,"7":3
		// print("   ".$adress);
		// print("   ".$date);
		// print("   ".$sessionuserid);

		$sql = "INSERT INTO orderr (adress,dateorder, idclient, idstatus ) VALUES('$adress', '$date', '$sessionuserid[0]', 1 )";

		if ($conn->query($sql) === TRUE) {
			$last_id = $conn->insert_id;
			// print("Last order id is:".$last_id);
			$i = 0;														//index детали
			$tok = strtok($cart2, ",");
			// print("first tok is".$tok);						//"4":4
				 while ($tok !== false) {
				 	list($idpr, $amou) = explode(":", $tok);			//убираем :
					//print("Айди еще в кавычках".$idpr);										//"4"

					 list($q1, $q2,$q3) = explode('"', $idpr);
				 	 //print(" IdProductt: ".$q2);										//4
				 	 // print(" Amount: ".$amou);										//4

				 		$sql1 = "INSERT INTO details (iddorder, iddproduct, amount) VALUES('$last_id', '$q2', '$amou')";
				 		$result = mysqli_query($conn, $sql1) or die("Bad Query: $sql1");
						// if ($conn->query($sql1) === TRUE) {
						// 		exit("Detail #".$i" sended success");
						// }else{
						//  		exit("Detail #".$i"Error adding record: " . $conn->error);
						// }

				 	$tok = strtok(",");
				 	// print(.$i" tok is".$tok);
					// print("Index i =".$i);
				 	$i++; 
				 }
   	 		exit('<div class="successmsg">Order sended success</div>');
		} else {
    		exit('<div class="errormsg">Error adding record: </div>'. $conn->error);
		}

		mysqli_close($conn);
	}else{
		exit('<div class="errormsg">To order goods you must log in <a target="_blank" href="login.php">here</a>.</div>');
	}
		
?>
