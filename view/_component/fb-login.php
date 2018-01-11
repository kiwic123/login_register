<?php
$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl(Config::FB_CALLBACK_URL, array('email'));
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';