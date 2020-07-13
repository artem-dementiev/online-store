<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Cart</title>
	<link rel="stylesheet" href="css/style.css">
	<style>
		.cartlist{
            border-spacing: 0;
            font-family: 'Open Sans', sans-serif;
            display: block;
        }
        .cartlist th {
            padding: 10px 20px;
            background: #56433D;
            color: #F9C941;
            font-size: 0.9em;
}

        .cartlist td {
            vertical-align: middle;
            padding: 10px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #56433D;
}
        .pagecartname{
            font-size: 28px;
            text-align: center;
            padding: 10px 0;
        }
        #cartloginmessage{
            display: absolute;
        }
        .cartcenter{
            position: absolute;
            left: 12%;
        }
        .errormsg{
    background:red;
    margin:10px 0;
}
.successmsg{
	margin:10px 0;
    background:green;
}
	</style>
</head>
<body>
	<?php require_once "header.php" ?>
<main>
	<div class="pagecartname">Корзина</div>
	<div class="cartcenter">
			<div class="main-cart"></div>
	
			<div id="cartloginmessage"></div>
		<p>Enter adress <input type="text" id="adress" placeholder="Adress"></p>
		<p><button class="send-order">Order</button></p>

	</div>

</main>
<footer></footer>

	<script src="js/jquery-3.2.1.min.js"></script>
<script>
	
	var cart = {};
function loadCart(){
    //проверка если ли запись в карт
    if(localStorage.getItem('cart')){
        cart = JSON.parse(localStorage.getItem('cart'));
       	showCart();
    	console.log("init");
    }
    else{
    	$('.main-cart').html('Корзина пуста');
    }
}

function showCart(){
	console.log("showCart");
	 if(!isEmpty(cart)){
        	$('.main-cart').html('Корзина пуста');
        }else{
        	$.post(
	        //куда посылаю запрос
	        "admin/core.php",
	        {
	            "action":"loadGoods"
	        },function(data){
		        var goods =JSON.parse(data);//из строки обратно в массив
				console.log(cart);
				console.log("--------------");
				console.log(goods);
				var out =`<table class="cartlist"><tr>
				<td>Удалить</td>
				<td>Товар</td>
				<td>Название</td>
				<td>-1</td>
				<td>Количество</td>
				<td>+1</td>
				<td>Цена за единицу товара</td>
				<td>Цена</td>
				<td>Итого</td></tr>`;
				var totalPrice=0;
				var i=0;
				var length = Object.keys(cart).length;
				for(var id in cart){
					out+=`<tr><td><button data-id="${id}" class="del-goods">x</button></td>	`;
				if(goods[id].img!=undefined){
					out +=`<td><img style="width: 50px;" src="img/${goods[id].img}"></td>`;
				}else{
					out +=`<td><img style="width: 50px;" src="img/undefined.png"></td>`;
				}
				out+=`<td>${goods[id].nameproduct}</td>`;
				out+=`<td><button data-id="${id}" class="minus-goods">-</button></td>`;
				
				out+=`<td>${cart[id]}</td>`;
					out+=`<td><button data-id="${id}" class="plus-goods">+</button></td>`;
					out += `<td>${goods[id].price}</td>`;
					sum=cart[id]*goods[id].price;
					out += `<td>${sum}</td>`;
		        
		        totalPrice+=cart[id]*goods[id].price;
		        i++;
		        if(i===length){out+=`<td style="border-top: 0px solid #56433D;"><div class="totalPrice">${totalPrice.toFixed(2)}</div></td></tr>`;}else{
		        	out+=`<td style="border-bottom: 0px solid #56433D;border-top: 0px solid #56433D;"></td></tr>`;
		        }
				}
				
				$('.main-cart').html(out);
		   		$('.del-goods').on('click', delGoods);
		   		$('.plus-goods').on('click', plusGoods);
		   		$('.minus-goods').on('click', minusGoods);	
	        }
	        
	    );
	       	
	 }
}

function delGoods(){
	var id=$(this).attr('data-id');
	delete cart[id];
	saveCart();
	showCart();
}

function plusGoods(){
	var id=$(this).attr('data-id');
	cart[id]++;
	saveCart();
	showCart();
}
function minusGoods(){
	var id=$(this).attr('data-id');
	if(cart[id]==1){
		delete cart[id];
	}else{
		cart[id]--;
	}
	
	saveCart();
	showCart();
}


function saveCart(){
    //localStorage.setItem('cart', cart);//
    localStorage.setItem('cart', JSON.stringify(cart));
}
function isEmpty(object){
	for(var key in object){
		if(object.hasOwnProperty(key)){return true;} else {return false;}
	}
}
function sendOrder(){
		var adress = $('#adress').val();
		if(adress==""){$("#cartloginmessage").html('<div class="errormsg">Введите адрес!</div>');return;}
		var d = new Date();
		console.log(d);
		var curr_month_str,curr_date_str;

	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	if(curr_month <10){
		curr_month_str="0"+curr_month;
	}else{
		curr_month_str=curr_month;
	}
	if(curr_date < 10){
		curr_date_str="0"+curr_date;
	}else{
		curr_date_str=curr_date;
	}
	var formated_date = curr_year + "-" + curr_month_str + "-" + curr_date_str;
	//console.log(formated_date);

	
	var cart = localStorage.getItem('cart');
	console.log(cart);
		if(isEmpty(cart)){	
			$.post(
				"cartquery.php",{
					   "adressPHP": adress,
					   "datePHP": formated_date,
					   "cartPHP": cart

				}, function(response){
								
								$("#cartloginmessage").html(response);
								// if(response.indexOf('success') >= 0){
								// 	window.location.assign("hidden.php");
								// }
							}

				);
				

		}else{
			alert("Cart is empty");
		}
}

$(document).ready(function(){
	loadCart();
	$('.send-order').on('click', sendOrder)//отправить заказ
});
</script>
</body>
</html>