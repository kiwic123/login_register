<?php
if(isset($_POST['submit'])) 
{
  $gump = new GUMP();

  $_POST = $gump->sanitize($_POST); 

  $validation_rules_array = array(
    'username'    => 'required|alpha_numeric|max_len,20|min_len,3',
    'email'       => 'required|valid_email',
    'password'    => 'required|max_len,20|min_len,3',
    'passwordConfirm' => 'required'
  );
  $gump->validation_rules($validation_rules_array);

  $filter_rules_array = array(
    'username' => 'trim|sanitize_string',
    'email'    => 'trim|sanitize_email',
    'password' => 'trim',
    'passwordConfirm' => 'trim'
  );
  $gump->filter_rules($filter_rules_array);

  $validated_data = $gump->run($_POST);

  if($validated_data === false) {
    $error = $gump->get_readable_errors(false);
  } else {
    // validation successful
    foreach($validation_rules_array as $key => $val) {
      ${$key} = $_POST[$key];
    }
    $userVeridator = new UserVeridator();
    $userVeridator->isPasswordMatch($password, $passwordConfirm);
    $userVeridator->isUsernameDuplicate($username);
    $userVeridator->isEmailDuplicate($email);
    $error = $userVeridator->getErrorArray();
  } 
  //if no errors have been created carry on
  if(count($error) == 0)
  {
    //hash the password
    $passwordObject = new Password();
    $hashedpassword = $passwordObject->password_hash($password, PASSWORD_BCRYPT);

    //create the random activasion code
    $activasion = md5(uniqid(rand(),true));

    try {
      // 新增到資料庫
      $table = 'members';
      $data_array = array(
        'username' => $username,
        'password' => $hashedpassword,
        'email' => $email,
        'active' => $activasion
      );
      Database::get()->insert($table, $data_array);
      $id = Database::get()->getLastId();

      if(isset($id) AND !empty($id) AND is_numeric($id)){
        // 寄出認證信
        $subject = "Registration Confirmation";
        $body = "<p>Thank you for registering at demo site.</p>
        <p>To activate your account, please click on this link: <a href='".Config::BASE_URL."activate/$id/$activasion'>".Config::BASE_URL."activate/$id/$activasion</a></p>
        <p>Regards Site Admin</p>";

        $mail = new Mail(Config::MAIL_USER_NAME, Config::MAIL_USER_PASSWROD);
        $mail->setFrom(Config::MAIL_FROM, Config::MAIL_FROM_NAME);
        $mail->addAddress($email);
        $mail->subject($subject);
        $mail->body($body);
        if($mail->send()){
        $msg->success('Registration successful, please check your email to activate your account.');
        }else{
        $msg->error('Sorry, unable to send Email.');
        }
        //redirect to index page
        header('Location: '.Config::BASE_URL.'register');
        exit;
      }else{
        $error[] = "Registration Error Occur on Database.";
      }
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