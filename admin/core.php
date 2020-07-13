<?php
$action = $_POST['action'];

require_once 'adminquery.php';

switch ($action) {
    case 'init':
        init();
        break;
    case 'initorders':
        initorders();
        break;
    case 'initorderdroplist':
        initorderdroplist();
        break;
    case 'initstatusdroplist':
        initstatusdroplist();
        break;
    case 'initprofile':
        initprofile();
        break;
   case 'selectOneGoods':
       selectOneGoods();
       break;
   case 'updateGoods':
       updateGoods();
       break;
   case 'updateOrder':
       updateOrder();
       break;
   case 'newGoods':
       newGoods();
       break;
   case 'loadGoods':
       loadGoods();
       break;
}

