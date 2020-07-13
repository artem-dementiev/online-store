var cart = {};
function init() {
    //вычитуем файл goods.json
    //$.getJSON("goods1.json", goodsOut);
    $.post(
        //куда посылаю запрос
        "admin/core.php",
        {
            "action":"loadGoods"
        },
        goodsOut
    );
}

function goodsOut(data) {
    // вывод на страницу
    data =JSON.parse(data);//из строки обратно в массив
    console.log(data);
    var out='';
    for (var key in data) {

        out +='<div class="cart">';
        out +=`<p class="name">${data[key].nameproduct}, ${data[key].size}</p>`;
        out +=`<div class="amount">${data[key].amount}</div>`;
        out +=`<img style="width: 50px;" src="img/${data[key].img}" alt="">`;
        out +=`<div class="cost">${data[key].price}</div>`;
        out +=`<button class="add-to-cart" data-id="${key}">Купить</button>`;
        out +='</div>';
    }
    $('.goods-out').html(out); 
    $('.add-to-cart').on('click', addToCart);
}

function addToCart(){
    var id = $(this).attr('data-id');
    if(cart[id]==undefined)
        {cart[id]=1;} //если в корзине нет товара то делаем равным 1
    else{
            cart[id]++;
        }
        showMiniCart();
        saveCart();
}
function saveCart(){
    localStorage.setItem('cart', JSON.stringify(cart)); //преобразует значение JavaScript в строку JSON
}
function showMiniCart(){
    var out='';
    for(var key in cart)
    {
        out+=key+' --- '+cart[key]+'<br>';//id & amount
    }
    $('.mini-cart').html(out);
}


function loadCart(){
    //проверка если ли запись в карт
    if(localStorage.getItem('cart')){
        cart = JSON.parse(localStorage.getItem('cart'));//разбирает строку JSON
        //console.log(cart);
        showMiniCart();
    }
}
$(document).ready(function () {
    init();
    loadCart();
});