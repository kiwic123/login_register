<?php
//if form has been submitted process it
if(isset($_POST['submit'])) {
  $gump = new GUMP();
  $_POST = $gump->sanitize($_POST); 
  $validation_rules_array = array(
    'email'    => 'required|valid_email'
  );
  $gump->validation_rules($validation_rules_array);
  $filter_rules_array = array(
    'email' => 'trim|sanitize_email'
  );
  $gump->filter_rules($filter_rules_array);
  $validated_data = $gump->run($_POST);
  if($validated_data === false) {
    $error = $gump->get_readable_errors(false);
  } else {
    //email validation
    foreach($validation_rules_array as $key => $val) {
      ${$key} = $_POST[$key];
    }
    $table = 'members';
    $condition = 'email = :email';
    $order_by = '1'; 
    $fields = 'email, memberID'; 
    $limit = '1';
    $data_array[':email'] = $email;
    $result = Database::get()->query($table, $condition, $order_by, $fields, $limit, $data_array);
    if(!isset($result[0]['memberID']) OR empty($result[0]['memberID'])){
      $error[] = 'Email provided is not recognised.';
    }else{
      $memberID = $result[0]['memberID'];
    }
  }
  //if no errors have been created carry on
  if(!isset($error)){
    //create the activation code
    try {
      $data_array = array();
      $data_array['resetComplete'] = 'No';
      $data_array['resetToken'] = md5(rand().time());
      $resetToken = $data_array['resetToken'];
      $key = "memberID";
      $id = $memberID;
      Database::get()->update('members', $data_array, $key, $id);
      
      //send email
      $to = $email;
      $subject = "Password Reset";
      $body = "<p>Someone requested that the password be reset.</p>
      <p>If this was a mistake, just ignore this email and nothing will happen.</p>
      <p>To reset your password, visit the following address: <a href='".Config::BASE_URL."reset/$resetToken'>".Config::BASE_URL."reset/$resetToken</a></p>";
      $mail = new Mail(Config::MAIL_USER_NAME, Config::MAIL_USER_PASSWROD);
      $mail->setFrom(Config::MAIL_FROM, Config::MAIL_FROM_NAME);
      $mail->addAddress($to);
      $mail->subject($subject);
      $mail->body($body);
      $mail->send();
      //redirect to index page
      $msg->success("Please check your inbox for a reset link.");
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