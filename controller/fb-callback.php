<?php
  $helper = $fb->getRedirectLoginHelper();
  try {
    $accessToken = $helper->getAccessToken();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
  
  if (! isset($accessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    exit;
  }
  
  // The OAuth 2.0 client handler helps us manage access tokens
  $oAuth2Client = $fb->getOAuth2Client();
  
  // Get the access token metadata from /debug_token
  $tokenMetadata = $oAuth2Client->debugToken($accessToken);
  
  // Validation (these will throw FacebookSDKException's when they fail)
  $tokenMetadata->validateAppId(Config::FB_APP_ID); // Replace {app-id} with your app id
  // If you know the user ID this access token belongs to, you can validate it here
  //$tokenMetadata->validateUserId('123');
  $tokenMetadata->validateExpiration();
  
  if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
      exit;
    }
  }
  
  $_SESSION['fb_access_token'] = (string) $accessToken;

  $fb->setDefaultAccessToken($accessToken);
  $response = $fb->get('/me?locale=en_US&fields=id,name,email');
  $userNode = $response->getGraphUser();
  $email = $userNode->getField('email');
  $fbUserId = $userNode->getField('id');

  $userVeridator = new UserVeridator();
  $userVeridator->isEmailDuplicate($email);
  $error = $userVeridator->getErrorArray();
  
  if(count($error) == 0)
  {
    // 沒有註冊

    $passwordObject = new Password();

    $table = 'members';
    $data_array = array(
      'username' => $fbUserId,
      'password' => $passwordObject->password_hash(date().rand(), PASSWORD_BCRYPT),
      'email' => $email,
      'active' => 'Yes',
      'fbUserId' => $fbUserId
    );
    Database::get()->insert($table, $data_array);
    
    $_SESSION['memberID'] = Database::get()->getLastId();
    $_SESSION['username'] = $fbUserId;

  }else{
    // 有註冊了
    if($userVeridator->fbLoginVerification($email, $fbUserId)){
      $_SESSION['memberID'] = $userVeridator->getMemberIdByFb($fbUserId);
      $_SESSION['username'] = $fbUserId;
    }
  }
  header('Location: '.Config::BASE_URL."upsell");
  exit;