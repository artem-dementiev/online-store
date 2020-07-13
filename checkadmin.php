<?php
switch ($action) {
	    case 'checkadmin':
        checkadmin();
        break;
}
function checkadmin(){
session_start(); 
        if(isset($_SESSION['loggedIN'])){
            
        $sessionuserid = $_SESSION['sessionuserid'];
        //вывожу список товаров
        $conn = connect();
         $sql = "SELECT iduser FROM user where useridrole <>2";
         $result = mysqli_query($conn, $sql);
         
        if (mysqli_num_rows($result) > 0) {
             $array = array ();$i=0;
             while (($row = $result->fetch_assoc()) != false){
             $array[] = $row;
             if($array[$i]!=$sessionuserid){echo "here";
                header('Location: index.php');
            }else{
                header('Location: admin.php');//ok
                echo "1";
            }
            $i++;
         }
        } else {
            header('Location: index.php');echo "0";
        }
        mysqli_close($conn);
    }
}
        function connect(){
    $conn = mysqli_connect("localhost", "root", "root", "clothingstore");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;}

?>