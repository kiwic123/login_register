<?php
  if(!UserVeridator::isLogin(isset($_SESSION['username'])?$_SESSION['username']:'')){
    header('Location: '.Config::BASE_URL);
    exit;
  }

  unset($_SESSION['cartQty']);
  unset($_SESSION['cartPrice']);
  unset($_SESSION['cartName']);

  include('view/header/default.php'); // 載入共用的頁首
  include('view/body/fail.php');    
  include('view/footer/default.php'); // 載入共用的頁尾