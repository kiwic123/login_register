<?php

  if(!UserVeridator::isLogin(isset($_SESSION['memberID'])?$_SESSION['memberID']:'')){
    header('Location: '.Config::BASE_URL);
    exit;
  }

  $order = unserialize(base64_decode($_SESSION['order']));

  if($order->status == 1){
    $cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
    $productKeys = $cart->getAllProductID();

    include('view/header/default.php'); // 載入共用的頁首
    include('view/body/thankyou.php');    
    include('view/footer/default.php'); // 載入共用的頁尾
  }else{
    
    unset($_SESSION['cartQty']);
    unset($_SESSION['cartPrice']);
    unset($_SESSION['cartName']);
    unset($_SESSION['order']);
    header('Location: '.Config::BASE_URL);
    exit;
  }

  