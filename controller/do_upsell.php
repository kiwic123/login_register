<?php
  if(!UserVeridator::isLogin(isset($_SESSION['memberID'])?$_SESSION['memberID']:'')){
    header('Location: '.Config::BASE_URL);
    exit;
  }

  $cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
  $cart->addToCartIfNotExist(2, "折扣圖庫", 150, 1);

  $_SESSION['cartQty'] = $cart->getCartQty();
  $_SESSION['cartPrice'] = $cart->getCartPrice();
  $_SESSION['cartName'] = $cart->getCartName();

  header('Location: '.Config::BASE_URL."do_checkout");
  exit;