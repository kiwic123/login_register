<?php
//if form has been submitted process it
if(isset($_POST['submit']))
{
  $gump = new GUMP();
  $_POST = $gump->sanitize($_POST); 

  $validation_rules_array = array(
    'password'    => 'required|max_len,20|min_len,3',
    'resetToken' => 'required',
    'passwordConfirm' => 'required'
  );
  $gump->validation_rules($validation_rules_array);

  $filter_rules_array = array(
    'resetToken' => 'trim',
    'password' => 'trim',
    'passwordConfirm' => 'trim'
  );
  $gump->filter_rules($filter_rules_array);

  $validated_data = $gump->run($_POST);

  if($validated_data === false) {
    $error = $gump->get_readable_errors(false);
    foreach( $error as $e) {
      $msg->error($e);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  } else {
    // validation successful
    foreach($validation_rules_array as $key => $val) {
      ${$key} = $_POST[$key];
    }
    $userVeridator = new UserVeridator();
    $userVeridator->isPasswordMatch($password, $passwordConfirm);
    $error = $userVeridator->getErrorArray();
  } 
  //if no errors have been created carry on
  if(count($error) == 0)
  {
    // 確認 resetToken
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

    // 都正確就變更密碼 hash the password
    $passwordObject = new Password();
    $hashedpassword = $passwordObject->password_hash($password, PASSWORD_BCRYPT);

    try {
      $data_array = array();
      $table = 'members';
      $data_array['password'] = $hashedpassword;
      $data_array['resetComplete'] = 'Yes';
      $key = "resetToken";
      $id = $resetToken;
      Database::get()->update($table, $data_array, $key, $id);
      
      //redirect to index page
      $msg->success('Password changed, you may now login.');
      header('Location: '.Config::BASE_URL.'login');
      exit;

    //else catch the exception and show the error.
    } catch(PDOException $e) {
        $error[] = $e->getMessage();
    }
  }
  if(isset($error) AND count($error) > 0){
    foreach( $error as $e) {
        $msg->error($e);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}else{
  header('Location: ' . Config::BASE_URL);
  exit;
}