<?php
session_start();
require('vendor/autoload.php'); // 載入 composer
require('init.php');    // 初始化，實作必須物件
require('route.php');   // 路由: 決定要去哪一頁，讀取該頁面需要的資料組合介面