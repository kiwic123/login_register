<?php
  //if logged in redirect to members page
  if(UserVeridator::isLogin(isset($_SESSION['username'])?$_SESSION['username']:'')){
    header('Location: home'); 
    exit();
  }
  
  //define page title
  $title = 'Forget';
  include('view/header/default.php'); // 載入共用的頁首
  include('view/body/forget.php');    // 載入忘記密碼的頁面
  include('view/footer/default.php'); // 載入共用的頁尾