<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "clothingstore";

function connect(){
    $conn = mysqli_connect("localhost", "root", "root", "clothingstore");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;
}
function checkuser(){
    session_start();   
        if(!isset($_SESSION['loggedIN'])){
        header('Location: ../index.php');
    }

    
}

function init(){
    //вывожу список товаров
    $conn = connect();
    $sql = "SELECT idproduct, nameproduct FROM product";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $out = array();
        while($row = mysqli_fetch_assoc($result)) {
            $out[$row["idproduct"]] = $row;
        }
        echo json_encode($out);
    } else {
        echo "0";
    }
    mysqli_close($conn);
}
function initorders(){ //тут мы берем инфу с сервака
    //вывожу список заказов
    $conn = connect();
    $sql = "SELECT p.nameproduct, p.price, 
    c.iduser, c.username, 
    d.amount, 
    o.idorder, o.dateorder, o.idstatus
    FROM product AS p 
    INNER JOIN details AS d 
    ON p.idproduct = d.iddproduct 
    INNER JOIN orderr AS o 
    ON d.iddorder = o.idorder 
    INNER JOIN user AS c 
    ON o.idclient = c.iduser 
    WHERE o.idstatus <> '2'  
    ORDER BY o.idorder";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
         $array = array ();
         while (($row = $result->fetch_assoc()) != false)
         $array[] = $row;
         echo json_encode($array);
    } else {
        echo "0";
    }
    mysqli_close($conn);
}
function initorderdroplist(){
    $conn = connect();
    //используем этот когда нам нужно знать не только айди заказа но и имя того кто заказал
    // $sql = "SELECT o.idorder, u.username
    //         FROM orderr AS o
    //         INNER JOIN user AS u ON o.idclient = u.iduser;";
    $sql = "SELECT idorder FROM orderr";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $out = array();
        while($row = mysqli_fetch_assoc($result)) {
            $out[$row["idorder"]] = $row;
        }
        echo json_encode($out);
    } else {
        echo "0";
    }
    mysqli_close($conn);
}
function initstatusdroplist(){
    $conn = connect();
    $sql = "SELECT idstatus, statusname FROM status";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $out = array();
        while($row = mysqli_fetch_assoc($result)) {
            $out[$row["idstatus"]] = $row;
        }
        echo json_encode($out);
    } else {
        echo "0";
    }
    mysqli_close($conn);
}
function selectOneGoods(){
	//вывожу список товаров
    $conn = connect();
    $id = $_POST['gid'];
    $sql = "SELECT * FROM product WHERE idproduct = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);        
        echo json_encode($row);
    } else {
        echo "0";
    }
    mysqli_close($conn);
}


function updateGoods(){
	$conn = connect();
	$id = $_POST['id'];
	$name = $_POST['gname'];
	$price = $_POST['gprice'];
	$size = $_POST['gsize'];
	$amount = $_POST['gamount'];
	$img = $_POST['gimg'];
    $category = $_POST['gcategory'];

	$sql = "UPDATE product SET nameproduct = '$name', price = '$price', size = '$size', amount = '$amount', img = '$img', idcategory = '$category'  WHERE idproduct='$id' ";

	if ($conn->query($sql) === TRUE) {
  	  echo "1";
	} else {
 	   echo "Error updating record: " . $conn->error;
	}
	mysqli_close($conn);
	//writeJSON();
}

function updateOrder(){
    $conn = connect();
    $idor = $_POST['idord'];
    $idst = $_POST['idsta'];

    $sql = "UPDATE orderr SET idstatus='$idst'  WHERE idorder='$idor' ";//adress='', dateorder='2019-06-07', idclient='1',

    if ($conn->query($sql) === TRUE) {
      echo "1";
    } else {
       echo "Error updating order: " . $conn->error;
    }
    mysqli_close($conn);
    writeJSON();
}

function newGoods(){
	$conn = connect();
	$name = $_POST['gname'];
	$price = $_POST['gprice'];
	$size = $_POST['gsize'];
	$amount = $_POST['gamount'];
	$img = $_POST['gimg'];
    $category = $_POST['gcategory'];

	$sql = "INSERT INTO product (nameproduct, price, size, amount, img, idcategory) VALUES('$name', '$price', '$size', '$amount', '$img', '$category')";

	if ($conn->query($sql) === TRUE) {
   	 	echo "1";
	} else {
    	echo "Error adding record: " . $conn->error;
	}
	mysqli_close($conn);
	//writeJSON();
}

//функция которая перезаписывает JSON файл после изменения в бд
function writeJSON(){
	$conn = connect();
    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $out = array();
        while($row = mysqli_fetch_assoc($result)) {
            $out[$row["idproduct"]] = $row;
        }
        	$a=file_put_contents('../goods1.json', json_encode($out));
        	echo $a;
    } else {
        echo "0";
    }
    mysqli_close($conn);
}

function loadGoods(){
    $conn = connect();
    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $out = array();
        while($row = mysqli_fetch_assoc($result)) {
            $out[$row["idproduct"]] = $row;
        }
            echo json_encode($out); //кодируем массив в строку
    } else {
        echo "0";
    }

    mysqli_close($conn);
}

function initprofile(){
    session_start();
    if(isset($_SESSION['loggedIN'])){

    $sessionuserid = $_SESSION['sessionuserid'];
     $conn = connect();
    $sql = "SELECT p.nameproduct, p.price, 
    d.amount, 
    o.idorder, o.dateorder, o.idstatus 
    FROM product AS p 
    INNER JOIN details AS d 
    ON p.idproduct = d.iddproduct 
    INNER JOIN orderr AS o 
    ON d.iddorder = o.idorder
    WHERE o.idclient = '$sessionuserid'  
    ORDER BY o.idorder";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
         $array = array ();
         while (($row = $result->fetch_assoc()) != false)
         $array[] = $row;
         echo json_encode($array);
    } else {
        echo "0";
    }
    mysqli_close($conn);
    }   
}
?>