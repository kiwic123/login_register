<?php
$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl(Config::FB_CALLBACK_URL, array('email'));
echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="images/common/fb-login.png" alt="Facebook帳號登入" class="img-fluid" ></a>';