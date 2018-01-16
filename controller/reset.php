<?php
/**
 * 載入頁面之前先檢查 resetToken 參數是否有帶
 */
// 檢查是否有帶 Token 
$verify_array['resetToken'] = $route->getParameter(2);

$gump = new GUMP();
$verify_array = $gump->sanitize($verify_array); 
$validation_rules_array = array(
  'resetToken'    => 'required'
);
$gump->validation_rules($validation_rules_array);
$filter_rules_array = array(
  'resetToken' => 'trim'
);
$gump->filter_rules($filter_rules_array);
$validated_data = $gump->run($verify_array);
if($validated_data === false) {
  // 沒有帶 Token 回來，直接踢回 login
  $msg->error('Invalid token provided, please use the link provided in the reset email.');
  header('Location: '.Config::BASE_URL.'login');
  exit;
} else {
  foreach($validation_rules_array as $key => $val) {
    ${$key} = $verify_array[$key];
  }
  // 有帶 Token 回來的話，確認是否存在
  $table = 'members';
  $condition = 'resetToken = :resetToken';
  $order_by = '1';
  $fields = 'resetToken, resetComplete';
  $limit = '1';
  $data_array[':resetToken'] = $resetToken;
  $result = Database::get()->query($table, $condition, $order_by, $fields, $limit, $data_array);
  if(!isset($result[0]['resetToken']) OR empty($result[0]['resetToken'])){
    $msg->error('Invalid token provided, please use the link provided in the reset email.');
    header('Location: '.Config::BASE_URL.'login');
    exit;
  }else if(isset($result[0]['resetComplete']) AND $result[0]['resetComplete'] == 'Yes'){
    $msg->info('Your password has already been changed!');
    header('Location: '.Config::BASE_URL.'login');
    exit;
  }
}
/**
 * 檢查完畢載入頁面
 */
//define page title
$title = 'Reset';
include('view/header/default.php'); // 載入共用的頁首
include('view/body/reset.php');     // 載入重置密碼的頁面
include('view/footer/default.php'); // 載入共用的頁尾