<?php
date_default_timezone_set('Asia/Taipei');

if(isset($_POST))$_POST = GUMP::xss_clean($_POST);

$route = new Router(Request::uri()); //搭配 .htaccess 排除資料夾名稱後解析 URL

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

