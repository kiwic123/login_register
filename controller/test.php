<?php

// Add messages
$msg->info('This is an info message');
$msg->success('This is a success message');
$msg->warning('This is a warning message');
$msg->error('This is an error message 1');
$msg->error('This is an error message 2');

// If you need to check for errors (eg: when validating a form) you can:

include('view/header/default.php'); // 載入共用的頁首
// Wherever you want to display the messages simply call:

if ($msg->hasErrors()) {
	$msg->display();
} else {
  // There are NOT any errors
}

include('view/footer/default.php'); // 載入共用的頁尾