<?php
  
  unset($_SESSION['cartQty']);
  unset($_SESSION['cartPrice']);
  unset($_SESSION['cartName']);
  unset($_SESSION['order']);

  header('Location: '.Config::BASE_URL);
  exit;