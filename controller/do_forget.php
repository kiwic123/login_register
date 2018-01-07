<?php
//if form has been submitted process it
if(isset($_POST['submit']) AND isset($_POST['email'])) 
{
  $email = $_POST['email'];
  $postVeridator = new PostVeridator();
  $userVeridator = new UserVeridator();
  $userAction = new UserAction();
  $log = new Log();
  if($postVeridator->isValidEmail($email)) { // 信箱是否合法
    if($userVeridator->isEmailDuplicate($email)) { // 信箱是否存在
      try {
        $resetToken = $userAction->getResetToken(); // 創建 Token 並存到資料庫
        $userAction->sendResetEmail($resetToken); // 用 Token 組出重置信件並寄出
        $userAction->redir2login(); // 重導向登入頁並顯示成功
      } catch(PDOException $e) {
        $error[] = $e->getMessage();
        $log->error(__FILE__, json_encode($error));
      }
    }else{ // 不存在就假裝成功, 避免被試出會員信箱
      $log->warning(__FILE__, 'WRONG EMAIL: ' .$email);
      sleep(rand(1,2));
      $userAction->redir2login(); // 重導向登入頁並顯示成功
      exit;
    }
  } else { // 不合法就顯示踢回上一頁顯示錯誤
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}else{ // 非正常進入就踢回首頁
  header('Location: ' . Config::BASE_URL);
  exit;
}