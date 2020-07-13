<?php
    session_start();   
        if(!isset($_SESSION['loggedIN'])){
        header('Location: index.php');}

switch ($action) {
    case 'checkadmin':
        checkadmin();
        break;}
function checkadmin(){
    session_start();
        if(isset($_SESSION['loggedIN'])){
            
         $sessionuseridrole = $_SESSION['sessionuseridrole'];print("::".$useridrole);
         if($sessionuserid==1){
            header('Location: admin.php');
        }else{
            header('Location: index.php');//ok
        }
        // $conn = connect();
        //  $sql = "SELECT idrole FROM role where namerole = 'admin'";
        //  $result = mysqli_query($conn, $sql);
         
        // if (mysqli_num_rows($result) > 0) {

        //      while (($row = $result->fetch_assoc()) != false){
        //         print($row);print("::".$useridrole);
        //      if($row==$sessionuseridrole){
        //         header('Location: admin.php');
        //     }else{
        //         header('Location: index.php');//ok
        //         echo "1";
        //     }
        //  }
        // } else {
        //     echo "0";
        // }
        // mysqli_close($conn);
    }}
        function connect(){
    $conn = mysqli_connect("localhost", "root", "root", "clothingstore");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;}
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>adminPage</title>
	<style>
		.goods-out{
			margin:10px 0;
		}
		.goods, .orders, .statuses{
			width:75%;
		}
		h2, .orderlist td, .goods-out, .adminform, .changestatus{
			text-align: center; text-align: center;
		}
		h2{
			color: #fff;
		}

		.orderlist{
			border-spacing: 0;
			font-family: 'Open Sans', sans-serif;
			font-weight: bold;
}
		.orderlist th {
			padding: 10px 20px;
			background: #56433D;
			color: #F9C941;
			font-size: 0.9em;
}

		.orderlist td {
			vertical-align: middle;
			padding: 10px;
			font-size: 14px;
			border: 1px solid #56433D;
}
		.adminform, h2, .changestatus{
			border:2px solid #0C0C0C;
			border-radius: 5px;
			background:#418141;
			padding: 10px 20px;
		}
		.adminform input{
			width: 20%;
		}
		.orders-out{
			margin:50px 20px;
		}
		.changestatus{

		}

	</style>
</head>
<body>
	<?php require_once "header.php" ?>
	<h2>Добавить/изменить товар</h2>
	<div class="goods-out"></div>
	<h2>Товар</h2>
	<form action="" class="adminform">
		<p>Name: <input type="text" id="gname"></p>
		<p>Price: <input type="text" id="gprice"></p>
		<p>Size: <input type="text" id="gsize"></p>
		<p>Amount: <input type="text" id="gamount"></p>
		<p>Image: <input type="text" id="gimg"></p>
		<p>Category: <input type="text" id="gcategory"></p>
		<input type="hidden" id="gid">
		<button class="add-to-db">Обновить/Добавить товар</button>
	</form>
	<div class="orders-out"></div>
	<h2><p>Choose order to change status</p></h2>
	<div class="changestatus">
			<div class="order-out"></div>
			<div class="status-out"></div>
			<input type="hidden" id="gidorder">
			<input type="hidden" id="gidstatus">
			<button class="update-order-to-db">Обновить заказ</button>
	</div>
<script src="js/jquery-3.2.1.min.js"></script>
<!-- <script src="js/admin.js"></script> -->
<script>
    function checkuser() {
    $.post(
        "admin.php",
        {
            "action" : "checkadmin"
        },
            console.log()
        
    );
}
	function init() {
    $.post(
        "admin/core.php",
        {
            "action" : "init"
        },
        showGoods
    );
}

function showGoods(data) {
    data = JSON.parse(data);
    console.log(data);
    var out='<select class="goods">';
    out +='<option data-id="0">Новый товар</option>';
    for (var id in data) {
        out +=`<option data-id="${id}">${data[id].nameproduct}</option>`;
    }
    out +='</select>';
    $('.goods-out').html(out);
    $('.goods-out select').on('change', selectGoods);
}

function selectGoods(){
    var id = $('.goods-out select option:selected').attr('data-id');
    //посылаем запрос на сервер для чтения товара
    $.post(
        "admin/core.php",
        {
            "action":"selectOneGoods",
            "gid": id
        },
        function(data){
            data = JSON.parse(data);
            //заносим в элементы значения полученые из сервера
            $('#gname').val(data.nameproduct);
            $('#gprice').val(data.price);
            $('#gsize').val(data.size);
            $('#gamount').val(data.amount);
            $('#gimg').val(data.img);
            $('#gid').val(data.idproduct);
            $('#gcategory').val(data.idcategory);

        }
    );
}
function saveToDb(){
    var id = $('#gid').val();
    if(id != ''){
        $.post(
            "admin/core.php",
            {
                "action" : "updateGoods",
                "id":id,
                "gname": $('#gname').val(),
                "gprice": $('#gprice').val(),
                "gsize": $('#gsize').val(),
                "gamount": $('#gamount').val(),
                "gimg": $('#gimg').val(),
                "gcategory": $('#gcategory').val()
            },
            function(data){
                if(data==1){
                        console.log("Обновлено");//отобразить на экране
                        init();
                    }else{
                        console.log(data);
                    }  
            }
        );
    }else{
        $.post(
            "admin/core.php",
            {
                "action" : "newGoods",
                "id":0,
                "gname": $('#gname').val(),
                "gprice": $('#gprice').val(),
                "gsize": $('#gsize').val(),
                "gamount": $('#gamount').val(),
                "gimg": $('#gimg').val(),
                "gcategory": $('#gcategory').val()
            },
            function(data){
                if(data==1){
                        console.log("Создано");//отобразить на экране
                        init();
                    }else{
                        console.log(data);
                    }
            }
        );
    }
}

function initorders() {
    $.post(
        "admin/core.php",
        {
            "action" : "initorders"
        },
        showOrders
    );
}
function showOrders(data) { //тут полученные данные выводим
    data = JSON.parse(data);
    console.log(data);
    var out=`<table class="orderlist"><tr>
    <td>Номер замовлення</td>
    <td>Ім'я клієнта</td>
    <td>Дата</td>
    <td>Статус замовлення</td>
    <td>Назва товару</td>
    <td>Кількість</td>
    <td>Цена товара</td>
    <td>Загальна вартість замовлення</td></tr>`;

        console.log("--------");

    //         var a = [], b = [], prev;
    // for ( var i = 0; i < data.length; i++ ) {
    //     if ( data[i] !== prev ) {
    //         a.push(data[i]);
    //         b.push(1);
    //     } else {
    //         b[b.length-1]++;
    //     }
    //     prev = data[i];
    // }

    // console.log(a);
    // console.log("---------");
    // console.log(b);
    var oldid =0, summ=0; 
    //for (var id in data) {
        for (var id=0; id<data.length;id++) {
        out +=`<tr>`;//${data[id].nameproduct}
        if(oldid!=data[id].idorder){
            out += `<td>${data[id].idorder}</td>`;
            out += `<td>${data[id].username}</td>`;
            out += `<td>${data[id].dateorder}</td>`;
            out += `<td>${data[id].idstatus}</td>`;
            summ=0;
        }else{
            out += `<td></td>`;
            out += `<td></td>`;
            out += `<td></td>`;
            out += `<td></td>`;
        }
        out += `<td>${data[id].nameproduct}</td>`;
        out += `<td>${data[id].amount}</td>`;
        sum = data[id].amount*data[id].price;

        out += `<td>${sum}</td>`;
        summ+=sum;
        if(oldid==data[id].idorder){
            out += `<td>${summ.toFixed(2)}</td>`;

        }else{
            out += `<td></td>`;
        }
        out += `</tr>`;
        oldid=data[id].idorder;
        
    }

    out +='</table>';
    $('.orders-out').html(out);
    // $('.goods-out select').on('change', selectGoods);
}
function initorderdroplist(){
    $.post(
        "admin/core.php",
        {
            "action" : "initorderdroplist"
        },
        showdroporderlist
    );
}

function showdroporderlist(data) {
    data = JSON.parse(data);
    console.log(data);
    var out='<select class="orders">';
    for (var id in data) {
        //out +=`<option data-id="${id}">${data[id].username}</option>`;
        out +=`<option data-id="${id}">${data[id].idorder}</option>`;
    }
    out +='</select>';
    $('.order-out').html(out);
}

function initstatusdroplist(){
    $.post(
        "admin/core.php",
        {
            "action" : "initstatusdroplist"
        },
        showdropstatuslist
    );
}
function showdropstatuslist(data) {
    data = JSON.parse(data);
    console.log(data);
    var out='<select class="statuses">';
    for (var id in data) {
        out +=`<option data-id="${id}">${data[id].statusname}</option>`;
    }
    out +='</select>';
    $('.status-out').html(out);
}

function updateOrder(){
    var idord = $('.order-out select option:selected').attr('data-id');
    var idsta = $('.status-out select option:selected').attr('data-id');
    console.log(idord);
    console.log(idsta);
    $.post(
            "admin/core.php",
            {
                "action" : "updateOrder",
                "idord":idord,
                "idsta":idsta
            },
            function(data){
                if(data==1){
                        console.log("Заказ обновлен");//отобразить на экране
                        initorders();
                    }else{
                        console.log("Статус заказа не обновлен"+data);
                    }  
            }
        );
}

$(document).ready(function () {
   checkuser();
   init();
   initorders();
   initorderdroplist();
   initstatusdroplist();
   $('.add-to-db').on('click', saveToDb);
   $('.update-order-to-db').on('click', updateOrder);
});
</script>
</body>
</html>