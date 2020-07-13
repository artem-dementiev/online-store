function init() {
    $.post(
        "core.php",
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
        "core.php",
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
            "core.php",
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
            "core.php",
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
        "core.php",
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

    var sum =0, summ=0;
    var oldid; 
    for (var id in data) {
        out +=`<tr>`;//${data[id].nameproduct}
        if(oldid!=data[id].idorder){
            out += `<td>${data[id].idorder}</td>`;
            out += `<td>${data[id].username}</td>`;
            out += `<td>${data[id].dateorder}</td>`;
            out += `<td>${data[id].idstatus}</td>`;
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
        if(oldid!=data[id].iddetail){
            out += `<td></td>`;
        }else{
            out += `<td>${summ.toFixed(2)}</td>`;
            
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
        "core.php",
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
        "core.php",
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
            "core.php",
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
   init();
   initorders();
   initorderdroplist();
   initstatusdroplist();
   $('.add-to-db').on('click', saveToDb);
   $('.update-order-to-db').on('click', updateOrder);
});