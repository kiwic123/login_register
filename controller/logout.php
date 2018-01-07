<?php
/**
 * 清除登入資料
 */
unset($_SESSION['memberID']);
unset($_SESSION['username']);
/**
 * 導向登入頁
 */
$msg->success('Logout Successful.');
header('Location: '.Config::BASE_URL.'login');