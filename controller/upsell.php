<?php
  if(!UserVeridator::isLogin(isset($_SESSION['username'])?$_SESSION['username']:'')){
    header('Location: '.Config::BASE_URL);
    exit;
  }

  // 登入成功，將產品加入加入購物車
  $cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
  $cart->removeProductFromCart(2);
  $cart->addToCartIfNotExist(1, "免費圖庫", 0, 1);

  $_SESSION['cartQty'] = $cart->getCartQty();
  $_SESSION['cartPrice'] = $cart->getCartPrice();
  $_SESSION['cartName'] = $cart->getCartName();

  include('view/header/default.php'); // 載入共用的頁首
  include('view/body/upsell.php');    
  include('view/footer/default.php'); // 載入共用的頁尾