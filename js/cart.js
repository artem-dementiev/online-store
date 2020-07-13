var cart = {};
function loadCart(){
    //проверка если ли запись в карт
    if(localStorage.getItem('cart')){
        cart = JSON.parse(localStorage.getItem('cart'));
       
        showCart();
    }
    else{
    	$('.main-cart').html('Корзина пуста');
    }
}

function showCart(){
	 if(!isEmpty(cart)){
        	$('.main-cart').html('Корзина пуста');
        }else{
		$.getJSON('goods1.json', function(data){
		var goods = data;
		var out ='';
		var totalPrice=0;
		for(var id in cart){
			out+=`<button data-id="${id}" class="del-goods">x</button>	`;
		out +=`<img style="width: 50px;" src="img/${data[id].img}">`;
		out+=`	${goods[id].name	}`;
			out+=`<button data-id="${id}" class="minus-goods">-</button>	`;
			console.log("here");
		out+=`	${cart[id]	}`;
			out+=`<button data-id="${id}" class="plus-goods">+</button>	`;
			out+=cart[id]*goods[id].price;
        out+=`<br>`;
        totalPrice+=cart[id]*goods[id].price;
		}
		out+=`<div class="totalPrice">${totalPrice}</div>`;
		$('.main-cart').html(out);
   		$('.del-goods').on('click', delGoods);
   		$('.plus-goods').on('click', plusGoods);
   		$('.minus-goods').on('click', minusGoods);
	});}
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
    localStorage.setItem('cart', JSON.stringify(cart));
}
function isEmpty(object){
	for(var key in object){
		if(object.hasOwnProperty(key)){return true;} else {return false;}
	}
	
}


$(document).ready(function(){
	loadCart();
	$('.send-order').on('click', sendOrder)//отправить заказ
});