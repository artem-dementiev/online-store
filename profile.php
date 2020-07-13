
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>profile</title>
    <style>
        .profileorderlist{
            border-spacing: 0;
            font-family: 'Open Sans', sans-serif;
            font-weight: bold;
}
        .profileorderlist th {
            padding: 10px 20px;
            background: #56433D;
            color: #F9C941;
            font-size: 0.9em;
}

        .profileorderlist td {
            vertical-align: middle;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #56433D;
}
    </style>
</head>
<body>
	<?php require_once "header.php" ?>
	<h2>Личный кабинет</h2>
	<div>Список ваших заказов:</div>
	<div class="profile-orders-out"></div>
	

<script src="js/jquery-3.2.1.min.js"></script>
	<script>
	function initprofile() {
    $.post(
        "admin/core.php",
        {
            "action" : "initprofile"
        },function(data){
        	showProfile(data);	
        }
        
    );
}
function showProfile(data){
	data = JSON.parse(data);
    console.log(data);
    var out=`<table class="profileorderlist"><tr>
    <td>Номер замовлення</td>
    <td>Дата</td>
    <td>Статус замовлення</td>
    <td>Назва товару</td>
    <td>Кількість</td>
    <td>Цена товара</td>
    <td>Загальна вартість замовлення</td></tr>`;

    var sum =0, summ=0;
    var oldid; 
    for (var id in data) {
        out +=`<tr>`;
        if(oldid!=data[id].idorder){
            out += `<td>${data[id].idorder}</td>`;
            out += `<td>${data[id].dateorder}</td>`;
            out += `<td>${data[id].idstatus}</td>`;
            summ=0;
        }else{
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
    $('.profile-orders-out').html(out);
}
$(document).ready(function () {
   initprofile();
});
</script>
</body>
</html>